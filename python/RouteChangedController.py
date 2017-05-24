#!/usr/bin/env python

import sys
import logging
import geopy
import LatLon
import time
import MySQLdb
import math
from datetime import datetime
from threading import Thread

#logging.basicConfig(level=logging.DEBUG,format='[%(levelname)s] (%(threadName)-10s) %(message)s', )
logging.basicConfig(filename='records.log', level=logging.INFO)
#Mientras que la ruta este activa

#db = MySQLdb.connect(host = "localhost", user = "biintux", passwd = "My9y7h!5", db = "admin_biintux")

#cursor = db.cursor(MySQLdb.cursors.DictCursor)

sqlQuery = ""

alternRoute = []

nodes = []


class RouteChangedController(Thread):

	global db
	global cursor
	global sqlQuery
	global alternRoute
	global nodes


	def __init__(self,id_Bus, id_route, group=None, target=None, name=None, args=(), kwargs=None, verbose=None):
		
		'''Constructor'''
	    Thread.__init__(self, group=group, target=target, name=name, verbose=verbose)        	
		self.daemon = True
        self.cancelled = False
		self.id_Bus = id_Bus
		self.id_route = id_route
#		self.id_user = id_user
		self.args = args # [id_Bus,id_route]
       	self.kwargs = kwargs

	def dbObject(self,query):

		db = MySQLdb.connect(host = "localhost", user = "biintux", passwd = "My9y7h!5", db = "admin_biintux")

		cursor = db.cursor(MySQLdb.cursors.DictCursor)
		
		cursor.execute(query)
		
		rows = cursor.fetchall()
		
		cursor.close()
		
		db.close()

		return rows

	
	def getNodes(self):
		
		sqlQuery = "SELECT nd.`latitude`, nd.`longitude`"\
				" FROM admin_biintux.`nodes` nd inner join rel_route rr"\
				" WHERE rr.start_node_id = nd.id AND rr.route_id = " + str(self.id_route)			

		rows = self.dbObject(sqlQuery)

		tmp = []

		for row in rows:

			tmp.append([row['latitude'],row['longitude']])

#		print("Ruta obtenida...")

		return tmp        	

	def keep_going(self):

		sqlQuery = "SELECT `enabled` FROM admin_biintux.`route_car` WHERE `id` = " +  str(self.id_Bus)

		rows =  self.dbObject(sqlQuery)

		for row in rows:

			if (row['enabled'] != 1):
				return False
			else:
				return True

	def run(self):

		logging.info("Bus "+ str(self.id_Bus) +"\'s backtrace starting at "+ str(datetime.now()))		
		
		nodes = self.getNodes()
		
		while (self.keep_going() == True): 

			currentLatLng = self.getLatLng(self.id_Bus)

			if(self.proximity(currentLatLng,nodes) == True):
							
				if(len(alternRoute) == 0):
				
					alternRoute.append(currentLatLng)				
				else:
					alternRoute.pop()
					alternRoute.append(currentLatLng)

			#if(self.proximity(self.getLatLng(self.id_Bus),nodes) == False):			
			else:
#				print("No en ruta...")

				flagGenerateDeviation = False

				alternRoute.append(currentLatLng)
				
				#cambiar 3 a 5 para evitar errores 3=prueba 5=estable
				for i in range(0,2):
					
					time.sleep(2)

					var = self.getLatLng(self.id_Bus)					
					
					if(self.proximity(var,nodes) == False):
						
#						print ("Todavia fuera...")

						if(alternRoute[-1][0] != var[0] and alternRoute[-1][1] != var[1]):						
						  	
							print (str(alternRoute[-1][0]) +  " - " + str(alternRoute[-1][1]) +  " --- " +  str(var[0]) +  " - " +  str(var[1]))
								
							alternRoute.append(var)
					else:

#						print ("Regreso a ruta...")

						del alternRoute[:] # Removemos la ruta temporal

						break # Rompemos ciclo en caso de que la ruta se estabilice

					flagGenerateDeviation = True # En caso de que la ruta NO se estabilizara
				
				if (flagGenerateDeviation == True): # Si se genero desviacion
					
#					print("Desviacion creada...")
				
					timeToHold = 1.0 # Seconds

					currentLatLng = self.getLatLng(self.id_Bus)					
	
					while(self.proximity(currentLatLng,nodes) == False):

						if(alternRoute[-1][0] != currentLatLng[0] and alternRoute[-1][1] != currentLatLng[1]):						

							alternRoute.append(currentLatLng)
						
						time.sleep(timeToHold) # Wait
						
						currentLatLng = self.getLatLng(self.id_Bus)
					
					alternRoute.append(currentLatLng)					
	
					print (len(alternRoute))

					self.saveInDatabase(alternRoute)

					del alternRoute[:]

#					print ("Borrada...")
			time.sleep(10)			

		logging.info("Bus "+ str(self.id_Bus) +"\'s backtrace suspended at "+ str(datetime.now()))

	def getLatLng(self,id_Bus):

		try:			
			sqlQuery = "SELECT dl.`latitude`, dl.`longitude` FROM admin_biintux.`device_location` dl"\
					" INNER JOIN admin_biintux.`devices` de INNER JOIN admin_biintux.`driver` dr"\
					" WHERE dl.`device_id` = de.`id` AND de.`user_id` = dr.`user_id`"\
					" AND dr.`route_car_id` = " + str(id_Bus)  + " ORDER BY dl.id DESC LIMIT 1" 

			row = self.dbObject(sqlQuery)		

			Lat = 0
			Lng = 0			

			for r in row:
				Lat = r['latitude']
				Lng = r['longitude']
		
#				print ("lat: " + str(Lat) + ", Lng: " + str(Lng))	
		
			tmp = [Lat, Lng]			
		
			return tmp

		except Exception:

			print("An Exception has ocurred...")	
			
			cursor.close()

			db.close()

			pass

	def saveInDatabase(self, alternRoute):
		
		db = MySQLdb.connect(host = "localhost", user = "biintux", passwd = "My9y7h!5", db = "admin_biintux")

		cursor = db.cursor(MySQLdb.cursors.DictCursor)
		
		sqlQuery="INSERT INTO deviation (route_id) values ("+str(self.id_route)+")"

		cursor.execute(sqlQuery)

		lastID = cursor.lastrowid

#		print("New id: "+str(lastID))		

		for node in alternRoute:

			query = "INSERT INTO deviation_nodes (deviation_id,latitude,longitude)"\
					" values ("+ str(lastID)+","+str(node[0])+","+str(node[1])+")"
			
			cursor.execute(query)
		
#		print("Nodos guardados...")

		db.commit()

		cursor.close()

		db.close()	

		
	def proximity(self, point, poly):
	
		size = len(poly)

	#	print (size)	

		if(size == 0):
			#print ("False")
			return False
		else:
	    		tolerance = 4 / 6371009.0
        		havTolerance = self.hav(tolerance)
        		lat3 = math.radians(point[0])
        		lng3 = math.radians(point[1])
        		prev = poly[0]
        		lat1 = math.radians(prev[0])
        		lng1 = math.radians(prev[1])
        		maxAcceptable = 0.0
        		y1 = 0.0
		
	    		var60 = lat3 - tolerance;
	    		maxAcceptable = lat3 + tolerance;
	    		y1 = self.mercator(lat1);
	    		y3 = self.mercator(lat3);
	    		xTry = [];
		
	    		y2 = 0.0
		
	    		for x in poly:
		
	    			point21 =  x
	    			lat2 = math.radians(point21[0])
	    			y2 = self.mercator(lat2)
	    			lng2 = math.radians(point21[1])
	    			Pie = math.pi
		
	    			if(max(lat1, lat2) >= var60 and min(lat1, lat2) <= maxAcceptable):
		
	    				x2 = self.wrap(lng2 - lng1, -Pie, Pie)
                			x3Base = self.wrap(lng3 - lng1, -Pie, Pie)
                			xTry.append(x3Base)
                			xTry.append(x3Base + 6.283185307179586)
                			xTry.append(x3Base - 6.283185307179586)
                			var41 = xTry
                			var42 = len(xTry)
			
                			for i in range (0, var42):
        					x3 = var41[i]
                    				dy = y2 - y1
                    				len2 = x2 * x2 + dy * dy
						t = 0.0 if len2 <= 0.0 else self.clamp((x3 * x2 + (y3 - y1) * dy) / len2, 0.0, 1.0)
                    				#t = len2 <= 0.0 ? 0.0 : clamp((x3 * x2 + (y3 - y1) * dy) / len2, 0.0, 1.0)
                    				xClosest = t * x2
                    				yClosest = y1 + t * dy
                    				latClosest = self.inverseMercator(yClosest)
                    				havDist = self.havDistance(lat3, latClosest, x3 - xClosest)
				
                    				if(havDist < havTolerance):
	#						print("True")
                    					return True
				
                    				lat1 = lat2
                    				lng1 = lng2
			
         		y1 = y2
	
	    	return False

	def clamp(self, x , low, high):
		
		return low if x < low else (high if x > high else x)
		#return x < low ? low : (x > high ? high : x) 

	def hav(self, x):
	
	    	sinHalf = math.sin(x * 0.5)
    		return sinHalf * sinHalf
	
	def mercator(self, lat):
	
    		return math.log(math.tan(lat * 0.5 + 0.7853981633974483))
	
	def wrap(self, n, min, max):

		return  n if n >= min and n < max else self.mod(n - min, max - min) + min
    		#return n >= min and n < max ? n : mod(n - min, max - min) + min
	
	def mod(self, x,m):
	
    		return (x % m + m) % m
	
	def inverseMercator(self, y):
    	
    		return 2.0 * math.atan(math.exp(y)) - 1.5707963267948966;
	
	def havDistance(self, lat1, lat2, dLng):
	
    		return self.hav(lat1 - lat2) + self.hav(dLng) * math.cos(lat1) * math.cos(lat2);
	
	def findNearestPoint(self, test, target):
	
    		distance = -1
	
    		minimumDistancePoint = test
	
    		if(test == None or target == None):
		
        		return minimumDistancePoint
		
    		for i in range (0, len(target)):
        		
        		point = target[i]
		
        		segmentPoint = i + 1
		
        		if (segmentPoint >= len(target)):
		
 	           		segmentPoint = 0
			
        		currentDistance = self.distanceToLine(test, point, target[segmentPoint])
		
        		if (distance == -1 or currentDistance < distance):
		
            			distance = currentDistance
		
            			minimumDistancePoint = self.distanceToLine(test, point, target[segmentPoint])
		
    		return minimumDistancePoint
			

	def distanceToLine(self, p, start, end):

		if (start == end): # beware

        		return start

    		s0lat = math.radians(p[0])
    		s0lng = math.radians(p[1])
    		s1lat = math.radians(start[0])
    		s1lng = math.radians(start[1])
    		s2lat = math.radians(end[0])
    		s2lng = math.radians(end[1])
		
    		s2s1lat = s2lat - s1lat
    		s2s1lng = s2lng - s1lng
		
    		u = ((s0lat - s1lat) * s2s1lat + (s0lng - s1lng) * s2s1lng)  / (s2s1lat * s2s1lat + s2s1lng * s2s1lng)
    		
   		if (u <= 0):

        		return start
    
    		if (u >= 1):

        		return end

    		return [start[0] + (u * (end[0] - start[0])), start[1] + (u * (end[1] - start[1]))]


if __name__ == "__main__":

	logging.info("Parametros de bd: "+sys.argv[1]+","+sys.argv[2])

#	r = RouteChangedController(1, 1, 3)
#	r.start()

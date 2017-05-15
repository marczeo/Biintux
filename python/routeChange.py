from threading import Thread

#Mientras que la ruta este activa

class RouteChangedController(Thread):
	def __init__(self, val):
        	''' Constructor. '''
        	Thread.__init__(self)
		self.val = val
 	
 
	def run(self):
		while True: 
			# Cambiar por consulta a la base de datos para saber si la ruta esta activa
			if(!proximity(getLatLng(id_Bus),getNodes(route))):
				#Obtenemos la posicion del camion y los nodos de la ruta, y si el nodo se aleja entonces:
				for i in range(0,5):
					# Rango de toletancia				
					if(!proximity(getLatLng(id_Bus), getNodes(route))):
						# Si el camion aun no regresa a la ruta
					else:
						del '''lista''' # Removemos la ruta temporal
						break # Rompemos en caso de que

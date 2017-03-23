/**
* @fileoverview Manipulating Google Maps API to create "bikeways"
*
* @author Marco Gómez
* @version 0.1
*/
var polis=[];
var map;
var infowindow;
function initialize() {
  var myLatLng = new google.maps.LatLng( 20.659699, -103.349609);
  infowindow = new google.maps.InfoWindow();
  var mapOptions = {
    zoom: 13,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };

  var bermudaTriangle;

  map = new google.maps.Map(document.getElementById('map'),
    mapOptions);


  /*RUTAS*/
    $.ajax(
  {
    url : '/api/getAllRoute',
    type: "GET",
    success:function(data) 
    {
      $parseData=JSON.parse(data);
      $.each($parseData.data, function(i, item) {
        var instring = google.maps.geometry.encoding.decodePath(item.encodepath);
        var routeCoordinates = Array();
        var points = instring;

        for (i = 0; i < points.length; i++) {
          var p = new google.maps.LatLng(points[i][0], points[i][1]);
          routeCoordinates.push(p);
        }
        var lineSymbol = {
          path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
          scale: 2.2,
          strokeColor: "#FFF",
          strokeOpacity: 1
        };

        var routePath = new google.maps.Polyline({
          path: points,
          interpolate: true,
          icons: [{
            icon: lineSymbol,
            offset: '50%',
            repeat: '240px'
          }],
          strokeColor: item.color,
          strokeOpacity: 0.7,
          strokeWeight: 8
        });
        google.maps.event.addListener(routePath, 'mouseover', function(event) {
          infowindow.open(map);
          infowindow.setContent(item.code);
          infowindow.setPosition(event.latLng);
        });
        google.maps.event.addListener(routePath, 'mouseout', function() {
            infowindow.close();
        });
        routePath.setMap(map);
        polis.push(routePath);

      });

    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      console.log("No se pudieron cargar los datos");
    }
  });
    /*CICLOVIAS*/
  $.ajax(
  {
    url : '/api/getAllCiclovia',
    type: "GET",
    success:function(data) 
    {
      $parseData=JSON.parse(data);
      //console.log($parseData);
      $.each($parseData.data, function(i, item) {
        var instring = google.maps.geometry.encoding.decodePath(item.encodepath);
        var routeCoordinates = Array();
        var points = instring;

        for (i = 0; i < points.length; i++) {
          var p = new google.maps.LatLng(points[i][0], points[i][1]);
          routeCoordinates.push(p);
        }
        var lineSymbol = {
          path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
          scale: 2.2,
          strokeColor: "#FFF",
          strokeOpacity: 1
        };

        var routePath = new google.maps.Polyline({
          path: points,
          interpolate: true,
          icons: [{
            icon: lineSymbol,
            offset: '50%',
            repeat: '240px'
          }],
          strokeColor: item.color,
          strokeOpacity: 0.7,
          strokeWeight: 8
        });


        google.maps.event.addListener(routePath, 'mouseover', function(event) {
          infowindow.open(map);
          infowindow.setContent(item.name);
          infowindow.setPosition(event.latLng);
        });
        google.maps.event.addListener(routePath, 'mouseout', function() {
            infowindow.close();
        });
        routePath.setMap(map);
        polis.push(routePath);

      });

    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      console.log("No se pudieron cargar los datos");
    }
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
//# sourceMappingURL=map_index.js.map

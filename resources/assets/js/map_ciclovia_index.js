/**
* @fileoverview Manipulating Google Maps API to create "bikeways"
*
* @author Marco Gómez
* @version 0.1
*/

function initialize() {
  var myLatLng = new google.maps.LatLng( 20.659699, -103.349609);
  var mapOptions = {
    zoom: 13,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };

  var bermudaTriangle;

  var map = new google.maps.Map(document.getElementById('map'),
    mapOptions);


  

  $.ajax(
  {
    url : '/getAllCiclovia',
    type: "GET",
    success:function(data) 
    {
      data.forEach(function(item){
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
          icons: [{
            icon: lineSymbol,
            offset: '50%',
            repeat: '240px'
          }],
          strokeColor: item.color,
          strokeOpacity: 0.7,
          strokeWeight: 8
        });
        routePath.setMap(map);

      });

    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
            //if fails      
          }
        });
}
google.maps.event.addDomListener(window, 'load', initialize);
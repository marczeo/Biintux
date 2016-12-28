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
        url : '/getAll',
        type: "GET",
        success:function(data) 
        {
          console.log(data);
          data.forEach(function(item){
            console.log(item.encodepath);
            var bermudaTrianglex = new google.maps.Polygon({
              paths: google.maps.geometry.encoding.decodePath(item.encodepath),
              strokeColor: '#FF0000',
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: '#FF0000',
              fillOpacity: 0.35
             });
            bermudaTrianglex.setMap(map);
          });

        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
var lat;
var lng;
var marker;
var map;

function initialize() 
{
  var myLatLng = new google.maps.LatLng( 20.659699, -103.349609);

  var mapOptions = 
  {

    zoom: 13,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    styles: [
      {
        "featureType": "poi",
        stylers: [
          { hue: "#3097D1" },
          { saturation: 60 },
          { lightness: -20 },
          { gamma: 1.51 }
        ]
      }
    ]
    
  };

  var bermudaTriangle;

  map = new google.maps.Map(document.getElementById('map'),
    mapOptions);

  $.ajax(
    {
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

  geocoder = new google.maps.Geocoder();

  //Instantiate an info window to hold step text.
  stepDisplay = new google.maps.InfoWindow();

  lat = 20.659699;
  lng = -103.349609;


  marker = new google.maps.Marker
  ({
    position: myLatLng,
    icon: '/images/mibici.svg',
    label: "",
    map: map,
    draggable: true,
    title: "Marcador",
    myData: "markerFrom",
    animation: google.maps.Animation.DROP
  });
            

  marker.addListener('dragend', moveMarkerEvent);
    google.maps.event.addListener(marker, "dblclick", function (event) 
    {
      getAddress(this.position, this.title);

      map.setCenter(this.getPosition());
      map.setZoom(14);

    });

  getAddress(marker.position, 'markerFrom');

  //getAddress(marker.position, marker.title);

}

/////Aqui
function getAddress(latLng, idInput)
{
  geocoder.geocode({
    latLng: latLng
  }, function(responses) 
  {
    if (responses && responses.length > 0) 
    {      
      document.getElementById(idInput+'Lat').value = latLng.lat();
      document.getElementById(idInput+'Lang').value = latLng.lng();
      document.getElementById(idInput+'Address').value = responses[0].formatted_address;

    } else {
      document.getElementById(idInput).value = 'Cannot determine address at this location.';
    }
  });
}

function moveMarkerEvent(event) 
{
  var idMarker=this.myData;  

  document.getElementById(idMarker+'Lat').value = event.latLng.lat();
  document.getElementById(idMarker+'Lang').value = event.latLng.lng();
  document.getElementById(idMarker+'Address').value = event.formatted_address;

  getAddress(event.latLng, idMarker+'Address');

  origin=
  {
    lat: parseFloat(document.getElementById('markerFromLat').value),
    lng: parseFloat(document.getElementById('markerFromLang').value)
  }; 
}

google.maps.event.addDomListener(window, 'load', initialize);

//# sourceMappingURL=mibici-create.js.map

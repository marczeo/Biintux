var map;
var geocoder;
var stepDisplay;

function initialize() 
{
  var myLatLng = new google.maps.LatLng( 20.67, -103.349609);

  var mapOptions = 
  {

    zoom: 13,
    center: myLatLng,
    disableDoubleClickZoom: true,
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    styles: [
      {
        "featureType": "poi",
        "stylers": [
          { "visibility": "off" }
        ]
      }
    ]
    
  };

  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  geocoder = new google.maps.Geocoder();
  stepDisplay = new google.maps.InfoWindow();

    $.ajax(
    {
        url : '/getAll',
        type: "GET",
        success:function(data) 
        {
          console.log(data);
          data.forEach(function(item)
          {
              var myLatLng = new google.maps.LatLng(item.latitude,item.longitude);

              var marker = new google.maps.Marker
              ({
                position: myLatLng,
                icon: '/images/mibici.png',
                label: "",
                map: map,
                draggable: false,
                title: item.description,
                myData: "markerFrom",
                animation: google.maps.Animation.DROP
              });

              marker.addListener('dblclick', clickMarkerEvent);
          }
          );
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    });

}

function getAddress(mylatLng)
{
  geocoder.geocode(
  {
    latLng: mylatLng
  }, function(responses)
   {
    if (responses && responses.length > 0) 
    {      
      return responses[0].formatted_address;
    } 
    else 
    {
      return 'Cannot determine address at this location.';
    }
  });
}

function clickMarkerEvent(event) 
{
  var idMarker=this.myData;  // this ---> MARKER

  map.setZoom(18);
  map.setCenter(this.position);

  alert(getAddress(this.getPosition()));
  
}

google.maps.event.addDomListener(window, 'load', initialize);

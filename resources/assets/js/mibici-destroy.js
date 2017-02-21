var map;
var geocoder;
var stepDisplay;

var idMarker; // TO DELETE

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
        stylers: [
          { hue: "#FF9E52" },
          { saturation: 60 },
          { lightness: -20 },
          { gamma: 1.51 }
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
                icon: '/images/mibici.svg',
                label: "",
                map: map,
                draggable: false,
                title: item.description,
                myData: item.id,
                //animation: google.maps.Animation.DROP,
                
              });

              google.maps.event.addListener(marker, "dblclick", function (event) 
              {
                var latitude = this.position.lat();
                var longitude = this.position.lng();

                getAddress(this.position, this.title);

                map.setCenter(this.getPosition());
                map.setZoom(16);

                idMarker = this.myData;
                document.getElementById('id').value = idMarker;

                //alert(idMarker);

              });
          }
          );
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    });
}

function getAddress(latLng, title)
{
  //alert(latLng);
  geocoder.geocode
  ({
    latLng: latLng
  }, 
  function(responses) 
  {
    if (responses && responses.length > 0)
    {      
      document.getElementById('markerFromAddress').value = responses[0].formatted_address;
      document.getElementById('name').value = title;
    }
    else
    {
      document.getElementById('markerFromAddress').value = 'Cannot determine address at this location.';
    }
  });
}


google.maps.event.addDomListener(window, 'load', initialize);


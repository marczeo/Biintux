var map;
var geocoder;
var stepDisplay;

var idMarker; // TO DELETE

var stations = [];

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
          { hue: "#A5AEFA" },
          { saturation: 40 },
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

              google.maps.event.addListener(marker, "click", function (event) 
              {
                getAddress(this.position, this.title);

                map.setCenter(this.getPosition());
                map.setZoom(16);

              });

              google.maps.event.addListener(marker, "dblclick", function (event) 
              {
                getAddress(this.position, this.title);

                map.setCenter(this.getPosition());
                map.setZoom(16);

                if(!this.getDraggable())
                {
                  // HAS QUE SE GUARDEN EN UN ARREGLO
                  // HAS QUE EL EVENTO TEXTCHANGED CAMBIE EL ARREGLO EN BASE AL ID DEL MARCADOR ACTUAL
                  // HAS QUE LA LATITUD Y LONGITUD CAMBIEN CON EL POSITION CHANGED
                  if(confirm('Editar?'))
                  {
                    // becomes Draggable
                    this.setDraggable(true);
                    // changes icon
                    this.setIcon('/images/mibici-update.svg');

                    Array.prototype.push.apply(stations, [this.position,this.myData]);

                    for (var i = stations.length - 1; i >= 0; i--) 
                    {
                      alert(stations[i].length);
                      //alert(stations[i]);
                    }

                    google.maps.event.addListener(marker, "dragend", function (event) 
                    {
                      getAddress(this.position, this.title);
                    });

                    idMarker = this.myData;
                    document.getElementById('id').value = idMarker;

                  }
                }

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


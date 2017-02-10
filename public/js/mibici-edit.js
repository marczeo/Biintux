var map;
var geocoder;
var stepDisplay;

var idMarker; // TO DELETE

var list;

function initialize() 
{
  var myLatLng = new google.maps.LatLng( 20.67, -103.349609);

  list = [];

  //list.push({position: 0, nombre: "", id: 0});

  //alert(list[0].id);

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
          { hue: "#E6CCFF" },
          { saturation: -40 },
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

                    list.push({position: this.position, description: this.title, id: this.myData});

                    google.maps.event.addListener(marker, "dragend", function (event) 
                    {
                      getAddress(this.position, this.title);

                      for(int i = 0; i < list.length - 1 ; i++)
                      {
                        if(list[i].id == this.myData)
                        {
                          alert(list[i].description);
                          list[i].description =  document.getElementById('name').value = title;

                          break;
                        }
                      }

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


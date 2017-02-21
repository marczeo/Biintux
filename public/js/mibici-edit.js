var map;
var geocoder;
var stepDisplay;

var idMarker; // TO DELETE

//var list;
var active;
var refList;

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
          active = false;

          console.log(data);
          data.forEach(function(item)
          {
            console.log(item);
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
                document.getElementById('name').value = this.title;
                getAddress(this.position, this.title, this.myData);

                map.setCenter(this.getPosition());
                map.setZoom(16);

              });

              google.maps.event.addListener(marker, "dblclick", function (event) 
              { 
                document.getElementById('name').value = this.title;
                getAddress(this.position, this.title, this.myData);

                map.setCenter(this.getPosition());
                map.setZoom(16);

                if(!this.getDraggable())
                {
                  if(active == false)
                  if(confirm('Editar?'))
                  {
                    active = true;
                    // becomes Draggable
                    this.setDraggable(true);
                    // changes icon
                    this.setIcon('/images/mibici-update.svg');

                    google.maps.event.addListener(marker, "dragend", function (event) 
                    {
                      getAddress(this.position, this.title, this.myData);
                    });

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

function getAddress(latLng, title, myData)
{

  idMarker = myData;
  document.getElementById('id').value = idMarker;

  geocoder.geocode
  ({
    latLng: latLng
  }, 
  function(responses) 
  {
    if (responses && responses.length > 0)
    {      

          document.getElementById('markerFromAddress').value = responses[0].formatted_address;  
          document.getElementById('markerFromLat').value = latLng.lat();
          document.getElementById('markerFromLang').value = latLng.lng();
    }
    else
    {
      document.getElementById('markerFromAddress').value = 'Cannot determine address at this location.';
    }
  });
}


google.maps.event.addDomListener(window, 'load', initialize);


//# sourceMappingURL=mibici-edit.js.map

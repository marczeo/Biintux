var map;

function initialize() 
{
  var myLatLng = new google.maps.LatLng( 20.67, -103.349609);

  var mapOptions = 
  {

    zoom: 13,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.TERRAIN
    
  };

  var bermudaTriangle;

  map = new google.maps.Map(document.getElementById('map'), mapOptions);

  $.ajax(
    {

        //url : '/getAll',
        type: "GET",
        success:function(data) 
        {
          console.log(data);
          data.forEach(function(item)
          {
            console.log(item.encodepath);
            var bermudaTrianglex = new google.maps.Polygon(
            {
              paths: google.maps.geometry.encoding.decodePath(item.encodepath),
              strokeColor: '#FF0000',
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: '#FF0000',
              fillOpacity: 0.35
            }
             );
            bermudaTrianglex.setMap(map);
          }
          );



        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    });

    $.ajax(
    {

        url : '/getAll',
        type: "GET",
        success:function(data) 
        {
          console.log(data);
          data.forEach(function(item)
          {
              var myLatLng = new google.maps.LatLng( item.latitude, item.longitude);

              var marker = new google.maps.Marker
              ({
                position: myLatLng,
                icon: '/images/mibici.png',
                label: "",
                map: map,
                draggable: false,
                title: item.description,
                myData: "markerFrom",
                //animation: google.maps.Animation.DROP
              });
          }
          );
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    });

    geocoder = new google.maps.Geocoder();

  //Instantiate an info window to hold step text.
    stepDisplay = new google.maps.InfoWindow();
}

function setMarker(item)
{
  var myLatLng = new google.maps.LatLng( item.latitude, item.longitude);

  var marker = new google.maps.Marker
  ({
    position: myLatLng,
    icon: '/images/mibici.png',
    label: "",
    map: map,
    draggable: true,
    title: item.description,
    myData: "markerFrom",
    animation: google.maps.Animation.DROP
  });

}

google.maps.event.addDomListener(window, 'load', initialize);

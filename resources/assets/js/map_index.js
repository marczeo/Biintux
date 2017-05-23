/**
* @fileoverview Manipulating Google Maps API to create "bikeways"
*
* @author Marco Gómez
* @version 0.1
*/
var rutasTemporales=[];
var cicloviasTemporales=[];
var estacionesMibiciTemporales=[];
var map;
var infowindow;
var infoWindow_currentPosition;
var geocoder;
var markerPosition;
var circle;
function initialize() {
  var myLatLng = new google.maps.LatLng( 20.659699, -103.349609);
  infowindow = new google.maps.InfoWindow();
  //New instantiate Geocoder
  geocoder = new google.maps.Geocoder();
  infoWindow_currentPosition = new google.maps.InfoWindow({map: map});
  var mapOptions = {
    zoom: 13,
    center: myLatLng,
  };
  map = new google.maps.Map(document.getElementById('map'),
    mapOptions);
  circle = new google.maps.Circle({
    map: map,
    fillColor: '#AA0000'
  });

  markerPosition=new google.maps.Marker({
    map: map,
    draggable: true,
    title: 'Su ubicaciónn!',
    myData: 'originNear'
  });
  markerPosition.addListener('dragend', moveMarkerEvent);
  /*RUTAS*/
    /*$.ajax(
  {
    url : '/api/getAllRoute',
    type: "GET",
    success:function(data) 
    {
      $parseData=JSON.parse(data);
      $.each($parseData.data, function(i, item) {
        
        for (var i = 0; i < item.paths.length; i++) {
          
        
          var points = google.maps.geometry.encoding.decodePath(item.paths[i].encodepath);

          var lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
            scale: 1.5,
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
            strokeWeight: 4
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
          rutasTemporales.push(routePath);
        }

      });

    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      console.log("No se pudieron cargar los datos");
    }
  });*/
    /*CICLOVIAS*/
  /*$.ajax(
  {
    url : '/api/getAllCiclovia',
    type: "GET",
    success:function(data) 
    {
      $parseData=JSON.parse(data);
      //console.log($parseData);
      $.each($parseData.data, function(i, item) {
        var points = google.maps.geometry.encoding.decodePath(item.encodepath);

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
        rutasTemporales.push(routePath);

      });

    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      console.log("No se pudieron cargar los datos");
    }
  });*/

  getCurrentPosition();
  //Input para ingresar la ubicacion a buscar
  var input = /** @type {!HTMLInputElement} */(
            document.getElementById('originNear_formatted_address'));
  var autocomplete = new google.maps.places.Autocomplete(input);

  autocomplete.addListener('place_changed', function() {
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          markerPosition.setPosition(place.geometry.location);
          markerPosition.setVisible(true);
          console.log(place.geometry.location);
          //Update form inputs
          document.getElementById(markerPosition.myData+'_'+'lat').value = place.geometry.location.lat();
          document.getElementById(markerPosition.myData+'_'+'lng').value = place.geometry.location.lng();
          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        });
  autocomplete.setTypes([]);
  //FIN UBICACION A BUSCAR
}
function handleLocationError(browserHasGeolocation, pos) {

  alert(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
  markerPosition.setPosition(new google.maps.LatLng( 20.659699, -103.349609));
  markerPosition.setVisible(true);
  getStreetName(new google.maps.LatLng( 20.659699, -103.349609), "originNear_formatted_address");
}
function getCurrentPosition()
{
  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      console.log(position.coords.latitude);
      console.log(position.coords.longitude);
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      markerPosition.setPosition(pos);
      markerPosition.setVisible(true);
      
      getStreetName(pos, "originNear_formatted_address");
      document.getElementById('originNear_lat').value=position.coords.latitude;
      document.getElementById('originNear_lng').value=position.coords.longitude;
      map.setCenter(pos);
    }, function() {
      handleLocationError(true, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, map.getCenter());
  }
}

/*
 * Get street name from position
*/
function getStreetName(position, IDInput) {
  geocoder.geocode({'latLng': position}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[1]) {
         document.getElementById(IDInput).value = results[0].formatted_address;
      } else {
         document.getElementById(IDInput).value = "-";
         console.log("fail");
      }
    } else {
       document.getElementById(IDInput).value  = "-";
       console.log("fail geocoder");
    }
  });
}
function moveMarkerEvent(event) {
  var idMarker=this.myData+'_';
  //Update form inputs
  document.getElementById(idMarker+'lat').value = event.latLng.lat();
  document.getElementById(idMarker+'lng').value = event.latLng.lng();
  document.getElementById(idMarker+'formatted_address').value = event.formatted_address;
  getStreetName(event.latLng, idMarker+'formatted_address');
}

/**
 * Submit form
 * @param {Object} form
 * @return {boolean}
 */
 function submit_form(form) {
  var serializeArray = new FormData(form);

  $.ajax({
    url: form.action,
    type: form.method,
    data : serializeArray,
    cache:false,
    contentType: false,
    processData: false,
    success: function(data){

      resetMap();

      $parseData=JSON.parse(data);
      if($parseData.data.length==0 && $parseData.bikeway.length==0 && $parseData.mibici.length==0)
        alert("No hay rutas cercanas, intenta aumentando el rango.");
      else{

        draw_rutas($parseData.data);
        draw_bikeways($parseData.bikeway);
        draw_mibici($parseData.mibici);
      }
    },
    error: function (response) {
      console.log("fail");
      console.log(response);
    }
  });
  return false;
 }
 function draw_circle(){
  var rango= parseInt(document.getElementById('rango').value);
// Add circle overlay and bind to marker
  circle.setRadius(rango);
  circle.bindTo('center', markerPosition, 'position');
  if(circle.getMap() == null) circle.setMap(map);
 }
function resetMap()
{
  /*Quitar todas las rutas actuales del mapa*/
  for (i=0; i<rutasTemporales.length; i++) 
  {
    rutasTemporales[i].setMap(null); //or line[i].setVisible(false);
  }
  for (i=0; i<cicloviasTemporales.length; i++) 
  {
    cicloviasTemporales[i].setMap(null); //or line[i].setVisible(false);
  }
  for (i=0; i<estacionesMibiciTemporales.length; i++) 
  {
    estacionesMibiciTemporales[i].setMap(null); //or line[i].setVisible(false);
  }
  rutasTemporales=[];
  cicloviasTemporales=[];
  estacionesMibiciTemporales=[];
}
function draw_rutas(rutas){
  $.each(rutas, function(i, item) {
    for (var i = 0; i < item.paths.length; i++) {
      var points = google.maps.geometry.encoding.decodePath(item.paths[i].encodepath);

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
      rutasTemporales.push(routePath);
    }
  });
}
function draw_bikeways(ciclovias)
{
  $.each(ciclovias, function(i, item) {
      var points = google.maps.geometry.encoding.decodePath(item.encodepath);

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
      rutasTemporales.push(routePath);
  });
}
function draw_mibici(estaciones)
{
    $.each(estaciones, function(i, item) {

      var myLatLng = new google.maps.LatLng( item.latitude, item.longitude);

              var marker = new google.maps.Marker
              ({
                position: myLatLng,
                icon: '/images/mibici.svg',
                label: "",
                map: map,
                draggable: false,
                title: item.name,
                
          
              });
      google.maps.event.addListener(marker, 'mouseover', function(event) {
        infowindow.open(map);
        infowindow.setContent(item.name);
        infowindow.setPosition(event.latLng);
      });
      google.maps.event.addListener(marker, 'mouseout', function() {
        infowindow.close();
      });
      marker.setMap(map);
      estacionesMibiciTemporales.push(marker);
  });
}
google.maps.event.addDomListener(window, 'load', initialize);

$( "#rango" ).change(function() {
  draw_circle();
});
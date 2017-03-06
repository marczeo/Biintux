/**
* @fileoverview Manipulating Google Maps API to create "bikeways"
*
* @author Marco Gómez
* @version 0.1
*/

var geocoder;
var map;
var zap;
var gdl;
var directionsService;
var directionsDisplay;
var stepDisplay;
var markerArray=[];
var origin;
var destination;
var myRoute;
var leRoute;
var flightPath;

/**
* Initialize map and default values
* @param  {void}
* @returns {boolean} Estado de la eliminación
*/
function initialize_ciclovia() {

  //New instantiate map from google 
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: gdl
  });

  //New instantiate Geocoder
  geocoder = new google.maps.Geocoder();

  //Instantiate an info window to hold step text.
  stepDisplay = new google.maps.InfoWindow();


  // Instantiate a directions service.
  directionsService = new google.maps.DirectionsService;
  directionsDisplay = new google.maps.DirectionsRenderer({
    map: map,
    draggable:true,
    suppressMarkers:true,
  });
  
  directionsDisplay.addListener('directions_changed', function() {
    computeTotalDistance(directionsDisplay.getDirections());
  });

  //Latitude n longitudo two places
  gdl = { lat: 20.659699, lng: -103.349609 };
  zap = { lat: 20.666196, lng: -103.314743 };
  origin={lat: gdl.lat, lng: gdl.lng};
  destination={lat: zap.lat, lng: zap.lng};

  //Add two markers at the center of the map.
  addMarker(gdl, "A", "markerFrom");
  addMarker(zap, "B","markerTo");
  getAddress(gdl, 'markerFromAddress');
  getAddress(zap, 'markerToAddress');
  
  //Initialize form inputs
  document.getElementById('markerFromLat').value = gdl.lat;
  document.getElementById('markerFromLang').value = gdl.lng;
  document.getElementById('markerToLat').value = zap.lat;
  document.getElementById('markerToLang').value = zap.lng;

  //Create a initial route
  calculateAndDisplayRoute(directionsService, directionsDisplay, origin, destination);
}


function moveMarkerEvent(event) {
  var idMarker=this.myData;  

  //Update form inputs
  document.getElementById(idMarker+'Lat').value = event.latLng.lat();
  document.getElementById(idMarker+'Lang').value = event.latLng.lng();
  document.getElementById(idMarker+'Address').value = event.formatted_address;

  getAddress(event.latLng, idMarker+'Address');

  //Update values origin and destinitacion markers of form
  origin={lat: parseFloat(document.getElementById('markerFromLat').value), lng: parseFloat(document.getElementById('markerFromLang').value)};
  destination={lat: parseFloat(document.getElementById('markerToLat').value), lng: parseFloat(document.getElementById('markerToLang').value)};
  
  calculateAndDisplayRoute(directionsService, directionsDisplay, origin, destination);
  
}

// Adds a marker to the map.
function addMarker(location, name,id) {
  var marker = new google.maps.Marker({
    position: location,
    icon: '/images/cycling.png',
    label: name,
    map: map,
    draggable: true,
    title: "Marcador ",
    myData: id,
    animation: google.maps.Animation.DROP
  });
  marker.addListener('dragend', moveMarkerEvent);
}

//Get address string
function getAddress(latLng, idInput)
{
  geocoder.geocode({
    latLng: latLng
  }, function(responses) {
    if (responses && responses.length > 0) {      
      document.getElementById(idInput).value = responses[0].formatted_address;
    } else {
      document.getElementById(idInput).value = 'Cannot determine address at this location.';
    }
  });
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
  // First, clear out any existing markerArray
  // from previous calculations.
  clearMarkers();

  directionsService.route({
    origin: pointA,
    destination: pointB,
    //travelMode: google.maps.TravelMode.BICYCLING
    travelMode: google.maps.TravelMode.WALKING
  }, function(response, status) {//directionResult
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
      //steps
      myRoute = response.routes[0].legs[0];
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}
function attachInstructionText(marker, text) {
  google.maps.event.addListener(marker, 'click', function() {
    stepDisplay.setContent(text);
    stepDisplay.open(map, marker);
  });
}
function computeTotalDistance(result) {
  var flightPlanCoordinates=[];
  document.getElementById('markerList').value="";
  // First, clear out any existing markerArray
  // from previous calculations.
  clearMarkers();
  var total = 0;
  myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  total = total / 1000;
  document.getElementById('distance').value = total + ' km';

  leRoute = result.routes[0].legs[0];

  console.log("lineas: " +leRoute.steps.length);
  for (var i = 0; i < leRoute.steps.length; i++) {
    var marker = new google.maps.Marker({
      position: leRoute.steps[i].start_point,
      //map: map
    });
    attachInstructionText(marker, leRoute.steps[i].instructions);
    markerArray[i] = marker;
    flightPlanCoordinates[i]=leRoute.steps[i].start_point;
    document.getElementById('markerList').value+=leRoute.steps[i].start_point;
  }
  console.log("Origen: " + origin.lat+", "+ origin.lng);
  console.log("Destino: " + destination.lat+", "+ destination.lng);
  markerArray[i]=new google.maps.Marker({
    position: { lat: destination.lat, lng: destination.lng },
    //map: map
  });
  document.getElementById('markerList').value+="("+ destination.lat +"," +destination.lng +")";
  flightPlanCoordinates[i]={ lat: destination.lat, lng: destination.lng };

  
  flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2,
    //map: map,
  });
  
  var encodePath=google.maps.geometry.encoding.encodePath(flightPath.getPath());
  document.getElementById('encodePath').value =encodePath.replace(/\\/g,"\\\\");  
  
}

function clearMarkers()
{
  for (i = 0; i < markerArray.length; i++) {
    markerArray[i].setMap(null);
  }
  markerArray=[];
}
google.maps.event.addDomListener(window, 'load', initialize_ciclovia);



//# sourceMappingURL=ciclovia.js.map

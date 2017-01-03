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
  origin={lat: gdl.lat, lng: gdl.lng};

  //Add two markers at the center of the map.
  addMarker(gdl, "A", "markerFrom");
  getAddress(gdl, 'markerFromAddress');
  
  //Initialize form inputs
  document.getElementById('markerFromLat').value = gdl.lat;
  document.getElementById('markerFromLang').value = gdl.lng;
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

function showSteps(directionResult) {
  // For each step, place a marker, and add the text to the marker's
  // info window. Also attach the marker to an array so we
  // can keep track of it and remove it when calculating new
  // routes.
  myRoute = directionResult.routes[0].legs[0];
  console.log("showSteps");
  console.log(myRoute);

  
}
function attachInstructionText(marker, text) {
  google.maps.event.addListener(marker, 'click', function() {
    stepDisplay.setContent(text);
    stepDisplay.open(map, marker);
  });
}
function computeTotalDistance(result) {
  var flightPlanCoordinates=[];
  // First, clear out any existing markerArray
  // from previous calculations.
  console.log(markerArray.length);
  clearMarkers();
  var total = 0;
  myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  total = total / 1000;
  document.getElementById('distance').value = total + ' km';

  leRoute = result.routes[0].legs[0];
  console.log(leRoute);
  console.log("computeTotalDistance");
  for (var i = 0; i < leRoute.steps.length; i++) {
    console.log(leRoute.steps[i].start_point.latLng);
    var marker = new google.maps.Marker({
      position: leRoute.steps[i].start_point,
      map: map
    });
    attachInstructionText(marker, leRoute.steps[i].instructions);
    markerArray[i] = marker;
    flightPlanCoordinates[i]=leRoute.steps[i].start_point;
    console.log("tambor");
  }

  
  flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });
  var encodePath=google.maps.geometry.encoding.encodePath(flightPath.getPath());
  console.log("paths"); 
  console.log(flightPath);
  console.log(encodePath);
  document.getElementById('encodePath').value =encodePath.replace(/\\/g,"\\\\");
  console.log("limpio");
  console.log(document.getElementById('encodePath').value);

  
}

function clearMarkers()
{
  for (i = 0; i < markerArray.length; i++) {
    markerArray[i].setMap(null);
  }
}
google.maps.event.addDomListener(window, 'load', initialize_ciclovia);



//# sourceMappingURL=ciclovia.js.map

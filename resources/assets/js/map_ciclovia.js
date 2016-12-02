var geocoder = new google.maps.Geocoder();
var map;
var zap;
var gdl;
var directionsService;
var directionsDisplay;
var stepDisplay;
var markerArray=[];
var or;
var to;
var myRoute;
function initialize_ciclovia() {
  gdl = { lat: 20.659699, lng: -103.349609 };
  zap = { lat: 20.666196, lng: -103.314743 };
  
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: gdl
  });

  // Instantiate an info window to hold step text.
  stepDisplay = new google.maps.InfoWindow();


    // Instantiate a directions service.
  directionsService = new google.maps.DirectionsService,
  directionsDisplay = new google.maps.DirectionsRenderer({
      map: map,
      draggable:true,
      suppressMarkers:true,
    });
   directionsDisplay.addListener('directions_changed', function() {
    console.log("aqui");
    computeTotalDistance(directionsDisplay.getDirections());
  });

  // Add two markers at the center of the map.
  addMarker(gdl, map, "A", "markerFrom");
  addMarker(zap, map, "B","markerTo");
  getAddress(gdl, 'markerFromAddress');
  getAddress(zap, 'markerToAddress');
  or={lat: parseFloat(document.getElementById('markerFromLat').value), lng: parseFloat(document.getElementById('markerFromLang').value)};
  to={lat: parseFloat(document.getElementById('markerToLat').value), lng: parseFloat(document.getElementById('markerToLang').value)};
  calculateAndDisplayRoute(directionsService, directionsDisplay, gdl, zap);
  document.getElementById('markerFromLat').value = gdl.lat;
  document.getElementById('markerFromLang').value = gdl.lng;
  document.getElementById('markerToLat').value = zap.lat;
  document.getElementById('markerToLang').value = zap.lng;

}

function handleEvent(event) {
  var idMarker=this.myData;
  
  document.getElementById(idMarker+'Lat').value = event.latLng.lat();
  document.getElementById(idMarker+'Lang').value = event.latLng.lng();
  document.getElementById(idMarker+'Address').value = event.formatted_address;

  getAddress(event.latLng, idMarker+'Address');
  or={lat: parseFloat(document.getElementById('markerFromLat').value), lng: parseFloat(document.getElementById('markerFromLang').value)};
  to={lat: parseFloat(document.getElementById('markerToLat').value), lng: parseFloat(document.getElementById('markerToLang').value)};
  calculateAndDisplayRoute(directionsService, directionsDisplay, or, to);
  
}

function clickMarker(event) {
  var idMarker=this.myData;
  getAddress(event.latLng, idMarker+'Address');
}

// Adds a marker to the map.
function addMarker(location, map, name,id) {
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

  //marker.addListener('drag', handleEvent);
  marker.addListener('dragend', handleEvent);
  marker.addListener('click', clickMarker);
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
  for (i = 0; i < markerArray.length; i++) {
    markerArray[i].setMap(null);
  }
  directionsService.route({
    origin: pointA,
    destination: pointB,
    //travelMode: google.maps.TravelMode.BICYCLING
    travelMode: google.maps.TravelMode.WALKING
  }, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
      showSteps(response);
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}
function showSteps(directionResult) {
  // For each step, place a marker, and add the text to the marker's
  // info window. Also attach the marker to an array so we
  // can keep track of it and remove it when calculating new
  // routes.
  myRoute = directionResult.routes[0].legs[0];

  for (var i = 0; i < myRoute.steps.length; i++) {
      var marker = new google.maps.Marker({
        position: myRoute.steps[i].start_point,
        map: map
      });
      attachInstructionText(marker, myRoute.steps[i].instructions);
      markerArray[i] = marker;
  }
}
function attachInstructionText(marker, text) {
  google.maps.event.addListener(marker, 'click', function() {
    stepDisplay.setContent(text);
    stepDisplay.open(map, marker);
  });
}
function computeTotalDistance(result) {
  var total = 0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  total = total / 1000;
  document.getElementById('distance').value = total + ' km';

  myRoute = result.routes[0].legs[0];

  // First, clear out any existing markerArray
  // from previous calculations.
  for (i = 0; i < markerArray.length; i++) {
    markerArray[i].setMap(null);
  }
  for (var i = 0; i < myRoute.steps.length; i++) {
      var marker = new google.maps.Marker({
        position: myRoute.steps[i].start_point,
        map: map
      });
      attachInstructionText(marker, myRoute.steps[i].instructions);
      markerArray[i] = marker;
  }
}
google.maps.event.addDomListener(window, 'load', initialize_ciclovia);
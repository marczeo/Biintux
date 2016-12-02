var geocoder = new google.maps.Geocoder();
function initialize_ciclovia() {
  var gdl = { lat: 20.659699, lng: -103.349609 };
  var zap = { lat: 20.666196, lng: -103.314743 };
  
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: gdl
  });

  // Add two markers at the center of the map.
  addMarker(gdl, map, "A", "markerFrom");
  addMarker(zap, map, "B","markerTo");
  getAddress(gdl, 'markerFromAddress');
  getAddress(zap, 'markerToAddress');
}

function handleEvent(event) {
  var idMarker=this.myData;
  
  document.getElementById(idMarker+'Lat').value = event.latLng.lat();
  document.getElementById(idMarker+'Lang').value = event.latLng.lng();
  document.getElementById(idMarker+'Address').value = event.formatted_address;

  getAddress(event.latLng, idMarker+'Address');
  
}

function clickMarker(event) {
  var idMarker=this.myData;
  getAddress(event.latLng, idMarker+'Address');
}

// Adds a marker to the map.
function addMarker(location, map, name,id) {
  var marker = new google.maps.Marker({
    position: location,
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

google.maps.event.addDomListener(window, 'load', initialize_ciclovia);
//# sourceMappingURL=ciclovia.js.map

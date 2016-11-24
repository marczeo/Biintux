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
}

function handleEvent(event) {
  document.getElementById(this.myData+'Lat').value = event.latLng.lat();
  document.getElementById(this.myData+'Lang').value = event.latLng.lng();
}

function clickMarker(event) {
  console.log(this.myData);
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

google.maps.event.addDomListener(window, 'load', initialize_ciclovia);
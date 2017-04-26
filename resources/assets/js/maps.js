var map;
var infoWindow;
var userPosition;
var gdlPosition = { lat:20.6596988, lng: -103.34960920000003 };

function inicializar_mapa() {
	var mapOptions = {
		zoom: 13,
		center: gdlPosition,
	};
	map = new google.maps.Map(document.getElementById('map'), mapOptions);
	infoWindow = new google.maps.InfoWindow({map: map});
	getUserPosition();
}

function getUserPosition()
{
	// Try HTML5 geolocation.
	var markerUserPosition;
	
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			userPosition = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};

			markerUserPosition = new google.maps.Marker({
				position: userPosition,
				map: map,
				title: 'Su ubicaciónn!'
			});
			map.setCenter(userPosition);
		}, function() {
			handleLocationError(true, infoWindow, map.getCenter());
		});
	} else {
		// Browser doesn't support Geolocation
		handleLocationError(false, infoWindow, map.getCenter());
	}
}
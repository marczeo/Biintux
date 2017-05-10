/*
 * Mostrar selección de ruta cuando el usuario a agregar es concesionario
*/
$( "#select_role" ).change(function() {
	 if($( "#select_role option:selected" ).text() == "Concessionaire" || $( "#select_role option:selected" ).text() == "Concesionario") {
		$("#route_container").addClass('show');
		$("#bus_container").removeClass('show');
		$("#concessionaire_container").removeClass('show');
	}
	else if($("#select_role option:selected" ).text() == "Driver" || $("#select_role option:selected" ).text() == "Conductor")
	{
		$("#route_container").addClass('show');
		$("#bus_container").addClass('show');
		$("#concessionaire_container").addClass('show');
	}
	else{
		$("#route_container").removeClass('show');
		$("#bus_container").removeClass('show');
		$("#concessionaire_container").removeClass('show');
	}
});
$( "#select_route" ).change(event => {
	$.get('/route/'+event.target.value+'/getConcesionarios',function (res, sta) {
		var default_opt=$("#select_concessionaire option:first-child").val();
		$("#select_concessionaire").empty();
		$("#select_concessionaire").append('<option selected disabled>'+default_opt+'</option>');
		//console.log(res);
		res.forEach(element=>{
			//console.log(element);
			$("#select_concessionaire").append('<option value="'+element.id+'">'+element.name+'</option>');
		});
	});
});
$("#select_concessionaire").change(event=>{
	$.get('/route/'+event.target.value+'/getBuses',function (res, sta) {
		var default_opt=$("#select_bus option:first-child").val();
		$("#select_bus").empty();
		$("#select_bus").append('<option selected disabled>'+default_opt+'</option>');
		//console.log(res);
		res.forEach(element=>{
			//console.log(element);
			$("#select_bus").append('<option value="'+element.id+'">'+element.economic_number+'</option>');
		});
	});
});
var map;
var infowindow;
var marcadores=[];
function initialize() {
  var myLatLng = new google.maps.LatLng( 20.659699, -103.349609);
  infowindow = new google.maps.InfoWindow();
  var mapOptions = {
    zoom: 13,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };


  map = new google.maps.Map(document.getElementById('map'),
    mapOptions);


  loadData();


}
function loadData()
{
  for (var i=0; i<marcadores.length; i++) {
     
        marcadores[i].setMap(null);
    }
    marcadores=[];
  $.ajax(
  {
    url : '/api/getAllLocation',
    type: "GET",
    success:function(data) 
    {
      $parseData=JSON.parse(data);
      //console.log($parseData);
      $.each($parseData.data, function(i, item) {
        console.log(item.name);
        var location= new google.maps.LatLng( parseFloat(item.latitude),parseFloat(item.longitude));
        var marker = new google.maps.Marker({
          position: location,
          //label: item.name,
          title: item.name,
          map: map
        });
        marcadores[i]=marker;
        google.maps.event.addListener(marker, 'mouseover', function(event) {
          infowindow.open(map);
          if(item.name=="")
            infowindow.setContent("User");
          else
            infowindow.setContent(item.name);
          infowindow.setPosition(event.latLng);
        });
        google.maps.event.addListener(marker, 'mouseout', function() {
            infowindow.close();
        });

      });

    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      console.log("No se pudieron cargar los datos");
    }
  });
}
google.maps.event.addDomListener(window, 'load', initialize);
window.setInterval(function(){
  loadData();
}, 2000);
//# sourceMappingURL=user.js.map

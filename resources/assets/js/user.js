/*
 * Mostrar selección de ruta cuando el usuario a agregar es concesionario
*/
$( "#select_role" ).change(function() {
	 if($( "#select_role option:selected" ).text() == "Concessionaire" || $("#select_role option:selected" ).text() == "Driver"){
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
	$.get('/routeGet/'+event.target.value,function (res, sta) {
		$("#select_bus").empty();
		console.log(res);
		res.forEach(element=>{
			console.log(element);
			$("#select_bus").append('<option value="'+element.id+'">'+element.economic_number+'</option>');
		});
	});
	$.get('/routeGetCon/'+event.target.value,function (res, sta) {
		$("#select_concessionaire").empty();
		console.log(res);
		res.forEach(element=>{
			console.log(element);
			$("#select_concessionaire").append('<option value="'+element.id+'">'+element.name+'</option>');
		});
	});
});
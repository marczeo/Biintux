@extends('layouts.app')

@section('scriptsTop')

@endsection

@section('content')
<div id="wrapper">
  <div id="sidebar-wrapper" class="col-md-3">
    <div id="sidebar">
      {{-- Tabs --}}
      <ul class="nav nav-tabs" id="tab-menu">
        <li class="nav-item active">
          <a class="nav-link " href="#home">Viajar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#nearRoutes">Rutas cercanas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#searchRoute">Buscar ruta</a>
        </li>
      </ul>

      {{-- Contenido de tabs --}}
      <div class="tab-content">
        {{-- Para viajar, poner inicio y fin --}}
        <div class="tab-pane active" id="home" role="tabpanel">
          <ul class="nav list-group">
            <li>
              <form class="col-xs-12" role="search" method="POST" action="route/search" onsubmit="return submit_form(this);">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Origin" name="origin">
                  <div class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                  </div>
                </div>
              </form>
            </li>
            <li>
              <form class="col-xs-12" role="search">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Destination" name="destination">
                  <div class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                  </div>
                </div>
              </form>
            </li>
          </ul>
        </div>
        {{-- Buscar rutas cercanas a un punto --}}
        <div class="tab-pane" id="nearRoutes" role="tabpanel">
          <ul class="nav list-group">
            <form class="col-xs-12" role="search" method="POST" action="route/searchNear" onsubmit="return submit_form(this);">
              {{ csrf_field() }}
              {{ method_field('POST') }}
              <li>              
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Cerca de mi ubicación" name="origin" id="originNear_formatted_address">
                  <input type="hidden" name="originNear_lat" id="originNear_lat">
                  <input type="hidden" name="originNear_lng" id="originNear_lng">
                  <div class="input-group-btn">
                    <button class="btn btn-default" type="submit" ><i class="glyphicon glyphicon-search"></i></button>
                  </div>
                </div>
              </li>
              <li>
                <div class="form-group row">
                  <div class="col-xs-9">
                    <input type="number" class="form-control" placeholder="Rango" name="rango" id="rango" min="10" value="50">
                  </div>
                  <label class="col-form-label col-xs-3" for="">Metros</label>
                </div>
              </li>
            </form>
          </ul>
        </div>
        {{-- Buscar rutas en especifico --}}
        <div class="tab-pane" id="searchRoute" role="tabpanel">
          <ul class="nav list-group">
            <li>
              <form class="col-xs-12" role="search" method="POST" action="route/search" onsubmit="return submit_form(this);">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="¿Qué ruta desea buscar?" name="origin">
                  <div class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                  </div>
                </div>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div id="main-wrapper" class="col-md-9">
    <div id="main">
      <div id="map" style="height: 100%;"></div>
    </div>
  </div>
</div>
@endsection
@section('scriptsBottom')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
    <script src="/js/map_index.js"></script>
    <script type="text/javascript">
      //Utilizar tabs
      $('#tab-menu a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      })
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

      /*Quitar todas las rutas actuales del mapa*/
      for (i=0; i<rutasTemporales.length; i++) 
      {                           
        rutasTemporales[i].setMap(null); //or line[i].setVisible(false);
      }
      $parseData=JSON.parse(data);
      $.each($parseData.data, function(i, item) {
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
    },
    error: function (response) {
      console.log("fail");
      console.log(response);
    }
  });
  return false;
 }
 function locationEdge(){
  var rango=document.getElementById('rango').value;
  var originNear_lat=document.getElementById('originNear_lat').value;
  var originNear_lng=document.getElementById('originNear_lng').value;
  var myPosition = new google.maps.LatLng(originNear_lat, originNear_lng);
  /*Quitar todas las rutas actuales del mapa*/
  for (i=0; i<rutasTemporales.length; i++) 
  {
    rutasTemporales[i].setMap(null); //or line[i].setVisible(false);
  }
  for (i=0; i<rutasOriginales.length; i++) 
  {
    if (google.maps.geometry.poly.containsLocation(myPosition, rutasOriginales[i])) {
      rutasTemporales.push(rutasOriginales[i]);
      rutasTemporales[i].setMap(map);
      console.log("otra");
    }
  }

  return false;
 }
    </script>
 
@endsection
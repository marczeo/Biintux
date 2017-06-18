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
          <a id="viajar"class="nav-link " href="#home">Viajar</a>
        </li>
        <li class="nav-item">
          <a id="cercana"class="nav-link" href="#nearRoutes">Rutas cercanas</a>
        </li>
        <li class="nav-item">
          <a id="buscar" class="nav-link" href="#searchRoute">Buscar ruta</a>
        </li>
      </ul>

      {{-- Contenido de tabs --}}
      <div class="tab-content">
        {{-- Para viajar, poner inicio y fin --}}
        <div class="tab-pane active" id="home" role="tabpanel">
          <ul class="nav list-group">
            <form class="col-xs-12" role="search" method="POST" action="route/searchRoute" onsubmit="return submit_form(this);">
              {{ csrf_field() }}
              {{ method_field('POST') }}
              <li>
                <div class="form-group">
                  
                    <input type="search" class="form-control" placeholder="Origin" name="origin" id="originRoute_formatted_address">
                    <input type="hidden" name="originRoute_lat" id="originRoute_lat">
                    <input type="hidden" name="originRoute_lng" id="originRoute_lng">
                  
                </div>
              </li>
              <li>
                <div class="form-group">
                  <div class="input-group">
                    <input type="search" class="form-control" placeholder="Destination" name="destination" id="destinyRoute_formatted_address">
                    <input type="hidden" name="destinyRoute_lat" id="destinyRoute_lat">
                    <input type="hidden" name="destinyRoute_lng" id="destinyRoute_lng">
                    <div class="input-group-btn">
                      <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="form-group row">
                  <div>
                    <label class="col-xs-4"><input type="checkbox" name="preferences[]" value="bus" checked="checked"><img src="/images/bus.png"  width="25" height="25"><span class="text-desktop">Camiones</span></label>
                    <label class="col-xs-4"><input type="checkbox" name="preferences[]" value="bikeway"><img src="/images/cycling.png"  width="25" height="25"><span class="text-desktop">Ciclovías</span></label>
                    <label class="col-xs-4"><input type="checkbox" name="preferences[]" value="mibici"><img src="/images/mibici.png"  width="25" height="25"><span class="text-desktop">Estaciones Mibici</span></label>
                  </div>
                </div>
              </li>
            </form>
          </ul>
        </div>
        {{-- Buscar rutas cercanas a un punto --}}
        <div class="tab-pane" id="nearRoutes" role="tabpanel">
          <ul class="nav list-group">
            <form class="col-xs-12" role="search" method="POST" action="route/searchNear" onsubmit="return submit_form(this);">
              {{ csrf_field() }}
              {{ method_field('POST') }}
              <li>              
                <div class="form-group">
                  <div class="input-group">
                    <input type="search" class="form-control" placeholder="Cerca de mi ubicación" name="origin" id="originNear_formatted_address">
                    <input type="hidden" name="originNear_lat" id="originNear_lat">
                    <input type="hidden" name="originNear_lng" id="originNear_lng">
                    <div class="input-group-btn">
                      <button class="btn btn-default" type="submit" ><i class="glyphicon glyphicon-search"></i></button>
                    </div>
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
              <li>
                <div class="form-group row">
                  <div>
                    <label class="col-xs-4"><input type="checkbox" name="preferences[]" value="bus" checked="checked"><img src="/images/bus.png"  width="25" height="25"><span class="text-desktop">Camiones</span></label>
                    <label class="col-xs-4"><input type="checkbox" name="preferences[]" value="bikeway" ><img src="/images/cycling.png"  width="25" height="25"><span class="text-desktop">Ciclovías</span></label>
                    <label class="col-xs-4"><input type="checkbox" name="preferences[]" value="mibici" ><img src="/images/mibici.png"  width="25" height="25"><span class="text-desktop">Estaciones Mibici</span></label>
                  </div>
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
                  <input type="search" class="form-control" placeholder="¿Qué ruta desea buscar?" name="origin">
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
    <script sync src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=false&libraries=geometry,places"></script>
    <script src="/js/map_index.js"></script>
    <script type="text/javascript">
      //Utilizar tabs
      $('#tab-menu a').click(function (e) {
        var opcion=this.id;
        console.log(this.id);
        e.preventDefault();
        $(this).tab('show');
        map.setCenter(markerPosition.getPosition());
        switch(opcion) {
          case "viajar":
            markerPosition_origin.setVisible(true);
            markerPosition_destiny.setVisible(true);
            markerPosition.setVisible(false);
            map.setZoom(13);
            if(circle.getMap() != null) circle.setMap(null);
          break;
          case "cercana":
            markerPosition.setVisible(true);
            markerPosition_origin.setVisible(false);
            markerPosition_destiny.setVisible(false);
            getCurrentPosition();
            map.setZoom(18);
            draw_circle();
          break;
          case "buscar":
            markerPosition.setVisible(true);
            markerPosition_origin.setVisible(false);
            markerPosition_destiny.setVisible(false);
            getCurrentPosition();
            map.setZoom(13);
            if(circle.getMap() != null) circle.setMap(null);
            
          break;
        }
      });
    	
    </script>
 
@endsection
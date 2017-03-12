@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #cbedbe;}</style>
@endsection

@section('content')
<div class="jumbotron-fluid">
    @include('flash::message')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">{{trans('route.route')}}
                
            </div>
            <div class="panel-body">
                <div class="form-group col-md-3">
                    <div id="toolbar" class="btn-group">
                       <a href="{{ url('/route/create') }}" class="btn  btn-success">{{trans('navbar.add')}} <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                       </a>
                   </div>
                   <table 
                   data-toggle="table"
                   data-url="/getAllRoute"
                   data-query-params="queryParams"
                   data-pagination="true"
                   data-page-list="[5,10,20]"
                   data-buttons-class="default"
                   data-search="true"
                   data-toolbar="#toolbar"
                   data-height="500"
                   data-locale="es-MX">
                   <thead>
                    <tr>
                        <th class="col-xs-1" data-field="" data-sortable="true" data-cell-style="formaterColumnColor"></th>
                        <th class="col-xs-7" data-field="code" data-align="center" data-sortable="true">Nombre</th>
                        <th class="col-xs-4" data-field="route" data-sortable="true" data-formatter="formaterColumnEditActions" data-align="center">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="form-group col-md-9 ">
            <div id="map" style="height: 600px;"></div>
        </div>
    </div>
</div>
</div>

</div>
@endsection

@section('scriptsBottom')
<!--<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script>-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
<script src="/js/route-index.js"></script>
@endsection

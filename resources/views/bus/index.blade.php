@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #ded4eb;}</style>
@endsection

@section('content')
    <div class= "container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                <div class="panel-heading">{{trans('navbar.bus')}}
                    
                </div>
                <div class="panel-body">
                    <div class="form-group col-md-12">
                        <div id="toolbar" class="btn-group">
                         <a href="{{ url('/bus/create') }}" class="btn  btn-success">{{trans('navbar.add')}} <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                         </a>
                     </div>
                        <table 
                        data-toggle="table"
                        data-url="getAllBus"
                        data-query-params="queryParams"
                        data-pagination="true"
                        data-page-list="[5,10,20]"
                        data-buttons-class="default"
                        data-search="true"
                        data-toolbar="#toolbar"
                        data-locale="es-MX"
                        data-height="500">
                        <thead>
                            <tr>
                                <th class="col-xs-4" data-field="economic_number" data-align="center" data-sortable="true">Número economico</th>
                                <th class="col-xs-4" data-field="passenger_capacity" data-align="center" data-sortable="true">Capacidad</th>
                                <th class="col-xs-1" data-field="bus" data-sortable="true" data-formatter="formaterColumnEditActions" data-align="center">Opciones</th>
                            </tr>
                        </thead>
                    </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsBottom')
<!--<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script>-->
@endsection

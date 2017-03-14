@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #ded4eb;}</style>
@endsection

@section('content')
    <div class= "container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                <div class="panel-heading">{{trans('navbar.user')}}
                    
                </div>
                <div class="panel-body">
                    <div class="form-group col-md-12">
                        <div id="toolbar" class="btn-group">
                         <a href="{{ url('/user/create') }}" class="btn  btn-success">{{trans('navbar.add')}} <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                         </a>
                     </div>
                        <table 
                        data-toggle="table"
                        data-url="getAllUser"
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
                                <th class="col-xs-4" data-field="name" data-align="center" data-sortable="true">{{trans('user.name')}}</th>
                                <th class="col-xs-4" data-field="email" data-align="center" data-sortable="true">{{trans('user.email')}}</th>
                                @if(Auth::user()->isAdmin())
                                <th class="col-xs-3" data-field="role" data-align="center" data-sortable="true" data-cell-style="formaterColumnColor">{{trans('user.role')}}</th>
                                @endif
                                <th class="col-xs-1" data-field="user" data-sortable="true" data-formatter="formaterColumnEditActions" data-align="center">{{trans('user.options')}}</th>
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

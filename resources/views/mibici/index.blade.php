@extends('layouts.app')

@section('content')
<!-- include "addresstogeo.blade.php";-->
<div class= "container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('mibici.title')}}
                    | <a href="{{ url('/mibici/create') }}" class="btn  btn-success btn-xs">{{trans('navbar.add')}}
                        <span class="glyphicon glyphicon-plus" aria-hidden="true">
                        </span>
                    </a>
                    | <a href="{{ url('/mibici/edit') }}" class="btn  btn-info btn-xs">{{trans('navbar.edit')}}
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                        </span>
                    </a>
                    | <a href="{{ url('/mibici/destroy') }}" class="btn  btn-danger btn-xs">{{trans('navbar.destroy')}}
                        <span class="glyphicon glyphicon-minus" aria-hidden="true">
                        </span>
                    </a>
                </div>
                <div class="panel-body">
                    <div class="panel-body">{{trans('mibici.label')}}
                        <div class="form-group">
                        <!-- FOREACH PARA MOSTRAR TODAS LAS ESTACIONES EXISTENTES -->
                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
                            <script src="/js/mibici-index.js"></script>
                            <div class="form-gr">
                                <div id="map" style="height: 500px;">
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

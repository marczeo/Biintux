@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #cbedbe;}</style>
@endsection


@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('route.route')}}</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/route') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        
                            <label for="name" class="col-md-1">{{trans('ciclovia.name')}}</label>
                            
                            <div class="col-md-11">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="{{trans('ciclovia.name')}}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                           

                            
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        
                            <label for="first_run" class="col-md-1">{{trans('route.first_run')}}</label>
                            <div class="col-md-5">
                                <input id="first_run" type="time" class="form-control" name="first_run" value="{{ old('name') }}" required autofocus placeholder="{{trans('ciclovia.name')}}">
                                @if ($errors->has('first_run'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="last_run" class="col-md-1">{{trans('route.last_run')}}</label>
                            <div class="col-md-5">
                                <input id="last_run" type="time" class="form-control" name="last_run" value="{{ old('name') }}" required autofocus placeholder="{{trans('ciclovia.name')}}">
                                @if ($errors->has('last_run'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            
                        </div>
                        <div class="form-group">
                            <label for="markerFromAddress" class="col-md-1 text-left">{{trans('ciclovia.from')}}</label>
                            <div class="col-md-11">
                                <input id="markerFromAddress" type="text" class="form-control" name="from" value="{{ old('name') }}" placeholder="{{trans('ciclovia.from')}}">
                            </div>                                                                         
                        </div>
                        <div class="form-group">
                            <label for="markerToAddress" class="col-md-1">{{trans('ciclovia.to')}}</label>
                            <div class="col-md-11">
                                <input id="markerToAddress" type="text" class="form-control" name="to" value="{{ old('name') }}" placeholder="{{trans('ciclovia.to')}}">
                            </div>
                        </div>      
                        <div class="form-group">
                            <input type="hidden" id="markerFromLang" name="markerFromLang" value="">
                            <input type="hidden" id="markerFromLat" name="markerFromLat" value="">
                            <input type="hidden" id="markerToLang" name="markerToLang" value="">
                            <input type="hidden" id="markerToLat" name="markerToLat" value="">
                            <input type="hidden" id="encodePath" name="encodePath" value="">
                            <input type="hidden" id="markerList" name="markerList" value="">

                            <label for="distance" class="col-md-1">{{trans('ciclovia.distance')}}</label>
                            <div class="col-md-9">
                                <input id="distance" type="text" class="form-control" name="distance" value="{{ old('name') }}" placeholder="{{trans('ciclovia.distance')}}">
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('navbar.add')}}
                                </button>
                            </div>
                        </div>
                        <!--<script async defer
                            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&callback=initMap">
                        </script>-->
                        <div class="form-gr">
                            <div id="map" style="height: 500px;"></div>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsBottom')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
    <script src="/js/ciclovia.js"></script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Ciclovía</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/ciclovia') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">Name</label>
                            <input type="hidden" id="markerFromLang" name="markerFromLang" value="">
                            <input type="hidden" id="markerFromLat" name="markerFromLat" value="">
                            <input type="hidden" id="markerToLang" name="markerToLang" value="">
                            <input type="hidden" id="markerToLat" name="markerToLat" value="">
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('navbar.add')}}
                                </button>
                            </div>
                        </div>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true"></script>
                        <script src="/js/ciclovia.js"></script>
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

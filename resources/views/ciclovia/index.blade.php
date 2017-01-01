@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('ciclovia.bikeway')}}
                    <a href="{{ url('/ciclovia/create') }}" class="btn  btn-success btn-xs">{{trans('navbar.add')}}<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                </div>
                <div class="panel-body">
                    {{trans('ciclovia.label')}}
                    <div class="form-group">
                    @foreach ($ciclovias as $ciclovia)
                        <a href="ciclovia/{{$ciclovia->id}}">
                            {{$ciclovia->name}}
                            </a><br>
                    @endforeach
                    </div>
                    <!--<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script>-->
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
                    <script src="/js/ciclovia-index.js"></script>
                        
                    <div class="form-gr">
                        <div id="map" style="height: 500px;"></div>                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

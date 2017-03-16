@extends('layouts.app')

@section('scriptsTop')
@endsection

@section('content')
<div class="jumbotron-fluid">
    <div id="map" style="height: 800px;"></div>
</div>
@endsection
@section('scriptsBottom')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
    <script src="/js/map_index.js"></script>
@endsection
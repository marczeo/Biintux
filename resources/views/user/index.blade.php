@extends('layouts.app')

@section('scriptsTop')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk3aVE_atNGMx06dHKbmU6RMCgAOMMWEQ&signed_in=true&libraries=geometry"></script>
@endsection

@section('content')
    
    @foreach($usuarios as $user)
        {{$user->id}}
    @endforeach
@endsection

@section('scriptsBottom')
<!--<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script>-->
@endsection

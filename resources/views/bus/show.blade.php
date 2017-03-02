@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #ded4eb;}</style>
@endsection

@section('content')

<div class="container">
    @include('flash::message')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('bus.title')}}</div>
                <div class="panel-body text-center">
                    <h1>{{ $bus->economic_number }}</h1>
                    <h5>{{ $bus->passenger_capacity }}</h5>
                    <a class="btn btn-info" href="/bus/{{$bus->id}}/edit">{{trans('bus.edit')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

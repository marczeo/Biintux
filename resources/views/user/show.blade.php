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
                <div class="panel-heading">{{trans('profile.title')}}</div>
                <div class="panel-body text-center">
                    <img class="profile-img" src="/images/profile.svg" onerror="this.src='/images/profile.png'" width="500" height="500">
                    <h1>{{ $user->name }}</h1>
                    <h5>{{ $user->email }}</h5>
                    <a class="btn btn-info" href="/user/{{$user->id}}/edit">{{trans('profile.edit')}}</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

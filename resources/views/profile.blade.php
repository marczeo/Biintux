@extends('layouts.app')

@section('content')
<style type="text/css">
    .profile-img {
        max-width: 150px;
        border: 5px solid #fff;
        border-radius: 50%;
        box-shadow: 0 2px 2px rgba(0,0,0,0.3)
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('profile.title')}}</div>
                <div class="panel-body text-center">
                    <img class="profile-img" src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png">
                    <h1>{{ Auth::user()->name }}</h1>
                    <h5>{{ Auth::user()->email }}</h5>
                    <h5>{{ Auth::user()->birthdate }}</h5>
                    <button class="btn btn-info">{{trans('profile.edit')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

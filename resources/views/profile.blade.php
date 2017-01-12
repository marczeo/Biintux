@extends('layouts.app')

@section('content')
<style type="text/css">
    .profile-img 
    {
        max-width: 200px;
        max-height: 200px;
        border: 2px solid #fff;
        border-radius: 100%;
        box-shadow: 0 2px 2px rgba(0,0,0,0.4)
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('profile.title')}}</div>
                <div class="panel-body text-center">
                    <img class="profile-img" src="/images/profile.svg" onerror="this.src='/images/profile.png'" width="500" height="500">
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

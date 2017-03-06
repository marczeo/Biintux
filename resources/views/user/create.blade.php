@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #ded4eb;}</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user') }}">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">{{trans('register.name')}}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{trans('register.email')}}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if(Auth::user()->isAdmin())
                        <div class="form-group{{ $errors->has('rol') ? ' has-error' : '' }}">
                            <label for="rol" class="col-md-4 control-label">Rol</label>

                            <div class="col-md-6">
                                <select id="select_role" class="form-control" name="role_id">
                                    <option selected disabled>Choose here</option>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}">{{$role->description}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <!--Solo para consecionario-->
                        <div id="route_container" class="hide form-group{{ $errors->has('route') ? ' has-error' : '' }}">
                            <label for="route" class="col-md-4 control-label">Route</label>

                            <div class="col-md-6">
                                <select id="select_route" class="form-control" name="route_id">
                                    <option selected disabled>Choose here</option>
                                @foreach ($rutas as $ruta)
                                    <option value="{{$ruta->id}}">{{$ruta->code}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <!--Solo para admin-->
                        <div id="concessionaire_container" class="hide form-group{{ $errors->has('route') ? ' has-error' : '' }}">
                            <label for="concessionaire" class="col-md-4 control-label">Concessionaire</label>

                            <div class="col-md-6">
                                <select id="select_concessionaire" class="form-control" name="concessionaire_id">
                                    <option selected disabled>Choose here</option>
                                </select>
                            </div>
                        </div>

                        <div id="bus_container" class="hide form-group{{ $errors->has('route') ? ' has-error' : '' }}">
                            <label for="route" class="col-md-4 control-label">Bus</label>

                            <div class="col-md-6">
                                <select id="select_bus" class="form-control" name="bus_id">
                                    <option selected disabled>Choose here</option>
                                </select>
                            </div>
                        </div>
                        
                        @endif

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('user.add')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsBottom')
    <script src="/js/user.js"></script>
@endsection
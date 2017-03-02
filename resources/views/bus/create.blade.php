@extends('layouts.app')

@section('scriptsTop')
    <style>body {background-color: #ded4eb;}</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('bus.title')}}</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/bus') }}">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="form-group{{ $errors->has('economic_number') ? ' has-error' : '' }}">
                            <label for="economic_number" class="col-md-4 control-label">{{trans('bus.economic_number')}}</label>

                            <div class="col-md-6">
                                <input id="economic_number" type="text" class="form-control" name="economic_number" value="{{ old('economic_number') }}" required autofocus>

                                @if ($errors->has('economic_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('economic_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('passenger_capacity') ? ' has-error' : '' }}">
                            <label for="passenger_capacity" class="col-md-4 control-label">{{trans('bus.passenger_capacity')}}</label>

                            <div class="col-md-6">
                                <input id="passenger_capacity" type="text" class="form-control" name="passenger_capacity" value="{{ old('passenger_capacity') }}" required>

                                @if ($errors->has('passenger_capacity'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('passenger_capacity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('bus.btn_add')}}
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

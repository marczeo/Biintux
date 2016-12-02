@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Ciclovía
                    <a href="{{ url('/ciclovia/create') }}" class="btn  btn-success btn-xs">{{trans('navbar.add')}}<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                </div>

                <div class="panel-body">
                    C I C L O V Í A S
                    <div class="form-group">
                    @foreach ($ciclovias as $ciclovia)
                        <a href="ciclovia/{{$ciclovia->id}}">
                            {{$ciclovia->name}}
                            </a><br>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

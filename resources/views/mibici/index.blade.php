@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('mibici.title')}}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('mibici.Btn_select')}}
                                </button>
                            </div>

                            <div class="col-md-offset-1 col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('mibici.Btn_delete')}}
                                </button>
                            </div>

                            <div class="col-md-offset-1 col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('mibici.Btn_update')}}
                                </button>
                            </div>

                            <div class="col-md-offset-1 col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{trans('mibici.Btn_update')}}
                                </button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="custom_html">
                    <iframe style="margin-bottom: 0px; overflow: hidden; height: 400px; width: 100%;" src="https://amg.bktbp.com/monitor.php" width="100%" height="500px" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/map_mibici.js"></script>
</div>
@endsection

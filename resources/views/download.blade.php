@extends('layouts.app')

@section('content')
<div id="wrapper">
    <div class="background-download col-xs-12">
        <div class="download-wrapper">
            <div class="col-md-6 download-left" >
                <div class="text-center"><h2>¡Ya no te pierdas!</h2></div>
                <div>
                    <ul>
                        <li>Busca qué camiones te quedan cerca</li>
                        <li>Viaja en transporte público</li>
                        <li>Usa transporte multimodal</li>
                    </ul>
                </div>
                <div>
                    <a href="/getApp">
                        <strong>Descarga ahora</strong><img class="google-icon" src="images/google_play.png">
                    </a>
                </div>
            </div>
            <div  class="col-md-6 download-right">
                <img src="images/patterns/download_image.png">
            </div>
        </div>
    </div>
</div>
@endsection

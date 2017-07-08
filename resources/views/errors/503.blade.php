<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <style>
            html, body {
                height: 100%;
            }

            body {
                background-color: #5d4d92;
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }
            footer{
                position: fixed;
                right: 0;
                bottom: 0;
                left: 0;
                padding: 1rem;
                background-color: #222222;
                text-align: center;
                height: 40px;
                border-color: #090909;
                border-top-style: solid;
                border-top-width: 1px;
            }
            a{
                text-decoration: none;
                color:white;
            }
            a:active { 
                color: white;
            }
            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
            .mail{
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Estamos en mantenimiento</div>
                <div><img src="{{ asset('images/logo_big.png') }}"></div>
                <div class="mail"><a href="mailto:biintuxmovilidad@gmail.com">Comunícate  con nosotros: <br>biintuxmovilidad@gmail.com</a></div>
            </div>
        </div>
        <footer>Biintux &#174; 2017</footer>
    </body>
</html>

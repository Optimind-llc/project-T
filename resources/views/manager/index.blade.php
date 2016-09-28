<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="domain" content="{{$domain}}" />
    <title>品質不良マッピングシステム</title>
    <!-- google Roboto font -->
    <!--<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'> -->
    <!--<link href='http://fonts.googleapis.com/earlyaccess/notosansjp.css' rel='stylesheet' type='text/css'> -->
  </head>
  <body>
    <div id="root"></div>
    @if ($env === 'local')
        <script src="http://localhost:3001/static/app.js"></script>
    @elseif ($env === 'production')
        <script src="/build/app.js"></script>
    @endif
  </body>
</html>
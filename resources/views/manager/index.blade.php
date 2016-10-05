<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="domain" content="{{$domain}}" />
    <title>品質不良マッピングシステム</title>
    @if ($hotReload === true)
    <link rel="stylesheet" href="http://localhost:3000/static/bundle.css">
    @elseif ($hotReload === false)
    <link rel="stylesheet" href="/dist/bundle.css">
    @endif
    <!-- google Roboto font -->
    <!--<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'> -->
    <!--<link href='http://fonts.googleapis.com/earlyaccess/notosansjp.css' rel='stylesheet' type='text/css'> -->
  </head>
  <body>
    <div id="root"></div>
    @if ($hotReload === true)
        <script src="http://localhost:3000/static/bundle.js"></script>
    @elseif ($hotReload === false)
        <script src="/dist/bundle.js"></script>
    @endif
  </body>
</html>
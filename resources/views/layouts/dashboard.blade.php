<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.5/css/mdb.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
    

    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>

    <title>eCloud School</title>

    <link rel="apple-touch-icon" sizes="57x57" href={{asset('favicons/apple-icon-57x57.png')}}>
    <link rel="apple-touch-icon" sizes="60x60" href={{asset('favicons//apple-icon-60x60.png')}}>
    <link rel="apple-touch-icon" sizes="72x72" href={{asset('favicons//apple-icon-72x72.png')}}>
    <link rel="apple-touch-icon" sizes="76x76" href={{asset('favicons//apple-icon-76x76.png')}}>
    <link rel="apple-touch-icon" sizes="114x114" href={{asset('favicons//apple-icon-114x114.png')}}>
    <link rel="apple-touch-icon" sizes="120x120" href={{asset('favicons//apple-icon-120x120.png')}}>
    <link rel="apple-touch-icon" sizes="144x144" href={{asset('favicons//apple-icon-144x144.png')}}>
    <link rel="apple-touch-icon" sizes="152x152" href={{asset('favicons//apple-icon-152x152.png')}}>
    <link rel="apple-touch-icon" sizes="180x180" href={{asset('favicons//apple-icon-180x180.png')}}>
    <link rel="icon" type="image/png" sizes="192x192"  href={{asset('favicons//android-icon-192x192.png')}}>
    <link rel="icon" type="image/png" sizes="32x32" href={{asset('favicons//favicon-32x32.png')}}>
    <link rel="icon" type="image/png" sizes="96x96" href={{asset('favicons//favicon-96x96.png')}}>
    <link rel="icon" type="image/png" sizes="16x16" href={{asset('favicons//favicon-16x16.png')}}>
    <link rel="manifest" href={{asset('favicons//manifest.json')}}>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

</head>

<body id="home">

@yield('content')




<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.5/js/mdb.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>


<script type="text/javascript" src="{!! asset('js/scripts.js') !!}"></script>

@yield('script')

</body>

</html>

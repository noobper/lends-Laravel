@php
    $url = explode('/',$_SERVER['REQUEST_URI']);
    //dd($_SERVER['REQUEST_URI']);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="{{{ asset('img/computer.png') }}}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{--<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }} ">--}}
    {{ Html::style('css/bootstrap.css') }}
    {{ Html::style('css/bootstrap-table.css') }}
    {{ Html::style('css/font-awesome.css') }}
    @if(isset($style))
        @foreach($style as $css)
         {{ Html::style($css) }}   
        @endforeach              
    @endif
    @yield('style');
    <style>
        @font-face {
            font-family: 'Pridi';
            src: url('/fonts/Pridi-Regular.ttf');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Pridi';
            src: url('/fonts/Pridi-bold.ttf');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: "Pridi";
            letter-spacing: 1px;
        }
        table > tbody > tr:nth-of-type(even){
            background-color: #ffffff;
        }            
    </style>
    <title>@yield('title') </title>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav01" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/lend" style="color:#ffcb05"><b>EGAT</b></a>
            </div>
            <div class="collapse navbar-collapse" id="nav01">
                <ul class="nav navbar-nav">
                    <li {{ Request::segment(1) === 'lend'?'class=active':null }}><a href="/lend">ยืมอุปกรณ์</a></li>
                    <li {{ Request::segment(1) === 'search'?'class=active':null }}><a href="/search">ค้นหา</a></li>
                    <li {{ Request::segment(1) === 'lendshow'?'class=active':null }}><a href="/lendshow">รายการยืม</a></li>
                    <li {{ Request::segment(1) === 'hardware'?'class=active':null }}><a href="/hardware">อุปกรณ์</a></li>
                    <li {{ Request::segment(1) === 'hardwaretype'?'class=active':null }}><a href="/hardwaretype">ประเภทอุปกรณ์</a></li>
                    <li {{ Request::segment(1) === 'events'?'class=active':null }}> <a href="/events">ปฏิทิน</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="padding-top:60px;min-height: 97vh;">
        @yield('content')
    </div>

{{-- 
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script> --}}
{{ Html::script('js/jquery.min.js') }}
{{ Html::script('js/bootstrap.js') }}
{{ Html::script('js/bootstrap-table.js') }}
{{ Html::script('js/bootstrap-table-th-TH.js') }}

@if(isset($script))
    @foreach($script as $js)
        {{ Html::script($js,['type'=>'text/javascript']) }}
    @endforeach
@endif
@yield('script')
<footer style="background-color:#0000008a;min-height: 3vh;">
</footer>
</body>

</html>

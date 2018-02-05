@extends('layouts/main') @section('style') 
{{ Html::style('css/fullcalendar.css') }} 
@endsection 
@section('title','calendar')
@section('content')
    <div class="col-md-12">
        <ul class="navbar-left">
            <h1><b>ปฏิทิน {{ isset($name)?$name:'ยืมอุปกรณ์' }} </b> </h1>
        </ul>
        <ul class="navbar-right">
            <div class="dropdown">
                <h1></h1>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">แสดงตามอุปกรณ์ <span class="fa fa-caret-down"></span></button>&emsp;
                <ul class="dropdown-menu">
                        <li>{{ Html::link('/events/','ทั้งหมด') }}</li>
                    @foreach ($hardware as $item)
                        <li>{{ Html::link('/events/'.$item->id,$item->hw_name) }}</li>
                    @endforeach
                            
                </ul>
            </div>
        </ul>
    </div>
<div class="panel panel-default">
    
    <div class="panel-body">
        {!! $calendar->calendar() !!}
    </div>
</div>
@endsection 
@section('script') 
{{ Html::script('js/moment.min.js') }}
{{ Html::script('js/fullcalendar.js') }}
{{ Html::script('js/th.js') }}
{!! $calendar->script() !!} @endsection
@php
    for($i=0;$i<24;$i++){
        $hour[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
        $minute[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
    };
    for($i;$i<60;$i++) {
        $minute[$i]=str_pad($i,2,'0',STR_PAD_LEFT);
    };
@endphp
@extends('layouts/main')
@section('title','ยืม/คืน อุปกรณ์')
@section('content')
    <div class="container">
        <h1>ฟอร์มยืมอุปกรณ์</h1>
        <hr>
        @if (isset($check))
            <div class="alert alert-danger">
            <table class="table-status">
                @foreach ($check as $q)
                <tr>
                    <td><span class="fa fa-exclamation"></span> {{$q->hw_name}} ถูกจองแล้ว</td>
                    <td>ตั้งแต่</td>
                    <td>{{ \Carbon\Carbon::parse($q->hw_out)->format('D d/m/Y เวลา H:i น.') }}</td>
                    <td>ถึง</td>
                    <td>{{ \Carbon\Carbon::parse($q->hw_back)->format('D d/m/Y เวลา H:i น.') }}</td>
                </tr>                    
                @endforeach
            </table>
            </div>
           
        @endif
        {{ Form::open(['action'=>'LendController@store','method'=>'post']) }}
        <div class="col-md-6 col-sm-6">
            <dl class="dl-horizontal">
                <dt>ผู้ยืม<span class='text-danger'>*</span></dt>
                <dd class='{{ $errors->has('user_id') ? ' has-error' : '' }}'>{{ Form::text('user_id','',['class'=>'form-control','autofocus']) }}
                    <span class='help-block text-danger'>{{ $errors->first('user_id') }}</span>
                </dd>
                <dt>อุปกรณ์ที่ต้องการ</dt>
                <dd>
                <div class="btn-group-vertical">
                @foreach($hardware as $q)
                @if(session('hw_id.'.$q->id)==$q->id)
                <p class="btn purple form-control" style='cursor:default'>{{ $q->hw_name }}</p>
                @endif
                @endforeach                    
                </div>
                </dd>
                <dt>เบอร์ติดต่อ<span class='text-danger'>*</span></dt>
                <dd class='{{ $errors->has('phone') ? ' has-error' : '' }}'>
                    {{ Form::text('phone','',['class'=>'form-control']) }}
                    <span class='help-block text-danger'>{{ $errors->first('phone') }}</span>
                </dd>
                
                <dd>
                    <ul class="radio">
                        <label>
                            {{Form::radio('with_setup', '0', true) }}
                            รับด้วยตนเอง
                        </label>
                    </ul>
                    <ul class="radio">
                        <label>
                            {{Form::radio('with_setup', '1', false) }} 
                            พร้อมติดตั้ง
                        </label>
                    </ul>

                </dd>
            </dl>
        </div>
        <div class="col-md-6 col-sm-6">
            <dl class="dl-horizontal">
                <dt>สถาณที่<span class='text-danger'>*</span></dt>
                <dd class='{{ $errors->has('room') ? ' has-error' : '' }}'>
                    ห้อง : {{ Form::text('room','',['class'=>'form-control']) }}
                    <span class='help-block text-danger'>{{ $errors->first('room') }}</span>
                </dd>
                <dd class='{{ $errors->has('building') ? ' has-error' : '' }}'> 
                    ตึก &nbsp;&nbsp;: {{ Form::text('building','',['class'=>'form-control']) }}
                    <span class='help-block text-danger'>{{ $errors->first('building') }}</span>
                </dd>
                <dt>วันที่รับ<span class='text-danger'>*</span></dt>
                <dd class='{{ $errors->has('hw_out') ? ' has-error' : '' }}'>
                    {{ Form::date('hw_out',\Carbon\Carbon::now(),['class'=>'form-control text-center']) }}
                    <span class='help-block text-danger'>{{ $errors->first('hw_out') }}</span>
                </dd>
                <dd>
                    {{ Form::select('hour_out',$hour,'8',['class'=>'form-control text-right']) }} : 
                    {{ Form::select('min_out',$minute,'',['class'=>'form-control']) }}
                    นาที
                    <span class="help-block text-danger"></span>
                </dd>
           
                <dt>กำหนดส่งคืน<span class='text-danger'>*</span></dt>
                <dd class='{{ $errors->has('hw_back') ? ' has-error' : '' }}'>
                    {{ Form::date('hw_back',\Carbon\Carbon::now(),['class'=>'form-control text-center']) }}
                    <span class='help-block text-danger'>{{ $errors->first('hw_back') }}</span>
                </dd>
                <dd>
                    {{ Form::select('hour_back',$hour,'',['class'=>'form-control text-right']) }} : 
                    {{ Form::select('min_back',$minute,'',['class'=>'form-control']) }}
                    นาที
                </dd>
            </dl>
        </div>
        <div class="col-md-12 col-sm-12">
            <dl class="dl-horizontal">
                <dt>หมายเหตุ</dt>
                <dd class='{{ $errors->has('other') ? ' has-error' : '' }}'>
                    {{ Form::textarea('other','',['class'=>'form-control width-90','rows'=>'4']) }}
                    <span class='help-block text-danger'>{{ $errors->first('other') }}</span>
                </dd>
            </dl>
        <div class="text-center">
            {{ Form::submit('บันทึกแบบฟอร์ม',['class'=>'btn btn-lg btn-success']) }} | 
            {{ Html::link('lend',' ย้อนกลับ',['class'=>'btn btn-lg btn-danger']) }}
        </div>
        {{ Form::close() }}
        </div>
    </div>
@endsection
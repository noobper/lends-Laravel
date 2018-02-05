@php
    $hw_out = explode(" ",$lend->hw_out);
    $hw_back = explode(" ",$lend->hw_back);
    if(null == session('hw_edit')){        
        foreach($bk as $q1){
        session()->put('hw_edit.'.$q1->hw_id, $q1->hw_id);
        };
       //session()->put('hw_edit.0', '0');
    };
    //session()->forget('hw_edit');
    //dd(session('hw_edit'));
@endphp
@extends('layouts/main')
@section('title','แก้ไขรายการยืม')
@section('content')
<div class="container">
    <div class="col-md-6 col-sm-6">
<h3>ฟอร์มแก้ไขรายการยืม</h3>
    {{ Form::open(['action'=>['LendshowController@update',$lend->id],'method'=>'put']) }}
        <dl class="dl-horizontal">
            <dt>
                <label for="user_id">ผู้ยืม : </label>
            </dt>
            <dd>
                {{ Form::text('user_id',$lend->user_id,['class'=>'form-control','autofocus']) }}
            </dd>
            <dt>
                <label for="phone">เบอร์ติดต่อ : </label>
            </dt>
            <dd>
                {{ Form::text('phone',$lend->phone,['class'=>'form-control']) }}
            </dd>
            <dt>
                <label for="hw">อุปกรณ์ : </label>
            </dt>
            <dd>
            <div class="btn-group-vertical">
                @if (session('hw_edit'))
                    @foreach($hardware as $q1)
                    @if(session('hw_edit.'.$q1->id)==$q1->id)
                        {{ Html::link('lendshow/del/'.$q1->id,$q1->hw_name,['class'=>'btn purple']) }}
                    @endif
                    @endforeach          
                    @endif
            </div>
            </dd>
            <dt>
                <label for="room">ห้อง : </label>
            </dt>
            <dd>
                {{ Form::text('room',$lend->room,['class'=>'form-control']) }}
            </dd>
            <dt>
                <label for="building">ตึก : </label>
            </dt>
            <dd>
                {{ Form::text('building',$lend->building,['class'=>'form-control']) }}
            </dd>
            <dt>
                <label for="hw_out">กำหนดรับ/ติดตั้ง : </label>
            </dt>
            <dd>
                {{ Form::date('hw_out',$hw_out[0],['class'=>'form-control']) }}
            </dd>
            <dt>
        <label for="hw_out_2">เวลา : </label>            
            </dt>
            <dd>
        {{ Form::time('hw_out_2',$hw_out[1],['class'=>'form-control']) }}            
            </dd>
            <dt>
        <label for="hw_back">กำหนดส่งคืน : </label>            
            </dt>
            <dd>
        {{ Form::date('hw_back',$hw_back[0],['class'=>'form-control']) }}            
            </dd>
            <dt>
        <label for="hw_back_2">เวลา : </label>            
            </dt>
            <dd>
        {{ Form::time('hw_back_2',$hw_back[1],['class'=>'form-control']) }}            
            </dd>
            <dt>
        <label for="status">สถานะ : </label>
            </dt>
            <dd>
        {{ Form::select('status',['2'=>'จอง','1'=>'รับ/ติดตั้งเรียบร้อย','0'=>'คืนเรียนร้อย'],$lend->status,['class'=>'form-control']) }}
            </dd>
            <dt>
        <label for="staff_send">เจ้าหน้าที่ผู้ส่งมอบ :</label>
            </dt>
            <dd>
        {{ Form::text('staff_send',$lend->staff_send,['class'=>'form-control']) }}        
            </dd>
            <dt>
        <label for="staff_receive">เจ้าหน้าที่ผู้รับคืน :</label>
            </dt>
            <dd>
        {{ Form::text('staff_receive',$lend->staff_receive,['class'=>'form-control']) }} 
            </dd>
            <dt>
        <label for="other">หมายเหตุ : </label>
            </dt>
            <dd>
        {{ Form::textarea('other',$lend->other,['class'=>'form-control','rows'=>'3','cols'=>'26']) }}
            </dd>
            <dd>
                <div style='display:inline-flex'>
        {{ Form::submit('บันทึก',['class'=>'btn btn-primary']) }}
    {{ Form::close() }}        
        &emsp;
        {{ Html::link(session('urlback'),'ยกเลิก',['class'=>'btn btn-warning']) }}
        &emsp;
        {{ Form::open(['action'=>['LendshowController@destroy',$lend->id],'method'=>'DELETE']) }}
            {{ Form::submit('ลบรายการนี้',['class'=>'btn btn-danger']) }}
        {{ Form::close() }}
            </div>
            </dd>
        </dl>
    </div>
    <div class="col-md-6 col-sm-6">
    <div class="btn-group-vertical">
    <h3>อุปกรณ์</h3>
    @foreach($hardware as $q2)
    @if(session('hw_edit.'.$q2->id)==$q2->id)
        <a href="/lendshow/del/{{ $q2->id }}" class="btn purple"><span class="fa fa-check-square pull-left"> </span> {{ $q2->hw_name }}</a>
    @else
        <a href="/lendshow/add/{{ $q2->id }}" class="btn purple"><span class="fa fa-square pull-left"> </span> {{ $q2->hw_name }}</a>
    @endif
        
        
    @endforeach
    </div>
    </div>
</div>

@endsection
@php
    session(['urlback' => $_SERVER['REQUEST_URI']]);
@endphp
@extends('layouts/main');
@section('titel','รายละเอียด')
@section('content')
    <h1>รายละเอียดรายการยืมอุปกรณ์</h1>
    <dl class="dl-horizontal">
        <dt>ผู้ยืม :</dt>
        <dd>{{ $lend->user_id }}</dd>
        <dt>เบอร์ติดต่อ :</dt>
        <dd>{{ $lend->phone }}</dd>
        <dt>อุปกรณ์ :</dt>
        <dd>
            @foreach ($detail as $item)
                <li>{{ $item->hw_name }}</li>
            @endforeach
        </dd>
        <dt>ห้อง :</dt>
        <dd>{{ $lend->room }}</dd>
        <dt>ตึก :</dt>
        <dd>{{ $lend->building }}</dd>
        <dt>บริการติดตั้ง :</dt>
        <dd>{{ ($lend->with_setup==0)?'มารับด้วยตนเอง':'พร้อมบริการติดตั้ง' }}</dd>
        <dt>กำหนดรับ/ติดตั้ง :</dt>
        <dd>{{ Carbon\Carbon::parse($lend->hw_out)->format('D d-m-Y เวลา H:i น.') }}</dd>
        <dt>กำหนดส่งคืน :</dt>
        <dd>{{ Carbon\Carbon::parse($lend->hw_back)->format('D d-m-Y เวลา H:i น.') }}</dd>
        <dt>สถานะ :</dt>
        <dd>{{ ($lend->status==1)?'มารับ/ติดตั้ง เรียบร้อย':(($lend->status==2)?'จอง':'คืนแล้ว') }} </dd>
        <dt>เจ้าหน้าที่ผู้ส่งมอบ :</dt>
        <dd>{{ $lend->staff_send }}</dd>
        <dt>เวลา :</dt>
        <dd>{{ Carbon\Carbon::parse($lend->staff_send_time)->format('D d-m-Y เวลา H:i น.') }}</dd>
        <dt>เจ้าหน้าที่ผู้รับคืน :</dt>
        <dd>{{ $lend->staff_receive }}</dd>
        <dt>เวลา :</dt>
        <dd>{{ Carbon\Carbon::parse($lend->staff_receive_time)->format('D d-m-Y เวลา H:i น.') }}</dd>
        <dt>หมายเหตุ : </dt>
        <dd>{{ Form::textarea('',$lend->other,['class'=>'form-control','rows'=>'3','cols'=>'26','readonly']) }}
            </dd>
        <dd>
            {{ Html::link('/lendshow/'.$lend->id.'/edit','แก้ไข',['class'=>'btn btn-warning']) }}
            {{ Html::link('/events','ย้อนกลับ',['class'=>'btn btn-danger']) }}

        </dd>
</dl>
    
@endsection
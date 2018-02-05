@php
    $created=explode(" ",$lend->created_at);
    $i=1;
@endphp
@extends('layouts/main')
@section('title','รายละเอียด')
@section('content')
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3>รายละเอียดการขอใช้บริการ</h3>
        </div>
        <div class="panel-body">
        <span class='pull-right'>
            <b>ลงวันที่</b> {{ \Carbon\Carbon::parse($lend->created_at)->format('d F Y เวลา H:i น.')}} &nbsp;&nbsp;
        </span>
            <h3><b>ข้อมูลผู้ใช้บริการ</b></h3>
        <ul>
            <p><b>ผู้ยืม : </b> {{ $lend->user_id }}</p>    
            <p><b>เบอร์ติดต่อ : </b>{{ $lend->phone }} </p>
            <p><b>ห้อง : </b>{{ $lend->room }} </p>
            <p><b>ตึก : </b>{{ $lend->building }} </p>
            <p><b>{{ ($lend->with_setup==1)?'ติดตั้งวันที่':'มารับวันที' }} : </b> 
            {{ \Carbon\Carbon::parse($lend->hw_out)->format('d F Y เวลา H:i น.')}} </p>
            <p><b>กำหนดส่งคืน : </b>
            {{ \Carbon\Carbon::parse($lend->hw_back)->format('d F Y เวลา H:i น.')}} </p>
            <p><b>หมายเหตุ : </b>{{ $lend->other }} </p>
        </ul>
            <h3><b>ข้อมูลอุปกรณ์</b></h3>
        <ul>
        <table class="table table-borderless" style="width:0px">
        @foreach($basket as $bk )
            <tr>
                <td>{{ $i++ }}</td>
                <td> {{ $bk->hw_name }} </td>
                <td><b>รายละเอียด : </b></td>
                <td> {{ $bk->hw_detail }} </td>
            </tr>
            
        @endforeach
        </table>
        </ul>
        </div>
        <div class="panel-footer">
            <ul class="col-md-4 col-md-offset-4">
                {{ Html::link('/lend','ปิด',['class'=>'btn btn-danger btn-lg btn-block']) }}
            </ul>
        </div>
    </div>
</div>


    

@endsection
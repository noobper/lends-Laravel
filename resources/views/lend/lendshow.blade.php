@php
    session(['urlback' => $_SERVER['REQUEST_URI']]);
@endphp
@extends('layouts/main')
@section('title','รายการยืม')
@section('content')
{{-- <meta http-equiv="refresh" content="3;"> --}}
<div class="pull-left">
    <h1><b>&emsp; รายการยืมอุปกรณ์</b></h1>
</div>

<div class="pull-right">
    <h1></h1>
  {{ Form::open(['method'=>'get']) }}
  {{ Form::select('type',['hw_out'=>'กำหนดรับ/ติดตั้ง','hw_back'=> 'กำหนดส่งคืน'],'',['class'=>'form-control']) }}
  {{ Form::date('date',now(),['class' => 'form-control']) }}
  {{ Form::submit('กรอง',['class'=>'form-control']) }}
  {{ Form::close() }}
</div>
<div class="container-fluid">
<table class="table table-bordered text-center">
    <thead>
        <th>ID</th>
        <th>วันที่ลง</th>
        <th>ผู้ยืม</th>
        <th>สถาณที่</th>
        <th>อุปกรณ์</th>
        <th>กำหนดเวลา</th>
        <th>สถานะ</th>
        <th>เจ้าหน้าที่</th>
        <th></th>
    </thead>
    @forelse ($lend as $q)
    <tr>
        <td>{{ $q->id }}</td>
        <td>{{ $q->created_at->format('D d/m/Y') }}<br>{{ $q->created_at->format('H:i น.') }}</td>
        <td>
            {{ $q->user_id }}
            <hr class="hr2">
            <b>เบอร์ติดต่อ</b><br>
            {{ $q->phone }}
        </td>
        <td>
            <p><b>ห้อง : </b>{{ $q->room }}</p> 
            <hr class="hr2">
            <p><b>ตึก : </b>{{ $q->building }}</p>
        </td>
        <td align='left'>
            @foreach($basket as $q2)
                @if($q2->lend_id==$q->id)
                {{ $q2->hw_name }}<hr class='hr3'>
                @endif
            @endforeach
        </td>
        <td>
            <b>มารับ/ติดตั้ง</b><br>        
            {{ \Carbon\Carbon::parse($q->hw_out)->format('D d/m/Y') }}<br>
            {{ \Carbon\Carbon::parse($q->hw_out)->format('H:i.น.') }}
            <hr class="hr2">
            <b>ส่งคืน</b><br>
            {{ \Carbon\Carbon::parse($q->hw_back)->format('D d/m/Y') }}<br>
            {{ \Carbon\Carbon::parse($q->hw_back)->format('H:i.น.') }}
        
        </td>
        <td>{{ ($q->status==1)?'มารับ/ติดตั้ง เรียบร้อย':(($q->status==2)?'จอง':'คืนแล้ว') }}<hr class='hr2'>
            {{ ($q->with_setup==0)?'มารับด้วยตนเอง':'พร้อมบริการติดตั้ง' }}
        </td>
        <td>
            @if ($q->status<=1)
                <b>ผู้ส่งมอบ</b>
                <br>
                {{ $q->staff_send }}
                <br>
                {{ \Carbon\Carbon::parse($q->staff_send_time)->format('d/m/Y H:i.น.') }}
            @if ($q->status==0)
                <hr class="hr2">
                <b>ผู้รับคืน</b><br>
                {{ $q->staff_receive }}
                <br>
                {{ \Carbon\Carbon::parse($q->staff_receive_time)->format('d/m/Y H:i.น.') }}
            @endif
            @endif 
        </td>
        <td>
            <div class="btn-group-vertical" role="group" aria-label="...">
                <button data-toggle="modal" data-target="#out{{$q->id}}" class="btn btn-success btn-lg fa fa-paper-plane {{($q->status<=1)?' disabled-2':''}}"> มารับ/ติดตั้งแล้ว</button>
                @if ($q->status<=1)
                <button data-toggle="modal" data-target="#back{{$q->id}}" class="btn btn-info btn-lg fa fa-archive {{($q->status==0)?' disabled-2':''}}"> ได้รับคืนแล้ว</button>
                @endif
                {{ Html::link('lendshow/'.$q->id.'/edit',' แก้ไขรายการนี้',['class'=>'btn btn-warning btn-lg fa fa-pencil-square-o']) }}
                {{--                  
                {{ Html::link('lendshow/out/'.$q->id,'',['class'=>'btn btn-primary fa fa-truck fa-2x'.(($q->status<=1)?' disabled-2':''),'data-toggle'=>'tooltip','title'=>'ติดตั้งแล้ว']) }}
                {{ Html::link('lendshow/back/'.$q->id,'',['class'=>'btn btn-info fa fa-archive fa-2x'.(($q->status==0)?' disabled-2':''),'data-toggle'=>'tooltip','title'=>'ได้รับคืนแล้ว']) }}  
                {{ Html::link('lendshow/'.$q->id.'/edit','',['class'=>'btn btn-warning fa fa-pencil-square-o fa-2x','data-toggle'=>'tooltip','title'=>'แก้ไขรายการนี้']) }}
                --}}
            </div>
        </td>
    </tr>
    {{-- out --}}
    
    <div id="out{{ $q->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog">
        {{ Form::open(['method'=>'get','url'=>'lendshow/out/'.$q->id.'/' ]) }}    
            <div class="modal-content">
                <div class="modal-body">
                    <label for="staff">เจ้าหน้าที่ผู้ส่งมอบ</label>
                    {{ Form::text('staff','',['class'=>'form-control width-90 ','autofocus'=>'ture']) }}
                    {{ Form::submit('บันทึก',['class'=>'btn btn-success width-10']) }}
                </div>
        {{ Form::close() }}
            </div>
        </div>
    </div>
    {{-- back --}}
    <div id="back{{ $q->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
            {{ Form::open(['method'=>'get','url'=>'lendshow/back/'.$q->id.'/' ]) }}    
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="staff">เจ้าหน้าที่ผู้รับคืน</label>
                        {{ Form::text('staff','',['class'=>'form-control width-90 ','autofocus'=>'ture']) }}
                        {{ Form::submit('บันทึก',['class'=>'btn btn-success width-10']) }}
                    </div>
            {{ Form::close() }}
                </div>
            </div>
        </div>

    @empty
        <tr>
            <td colspan="11"><b>ไม่มีรายการยืมอุปกรณ์</b></td>
        </tr>
    @endforelse

</table>
</div>
    {{ $lend->links() }}
    {{ Html::link('lendshow','ล้างค่าการกรอง',['class'=>'btn btn-default pull-right']) }}
<br><br><br>
@endsection
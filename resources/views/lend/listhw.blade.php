@php
    session(['urlback' => $_SERVER['REQUEST_URI']]);
    $i=(isset($_GET['page']))?($_GET['page']*10)-10:0;
@endphp
@extends('layouts/main')
@section('title','รายการอุปกรณ์')
@section('content')
    <div class="container">
    <div class="col-md-12">
    <ul class="navbar-left"><h1><b>อุปกรณ์ทั้งหมด</b></h1></ul>
    <ul class="navbar-right btn-group"><h1> </h1>
    <div class="btn-group" role="group">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            ประเภทอุปกรณ์
            <span class="fa fa-caret-down"></span>
            </button>
            <ul class="dropdown-menu">
            @foreach($hw_type as $q2)
                <li>{{ Html::link('hardware/'.$q2->id,$q2->type_name) }}</li>
            @endforeach
                <li class="divider"></li>
                <li>{{ Html::link('hardware','ทั้งหมด') }}</li>
            </ul>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            สถานะ
            <span class="fa fa-caret-down"></span>
            </button>
            <ul class="dropdown-menu">
                <li>{{ Html::link('hardware/0/0','ว่าง') }} </li>
                <li>{{ Html::link('hardware/0/1','รอส่งคืน') }} </li>
                <li>{{ Html::link('hardware/0/2','ถูกจองแล้ว') }} </li>
                <li class="divider"></li>
                <li>{{ Html::link('hardware','ทั้งหมด') }}</li>
            </ul>
        </div>
    </div>
    {{ Html::link('hardware/create','เพิ่มอุปกรณ์ใหม่',['class'=>'btn btn-default']) }}  
</div>        
    </ul>
    </div>{{-- dropdrow --}}
        <table class="table table-striped table-bordered text-center">
        {{-- 
        @if ($errors->any())
            <tr>
                <td colspan="8" align="center" class="warning">
                    {{ $errors->first() }}
                </td>
            </tr>
        @elseif(Session::has('message'))
            <tr>
                <td colspan="8" align="center" class="info">
                    {{ Session::get('message') }}
                </td>
            </tr>
        @endif
        --}}
            
        
        <thead>          
            <tr>
                <th>No.</th>
                <th>ชื่ออุปกรณ์</th>
                <th>ประเภทอุปกรณ์</th>
                <th>รายละเอียดอุปกรณ์</th>
                <th>เลขครุภัณฑ์</th>
                <th>สถานะ</th>
                <th>เพิ่มเมื่อ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($hardware as $q)
        @php
            $i++;
        @endphp
        
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $q->hw_name }}</td>
                <td>
                    {{ $q->type_name }}
                </td>
                <td>{{ $q->hw_detail }}</td>
                <td>{{ $q->hw_no }}</td>
            @if($q->deleted!=0)
                <td class="danger">ปิดการใช้งาน</td>   
            @elseif ($q->status==1)
                <td class="warning">รอส่งคืน</td>                
            @elseif ($q->status==2)
                <td class="info">ถูกจองแล้ว</td>
            @else
                <td class="success">ว่าง</td>
            @endif
                <td>{{ \Carbon\Carbon::parse($q->created_at)->format('d/m/Y H:i') }}</td>
                <td>
                    {{ Html::link('hardware/'.$q->id.'/edit','แก้ใข',['class'=>'btn btn-success']) }}
                    |
                    {{ Html::link('hardware/search/'.$q->id,'ประวัติ',['class'=>'btn btn-primary']) }}
                    |
                    <a href="events/{{$q->id}}" class="btn btn-info" title="ปฏิทิน"><span class="fa fa-calendar"></span></a>
                    |
                    <button class="btn btn-{{ ($q->deleted==0)?'danger':'warning' }}" data-toggle="modal" data-target="#deltype{{ $q->id }}">{{ ($q->deleted==0)?'ลบ':'กู้คืน' }}</button>
                </td>
            </tr>

    {{-- delete-type-modal --}}           

    <div id="deltype{{ $q['id'] }}" class="modal fade text-center" role="dialog">
        <div class="modal-dialog">
        {{ Form::open(['method'=>'DELETE','action' =>['HardwareController@destroy',$q->id]]) }}
        {{ Form::hidden('softdel',($q->deleted==0)?'1':'0') }}                  
        <div class="modal-content">
            <div class="modal-body">
            <b>{{ ($q->deleted==0)?'ยืนยันการลบ':'ยกเลิกการลบ' }}</b> : {{ $q['hw_name'] }}  <br><br>
                    {{ Form::submit('ยืนยัน',['class'=>'btn btn-success']) }} |
                    <button class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>                
            </div>
    {{ Form::close() }}
        </div>
        </div>
    </div>
        @empty
        <tr>
            <td colspan="8"><b>ไม่มีอุปกรณ์ในระบบ</b></td>
        </tr>                  
        @endforelse
        </tbody>
        </table>
        {{ $hardware->links() }}   
@endsection
@extends('layouts/main')
@section('title','ระบบยืนอุปกรณ์ (เพิ่มอุปกรณ์)')
@section('content')
    {{ Form::open(['metod'=>'post','action'=>'AddhardwareController@store']) }}
    <div class="container">
    <div class="col-md-8 col-md-offset-1">
    <h1 class="col-md-offset-2">ฟอร์มเพิ่มอุปกรณ์</h1>
        <table class="table text-right">
            @if ($errors->any())
            <tr>
                <td colspan="2" align="center" class="danger">
                    {{ $errors->first() }}
                </td>
            </tr>
            @elseif(Session::has('message'))
            <tr>
                <td colspan="2" align="center" class="info">
                    {{ Session::get('message') }}
                </td>
            </tr>
            @endif
            <tr>
                <td>ประเภทอุปกรณ์</td>
                <td align="left">
                    <select name="hardware_type" class="form-control">
                        @foreach($hw_type as $r)
                            <option value='{{ $r['type_id'] }}'>{{ $r['type_name'] }}</option>
                        @endforeach
                    </select>
                   {{-- {{ Form::select('hardware_type',[],'1',['class'=>'form-control width-90']) }} --}}                    
                {{ Html::link('#','เพิ่ม',array('class'=>'btn btn-default addhw')) }}
                </td>
            </tr>
            <tr>
                <td>ชื่ออุปกรณ์</td>
                <td>{{ Form::text('hardware_name','',['class'=>'form-control width-100','autofocus']) }}</td>
            </tr>
            <tr>
                <td>รายละเอียด</td>
                <td>{{ Form::text('hardware_detail','',['class'=>'form-control width-100']) }}</td>
            </tr>
            <tr>
                <td>เลขครุภัณฑ์</td>
                <td>{{ Form::text('hw_no','',['class'=>'form-control width-100']) }}</td>
            </tr> 
            
            <tr>
                <td colspan="2" align="center">
                    {{ Form::submit('บันทึก',['class'=>'btn btn-success','title'=>'บันทึกอุปกรณนี้']) }} ||
                    {{ Html::link('/lend','ย้อนกลับ',array('class'=>'btn btn-danger','title'=>'กลับไปหน้ายืมอุปกรณ์')) }}
                </td>
            </tr>
            
           
                
        </table>
    </div>
    </div>
    {{ Form::close() }}
    
    
     <div class="modal fade">
        <div class="modal-dialog" id="modal01">
            {{ Form::open(['method'=>'post','action'=>'AddtypeController@store']) }}
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มประเภทอุปกรณ์</h4>
                </div>
                <div class="modal-body">
                    {{ Form::text('type_name','',['class'=>'form-control width-100','id'=>'addnew','id'=>'type_name']) }}
                </div>
                <div class="modal-footer">
                    {{ form::submit('บันทึก',['class'=>'btn btn-primary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>{{-- /end moddal --}}
    
@endsection
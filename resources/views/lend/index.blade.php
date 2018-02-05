@extends('layouts/main')
@section('title','ระบบยืนอุปกรณ์')
@section('content')
    <h1>Lend Form</h1>
    {{ Form::open(['metod'=>'post','action'=>'LendController@store']) }}
    <div class="container">
    <div class='col-md-4'>
        รหัส
    </div>
    <div class='col-md-8'>
        <table class="table table-bordered text-right">
            <tr>
                <td>ชื่อผู้ยืม</td>
                <td>{{ Form::text('user','',['class'=>'form-control width-100']) }}</td>
            </tr>
            <tr>
                <td>ประเภทอุปกรณ์</td>
                <td>{{ Form::select('type',array('1','2'),'1',['class'=>'form-control width-100']) }}</td>
            </tr>
            <tr>
                <td>เริ่มจอง</td>
                <td class="text-left">
                    {{ Form::date('start-date',\Carbon\Carbon::now(),['class'=>'form-control']) }}
                    {{-- 
                    วันที่
                    {{ Form::select('startday',$day,'',['class'=>'form-control']) }}
                    เดือน
                    {{ Form::select('startmonth',array('L' => 'Large', 'S' => 'Small'),'S',['class'=>'form-control']) }}
                    ปี
                    {{ Form::select('startyear',array('L' => 'Large', 'S' => 'Small'),'S',['class'=>'form-control']) }}
                    --}}
                </td> 
            </tr>
            <tr>
                <td>กำหนดส่งคืน</td>
                <td class="text-left">
                   {{ Form::date('end-date',now(),['class'=>'form-control']) }}
                </td>
            </tr>
        </table>
    </div>
    </div>
    {{ Form::close() }}

@endsection

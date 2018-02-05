@php
    $i=(isset($_GET['page']))?($_GET['page']*10)-10:0;
@endphp
@extends('layouts/main')
@section('title','รายการประเภทอุปกรณ์')
@section('content')
    <div class="container">
    <div class="col-md-12">
    <ul class="navbar-left"><h1><b>ประเภทของอุปกรณ์</b></h1></ul>
    <ul class="navbar-right">
        <h1></h1><button class="btn btn-default" data-toggle="modal" data-target="#addtype">เพิ่มประเภทอุปกรณ์ใหม่</button>
    </ul>
    </div>
        <table class="table table-hover table-bordered text-center">
        @if ($errors->any())
            <tr>
                <td colspan="6" align="center" class="warning">
                    {{ $errors->first() }}
                </td>
            </tr>
        @elseif(isset($message))
            <tr>
                <td colspan="6" align="center" class="info">
                    {{ $message->first() }}
                </td>
            </tr>
        @endif           
            <tr>
                <th width="80px">No.</th>
                <th>ชื่อประเภท</th>
                <th>สถานะ</th>
                <th>เพิ่มเมื่อ</th>
                <th>แก้ไขเมื่อ</th>
                <th></th>
            </tr>
        @forelse ($hw_type as $q)
        @php
            $i++;
        @endphp
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $q->type_name }}</td>
            @if($q->deleted==0)
                <td class="success">พร้อมใช้งาน</td>
            @else
                <td class="danger">ลบแล้ว</span></td>   
            @endif
                <td>{{ \Carbon\Carbon::parse($q->created_at)->format('d/m/Y H/i') }}</td>
                <td>{{ $q->updated_at->diffforhumans() }}</td>
                <td>
                    <button class="btn btn-success" data-toggle="modal" data-target="#edittype{{ $q['id'] }}">แก้ใข</button>
                    ||
                @if($q->deleted==0)
                    <button class="btn btn-warning" data-toggle="modal" data-target="#deltype{{ $q['id'] }}">ปิดการเพิ่มอุปกรณ์</button>
                @else
                    <button class="btn btn-info" data-toggle="modal" data-target="#undeltype{{ $q['id'] }}">เปิดการเพิ่มอุปกรณ์</button>                        
                @endif
                    ||
                    <button class="btn btn-danger" data-toggle="modal" data-target="#harddel{{ $q['id'] }}">ลบถาวร</button>
                </td>
            </tr>
        
    {{-- edit-type-modal --}}           
    <div id="edittype{{ $q['id'] }}" class="modal fade" role="dialog">
        <div class="modal-dialog">
    {{ Form::open(['method'=>'put','action' =>['HwtypeController@update',$q->id]]) }}   
                    
        <div class="modal-content">
            <div class="modal-body">
            <label for="type_name">เปลี่ยนเป็น...</label>
                {{-- Form::hidden('id',$q['id']) --}}
                {{ Form::text('type_name',$q['type_name'],['class'=>'form-control width-90']) }}
                {{ Form::submit('บันทึก',['class'=>'btn btn-success width-10']) }}
            </div>
    {{ Form::close() }}
        </div>
        </div>
    </div>
    {{-- delete-type-modal --}}           

    <div id="deltype{{ $q['id'] }}" class="modal fade text-center" role="dialog">
        <div class="modal-dialog">
        {{ Form::open(['method'=>'DELETE','action' =>['HwtypeController@destroy',$q->id]]) }}
        {{ Form::hidden('softdel','1') }}                     
        <div class="modal-content">
            <div class="modal-body">
            <b>ปิดการเพิ่มอุปกรณ์</b> : {{ $q['type_name'] }}  <br><br>
                    {{ Form::submit('ใช่',['class'=>'btn btn-success']) }} |
                    <button class="btn btn-danger" data-dismiss="modal">ไม่</button>                
            </div>
    {{ Form::close() }}
        </div>
        </div>
    </div>
    {{-- undelete-type-modal --}}           

    <div id="undeltype{{ $q['id'] }}" class="modal fade text-center" role="dialog">
        <div class="modal-dialog">
        {{ Form::open(['action' =>['HwtypeController@destroy',$q->id],'method'=>'delete']) }}'
        {{ Form::hidden('softdel','0') }}                    
        <div class="modal-content">
            <div class="modal-body">
            <b>เปิดการเพิ่มอุปกรณ์</b> : {{ $q['type_name'] }}  <br><br>
                    {{ Form::submit('ใช่',['class'=>'btn btn-success']) }} |
                    <button class="btn btn-danger" data-dismiss="modal">ไม่</button>                
            </div>
    {{ Form::close() }}
        </div>
        </div>
    </div>
    {{-- harddelete-type-modal --}}           

    <div id="harddel{{ $q['id'] }}" class="modal fade text-center" role="dialog">
            <div class="modal-dialog">          
            <div class="modal-content">
                <div class="modal-body">
                <b>ลบ</b> : {{ $q['type_name'] }} ออกถาวร  <br><br>
                    {{ Html::link('hardwaretype/delete/'.$q['id'],'ลบ',['class' => 'btn btn-success']) }}
                    |
                    <button class="btn btn-danger" data-dismiss="modal">ไม่</button>                
                </div>
        {{ Form::close() }}
            </div>
            </div>
        </div>
        @empty
                <td colspan="6" align="center">
                    <b>ไม่มีประเภทอุปกรณ์ในระบบ</b> 
                </td>               
        @endforelse
        </table>
        <div id="addtype" class="modal fade" role="dialog">
            <div class="modal-dialog">
            {{ Form::open(['method'=>'get','action'=>'HwtypeController@create']) }}                   
                <div class="modal-content">
                    <div class="modal-body">
                    <label for="type_name">เพิ่มประเภทอุปกรณ์ใหม่...</label>
                    {{ Form::text('type_name','',['class'=>'form-control width-90 ','autofocus'=>'ture']) }}
                    {{ Form::submit('บันทึก',['class'=>'btn btn-success width-10']) }}
                    </div>
    {{ Form::close() }}
                </div>
            </div>
        </div>
        {{ $hw_type->links() }}   
    </div>
@endsection
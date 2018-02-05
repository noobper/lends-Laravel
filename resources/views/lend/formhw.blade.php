@extends('layouts/main')
@section('title','เพิ่มอุปกรณ์ใหม่')
@section('content')
    <div class="container">
    <div class="col-md-8 col-md-offset-1">
    <h1 class="col-md-offset-2">
    @if(isset($edithw))
            ฟอร์มแก้ไขอุปกรณ์
            {{ Form::open(['action'=>['HardwareController@update',$edithw->id],'method'=>'PUT']) }}
        @else
            ฟอร์มเพิ่มอุปกรณ์
            {{ Form::open(['method'=>'post','action'=>'HardwareController@store']) }}
        @endif    
    </h1>
        <table class="table text-right">
            @if ($errors->any())
            <tr>
                <td colspan="2" align="center" class="danger">
                    {{ $errors->first() }}
                </td>
            </tr>
            @endif
            @if(Session::has('message'))
            <tr>
                <td colspan="2" align="center" class="info">
                    {{ Session::get('message') }}
                </td>
            </tr>
            @endif
            <tr>
                <td>ประเภทอุปกรณ์</td>
                <td align="left">
                    <select name="hw_type" class="form-control">
                        @foreach($hw_type as $q)
                            <option value='{{ $q->id }}' {{ isset($edithw)?($q->id==$edithw->hw_type)?'selected':'':'' }}>{{ $q->type_name }}</option>
                        @endforeach
                    </select>
                    {{ Html::link('/hardwaretype','เพิ่ม',['class'=>'btn btn-default','title'=>'ไปหน้าประเภทอุปกรณ์']) }}
                </td>
            </tr>
            <tr>
                <td>ชื่ออุปกรณ์</td>
                <td align="left">{{ Form::text('hw_name',isset($edithw)?$edithw->hw_name:'',['class'=>'form-control','autofocus']) }}</td>
            </tr>
            <tr>
                <td>รายละเอียด</td>
                <td align="left">{{ Form::text('hw_detail',isset($edithw)?$edithw->hw_detail:'',['class'=>'form-control']) }}</td>
            </tr>
            <tr>
                <td>เลขครุภัณฑ์</td>
                <td align="left">{{ Form::text('hw_no',isset($edithw)?$edithw->hw_no:'',['class'=>'form-control']) }}</td>
            </tr> 
            
            <tr>
                <td colspan="2" align="center">
                    {{ Form::submit('บันทึก',['class'=>'btn btn-success','title'=>'บันทึกอุปกรณนี้']) }} || 
                    @if (isset($edithw))
                        {{ Html::link('hardware/delete/'.$edithw->id,'ลบถาวร',['class'=>'btn btn-warning']) }} ||
                    @endif
                    {{ Html::link(Session('urlback'),'ย้อนกลับ',array('class'=>'btn btn-danger','title'=>'ย้อนกลับ')) }}
                   
                </td>
            </tr>
            
           
                
        </table>
    </div>
    </div>
    {{ Form::close() }}
    {{ Session::get('message') }}
@endsection
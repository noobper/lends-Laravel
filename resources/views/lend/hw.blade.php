@php
    $i=(isset($_GET['page']))?($_GET['page']-1)*8:0;
    //session()->flush();
@endphp
@extends('layouts/main')
@section('title','ยืม/คืน อุปกรณ์')
@section('content')
    <div class="container">
     <div class='col-md-3'>
        <div class="panel panel-success" name="selected">
            <div class="panel-heading"> 
                <b>อุปกรณ์ที่เลือก</b>
                {{ Html::link('lend/del/all','ล้าง',['class'=>'btn btn-default btn-sm pull-right','style'=>'padding: 2px 10px']) }}
            </div>
            <div class="panel-body">
                <div class="btn-group-vertical width-100" role="group" aria-label="#selected">
                    @if (session('hw_id'))
                    @foreach($hardware as $q1)
                    @if(session('hw_id.'.$q1->id)==$q1->id)
                        <a href='lend/del/{{ $q1->id }}' class='btn purple'>
                            {{ $q1->hw_name }}
                            <span class='glyphicon glyphicon-trash align-right' style='color:red'></span>
                        </a>
                    @endif
                    @endforeach
                        {{ Html::link('','',['class'=>'btn disabled']) }}
                        {{ Html::link('lend/create','กรอกข้อมูลการจอง',['class'=>'btn btn-default']) }}          
                    @else
                        <a href="" class="btn disabled ">
                            <b>ย้งไม่ได้เลือกอุปกรณ์</b>
                        </a>
                        
                    @endif
                </div>
            </div>
        </div> 
        <div class="panel panel-danger">
            <div class="panel-heading">
                <span class="fa fa-exclamation-circle"></span><b> คำเตือน</b>
            </div>
            <div class="panel-body">
                &emsp;กรุณาตรวจสอบช่วงเวลาการจองของอุปกรณ์ก่อนการจองทุกครั้ง
            </div>
        </div>
    </div>
    
    <div class='col-md-8'>
    <div class="panel panel-info">
        <div class="panel-heading">
            <b> รายการ พัสดุ/ครุภัณฑ์</b>
            <span class="pull-right" style="color:black"><b>วันที่ปัจจุบัน {{ date('d-m-Y') }}</b></span>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ชื่ออุปกรณ์</th>
                    <th>ประเภทอุปกรณ์</th>
                    <th>รายละเอียด</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            {{-- @forelse ($hardware as $q) --}} 
            @forelse ($hardware as $q)           
            @php                
                $i++;
            @endphp 
                <tr>
                    <td align='center'>{{ $i }}</td>
                    <td>{{ $q->hw_name }}</td>
                    <td>{{ $q->type_name }}</td>
                    <td>{{ $q->hw_detail }}</td>

                    @if($q->deleted!=0)
                        <td class="danger">ลบแล้ว</td>   
                    @elseif ($q->status==1)
                        <td class="warning">รอส่งคืน</td>                
                    @elseif ($q->status==2)
                    <td class="warning text-center">
                        ถูกจองแล้ว
                        <hr class="hr2">
                            {{ Html::link('#',' ตรวจสอบ',['class'=>'btn btn-default btn-block fa fa-search','data-toggle'=>'modal','data-target'=>'#check'.$q->id]) }}
                    </td>
                    @else
                    <td class="success text-center">
                        ว่าง
                    </td>
                    @endif
                    
                    <td align='center'>       
                    @if(session('hw_id.'.$q->id)==$q->id)     
                        {{ Html::link('lend/del/'.$q->id,' เลือกแล้ว',['class'=>'btn btn-success btn-block btn-lg fa fa-check']) }}
                    @else
                        {{ Html::link('lend/add/'.$q->id,' เลือก',['class'=>'btn btn-primary btn-lg btn-block fa fa-plus']) }}
                    @endif
                    </td>
                </tr>
{{-- modal --}}
<div id='check{{ $q->id }}' class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
         <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
          <h4><b> ตารางการจอง : {{ $q->hw_name }}</b></h4>
            @foreach ($basket as $q1)
            @if (($q->id==$q1->hw_id))
            <hr class="hr2">
            <p>ผู้จอง : {{ $q1->user_id }}</p>            
                เริ่มวันที่
                {{ \Carbon\Carbon::parse($q1->hw_out)->format('d-m-Y ( H:m น. )') }}<br>
            &nbsp;ถึงวันที่
               {{ \Carbon\Carbon::parse($q1->hw_back)->format('d-m-Y ( H:m น. )') }}
            @endif
            @endforeach
        </div>
    </div>
  </div>
</div>
{{-- end modal --}}

            @empty
                <tr><td colspan="6" class='text-center'><b>ไม่มีอุปกรณ์ในระบบ</b></td></tr>
            @endforelse
        
        </table>
        {{ $hardware->links() }}
        </div>
    </div>
        
    </div>
</div>

@endsection

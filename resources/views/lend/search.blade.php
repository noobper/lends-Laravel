@php
    session(['urlback' => $_SERVER['REQUEST_URI']]);
    
@endphp
@extends('layouts/main')
@section('title','ค้นหา')
@section('content')
    <div class="container">
        <ul class="text-center">
            {{ Form::open(['method'=>'get','url'=>'search','class'=>'form-inline']) }} 
            {{ Form::select('from', ['อุปกรณ์' => 'อุปกรณ์', 'ผู้ยืม' => 'ผู้ยืม'],(isset($_GET['from']))?$_GET['from']:'',['class'=>'form-control']) }}
            {{ Form::text('search','',['class'=>'form-control','autofocus']) }}
            <button class="btn btn-default" type="submit" style="padding: 2px 4px;" >
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                </span>
                <b> ค้นหา</b> &nbsp;
            </button>
            {{ Form::close() }}
        </ul>
    @if (!empty($result))
        <hr class="hr2">
        <ul>
            <h1>ผลการค้นหา</h1>
            <table class="table table-bordered text-center">
            @if ($from=='อุปกรณ์')
                <tr>
                    <th>ขื่ออุปกรณ์</th>
                    <th>ประเภท</th>
                    <th>รายละเอียด</th>
                    <th>เลขครุภัณฑ์</th>
                    <th>สถาณะ</th>
                    <th></th>
                </tr>
            @forelse ($result as $item)
                <tr>
                    <td>{{ $item->hw_name }}</td>
                    <td>{{ $item->type_name }}</td>
                    <td>{{ $item->hw_detail }}</td>
                    <td>{{ $item->hw_no }}</td>
                    @if($item->deleted!=0)
                        <td class="danger">ปิดการใช้งาน</td>   
                    @elseif ($item->status==1)
                        <td class="warning">รอส่งคืน</td>                
                    @elseif ($item->status==2)
                        <td class="info">ถูกจองแล้ว</td>
                    @else
                        <td class="success">ว่าง</td>
                    @endif
                    <td>{{ Html::link('search/hardware/'.$item->id,'แก้ใข',['class'=>'btn btn-success']) }} |
                        {{ Html::link('search/'.$item->id,'ประวัติ',['class'=>'btn btn-primary']) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5"><br><br><br><b>ไม่พบข้อมูล "<span class="text-danger">{{ $_GET['search'] }}</span>" ในระบบ</b><br><br><br><br></td>
                </tr>
            @endforelse
            @endif                
            @if ($from=='ผู้ยืม')
                <tr>
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
                </tr>
            @forelse ($result as $item)
                <tr>

                    <td>{{ $item->id }}</td>
                    <td>{{ $item->created_at->format('D d/m/Y') }}<br>{{ $item->created_at->format('H:i น.') }}</td>
                    <td>
                        {{ $item->user_id }}
                        <hr class="hr2">
                        <b>เบอร์ติดต่อ</b><br>
                        {{ $item->phone }}
                    </td>
                    <td>
                        <p><b>ห้อง : </b>{{ $item->room }}</p> 
                        <hr class="hr2">
                        <p><b>ตึก : </b>{{ $item->building }}</p>
                    </td>
                    <td align='left'>
                        @foreach($basket as $q)
                            @if($q->lend_id==$item->id)
                            {{ $q->hw_name }}<hr class='hr3'>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <b>มารับ/ติดตั้ง</b><br>        
                        {{ \Carbon\Carbon::parse($item->hw_out)->format('D d/m/Y') }}<br>
                        {{ \Carbon\Carbon::parse($item->hw_out)->format('H:i.น.') }}
                        <hr class="hr2">
                        <b>ส่งคืน</b><br>
                        {{ \Carbon\Carbon::parse($item->hw_back)->format('D d/m/Y') }}<br>
                        {{ \Carbon\Carbon::parse($item->hw_back)->format('H:i.น.') }}
                        
                    </td>
                    <td>{{ ($item->status==1)?'มารับ/ติดตั้ง เรียบร้อย':(($q->status==2)?'จอง':'คืนแล้ว') }}<hr class='hr2'>
                        {{ ($item->with_setup==0)?'มารับด้วยตนเอง':'พร้อมบริการติดตั้ง' }}
                    </td>
                    <td>
                        @if ($item->status<=1)
                            <b>ผู้ส่งมอบ</b><br>
                            {{ $item->staff_send }}
                            <br>
                            {{ \Carbon\Carbon::parse($item->staff_send_time)->format('d/m/Y H:i.น.') }}
                        @if ($item->status==0)
                            <hr class="hr2">
                            <b>ผู้รับคืน</b><br>
                            {{ $item->staff_receive }}
                            <br>
                            {{ \Carbon\Carbon::parse($item->staff_receive_time)->format('d/m/Y H:i.น.') }}
                        @endif
                        @endif 
                    </td>
                    <td>{{ Html::link('lendshow/'.$item->id.'/edit','แก้ใข',['class'=>'btn btn-success']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10"><br><br><br><b>ไม่พบข้อมูล "<span class="text-danger">{{ $_GET['search'] }}</span>" ในระบบ</b><br><br><br><br></td>
                </tr>
            @endforelse
            @endif
            
            </table>
        </ul>
    @endif
    </div>
@endsection
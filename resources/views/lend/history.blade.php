@extends('layouts/main')
@section('title','ประวัติการยืม')
@section('content')
    <div class="container">
        <h1><b>&emsp;ประวัติการยืมของ :</b> {{ $name->hw_name }}</h1>
        <table class="table table-bordered text-center" style="border-collapse:separate">
            <tr>
                <th>วันที่ลง</th>
                <th>ผู้ยืม</th>
                <th>เบอร์ติดต่อ</th>
                <th>สถาณที่</th>
                <th>กำหนดเวลา</th>
                <th>เจ้าหน้าที่</th>
                <th>สถานะ</th>
            </tr>
        @forelse ($basket as $item)
            <tr>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->user_id }}</td>
                <td>{{ $item->phone }}</td>
                <td>
                    <p><b>ห้อง : </b>{{ $item->room }}</p> 
                    <hr class="hr2">
                    <p><b>ตึก : </b>{{ $item->building }}</p>
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
                <td style="border:solid 2px {{ ($item->status==1)?'orange':(($item->status==2)?'skyblue':'darkseagreen') }}">
                    {{ ($item->status==1)?'มารับ/ติดตั้ง เรียบร้อย':(($item->status==2)?'จอง':'คืนแล้ว') }}<hr class='hr2'>
                    {{ ($item->with_setup==0)?'มารับด้วยตนเอง':'พร้อมบริการติดตั้ง' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8"><br><br> ไม่มีประวัติของอุปกรณ์นี้ <br><br><br></td>
            </tr>
        @endforelse
            
        </table>
        <div class="width-100 text-center">
            {{ $basket->links() }}
        </div>        
        {{ Html::link(session('urlback'),'ย้อนกลับ',['class'=>'btn btn-danger']) }}
        
    </div>
    <br>
@endsection
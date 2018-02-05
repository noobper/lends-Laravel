@extends('layouts/main')
@section('title','ระบบยืนอุปกรณ์')
@section('content')
<div class="container">
<h1><b>รายการอุปกรณ์ทั้งหมด</b></h1>
<div class="Responsive-tables">
    <table class="table table-bordered table-hover text-center">
    <tr>
        <th>No.</th>
        <th>ชื่ออุปกรณ์</th>
        <th>ประเภท</th>
        <th>รายละเอียด</th>
        <th>เลขครุภัณฑ์</th>
        <th>เพิ่มเมื่อ</th>
        <th></th>
    </tr>
@forelse($hardware as $r)  
        <tr>
            <td>{{ $r['id'] }}</td>
            <td>{{ $r['hardware_name'] }}</td>
            <td>
            @foreach($hw_type as $l)
                @if($l['type_id']==$r['hardware_type'])
                    {{ $l['type_name'] }}
                @endif
            @endforeach
            </td>
            <td>{{ $r['hardware_detail'] }}</td>
            <td>{{ $r['hw_no'] }}</td>
            <td>{{ $r['created_at'] }}</td>
            <td>
                {{ Html::link('allhardware/'.$r['id'].'/show', 'เรียกดู',array('class'=>'btn btn-success','title'=>'เรียกดู'.$r['hardware_name'])) }}
                {{ Html::link('listhardware/'.$r['id'].'/edit', 'แก้ไข',array('class'=>'btn btn-warning','title'=>'แก้ไข'.$r['hardware_name'])) }}
                @if($r['deleted']==0)
                    {{ Html::link('allhardware/'.$r['id'].'/edit', 'ลบ',array('class'=>'btn btn-danger','title'=>'ลบ'.$r['hardware_name'])) }}
                @else
                    {{ Html::link('allhardware/'.$r['id'].'/edit', 'ลบแล้ว',array('class'=>'btn btn-default','title'=>'กู้คืน'.$r['hardware_name'])) }}  
                @endif
            </td>
        </tr>        
@empty
    <tr>
        <td colspan="7"><h1><b>----- /ไม่มีข้อมูลในระบบ/ -----</b></h1> </td>
    </tr>
    {{-- /EMPTY/ --}}    
@endforelse
    </table>
    {{ $hardware->links() }}
</div>
</div>
@endsection
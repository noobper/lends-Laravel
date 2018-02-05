<?php

namespace App\Http\Controllers;

use App\Hardwares;
use App\Hwtypes;
use App\Lends;
use App\Baskets;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Session;

class LendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $style=['css/lend/stlye.css'];
        $aScript =['js/lend/app.js'];

        $hardware=Hardwares::join('hwtypes','hardwares.hw_type','=','hwtypes.id')
        ->select('hardwares.*','hwtypes.type_name')->where('hardwares.deleted','0')
        ->where('status','!=','1')
        ->orderby('hw_name')->orderby('hw_type')->paginate(8);
        $basket = Baskets::join('lends','baskets.lend_id','=','lends.id')
        ->where('lends.status','=','2')->get();

        $data = array(
            'hardware'=> $hardware,
            'basket' => $basket,
            'script' => $aScript,
            'style' => $style
        
        );
        //dd($basket2);
        return view ("lend/hw",$data);
    }

    public function add_hw($values)
    {   
        $hw=Hardwares::find($values);
        session()->put('hw_id.'.$values, $values);
        #session()->push('hw_name', $hw->hw_name);
        return redirect('lend');
    }

    public function del_hw($values)
    {   
        if ($values=='all') {
            session()->forget('hw_id');
        } else {
            Session()->forget('hw_id.'.$values);
        } 
        return redirect('lend');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $style=['css/lend/stlye.css'];
        $hardware=Hardwares::join('hwtypes','hardwares.hw_type','=','hwtypes.id')
        ->select('hardwares.*','hwtypes.type_name')->where('hardwares.deleted','0')
        ->orderby('hw_type','asc')->orderby('hw_name','asc')->get();
        $data=array(
            'hardware'=>$hardware,
            'style' => $style
        );
        return view('lend.formlend',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $this->validate($request,[
            'user_id'=>'required',
            'phone'=>'required',
            'hw_out'=>'date|after:yesterday',
            'hw_back'=>'date|after:yesterday|after_or_equal:hw_out'
        ],[
            'required'=>'กรุณากรอก:attribute',
            'after'=>'กรุณาตรวจสอบวันที่ให้ถูกต้อง',
            'after_or_equal'=>'กรุณาตรวจสอบวันที่ให้ถูกต้อง',
        ],[
            'user_id'=>'ชื่อผู้ยืม',
            'phone'=>'เบอร์ติดต่อ',
        ]); 

        $date_out=$request->hw_out.' '.str_pad($request->hour_out,2,'0',STR_PAD_LEFT).':'.str_pad($request->min_out,2,'0',STR_PAD_LEFT);
        $date_back=$request->hw_back.' '.str_pad($request->hour_back,2,'0',STR_PAD_LEFT).':'.str_pad($request->min_back,2,'0',STR_PAD_LEFT);
        //check 
       
        $check = baskets::select('hw_name','hw_out','hw_back')
                ->join('lends','baskets.lend_id','=','lends.id')
                ->join('hardwares','baskets.hw_id','=','hardwares.id')
                ->wherein('hw_id',session('hw_id'))
                ->where('lends.status','=','2')
                ->where(function ($query) use($date_out,$date_back) {
                    $query->whereBetween('hw_out',[$date_out,$date_back]);
                    $query->orwhereBetween('hw_back',[$date_out,$date_back]);
                    $query->orWhereRaw('? BETWEEN hw_out AND hw_back',$date_out);
                    $query->orWhereRaw('? BETWEEN hw_out AND hw_back',$date_back);
                })
                ->orderby('hw_name')
                ->get() ;
            if (count($check)>0) {

                $report = '1';
            }
        
        if (isset($report)) {
            $style=['css/lend/stlye.css'];
            $hardware=Hardwares::join('hwtypes','hardwares.hw_type','=','hwtypes.id')
            ->select('hardwares.*','hwtypes.type_name')->where('hardwares.deleted','0')
            ->orderby('hw_type','asc')->orderby('hw_name','asc')->get();
            $data=[
                'check' => $check,
                'hardware'=>$hardware,
                'style' => $style
            ];
            return view('lend.formlend',$data);
        }

        //endcheck
        $lend = new Lends;
        $lend->user_id = $request->user_id;
        $lend->phone = $request->phone;
        $lend->with_setup = $request->with_setup;
        $lend->room = (($request->room=='')?'-':$request->room);
        $lend->building = (($request->building=='')?'-':$request->building);
        $lend->status = 2;
        $lend->hw_out = $date_out;
        $lend->hw_back = $date_back;
        if (($request->other)=='') {
            $lend->other = '-';
        } else {
            $lend->other = $request->other;
        }
        $lend->save();
        foreach (session('hw_id') as $ss_hw) {
            $basket = new Baskets;
            $basket->hw_id = $ss_hw;
            $basket->lend_id=$lend->id;
            $basket->save();
            $status = Hardwares::find($ss_hw);
            $status->status = 2;
            $status->save();
        }
        session()->forget('hw_id');
        return redirect('lend/result/'.$lend->id);
    }

    public function result($values)
    {
        $lend=Lends::find($values);        
        $basket=Baskets::join('hardwares','baskets.hw_id','=','hardwares.id')
        ->join('lends','baskets.lend_id','=','lends.id')
        ->where('lend_id',$values)->get();
        $hardware=Hardwares::all();
        $data = [
            'lend' => $lend,
            'basket' => $basket,
            'hardware' => $hardware,
        ];
        return view('lend.result',$data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

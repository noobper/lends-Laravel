<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hardwares;
use App\Hwtypes;
use App\Lends;
use App\Baskets;
use Validator;
use Illuminate\Validation\Rule;
use Session;
use Input;

class LendshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #check status
        $a = Baskets::select('hw_id','status')->join('lends','lend_id','=','lends.id')->where('status','!=','0')->orderby('status','desc')->get();
        foreach ($a as $q) {
            $h = Hardwares::find($q->hw_id);
            $h->status = $q->status;
            $h->save();
        }
        #--end check
        $style=['css/lend/stlye.css'];
        $script=['js/lend/app.js'];
        if ((null!==Input::get('type'))and(null!==Input::get('date'))) {
            $lend = Lends::select('*')->where(Input::get('type'),'like','%'.Input::get('date').'%')->orderby('status','DSC')
                ->orderby('hw_back','DSC')->orderby('hw_out','DSC')->paginate(100);
            $bk = Baskets::join('Hardwares','baskets.hw_id','=','Hardwares.id')->join('Lends','lend_id','=','Lends.id')
                ->where(Input::get('type'),'like','%'.Input::get('date').'%')->orderby('hw_type')->get();
        } else {
            $lend = Lends::select('*')->orderby('status','DSC')->orderby('hw_back','DSC')->orderby('hw_out','DSC')->paginate(10);
            $bk = Baskets::join('Hardwares','baskets.hw_id','=','Hardwares.id')->get();
        }
        session()->forget('hw_edit');
        $data = [
            'lend' => $lend,
            'basket' => $bk,
            'style' => $style,
            'script' => $script,
        ];
        return view ('lend/lendshow',$data);
    }

    public function add_hw($values)
    {   
        $hw=Hardwares::find($values);
        session()->put('hw_edit.'.$values, $values);
        return redirect()->back();
    }

    public function del_hw($values)
    {   
        Session()->forget('hw_edit.'.$values);
        return redirect()->back();
        
    }


    public function out($values)
    {
        $lend = Lends::find($values);
        $lend->staff_send = Input::get('staff');
        $lend->staff_send_time = now();
        $lend->status = 1;
        $lend->save();
        $basket = Baskets::where('lend_id','=',$values)->get();
        foreach ($basket as $q) {
            $status = Hardwares::find($q->hw_id);
            $status->status = 1;
            $status->save();
        }

        return redirect()->back();
    }

    public function back($values)
    {
        $lend = Lends::find($values);
        $lend->staff_receive = Input::get('staff');
        $lend->staff_receive_time = now();
        $lend->status = 0;
        $lend->save();
        $basket = Baskets::where('lend_id','=',$values)->get();
        foreach ($basket as $q) {
            $status = Hardwares::find($q->hw_id);
            $status->status = 0;
            $status->save();
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $style=['css/lend/stlye.css'];
        $script=['js/lend/app.js'];
        $lend = Lends::find($id);
        $bk = Baskets::where('Lend_id','=',$id)->join('Hardwares','baskets.hw_id','=','Hardwares.id')->get();
        $hardware= Hardwares::all();
        $data = [
            'lend' => $lend,
            'bk' => $bk,
            'hardware' => $hardware,
            'style' => $style,
            'script' => $script,
        ];
        return view ('lend/lendedit',$data);
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
        $date_out=$request->hw_out.' '.$request->hw_out_2;
        
        $date_back=$request->hw_back.' '.$request->hw_back_2;
        $lend = Lends::find($id);
        $lend->user_id = $request->user_id;
        $lend->phone = $request->phone;
        $lend->room = (($request->room=='')?'-':$request->room);
        $lend->building = (($request->building=='')?'-':$request->building);
        $lend->hw_out = $date_out;
        $lend->hw_back = $date_back;        
        $lend->other = ($request->other == '')?'-':$request->other;
        $lend->status = $request->status;
        $lend->staff_send_time = (isset($lend->staff_send))?$lend->staff_send_time:now();
        $lend->staff_receive_time = (isset($lend->staff_receive))?$lend->staff_receive_time:now();
        $lend->staff_send = $request->staff_send;
        $lend->staff_receive = $request->staff_receive;
        $lend->save();
        $basket = Baskets::select('*')->where('lend_id',$id)->get();
        foreach ($basket as $bk) {
            if(array_search($bk->hw_id,session('hw_edit'))==false){
                $status = Hardwares::find($bk->hw_id);
                $status->status = 0;
                $status->save();
                $bk->delete();
            }else{
                $status = Hardwares::find($bk->hw_id);
                $status->status = $request->status;
                $status->save();
                session()->forget('hw_edit.'.$bk->hw_id);
            }
        }
        if (session('hw_edit')) {
            foreach (session('hw_edit') as $ss_hw) {
                $addbk = new Baskets;
                $addbk->hw_id = $ss_hw;
                $addbk->lend_id = $id;
                $addbk->save(); 
                $status = Hardwares::find($ss_hw);
                $status->status = $request->status;
                $status->save();
            }
        }
        return redirect('/lendshow/?page='.session('urlback'));
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lend = Lends::find($id);
        $lend->delete();
        return redirect('/lendshow/');
    }
}

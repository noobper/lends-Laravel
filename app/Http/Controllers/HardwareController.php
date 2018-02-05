<?php

namespace App\Http\Controllers;

use App\Hardwares;
use App\Hwtypes;
use App\Baskets;
use App\Lends;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Session;
use Input;

class HardwareController extends Controller
{

    public function history($values)
    {
        $basket = Baskets::join('Lends','lend_id','=','Lends.id')
                ->join('Hardwares','hw_id','Hardwares.id')
                ->select('lends.*','hardwares.hw_name','baskets.id')
                ->where('hw_id','=',$values)->orderby('lends.status','DSC')
                ->orderby('lends.hw_out','DSC')
                ->orderby('lends.hw_back','DSC')->paginate(10);
        $name = Hardwares::find($values);
        $data = [
            'basket' => $basket,
            'name' => $name,
        ];
        return view('/lend/history',$data);
    }

    public function status($values)
    {
        $hardware = Hardwares::join('Hwtypes','hw_type','=','Hwtypes.id')
        ->select('Hardwares.*','Hwtypes.type_name')
        ->where('status',$values)->orderby('hw_name')
        ->paginate(10);        
        $hw_type = Hwtypes::where('deleted','=',0)->get();
        $data = array(
            'hw_type' => $hw_type,
            'hardware'=> $hardware,
        );
        return view ("lend/listhw",$data);
    }

    public function harddel($values)
    {
        $del = Hardwares::find($values);
        $del->delete();
        return redirect('/hardware');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $basket = baskets::join('lends','lend_id','=','lends.id')->where('status','!=','0')->orderby('status')->get();
        $hardware = Hardwares::leftjoin('Hwtypes','hw_type','=','Hwtypes.id')->select('Hardwares.*','Hwtypes.type_name')
        ->orderby('type_name')->orderby('hw_name')->paginate(10);
        $hw_type = Hwtypes::where('deleted','=',0)->get();
        $hwjs = ['js/lend/app.js'];
        //dd($basket);

        $data = array(
            'basket' => $basket,
            'hw_type' => $hw_type,
            'hardware'=> $hardware,
            'script' => $hwjs
        );
        return view ("lend/listhw",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hw_type = Hwtypes::where('deleted','=',0)->get();
        $data = array(
            'hw_type' => $hw_type,
        );
        return view("lend/formhw",$data);
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
            'hw_name'=>'required|max:100|unique:Hardwares',
            'hw_name'=>'required',
            'hw_detail'=>'required',
            'hw_no'=>'max:100|unique:Hardwares',
        ]);
        $hw = new Hardwares;
        $hw->hw_name = $request->hw_name;
        $hw->hw_type = $request->hw_type;
        $hw->hw_detail = $request->hw_detail;
        $hw->status = 0;
        $hw->hw_no = (isset($request->hw_no)) ? $request->hw_no : '-' ;
        //$hw->hw_no = $request->hw_no;
        $hw->save();
        //Session::flash('message','Success Create New Hardware');
        return redirect('hardware');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hardwares  $hardwares
     * @return \Illuminate\Http\Response
     */
    public function show(Hardwares $hardwares,$id)
    {
        $hardware = Hardwares::join('Hwtypes','hw_type','=','Hwtypes.id')
            ->select('Hardwares.*','Hwtypes.type_name')
            ->where('hw_type',$id)->orderby('hw_name')->orderby('status','desc')
            ->paginate(10);        
        $hw_type = Hwtypes::where('deleted','=',0)->get();
        $data = array(
            'hw_type' => $hw_type,
            'hardware'=> $hardware,
        );
        return view ("lend/listhw",$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hardwares  $hardwares
     * @return \Illuminate\Http\Response
     */
    public function edit(Hardwares $hardwares,$id)
    {
        $hw_type = Hwtypes::where('deleted','=',0)->get();
        $edithw = Hardwares::find($id);
        $data = array(
            'hw_type' => $hw_type,
            'edithw' => $edithw
        );
        return view("lend/formhw",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hardwares  $hardwares
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hardwares $hardwares,$id)
    {
        $this->validate($request,[
            'hw_name'=>'required|max:100',
            'hw_name'=>'required',
            'hw_detail'=>'required',
            'hw_no'=>'max:100',
        ]);
        $hw = Hardwares::find($id);
        $hw->hw_name = $request->hw_name;
        $hw->hw_type = $request->hw_type;
        $hw->hw_detail = $request->hw_detail;
        $hw->hw_no = (isset($request->hw_no)) ? $request->hw_no : '-' ;
        //$hw->hw_no = $request->hw_no;
        $hw->save();
        //Session::flash('message','Success Update Hardware');
        return redirect('hardware');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hardwares  $hardwares
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hwtypes $Hwtypes,$id,Request $request)
    {
        $del=hardwares::find($id);
        $del->deleted = $request->softdel;
        $del->save();
        //Session::flash('message', 'Deleted complete!');
        return redirect('hardware');
    }
}

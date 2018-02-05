<?php

namespace App\Http\Controllers;

use App\Hwtypes;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Session;

class HwtypeController extends Controller
{

    public function harddel($values)
    {
        $del = Hwtypes::find($values);
        $del->delete();
        return redirect ('/hardwaretype');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hw_type = Hwtypes::select('*')->paginate(10);
        $data = array(
            'hw_type' => $hw_type,
        );
        return view ("lend/listtype",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request,[
            'type_name'=>'required|max:100|unique:Hwtypes',
        ]);
        $hw_type = new Hwtypes;
        $hw_type->type_name = $request->type_name;

        $hw_type->save();

        Session::flash('message','Success Create New Hardware Type');
        return redirect('hardwaretype');
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
     * @param  \App\Hwtypes  $Hwtypes
     * @return \Illuminate\Http\Response
     */
    public function show(Hwtypes $Hwtypes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hwtypes  $Hwtypes
     * @return \Illuminate\Http\Response
     */
    public function edit(Hwtypes $Hwtypes)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hwtypes  $Hwtypes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'type_name'=>'required|max:100|unique:Hwtypes'
        ]);
        $hw = Hwtypes::find($id);

        $hw->type_name = $request->type_name;
        $hw->save();

        Session::flash('message','Update complete!');
        return redirect('hardwaretype');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hwtypes  $Hwtypes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hwtypes $Hwtypes,$id,Request $request)
    {
        $del=Hwtypes::find($id);
        $del->deleted = $request->softdel;
        $del->save();
        Session::flash('message', 'Deleted complete!');
        return redirect('hardwaretype');
    }

    
}

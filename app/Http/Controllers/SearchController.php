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

class SearchController extends Controller
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
        
        $from = Input::get('from');
        $search = Input::get('search');
        if (isset($search)) {
            if ($from=='อุปกรณ์') {
                $result = Hardwares::join('Hwtypes','hw_type','=','Hwtypes.id')
                        ->select('Hardwares.*','Hwtypes.type_name')
                        ->where('Hardwares.deleted','=','0')
                        ->where(function($q){
                            $search = Input::get('search');
                            $q  ->orwhere('hw_name','like','%'.$search.'%')
                                ->orwhere('hw_detail','like','%'.$search.'%')
                                ->orwhere('type_name','like','%'.$search.'%')
                                ->orwhere('hw_no','like','%'.$search.'%');
                        })                        
                        ->paginate(10);
            }       
            elseif ($from=='ผู้ยืม') {
                $result = Lends::select('*')
                        ->where('user_id','like','%'.$search.'%')
                        ->orwhere('phone','like','%'.$search.'%')
                        ->orwhere('room','like','%'.$search.'%')
                        ->orwhere('building','like','%'.$search.'%')
                        ->orwhere('other','like','%'.$search.'%')
                        ->orderby('status','DSC')->paginate(10);
                $basket = Baskets::join('Hardwares','hw_id','=','hardwares.id')
                        ->select('hardwares.*','Baskets.hw_id','Baskets.lend_id')
                        ->get();
            }
        }
        //$hardware = Hardwares::where('hw_name','like',$values)->orwhere('hw_type','like',$values)->paginate('10');
        $style = ['css/lend/stlye.css'];
        $data = [
        'style' => $style,
        //'hardware' => $hardware,
        'result' => (isset($result))?$result:'',
        'basket' => (isset($basket))?$basket:'',
        'from' => (isset($from))?$from:'',
    ];

    return view ("lend/search",$data);
    }

    public function hardware($values)
    {
        $hw_type = Hwtypes::where('deleted','=',0)->get();
        $edithw = Hardwares::find($values);
        $data = array(
            'hw_type' => $hw_type,
            'edithw' => $edithw
        );
        return view("lend/formhw",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $basket = Baskets::join('Lends','lend_id','=','Lends.id')
                ->join('Hardwares','hw_id','Hardwares.id')
                ->select('lends.*','hardwares.hw_name','baskets.id')
                ->where('hw_id','=',$id)->orderby('lends.status','DSC')
                ->orderby('lends.hw_out','DSC')
                ->orderby('lends.hw_back','DSC')->paginate(10);
        $name = Hardwares::find($id);
        $data = [
            'basket' => $basket,
            'name' => $name,
        ];
        return view('/lend/history',$data);
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

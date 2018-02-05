<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Lends;
use App\Baskets;
use App\Hardwares;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
class EventController extends Controller
{
       public function index()
            {
                $events = [];
                $data = Lends::all();
                /*$data = Baskets::join('lends','lend_id','=','lends.id')
                        ->join('hardwares','hw_id','=','hardwares.id')
                        //->where('hw_id','2')
                        ->get();*/
                if($data->count()) {
                    foreach ($data as $key => $value) { 
                    
                        $out = explode(' ',$value->hw_out);
                        $back = explode(' ',$value->hw_back);
                        $events[] = Calendar::event(
                            $value->user_id.' '.$value->room.' '.$value->building,
                            false,
                            new \DateTime($out[0].$out[1]),
                            new \DateTime($value->hw_back),
                            null,
                            // Add color and link on event
                         [
                             'color' => '#337ab7',
                             'url' => '/events/detail/'.$value->id,
                         ]
                        );
                    }
                }
                $calendar = Calendar::addEvents($events)
                ->setOptions([ //set fullcalendar options
                    'nowIndicator' => 'true',
                    'timeFormat' => 'h:mm',
                    'contentHeight' => 600,
                ])->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
                    //'viewRender' => 'function() {alert("Callbacks!");}'
                ]);
                $hardware = hardwares::all();
                $intel = [
                    'hardware' => $hardware,
                ];
                return view('/lend/calendar', compact('calendar'),$intel);
            }
    public function show($id) 
    {
        $events = [];
               
                $data = Baskets::join('lends','lend_id','=','lends.id')
                    ->where('hw_id',$id)
                    ->get();
                if($data->count()) {
                    foreach ($data as $key => $value) { 
                    
                        $out = explode(' ',$value->hw_out);
                        $back = explode(' ',$value->hw_back);
                        $events[] = Calendar::event(
                            $value->user_id.' '.$value->room.' '.$value->building,
                            false,
                            new \DateTime($out[0].$out[1]),
                            new \DateTime($value->hw_back),
                            null,
                            // Add color and link on event
                         [
                             'color' => '#337ab7',
                             'url' => '/events/detail/'.$value->lend_id,
                         ]
                        );
                    }
                }
                $calendar = Calendar::addEvents($events)
                ->setOptions([ //set fullcalendar options
                    'nowIndicator' => 'true',
                    'timeFormat' => 'h:mm',
                ])->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
                    //'viewRender' => 'function() {alert("Callbacks!");}'
                ]);
                
                $hardware = hardwares::orderby('hw_type')->orderby('hw_name')->get();
                $name= $data->first()->hw_name;
                $data= [
                    'name' => $name,
                    'hardware' => $hardware,
                ];
                return view('/lend/calendar', compact('calendar'),$data);
    }

    
    public function detail($id)
    {

        $hardware = Hardwares::join('hwtypes','hw_type','=','hwtypes.id')->get();
        $lend = Lends::find($id);
        $detail = Baskets::join('hardwares','hw_id','=','hardwares.id')->
            where('lend_id',$id)->get();
        $data = [
            'lend' => $lend,
            'detail' => $detail,
        ];
        return view('/lend/calendardetail',$data);

    }

}
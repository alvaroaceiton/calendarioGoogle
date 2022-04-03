<?php

namespace App\Http\Controllers;
use App\Services\SocialUserResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ApiCalendarController extends Controller
{
    private $calendar;
    public function __construct(Google_Client $client)
    {
    
        $this->middleware(function ($request, $next) use ($client) {
            //dd(Auth::user()->expires_in);
            //$client->refreshToken(Auth::user()->refresh_token);
            $client->setAccessToken(
                [
                    'access_token' => Cache::get('tgoogle') ,
                    'expires_in' => 35980, // Google default
                    'created' => 35980,
                ]
            );
            //$client->setExpiresIn(Auth::user()->expires_in);

            //dd(Auth::user());
            //dd($client);
            if($client->isAccessTokenExpired()){
                //dd(Auth::user());
                $client->refreshToken(
                    [
                        'refresh_token' =>  Auth::user()->refresh_token
                    ]
                );
            }
            $this->calendar = new Google_Service_Calendar($client);
        
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = $this->calendar->events->listEvents('primary');
        $data = array();
        foreach ($events->getItems() as $key => $event) 
        {       
            $data[] = $this->formatData($event);
        }
        return response()->json($data, 200);
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

        $rules = ["summary" => 'required'];
        $mesagges = ["required" => "El campo :attribute es requerido" ];
        $validator = Validator::make($request->all(), $rules, $mesagges);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $sumary = $request->get("summary");
        //dd($sumary);
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $sumary,
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
              'dateTime' => '2022-03-26T09:03:00-07:00',
              'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
              'dateTime' => '2022-03-27T17:03:00-07:00',
              'timeZone' => 'America/Los_Angeles',
            ),
            'recurrence' => array(
              'RRULE:FREQ=DAILY;COUNT=2'
            ),
            'attendees' => array(
              array('email' => 'lpage@example.com'),
              array('email' => 'sbrin@example.com'),
            ),
            'reminders' => array(
              'useDefault' => FALSE,
              'overrides' => array(
                array('method' => 'email', 'minutes' => 24 * 60),
                array('method' => 'popup', 'minutes' => 10),
              ),
            ),
          ));

        $calendarId = 'primary';
        $event = $this->calendar->events->insert($calendarId, $event);
        return response()->json(array('id' => $event->getId()), 201);
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
        if(empty($id))
            return response()->json("Se requiere un ID"); 
        $event = $this->calendar->events->get('primary', $id);
        //dd($event);
        $data = $this->formatData($event);
        return response()->json($data, 200);
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
        if(empty($id))
            return response()->json("Se requiere un ID");
        $event = $this->calendar->events->get('primary', $id);
        if(!$event)
            return response()->json("Se requiere un ID valido");
        $rules = ["summary" => 'required'];
        $mesagges = ["required" => "El campo :attribute es requerido" ];
        $validator = Validator::make($request->all(), $rules, $mesagges);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $event->setSummary($request->get('summary'));
        //dd($event);
        $updatedEvent = $this->calendar->events->update('primary', $event->getId(), $event);
        $data = array("status" => true);
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(empty($id))
            return response()->json("Se requiere un ID"); 
        $this->calendar->events->delete('primary', $id);
        $data = array("status" => true);
        return response()->json($data, 200);
    }

    public function formatData($event){
        $data = array(
            "id" => $event->getId(),
            "name" => $event->getSummary(),
            "summary" => $event->getSummary(),
            "fecha_start" => date("Y-m-d H:i:s",strtotime($event->getStart()->getDateTime())),
            "fecha_end" => date("Y-m-d H:i:s",strtotime($event->getEnd()->getDateTime()))
        );
        return $data;
    }

}

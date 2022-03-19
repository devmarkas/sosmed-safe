<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Schedule;
use App\Models\User;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $schedule = Schedule::with('user')->where('user_id', Auth::user()->id)->get();
            
            return response()
            ->json([
                "message" => "Schedule " . Auth::user()->name,
                "data" => $schedule
            ]);
          
        }
        
        catch (\Exception $error)
        {
            return response()
            ->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            );
        } 
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
        try
        {
            $validator = Validator::make($request->all(),
            [
                'is_active'  => 'required|boolean',
                'title'      => 'required|string|max:255',
                'start_time' => 'required|string|max:255',
                'end_time'   => 'required|string|max:255',
               
            ]);

            if($validator->fails())
            {
                return response()->json($validator->errors());       
            }

            $schedule = Schedule::create(
                [
                    'user_id' => Auth::user()->id,
                    'is_active' => $request->is_active,
                    'title' => $request->title,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                ]
            );

            return response()
                ->json(
                    [
                        'message'   => 'Schedule ' . Auth::user()->name . ' berhasil dibuat',
                        'data' => $schedule,
                    ],
                200);

        }
        catch (\Exception $error)
        {
            return response()
            ->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            500);
        }  
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
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
        try
        {
            $schedule = Schedule::find($id);

            $validator = $request->validate(
                [
                    'is_active'  => 'boolean',
                    'title'      => 'string|max:255',
                    'start_time' => 'string|max:255',
                    'end_time'   => 'string|max:255',
                ]
            );

            $schedule->update($validator);

            return response()
                ->json(
                    [
                        'message' => 'schedule ' .Auth::user()->name.  ' berhasil di update',
                        'data' => $schedule,
                    ],
                200);

        }
        catch (\Exception $error)
        {
            return response()
            ->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            500);
        } 
    }

    /**
     * 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy(Schedule $id)
    {
        try
        {
            $id->delete();
            
            return response()
            ->json([
                "success" => true,
                "message" => "Schedule " . $id->title . " deleted successfully.",
                "data" => $id
            ]);
          
        }
        
        catch (\Exception $error)
        {
            return response()
            ->json(
                [
                    'message'   => 'Err',
                    'errors'    => $error->getMessage(),
                ],
            );
        } 
        
    }

    public function send(Request $request)
    {
        return $this->sendNotification($request->fcm_token, array(
          "title" => "Sample Message", 
          "body" => "This is Test message body"
        ));
    }

    public function sendNotification($fcm_token, $message)
    {
        $SERVER_API_KEY = 'AAAAN2Yj2CM:APA91bFbmIw3wMSKRSt8QWmet9dG9-GNBdZVoE0SbcSfM6DYLMWLoGLDS2zz3SgTgHIqeVRcj8B__Mgvy7-PXZkgjvspHQ9eer9pE_8_uyXKcGJ3B7JHEda8d8Yqx3I9P39-wK99lcCo	';
  
        // payload data, it will vary according to requirement
        $data = [
            "to" => $fcm_token, // for single device id
            "data" => $message
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
      
        curl_close($ch);
      
        return $response;
    }
    
  
}

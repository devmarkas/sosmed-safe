<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;

class NotifsendController extends Controller
{
    public static function sendnotifstart()
    {
        $data = Schedule::with(['user'])->where('is_active', 1)
            ->where('start_time', Carbon::now()->isoFormat('HH:mm'))
            ->get();

            foreach ($data as $key => $value) {
            # code...


            // $SERVER_API_KEY = env('FCM_SERVER_KEY');
            $SERVER_API_KEY = "AAAA1EmfY8M:APA91bHVoS-DFrLj2jfSvjVquXzr_hbGrOosZm3HeNne4FW5fbMTp-DbJi-ymxKPizq4pVQOo-6Z4plS-a3pDTJuKW1YEBnwPNgKFQYZNKUk_iNKJ0aLGhUMtPi3Cr_8TVLRTjPZZZpx";

            $data = [
                "to" => $value->user->fcm_token,
                "notification" => [
                    "title" => 'Waktu ' . $value->title . ' di mulai',
                    "body" => $value->start_time,
                ],
                "data" => [
                    "title" => $value->title,
                    "body" => $value->start_time,
                    "notification_title" => 'Waktu ' . $value->title . ' di mulai',
                ],
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

            dd($response);
        }
    }
    public static function sendnotifend()
    {
        $data = Schedule::with(['user'])->where('is_active', 1)
            ->where('end_time', Carbon::now()->isoFormat('HH:mm'))
            ->get();
        // dd($data);
        // dd(Carbon::now()->isoFormat('HH:mm') == Carbon::now()->isoFormat('HH:mm'));

        foreach ($data as $key => $value) {
            # code...


            // $SERVER_API_KEY = env('FCM_SERVER_KEY');
            $SERVER_API_KEY = "AAAA1EmfY8M:APA91bHVoS-DFrLj2jfSvjVquXzr_hbGrOosZm3HeNne4FW5fbMTp-DbJi-ymxKPizq4pVQOo-6Z4plS-a3pDTJuKW1YEBnwPNgKFQYZNKUk_iNKJ0aLGhUMtPi3Cr_8TVLRTjPZZZpx";


            $data = [
                "to" => $value->user->fcm_token,
                "notification" => [
                    "title" => 'Waktu ' . $value->title . ' telah selesai',
                    "body" => $value->end_time,
                ],
                "data" => [
                    "title" => $value->title,
                    "body" => $value->end_time,
                    "notification_title" => 'Waktu ' . $value->title . ' telah selesai',
                ],
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

            dd($response);
        }
    }
}

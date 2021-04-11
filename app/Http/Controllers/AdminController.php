<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Meeting;

class AdminController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();
        $noZoomCode = $user->zoom_code == null;
        $zoomOuthLink = 'https://zoom.us/oauth/authorize?'.http_build_query([
            'response_type'=>'code',
            'redirect_uri'=>env('APP_URL').'/zoomoauth/check',
            'client_id'=>env('ZOOM_CLIENT_ID'),
        ]);
        $oauthSuccess=false;
        $meetings = Meeting::all();

        return view('admin',compact('noZoomCode','zoomOuthLink','oauthSuccess','meetings'));
    }

    public function zoomOauth(Request $request){
        $user = auth()->user();

        if($user->zoom_code==null){
            $code = $request['code'];
    
            $user->zoom_code = $code;
            $user->save();
    
            $basic = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
            $client = new \GuzzleHttp\Client([
                'headers' => ['Authorization' => 'Basic '.$basic]
            ]);
            $res = $client->request('POST','https://zoom.us/oauth/token',[
                'query' => [
                    'grant_type'=>'authorization_code',
                    'code'=>$code,
                    'redirect_uri'=>'http://localhost:8000/zoomoauth/check'
                ]
            ]);
            $result = json_decode($res->getBody()->getContents());

            $user->access_token= $result->access_token;
            $user->refresh_token= $result->refresh_token;
            $unixTime = time();
            $user->zoom_expires_in= date("Y-m-d H:i:s",$unixTime+$result->expires_in);
            $user->save();

            return redirect()->route('amdin')->with([
                'noZoomCode'=>false,
                'oauthSuccess'=>true
            ]);
        }
    }

    public function getOauth(Request $request){
        $user = auth()->user();
        $user->access_token= $request->access_token;
        $user->refresh_token= $request->access_token;
        $unixTime = time();
        $user->zoom_expires_in= date("Y-m-d H:i:s",$unixTime+$request->expires_in);
        $user->save();
        return redirect()->route('admin');
    }
}

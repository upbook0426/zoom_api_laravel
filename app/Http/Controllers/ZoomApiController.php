<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Meeting;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ZoomApiController extends Controller
{
    //
    protected function me(){
        $user = auth()->user();
        $client = new \GuzzleHttp\Client([
            'headers' => ['Authorization' => 'Bearer '.$user->access_token]
        ]);
        $res = $client->request('GET','https://api.zoom.us/v2/users/me');
        $result = json_decode($res->getBody()->getContents());
        // dd($result);
        return $result;
    }

    protected function checkRefresh(){
        $user = auth()->user();
        $token_expires =  new \DateTime($user->zoom_expires_in);
        $now = new \DateTime();

        if($now >= $token_expires){
            $basic = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
            $client = new \GuzzleHttp\Client([
                'headers' => ['Authorization' => 'Basic '.$basic]
            ]);
            $res = $client->request('POST','https://zoom.us/oauth/token',[
                'query' => [
                    'grant_type'=>'refresh_token',
                    'refresh_token'=>$user->refresh_token
                ]
            ]);
            $result = json_decode($res->getBody()->getContents());

            $user->access_token= $result->access_token;
            $user->refresh_token= $result->access_token;
            $unixTime = time();
            $user->zoom_expires_in= date("Y-m-d H:i:s",$unixTime+$result->expires_in);
            $user->save();
            return $user;
        }
        return $user;
    }

    public function createMeeting(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email:rfc',
            'yourname'=>'required',
            'companyname'=>'required',
            'startAt'=>'date|required',
            'content'=>'required|max:1000',
        ]);

        $error = $validator->getMessageBag()->toArray();

        if ($validator->fails()) {
            return view('form',compact('error'));
        }
        
        $topic = $request->companyname.' '.$request->yourname.'様 ご相談';

        // $user = $this->checkRefresh();
        $user = auth()->user();

        $zoom_user = $this->me();

        $url = 'https://api.zoom.us/v2/users/'.$zoom_user->id.'/meetings';
        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Authorization' => 'Bearer '.$user->access_token,
                'Content-Type'=>'application/json'
            ],
        ]);

        $meeting_password = substr(base_convert(bin2hex(openssl_random_pseudo_bytes(9)),16,36),0,9);
        $res = $client->request('POST',$url,[
            \GuzzleHttp\RequestOptions::JSON => [
                'topic'=>$topic,
                'type'=>2,
                'start_time'=>$request->startAt,
                'password'=>$meeting_password
            ]
        ]);
        $result = json_decode($res->getBody()->getContents());

        $meeting = new Meeting();
        $meeting->name=$request->yourname;
        $meeting->company_name=$request->companyname;
        $meeting->email=$request->email;
        $meeting->content=$request->content;
        
        $start = new \DateTime($result->start_time);
        $meeting->start_at=$start;
        $meeting->hash=substr(base_convert(bin2hex(openssl_random_pseudo_bytes(64)),16,36),0,64);
        $meeting->is_canceled=false;

        $meeting->zoom_meeting_id=$result->id;
        $meeting->zoom_join_url=$result->join_url;
        $meeting->zoom_start_url=$result->start_url;
        $meeting->zoom_password=$result->password;
        $meeting->save();

        $format = $start->format('Y年m月d日 H時i分');
        // $meeting->start_at = $format;
        // $mail = new ContactMail($meeting);
        // Mail::to($request->email)->send($mail);


        return redirect('/confirm')->with([
            'form_id'=>$meeting->id,
            'name'=>$request->yourname,
            'companyname'=>$request->companyname,
            'content'=>$request->content,
            'start_time'=>$format
        ]);
    }

    public function change(Request $request){
        
        if(isset($request['hash'])){
            $hash = $request['hash'];
            $meeting = Meeting::where('hash',$hash)->first();

            if(!empty($meeting)) return view('change');
        }

        return redirect()->route('base');
    }

    public function deleteConfirm(Request $request){
        
        if(isset($request['hash'])){
            $hash = $request['hash'];
            $meeting = Meeting::where('hash',$hash)->first();

            if(!empty($meeting)) return view('delete');
        }

        return redirect()->route('base');
    }

    public function deleteComplete(Request $request){
        
        if(isset($request['hash'])){
            $hash = $request['hash'];
            $meeting = Meeting::where('hash',$hash)->first();

            if(!empty($meeting)){
                $meeting->is_canceled = true;
                $meeting->save();
                return redirect()->route('deletecomplete');
            }
        }

        return redirect()->route('base');
    }
}

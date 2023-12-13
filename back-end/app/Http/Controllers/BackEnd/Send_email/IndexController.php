<?php

namespace App\Http\Controllers\Admin\Send_email;


use App\City;
use App\Country;
use App\District;
use App\Driver;
use App\Driver_districts;
use App\ModulesConst\UserOnlineStatus;
use App\ModulesConst\UserPaidTyps;
use App\ModulesConst\UserTyps;
use Illuminate\Support\Facades\Notification;
use App\Mail\SendEmail;
use App\User;
use Mail;
use App\User_notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\RequiredIf;
class IndexController extends Controller
{
    // public function index()
    // {
    //     $items = Notification::orderBy('id', 'desc')->get();
    //     return view('admin.notifications.index', compact('items'));
    // }
    public function create()
    {
        return view('admin.Send_email.create');

    }


    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'all' =>  new RequiredIf(!$request->has("select_all")),

        ]);
        
        
            
        // return $request->all();
       
        $this->clientsTypeHandler($request);
        //dd($request);
        // return $return;
        session()->flash('success', trans('language.SendNotifMessage'));
        return back();
    }

    public function clientsTypeHandler($request)
    {
        
        //onlineClients
        if ($request->client_type == "users") {
            if($request->has("select_all")){
                $users = User::where('user_type_id', UserTyps::user)->whereNotNull('email')->get();
                // $return = "$users";
                $this->clientsHandler($users, $request);
            }else{
                $users = User::where('user_type_id', UserTyps::user)->whereNotNull('email')->wherein('id',$request->all)->get();
                // $return="$users";
                 $this->clientsHandler($users, $request);
            
            }
            
        }


        if ($request->client_type == "alluser") {
            if($request->has("select_all")){
                $users = User::where('user_type_id', UserTyps::garage)->whereNotNull('email')->get();
                // $return="$users";

                 $this->clientsHandler($users, $request);
            }else{
                $users = User::where('user_type_id', UserTyps::garage)->whereNotNull('email')->wherein('id',$request->all)->get();
                
                // $return="$users";
                 $this->clientsHandler($users, $request);
            
            }

        }
        
        
        // dd( $return);

    }

    public function clientsHandler($users, $request)
    {
       
        if (count($users) > 0) {
            foreach($users as $user){
                 $usermail =$user->email;
                     Mail::to($usermail)->send(new SendEmail($request->body,$user->name));
                
            }
        }
        

    }


    
}

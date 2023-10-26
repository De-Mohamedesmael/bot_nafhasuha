<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use function App\CPU\translate;


class AuthController extends ApiController
{
    protected $commonUtil;
    protected $TransactionUtil;

    public function __construct(Util $commonUtil, TransactionUtil $trnUit)
    {
        $this->TransactionUtil = $trnUit;
        $this->commonUtil = $commonUtil;
        $this->middleware('auth.guard:api', ['except' => ['login', 'register', 'forgotPassword', 'checkPhone','checkCode', 'SendCode','customRemoveAccount','ActiveRemoveAccount']]);
    }


    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $user= User::where('phone',$request->phone)->first();

        if(!$user){
            return responseApiFalse(405, translate('Not found user'));
        }
        if (!$token=auth()->attempt($validator->validated())){
            $token = auth()->attempt(['phone'=>$request->phone,'password'=>$request->password]);
        }



        if (!$token){
            return responseApiFalse(500, translate('The password is incorrect'));
        }
//        if(! auth()->user()->is_activation()){
//            return responseApiFalse(500, translate('user Not activation'));
//
//        }
//        \Settings::set('InvitationBonusValue', 5);
//        \Settings::set('JoiningBonusValue', 5);
        $user=auth()->user();
        if(!$user->invite_code){
            $user-> generateInviteCode();
        }
        return responseApi(200, translate('user login'), $this->createNewToken($token));
    }

    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null ,//auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user())
        ];
    }

    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|between:2,200',
            'phone' => 'required|string|max:20|unique:users',
            'email' => 'nullable|string|max:20|unique:users',
            'invitation_code' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'long' => 'nullable|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'area_id' => 'nullable|integer|exists:areas,id',
            'password' => 'required|string|min:4|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();
            $inputs = $request->except('invitation_code');
            $user = User::create($inputs);
            $user-> generateInviteCode();
            $this->commonUtil->SendActivationCode($user, 'Activation');


            if ($request->has('invitation_code')){
                $this->TransactionUtil->SaveInviteCode( $user ,$request->invitation_code);
            }

//            if (){
//                $this->TransactionUtil->SaveInviteCode( $user ,$request->invitation_code);
//            }

            $data= $this->createNewToken(auth()->attempt($request->only(['phone', 'password'])));
            DB::commit();
            return responseApi(200, translate('user registered'),$data);
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    public function logout()
    {

        auth()->logout();
        return responseApi(200, translate('user logout'));
    }

    public function refresh()
    {
        return responseApi(200, translate('user login'), $this->createNewToken(auth()->refresh()));
    }

    public function userProfile()
    {
        $data =  ['user' => new UserResource(auth()->user())];

        return responseApi(200, translate('get_data_success'), $data );
    }
    public function uploadImage(Request $request)
    {
        $validator = validator($request->all(), [
            'image' => 'required|Image|mimes:jpeg,jpg,png,gif',//|max:10000',
        ]);
        $user=auth()->user();
        if ($validator->fails())return responseApiFalse(405, $validator->errors()->first());
//        $user=Provider::whereId(4)->first();
        $image = $user->getFirstMedia('images');

        if($image){
            $image->delete();
        }
        $uploadedFile = $request->file('image');
        $extension = $uploadedFile->getClientOriginalExtension();
        $user->addMedia($uploadedFile)
            ->usingFileName(time().'.'.$extension)
            ->toMediaCollection('images');
        $data =  ['user' => new UserResource(User::find($user->id))];
        return responseApi(200, translate('user profile update'), $data);
    }

    public function editProfile(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'nullable|string|email|max:100|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|string|max:20|unique:users,phone,' . auth()->id(),
            'address' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lang' => 'nullable|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'area_id' => 'nullable|integer|exists:areas,id',
            'image' => 'nullable|Image|mimes:jpeg,jpg,png,gif',
        ]);
        $user=auth()->user();
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        auth()->user()->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'address'=>$request->address,
            'lat'=>$request->lat,
            'lang'=>$request->lang,
            'country_id'=>$request->country_id,
            'city_id'=>$request->city_id,
            'area_id'=>$request->area_id,
        ]);

        if($request->has('image')){
            $image = $user->getFirstMedia('images');

            if($image){
                $image->delete();
            }
            $uploadedFile = $request->file('image');
            $extension = $uploadedFile->getClientOriginalExtension();
            $user->addMedia($uploadedFile)
                ->usingFileName(time().'.'.$extension)
                ->toMediaCollection('images');
        }

        $data =  ['user' => new UserResource(User::find($user->id))];

        return responseApi(200, translate('user profile update'), $data);
    }


    public function changePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'old_password' => 'required|string|min:4|max:255',
            'new_password' => 'required|string|min:4|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        if (Hash::check($request->old_password, auth()->user()->getAuthPassword())) {
            auth()->user()->update(['password' => $request->new_password]);

            return responseApi(200, translate('password update'));
        }
        return responseApiFalse(500, translate('old password is incorrect'));
    }

    public function checkPhone(Request $request)
    {
         $validator = validator($request->all(), [
             'country_id' => 'required|integer|exists:countries,id',
             'phone' => 'required|string|string|max:20',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $user = User::where('country_id',$request->country_id)->where('phone', $request->phone)->first();
        if($user){
            if ($request->has('is_reset')){
                $this->commonUtil->SendActivationCode($user, 'Reset');
            }
//            dd($user);
//            $user->activation_code= 1111;//rand ( 1000 , 9999 );
//            $user->save();
//            $from=env('MAIL_FROM_ADDRESS');
//            $data=[];
//             $data["subject"] = 'Reset Password';
//            $data["code"] = $user->activation_code;
//            $data["name"] = $user->name;
//            $data["email"] = $request->email;
//             Mail::send('emails.resetPassword', $data, function ($message) use ($data, $from) {
//            $message->from($from)->to($data["email"], $data["email"] )
//                ->subject($data["subject"]);
//             });
            return responseApi(200, translate('return success'), $user->id);
        }
       return responseApiFalse(405, translate('user not found'));
    }
    public function SendCode(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:Reset,Activation',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $user = User::where('id', $request->user_id)->first();


        if($user){
            $this->commonUtil->SendActivationCode($user,$request->type);
            return responseApi(200, translate('return success'), $user->id);
        }
        return responseApiFalse(405, translate('user not found'));
    }
    public function checkCode(Request $request)
    {
         $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|max:6',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        try {
            DB::beginTransaction();
            $user = User::where('id', $request->user_id)->first();
            if($user->activation_code ==  $request->code){
                $user->activation_code=null;
                if($user->activation_at == null ){
                    $user->activation_at=now();

                   $this->TransactionUtil->SaveJoiningBonus($user);

                   if($user->invite_by){
                       $this->TransactionUtil->ActiveInvitationBonus($user->invite_by,$user->id);
                   }
                }
                $user->save();
                DB::commit();
              return responseApi(200, translate('return success'), $user->id);
            }
            DB::commit();
            return responseApiFalse(500, translate('activation code is incorrect'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function forgotPassword(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'password' => 'required|string|min:4|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        User::where('id', $request->user_id)->update(['activation_code'=>null,'password' => bcrypt($request->password)]);

        return responseApi(200, translate('Password has been restored'));
    }

    public function removeAccount(Request $request)
    {
        $validator = validator($request->all(), [
            'password' => 'required|string|min:4|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        if (Hash::check($request->password, auth()->user()->getAuthPassword())) {
            auth()->user()->delete();
            auth()->logout();

            return responseApi(200, translate('Account deleted'));
        }
        return responseApiFalse(500, translate('password is incorrect'));
    }

    public function customRemoveAccount()
    {
        User::where('email', \request('email'))->delete();
        return responseApi(200, __('delete success'));
    }
    public function ActiveRemoveAccount(){
       $is_active= Setting::first();
        if(!$is_active){
           return responseApi(200,'', false);
        }elseif($is_active->active_delete_acount != \request('app_version') &&  \request('type')  != 'android'){
            return responseApi(200,'', false);
        }elseif($is_active->active_delete_acount_android != \request('app_version') &&  \request('type')  == 'android'){
            return responseApi(200,'', false);
        }
        return responseApi(200,'', true);


    }


    public function ChangeDefaultLanguage(Request $request){
        $validator = validator($request->all(), [
            'default_language' => 'required|in:ar,en',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $user=  auth()->user();
        $user->default_language=$request->default_language;
        $user->save();

        return responseApi(200,\App\CPU\translate('return_data_success'), new UserResource($user));
    }
}


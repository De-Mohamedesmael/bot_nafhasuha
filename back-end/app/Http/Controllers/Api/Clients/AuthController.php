<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Setting;
use App\Models\User;
use App\Utils\Util;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use function App\CPU\translate;


class AuthController extends Controller
{
    protected $commonUtil;

    public function __construct(Util $commonUtil)
    {
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
            return responseApiFalse(404, translate('Not found user'));
        }
        if (!$token=auth()->attempt($validator->validated())){
            $token = auth()->attempt(['phone'=>$request->phone,'password'=>$request->password]);
        }



        if (!$token){
            return responseApiFalse(500, translate('The password is incorrect'));
        }
        if(! auth()->user()->is_activation()){
            return responseApiFalse(500, translate('user Not activation'));

        }
        return responseApi(200, translate('user login'), $this->createNewToken($token));
    }

    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user())
        ];
    }

    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|between:2,200',
            'phone' => 'required|string|max:20|unique:users',
            'email' => 'nullable|string|max:20|unique:users',
            'address' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lang' => 'nullable|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'area_id' => 'nullable|integer|exists:areas,id',
            'password' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $inputs = $request->all();
        $user= User::create($inputs);
        $this->commonUtil->SendActivationCode($user,'Activation');
        return responseApi(200, translate('user registered'),
            $this->createNewToken(auth()->attempt($request->only(['email', 'password']))));
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

        return responseApi(200, translate('get_data_success'),  new UserResource(auth()->user()));
    }
    public function uploadImage(Request $request)
    {
        $validator = validator($request->all(), [
            'image' => 'required|Image|mimes:jpeg,jpg,png,gif',//|max:10000',
        ]);
        $user=auth()->user();
        if ($validator->fails())return responseApiFalse(405, $validator->errors()->first());

        $image = $user->getFirstMedia('images');

        if($image){
            $image->delete();
        }
        $uploadedFile = $request->file('image');
        $extension = $uploadedFile->getClientOriginalExtension();
        $user->addMedia($uploadedFile)
            ->usingFileName(time().'.'.$extension)
            ->toMediaCollection('images');
        $user= User::find($user->id);
        return responseApi(200, translate('user profile update'), new UserResource($user));
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

        $user= User::find($user->id);

        return responseApi(200, translate('user profile update'), new UserResource($user));
    }


    public function changePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'old_password' => 'required|string|min:3|max:255',
            'new_password' => 'required|string|min:3|max:255',
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
            return responseApiFalse(405, $validator->errors());

        $user = User::where('country_id',$request->country_id)->where('phone', $request->phone)->first();
        if($user){
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
       return responseApiFalse(404, translate('user not found'));
    }
    public function SendCode(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:Reset,Activation',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors());

        $user = User::where('id', $request->user_id)->first();


        if($user){
            $this->commonUtil->SendActivationCode($user,$request->type);
            return responseApi(200, translate('return success'), $user->id);
        }
        return responseApiFalse(404, translate('user not found'));
    }
    public function checkCode(Request $request)
    {
         $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|max:6',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors());

        $user = User::where('id', $request->user_id)->first();


        if($user->activation_code ==  $request->code){

            $user->activation_code=null;
            if($user->activation_at == null ){
                $user->activation_at=now();
            }
            $user->save();
          return responseApi(200, translate('return success'), $user->id);
        }
        return responseApiFalse(500, translate('activation code is incorrect'));
    }
    public function forgotPassword(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'password' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        User::where('id', $request->user_id)->update(['activation_code'=>null,'password' => bcrypt($request->password)]);

        return responseApi(200, translate('Password has been restored'));
    }

    public function removeAccount(Request $request)
    {
        $validator = validator($request->all(), [
            'password' => 'required|string|min:3|max:255',
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
}


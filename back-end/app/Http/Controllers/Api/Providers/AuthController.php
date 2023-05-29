<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Providers\ProviderResource;
use App\Http\Resources\UserResource;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Provider::class );
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

        $Provider= Provider::where('phone',$request->phone)->first();

        if(!$Provider){
            return responseApiFalse(405, translate('Not found Provider'));
        }
        if($Provider->is_deleted){
            return responseApiFalse(405, translate("Your account has already been deleted. Please contact customer service to recover your account."));
        }
        if (!$token=auth()->attempt($validator->validated())){
            $token = auth()->attempt(['phone'=>$request->phone,'password'=>$request->password]);
        }

        if (!$token){
            return responseApiFalse(500, translate('The password is incorrect'));
        }

        return responseApi(200, translate('Provider login'), $this->createNewToken($token));
    }

    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            'provider' => new ProviderResource(auth()->user())
        ];
    }

    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'provider_type' => 'required|string|in:Provider,ProviderCenter',
            'name' => 'required|string|between:2,200',
            'phone' => 'required|string|max:20|unique:providers',
            'email' => 'nullable|string|max:20|unique:providers',
            'address' => 'required|string|max:255',
            'lat' => 'required|string|max:255',
            'long' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'area_id' => 'required|integer|exists:areas,id',
            'password' => 'required|string|min:4|max:255',
            'services_from_home' => 'required|string|in:1,0',
            'commercial_register' => 'required|Image|mimes:jpeg,jpg,png,gif',
            'categories' => 'required|array',
            'categories.*' =>'required|integer|exists:categories,id',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();
            $inputs = $request->except('categories','commercial_register');
            $provider = Provider::create($inputs);
            $this->commonUtil->SendActivationCode($provider, 'Activation','Provider');
            $provider->categories()->sync($request->categories);
            if( $request->hasFile('commercial_register')){
                $uploadedFile = $request->file('commercial_register');
                $extension = $uploadedFile->getClientOriginalExtension();
                $provider->addMedia($uploadedFile)
                    ->usingFileName(time().'.'.$extension)
                    ->toMediaCollection('commercial_register');
            }
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
        return responseApi(200, translate('Provider logout'));
    }

    public function refresh()
    {
        return responseApi(200, translate('Provider login'), $this->createNewToken(auth()->refresh()));
    }

    public function ProviderProfile()
    {
        $data =  ['provider' => new ProviderResource(auth()->user())];
        return responseApi(200, translate('get_data_success'), $data );
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
        $data =  ['provider' => new ProviderResource(Provider::find($user->id))];
        return responseApi(200, translate('Provider profile update'), $data);
    }

    public function editProfile(Request $request)
    {
        $validator = validator($request->all(), [
            'provider_type' => 'nullable|string|in:Provider,ProviderCenter',
            'name' => 'required|string|between:2,200',
            'email' => 'nullable|string|email|max:100|unique:providers,email,' . auth()->id(),
            'address' => 'required|string|max:255',
            'lat' => 'required|string|max:255',
            'long' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'area_id' => 'required|integer|exists:areas,id',
            'services_from_home' => 'required|string|in:1,0',
            'commercial_register' => 'nullable|Image|mimes:jpeg,jpg,png,gif',
            'categories' => 'nullable|array',
            'categories.*' =>'nullable|integer|exists:categories,id',
            'image' => 'nullable|Image|mimes:jpeg,jpg,png,gif',
        ]);
        $provider=auth()->user();
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        auth()->user()->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'address'=>$request->address,
            'lat'=>$request->lat,
            'long'=>$request->long,
            'country_id'=>$request->country_id,
            'city_id'=>$request->city_id,
            'area_id'=>$request->area_id,
            'services_from_home'=>$request->services_from_home,
        ]);

        if($request->has('image')){
            $image = $provider->getFirstMedia('images');

            if($image){
                $image->delete();
            }
            $uploadedFile = $request->file('image');
            $extension = $uploadedFile->getClientOriginalExtension();
            $provider->addMedia($uploadedFile)
                ->usingFileName(time().'.'.$extension)
                ->toMediaCollection('images');
        }
        if($request->has('categories')){
            $provider->categories()->sync($request->categories);
        }
        if($request->has('provider_type')){
            $provider->provider_type = $request->provider_type;
            $provider->save();
        }
        if( $request->hasFile('commercial_register')){
            $uploadedFile = $request->file('commercial_register');
            $extension = $uploadedFile->getClientOriginalExtension();
            $provider->addMedia($uploadedFile)
                ->usingFileName(time().'.'.$extension)
                ->toMediaCollection('commercial_register');
        }


        $data =  ['provider' => new ProviderResource(Provider::find($provider->id))];

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

        $user = Provider::where('country_id',$request->country_id)
            ->where('phone', $request->phone)->first();
        if($user){
            if ($request->has('is_reset')){
                $this->commonUtil->SendActivationCode($user, 'Reset','Provider');
            }
            return responseApi(200, translate('return success'), $user->id);
        }
       return responseApiFalse(405, translate('user not found'));
    }
    public function SendCode(Request $request)
    {
        $validator = validator($request->all(), [
            'provider_id' => 'required|integer|exists:providers,id',
            'type' => 'required|in:Reset,Activation',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $provider = Provider::where('id', $request->provider_id)->first();


        if($provider){
            $this->commonUtil->SendActivationCode($provider,$request->type);
            return responseApi(200, translate('return success'), $provider->id);
        }
        return responseApiFalse(405, translate('provider not found'));
    }
    public function checkCode(Request $request)
    {
         $validator = validator($request->all(), [
            'provider_id' => 'required|integer|exists:providers,id',
            'code' => 'required|max:6',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        try {
            DB::beginTransaction();
            $provider = Provider::where('id', $request->provider_id)->first();
            if($provider->activation_code ==  $request->code){
                $provider->activation_code=null;
                if($provider->activation_at == null ){
                    $provider->activation_at=now();
                }
                $provider->save();
                DB::commit();
              return responseApi(200, translate('return success'), $provider->id);
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
            'provider_id' => 'required|integer|exists:providers,id',
            'password' => 'required|string|min:4|max:255',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        Provider::where('id', $request->provider_id)
            ->update(['activation_code'=>null,'password' => bcrypt($request->password)]);

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
            $Provider = auth()->user();
            $Provider ->is_deleted=1;
            $Provider ->deleted_by=auth()->id();
            $Provider ->deleted_by_type = 'Provider';
            $Provider ->is_active = 0;
            $Provider ->save();
            auth()->logout();

            return responseApi(200, translate('Account deleted'));
        }
        return responseApiFalse(500, translate('password is incorrect'));
    }


}


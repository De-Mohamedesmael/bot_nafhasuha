<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Currency;
use App\Models\Info;
use App\Models\Slider;
use App\Models\System;
use App\Models\TermsAndCondition;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class SettingController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;


    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
    }


   public function getGeneralSetting()
{
    $settings = System::pluck('value', 'key');
    $config_languages = config('constants.langs');
    $languages = [];
    foreach ($config_languages as $key => $value) {
        $languages[$key] = $value['full_name'];
    }

    return view('back-end.settings.general_setting')->with(compact(
        'settings',
        'languages'
    ));
}

    public function updateGeneralSetting(Request $request)
    {

        try {

            \Settings::set('site_title',$request->site_title);
            \Settings::set('JoiningBonusValue',$request->JoiningBonusValue);
            \Settings::set('InvitationBonusValue',$request->InvitationBonusValue);
            \Settings::set('change_price',$request->change_price);
            \Settings::set('subscription_price',$request->subscription_price);
            \Settings::set('limit_cancel',$request->limit_cancel);
            \Settings::set('max_distance',$request->max_distance);
            \Settings::set('IFTrueHome',$request->IFTrueHome);
            \Settings::set('IFTrueCenter',$request->IFTrueCenter);
            \Settings::set('update_version_IOS',$request->update_version_IOS);
            \Settings::set('update_version_Android',$request->update_version_Android);
            \Settings::set('update_version_Provider_IOS',$request->update_version_Provider_IOS);
            \Settings::set('update_version_Provider_Android',$request->update_version_Provider_Android);


             $data['logo'] = null;
            if ($request->has('logo') && !is_null('logo')) {
                $imageData = $this->getCroppedImage($request->logo);
                $folderPath = 'assets/images/settings/';
                $extention = explode(";", explode("/", $imageData)[1])[0];
                $image = rand(1, 1500) . "_image." . $extention;
                $filePath = $folderPath . $image;


                $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));

                \Settings::set('logo',$image);



            }

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            dd($e);
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }


    public function getBase64Image($Image)
    {

        $image_path = str_replace(env("APP_URL") . "/", "", $Image);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image_path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_content = curl_exec($ch);
        curl_close($ch);
//    $image_content = file_get_contents($image_path);
        $base64_image = base64_encode($image_content);
        $b64image = "data:image/jpeg;base64," . $base64_image;
        return  $b64image;
    }
    public function getCroppedImage($img){
        $dataNewImage = null;

        if (filter_var($img, FILTER_VALIDATE_URL) === false) {
            if (strlen($img) < 200) {
                $dataNewImage = $this->getBase64Image($img);
            } else {
                $dataNewImage = $img;
            }
        }

        return $dataNewImage;
    }
}

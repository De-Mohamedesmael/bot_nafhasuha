<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InfoRequest;
use App\Http\Resources\VehicleTypeResource;
use App\Models\Info;
use App\Models\VehicleType;
use App\Models\City;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class InfoController extends Controller
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
        $this->middleware('CheckPermission:info_module,infos,special');

    }


    public function edit($slug)
    {
        if($slug=='faq'){
            return  redirect()->route('admin.faqs.index');
        }
        $info = Info::where('slug', $slug)->firstOrFail();

        return view('back-end.infos.edit')->with(compact(
            'info'
        ));
    }

    public function update ($id,Request $request)
    {
        $validator = validator($request->all(), [
            'ar' => 'required|array',
            'ar.title' => 'required|string|max:200',
            'en' => 'required|array',
            'en.title' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return [
                'success'=>false,
                'error' =>$validator->errors()->first()
            ];
        }
        $info = Info::findOrFail($id);
        DB::beginTransaction();

        try {

            $data = $request->except('_token','lang');

            if($request->has('ar.img')){
                $data['ar']['img'] = 'infos/'.self::file_update('images/infos/', $info->translateOrDefault('ar') ? 'images/'.$info->translateOrDefault('ar')->img : null ,'png', $request->file('ar.img'));
            }
            if($request->has('en.img')){
                $data['en']['img'] = 'infos/'.self::file_update('images/infos/', $info->translateOrDefault('en') ? 'images/'.$info->translateOrDefault('en')->img : null ,'png', $request->file('en.img'));
            }
            $info->update($data);
            DB::commit();
            $output = [
                'success'=>true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success'=>false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
        return redirect()->back()->with('status', $output);

    }

    public static function file_update(string $dir, $old_image, string $format, $image = null)
    {
        $publicPath = 'assets/' . $dir;
        if (File::exists($publicPath . '/' . $old_image)) {
            File::delete($publicPath . '/' . $old_image);
        }

        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            $publicPath = 'assets/' . $dir;
            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0777, true);
            }
            Image::make($image)->save($publicPath . '/' . $imageName);
        } else {
            $imageName = 'def.png';
        }
        return $imageName;
    }





}

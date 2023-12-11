<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Coupon;
use App\Models\FcmToken;
use App\Models\FcmTokenProvider;
use App\Models\Info;
use App\Models\Notification;
use App\Models\Provider;
use App\Models\User;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class NotificationController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $logo=\Settings::get('logo');
            $notifications = Notification::wherein('type',['2','3'])->listsTranslations('title','body')
                ->select('notifications.*',
                    'notification_translations.body',
                    'notification_translations.title'
                );
            $notifications=$notifications->groupBy('notifications.id');
            return DataTables::of($notifications)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('type_model', function ($row) {
                    return  __('lang.'.$row->type_model);
                })
                ->editColumn('type', function ($row) {
                    return  __('lang.'.$row->type);
                })
                ->addColumn('image', function ($row) use($logo) {
                    $image = asset('assets/images/'.$row->image);
                    if ($row->image) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/assets/images/settings/' . $logo) . '" height="50px" width="50px">';
                    }
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" notification="menu">';




                        $html .= '<li class="divider"></li>';

//                            if (auth()->notification()->can('notification_module.notification.delete')) {
                                $html .=
                                    '<li>
                                    <a data-href="' . route('admin.notifications.delete', $row->id)  . '"
                                        data-check_password="' . route('admin.checkPassword', Auth::id()) . '"
                                        class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                        ' . __('lang.delete') . '</a>
                                    </li>';
//                            }



                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'type_model',
                    'image',
                    'type',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.notifications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $users = User::pluck('name','id');
        $providers = Provider::pluck('name','id');
        $coupons = Coupon::pluck('name','id');
        return view('back-end.notifications.create')->with(compact(
            'users',
            'providers',
            'coupons'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $filteredInput = array_filter($request->all(), function ($value) {
            return $value !== null;
        });
        $filteredRequest = new Request($filteredInput);
        $validator = validator($filteredRequest->all(), [
            'type' => 'required|in:2,3',
            'type_model' => 'required|in:User,Provider',
            'type_id' => 'required_if:type,==,2|integer|exists:coupons,id',
            'user_id' => 'required_if:type_model,==,User|array',
            'user_id.*' => 'integer|exists:users,id',
            'provider_id' => 'required_if:type_model,==,Provider|array',
            'provider_id.*' => 'integer|exists:providers,id',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.title' => 'required|max:255',
            'translations.*.body' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
         try {
        DB::beginTransaction();
        $notification = Notification::create([
            "type" => $request->type,
            "type_id" => $request->type_id,
            "type_model" => $request->type_model,
        ]);
             $notification->update($request->translations);

             if ($request->has("cropImages") && count($request->cropImages) > 0) {
                 foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                     $folderPath = 'assets/images/notifications/';
                     $extention = explode(";", explode("/", $imageData)[1])[0];
                     $image = rand(1, 1500) . "_image." . $extention;
                     $filePath = $folderPath . $image;
                     if (!empty($notification->image)) {
                         $oldImagePath = 'assets/images/' . $notification->image;
                         if (File::exists($oldImagePath)) {
                             File::delete($oldImagePath);
                         }
                     }

                     $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                     $notification->image = 'notifications/' . $image;
                     $notification->save();
                 }

             }
             if($request->has("user_id")){
                 $notification->users()->sync($request->user_id);
             }
             if($request->has("provider_id")){
                 $notification->providers()->sync($request->provider_id);
             }
            if($request->type_model == 'User'){
                $FcmToken=FcmToken::wherein('user_id',$request->user_id)->pluck('token');
                $this->sendNotification($notification,$FcmToken);
            }else{
                $FcmToken=FcmTokenProvider::wherein('provider_id',$request->provider_id)
                    ->pluck('token');
                $this->sendNotification($notification,$FcmToken);
            }

        $notification_id=$notification->id;
        DB::commit();
        $output = [
            'code' => 200,
            'notification_id' => $notification_id,
            'msg' => __('lang.success')
        ];
         } catch (\Exception $e) {
            dd($e);
             Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
             $output = [
                 'code' => 500,
                 'msg' => __('lang.something_went_wrong')
             ];
         }


        return $output;


//        return redirect()->to('notification')->with('status', $output);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::find($id);
            if ($notification){
                $notification->delete();
            }
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }


    /**
     * delete Image for Notification
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request  $request)
    {
        try {
            DB::beginTransaction();
            $notification = Notification::find($request->id);
            if($notification){
                $notification->clearMediaCollection('images');

                DB::commit();
                $output = [
                    'success' => true,
                    'msg' => __('lang.success')
                ];
            }else{
                $output = [
                    'success' => false,
                    'msg' => __('lang.something_went_wrong')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
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
    public function getCroppedImages($cropImages){
        $dataNewImages = [];
        foreach ($cropImages as $img) {
            if (filter_var($img, FILTER_VALIDATE_URL) === false) {
                if (strlen($img) < 200) {
                    $dataNewImages[] = $this->getBase64Image($img);
                } else {
                    $dataNewImages[] = $img;
                }
            }
        }
        return $dataNewImages;
    }
}

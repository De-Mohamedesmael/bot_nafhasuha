<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Info;
use App\Models\Slider;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class SliderController extends Controller
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
            $sliders = Slider::select('sliders.*');
            $sliders=$sliders->groupBy('sliders.id');
            return DataTables::of($sliders)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('status', function ($row) {
                    $checked='';
                    $now = date('Y-m-d');
                    if(($row->start_at <= $now||$row->start_at == null) && ($row->end_at > $now||$row->end_at == null) && $row->status){
                        $checked='checked';
                    }
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
                })->addColumn('type_id', function ($row) {
                    $type_id=null;
                    switch ($row->type){
                        case 'OutSite':
                            $type_id=$row->type_id;
                            break;
                        case 'Service':
                            $type_id=$row->service?->title;
                            break;
                        case 'Info':
                            $type_id=$row->info?->title;
                            break;
                    }
                    return $type_id;
                })
                ->addColumn('image', function ($row) use($logo) {
                    $image = $row->getFirstMediaUrl('images');
                    if (!empty($image)) {
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" slider="menu">';

                        $html .= '<li class="divider"></li>';


//                        if (auth()->slider()->can('slider_module.slider.add_balen')){
                        $html .='<li>
                                                <a href="'. route('admin.slider.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';

//                        }
                        $html .= '<li class="divider"></li>';

//                            if (auth()->slider()->can('slider_module.slider.delete')) {
                                $html .=
                                    '<li>
                                    <a data-href="' . route('admin.slider.delete', $row->id)  . '"
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
                    'status',
                    'image',
                    'type_id',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $infos = Info::listsTranslations('title as name')->pluck('name','id');
        $quick_add = request()->quick_add ?? null;


        if ($quick_add) {
            return view('back-end.slider.quick_add')->with(compact(
                'categories',
                'infos',
                'quick_add'
            ));
        }

        return view('back-end.slider.create')->with(compact(
            'categories',
            'infos',
            'quick_add'
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
        $this->validate(
            $request,
            ['type' => ['required','in:Service,OutSite,Info']],
            ['service_id' => ['required_if:type,==,Service']],
            ['info_id' => ['required_if:type,==,Info']],
            ['url' => ['required_if:type,==,OutSite', 'max:255']],
            ['sort' => ['required']],
            ['end_at' => ['nullable']],
            ['start_at' => ['nullable']],
            ['cropImages' => ['required']]
        );

         try {
        DB::beginTransaction();
             $type_id=null;
        switch ($request->type){
            case 'OutSite':
                $type_id=$request->url;
                break;
            case 'Service':
                $type_id=$request->service_id;
                break;
            case 'Info':
                $info = Info::where('id',$request->info_id)->first();
                $type_id=$info ? $info->slug :$request->info_id;
                break;
        }
        $slider = Slider::create([
            "type" => $request->type,
            "type_id" => $type_id,
            "sort" => $request->sort,
            "end_at" => $request->end_at? date('Y-m-d', strtotime($request->end_at)):null,
            "start_at" => $request->start_at ? date('Y-m-d', strtotime($request->start_at)):null,

        ]);
        if ($request->has("cropImages") && count($request->cropImages) > 0) {
            foreach ($request->cropImages as $imageData) {
                $extention = explode(";",explode("/",$imageData)[1])[0];
                $image = rand(1,1500)."_image.".$extention;
                $filePath = public_path('uploads/' . $image);
                $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                $slider->addMedia($filePath)->toMediaCollection('images');
            }
        }
        $slider_id=$slider->id;
        DB::commit();
        $output = [
            'success' => true,
            'slider_id' => $slider_id,
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


//        return redirect()->to('slider')->with('status', $output);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::find($id);

        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $infos = Info::listsTranslations('title as name')->pluck('name','id');
        $info = Info::where('slug',$slider->type_id)->first();
        $type_id=$info ? $info->id :$slider->type_id;
        return view('back-end.slider.edit')->with(compact(
            'slider',
            'categories',
            'type_id',
            'infos',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['type' => ['required','in:Service,OutSite,Info']],
            ['service_id' => ['required_if:type,==,Service']],
            ['info_id' => ['required_if:type,==,Info']],
            ['url' => ['required_if:type,==,OutSite', 'max:255']],
            ['sort' => ['required']],
            ['end_at' => ['nullable']],
            ['start_at' => ['nullable']],
            ['cropImages' => ['required']]
        );

        try {
            DB::beginTransaction();
            $type_id=null;
            switch ($request->type){
                case 'OutSite':
                    $type_id=$request->url;
                    break;
                case 'Service':
                    $type_id=$request->service_id;
                    break;
                case 'Info':
                    $info = Info::where('id',$request->info_id)->first();
                    $type_id=$info ? $info->slug :$request->info_id;
                    break;
            }
            $slider = Slider::find($id);
                $slider->update([
                    "type" => $request->type,
                    "type_id" => $type_id,
                    "sort" => $request->sort,
                    "end_at" => $request->end_at? date('Y-m-d', strtotime($request->end_at)):null,
                    "start_at" => $request->start_at ? date('Y-m-d', strtotime($request->start_at)):null,
                ]);

                if (!$request->has('have_image')){
                    $slider->clearMediaCollection('images');
                }
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                $slider->clearMediaCollection('images');
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $slider->addMedia($filePath)->toMediaCollection('images');
                }
            }



            DB::commit();
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
        if ($request->ajax()) {
            return $output;
        } else {
            return redirect()->back()->with('status', $output);
        }

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
            $slider = Slider::find($id);
            if ($slider){
                $slider->clearMediaCollection('images');
                $slider->delete();
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

    public function update_status(Request $request ){

        try {
            $slider=Slider::find($request->id);
            if(!$slider){
                return [
                    'success'=>false,
                    'msg'=>translate('slider_not_found')
                ];
            }


            DB::beginTransaction();
            $now = date('Y-m-d');

            if(!(($slider->start_at <= $now||$slider->start_at == null) && ($slider->end_at > $now||$slider->end_at == null) && $slider->status)) {
                $slider->status = 1;
                $slider->start_at=null;
                $slider->end_at=null;
            }else{
                $slider->status = 0;
            }

            $slider->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('Slider updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }
    /**
     * delete Image for Slider
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request  $request)
    {
        try {
            DB::beginTransaction();
            $slider = Slider::find($request->id);
            if($slider){
                $slider->clearMediaCollection('images');

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

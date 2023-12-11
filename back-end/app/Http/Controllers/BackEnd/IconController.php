<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Info;
use App\Models\Icon;
use App\Models\Service;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class IconController extends Controller
{

    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
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
            $icons = Icon::groupBy('icons.id');
            return DataTables::of($icons)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" icon="menu">';

//                            if (auth()->icon()->can('icon_module.icon.delete')) {
                        $html .='<li>
                                                <a href="'. route('admin.icons.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';
//                            }

                        $html .= '<li class="divider"></li>';

//                            if (auth()->icon()->can('icon_module.icon.delete')) {
                        $html .=
                            '<li>
                                    <a data-href="' . route('admin.icons.delete', $row->id)  . '"
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
                    'image',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.icons.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.icons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = validator($request->all(), [
            'link' => 'required|URL',
            'title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
        try {
            DB::beginTransaction();
            $icon = Icon::create([
                "title" => $request->title,
                "link" => $request->link,
               ]);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                    $folderPath = 'assets/images/icons/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($icon->image)) {
                        $oldImagePath = 'assets/images/' . $icon->image;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $icon->image = 'icons/' . $image;
                    $icon->save();
                }

            }

            $icon_id=$icon->id;
            DB::commit();
            $output = [
                'code' => 200,
                'icon_id' => $icon_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {

            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'code' => 500,
                'msg' => __('lang.something_went_wrong')
            ];
        }


        return $output;


//        return redirect()->to('icon')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $icon = Icon::find($id);

        return view('back-end.icons.edit')->with(compact(
            'icon'
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,

            ['title' => ['required','string']],
            ['link' => ['required','URL']],
        );

        try {
            DB::beginTransaction();
            $icon = Icon::find($id);
            $icon->title=$request->title;
            $icon->link=$request->link;
            $icon->save();
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                    $folderPath = 'assets/images/icons/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($icon->image)) {
                        $oldImagePath = 'assets/images/' . $icon->image;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $icon->image = 'icons/' . $image;
                    $icon->save();
                }

            }

            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return  $output;
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

        foreach ($cropImages as $image) {
            if (filter_var($image, FILTER_VALIDATE_URL) === false) {
                if (strlen($image) < 200) {
                    $dataNewImages[] = $this->getBase64Image($image);
                } else {
                    $dataNewImages[] = $image;
                }
            }
        }
        return $dataNewImages;
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
            $icon = Icon::find($id);
            if ($icon){
                if (!empty($icon->image)) {
                    $oldImagePath = public_path('assets/images/' . $icon->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                $icon->delete();
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




}

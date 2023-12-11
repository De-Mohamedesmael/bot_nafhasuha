<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Info;
use App\Models\Country;
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

class CountryController extends Controller
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
            $countries = Country::listsTranslations('title')
                ->select('countries.*',
                    'country_translations.title'
                );
            $countries=$countries->groupBy('countries.id');
            return DataTables::of($countries)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('image', function ($row) use($logo) {
                    $image = asset('assets/images/'.$row->img);
                    if ($row->img) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/assets/images/settings/' . $logo) . '" height="50px" width="50px">';
                    }
                })->addColumn('status', function ($row) {
                    $checked=$row->is_active?'checked':'';
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" country="menu">';

//                            if (auth()->country()->can('country_module.country.delete')) {
                        $html .='<li>
                                                <a href="'. route('admin.countries.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';
//                            }

                        $html .= '<li class="divider"></li>';

//                            if (auth()->country()->can('country_module.country.delete')) {
                        $html .=
                            '<li>
                                    <a data-href="' . route('admin.countries.delete', $row->id)  . '"
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
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.countries.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.countries.create');
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
            'sort' => 'required|integer',
            'code_number' => 'required|string',
            'count_number' => 'required|integer',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
        try {
            DB::beginTransaction();
            $country = Country::create([
                "general_title" => $request->title,
                "sort" => $request->sort,
                "count_number" => $request->count_number,
                "code_number" => $request->code_number,
               ]);

            $country->update($request->translations);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                    $folderPath ='assets/images/countries/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($country->img)) {
                        $oldImagePath = 'assets/images/' . $country->img;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $country->img = 'countries/' . $image;
                    $country->save();
                }

            }

            $country_id=$country->id;
            DB::commit();
            $output = [
                'code' => 200,
                'country_id' => $country_id,
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


//        return redirect()->to('country')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $country = Country::find($id);

        return view('back-end.countries.edit')->with(compact(
            'country'
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            ['sort' => ['required','integer']],
            ['code_number' => ['required','string']],
            ['count_number' => ['required','integer']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
        );

        try {
            DB::beginTransaction();
            $country = Country::find($id);
            $country->general_title=$request->title;
            $country->count_number=$request->count_number;
            $country->code_number=$request->code_number;
            $country->sort=$request->sort;
            $country->save();
            $country->update($request->translations);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                    $folderPath = 'assets/images/countries/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($country->img)) {
                        $oldImagePath = 'assets/images/' . $country->img;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $country->img = 'countries/' . $image;
                    $country->save();
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $country = Country::find($id);
            if ($country){
                if($country->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_country_cannot_be_deleted')
                    ];
                }
                $country->delete();
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
            $country=Country::find($request->id);
            if(!$country){
                return [
                    'success'=>false,
                    'msg'=>translate('country_not_found')
                ];
            }


            DB::beginTransaction();
            $country->is_active=($country->is_active - 1) *-1;
            $country->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('country updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }

    public function cities(Request $request){
        $validator = validator($request->all(), [
            'country_id' => 'nullable|integer|exists:countries,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $cities= City::orderBy('sort', 'Asc');
        if($request->has('country_id')){
            $cities=$cities->where('country_id',$request->country_id);
        }

        $cities=  $cities->get();

        return responseApi(200,\App\CPU\translate('return_data_success'), CityResource::collection($cities));
    }
}

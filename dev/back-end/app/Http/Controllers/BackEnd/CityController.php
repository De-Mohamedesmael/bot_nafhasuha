<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class CityController extends Controller
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
            $cities = City::leftjoin('country_translations', 'cities.country_id', 'country_translations.country_id')
            ->listsTranslations('title')
                ->select('cities.*',
                    'city_translations.title'
                );
            $cities=$cities->groupBy('cities.id');
            return DataTables::of($cities)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('name_country', function ($row) {
                    return $row->country?->title;
                })
                ->addColumn('status', function ($row) {
                    $checked=$row->is_active?'checked':'';
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' ';
//                        if (auth()->user()->can('admin_module.areas.edit')) {
                        $html .= '<a href="' . route('admin.city.edit', $row->id) . '"
                                     class="btn edit_employee a-image" title="' . __('lang.edit') . '">
                                            <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">

                                    </a>';
//                        }
//                        if (auth()->user()->can('admin_module.areas.delete')) {
                        $html .= '<a data-href="' . route('admin.city.delete', $row->id) . '"
                                    data-check_password="' . route('admin.checkPassword', Auth::user()->id) . '"
                                    class="btn delete_item text-red" title="' . __('lang.delete') . '"><i
                                        class="dripicons-trash"></i>
                                    </a>';
//                        }
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'image',
                    'name_country',
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.cities.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries =Country::listsTranslations('title as name')->pluck('name','id');
        return view('back-end.cities.create',compact('countries'));
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
            'title' => 'required|string',
            'country_id' => 'required|integer|exists:countries,id',
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
            $city = City::create([
                "general_title" => $request->title,
                "sort" => $request->sort,
                "country_id" => $request->country_id,
            ]);

            $city->update($request->translations);
            $city_id=$city->id;
            DB::commit();
            $output = [
                'code' => 200,
                'city_id' => $city_id,
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


//        return redirect()->to('city')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $city = City::find($id);
        $countries =Country::listsTranslations('title as name')->pluck('name','id');

        return view('back-end.cities.edit')->with(compact(
            'city',
            'countries',
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            ['sort' => ['required','integer']],
            ['country_id' => ['required','integer','exists:countries,id']],
            ['title' => ['required','string']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
        );

        try {
            DB::beginTransaction();
            $city = City::find($id);
            $city->general_title=$request->title;
            $city->country_id=$request->country_id;
            $city->sort=$request->sort;
            $city->save();
            $city->update($request->translations);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $city = City::find($id);
            if ($city){
                if($city->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_city_cannot_be_deleted')
                    ];
                }
                $city->delete();
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
            $city=City::find($request->id);
            if(!$city){
                return [
                    'success'=>false,
                    'msg'=>translate('city_not_found')
                ];
            }


            DB::beginTransaction();
            $city->is_active=($city->is_active - 1) *-1;
            $city->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('city updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }
    public function areas(Request $request){
        $validator = validator($request->all(), [
            'city_id' => 'nullable|integer|exists:cities,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $areas= Area::orderBy('sort', 'Asc');
        if($request->has('city_id')){
            $areas=$areas->where('city_id',$request->city_id);
        }

            $areas=  $areas->get();

        return responseApi(200,\App\CPU\translate('return_data_success'), AreaResource::collection($areas));
    }
}
<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleBrandResource;
use App\Models\VehicleBrand;
use App\Models\City;
use App\Models\VehicleType;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class VehicleBrandController extends Controller
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
            $vehicle_brands = VehicleBrand::listsTranslations('title')
                ->leftJoin('vehicle_type_translations', 'vehicle_brands.vehicle_type_id', '=', 'vehicle_type_translations.vehicle_type_id')
                ->select('vehicle_brands.*',
                    'vehicle_brand_translations.title',
                );
            $vehicle_brands=$vehicle_brands->groupBy('vehicle_brands.id');
            return DataTables::of($vehicle_brands)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('vehicle_type_name', function ($row) {
                    return $row->vehicle_type?->title;
                })
                ->addColumn('status', function ($row) {
                    $checked=$row->status?'checked':'';
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' ';
//                        if (auth()->user()->can('admin_module.areas.edit')) {
                        $html .= '<a href="' . route('admin.vehicle_brands.edit', $row->id) . '"
                                     class="btn edit_employee a-image" title="' . __('lang.edit') . '">
                                            <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">

                                    </a>';
//                        }
//                        if (auth()->user()->can('admin_module.areas.delete')) {
                        $html .= '<a data-href="' . route('admin.vehicle_brands.delete', $row->id) . '"
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
                    'vehicle_type_name',
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.vehicle_brands.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicle_types=VehicleType::listsTranslations('title as name')->pluck('name','id');
        return view('back-end.vehicle_brands.create',compact('vehicle_types'));
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
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'title' => 'required|string',
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
            $data=$request->translations;
            $data['vehicle_type_id']=$request->vehicle_type_id;

            DB::beginTransaction();
            $vehicle_brand = VehicleBrand::create($data);

            $vehicle_brand_id=$vehicle_brand->id;
            DB::commit();
            $output = [
                'code' => 200,
                'vehicle_brand_id' => $vehicle_brand_id,
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


//        return redirect()->to('vehicle_brand')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $vehicle_brand = VehicleBrand::find($id);
        $vehicle_types=VehicleType::listsTranslations('title as name')->pluck('name','id');

        return view('back-end.vehicle_brands.edit')->with(compact(
            'vehicle_brand','vehicle_types'
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            ['vehicle_type_id' => ['required','exists:vehicle_types,id']],
            ['title' => ['required','string']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
        );

        try {
            DB::beginTransaction();
            $vehicle_brand = VehicleBrand::find($id);
            $vehicle_brand->vehicle_type_id=$request->vehicle_type_id;
            $vehicle_brand->save();

            $vehicle_brand->update($request->translations);

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
            $vehicle_brand = VehicleBrand::find($id);
            if ($vehicle_brand){
                if($vehicle_brand->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_vehicle_brand_cannot_be_deleted')
                    ];
                }
                $vehicle_brand->delete();
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
            $vehicle_brand=VehicleBrand::find($request->id);
            if(!$vehicle_brand){
                return [
                    'success'=>false,
                    'msg'=>translate('vehicle_brand_not_found')
                ];
            }


            DB::beginTransaction();
            $vehicle_brand->status=($vehicle_brand->status - 1) *-1;
            $vehicle_brand->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('vehicle_brand updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }

}

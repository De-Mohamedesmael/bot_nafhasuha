<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleType;
use App\Models\City;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class VehicleTypeController extends Controller
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
            $vehicle_types = VehicleType::listsTranslations('title')
                ->select('vehicle_types.*',
                    'vehicle_type_translations.title'
                );
            $vehicle_types=$vehicle_types->groupBy('vehicle_types.id');
            return DataTables::of($vehicle_types)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('status', function ($row) {
                    $checked=$row->status?'checked':'';
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" vehicle_type="menu">';

//                            if (auth()->vehicle_type()->can('vehicle_type_module.vehicle_type.delete')) {
                        $html .='<li>
                                                <a href="'. route('admin.vehicle_types.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';
//                            }

                        $html .= '<li class="divider"></li>';

//                            if (auth()->vehicle_type()->can('vehicle_type_module.vehicle_type.delete')) {
                        $html .=
                            '<li>
                                    <a data-href="' . route('admin.vehicle_types.delete', $row->id)  . '"
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
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.vehicle_types.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.vehicle_types.create');
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
            DB::beginTransaction();
            $vehicle_type = VehicleType::create($request->translations);

            $vehicle_type_id=$vehicle_type->id;
            DB::commit();
            $output = [
                'code' => 200,
                'vehicle_type_id' => $vehicle_type_id,
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


//        return redirect()->to('vehicle_type')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $vehicle_type = VehicleType::find($id);

        return view('back-end.vehicle_types.edit')->with(compact(
            'vehicle_type'
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            ['title' => ['required','string']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
        );

        try {
            DB::beginTransaction();
            $vehicle_type = VehicleType::find($id);
            $vehicle_type->update($request->translations);

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
            $vehicle_type = VehicleType::find($id);
            if ($vehicle_type){
                if($vehicle_type->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_vehicle_type_cannot_be_deleted')
                    ];
                }
                $vehicle_type->delete();
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
            $vehicle_type=VehicleType::find($request->id);
            if(!$vehicle_type){
                return [
                    'success'=>false,
                    'msg'=>translate('vehicle_type_not_found')
                ];
            }


            DB::beginTransaction();
            $vehicle_type->status=($vehicle_type->status - 1) *-1;
            $vehicle_type->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('vehicle_type updated successfully!')
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

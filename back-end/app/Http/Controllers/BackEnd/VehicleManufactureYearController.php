<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleManufactureYearResource;
use App\Models\VehicleManufactureYear;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class VehicleManufactureYearController extends Controller
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
            $vehicle_manufacture_years = VehicleManufactureYear::
                select('vehicle_manufacture_years.*');
            return DataTables::of($vehicle_manufacture_years)
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" vehicle_manufacture_year="menu">';

//                            if (auth()->vehicle_manufacture_year()->can('vehicle_manufacture_year_module.vehicle_manufacture_year.delete')) {
                        $html .='<li>
                                                <a href="'. route('admin.vehicle_manufacture_years.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';
//                            }

                        $html .= '<li class="divider"></li>';

//                            if (auth()->vehicle_manufacture_year()->can('vehicle_manufacture_year_module.vehicle_manufacture_year.delete')) {
                        $html .=
                            '<li>
                                    <a data-href="' . route('admin.vehicle_manufacture_years.delete', $row->id)  . '"
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
                    'name_city',
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.vehicle_manufacture_years.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.vehicle_manufacture_years.create');
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
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
        try {
            DB::beginTransaction();
            $vehicle_manufacture_year = VehicleManufactureYear::create([
                "title" => $request->title,
            ]);

            $vehicle_manufacture_year_id=$vehicle_manufacture_year->id;
            DB::commit();
            $output = [
                'code' => 200,
                'vehicle_manufacture_year_id' => $vehicle_manufacture_year_id,
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


//        return redirect()->to('vehicle_manufacture_year')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $vehicle_manufacture_year = VehicleManufactureYear::find($id);

        return view('back-end.vehicle_manufacture_years.edit')->with(compact(
            'vehicle_manufacture_year'
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            ['title' => ['required','string']],

        );

        try {
            DB::beginTransaction();
            $vehicle_manufacture_year = VehicleManufactureYear::find($id);
            $vehicle_manufacture_year->title=$request->title;
            $vehicle_manufacture_year->save();

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
            $vehicle_manufacture_year = VehicleManufactureYear::find($id);
            if ($vehicle_manufacture_year){
                if($vehicle_manufacture_year->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_vehicle_manufacture_year_cannot_be_deleted')
                    ];
                }
                $vehicle_manufacture_year->delete();
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
            $vehicle_manufacture_year=VehicleManufactureYear::find($request->id);
            if(!$vehicle_manufacture_year){
                return [
                    'success'=>false,
                    'msg'=>translate('vehicle_manufacture_year_not_found')
                ];
            }


            DB::beginTransaction();
            $vehicle_manufacture_year->status=($vehicle_manufacture_year->status - 1) *-1;
            $vehicle_manufacture_year->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('vehicle_manufacture_year updated successfully!')
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

<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;

use App\Models\TypeBattery;
use App\Models\VehicleType;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class TypeBatteryController extends Controller
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
            $type_batteries = TypeBattery::listsTranslations('title')->
            select('type_batteries.*',
                'type_battery_translations.title',
            )->groupBy('type_batteries.id');
            return DataTables::of($type_batteries)
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
                                                <a href="'. route('admin.type_batteries.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';

//                        }
                        $html .= '<li class="divider"></li>';

//                            if (auth()->slider()->can('slider_module.slider.delete')) {
                                $html .=
                                    '<li>
                                    <a data-href="' . route('admin.type_batteries.delete', $row->id)  . '"
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

        return view('back-end.type_batteries.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.type_batteries.create');
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
            $data=$request->translations;
            DB::beginTransaction();
            $type_battery = TypeBattery::create($data);

            $type_battery_id=$type_battery->id;
            DB::commit();
            $output = [
                'code' => 200,
                'type_battery_id' => $type_battery_id,
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


//        return redirect()->to('type_battery')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $type_battery = TypeBattery::find($id);
        return view('back-end.type_batteries.edit')->with(compact(
            'type_battery'
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
            $type_battery = TypeBattery::find($id);
            $type_battery->update($request->translations);

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
            $type_battery = TypeBattery::find($id);
            if ($type_battery){
                if($type_battery->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_type_battery_cannot_be_deleted')
                    ];
                }
                $type_battery->delete();
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
            $type_battery=TypeBattery::find($request->id);
            if(!$type_battery){
                return [
                    'success'=>false,
                    'msg'=>translate('type_battery_not_found')
                ];
            }


            DB::beginTransaction();
            $type_battery->status=($type_battery->status - 1) *-1;
            $type_battery->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('type_battery updated successfully!')
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
<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\TypeGasolineResource;
use App\Models\TypeGasoline;
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

class TypeGasolineController extends Controller
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
            $type_gasolines = TypeGasoline::groupBy('id');
            return DataTables::of($type_gasolines)
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
                        $html = ' ';
//                        if (auth()->user()->can('admin_module.areas.edit')) {
                        $html .= '<a href="' . route('admin.type_gasolines.edit', $row->id) . '"
                                     class="btn edit_employee a-image" title="' . __('lang.edit') . '">
                                            <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">

                                    </a>';
//                        }
//                        if (auth()->user()->can('admin_module.areas.delete')) {
                        $html .= '<a data-href="' . route('admin.type_gasolines.delete', $row->id) . '"
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
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.type_gasolines.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.type_gasolines.create');
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
            'title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
        try {
            DB::beginTransaction();
            $type_gasoline = TypeGasoline::create([
                'title'=>$request->title
            ]);

            DB::commit();
            $output = [
                'code' => 200,
                'type_gasoline_id' => $type_gasoline->id,
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


//        return redirect()->to('type_gasoline')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $type_gasoline = TypeGasoline::find($id);

        return view('back-end.type_gasolines.edit')->with(compact(
            'type_gasoline'
        ));
    }


    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            ['title' => ['required','string']]
        );

        try {
            DB::beginTransaction();
            $type_gasoline = TypeGasoline::find($id);
            $type_gasoline->title=$request->title;
            $type_gasoline->save();

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
            $type_gasoline = TypeGasoline::find($id);
            if ($type_gasoline){
                if($type_gasoline->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_type_gasoline_cannot_be_deleted')
                    ];
                }
                $type_gasoline->delete();
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
            $type_gasoline=TypeGasoline::find($request->id);
            if(!$type_gasoline){
                return [
                    'success'=>false,
                    'msg'=>translate('type_gasoline_not_found')
                ];
            }


            DB::beginTransaction();
            $type_gasoline->status=($type_gasoline->status - 1) *-1;
            $type_gasoline->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('type_gasoline updated successfully!')
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

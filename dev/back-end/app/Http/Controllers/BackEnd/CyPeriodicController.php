<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CyPeriodic;
use App\Models\City;
use App\Models\Provider;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class CyPeriodicController extends Controller
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
            $cy_periodics = CyPeriodic::leftjoin('city_translations', 'cy_periodics.city_id', 'city_translations.city_id')
            ->listsTranslations('title')
                ->select('cy_periodics.*',
                    'cy_periodic_translations.title'
                );
            $cy_periodics=$cy_periodics->groupBy('cy_periodics.id');
            return DataTables::of($cy_periodics)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('name_city', function ($row) {
                    return $row->city?->title;
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
                        $html .= '<a href="' . route('admin.cy_periodics.edit', $row->id) . '"
                                     class="btn edit_employee a-image" title="' . __('lang.edit') . '">
                                            <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">

                                    </a>';
//                        }
//                        if (auth()->user()->can('admin_module.areas.delete')) {
                        $html .= '<a data-href="' . route('admin.cy_periodics.delete', $row->id) . '"
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
                    'name_city',
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.cy_periodics.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities =City::listsTranslations('title as name')->pluck('name','id');
        $providers =Provider::pluck('name','id');

        return view('back-end.cy_periodics.create',compact('cities','providers'));
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
            'price' => 'required|numeric',
            'sort' => 'required|integer',
            'city_id' => 'required|integer|exists:cities,id',
            'providers' => 'required|array',
            'providers.*' => 'required|integer|exists:providers,id',
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
            $date=$request->translations;
            $date['city_id']=$request->city_id;
            $date['price']=$request->price;
            $date['sort']=$request->sort;
            $cy_periodic = CyPeriodic::create($date);
            if($request->has('providers')){
                $cy_periodic->providers()->sync($request->providers);
            }
            $cy_periodic_id=$cy_periodic->id;
            DB::commit();
            $output = [
                'code' => 200,
                'cy_periodic_id' => $cy_periodic_id,
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


//        return redirect()->to('cy_periodic')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $cy_periodic = CyPeriodic::find($id);
        $cities =City::listsTranslations('title as name')->pluck('name','id');
        $providers =Provider::pluck('name','id');
        $array_providers=$cy_periodic->providers()->pluck('providers.id');

        return view('back-end.cy_periodics.edit')->with(compact(
            'providers',
            'cy_periodic',
            'array_providers',
            'cities'
        ));
    }


    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['price' => ['required','numeric']],
            ['sort' => ['required','integer']],
            ['city_id' => ['required','integer','exists:cities,id']],
            ['providers' => ['required','array']],
            ['providers.*' => ['required','integer','exists:providers,id']],
            ['title' => ['required','string']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
        );

        try {
            DB::beginTransaction();
            $date=$request->translations;
            $date['city_id']=$request->city_id;
            $date['price']=$request->price;
            $date['sort']=$request->sort;
            $cy_periodic = CyPeriodic::find($id);
            $cy_periodic->update($date);
            if($request->has('providers')){
                $cy_periodic->providers()->sync($request->providers);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cy_periodic = CyPeriodic::find($id);
            if ($cy_periodic){
                if($cy_periodic->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_cy_periodic_cannot_be_deleted')
                    ];
                }
                $cy_periodic->delete();
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
            $cy_periodic=CyPeriodic::find($request->id);
            if(!$cy_periodic){
                return [
                    'success'=>false,
                    'msg'=>translate('cy_periodic_not_found')
                ];
            }


            DB::beginTransaction();
            $cy_periodic->status=($cy_periodic->status - 1) *-1;
            $cy_periodic->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('cy_periodic updated successfully!')
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

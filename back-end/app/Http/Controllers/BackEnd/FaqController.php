<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\CategoryFaq;
use App\Models\Faq;
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

class FaqController extends Controller
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
        $this->middleware('CheckPermission:info_module,faqs,view')->only('index');
        $this->middleware('CheckPermission:info_module,faqs,create')->only('create','store');
        $this->middleware('CheckPermission:info_module,faqs,edit')->only('edit','update');
        $this->middleware('CheckPermission:info_module,faqs,delete')->only('destroy');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $faqs = Faq::listsTranslations('title')
                ->leftJoin('category_faq_translations', 'faqs.category_faq_id', '=', 'category_faq_translations.category_faq_id')
                ->select('faqs.*',
                    'category_faq_translations.title',
                )->groupBy('id');
            return DataTables::of($faqs)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('category_faq_name', function ($row) {
                    return $row->category_faq?->title;
                })
                ->addColumn('description', function ($row) {
                    return $row->description;
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" faq="menu">';

                            if (auth()->user()->can('info_module.faqs.edit')) {
                        $html .='<li>
                                                <a href="'. route('admin.faqs.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';
                            }

                        $html .= '<li class="divider"></li>';

                            if (auth()->user()->can('info_module.faqs.delete')) {
                        $html .=
                            '<li>
                                    <a data-href="' . route('admin.faqs.delete', $row->id)  . '"
                                        data-check_password="' . route('admin.checkPassword', Auth::id()) . '"
                                        class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                        ' . __('lang.delete') . '</a>
                                    </li>';
                            }



                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'category_faq_name',
                    'description',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.faqs.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_faqs=CategoryFaq::listsTranslations('title as name')->pluck('name','id');

        return view('back-end.faqs.create',compact('category_faqs'));
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
            'category_faq_id' => 'required|integer|exists:category_faqs,id',
            'title' => 'required|string',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.title' => 'required|max:255',
            'translations.*.description' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
        try {
            DB::beginTransaction();
            $data=$request->translations;
            $data['category_faq_id']=$request->category_faq_id;
            $faq = Faq::create($data);

            DB::commit();
            $output = [
                'code' => 200,
                'faq_id' => $faq->id,
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


//        return redirect()->to('faq')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
        $category_faqs=CategoryFaq::listsTranslations('title as name')->pluck('name','id');

        return view('back-end.faqs.edit')->with(compact(
            'faq',
            'category_faqs'
        ));
    }


    public function update(Request $request, $id)
    {

        $validator = validator($request->all(), [
            'category_faq_id' => 'required|integer|exists:category_faqs,id',
            'title' => 'required|string',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.title' => 'required|max:255',
            'translations.*.description' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }


        try {
            DB::beginTransaction();
            $faq = Faq::find($id);
            $faq->category_faq_id=$request->category_faq_id;
            $faq->save();
            $faq->update($request->translations);
            DB::commit();
            $output = [
                'code' => 200,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'code' => 500,
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
            $faq = Faq::find($id);
            if ($faq){
                if($faq->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_faq_cannot_be_deleted')
                    ];
                }
                $faq->delete();
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
            $faq=Faq::find($request->id);
            if(!$faq){
                return [
                    'success'=>false,
                    'msg'=>translate('faq_not_found')
                ];
            }


            DB::beginTransaction();
            $faq->status=($faq->status - 1) *-1;
            $faq->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('faq updated successfully!')
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

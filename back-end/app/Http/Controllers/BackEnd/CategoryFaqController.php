<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFaqResource;
use App\Models\CategoryFaq;
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

class CategoryFaqController extends Controller
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
        $this->middleware('CheckPermission:info_module,category_faqs,view')->only('index');
        $this->middleware('CheckPermission:info_module,category_faqs,create')->only('create','store');
        $this->middleware('CheckPermission:info_module,category_faqs,edit')->only('edit','update');
        $this->middleware('CheckPermission:info_module,category_faqs,delete')->only('destroy');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $category_faqs = CategoryFaq::listsTranslations('title')
                ->select('category_faqs.*',
                    'category_faq_translations.title',
                )->groupBy('id');
            return DataTables::of($category_faqs)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')

                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" category_faq="menu">';

                            if (auth()->user()->can('info_module.category_faqs.delete')) {
                        $html .='<li>
                                                <a href="'. route('admin.category_faqs.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';
                            }

                        $html .= '<li class="divider"></li>';

                            if (auth()->user()->can('info_module.category_faqs.delete')) {
                        $html .=
                            '<li>
                                    <a data-href="' . route('admin.category_faqs.delete', $row->id)  . '"
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
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.category_faqs.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back-end.category_faqs.create');
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
            $data=$request->translations;
            $data['sort']=$request->sort;
            $category_faq = CategoryFaq::create($data);

            DB::commit();
            $output = [
                'code' => 200,
                'category_faq_id' => $category_faq->id,
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


//        return redirect()->to('category_faq')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category_faq = CategoryFaq::find($id);

        return view('back-end.category_faqs.edit')->with(compact(
            'category_faq'
        ));
    }


    public function update(Request $request, $id)
    {

        $validator = validator($request->all(), [
            'sort' => 'required|integer',
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
            $category_faq = CategoryFaq::find($id);
            $category_faq->sort=$request->sort;
            $category_faq->save();
            $category_faq->update($request->translations);

            DB::commit();
            $output = [
                'code' => 200,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
//            dd($e);
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
            $category_faq = CategoryFaq::find($id);
            if ($category_faq){
                if($category_faq->id == 1){
                    return [
                        'success' => false,
                        'msg' => __('lang.This_category_faq_cannot_be_deleted')
                    ];
                }
                $category_faq->delete();
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

<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;

use App\Http\Requests\Dashboard\ContactUsRequest;
use App\Models\ContactUs;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class ContactUsController extends Controller
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
            $contact_us = ContactUs::leftjoin('country_translations', 'country_translations.country_id', 'contact_us.country_id')
               ->select('contact_us.*') ->groupBy('contact_us.id');
            return DataTables::of($contact_us)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->addColumn('country_name', function ($row) {
                    return $row->country?->title;
                })->editColumn('note', function ($row) {
                    return  substr(strip_tags($row->note), 0, 100);
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '';
                        $html.='<a data-href="'.route('admin.contact_us.show', $row->id).'"
                           data-container=".view_modal" class="btn btn-modal a-image" title="'.__('lang.show').'">
                           <i class="far fa-eye"></i> </a>
                        </a>';

//                            if (auth()->contact_us()->can('contact_us_module.contact_us.delete')) {
                                $html .=
                                    '
                                    <a data-href="' . route('admin.contact_us.delete', $row->id)  . '"
                                        data-check_password="' . route('admin.checkPassword', Auth::id()) . '"
                                        class="btn text-red delete_item" title="' . __('lang.delete') . '"><i class="dripicons-trash"></i>
                                        </a>
                                    ';
//                            }



                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'country_name',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.messages.contact_us.index');
    }


    public function show($id)
    {
        $ContactUs = ContactUs::find($id);
        return view('back-end.messages.contact_us.show')->with(compact(
            'ContactUs'
        ));
    }
    public function sendMessage(ContactUsRequest $request)
    {
        $r=$this->commonUtil->SendSMS($request->replay, '966'.$request->phone);
        if($r['status']){
            $output = [
                'success' => $r['status'],
                'msg' => __('lang.doneSendMessage')
            ];
        }else{
            $output = [
                'success' => $r['status'],
                'msg' => $r['message']
            ];
        }

        return redirect()->back()->with('status', $output);

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
            $contact_us = ContactUs::find($id);
            if ($contact_us){
                $contact_us->delete();
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

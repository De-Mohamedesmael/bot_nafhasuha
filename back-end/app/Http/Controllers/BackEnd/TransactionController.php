<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Bank;
use App\Models\Category;
use App\Models\City;
use App\Models\Provider;
use App\Models\Slider;
use App\Models\Transaction;
use App\Models\System;
use App\Models\User;
use App\Models\UserRequest;
use App\Utils\TransactionUtil;
use App\Utils\ServiceUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class TransactionController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;
    protected $serviceUtil;


    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @param ServiceUtil $serviceUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil,ServiceUtil $serviceUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
        $this->serviceUtil = $serviceUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type=null)
    {
        if (request()->ajax()) {

            $query = Transaction::leftJoin('users', 'users.id', '=', 'transactions.user_id')
                ->leftJoin('providers', 'providers.id', '=', 'transactions.provider_id')

                ->select('transactions.*',
                'users.name as client_name',
                'users.phone as client_phone',
                'providers.name as provider_name',
                'providers.phone as provider_phone',
            );
            ///'pending','approved','received','completed','declined','canceled'

            if($type){

                switch ($type){
                    case 'provider':
                        $query= $query->whereNull('user_id');
                        break;
                    case 'user':
                        $query= $query->whereNull('provider_id');
                        break;

                }


            }

            if (!empty(request()->type_data)) {
                $query->where('transactions.type', request()->type_data);
            }else{
                $query->wherein('transactions.type', ['TopUpCredit','JoiningBonus','InvitationBonus','Withdrawal']);

            }

            if (!empty(request()->provider_id)) {
                $query->where('transactions.provider_id', request()->provider_id);
            }
            if (!empty(request()->status)) {
                switch (request()->status){
                    case 'pending':
                    case 'received':
                        $query->where('transactions.status',request()->status );
                        break;

                    default :
                        $query->wherein('transactions.status', ['declined','canceled']);
                        break;
                }

            }

            if (!empty(request()->user_id)) {
                $query->where('transactions.user_id', request()->user_id);
            }

            if (!empty(request()->start_date)) {
                $query->whereDate('transactions.created_at', '>=', request()->start_date);
            }
            if (!empty(request()->end_date)) {
                $query->whereDate('transactions.created_at', '<=', request()->end_date);
            }
            if (!empty(request()->start_time)) {
                $query->where('transactions.created_at', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }
            if (!empty(request()->end_time)) {
                $query->where('transactions.created_at', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
            }
            if (!empty(request()->complete_start_date)) {
                $query->whereDate('transactions.completed_at', '>=', request()->complete_start_date);
            }
            if (!empty(request()->complete_end_date)) {
                $query->whereDate('transactions.completed_at', '<=', request()->complete_end_date);
            }
            if (!empty(request()->complete_start_time)) {
                $query->where('transactions.completed_at', '>=', request()->complete_start_date . ' ' . Carbon::parse(request()->complete_start_time)->format('H:i:s'));
            }
            if (!empty(request()->complete_end_time)) {
                $query->where('transactions.completed_at', '<=', request()->complete_end_date . ' ' . Carbon::parse(request()->complete_end_time)->format('H:i:s'));
            }

            return DataTables::of($query)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('completed_at', '{{@format_datetime($completed_at)}}')
                ->editColumn('updated_at', '{{@format_datetime($updated_at)}}')
                ->editColumn('final_total', '{{@num_format($final_total)}}')
                ->editColumn('canceled_by', function ($row) {
                    if (!in_array($row->status,['declined','canceled']))
                        return'';

                    $type=$row->canceled_type;
                    $name=$row->canceledby?->name;
                    return __('lang.'.$type).' => '.$name;
                })
                ->editColumn('canceled_by', function ($row) {
                    if (!in_array($row->status,['declined','canceled']))
                        return'';

                    $type=$row->canceled_type;
                    $name=$row->canceledby?->name;
                    return __('lang.'.$type).' => '.$name;
                })->editColumn('created_by', function ($row) {


                    $type=$row->created_by_type;
                    if( $type == null){
                        return'';
                    }
                    $name=$row->createdby?->name;
                    return __('lang.'.$type).' => '.$name;


                })
                ->editColumn('status', function ($row) {
                    switch ($row->status){
                        case 'pending':
                            $class='pending';
                            break;
                        case 'approved':
                        case 'PickUp':
                            $class='approved';
                            break;
                        case 'completed':
                        case  'received':
                            $class='completed';
                            break;

                        case 'canceled':
                        case 'declined':
                            $class='canceled';
                            break;
                    }
                    $html ='<span class="span-status '.$class.'"> '.__('lang.'.$class).'</span>';

                    return $html;
                })->editColumn('type', function ($row) {

                    $class=$row->type;
                    $html ='<span class="span-status '.$class.'"> '.__('lang.'.$class).'</span>';

                    return $html;
                })
                ->addColumn('type_phone', function ($row) {

                        return $row->provider_phone?:$row->client_phone;
                })->addColumn('type_name', function ($row) {

                        return $row->provider_name?:$row->client_name;
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
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        $html .= '<li class="divider"></li>';
                        if($row->status == 'pending'  ){


                            //                        if (auth()->user()->can('customer_module.customer.accept_transaction')){
                            $html .='<li >
                                                        <a href = "'. route('admin.transaction.accept',  ['id'=>$row->id]).'"><i
                                                                class="fa fa-money btn" ></i > '. __('lang.accept_transaction').' </a >
                                                    </li >';

                            //                        }
                        }


                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'type',
                    'status',
                    'type_phone',
                    'type_name',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'completed_at',
                ])
                ->make(true);
        }


        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $areas = Area::listsTranslations('title as name')->pluck('name','id');
        $users = User::pluck('name','id');
        $providers = Provider::pluck('name','id');

        return view('back-end.transactions.index')->with([
          'type'=>$type,
          'categories'=>$categories,
          'cities'=>$cities,
          'areas'=>$areas,
          'users'=>$users,
          'providers'=>$providers,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers=Provider::pluck('name','id');
        $banks = Bank::listsTranslations('title as name')->pluck('name','id');

        return view('back-end.transactions.create')->with(compact(
            'banks',
            'providers'
        ));
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
            'provider_id' => 'required|integer|exists:providers,id',
            'bank_id' => 'required|integer|exists:banks,id',
            'full_name' => 'required|string|max:100',
            'iban' => 'required|string',
            'paid_on' => 'required|Date',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }

        try {
            $provider=Provider::whereId($request->provider_id)->first();
            if (!$provider) {
                return [
                    'code' => 500,
                    'msg'=>translate('provider_not_found')
                ];
            }
            $my_wallet=$this->transactionUtil->getWalletProviderBalance($provider);


            if($my_wallet < $request->amount){
                return [
                    'code' => 500,
                    'msg'=>__('messages.Sorry_the_current_balance_is',['amount'=>$my_wallet])
                ];
            }
            DB::beginTransaction();
            $date=date('Y-m-d', strtotime($request->paid_on));
            $admin_id=\auth()->id();
            $this->transactionUtil->saveProviderWithdrawalRequest($provider,$request->bank_id,$request->full_name,$request->amount,$request->iban,$date,$admin_id);
            DB::commit();
            $output = [
                'code' => 200,
                'provider_id' => $request->provider_id,
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


//        return redirect()->to('provider')->with('status', $output);
    }


    public function accept($id ){

        try {
            $Transaction=Transaction::where('status','pending')->whereId($id)->first();

            if(!$Transaction){
                $output = [
                    'success'=>false,
                    'msg'=>translate('Transaction_not_found')
                ];
            }else{

                DB::beginTransaction();
                $Transaction->status='received';
                $Transaction->completed_at=now();
                $Transaction->save();
                DB::commit();
                $output = [
                    'success'=>true,
                    'msg'=>translate('Transaction updated successfully!')
                ];
            }


        }catch (\Exception $e){
            DB::rollback();
            $output = [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
        return redirect()->back()->with('status', $output);

    }

}

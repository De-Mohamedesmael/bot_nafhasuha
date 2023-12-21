<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProviderHomeResource;

use App\Models\City;
use App\Models\OrderService;
use App\Models\Provider;
use App\Models\System;
use App\Models\User;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    protected $commonUtil;
    protected $productUtil;
    protected $transactionUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->middleware('auth');
        $this->commonUtil = $commonUtil;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $start_date = new Carbon('first day of this month');
        $end_date = new Carbon('last day of this month');
        $cities =City::listsTranslations('title as name')->pluck('name','id');


        $providers = Provider::with(['orders'=>function($q){
            $q->wherein('order_services.status',['pending', 'approved','received']);
        }])->withAvg('rates as totalRate', 'rate')
            ->withCount(['orders'=>function ($q) {
                $q->wherein('status',['completed']);
            }])->get();
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        $userCounts = [];
        $userCounts_all = [];
        $providerCounts = [];
        $providerCounts_all = [];
        $Complete =[];
        $Canceled=[];
        $Pending=[];
        $currentYear = now()->year;

        $usersByMonthTotal = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $usersByMonthThisYear = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $providersByMonthTotal = Provider::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $providersByMonthThisYear = Provider::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();



        $AmountCompletedByMonthThisYear=OrderService::leftJoin('transactions', 'transactions.id', '=', 'order_services.transaction_id')
               -> wherein('order_services.status',  ['completed'])
            ->select(DB::raw('MONTH(order_services.created_at) as month'),
                DB::raw('sum(transactions.final_total) as sum_final_total'))
            ->whereYear('order_services.created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $AmountCanceledByMonthThisYear=OrderService::leftJoin('transactions', 'transactions.id', '=', 'order_services.transaction_id')
        ->wherein('order_services.status',  ['declined','canceled'])
            ->select(DB::raw('MONTH(order_services.created_at) as month'),
                DB::raw('sum(transactions.final_total) as sum_final_total'))
            ->whereYear('order_services.created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $AmountPendingByMonthThisYear=OrderService::leftJoin('transactions', 'transactions.id', '=', 'order_services.transaction_id')
            ->wherein('order_services.status',  ['pending', 'approved','received'])
            ->select(DB::raw('MONTH(order_services.created_at) as month'),
                DB::raw('sum(transactions.final_total) as sum_final_total'))
            ->whereYear('order_services.created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $AmountCompleted=0;
        $AmountPending=0;
        $AmountCanceled=0;
        foreach ($months as $month) {
            $userCount = $usersByMonthTotal->where('month', array_search($month, $months) + 1)->first();
            $userCountThisYear = $usersByMonthThisYear->where('month', array_search($month, $months) + 1)->first();
            $providerCount = $providersByMonthTotal->where('month', array_search($month, $months) + 1)->first();
            $providerCountThisYear = $providersByMonthThisYear->where('month', array_search($month, $months) + 1)->first();




            $CompletedCount = $AmountCompletedByMonthThisYear->where('month', array_search($month, $months) + 1)->first();
            $PendingThisYear = $AmountPendingByMonthThisYear->where('month', array_search($month, $months) + 1)->first();
            $CanceledThisYear = $AmountCanceledByMonthThisYear->where('month', array_search($month, $months) + 1)->first();

            $userCounts[] = $userCount ? $userCount->count : 0;
            $userCounts_all[] = $userCountThisYear ? $userCountThisYear->count : 0;
            $providerCounts[] = $providerCount ? $providerCount->count : 0;
            $providerCounts_all[] = $providerCountThisYear ? $providerCountThisYear->count : 0;


            $Complete[] = $CompletedCount ? $CompletedCount->sum_final_total : 0;
            $Canceled[] = $CanceledThisYear ? $CanceledThisYear->sum_final_total : 0;
            $Pending[] = $PendingThisYear ? $PendingThisYear->sum_final_total : 0;
            $AmountCompleted+=$PendingThisYear ? $PendingThisYear->sum_final_total : 0;
            $AmountPending +=$PendingThisYear ? $PendingThisYear->sum_final_total : 0;
            $AmountCanceled +=$CanceledThisYear ? $CanceledThisYear->sum_final_total : 0;
        }
        $userCountsString = '[' . implode(', ', $userCounts) . ']';
        $userCountsAllString = '[' . implode(', ', $userCounts_all) . ']';
        $providerCountsString = '[' . implode(', ', $providerCounts) . ']';
        $providerCountsAllString = '[' . implode(', ', $providerCounts_all) . ']';


        $CompleteString = '[' . implode(', ', $Complete) . ']';
        $CanceledString = '[' . implode(', ', $Canceled) . ']';
        $PendingString = '[' . implode(', ', $Pending) . ']';
        $providers= ProviderHomeResource::collection($providers);
        return view('back-end.home.index')->with([
            'cities'=>$cities,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'providers'=>$providers,
            'userCountsString'=>$userCountsString,
            'userCountsAllString'=>$userCountsAllString,
            'providerCountsString'=>$providerCountsString,
            'providerCountsAllString'=>$providerCountsAllString,
            'CompleteString'=>$CompleteString,
            'CanceledString'=>$CanceledString,
            'PendingString'=>$PendingString,
            'AmountCompleted'=>$AmountCompleted,
            'AmountPending'=>$AmountPending,
            'AmountCanceled'=>$AmountCanceled,
        ]);
    }

    /**
     * show the help page content
     *
     * @return void
     */
    public function getHelp()
    {
        $help_page_content = System::getProperty('help_page_content');

        return view('home.help')->with(compact(
            'help_page_content'
        ));
    }


}

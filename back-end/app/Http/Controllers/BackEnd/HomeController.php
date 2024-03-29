<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProviderHomeResource;

use App\Models\City;
use App\Models\OrderService;
use App\Models\Provider;
use App\Models\System;
use App\Models\Transaction;
use App\Models\User;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $start_date = Carbon::now()->firstOfYear();
        $end_date = new Carbon('last day of this month');
        $cities =City::listsTranslations('title as name')->pluck('name','id');
        $providers = Provider::with(['orders'=>function($q){
            $q->wherein('order_services.status',['pending', 'approved','PickUp','received']);
        }])->withAvg('rates as totalRate', 'rate')
            ->withCount(['orders'=>function ($q) {
                $q->wherein('status',['completed']);
            }])->get();
        $providers= ProviderHomeResource::collection($providers);
        return view('back-end.home.index')->with([
            'cities'=>$cities,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'providers'=>$providers,
        ]);
    }

    /**
     * get Chart  data forDashboard
     *
     */
    public function getChartData()
    {
        $start_date = !empty(request()->start_date) ? request()->start_date : Carbon::now()->firstOfYear();
        $end_date = !empty(request()->end_date) ? request()->end_date : new Carbon('last day of this month');

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $providerCounts = [];
        $providerCounts_all = [];
//        $currentYear = now()->year;

        $usersByMonthTotal = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
        if (!empty(request()->area_id)) {
            $usersByMonthTotal =   $usersByMonthTotal->where('area_id',  request()->area_id);
        }elseif (!empty(request()->city_id)) {
            $usersByMonthTotal =    $usersByMonthTotal->whereIn('city_id', request()->city_id);

        }
        $usersByMonthTotal =  $usersByMonthTotal->groupBy('month')
            ->orderBy('month')
            ->get();

        $usersByMonthThisYear = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
//            ->whereYear('created_at', $currentYear)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
        if (!empty(request()->area_id)) {
            $usersByMonthThisYear = $usersByMonthThisYear->where('area_id',  request()->area_id);
        }elseif (!empty(request()->city_id)) {
            $usersByMonthThisYear =   $usersByMonthThisYear->whereIn('city_id', request()->city_id);

        }
        $usersByMonthThisYear =  $usersByMonthThisYear->groupBy('month')
            ->orderBy('month')
            ->get();

        $providersByMonthTotal = Provider::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
        if (!empty(request()->area_id)) {
            $providersByMonthTotal =     $providersByMonthTotal->where('area_id',  request()->area_id);
        }elseif (!empty(request()->city_id)) {
            $providersByMonthTotal =      $providersByMonthTotal->whereIn('city_id', request()->city_id);

        }
        $providersByMonthTotal =  $providersByMonthTotal->groupBy('month')
            ->orderBy('month')
            ->get();

        $providersByMonthThisYear = Provider::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
//            ->whereYear('created_at', $currentYear)
                ->wherehas('orders')
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
            if (!empty(request()->area_id)) {
                $providersByMonthThisYear =  $providersByMonthThisYear->where('area_id',  request()->area_id);
            }elseif (!empty(request()->city_id)) {
                $providersByMonthThisYear =    $providersByMonthThisYear->whereIn('city_id', request()->city_id);

            }
        $providersByMonthThisYear = $providersByMonthThisYear ->groupBy('month')
            ->orderBy('month')
            ->get();

        foreach ($months as $month) {
           $providerCount = $providersByMonthTotal->where('month', array_search($month, $months) + 1)->first();
            $providerCountThisYear = $providersByMonthThisYear->where('month', array_search($month, $months) + 1)->first();

            $providerCounts[] = $providerCount ? $providerCount->count : 0;
            $providerCounts_all[] = $providerCountThisYear ? $providerCountThisYear->count : 0;
        }
        $providerCountsString = '[' . implode(', ', $providerCounts) . ']';
        $providerCountsAllString = '[' . implode(', ', $providerCounts_all) . ']';



        $data=[
            'success'=>true,
//            'userCountsString'=>$userCountsString,
//            'userCountsAllString'=>$userCountsAllString,
            'providerCountsString'=>$providerCountsString,
            'providerCountsAllString'=>$providerCountsAllString,

        ];
        return $data;
    }

    /**
     * get Counter data for Dashboard
     *
     */
    public function getCounterData()
    {
        $start_date = !empty(request()->start_date) ? request()->start_date : Carbon::now()->firstOfYear();
        $end_date = !empty(request()->end_date) ? request()->end_date : new Carbon('last day of this month');

        $count_users=User::where('created_at', '>=', $start_date)
        ->where('created_at', '<=', $end_date);
        if (!empty(request()->area_id)) {
            $count_users =   $count_users->where('area_id',  request()->area_id);
        }elseif (!empty(request()->city_id)) {
            $count_users =    $count_users->whereIn('city_id', request()->city_id);

        }
        $count_users=$count_users->count();

        $count_providers=Provider::where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date);
        if (!empty(request()->area_id)) {
            $count_providers =   $count_providers->where('area_id',  request()->area_id);
        }elseif (!empty(request()->city_id)) {
            $count_providers =    $count_providers->whereIn('city_id', request()->city_id);

        }
        $count_providers=$count_providers->count();

        $Transaction = OrderService::join('transactions', 'transactions.id', 'order_services.transaction_id')
        ->join('users', 'users.id', 'order_services.user_id');

        if (!empty($start_date)) {
            $Transaction= $Transaction->whereDate('order_services.created_at', '>=', $start_date);
        }
        if (!empty($end_date)) {
            $Transaction= $Transaction->whereDate('order_services.created_at', '<=', $end_date);
        }

        if (!empty(request()->area_id)) {
            $Transaction =   $Transaction->where('users.area_id',  request()->area_id);
        }elseif (!empty(request()->city_id)) {
            $Transaction =    $Transaction->whereIn('users.city_id', request()->city_id);

        }
        $Transaction = $Transaction->select(
            DB::raw('SUM(IF(order_services.status in ("pending","approved","received"),transactions.final_total,0)) as total_pending'),
            DB::raw('SUM(IF(order_services.status = "completed",transactions.final_total,0)) as total_completed'),
            DB::raw('SUM(IF(order_services.status in ("declined","canceled"),transactions.final_total,0)) as total_canceled'),
        )->first();
        $total_orders_pending=$Transaction->total_pending??0;
        $total_orders_completed=$Transaction->total_completed??0;
        $total_orders_canceled=$Transaction->total_canceled??0;


        $data=[
            'success'=>true,
            'count_users'=>$count_users,
            'count_providers'=>$count_providers,
            'total_orders_pending'=>(int)$total_orders_pending,
            'total_orders_completed'=>(int)$total_orders_completed,
            'total_orders_canceled'=>(int)$total_orders_canceled,
        ];


        return $data;
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

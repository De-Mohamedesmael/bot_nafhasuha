<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Transaction;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class ReportController extends Controller
{

    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->middleware('CheckPermission:reports,daily_report,special')->only('getDailyReport');
        $this->middleware('CheckPermission:reports,monthly_report,special')->only('getMonthlyReport');
        $this->middleware('CheckPermission:reports,yearly_report,special')->only('getYearlyReport');
        $this->middleware('CheckPermission:reports,best_report,special')->only('getBestReport');

    }


    /**
     * show the daily sale report
     *
     * @return view
     */
    public function getDailyReport(Request $request)
    {
        $method = $request->payment_type;
        $city_id = $request->city_id;
        $area_id = $request->area_id;

        $year = request()->year;
        $month = request()->month;

        if (empty($year)) {
            $year = Carbon::now()->year;
        }
        if (empty($month)) {
            $month = Carbon::now()->month;
        }
        $start = 1;
        $number_of_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        while ($start <= $number_of_day) {
            if ($start < 10) {
                $date = $year . '-' . $month . '-0' . $start;
            } else {
                $date = $year . '-' . $month . '-' . $start;
            }

            $query = Transaction::leftjoin('users', 'users.id', 'transactions.user_id')
                ->leftjoin('providers', 'providers.id', 'transactions.provider_id')
                ->leftjoin('order_services', 'order_services.transaction_id', 'transactions.id')
                ->whereDate('transactions.created_at', $date);
//            'TopUpCredit','OrderService','JoiningBonus','InvitationBonus','Withdrawal'
//            'pending','approved','received','completed','declined','canceled'

            if (!empty($area_id)) {
                $query->where(function ($query) use ($area_id) {
                   return $query->where('users.area_id', $area_id)
                       ->orwhere(function ($query2) use ($area_id) {
                           return $query2->where('providers.area_id', $area_id)
                               ->wherein('transactions.type',['TopUpCredit','Withdrawal']);
                       });
                });
            }elseif (!empty($city_id)) {
                $query->where(function ($query) use ($city_id) {
                    return $query->where('users.city_id', $city_id)
                        ->orwhere(function ($query2) use ($city_id) {
                            return $query2->where('providers.city_id', $city_id)
                                ->wherein('transactions.type',['TopUpCredit','Withdrawal']);
                        });
                });
            }

            if (!empty($method)) {
                $query->where('order_services.payment_method', $method);
            }

            $data_query = $query->select(
                DB::raw('count(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.id, 0))  AS count_order_completed'),
                DB::raw('count(IF(transactions.type="OrderService", transactions.id, 0))  AS count_all_order'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.deducted_total, 0))  AS sum_deducted_total'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.final_total, 0))  AS sum_final_total'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.discount_amount, 0))  AS sum_discount_amount'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status in( "canceled","declined"), transactions.final_total, 0))  AS sum_final_total_canceled'),
                DB::raw('SUM(IF(transactions.type="TopUpCredit" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_TopUpCredit'),
                DB::raw('SUM(IF(transactions.type="Withdrawal" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_Withdrawal'),
                DB::raw('SUM(IF(transactions.type="InvitationBonus" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_InvitationBonus'),
                DB::raw('SUM(IF(transactions.type="JoiningBonus" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_JoiningBonus'),
            )->first();


            $data['count_all_order'][$start]=$data_query->count_all_order;
            $data['count_order_completed'][$start]=$data_query->count_order_completed;
            $data['sum_deducted_total'][$start]=$data_query->sum_deducted_total;
            $data['sum_provider_total'][$start]=$data_query->sum_final_total - $data_query->sum_deducted_total;
            $data['sum_final_total'][$start]=$data_query->sum_final_total;
            $data['sum_final_total_canceled'][$start]=$data_query->sum_final_total_canceled;
            $data['sum_final_total_TopUpCredit'][$start]=$data_query->sum_final_total_TopUpCredit;
            $data['sum_final_total_Withdrawal'][$start]=$data_query->sum_final_total_Withdrawal;
            $data['sum_final_total_InvitationBonus'][$start]=$data_query->sum_final_total_InvitationBonus;
            $data['sum_final_total_JoiningBonus'][$start]=$data_query->sum_final_total_JoiningBonus;
            $data['total_discount'][$start] = $data_query->sum_discount_amount;
            $start++;
        }




        $start_day = date('w', strtotime($year . '-' . $month . '-01')) + 1;
        $prev_year = date('Y', strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
        $prev_month = date('m', strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
        $next_year = date('Y', strtotime('+1 month', strtotime($year . '-' . $month . '-01')));
        $next_month = date('m', strtotime('+1 month', strtotime($year . '-' . $month . '-01')));
        $cities =City::listsTranslations('title as name')->pluck('name','id');
        $payment_types = $this->commonUtil->getPaymentTypeArray();

        return view('back-end.reports.daily_report', compact(
            'data',
            'start_day',
            'year',
            'month',
            'number_of_day',
            'prev_year',
            'prev_month',
            'next_year',
            'next_month',
            'cities',
            'payment_types'
        ));
    }
    /**
     * show the monthly  report
     *
     * @return view
     */
    public function getMonthlyReport(Request $request)
    {
        $method = $request->payment_type;
        $city_id = $request->city_id;
        $area_id = $request->area_id;

        $year = request()->year;

        if (empty($year)) {
            $year = Carbon::now()->year;
        }

        $start = strtotime($year . '-01-01');
        $end = strtotime($year . '-12-31');
        $i=1;
        while ($start <= $end) {
            $start_date = $year . '-' . date('m', $start) . '-' . '01';
            $end_date = $year . '-' . date('m', $start) . '-' . '31';

            $total_query = Transaction::leftjoin('users', 'users.id', 'transactions.user_id')
                ->leftjoin('providers', 'providers.id', 'transactions.provider_id')
                ->leftjoin('order_services', 'order_services.transaction_id', 'transactions.id')
                ->whereDate('transactions.created_at', '>=', $start_date)
                ->whereDate('transactions.created_at', '<=', $end_date);


//            if (!empty($request->start_date)) {
//                $total_query =  $total_query->whereDate('transactions.created_at', '>=', $request->start_date);
//            }
//            if (!empty($request->end_date)) {
//                $total_query =  $total_query->whereDate('transactions.created_at', '<=', $request->end_date);
//            }
//            if (!empty(request()->start_time)) {
//                $total_query =  $total_query->where('transactions.created_at', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
//            }
//            if (!empty(request()->end_time)) {
//                $total_query =  $total_query->where('transactions.created_at', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
//            }

            if (!empty($area_id)) {
                $total_query =  $total_query->where(function ($query) use ($area_id) {
                    return $query->where('users.area_id', $area_id)
                        ->orwhere(function ($query2) use ($area_id) {
                            return $query2->where('providers.area_id', $area_id)
                                ->wherein('transactions.type',['TopUpCredit','Withdrawal']);
                        });
                });
            }elseif (!empty($city_id)) {
                $total_query =  $total_query->where(function ($query) use ($city_id) {
                    return $query->where('users.city_id', $city_id)
                        ->orwhere(function ($query2) use ($city_id) {
                            return $query2->where('providers.city_id', $city_id)
                                ->wherein('transactions.type',['TopUpCredit','Withdrawal']);
                        });
                });
            }

            if (!empty($method)) {
                $total_query =  $total_query->where('order_services.payment_method', $method);
            }


            $data_query = $total_query->select(
                DB::raw('count(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.id, 0))  AS count_order_completed'),
                DB::raw('count(IF(transactions.type="OrderService", transactions.id, 0))  AS count_all_order'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.deducted_total, 0))  AS sum_deducted_total'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.final_total, 0))  AS sum_final_total'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.discount_amount, 0))  AS sum_discount_amount'),
                DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status in( "canceled","declined"), transactions.final_total, 0))  AS sum_final_total_canceled'),
                DB::raw('SUM(IF(transactions.type="TopUpCredit" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_TopUpCredit'),
                DB::raw('SUM(IF(transactions.type="Withdrawal" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_Withdrawal'),
                DB::raw('SUM(IF(transactions.type="InvitationBonus" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_InvitationBonus'),
                DB::raw('SUM(IF(transactions.type="JoiningBonus" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_JoiningBonus'),
            )->first();


            $data['count_all_order'][$i]=$data_query->count_all_order;
            $data['count_order_completed'][$i]=$data_query->count_order_completed;
            $data['sum_deducted_total'][$i]=$data_query->sum_deducted_total;
            $data['sum_provider_total'][$i]=$data_query->sum_final_total - $data_query->sum_deducted_total;
            $data['sum_final_total'][$i]=$data_query->sum_final_total;
            $data['sum_final_total_canceled'][$i]=$data_query->sum_final_total_canceled;
            $data['sum_final_total_TopUpCredit'][$i]=$data_query->sum_final_total_TopUpCredit;
            $data['sum_final_total_Withdrawal'][$i]=$data_query->sum_final_total_Withdrawal;
            $data['sum_final_total_InvitationBonus'][$i]=$data_query->sum_final_total_InvitationBonus;
            $data['sum_final_total_JoiningBonus'][$i]=$data_query->sum_final_total_JoiningBonus;
            $data['total_discount'][$i] = $data_query->sum_discount_amount;



            $start = strtotime("+1 month", $start);
            $i++;
        }
        $cities =City::listsTranslations('title as name')->pluck('name','id');
        $payment_types = $this->commonUtil->getPaymentTypeArray();

        return view('back-end.reports.monthly_report', compact(
            'year',
            'data',
            'cities',
            'payment_types'
        ));
    }


    /**
     * show the Yearly report
     *
     * @return view
     */
    public function getYearlyReport(Request $request)
    {

        $method = $request->payment_type;
        $city_id = $request->city_id;
        $area_id = $request->area_id;

        $year = request()->year??date('Y');
        $total_query = Transaction::leftjoin('users', 'users.id', 'transactions.user_id')
            ->leftjoin('providers', 'providers.id', 'transactions.provider_id')
            ->leftjoin('order_services', 'order_services.transaction_id', 'transactions.id')
            ->whereYear('transactions.created_at', '=', $year);

            if (!empty($request->start_date)) {
                $total_query =  $total_query->whereDate('transactions.created_at', '>=', $request->start_date);
            }
            if (!empty($request->end_date)) {
                $total_query =  $total_query->whereDate('transactions.created_at', '<=', $request->end_date);
            }
            if (!empty(request()->start_time)) {
                $total_query =  $total_query->where('transactions.created_at', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }
            if (!empty(request()->end_time)) {
                $total_query =  $total_query->where('transactions.created_at', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
            }

            if (!empty($area_id)) {
                $total_query =  $total_query->where(function ($query) use ($area_id) {
                    return $query->where('users.area_id', $area_id)
                        ->orwhere(function ($query2) use ($area_id) {
                            return $query2->where('providers.area_id', $area_id)
                                ->wherein('transactions.type',['TopUpCredit','Withdrawal']);
                        });
                });
            }elseif (!empty($city_id)) {
                $total_query =  $total_query->where(function ($query) use ($city_id) {
                    return $query->where('users.city_id', $city_id)
                        ->orwhere(function ($query2) use ($city_id) {
                            return $query2->where('providers.city_id', $city_id)
                                ->wherein('transactions.type',['TopUpCredit','Withdrawal']);
                        });
                });
            }

            if (!empty($method)) {
                $total_query =  $total_query->where('order_services.payment_method', $method);
            }

        $data_query = $total_query->select(
            DB::raw('count(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.id, 0))  AS count_order_completed'),
            DB::raw('count(IF(transactions.type="OrderService", transactions.id, 0))  AS count_all_order'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.deducted_total, 0))  AS sum_deducted_total'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.final_total, 0))  AS sum_final_total'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed" AND order_services.payment_method = "Online", transactions.final_total, 0))  AS sum_final_total_Online'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed" AND order_services.payment_method = "Wallet", transactions.final_total, 0))  AS sum_final_total_Wallet'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed" AND order_services.payment_method = "Cash", transactions.final_total, 0))  AS sum_final_total_Cash'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status = "completed", transactions.discount_amount, 0))  AS sum_discount_amount'),
            DB::raw('SUM(IF(transactions.type="OrderService" AND order_services.status in( "canceled","declined"), transactions.final_total, 0))  AS sum_final_total_canceled'),
            DB::raw('SUM(IF(transactions.type="TopUpCredit" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_TopUpCredit'),
            DB::raw('SUM(IF(transactions.type="Withdrawal" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_Withdrawal'),
            DB::raw('SUM(IF(transactions.type="InvitationBonus" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_InvitationBonus'),
            DB::raw('SUM(IF(transactions.type="JoiningBonus" AND transactions.status = "received", transactions.final_total, 0))  AS sum_final_total_JoiningBonus'),
        )->first();


        $data['count_all_order']=$data_query->count_all_order;
        $data['count_order_completed']=$data_query->count_order_completed;
        $data['sum_deducted_total']=$data_query->sum_deducted_total;
        $data['sum_provider_total']=$data_query->sum_final_total - $data_query->sum_deducted_total;
        $data['sum_final_total']=$data_query->sum_final_total;
        $data['sum_final_total_canceled']=$data_query->sum_final_total_canceled;
        $data['sum_final_total_TopUpCredit']=$data_query->sum_final_total_TopUpCredit;
        $data['sum_final_total_Withdrawal']=$data_query->sum_final_total_Withdrawal;
        $data['sum_final_total_InvitationBonus']=$data_query->sum_final_total_InvitationBonus;
        $data['sum_final_total_JoiningBonus']=$data_query->sum_final_total_JoiningBonus;
        $data['total_discount'] = $data_query->sum_discount_amount;
        $data['sum_final_total_Cash'] = $data_query->sum_final_total_Cash;
        $data['sum_final_total_Wallet'] = $data_query->sum_final_total_Wallet;
        $data['sum_final_total_Online'] = $data_query->sum_final_total_Online;

        $cities =City::listsTranslations('title as name')->pluck('name','id');
        $payment_types = $this->commonUtil->getPaymentTypeArray();
        return view('back-end.reports.yearly_report')->with(compact(
            'year',
            'data',
            'cities',
            'payment_types'
        ));
    }

}

<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\GiftCard;
use App\Models\Leave;
use App\Models\Store;
use App\Models\System;
use App\Models\Transaction;
use App\Models\TransactionSellLine;
use App\Models\WagesAndCompensation;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use App\Models\WagesAndCompensation;

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
        return view('back-end.home.index')->with(compact(
            'start_date',
            'end_date'
        ));
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

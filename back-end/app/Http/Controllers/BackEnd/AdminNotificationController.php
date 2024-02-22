<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Coupon;
use App\Models\FcmToken;
use App\Models\FcmTokenProvider;
use App\Models\Info;
use App\Models\Notification;
use App\Models\NotificationAdmin;
use App\Models\Provider;
use App\Models\User;
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

class AdminNotificationController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;
/**
* change status to as read
*
* @param int $id
* @return void
*/
    public function markAsRead($id)
    {
        try {
            $notification = NotificationAdmin::find($id);
            $notification->status = 'read';
            $notification->save();

        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());

        }
    }

    public function notificationSeen()
    {
        NotificationAdmin::where('admin_id', Auth::user()->id)->where('is_seen', 0)->update(['is_seen' => 1]);

        return true;
    }
}

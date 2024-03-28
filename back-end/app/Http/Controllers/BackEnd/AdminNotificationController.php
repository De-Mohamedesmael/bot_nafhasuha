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
    public function getDetails ($id)
    {
        try {
            $notification = NotificationAdmin::find($id);
                    if($notification->type == 'ContactUs'){
                            $url = "{{route('admin.contact_us.index')}}" ;
                            $icon='<i class="dripicons-conversation " style="color: rgb(255, 187, 60)"></i>';
                            $text='<span class="text-muted">'.  substr(strip_tags($notification->message), 0, 150) .'</span>';
                        }else{
                            $url = "{{route('admin.provider.index')}}" ;
                            $icon='<i class="fa fa-user " style="color: rgb(19, 35, 255)"></i>';
                            $text='<span class="text-muted">
                              '. __('lang.name') .':  '.$notification->provider?->name.'
                            </span> <br>
                            <span class="text-muted">
                              '. __('lang.phone') .':  '.$notification->provider?->phone.'
                            </span>';

                        }
                        
                        $HTML='<li>
                                <a class="unread notification_item"
                                    data-mark-read-action=""
                                    data-href="'.$url.'">
                                    <p style="margin:0px"> '.$icon.' '.__('notifications.admin.title.'.$notification->type).' </p>
                                    '.$text.'
                                </a>

                            </li>';
            return $HTML;

        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            return '';
        }
    }
    public function notificationSeen()
    {
        NotificationAdmin::where('admin_id', Auth::user()->id)->where('is_seen', 0)->update(['is_seen' => 1]);

        return true;
    }
}

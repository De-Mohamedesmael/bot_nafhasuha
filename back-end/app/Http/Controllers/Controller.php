<?php

namespace App\Http\Controllers;

use App\Models\AdsImage;
use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Notification as ModelNotification;
use App\Models\UserNotification;
use App\Models\NotificationNote;
use App\Models\NotificationTranslation;
use App\Models\NotificationNoteTranslation;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function files($files, $position, $id)
    {
        foreach ($files as $file) {
            $type = explode('/', $file->getClientMimeType())[0];
            if ($type == 'image' || $type == 'video') {
                AdsImage::create([
                    'ads_id' => $id,
                    'file' => $file,
                    'position' => $position,
                    'type' => $type == 'image' ? 'img' : 'video',
                ]);
            }
        }
    }
    
    public function pushNotof($type,$type_item,$user_id,$step=null){
        // type=> ,'FriendRequest'
        // type_item
        
        $NotificationNote= NotificationNote::where('type',$type)->first();  
        
        
        if($type == 'FriendRequest'|| $type =='FriendAccept'){
            $image=asset('assets/users').'/'.$type_item->image;
            $name=$type_item->name;
        }elseif($type =='Order'){
             $image=null;
             $name=null;
        }
        $Notification = ModelNotification::create([
            'is_new'=>1,
            'type'=>$type,
            'image'=>$image,
            'title_general'=>$NotificationNote->title,
        ]);
       if($type =='Order'){
            $Notification->type_id=$type_item->id;
            $Notification->save();
        } 
            
        foreach(NotificationNoteTranslation::where('notification_note_id',$NotificationNote->id)->get() as $item){
           NotificationTranslation::create([
                'notification_id'=>$Notification->id,
                'locale'=>$item->locale,
                'title'=>$item->title,
                'description'=>$name .' '.$item->description,
            ]); 
        }
        if(! is_array($user_id)){
            UserNotification::create([
            'user_id'=> $user_id,
            'notification_id'=>$Notification->id,
            ]); 
        }else{
            
            dd("is_array");
        }
            
        
        return true;
        
        
    }
}

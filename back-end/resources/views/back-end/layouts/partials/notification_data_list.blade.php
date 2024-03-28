
<li>
    @php
    switch($notification->type){
        case'NewProvider':
            $url=route('admin.provider.show',$notification->type_id);
            $icon='<i class="fa fa-user " style="color: rgb(19, 35, 255)"></i>';
        break;
        default:
            $url=route('admin.contact_us.index',['contact_id'=>$notification->type_id]);
            $icon='<i class="dripicons-conversation " style="color: rgb(255, 187, 60)"></i>';
        break;
    }



    @endphp
    <a class="{{$notification->status}} notification_item"
        data-mark-read-action="{{route('admin.admin-notifications.markAsRead', $notification->id)}}"
        data-href="{{$url}}">
        <p style="margin:0px">
            {!! $icon !!}
           ({{__('notifications.admin.title.'.$notification->type)}})
        </p>
        <br>

            @if($notification->type == 'NewProvider')
            <span class="text-muted">
              @lang('lang.name'):  {{$notification->provider?->name}}
            </span> <br>
            <span class="text-muted">
              @lang('lang.phone'):  {{$notification->provider?->phone}}
            </span>
            @else
            <span class="text-muted">
                {!!  substr(strip_tags($notification->message), 0, 150) !!}
             </span>
            @endif


    </a>
</li>

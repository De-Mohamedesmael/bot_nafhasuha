<p style='line-height: 1.7em; text-align: justify; font-size: 14px'>
    <b>Dear, {{$name}} </b>
</p>

<p style='line-height: 1.7em; text-align: justify; font-size: 14px'>
    <!--<b> {{__('api.title_email_for_send')}} </b>-->
    <b> {{__('api.title_email_for_send_old')}} </b>


    <b > {{$code}} </b>
    <br>
    <b> {{__('api.title_email_for_send2')}} </b>
</p>
<span style="color:darkblue"> {{__('api.if you have any questions you can contact with us in our email')}}, ({{env('MAIL_FROM_ADDRESS')}}) </span>
<strong>
    {{__('api.thank you')}} .
</strong>

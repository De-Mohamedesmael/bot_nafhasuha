<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name')}}</title>
</head>
<body>
{!! $body !!}


<span style="color:darkblue"> {{__('api.if you have any questions you can contact with us in our email')}}, ({{env('MAIL_FROM_ADDRESS')}}) </span>
<strong>
    {{__('api.thank you')}} .
</strong>
</body>
</html>

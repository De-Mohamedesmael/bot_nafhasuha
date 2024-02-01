@extends('back-end.layouts.app')
@section('title', __('lang.dashboard'))
@section('styles')
    <style>
        div#content {
            background-color: #ffffff;
        }
        div#map {
            margin: 25px;
            height: 400px;
            width: 95%;
            text-align: center;
        }

        div#map img {
            width:90px;
            height:90px;
            margin:5px;
            border-radius:10px;
        }
        .gm-style-iw.gm-style-iw-c {
            width: 350px !important;
        }
        .gm-ui-hover-effect>span {
            font-weight: 900;
            background-color: #2d0000;
            width: 20px !important;
            height: 20px !important;
            margin: 7px 0px !important;
        }
        .all-data {
            display: inline-flex;
            padding: 10px;
            width: 100%;
        }
        .data-right {
            margin-top: 5px;
        }
        .pending-info-map {
            display: inline-flex;
        }
        .img-info-map img {
            width: 50px !important;
            height: 50px !important;
            border-radius: 50% !important;
        }
        span.font-label-info-map {
            padding: 5px;
            /* color: #000; */
            font-weight: 400;
            font-size: 14px;
        }
        span.font-value-info-map {
            padding: 5px 0;
        }


        .rate-info-map .star{
            font-family: Wingdings;
            font-size: 18px;
            color: #ccc;
            display: inline-block;


        }
        .rate-info-map .star-mark {
            color: gold;
        }
        .rate-info-map .star:first-child:before{
            color: transparent;

        }
        .rate-info-map .star::before{
            font-family: Wingdings;
            font-size: 18px;
            content: "\2605";
        }
        .count-title {

            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px 0px #c1c1c1;
            padding: 20px 10px;
        }
    </style>
    <style>

        input[switch] + label {

            width: 75px !important;
        }

        span.class-Confirmed {
            color: #34c38f;
            background-color: #34c38f2e;
            padding: 2px 5px;
            border-radius: 5px;
        }

        span.class-Ongoing {
            color: #f1b44c;
            background-color: #f1b44c2e;
            padding: 2px 5px;
            border-radius: 5px;
        }

        span.class-Finished {
            color: #348cc3;
            background-color: rgba(52, 140, 195, 0.18);
            padding: 2px 5px;
            border-radius: 5px;
        }
        .mini-stats-wid .card-body{
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        span.color-success {
            color: #0fad01;
            font-weight: 500;
        }
        .counter {
            padding: 5px !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="container-fluid">
            <div class="col-md-12">

                <div class="filter-toggle btn-group">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="city_id"><b>@lang('lang.city')</b></label>
                            {!! Form::select('city_id', $cities,  false, ['class' => 'form-control ','data-live-search' => 'true', 'placeholder' => __('lang.please_select'), 'id' => 'city_id']) !!}
                        </div>
                        <div class="col-md-2">
                            <label for="area_id"><b>@lang('lang.area')</b></label>
                            {!! Form::select('area_id', [],false, ['class' => 'form-control ','data-live-search' => 'true', 'id' => 'area_id']) !!}
                        </div>
                        <div class="col-md-2">
                            <label for="from_date"><b>@lang('lang.from_date')</b></label>
                            <input type="date" class="form-control filter" name="from_date" id="from_date"
                                   value="{{ date('Y-01-01') }}" placeholder="{{ __('lang.from_date') }}">

                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="start_time"><b>@lang('lang.start_time')</b></label>
                                {!! Form::text('start_time', null, ['class' => 'form-control time_picker filter', 'id' => 'start_time']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="to_date"><b>@lang('lang.to_date')</b></label>
                            <input type="date" class="form-control filter" name="to_date" id="to_date"
                                   value="{{ date('Y-m-t') }}" placeholder="{{ __('lang.to_date') }}">
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="end_time"><b>@lang('lang.end_time')</b></label>
                                {!! Form::text('end_time', null, ['class' => 'form-control time_picker filter', 'id' => 'end_time']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="container-fluid mt-4">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 row ">
                        <div class="col-sm-6 counter">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-user-group" style="color: #498636"></i>

                                </div>
                                <div class="name"><strong
                                        style="color: #498636">{{\App\CPU\translate('count_users')}}</strong>
                                </div>
                                <div class="count-number current_users_value-data">
                                    {{ 0 }}</div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-6 counter">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-disc"
                                                     style="color: #733686"></i>
                                </div>
                                <div class="name"><strong
                                        style="color: #733686">{{\App\CPU\translate('count_providers')}}</strong>
                                </div>
                                <div class="count-number count_providers-data">{{0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 row ">
                        <!-- Count item widget-->
                        <div class="col-sm-4 counter">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-stopwatch" style="color: #ff8952"></i>
                                </div>
                                <div class="name"><strong
                                        style="color: #ff8952">{{\App\CPU\translate('total_orders_pending')}}</strong>
                                </div>
                                <div class="count-number">
                                    <span class="total_orders_pending-data">   {{ @num_format(0) }}</span> SAR
                                </div>
                            </div>
                        </div>
                        <!-- Count item widget-->
                        <div class="col-sm-4 counter">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-wallet" style="color: #297ff9"></i>
                                </div>
                                <div class="name"><strong
                                        style="color: #297ff9">{{\App\CPU\translate('total_orders_completed')}}</strong>
                                </div>
                                <div class="count-number "> <span class="total_orders_completed-data">{{ @num_format(0) }}</span> SAR
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 counter">
                            <div class="wrapper count-title text-center">
                                <div class="icon"><i class="dripicons-wrong" style="color: #f92929"></i>
                                </div>
                                <div class="name"><strong
                                        style="color: #f92929">{{\App\CPU\translate('total_orders_canceled')}}</strong>
                                </div>
                                <div class="count-number "> <span class="total_orders_canceled-data">{{ @num_format(0) }}</span> SAR
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="map">

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title mb-4">{{\App\CPU\translate('users_registration')}}</h4>
                                <canvas id="bar" height="150"></canvas>

                            </div>
                        </div>
                    </div> <!-- end col -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title mb-4">{{\App\CPU\translate('providers_registration')}}</h4>
                                <canvas id="bar_providers" height="150"></canvas>

                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title mb-4">{{\App\CPU\translate('amount_Subscribe')}}</h4>

                                <div class="row text-center">
                                    <div class="col-3">
                                        <h5 class="mb-0" id="AmountCompleted">{{$AmountCompleted??0}}</h5>
                                        <p class="text-muted text-truncate">{{\App\CPU\translate('chart_Completed')}}</p>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="mb-0 " id="AmountPending">{{$AmountPending??0}}</h5>
                                        <p class="text-muted text-truncate">{{\App\CPU\translate('chart_Pending')}}</p>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="mb-0" id="AmountCanceled">{{$AmountCanceled??0}}</h5>
                                        <p class="text-muted text-truncate">{{\App\CPU\translate('chart_Canceled')}}</p>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="mb-0" id="TotalAmount">{{--$AmountCompleted+$AmountPending+$AmountCanceled--}}</h5>
                                        <p class="text-muted text-truncate">{{\App\CPU\translate('total_amount')}}</p>
                                    </div>
                                </div>

                                <canvas id="lineChart" height="150"></canvas>

                            </div>
                        </div>
                    </div>

                    <!-- end col -->

                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    @if (auth()->user()->can('dashboard.details.view'))
    <script src="{{ URL::asset('assets/back-end/libs/chart.js/Chart.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#city_id').change();

        });
        $(document).on("change", '.filter, #city_id,#area_id', function() {
            var city_id = $('select#city_id').val();
            var area_id = $('select#area_id').val();
            var start_date = $('#from_date').val();
            if (!start_date) {
                start_date = 0;
            }
            var end_date = $('#to_date').val();
            if (!end_date) {
                end_date = 0;
            }
            getDashboardData(start_date, end_date,city_id,area_id);
        });


        $('#city_id').on('change', function(){
            var city_id =$(this).val();

            $.get( "{{url('/admin/city/areas')}}", { city_id: city_id })
                .done(function( data ) {
                    if(data.code == 200){
                        var e=data.data;

                        $('#area_id')
                            .find('option')
                            .remove();
                        $.each(e, function (key, val) {
                            $("#area_id").append('<option value="'+val.id+'" >'+val.title+'</option>');
                        });
                        $("#area_id").selectpicker("refresh");
                    }

                });

        });
        $('#start_time, #end_time').focusout(function(event) {
            var city_id = $('#city_id').val();
            var area_id = $('#area_id').val();
            var start_date = $('#from_date').val();
            if (!start_date) {
                start_date = 0;
            }
            var end_date = $('#to_date').val();
            if (!end_date) {
                end_date = 0;
            }

            getDashboardData(start_date, end_date,city_id,area_id);

        })
        var months =  [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];



        function initializeUserChart(userCountsString,userCountsAllString) {
            var userCounts = JSON.parse(userCountsString);
            var userCounts_all = JSON.parse(userCountsAllString);
            var ctx = document.getElementById('bar').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: '{{\App\CPU\translate('This year the number of registered users')}}',
                        data: userCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: '{{\App\CPU\translate('Number of registered users')}}',
                        data: userCounts_all,
                        backgroundColor: 'rgba(54,235,229,0.2)',
                        borderColor: 'rgb(54,235,235)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        function initializeProviderChart(providerCountsString,providerCountsAllString) {
            var providerCounts = JSON.parse(providerCountsString);
            var providerCounts_all = JSON.parse(providerCountsAllString);
            var ctx = document.getElementById('bar_providers').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: '{{\App\CPU\translate('This year the number of registered users')}}',
                        data: providerCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: '{{\App\CPU\translate('Number of registered users')}}',
                        data: providerCounts_all,
                        backgroundColor: 'rgba(54,235,229,0.2)',
                        borderColor: 'rgb(54,235,235)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        function initializeOrderChart(CompleteString,CanceledString,PendingString) {

            var Completed =JSON.parse(CompleteString);
            var Canceled = JSON.parse(CanceledString);
            var Pending = JSON.parse(PendingString);

            var ctx = document.getElementById('lineChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: '{{\App\CPU\translate('chart_Completed')}}',
                        data: Completed,
                        backgroundColor: 'rgba(72,234,18,0.2)',
                        borderColor: 'rgb(66,235,54)',
                        borderWidth: 1
                    },{
                        label: '{{\App\CPU\translate('chart_Pending')}}',
                        data: Pending,
                        backgroundColor: 'rgba(54,235,229,0.2)',
                        borderColor: 'rgb(54,235,235)',
                        borderWidth: 1
                    },{
                        label: '{{\App\CPU\translate('chart_Canceled')}}',
                        data: Canceled,
                        backgroundColor: 'rgba(235,54,54,0.2)',
                        borderColor: 'rgb(235,54,54)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function getDashboardData(start_date, end_date,city_id,area_id) {
            $.ajax({
                method: 'get',
                url: "{{route('admin.getCounterData')}}",
                data: {
                    start_date,
                    end_date,
                    city_id,
                    area_id
                },
                success: function (result) {
                    if (result.success) {
                        $('.current_users_value-data').html(result.count_users);
                        $('.count_providers-data').html(result.count_providers);
                        $('.total_orders_pending-data').html(result.total_orders_pending);
                        $('.total_orders_completed-data').html(result.total_orders_completed);
                        $('.total_orders_canceled-data').html(result.total_orders_canceled);
                    }
                },
            });
            $.ajax({
                method: 'get',
                url: "{{route('admin.getChartData')}}",
                data: {
                    start_date,
                    end_date,
                    city_id,
                    area_id
                },
                success: function (result) {
                    if (result.success) {
                        console.log('dd');
                        TotalAmount
                        AmountCanceled
                        AmountPending
                        AmountCompleted
                        $('#AmountCompleted').html(result.AmountCompleted);
                        $('#AmountPending').html(result.AmountPending);
                        $('#AmountCanceled').html(result.AmountCanceled);
                        TotalAmount = result.AmountCanceled+result.AmountPending+result.AmountCompleted;
                        $('#TotalAmount').html(TotalAmount);
                        initializeUserChart(result.userCountsString, result.userCountsAllString);
                        initializeProviderChart(result.providerCountsString, result.providerCountsAllString);
                        initializeOrderChart(result.CompleteString, result.CanceledString, result.PendingString)
                    }
                },
            });
        }



    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.js"></script>

    <script>
        let map, activeInfoWindow, markers ,info_window_data= [];
        /* ----------------------------- Initialize Map ----------------------------- */
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 24.774265,
                    lng: 46.738586,
                },
                zoom: 8
            });

            map.addListener("click", function(event) {
                mapClicked(event);
            });

            initMarkers();
        }

        /* --------------------------- Initialize Markers --------------------------- */
        function initMarkers() {
            markers=[];
            const initialMarkers = <?php echo json_encode($providers) ?>;
            console.log(initialMarkers);
            for (let index = 0; index < initialMarkers.length; index++) {
                const markerData = initialMarkers[index];
                info_window_data[index]=`<div class="all-data">
                    <div class="w-50 data-left">
                        <div>
                            <div class="img-info-map">
                                <img  src="${markerData.image_url}">
                            </div>
                            <div class="title-info-map">${markerData.name}</div>
                            <div class="rate-info-map">
                                ${markerData.rate}
                            </div>
                        </div>
                    </div>
                    <div class="w-50 data-right">
                        <div class="count-order-info-map">
                            <span class="font-label-info-map"> {{\App\CPU\translate('count_order')}}:</span>
                            <span  class="font-value-info-map"> ${markerData.count_order} </span>
                        </div>
                        <div class="count-wallet-info-map">
                            <span class="font-label-info-map"> {{\App\CPU\translate('wallet')}}:</span>
                            <span   class="font-value-info-map"> ${markerData.wallet} SAR</span>
                        </div>
                        <div class="pending-info-map">
                            <span class="font-label-info-map"> {{\App\CPU\translate('pending')}}:</span>
                            <span   class="font-value-info-map"> ${markerData.pending} </span>
                        </div>
                    </div>



                </div>`;
                const position =   { lat: parseFloat(markerData.lat), lng: parseFloat(markerData.long)};
                const marker = new google.maps.Marker({
                    position:position,
                    icon: {
                        url: "{{asset('assets/back-end/images/icon-car.png')}}",
                        scaledSize: new google.maps.Size(35, 35),
                    },
                    title:  markerData.name ,
                    draggable: true,
                    map
                });
                console.log( markers);
                markers.push({
                    key: markerData.id,
                    marker: marker
                });
                console.log( markers);
                const infowindow = new google.maps.InfoWindow({
                    content: info_window_data[index],
                });
                marker.addListener("click", (event) => {
                    if(activeInfoWindow) {
                        activeInfoWindow.close();
                    }
                    infowindow.open({
                        anchor: marker,
                        shouldFocus: false,
                        map
                    });
                    activeInfoWindow = infowindow;
                    markerClicked(marker, index);
                });

                marker.addListener("dragend", (event) => {
                    markerDragEnd(event, index);
                });



            }
        }
        /* --------------------------- update Markers --------------------------- */

        function UpdateMarkerByKey(key,newLat,newLng) {
            const markerObject = markers.find(markerObj => markerObj.key === key);

            if (markerObject) {
                markerObject.marker.setMap();
                markerObject.marker.setPosition(new google.maps.LatLng(newLat, newLng));

                // Remove the marker object from the markers array
                markers = markers.filter(markerObj => markerObj.key !== key);
            }
        }
        const socket = io('https://socket.nafhasuha.com', { withCredentials: true });

        socket.on('update-marker', (coordinates) => {
            console.log(coordinates);
            UpdateMarkerByKey(coordinates.id,coordinates.lat,coordinates.long)
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&callback=initMap&language={{app()->getLocale()}}" async></script>
    @endif
@endsection

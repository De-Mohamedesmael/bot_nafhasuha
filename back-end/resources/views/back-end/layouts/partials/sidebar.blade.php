@php
    $module_settings = App\Models\System::getProperty('module_settings');
    $module_settings = !empty($module_settings) ? json_decode($module_settings, true) : [];
@endphp
<!-- Side Navbar -->
<nav class="side-navbar no-print  " style="    height: 100%;">
    <div class="side-navbar-wrapper">
        <!-- Sidebar Navigation Menus-->
        <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">
                <li class="li-item">
                    &nbsp;
                </li>
                <li class="li-item @if(request()->segment(2)=='home') active @endif">
                    <a href="{{route('admin.home')}}" class="a-itemOne">
                        <i class="dripicons-meter"></i>
                        <span>{{ __('lang.dashboard')
                            }}
                        </span>
                    </a>
                </li>
                 @if( !empty($module_settings['order_module']) )
                                   @if(auth()->user()->can('order_module.order.view') ||
                                   auth()->user()->can('order_module.order.create')||
                                   auth()->user()->can('order_module.order.edit')  )
                <li class="li-item have-children @if(in_array(request()->segment(2), ['orders'])) active @endif">
                    <a href="#orders" aria-expanded="false" data-toggle="collapse"  class="a-itemhavecheld">
                        <i
                            class="dripicons-card"></i>
                        <span>{{__('lang.orders')}}</span>

                    </a>
                    <ul id="orders"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['orders'])) show @endif">

                          @can('order_module.order.view')
                        <li
                            class="@if(request()->segment(2) == 'orders' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.order.index')}}">
                                <span ></span>
                                {{__('lang.view_all_orders')}} <span class="count-span-side-bar all_orders" >{{array_sum($side_counts_orders)}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'orders' && request()->segment(3)=='pending') active @endif">
                            <a href="{{route('admin.order.index','pending')}}">{{__('lang.view_all_orders_pending')}}<span class="count-span-side-bar pending" >{{$side_counts_orders['pending']??0}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'orders' &&  request()->segment(3)=='approved') active @endif">
                            <a href="{{route('admin.order.index','approved')}}">{{__('lang.view_all_orders_approved')}}<span class="count-span-side-bar approved" >{{$side_counts_orders['approved']??0}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'orders' && request()->segment(3)=='completed') active @endif">
                            <a href="{{route('admin.order.index','completed')}}">{{__('lang.view_all_orders_completed')}}<span class="count-span-side-bar completed" >{{($side_counts_orders['completed']??0 )+ ($side_counts_orders['received']??0 ) }}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'orders' && request()->segment(3)=='canceled-provider') active @endif">
                            <a href="{{route('admin.order.canceled-provider')}}">{{__('lang.view_all_orders_canceled-provider')}}<span class="count-span-side-bar canceled" >{{\App\Models\CancellationRecord::count() }}</span></a>
                        </li>
                            <li
                            class="@if(request()->segment(2) == 'orders' && request()->segment(3)=='canceled') active @endif">
                            <a href="{{route('admin.order.index','canceled')}}">{{__('lang.view_all_orders_canceled')}}<span class="count-span-side-bar canceled" >{{($side_counts_orders['declined']??0 )+($side_counts_orders['canceled']??0 ) }}</span></a>
                        </li>
                          @endcan


                    </ul>
                </li>
                  @endif
             @endif
               @if( !empty($module_settings['transactions_module']) )
                                  @if(auth()->user()->can('transactions_module.transactions.create') ||
                                  auth()->user()->can('transactions_module.transactions.view') ||
                                  auth()->user()->can('transactions_module.transactions.edit'))

                <li>
                    <a href="#transactions" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-card"></i>
                        <span>{{__('lang.transactions')}}</span>

                    </a>
                    <ul id="transactions"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['transactions'])) show @endif">

                          @can('order_module.order.view')
                        <li
                            class="@if(request()->segment(2) == 'transactions' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.transaction.index')}}">{{__('lang.view_all_transactions')}} <span class="count-span-side-bar all_orders" >{{$side_counts_transactions}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'transactions' && request()->segment(3)=='user') active @endif">
                            <a href="{{route('admin.transaction.index','user')}}">{{__('lang.view_all_transactions_user')}}<span class="count-span-side-bar pending" >{{$side_counts_transactions_user}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'transactions' &&  request()->segment(3)=='provider') active @endif">
                            <a href="{{route('admin.transaction.index','provider')}}">{{__('lang.view_all_transactions_provider')}}<span class="count-span-side-bar approved" >{{$side_counts_transactions_provider}}</span></a>
                        </li>

                          @endcan


                    </ul>
                </li>
                  @endif
             @endif
               @if( !empty($module_settings['customer_module']) )
                    @if(auth()->user()->can('customer_module.customer.edit') ||
                    auth()->user()->can('customer_module.customer.view') ||
                    auth()->user()->can('customer_module.customer.create')  )
                        <li>
                            <a href="#customer" aria-expanded="false" data-toggle="collapse">
                                <i
                                    class="dripicons-user-group"></i>
                                <span>{{__('lang.customers')}}</span>

                            </a>
                            <ul id="customer"
                                class="collapse list-unstyled @if(in_array(request()->segment(2), ['customer', 'customer-type'])) show @endif">

                                  @can('customer_module.customer.view')
                                    <li
                                        class="@if(request()->segment(2) == 'customer' && empty(request()->segment(3))) active @endif">
                                        <a href="{{route('admin.customer.index')}}">{{__('lang.view_all_customer')}}</a>
                                    </li>
                                  @endcan
                                @can('customer_module.customer.create')
                                <li
                                    class="@if(request()->segment(2) == 'customer' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.customer.create')}}">{{__('lang.add_new_customer')}}</a>
                                </li>
                                 @endcan

                            </ul>
                        </li>
                 @endif
             @endif

                 @if( !empty($module_settings['provider_module']) )
                   @if(auth()->user()->can('provider_module.provider.edit') ||
                   auth()->user()->can('provider_module.provider.view') ||
                   auth()->user()->can('provider_module.provider.create')  )
                <li>
                    <a href="#provider" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-user-group"></i>
                        <span>{{__('lang.providers')}}</span>

                    </a>
                    <ul id="provider"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['provider', 'provider-type'])) show @endif">

                          @can('provider_module.provider.view')
                        <li
                            class="@if(request()->segment(2) == 'provider' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.provider.index')}}">{{__('lang.view_all_provider')}}</a>
                        </li>
                          @endcan
                        @can('provider_module.provider.create')
                        <li
                            class="@if(request()->segment(2) == 'provider' && request()->segment(3) == 'create') active @endif">
                            <a href="{{route('admin.provider.create')}}">{{__('lang.add_new_provider')}}</a>
                        </li>
                         @endcan

                    </ul>
                </li>
                  @endif
             @endif

                @if( !empty($module_settings['reports']) )

                    @if( auth()->user()->can('reports.daily_report.special')
                    || auth()->user()->can('reports.monthly_report.special')
                    || auth()->user()->can('reports.yearly_report.special')
                    || auth()->user()->can('reports.best_report.special')
                    )

                        <li>
                            <a href="#reports" aria-expanded="false" data-toggle="collapse">
                                <i class="fa fa-file-text"></i>
                                <span>{{__('lang.reports')}}</span>

                            </a>
                            <ul id="reports"
                                class="collapse list-unstyled @if(in_array(request()->segment(2), ['reports'])) show @endif">
                                @can('reports.daily_report.special')
                                    <li
                                        class="@if(request()->segment(2) == 'reports' && request()->segment(3) == 'get-daily-report') active @endif">
                                        <a
                                            href="{{route('admin.reports.getDailyReport')}}">{{__('lang.daily_report')}}</a>
                                    </li>
                                @endcan
                                @can('reports.monthly_report.special')
                                    <li
                                        class="@if(request()->segment(2) == 'reports' && request()->segment(3) == 'get-monthly-report') active @endif">
                                        <a
                                            href="{{route('admin.reports.getMonthlyReport')}}">{{__('lang.monthly_report')}}</a>
                                    </li>
                                @endcan
                                @can('reports.yearly_report.special')
                                    <li
                                        class="@if(request()->segment(2) == 'reports' && request()->segment(3) == 'get-yearly-report') active @endif">
                                        <a
                                            href="{{route('admin.reports.getYearlyReport')}}">{{__('lang.yearly_report')}}</a>
                                    </li>
                                @endcan
                               {{-- @can('reports.best_report.special')
                                    <li
                                        class="@if(request()->segment(2) == 'reports' && request()->segment(3) == 'get-best-report') active @endif">
                                        <a
                                            href="{{route('admin.reports.getBestReport')}}">{{__('lang.best_report')}}</a>
                                    </li>
                                @endcan--}}

                            </ul>
                        </li>
                    @endif
                @endif

                 @if( !empty($module_settings['notification_module']) )
                                   @if(auth()->user()->can('notification_module.notification.edit') ||
                                   auth()->user()->can('notification_module.notification.view') ||
                                   auth()->user()->can('notification_module.notification.create')  )
                <li>
                    <a href="#notifications" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-user-group"></i>
                        <span>{{__('lang.notifications')}}</span>

                    </a>
                    <ul id="notifications"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['notifications'])) show @endif">

                          @can('notification_module.notification.view')
                        <li
                            class="@if(request()->segment(2) == 'notifications' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.notifications.index')}}">{{__('lang.view_all_notification')}}</a>
                        </li>
                          @endcan
                        @can('notification_module.notification.create_and_edit')
                        <li
                            class="@if(request()->segment(2) == 'notifications' && request()->segment(3) == 'create') active @endif">
                            <a href="{{route('admin.notifications.create')}}">{{__('lang.add_notification')}}</a>
                        </li>
                         @endcan

                    </ul>
                </li>
                  @endif
             @endif


                                @if( !empty($module_settings['system_settings']) )
                <li><a href="#system_settings" aria-expanded="false" data-toggle="collapse"> <i
                            class="dripicons-network-4"></i><span>@lang('lang.system_settings')</span></a>
                    <ul id="system_settings"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['transporters','cy_periodics','tires','type_batteries','type_gasolines','vehicle_brands','vehicle_models','vehicle_types','vehicle_manufacture_years'])) show @endif">


                        @can('system_settings.vehicle_types.view')
                            <li
                                class="@if(request()->segment(2) == 'vehicle_types') active @endif">
                                <a href="{{route('admin.vehicle_types.index')}}">{{__('lang.vehicle_types')}}</a>
                            </li>
                        @endcan
                        @can('system_settings.vehicle_types.create')
                            <li
                                class="@if(request()->segment(2) == 'vehicle_types' && request()->segment(3) == 'create') active @endif">
                                <a href="{{route('admin.vehicle_types.create')}}">{{__('lang.add_vehicle_type')}}</a>
                            </li>
                        @endcan

                        @can('system_settings.vehicle_manufacture_years.view')
                            <li
                                class="@if(request()->segment(2) == 'vehicle_manufacture_years') active @endif">
                                <a href="{{route('admin.vehicle_manufacture_years.index')}}">{{__('lang.vehicle_manufacture_years')}}</a>
                            </li>
                        @endcan
                            @can('system_settings.vehicle_manufacture_years.create')
                                <li
                                    class="@if(request()->segment(2) == 'vehicle_manufacture_years' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.vehicle_manufacture_years.create')}}">{{__('lang.add_vehicle_manufacture_year')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.vehicle_brands.view')
                            <li
                                class="@if(request()->segment(2) == 'vehicle_brands') active @endif">
                                <a href="{{route('admin.vehicle_brands.index')}}">{{__('lang.vehicle_brands')}}</a>
                            </li>
                        @endcan
                            @can('system_settings.vehicle_brands.create')
                                <li
                                    class="@if(request()->segment(2) == 'vehicle_brands' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.vehicle_brands.create')}}">{{__('lang.add_vehicle_brand')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.vehicle_models.view')
                            <li
                                class="@if(request()->segment(2) == 'vehicle_models') active @endif">
                                <a href="{{route('admin.vehicle_models.index')}}">{{__('lang.vehicle_models')}}</a>
                            </li>

                        @endcan
                            @can('system_settings.vehicle_models.create')
                                <li
                                    class="@if(request()->segment(2) == 'vehicle_types' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.vehicle_models.create')}}">{{__('lang.add_vehicle_model')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.type_gasolines.view')
                            <li
                                class="@if(request()->segment(2) == 'type_gasolines') active @endif">
                                <a href="{{route('admin.type_gasolines.index')}}">{{__('lang.type_gasolines')}}</a>
                            </li>
                        @endcan
                            @can('system_settings.type_gasolines.create')
                                <li
                                    class="@if(request()->segment(2) == 'type_gasolines' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.type_gasolines.create')}}">{{__('lang.add_type_gasoline')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.type_batteries.view')
                            <li
                                class="@if(request()->segment(2) == 'type_batteries') active @endif">
                                <a href="{{route('admin.type_batteries.index')}}">{{__('lang.type_batteries')}}</a>
                            </li>

                        @endcan
                            @can('system_settings.type_batteries.create')
                                <li
                                    class="@if(request()->segment(2) == 'type_batteries' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.type_batteries.create')}}">{{__('lang.add_type_battery')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.tires.view')
                            <li
                                class="@if(request()->segment(2) == 'tires') active @endif">
                                <a href="{{route('admin.tires.index')}}">{{__('lang.tires')}}</a>
                            </li>
                        @endcan
                            @can('system_settings.tires.create')
                                <li
                                    class="@if(request()->segment(2) == 'tires' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.tires.create')}}">{{__('lang.add_tire')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.transporters.view')
                            <li
                                class="@if(request()->segment(2) == 'transporters') active @endif">
                                <a href="{{route('admin.transporters.index')}}">{{__('lang.transporters')}}</a>
                            </li>
                        @endcan
                            @can('system_settings.transporters.create')
                                <li
                                    class="@if(request()->segment(2) == 'transporters' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.transporters.create')}}">{{__('lang.add_transporter')}}</a>
                                </li>
                            @endcan
                        @can('system_settings.cy_periodics.view')
                            <li
                                class="@if(request()->segment(2) == 'cy_periodics') active @endif">
                                <a href="{{route('admin.cy_periodics.index')}}">{{__('lang.cy_periodics')}}</a>
                            </li>
                        @endcan
                            @can('system_settings.cy_periodics.create')
                                <li
                                    class="@if(request()->segment(2) == 'cy_periodics' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.cy_periodics.create')}}">{{__('lang.add_cy_periodic')}}</a>
                                </li>
                            @endcan

                    </ul>
                </li>

                                @endif

                @if( !empty($module_settings['settings']) )
                    <li><a href="#setting" aria-expanded="false" data-toggle="collapse"> <i
                                class="dripicons-gear"></i><span>@lang('lang.settings')</span></a>
                        <ul id="setting"
                            class="collapse list-unstyled @if(in_array(request()->segment(2), ['service','banks','icons', 'category','slider','splash_screen','countries','city','areas'])) show @endif">


                                @can('settings.service.view')
                                    <li
                                        class="@if(request()->segment(2) == 'service' ) active @endif">
                                        <a href="{{route('admin.service.index')}}">{{__('lang.services')}}</a>
                                    </li>
                                @endcan
                                @can('settings.category.view')
                                    <li
                                        class="@if(request()->segment(2) == 'category' ) active @endif">
                                        <a href="{{route('admin.category.index')}}">{{__('lang.categories')}}</a>
                                    </li>
                                @endcan
                                @can('settings.slider.view')
                                    <li
                                        class="@if(request()->segment(2) == 'slider') active @endif">
                                        <a href="{{route('admin.slider.index')}}">{{__('lang.slider')}}</a>
                                    </li>
                                @endcan
                                    @can('settings.slider.create')
                                        <li
                                            class="@if(request()->segment(2) == 'slider' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.slider.create')}}">{{__('lang.add_slider')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.splash_screen.view')
                                    <li
                                        class="@if(request()->segment(2) == 'splash_screen') active @endif">
                                        <a href="{{route('admin.splash_screen.index')}}">{{__('lang.splash_screen')}}</a>
                                    </li>
                                @endcan
                                    @can('settings.splash_screen.create')
                                        <li
                                            class="@if(request()->segment(2) == 'splash_screen' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.splash_screen.create')}}">{{__('lang.add_splash_screen')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.icons.view')
                                    <li
                                        class="@if(request()->segment(2) == 'icons') active @endif">
                                        <a href="{{route('admin.icons.index')}}">{{__('lang.icons')}}</a>
                                    </li>
                                @endcan
                                    @can('settings.icons.create')
                                        <li
                                            class="@if(request()->segment(2) == 'icons' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.icons.create')}}">{{__('lang.add_icon')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.banks.view')
                                    <li
                                        class="@if(request()->segment(2) == 'banks') active @endif">
                                        <a href="{{route('admin.banks.index')}}">{{__('lang.banks')}}</a>
                                    </li>
                                @endcan
                                    @can('settings.banks.create')
                                        <li
                                            class="@if(request()->segment(2) == 'banks' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.banks.create')}}">{{__('lang.add_bank')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.countries.view')
                                    <li
                                        class="@if(request()->segment(2) == 'countries') active @endif">
                                        <a href="{{route('admin.countries.index')}}">{{__('lang.countries')}}</a>
                                    </li>
                                @endcan
                                    @can('settings.countries.create')
                                        <li
                                            class="@if(request()->segment(2) == 'countries' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.countries.create')}}">{{__('lang.add_country')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.city.view')
                                    <li
                                        class="@if(request()->segment(2) == 'city') active @endif">
                                        <a href="{{route('admin.city.index')}}">{{__('lang.cities')}}</a>
                                    </li>
                                @endcan
                                    @can('settings.city.create')
                                        <li
                                            class="@if(request()->segment(2) == 'city' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.city.create')}}">{{__('lang.add_city')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.areas.view')
                                    <li
                                        class="@if(request()->segment(2) == 'areas') active @endif">
                                        <a href="{{route('admin.areas.index')}}">{{__('lang.areas')}}</a>
                                    </li>

                                @endcan
                                    @can('settings.areas.create')
                                        <li
                                            class="@if(request()->segment(2) == 'areas' && request()->segment(3) == 'create') active @endif">
                                            <a href="{{route('admin.areas.create')}}">{{__('lang.add_area')}}</a>
                                        </li>
                                    @endcan
                                @can('settings.general_settings.view')
                                    <li
                                        class="@if(request()->segment(1) == 'settings' && request()->segment(2) == 'get-general-setting') active @endif">
                                        <a
                                            href="{{route('admin.settings.getGeneralSetting')}}">{{__('lang.general_settings')}}</a>
                                    </li>
                                @endcan
                        </ul>
                    </li>
                @endif
                @if( !empty($module_settings['messages']) )
                <li><a href="#messages" aria-expanded="false" data-toggle="collapse"> <i
                            class="dripicons-conversation"></i><span>@lang('lang.messages_and_contact_us')</span></a>
                    <ul id="messages"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['contact_us'])) show @endif">


                        @can('messages.contact_us.view')
                            <li
                                class="@if(request()->segment(2) == 'contact_us') active @endif">
                                <a href="{{route('admin.contact_us.index')}}">{{__('lang.contact_us')}}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
                @endif
                @if( !empty($module_settings['info_module']) )
                    <li>
                        <a href="#infos" aria-expanded="false" data-toggle="collapse">
                            <i
                                class="dripicons-document"></i>
                            <span>{{__('lang.infos')}}</span>

                        </a>
                        <ul id="infos"
                            class="collapse list-unstyled @if(in_array(request()->segment(2), ['infos','category_faqs','faqs'])) show @endif">


                            @can('info_module.infos.special')
                                <li
                                    class="@if(request()->segment(2) == 'infos' && request()->segment(4) == 'who-are-we') active @endif">
                                    <a href="{{route('admin.infos.edit','who-are-we')}}">{{__('lang.who-are-we')}}</a>
                                </li>
                            @endcan
                            @can('info_module.infos.special')
                                <li
                                    class="@if(request()->segment(2) == 'infos' && request()->segment(4)=='terms-of-service') active @endif">
                                    <a href="{{route('admin.infos.edit','terms-of-service')}}">{{__('lang.terms-of-service')}}</a>
                                </li>
                            @endcan
                            @can('info_module.infos.special')
                                <li
                                    class="@if(request()->segment(2) == 'infos' && request()->segment(4) == 'privacy-policy') active @endif">
                                    <a href="{{route('admin.infos.edit','privacy-policy')}}">{{__('lang.privacy-policy')}}</a>
                                </li>
                            @endcan
                            @can('info_module.infos.special')
                                <li
                                    class="@if(request()->segment(2) == 'infos' && request()->segment(4) =='terms-periodic-inspection') active @endif">
                                    <a href="{{route('admin.infos.edit','terms-periodic-inspection')}}">{{__('lang.terms-periodic-inspection')}}</a>
                                </li>
                            @endcan
                             @can('info_module.infos.special')
                                <li
                                    class="@if(request()->segment(2) == 'infos' && request()->segment(4) =='terms-and-conditions') active @endif">
                                    <a href="{{route('admin.infos.edit','terms-and-conditions')}}">{{__('lang.terms-and-conditions')}}</a>
                                </li>
                            @endcan
                                @can('info_module.category_faqs.view')
                                    <li
                                        class="@if(request()->segment(2) == 'category_faqs' && empty(request()->segment(3)) ) active @endif">
                                        <a href="{{route('admin.category_faqs.index')}}">{{__('lang.category_faqs')}}</a>
                                    </li>

                                @endcan
                                @can('info_module.category_faqs.create')
                                    <li
                                        class="@if(request()->segment(2) == 'category_faqs' && request()->segment(3) == 'create') active @endif">
                                        <a href="{{route('admin.category_faqs.create')}}">{{__('lang.add_category_faq')}}</a>
                                    </li>
                                @endcan

                                @can('info_module.faqs.view')
                                    <li
                                        class="@if(request()->segment(2) == 'faqs' && empty(request()->segment(3)) ) active @endif">
                                        <a href="{{route('admin.faqs.index')}}">{{__('lang.faqs')}}</a>
                                    </li>

                                @endcan
                                @can('info_module.faqs.create')
                                    <li
                                        class="@if(request()->segment(2) == 'faqs' && request()->segment(3) == 'create') active @endif">
                                        <a href="{{route('admin.faqs.create')}}">{{__('lang.add_faq')}}</a>
                                    </li>
                                @endcan

                        </ul>
                    </li>
                @endif
                 @if( !empty($module_settings['admin_module']) )
                       @if(auth()->user()->can('admin_module.admins.create') ||
                       auth()->user()->can('admin_module.admins.view') ||
                       auth()->user()->can('admin_module.admins.edit'))
                            <li>
                    <a href="#admins" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-user-group"></i>
                        <span>{{__('lang.admins')}}</span>

                    </a>
                    <ul id="admins"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['admins'])) show @endif">

                          @can('admin_module.admins.view')
                        <li
                            class="@if(request()->segment(2) == 'admins' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.admins.index')}}">{{__('lang.admins')}}</a>
                        </li>
                          @endcan
                        @can('admin_module.admins.create')
                        <li
                            class="@if(request()->segment(2) == 'admins' && request()->segment(3) == 'create') active @endif">
                            <a href="{{route('admin.admins.create')}}">{{__('lang.add_new_admin')}}</a>
                        </li>
                         @endcan

                    </ul>
                </li>
                       @endif
                 @endif
{{--                <li class="@if(request()->segment(1) == 'tutorials' && empty(request()->segment(2))) active @endif">--}}
{{--                    <a href="#"><i--}}
{{--                            class="fa fa-info-circle"></i><span>{{__('lang.tutorials')}}</span></a>--}}
{{--                </li>--}}
            </ul>
        </div>
    </div>
</nav>

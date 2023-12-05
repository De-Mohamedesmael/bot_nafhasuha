
<!-- Side Navbar -->
<nav class="side-navbar no-print @if(request()->segment(1) == 'pos') shrink @endif">
    <div class="side-navbar-wrapper">
        <!-- Sidebar Navigation Menus-->
        <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">
                <li><a href="{{route('admin.home')}}"> <i class="dripicons-meter"></i><span>{{ __('lang.dashboard')
                            }}</span></a></li>
                {{-- @if( !empty($module_settings['order_module']) )
                                   @if(auth()->user()->can('customer_module.customer.create_and_edit') ||
                                   auth()->user()->can('customer_module.customer.view') ||
                                   auth()->user()->can('customer_module.customer_type.create_and_edit') ||
                                   auth()->user()->can('customer_module.customer_type.view') ) --}}
                <li>
                    <a href="#orders" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-card"></i>
                        <span>{{__('lang.orders')}}</span>

                    </a>
                    <ul id="orders"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['orders'])) show @endif">

                        {{--  @can('order_module.order.view')--}}
                        <li
                            class="@if(request()->segment(2) == 'orders' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.order.index')}}">{{__('lang.view_all_orders')}} <span class="count-span-side-bar all_orders" >{{array_sum($side_counts_orders)}}</span></a>
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
                            class="@if(request()->segment(2) == 'orders' && request()->segment(3)=='canceled') active @endif">
                            <a href="{{route('admin.order.index','canceled')}}">{{__('lang.view_all_orders_canceled')}}<span class="count-span-side-bar canceled" >{{($side_counts_orders['declined']??0 )+($side_counts_orders['canceled']??0 ) }}</span></a>
                        </li>
                        {{--  @endcan--}}


                    </ul>
                </li>
                {{--  @endif
             @endif --}}
                {{-- @if( !empty($module_settings['order_module']) )
                                  @if(auth()->user()->can('customer_module.customer.create_and_edit') ||
                                  auth()->user()->can('customer_module.customer.view') ||
                                  auth()->user()->can('customer_module.customer_type.create_and_edit') ||
                                  auth()->user()->can('customer_module.customer_type.view') ) --}}
                <li>
                    <a href="#transactions" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-card"></i>
                        <span>{{__('lang.transactions')}}</span>

                    </a>
                    <ul id="transactions"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['transactions'])) show @endif">

                        {{--  @can('order_module.order.view')--}}
                        <li
                            class="@if(request()->segment(2) == 'transactions' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.transaction.index')}}">{{__('lang.view_all_transactions')}} <span class="count-span-side-bar all_orders" >{{array_sum($side_counts_orders)}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'transactions' && request()->segment(3)=='user') active @endif">
                            <a href="{{route('admin.transaction.index','user')}}">{{__('lang.view_all_transactions_user')}}<span class="count-span-side-bar pending" >{{$side_counts_orders['pending']??0}}</span></a>
                        </li>
                        <li
                            class="@if(request()->segment(2) == 'transactions' &&  request()->segment(3)=='provider') active @endif">
                            <a href="{{route('admin.transaction.index','provider')}}">{{__('lang.view_all_transactions_provider')}}<span class="count-span-side-bar approved" >{{$side_counts_orders['approved']??0}}</span></a>
                        </li>

                        {{--  @endcan--}}


                    </ul>
                </li>
                {{--  @endif
             @endif --}}
               {{-- @if( !empty($module_settings['customer_module']) )
                    @if(auth()->user()->can('customer_module.customer.create_and_edit') ||
                    auth()->user()->can('customer_module.customer.view') ||
                    auth()->user()->can('customer_module.customer_type.create_and_edit') ||
                    auth()->user()->can('customer_module.customer_type.view') ) --}}
                        <li>
                            <a href="#customer" aria-expanded="false" data-toggle="collapse">
                                <i
                                    class="dripicons-user-group"></i>
                                <span>{{__('lang.customers')}}</span>

                            </a>
                            <ul id="customer"
                                class="collapse list-unstyled @if(in_array(request()->segment(2), ['customer', 'customer-type'])) show @endif">

                                {{--  @can('customer_module.customer.view')--}}
                                    <li
                                        class="@if(request()->segment(2) == 'customer' && empty(request()->segment(3))) active @endif">
                                        <a href="{{route('admin.customer.index')}}">{{__('lang.view_all_customer')}}</a>
                                    </li>
                                {{--  @endcan--}}
                                {{--@can('customer_module.customer.create_and_edit')--}}
                                <li
                                    class="@if(request()->segment(2) == 'customer' && request()->segment(3) == 'create') active @endif">
                                    <a href="{{route('admin.customer.create')}}">{{__('lang.add_new_customer')}}</a>
                                </li>
                                {{-- @endcan--}}

                            </ul>
                        </li>
                {{--  @endif
             @endif --}}

                {{-- @if( !empty($module_settings['customer_module']) )
                   @if(auth()->user()->can('customer_module.customer.create_and_edit') ||
                   auth()->user()->can('customer_module.customer.view') ||
                   auth()->user()->can('customer_module.customer_type.create_and_edit') ||
                   auth()->user()->can('customer_module.customer_type.view') ) --}}
                <li>
                    <a href="#provider" aria-expanded="false" data-toggle="collapse">
                        <i
                            class="dripicons-user-group"></i>
                        <span>{{__('lang.providers')}}</span>

                    </a>
                    <ul id="provider"
                        class="collapse list-unstyled @if(in_array(request()->segment(2), ['provider', 'provider-type'])) show @endif">

                        {{--  @can('provider_module.provider.view')--}}
                        <li
                            class="@if(request()->segment(2) == 'provider' && empty(request()->segment(3))) active @endif">
                            <a href="{{route('admin.provider.index')}}">{{__('lang.view_all_provider')}}</a>
                        </li>
                        {{--  @endcan--}}
                        {{--@can('provider_module.provider.create_and_edit')--}}
                        <li
                            class="@if(request()->segment(2) == 'provider' && request()->segment(3) == 'create') active @endif">
                            <a href="{{route('admin.provider.create')}}">{{__('lang.add_new_provider')}}</a>
                        </li>
                        {{-- @endcan--}}

                    </ul>
                </li>
                {{--  @endif
             @endif --}}





{{--                @if( !empty($module_settings['settings']) )--}}
                    <li><a href="#setting" aria-expanded="false" data-toggle="collapse"> <i
                                class="dripicons-gear"></i><span>@lang('lang.settings')</span></a>
                        <ul id="setting"
                            class="collapse list-unstyled @if(in_array(request()->segment(1), ['service', 'category'])) show @endif">


{{--                                @can('product_module.category.view')--}}
                                    <li
                                        class="@if(request()->segment(1) == 'service' && empty(request()->segment(2))) active @endif">
                                        <a href="{{route('admin.service.index')}}">{{__('lang.services')}}</a>
                                    </li>
                                    <li
                                        class="@if(request()->segment(1) == 'category' && empty(request()->segment(2))) active @endif">
                                        <a href="{{route('admin.category.index')}}">{{__('lang.categories')}}</a>
                                    </li>

{{--                                    @endcan--}}
                                <li
                                    class="@if(request()->segment(1) == 'settings' && request()->segment(2) == 'get-general-setting') active @endif">
                                    <a
                                        href="#">{{__('lang.general_settings')}}</a>
                                </li>
                        </ul>
                    </li>
                    <li class="@if(request()->segment(1) == 'tutorials' && empty(request()->segment(2))) active @endif">
                        <a href="#"><i
                                class="fa fa-info-circle"></i><span>{{__('lang.tutorials')}}</span></a>
                    </li>
{{--                @endif--}}
            </ul>
        </div>
    </div>
</nav>

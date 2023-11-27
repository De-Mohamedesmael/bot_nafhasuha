
<!-- Side Navbar -->
<nav class="side-navbar no-print @if(request()->segment(1) == 'pos') shrink @endif">
    <div class="side-navbar-wrapper">
        <!-- Sidebar Navigation Menus-->
        <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">
                <li><a href="{{route('admin.home')}}"> <i class="dripicons-meter"></i><span>{{ __('lang.dashboard')
                            }}</span></a></li>

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

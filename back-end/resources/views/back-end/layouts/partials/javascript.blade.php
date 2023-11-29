@php
$moment_time_format = App\Models\System::getProperty('time_format') == '12' ? 'hh:mm A' : 'HH:mm';
@endphp
<script>
    var moment_time_format = "{{$moment_time_format}}";
</script>
<script type="text/javascript" src="{{asset('assets/back-end/js/lang/'.app()->getLocale().'.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/jquery/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/popper.js/umd/popper.min.js') }}">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/daterange/js/moment.min.js') }}"></script>

<script type="text/javascript" src="{{asset('assets/back-end/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.'.session('language').'.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/back-end/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/bootstrap/js/bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/keyboard/js/jquery.keyboard.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/keyboard/js/jquery.keyboard.extension-autocomplete.js') }}">
</script>
<script type="text/javascript" src="{{asset('assets/back-end/js/grasp_mobile_progress_circle-1.0.0.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/jquery.cookie/jquery.cookie.js') }}">
</script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/chart.js/Chart.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript"
    src="{{asset('assets/back-end/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/charts-custom.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/front.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/daterange/js/knockout-3.4.2.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/daterange/js/daterangepicker.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/dropzone.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/bootstrap-treeview.js') }}"></script>

<!-- table sorter js-->
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/buttons.bootstrap4.min.js') }}">
    ">
</script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/buttons.colVis.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/buttons.print.min.js') }}"></script>

<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/sum().js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/dataTables.checkboxes.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/datatable/date-eu.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/accounting.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/toastr/toastr.min.js')}}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js">
</script>
<script type="text/javascript" src="{{asset('assets/back-end/vendor/cropperjs/cropper.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/printThis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/common.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/currency_exchange.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/customer.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/back-end/js/cropper.js') }}"></script>

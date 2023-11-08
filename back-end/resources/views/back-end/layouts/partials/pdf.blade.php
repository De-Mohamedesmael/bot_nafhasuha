<!DOCTYPE html>
<html>
<head>
  <link href="{{ asset('css/pdf.css') }}" rel="stylesheet" type="text/css" />
<style>

.notexport{
    display: none !important;
}
 .edit-options {
            display: none;
        }
        button.btn.btn-default.btn-sm.dropdown-toggle {
            display: none !important;
        }
</style>
</head>

<body style="font-family: XB Riyaz;direction:rtl;">
    @php
        $logo = App\Models\System::getProperty('logo');
    @endphp
    <table>
      <tr>
          <td>
            <img style="width: 500px !important; height: auto !important;" src="{{asset('/uploads/'.$logo)}}">
          </td>
          <td>
              {{$title}}
          </td>
      </tr>
      <tr>
        <td colspan="2">
          {!!$data!!}
        </td>
      </tr>
    </table>
</body>
</html>

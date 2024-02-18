<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Currency;
use App\Models\CustomerType;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Product;
use App\Models\Store;
use App\Models\System;
use App\Models\User;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class AdminController extends Controller
{

    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;

        $this->middleware('CheckPermission:admin_module,admins,view')->only('index');
        $this->middleware('CheckPermission:admin_module,admins,create')->only('create','store');
        $this->middleware('CheckPermission:admin_module,admins,edit')->only('edit','update','update_status');
        $this->middleware('CheckPermission:admin_module,admins,delete')->only('destroy');

    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Admin::select('admins.*');

        if (!auth()->user()->can('superadmin') && auth()->user()->is_admin != 1) {
            $query->where('is_superadmin', 0);
        }
        if ( auth()->user()->email != env( 'SYSTEM_SUPERADMIN')) {
            $query->where('email', '!=',env( 'SYSTEM_SUPERADMIN'));

        }
        $employees =  $query->groupBy('admins.id');

        if (request()->ajax()) {
            $logo=\Settings::get('logo');
            return DataTables::of($employees)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')

                ->addColumn('profile_photo', function ($row) use($logo) {
                    if (!empty($row->getFirstMediaUrl('photo'))) {
                        return '<img src="' . $row->getFirstMediaUrl('photo') . '"
                        alt="photo" width="50" height="50">';
                    } else {
                        return '<img src="' . asset('/assets/images/settings/' . $logo) . '" alt="photo" width="50" height="50">';
                    }
                })->addColumn('status', function ($row) {
                    $checked=$row->is_active?'checked':'';
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '';

                        if (auth()->user()->can('admin_module.admins.edit')) {
                            $html .= '<a href="' . route('admin.admins.edit', $row->id) . '"
                                     class="btn edit_employee a-image" title="' . __('lang.edit') . '">
                                            <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">

                                    </a>';
                        }
                        if (auth()->user()->can('admin_module.admins.delete')) {
                            $html .= '<a data-href="' . route('admin.admins.delete', $row->id) . '"
                                    data-check_password="' . route('admin.checkPassword', Auth::user()->id) . '"
                                    class="btn delete_item text-red" title="' . __('lang.delete') . '"><i
                                        class="dripicons-trash"></i>
                                    </a>';
                        }
                        /* $html .= '<li class="divider"></li>';
                            if (auth()->user()->can('sms_module.sms.create_and_edit')) {
                               $html .= '<li>
                                   <a href="' . action('SmsController@create', ['employee_id' => $row->id]) . '"
                                       class="btn"><i
                                           class="fa fa-comments-o"></i>
                                       ' . __('lang.send_sms') . '</a>
                               </li>';
                           }
                           $html .= '<li class="divider"></li>';
                           if (auth()->user()->can('email_module.email.create_and_edit')) {
                               $html .= '<li>
                                   <a href="' . action('EmailController@create', ['employee_id' => $row->id]) . '"
                                       class="btn"><i
                                           class="fa fa-envelope "></i>
                                       ' . __('lang.send_email') . '</a>
                               </li>';
                           }*/

                        return $html;
                    }
                )
                ->rawColumns([
                    'created_at',
                    'action',
                    'profile_photo',
                    'status',
                ])
                ->make(true);
        }

        return view('back-end.admin.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modulePermissionArray=Admin::modulePermissionArray();
        $subModulePermissionArray=Admin::subModulePermissionArray();
        return view('back-end.admin.create',compact('modulePermissionArray','subModulePermissionArray'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->email != env('SYSTEM_SUPERADMIN')&&!auth()->user()->can('admin_module.admins.create')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users|max:255',
            'name' => 'required|max:255',
            'password' => 'required|confirmed|max:255',
        ]);

        try {

            DB::beginTransaction();

            $data = $request->except('_token');



            $user_data = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),

            ];
            $admin = Admin::create($user_data);


            if ($request->hasFile('photo')) {
                $admin->addMedia($request->photo)->toMediaCollection('photo');
            }

            $specialModulePermissionArray=Admin::specialModulePermissionArray();
            //assign permissions to employee
            $permissions=[];
            if (!empty($data['permissions'])) {
                foreach ($data['permissions'] as $key => $value) {
                    $permissions[] = $key;
                }

            }

            if (!empty($data['special'])) {
                foreach ($data['special'] as $key => $value) {
                    if(in_array($value,$specialModulePermissionArray)){
                        $permissions[] = $key;
                    }

                }

            }

            if (!empty($permissions)) {
                $admin->syncPermissions($permissions);
            }
            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang.employee_added')
            ];

            return redirect()->route('admin.admins.index')->with('status', $output);
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];

            return redirect()->back()->with('status', $output);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);

        $modulePermissionArray=Admin::modulePermissionArray();
        $subModulePermissionArray=Admin::subModulePermissionArray();
        return view('back-end.admin.edit',compact('admin','modulePermissionArray','subModulePermissionArray'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('admin_module.admins.edit')) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'required|max:255'
        ]);

        try {

            DB::beginTransaction();

            $data = $request->except('_token');

            $user_data = [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email']
            ];


            if (!empty($request->input('password'))) {
                $validated = $request->validate([
                    'password' => 'required|confirmed|max:255',
                ]);
                $user_data['password'] = Hash::make($request->input('password'));
                $employee_data['pass_string'] = Crypt::encrypt($data['password']);;
            }

            $admin = Admin::find($id);
            $admin->update($user_data);

            if ($request->hasFile('photo')) {
                $admin->clearMediaCollection('photo');
                $admin->addMedia($request->photo)->toMediaCollection('photo');
            }



            $specialModulePermissionArray=Admin::specialModulePermissionArray();


            $permissions=[];
            if (!empty($data['permissions'])) {
                foreach ($data['permissions'] as $key => $value) {
                    $permissions[] = $key;
                }

            }

            if (!empty($data['special'])) {
                foreach ($data['special'] as $key => $value) {
                    if(in_array($value,$specialModulePermissionArray)){
                        $permissions[] = $key;
                    }

                }

            }

            if (!empty($permissions)) {
                $admin->syncPermissions($permissions);
            }

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang.employee_updated')
            ];

            return redirect()->route('admin.admins.index')->with('status', $output);
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
            return redirect()->back()->with('status', $output);
        }
    }

    public function update_status(Request $request ){

        try {
            $user=Admin::find($request->id);
            if(!$user){
                return [
                    'success'=>false,
                    'msg'=>translate('admin_not_found')
                ];
            }


            DB::beginTransaction();
            $user->is_active=($user->is_active - 1) *-1;
            $user->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('Admin updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $admin = Admin::find($id);
            if ($admin){
                $admin->clearMediaCollection('images');
                $admin->delete();
            }


            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function getProfile(Request $request)
    {
        $admin = Admin::find(Auth::id());

        return view('back-end.admin.profile')->with(compact(
            'admin'
        ));
    }
    public function updateProfile(Request $request)
    {
        $admin = Admin::find(Auth::id());
        if (!empty($request->current_password) || !empty($request->password) || !empty($request->password_confirmation)) {
            $this->validate($request, [
                'current_password' => ['required', function ($attribute, $value, $fail) use ($admin) {
                    if (!\Hash::check($value, $admin->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                }],
                'password' => 'required|confirmed',
            ]);
        }

        try {

            $admin->phone = $request->phone;

            if (!empty($request->password)) {
                $admin->password  = Hash::make($request->password);
            }
            $admin->save();

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * check password
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkPassword($id)
    {
        $admin = Admin::where('id', $id)->first();

        if (Hash::check(request()->value, $admin->password)) {
            return ['success' => true];
        }

        return ['success' => false];
    }
    /**
     * check admin password
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkAdminPassword($id)
    {
        $admin = Admin::where('id', $id)->first();
        if($admin){
            if (Hash::check(request()->value, $admin->password)) {
                return ['success' => true];
            }
        }else{
            $admin = Admin::first();
            if (Hash::check(request()->value, $admin->password)) {
                return ['success' => true];
            }
        }

        return ['success' => false];
    }
    public function getDropdown()
    {
        $admin = Admin::orderBy('name', 'asc')->pluck('name', 'id');
        $admin_dp = $this->commonUtil->createDropdownHtml($admin, 'Please Select');

        return $admin_dp;
    }


}

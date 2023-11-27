<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProfile(Request $request)
    {
        $admin = Admin::find(Auth::admin()->id);

        return view('admin.profile')->with(compact(
            'admin'
        ));
    }
    public function updateProfile(Request $request)
    {
        $admin = Admin::find(Auth::admin()->id);
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

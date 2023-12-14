<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\System;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for testing purpose only
        // Schema::disableForeignKeyConstraints();
        // Permission::truncate();
        // Schema::enableForeignKeyConstraints();
        // for testing purpose only
        $modules = Admin::modulePermissionArray();
        $module_settings = [];
        foreach ($modules as $key => $value) {
            $module_settings[$key] = 1;
        }
        System::insert([
            'key' => 'module_settings',
            'value' => json_encode($module_settings),
            'created_by' => 1,
            'date_and_time' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()]);

        $modulePermissionArray = Admin::modulePermissionArray();
        $subModulePermissionArray = Admin::subModulePermissionArray();
        $specialModulePermissionArray = Admin::specialModulePermissionArray();

        $data = [];
        foreach ($modulePermissionArray as $key_module => $moudle) {
            if (!empty($subModulePermissionArray[$key_module])) {
                foreach ($subModulePermissionArray[$key_module] as $key_sub_module =>  $sub_module) {
                    if(!in_array($key_sub_module,$specialModulePermissionArray)){
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.view'];
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.create'];
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.edit'];
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.delete'];
                    }else{
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.special'];

                    }

                }
            }
        }

        $insert_data = [];
        $time_stamp = Carbon::now()->toDateTimeString();
        foreach ($data as $d) {
            $d['guard_name'] = 'admin';
            $d['created_at'] = $time_stamp;
            $insert_data[] = $d;
        }
        Permission::insert($insert_data);
    }
}

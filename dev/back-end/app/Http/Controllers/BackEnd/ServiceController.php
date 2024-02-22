<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use function App\CPU\translate;

class ServiceController extends Controller
{

    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
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
        $services = Service::latest()->get();
        return view('back-end.service.index')->with(compact(
            'services'
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubCategories()
    {
        $services = Service::whereNotNull('parent_id')->get();

        return view('service.sub_services')->with(compact(
            'services'
        ));
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $service = Service::find($id);
        $type = request()->type ?? null;
        return view('back-end.service.edit')->with(compact(
            'type',
            'service',
        ));
    }


    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['sort' => ['required']],
        );
        try {
            DB::beginTransaction();
            $service = Service::find($id);
            $service->sort=$request->sort;
            $service->save();
            $service->update($request->translations);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {
                    $folderPath = 'assets/images/services/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($service->image)) {
                        $oldImagePath = 'assets/images/'.$service->image;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $service->image = 'services/'.$image;
                    $service->save();
                }
            }


            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }
    public function getBase64Image($Image)
    {

        $image_path = str_replace(env("APP_URL") . "/", "", $Image);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image_path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_content = curl_exec($ch);
        curl_close($ch);
//    $image_content = file_get_contents($image_path);
        $base64_image = base64_encode($image_content);
        $b64image = "data:image/jpeg;base64," . $base64_image;
        return  $b64image;
    }
    public function getCroppedImages($cropImages){
        $dataNewImages = [];
        foreach ($cropImages as $img) {
            if (filter_var($img, FILTER_VALIDATE_URL) === false) {
                if (strlen($img) < 200) {
                    $dataNewImages[] = $this->getBase64Image($img);
                } else {
                    $dataNewImages[] = $img;
                }
            }
        }
        return $dataNewImages;
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
            if (request()->source == 'pct') {
                Service::find($id)->delete();
                Service::where('parent_id', $id)->delete();
                $products = Product::where('service_id', $id)->orWhere('sub_service_id', $id)->get();
                foreach ($products as $product) {
                    ProductStore::where('product_id', $product->id)->delete();
                    $product->delete();
                }
            } else {
                $sub_service_exsist = Service::where('parent_id', $id)->exists();
                if ($sub_service_exsist) {
                    $output = [
                        'success' => false,
                        'msg' => __('lang.sub_service_exsist')
                    ];

                    return $output;
                } else {
                    Service::find($id)->delete();
                }
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

    public function getDropdown()
    {
        if (!empty(request()->product_class_id)) {
            $services = Service::where('product_class_id', request()->product_class_id)->orderBy('name', 'asc')->pluck('name', 'id');
        } else {
            $services = Service::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        }
        $services_dp = $this->commonUtil->createDropdownHtml($services, 'Please Select');

        return $services_dp;
    }

    public function update_status(Request $request ){

        try {
            $service=Service::find($request->id);
            if(!$service){
                return [
                    'success'=>false,
                    'msg'=>translate('service_not_found')
                ];
            }


            DB::beginTransaction();
            $service->status=($service->status - 1) *-1;
            $service->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('Provider updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }


}

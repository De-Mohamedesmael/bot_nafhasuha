<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Category;
use App\Models\Service;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class CategoryController extends Controller
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
        $categories = Category::latest()->get();
        return view('back-end.category.index')->with(compact(
            'categories'
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubCategories()
    {
        $categories = Category::whereNotNull('parent_id')->get();

        return view('category.sub_categories')->with(compact(
            'categories'
        ));
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $services=Service::listsTranslations('title as name')->pluck('name','id');
        $category_services=$category->services()->pluck('services.id');
        return view('back-end.category.edit')->with(compact(
            'services',
            'category_services',
            'category',
        ));
    }


    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['sort' => ['required']],
            ['services' => ['required','array']],
            ['services.*' => ['required', 'integer','exists:services,id']]
        );

        try {
            DB::beginTransaction();
            $category = Category::find($id);
            $category->sort=$request->sort;
            $category->save();
            $category->update($request->translations);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                    $folderPath = 'assets/images/categories/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($category->image)) {
                        $oldImagePath ='assets/images/' . $category->image;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $category->image = 'categories/' . $image;
                    $category->save();
                }

            }
            if($request->has("services")){
                $category->services()->sync($request->services);
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
                Category::find($id)->delete();
                Category::where('parent_id', $id)->delete();
                $products = Product::where('category_id', $id)->orWhere('sub_category_id', $id)->get();
                foreach ($products as $product) {
                    ProductStore::where('product_id', $product->id)->delete();
                    $product->delete();
                }
            } else {
                $sub_category_exsist = Category::where('parent_id', $id)->exists();
                if ($sub_category_exsist) {
                    $output = [
                        'success' => false,
                        'msg' => __('lang.sub_category_exsist')
                    ];

                    return $output;
                } else {
                    Category::find($id)->delete();
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
            $categories = Category::where('product_class_id', request()->product_class_id)->orderBy('name', 'asc')->pluck('name', 'id');
        } else {
            $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->pluck('name', 'id');
        }
        $categories_dp = $this->commonUtil->createDropdownHtml($categories, 'Please Select');

        return $categories_dp;
    }

    public function update_status(Request $request ){

        try {
            $category=Category::find($request->id);
            if(!$category){
                return [
                    'success'=>false,
                    'msg'=>translate('category_not_found')
                ];
            }


            DB::beginTransaction();
            $category->status=($category->status - 1) *-1;
            $category->save();
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

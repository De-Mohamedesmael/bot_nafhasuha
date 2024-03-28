<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Info;
use App\Models\SplashScreen;
use App\Models\Service;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class SplashScreenController extends Controller
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
        $splash_screens = SplashScreen::latest()->get();
        return view('back-end.splash_screen.index')->with(compact(
            'splash_screens'
        ));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_add = request()->quick_add ?? null;


        if ($quick_add) {
            return view('back-end.splash_screen.quick_add')->with(compact(
                'quick_add'
            ));
        }

        return view('back-end.splash_screen.create')->with(compact(
            'quick_add'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $this->validate(
            $request,
            ['sort' => ['required']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
            ['translations.*.description' => ['required', 'max:255']],
        );
        try {
            DB::beginTransaction();
            $splash_screen = SplashScreen::create([
                "sort" => $request->sort,
               ]);

            $splash_screen->update($request->translations);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $splash_screen->addMedia($filePath)->toMediaCollection('images');
                }
            }
            $splash_screen_id=$splash_screen->id;
            DB::commit();
            $output = [
                'success' => true,
                'splash_screen_id' => $splash_screen_id,
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


//        return redirect()->to('splash_screen')->with('status', $output);
    }




    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $splash_screen = SplashScreen::find($id);

        return view('back-end.splash_screen.edit')->with(compact(
            'splash_screen'
        ));
    }


    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['sort' => ['required']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required', 'max:255']],
            ['translations.*.description' => ['required', 'max:255']],
        );

        try {
            DB::beginTransaction();
            $splash_screen = SplashScreen::find($id);
            $splash_screen->sort=$request->sort;
            $splash_screen->save();
            $splash_screen->update($request->translations);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $splash_screen->clearMediaCollection('images');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $splash_screen->addMedia($filePath)->toMediaCollection('images');
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
            $splash_screen = SplashScreen::find($id);
            if ($splash_screen){
                $splash_screen->clearMediaCollection('images');
                $splash_screen->delete();
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

    public function update_status(Request $request ){

        try {
            $splash_screen=SplashScreen::find($request->id);
            if(!$splash_screen){
                return [
                    'success'=>false,
                    'msg'=>translate('splash_screen_not_found')
                ];
            }


            DB::beginTransaction();
            $splash_screen->is_active=($splash_screen->is_active - 1) *-1;
            $splash_screen->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('splash_screen updated successfully!')
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

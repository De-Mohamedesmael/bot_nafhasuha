<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;

use App\Models\Transporter;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class TransporterController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;


    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $logo=\Settings::get('logo');
            $transporters = Transporter::listsTranslations('title')
                ->select('transporters.*',
                    'transporter_translations.title'
                );
            $transporters=$transporters->groupBy('transporters.id');
            return DataTables::of($transporters)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')

                ->addColumn('image', function ($row) use($logo) {
                    $image = asset('assets/images/'.$row->image);
                    if ($row->image) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/assets/images/settings/' . $logo) . '" height="50px" width="50px">';
                    }
                })->addColumn('status', function ($row) {
                    $checked=$row->status?'checked':'';
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" transporter="menu">';




                        $html .= '<li class="divider"></li>';
                        $html .='<li>
                                                <a href="'. route('admin.transporters.edit',$row->id) .'" target="_blank"><i
                                                        class="dripicons-document-edit btn"></i>'.__('lang.edit').'</a>
                                            </li>';

//                        }
                        $html .= '<li class="divider"></li>';
//                            if (auth()->transporter()->can('transporter_module.transporter.delete')) {
                                $html .=
                                    '<li>
                                    <a data-href="' . route('admin.transporters.delete', $row->id)  . '"
                                        data-check_password="' . route('admin.checkPassword', Auth::id()) . '"
                                        class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                        ' . __('lang.delete') . '</a>
                                    </li>';
//                            }



                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'image',
                    'status',
                    'created_at',
                ])
                ->make(true);
        }

        return view('back-end.transporters.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('back-end.transporters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $filteredInput = array_filter($request->all(), function ($value) {
            return $value !== null;
        });
        $filteredRequest = new Request($filteredInput);
        $validator = validator($filteredRequest->all(), [
            'sort' => 'required|integer',
            'price' => 'required|numeric',
            'price_for_minute' => 'required|numeric',
            'price_for_kilo' => 'required|numeric',
            'title' => 'required|string',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 405,
                'error' =>$validator->errors()->first()
            ];
        }
         try {
        DB::beginTransaction();
        $data=$request->translations;
        $data['sort']=$request->sort;
        $data['price']=$request->price;
        $data['price_for_kilo']=$request->price_for_kilo;
        $data['price_for_minute']=$request->price_for_minute;
        $transporter = Transporter::create($data);

             if ($request->has("cropImages") && count($request->cropImages) > 0) {
                 foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                     $folderPath = 'assets/images/transporters/';
                     $extention = explode(";", explode("/", $imageData)[1])[0];
                     $image = rand(1, 1500) . "_image." . $extention;
                     $filePath = $folderPath . $image;
                     $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                     $transporter->image = 'transporters/' . $image;
                     $transporter->save();
                 }

             }
        $transporter_id=$transporter->id;
        DB::commit();
        $output = [
            'code' => 200,
            'transporter_id' => $transporter_id,
            'msg' => __('lang.success')
        ];
         } catch (\Exception $e) {
            dd($e);
             Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
             $output = [
                 'code' => 500,
                 'msg' => __('lang.something_went_wrong')
             ];
         }


        return $output;


//        return redirect()->to('transporter')->with('status', $output);
    }

    public function edit($id)
    {
        $transporter = Transporter::find($id);

        return view('back-end.transporters.edit')->with(compact(
            'transporter'
        ));
    }


    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['sort' => ['required','integer']],
            ['price' => ['required','numeric']],
            ['price_for_minute' => ['required','numeric']],
            ['price_for_kilo' => ['required','numeric']],
            ['title' => ['required','string']],
            ['translations' => ['required','array']],
            ['translations.*' => ['required','array']],
            ['translations.*.title' => ['required','string','max:255']],
        );

        try {
            DB::beginTransaction();
            $data=$request->translations;
            $data['sort']=$request->sort;
            $data['price']=$request->price;
            $data['price_for_kilo']=$request->price_for_kilo;
            $data['price_for_minute']=$request->price_for_minute;
            $transporter = Transporter::find($id);
            $transporter->update($data);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($this->getCroppedImages($request->cropImages) as $imageData) {

                    $folderPath = 'assets/images/transporters/';
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = $folderPath . $image;
                    if (!empty($transporter->image)) {
                        $oldImagePath = 'assets/images/' . $transporter->image;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $transporter->image = 'transporters/' . $image;
                    $transporter->save();
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

        return  $output;
    }

    public function update_status(Request $request ){

        try {
            $transporter=Transporter::find($request->id);
            if(!$transporter){
                return [
                    'success'=>false,
                    'msg'=>translate('transporter_not_found')
                ];
            }


            DB::beginTransaction();
            $transporter->status=($transporter->status - 1) *-1;
            $transporter->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('transporter updated successfully!')
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
            $transporter = Transporter::find($id);
            if ($transporter){
                $transporter->delete();
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


    /**
     * delete Image for Transporter
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request  $request)
    {
        try {
            DB::beginTransaction();
            $transporter = Transporter::find($request->id);
            if($transporter){
                $transporter->clearMediaCollection('images');

                DB::commit();
                $output = [
                    'success' => true,
                    'msg' => __('lang.success')
                ];
            }else{
                $output = [
                    'success' => false,
                    'msg' => __('lang.something_went_wrong')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
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
}

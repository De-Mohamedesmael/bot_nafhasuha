<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use function App\CPU\translate;

class ServiceController extends ApiController
{
     public function __construct()
    {

    }

    public function index(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $services= Service::Active()->orderBy('sort', 'Asc')->get();
        return  responseApi(200, translate('return_data_success'),ServiceResource::collection($services));

    }

}

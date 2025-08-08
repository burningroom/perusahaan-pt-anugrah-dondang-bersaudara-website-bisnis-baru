<?php

namespace App\Http\Controllers;

use App\Services\SoapService;
use Illuminate\Http\Request;

class InaportnetController extends Controller
{
    public function index(Request $request)
    {
        // Disable Laravel's default error handling to let SOAP handle it
        ini_set("soap.wsdl_cache_enabled", "0");

        $options = [
            'uri' => url('/services/inaportnet'),
        ];


        $server = new \SoapServer(null, $options);
        $server->setObject(new SoapService());

        ob_start();
        $server->handle();
        $response = ob_get_clean();

        return response($response, 200)
            ->header('Content-Type', 'text/xml');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [])
    {
        return $this->json($data,200,200);
    }

    public function json($data,$code,$httpCode)
    {
        $data = [
            'code' => $code,
            'data' => $data,
        ];

        return response()->json($data,$httpCode);
    }
}

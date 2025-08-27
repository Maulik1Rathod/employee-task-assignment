<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     @OA\Response(
     *         response="200",
     *         description="The data"
     *     )
     * )
     */
    public function getData()
    {
        return response()->json(['data' => 'This is the data']);
    }
}

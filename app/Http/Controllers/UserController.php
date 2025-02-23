<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request, $route, $id = null)
    {
        return response()->json([
            'message' => $request->route(),
        ]);
    }
}

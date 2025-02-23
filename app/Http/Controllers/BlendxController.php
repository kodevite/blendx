<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BlendxController extends Controller
{
    public static function index(Request $request){
        $uri = $request->path();
        $blended = BlendxHelpers::blendme($uri);
        return $blended->path::all();
    }
}

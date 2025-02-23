<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class BlendxController extends Controller
{
    public static function index(Request $request){
        $uri = $request->path();
        $blended = BlendxHelpers::blendme($uri);
        $data = $blended->path::paginate(10);
        return response()->json(BlendxHelpers::generate_response(false, 'Data retrieved successfully!', $data), 200);
    }

    public static function show(Request $request, $route, $id){
        $uri = $request->path();
        $blended = BlendxHelpers::blendme($uri);
        $data = $blended->path::findOrFail($id);
        return response()->json(BlendxHelpers::generate_response(false, 'Data retrieved successfully!', $data), 200);
    }

    public static function store(Request $request){
        $uri = $request->path();
        $blended = BlendxHelpers::blendme($uri);
        $getValidationRules = BlendxHelpers::store_validator($request,$blended->name);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $validator = Validator::make(
            $request->all(),
            $getValidationRules

        );

        if ($validator->fails()) return response()->json(BlendxHelpers::generate_response(true, 'Validation failed!', $validator->errors()), 400);

        $user = $blended->model::create($request->all());
        return response()->json(BlendxHelpers::generate_response(false, 'Data stored successfully!', $user), 200);
    }

    public static function register(Request $request){
        $request->all();
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        return $validate;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlendxHelpers extends Controller
{

    public static function blendme($uri) {
        $routes = explode('/', $uri);
        $route = Str::singular($routes[1]);
        $model = BlendxHelpers::route_to_model($route);
        return $model;
    }
    public static function route_to_model($route){
        $model_name = Str::ucfirst(Str::camel($route));
        $model_path = "App\\Models\\".$model_name;
        if(!class_exists($model_path)){
            return response()->json(BlendxHelpers::generate_response(true, 'Model not found!', []), 404);
        }
        $model = new \stdClass();
        $model->name = $model_name;
        $model->path = $model_path;
        return $model;
    }

    public static function generate_response($error, $message, $data){
        $response = new \stdClass();
        $response->error = $error;
        $response->message = $message;
        $response->data = $data;
        return $response;
    }

    public static function is_api_request($request){
        return $request->is('api/*');
    }
}

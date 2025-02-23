<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    public static function store_validator($request,$model){
        $tableName = Str::plural(Str::snake($model));
        $columns = \DB::getSchemaBuilder()->getColumns($tableName);

        $rules = [];

        foreach ($columns as $column) {
            $name = $column['name'];
            $type = $column['type_name'];
            $nullable = $column['nullable'] ?? false;
            $autoIncrement = $column['auto_increment'] ?? false;

            if ($autoIncrement) {
                continue; // Skip auto-incremented fields like 'id'
            }

            $ruleSet = [];

            // Common Rules
            if (!$nullable) {
                $ruleSet[] = 'required';
            } else {
                $ruleSet[] = 'nullable';
            }

            // Type-based Validation
            if ($type === 'varchar') {
                $ruleSet[] = 'string';
                $ruleSet[] = 'max:255';
            } elseif ($type === 'bigint') {
                $ruleSet[] = 'integer';
                $ruleSet[] = 'min:1';
            } elseif ($type === 'timestamp') {
                $ruleSet[] = 'date';
            } elseif ($type === 'tinyint') {
                $ruleSet[] = 'boolean';
            } elseif ($type === 'text') {
                $ruleSet[] = 'string';
            } elseif ($type === 'decimal') {
                $ruleSet[] = 'numeric';
            } elseif ($type === 'enum') {
                $ruleSet[] = 'string';
                $ruleSet[] = 'in:' . implode(',', $column['enum_values']);
            } elseif ($type === 'json') {
                $ruleSet[] = 'array';
            } elseif ($type === 'jsonb') {
                $ruleSet[] = 'array';
            } elseif ($type === 'date') {
                $ruleSet[] = 'date';
            } elseif ($type === 'time') {
                $ruleSet[] = 'date_format:H:i:s';
            } elseif ($type === 'datetime') {
                $ruleSet[] = 'date';
            } elseif ($type === 'tinytext') {
                $ruleSet[] = 'string';
            } elseif ($type === 'mediumtext') {
                $ruleSet[] = 'string';
            } elseif ($type === 'longtext') {
                $ruleSet[] = 'string';
            } elseif ($type === 'double') {
                $ruleSet[] = 'numeric';
            } elseif ($type === 'float') {
                $ruleSet[] = 'numeric';
            } elseif ($type === 'integer') {
                $ruleSet[] = 'integer';
            } elseif ($type === 'smallint') {
                $ruleSet[] = 'integer';
            } elseif ($type === 'tinyint') {
                $ruleSet[] = 'boolean';
            } elseif ($type === 'mediumint') {
                $ruleSet[] = 'integer';
            } elseif ($type === 'bigint') {
                $ruleSet[] = 'integer';
            } elseif ($type === 'char') {
                $ruleSet[] = 'string';
                $ruleSet[] = 'max:1';
            } elseif ($type === 'binary') {
                $ruleSet[] = 'string';
            } elseif ($type === 'varbinary') {
                $ruleSet[] = 'string';
            } elseif ($type === 'blob') {
                $ruleSet[] = 'string';
            } elseif ($type === 'tinyblob') {
                $ruleSet[] = 'string';
            } elseif ($type === 'mediumblob') {
                $ruleSet[] = 'string';
            } elseif ($type === 'longblob') {
                $ruleSet[] = 'string';
            } elseif ($type === 'geometry') {
                $ruleSet[] = 'string';
            } elseif ($type === 'point') {
                $ruleSet[] = 'string';
            } elseif ($type === 'linestring') {
                $ruleSet[] = 'string';
            } elseif ($type === 'polygon') {
                $ruleSet[] = 'string';
            } elseif ($type === 'multipoint') {
                $ruleSet[] = 'string';
            } elseif ($type === 'multilinestring') {
                $ruleSet[] = 'string';
            } elseif ($type === 'multipolygon') {
                $ruleSet[] = 'string';
            } elseif ($type === 'geometrycollection') {
                $ruleSet[] = 'string
                ';
            }

            // Special Cases
            if ($name === 'email') {
                $ruleSet[] = 'email';
                $ruleSet[] = Rule::unique('users', 'email'); // Unique email
            }
            if ($name === 'password') {
                $ruleSet[] = 'min:8';
            }

            $rules[$name] = implode('|', $ruleSet);
        }
        return $rules;
    }
}

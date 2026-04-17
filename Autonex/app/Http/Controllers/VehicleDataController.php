<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleDataController extends Controller
{
    public function types(): JsonResponse
    {
        return response()->json(array_keys(config('vehicles.types', [])));
    }

    public function brands(Request $request): JsonResponse
    {
        $type = $request->query('type', '');
        $search = mb_strtolower($request->query('q', ''));

        $brands = array_keys(config("vehicles.types.{$type}", []));

        if ($search !== '') {
            $brands = array_values(array_filter($brands, fn($b) => str_contains(mb_strtolower($b), $search)));
        }

        return response()->json($brands);
    }

    public function models(Request $request): JsonResponse
    {
        $type = $request->query('type', '');
        $brand = $request->query('brand', '');
        $search = mb_strtolower($request->query('q', ''));

        $models = config("vehicles.types.{$type}.{$brand}", []);

        if ($search !== '') {
            $models = array_values(array_filter($models, fn($m) => str_contains(mb_strtolower($m), $search)));
        }

        return response()->json($models);
    }

    public function bodyTypes(Request $request): JsonResponse
    {
        $type = $request->query('type', '');

        return response()->json(config("vehicles.body_types.{$type}", config('vehicles.body_types.Egyéb', [])));
    }
}

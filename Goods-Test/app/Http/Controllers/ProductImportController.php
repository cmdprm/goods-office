<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductImportController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return response()->json(['success' => 'Товары успешно импортированы.']);
        } catch (\Exception $e) {
            Log::error('Ошибка импорта товаров: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка при импорте товаров.'], 500);
        }
    }
}

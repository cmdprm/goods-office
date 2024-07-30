<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductImportController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/products/import",
     *     summary="Import products from an Excel file",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="The Excel file to upload"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful import",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="string",
     *                 example="Товары успешно импортированы."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 description="Validation errors"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Ошибка при импорте товаров."
     *             )
     *         )
     *     )
     * )
     */
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

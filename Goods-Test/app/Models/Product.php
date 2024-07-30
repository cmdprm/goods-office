<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Product ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the product"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Price of the product"
 *     ),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *         @OA\Items(type="string"),
 *         description="Product images"
 *     ),
 *     @OA\Property(
 *         property="attributes",
 *         type="array",
 *         @OA\Items(type="object"),
 *         description="Product attributes"
 *     )
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_code',
        'name',
        'description',
        'price',
        'discount',
    ];

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}

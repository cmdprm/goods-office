<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Jobs\DownloadImage;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            $product = Product::create([
                'external_code' => $row['vnesnii_kod'],
                'name' => $row['naimenovanie'],
                'description' => $row['opisanie'],
                'price' => floatval(str_replace(',', '.', $row['cena_cena_prodazi'])),
                'discount' => 0,
            ]);

            foreach ($row as $key => $value) {
                if (strpos($key, 'dop_pole') !== false && $key !== 'dop_pole_ssylki_na_foto') {
                    ProductAttribute::create([
                        'product_id' => $product->id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }

            if (isset($row['dop_pole_ssylki_na_foto'])) {
                $imageUrls = explode(',', $row['dop_pole_ssylki_na_foto']);
                foreach ($imageUrls as $imageUrl) {
                    DownloadImage::dispatch($product->id, trim($imageUrl));
                }
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при создании товара: ' . $e->getMessage());
        }
    }

    protected function downloadImage($url)
    {
        try {
            $client = new Client();
            $response = $client->get($url);

            if ($response->getStatusCode() == 200) {
                $imageContent = $response->getBody()->getContents();
                $imageName = basename($url);
                $imagePath = 'public/products/' . $imageName;

                Storage::put($imagePath, $imageContent);

                return Storage::url($imagePath);
            } else {
                Log::error('Не удалось загрузить изображение с URL: ' . $url . '. Статус код: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке изображения с URL: ' . $url . ' - ' . $e->getMessage());
        }

        return null;
    }
}

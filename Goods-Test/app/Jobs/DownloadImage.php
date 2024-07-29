<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\ProductImage;

class DownloadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imageUrl;
    protected $productId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productId, $imageUrl)
    {
        $this->productId = $productId;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->get($this->imageUrl);

            if ($response->getStatusCode() == 200) {
                $imageContent = $response->getBody()->getContents();
                $imageName = basename($this->imageUrl);
                $imagePath = 'public/products/' . $imageName;

                Storage::put($imagePath, $imageContent);

                ProductImage::create([
                    'product_id' => $this->productId,
                    'url' => $this->imageUrl,
                    'path' => Storage::url($imagePath),
                ]);

            } else {
                Log::error('Не удалось загрузить изображение с URL: ' . $this->imageUrl . '. Статус код: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке изображения с URL: ' . $this->imageUrl . ' - ' . $e->getMessage());
        }
    }
}


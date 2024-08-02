<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Services\ElasticSearchService;

class UpdateVariationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $update;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($update)
    {
        $this->update = $update;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ElasticSearchService $elasticSearch)
    {
        $product = Product::find($this->update['product_id']);
        $variation = $product->variations()->find($this->update['variation_id']);

        if ($variation) {
            $variation->update([
                'size' => $this->update['size'],
                'price' => $this->update['price'],
            ]);

            $body = [
                'name' => $product->name,
                'brand' => $product->brand,
                'season' => $product->season,
                'color' => $product->color,
                'variations' => $product->variations->map(function ($variation) {
                    return [
                        'size' => $variation->size,
                        'price' => $variation->price,
                    ];
                }),
            ];

            $elasticSearch->index('products', $product->id, $body);
        }
    }
}

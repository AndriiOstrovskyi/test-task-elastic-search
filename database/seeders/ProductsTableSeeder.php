<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Variation;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'T-Shirt 1',
                'brand' => 'Brand A',
                'season' => 'Summer',
                'color' => 'Red',
                'variations' => [
                    ['size' => 'XS', 'price' => 10.99],
                    ['size' => 'S', 'price' => 12.99],
                    ['size' => 'M', 'price' => 14.99],
                    ['size' => 'L', 'price' => 16.99],
                    ['size' => 'XL', 'price' => 18.99],
                ],
            ],
            [
                'name' => 'T-Shirt 2',
                'brand' => 'Brand B',
                'season' => 'Winter',
                'color' => 'Blue',
                'variations' => [
                    ['size' => 'XS', 'price' => 11.99],
                    ['size' => 'S', 'price' => 13.99],
                    ['size' => 'M', 'price' => 15.99],
                    ['size' => 'L', 'price' => 17.99],
                    ['size' => 'XL', 'price' => 19.99],
                ],
            ],
            [
                'name' => 'T-Shirt 3',
                'brand' => 'Brand C',
                'season' => 'Spring',
                'color' => 'Green',
                'variations' => [
                    ['size' => 'XS', 'price' => 9.99],
                    ['size' => 'S', 'price' => 11.99],
                    ['size' => 'M', 'price' => 13.99],
                    ['size' => 'L', 'price' => 15.99],
                    ['size' => 'XL', 'price' => 17.99],
                ],
            ],
            [
                'name' => 'T-Shirt 4',
                'brand' => 'Brand D',
                'season' => 'Fall',
                'color' => 'Yellow',
                'variations' => [
                    ['size' => 'XS', 'price' => 12.99],
                    ['size' => 'S', 'price' => 14.99],
                    ['size' => 'M', 'price' => 16.99],
                    ['size' => 'L', 'price' => 18.99],
                    ['size' => 'XL', 'price' => 20.99],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $variations = $productData['variations'];
            unset($productData['variations']);
            
            $product = Product::create($productData);

            foreach ($variations as $variationData) {
                $variation = new Variation($variationData);
                $product->variations()->save($variation);
            }
        }
    }
}

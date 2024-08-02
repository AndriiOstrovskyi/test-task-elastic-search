<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Services\ElasticSearchService;
use App\Jobs\UpdateVariationJob;


class ProductController extends Controller
{
    protected $elasticSearch;

    public function __construct(ElasticSearchService $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    public function showAvailableSizesAndPrices()
    {
        // Отримуємо всі продукти
        $products = Product::with('variations')->get();

        // Повернення ресурсу зі списком варіацій для кожного продукту
        return ProductResource::collection($products);
    }

    public function indexElasticSearch()
    {
        // Отримуємо всі продукти
        $products = Product::with('variations')->get();

        foreach ($products as $product) {
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

            // Індексуємо продукт в ElasticSearch
            $this->elasticSearch->index('products', $product->id, $body);
        }

        return response()->json(['message' => 'Products indexed successfully']);
    }

    public function createElasticSearchIndex()
    {
        $indexParams = [
            'index' => 'products'
        ];

        if ($this->elasticSearch->indexExists($indexParams)) {
            return response()->json(['message' => 'Index already exists']);
        }

        $params = [
            'index' => 'products',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'name' => ['type' => 'text'],
                        'brand' => ['type' => 'text'],
                        'season' => ['type' => 'text'],
                        'color' => ['type' => 'text'],
                        'variations' => [
                            'type' => 'nested',
                            'properties' => [
                                'size' => ['type' => 'keyword'],
                                'price' => ['type' => 'float']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->elasticSearch->createIndex($params);
            return response()->json(['message' => 'Index created successfully', 'response' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateVariations(Request $request)
    {
        $updates = $request->all(); // Очікується, що ERP надішле масив з оновленнями

        foreach ($updates as $update) {
            dispatch(new UpdateVariationJob($update));
        }

        return response()->json(['message' => 'Variations updated successfully']);
    }
    
}

<?php

use Illuminate\Support\Facades\Route;
use App\Services\ElasticSearchService;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-elasticsearch', function (ElasticSearchService $elasticSearchService) {
    $index = 'test';
    $id = '1';
    $body = ['testField' => 'testValue'];

    // Index a document
    $indexResponse = $elasticSearchService->index($index, $id, $body);

    // Search for the document
    $searchResponse = $elasticSearchService->search($index, [
        'query' => [
            'match' => [
                'testField' => 'testValue'
            ]
        ]
    ]);

    // Delete the document
    $deleteResponse = $elasticSearchService->delete($index, $id);

    return response()->json([
        'index' => $indexResponse,
        'search' => $searchResponse,
        'delete' => $deleteResponse
    ]);
});

Route::get('/create-elasticsearch-index', [ProductController::class, 'createElasticSearchIndex']);

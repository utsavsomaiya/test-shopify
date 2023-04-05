<?php

use App\Models\Product as ModelsProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;
use Shopify\Auth\FileSessionStorage;
use Shopify\Auth\OAuth;
use Shopify\Clients\Rest;
use Shopify\Context;
use Shopify\Rest\Admin2023_01\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/install', function () {
    // Initialize the Shopify App Context
    Context::initialize(
        env('SHOPIFY_API_KEY'),
        env('SHOPIFY_API_SECRET'),
        explode(',', env('SHOPIFY_APP_SCOPES')),
        env('SHOPIFY_APP_HOST_NAME'),
        new FileSessionStorage(),
        '2023-01',
        true,
        false,
    );

    // Start the OAuth Process
    $redirectUrl = OAuth::begin('robote-store.myshopify.com', '/callback', false);

    // Redirect the User to Shopify for Authorization
    return redirect($redirectUrl)->withCookie(
        cookie('shopify_session_id', 'offline_robote-store.myshopify.com'),
    );
});

Route::get('/callback', function (Request $request) {

    // dd(Uuid::uuid4()->toString());
    // Initialize the Shopify App Context
    Context::initialize(
        env('SHOPIFY_API_KEY'),
        env('SHOPIFY_API_SECRET'),
        env('SHOPIFY_APP_SCOPES'),
        env('SHOPIFY_APP_HOST_NAME'),
        new FileSessionStorage(),
        '2023-01',
        true,
        false,
    );

    $session = OAuth::callback([
        'shopify_session_id' => $sessionId = $request->cookie('shopify_session_id'),
        'shopify_session_id_sig' => hash_hmac('sha256', $sessionId, env('SHOPIFY_API_SECRET')),
    ], request()->query());

    $product = Product::all($session);

    dd($product);
});

Route::get('/', function () {
    return view('welcome', [
        'products' => [ModelsProduct::first()->toArray(), ModelsProduct::all()->last()->toArray()],
    ]);
});

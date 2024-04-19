<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom model
    |--------------------------------------------------------------------------
    |
    | This option defines the item model, by pointing it to a laravel model, e.g
    | App\Models\Product::class
    |
    */
    'item_model' => 'LARAVEL_PRODUCT_MODEL',

    /*
    |--------------------------------------------------------------------------
    | Wishlist table name
    |--------------------------------------------------------------------------
    |
    | Provide a different table name if needed.
    |
    */
    'table_name' => 'cart',

    /*
    |--------------------------------------------------------------------------
    | Custom Cart model
    |--------------------------------------------------------------------------
    |
    | This option allows for the extension of the wishlist Model
    | App\Models\MyCart::class
    |
    */
    'model' => r4f4dev\Cartel\Models\Cart::class,
];

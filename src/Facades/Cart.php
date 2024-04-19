<?php

namespace r4f4dev\Cartel\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @method static \r4f4dev\Cartel\Cart getUserCartList(int | string $user, string $type = 'user')
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Cart';
    }
}

<?php

namespace r4f4dev\Cartel;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart
{
    public $instance;

    public $cartModel;

    public function __construct()
    {
        $this->cartModel = config('cart.model');
        $this->instance = new $this->cartModel();
    }

    /**
     * Adds a product to the cart associating it to a given user.
     * Returns false on failure.
     */
    public function add(int $item, int|string $user, int $count, string $type = 'user'): bool
    {
        return Cart::create($item, $user, $count, $type);
    }

    /**
     * Returns the cart of a specified user.
     */
    public function getUserCartList(int|string $user, string $type = 'user'): Collection
    {
        return $this->instance->ofUser($user, $type)->with('item')->get();
    }

    /**
     * Removes a specific cart entry from a given user.
     */
    public function remove(int $id, int|string $user, string $type = 'user'): bool|null
    {
        $cart = $this->instance->where('id', $id)
            ->ofUser($user, $type)->first();

        if (! $cart) {
            return false;
        }

        return $cart->delete();
    }

    /**
     * Increases a specific cart entry from a given user.
     *
     * @param  int  $id
     */
    public function increase(int $item, int|string $user, string $type = 'user'): bool|null
    {
        $itembe = $this->instance->byItem($item)->ofUser($user, $type)->first();

        if (! $itembe) {
            return false;
        }

        if ($itembe->count >= 100) {
            return false;
        }

        $itembe->count++;

        return $itembe->save();
    }

    /**
     * Decreases a specific cart entry from a given user.
     *
     * @param  int  $id
     */
    public function decrease(int $item, int|string $user, string $type = 'user'): bool|null
    {
        $itembe = $this->instance->byItem($item)->ofUser($user, $type)->first();

        if (! $itembe) {
            return false;
        }

        if ($itembe->count === 1) {
            return $this->removeByItem($item, $user, $type);
        }

        $itembe->count--;

        return $itembe->save();
    }

    /**
     * Removes all values from a user cart.
     */
    public function removeUserCart(int|string $user, string $type = 'user'): mixed
    {
        return $this->instance->ofUser($user, $type)->delete();
    }

    /**
     * Removes a specific item from a specified user.
     */
    public function removeByItem(int $item, int|string $user, string $type = 'user'): bool|null
    {
        return $this->getCartItem($item, $user, $type)->delete();
    }

    /**
     * Number of cart items by user
     */
    public function count(int|string $user, string $type = 'user'): int
    {
        return $this->instance->ofUser($user, $type)->count();
    }

    /**
     * Number of cart items by user
     */
    public function getTotal(int|string $user, string $type = 'user'): int
    {
        $total = 0;

        if ($this->count($user, $type) > 0) {
            foreach ($this->instance->ofUser($user, $type)->get() as $item) {
                $total += $item->count * $item->item->price;
            }

        }

        return $total;
    }

    /**
     * Get cart item from a user
     */
    public function getCartItem(int $item, int|string $user, string $type = 'user'): Model
    {
        return $this->instance->byItem($item)
            ->ofUser($user, $type)->first();
    }

    /**
     * Associates a session_id cart to a given user_id wishlist.
     */
    public function assocSessionCartToUser(int $user_id, string $session_id): bool
    {
        $sessionCartList = $this->getUserCartList($session_id, 'session');
        if ($sessionCartList->isEmpty()) {
            return true;
        }

        try {
            DB::transaction(function () use ($sessionCartList, $user_id, $session_id) {
                foreach ($sessionCartList as $sessionItem) {
                    $association = Cart::create($sessionItem->item_id, $user_id, $sessionItem->count);
                    if (! $association) {
                        throw new \Exception('Error');
                    }
                }

                $this->removeUserCart($session_id, 'session');
            });
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected static function create(int $item, int|string $user, int $count, string $type = 'user'): bool
    {
        $column = ($type === 'user') ? 'user_id' : 'session_id';

        $matchThese = [
            'item_id' => $item,
            'count' => $count,
            $column => $user,
        ];

        $cart = config('cart.model')::updateOrCreate($matchThese, $matchThese);

        return (bool) $cart;
    }
}

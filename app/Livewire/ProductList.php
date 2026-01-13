<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductList extends Component
{
    // This tells the Product List to refresh whenever stock changes in the cart
    protected $listeners = ['stockChanged' => '$refresh'];

    public function addToCart($productId)
    {
        if (!Auth::check()) return redirect()->route('login');

        $product = Product::findOrFail($productId);

        if ($product->stock_quantity > 0) {
            $product->decrement('stock_quantity');

            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => 1
                ]);
            }

            if ($product->stock_quantity <= 5) {
                \App\Jobs\SendLowStockEmail::dispatch($product);
            }
            // Tell the Cart to update
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        return view('livewire.product-list', [
            'products' => Product::all() // Or your specific query
        ]);
    }
}

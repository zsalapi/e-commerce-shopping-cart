<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ShoppingCart extends Component
{
    // Listen for changes made in the Product List
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function updateQuantity($itemId, $amount)
    {
        // Eager load 'product' to access stock_quantity immediately
        $item = CartItem::with('product')->where('user_id', Auth::id())->find($itemId);

        if ($item) {
            $product = $item->product;

            if ($amount > 0) {
                if ($product->stock_quantity < $amount) {
                    session()->flash('error', 'Not enough stock!');
                    return;
                }
                $product->decrement('stock_quantity', $amount);
            } else {
                $product->increment('stock_quantity', abs($amount));
            }

            $item->quantity += $amount;
            $item->quantity <= 0 ? $item->delete() : $item->save();

            // Refresh this component AND tell the Product List to refresh
            $this->dispatch('stockChanged');
        }
    }

    public function removeItem($itemId)
    {
        // Eager load 'product'
        $item = CartItem::with('product')->where('user_id', Auth::id())->find($itemId);

        if ($item) {
            $item->product->increment('stock_quantity', $item->quantity);
            $item->delete();

            $this->dispatch('stockChanged');
        }
    }

    public function render()
    {
        // Eager loading products in the render loop
        $items = CartItem::with('product')->where('user_id', Auth::id())->get();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return view('livewire.shopping-cart', compact('items', 'total'));
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        // 1. Calculate Total
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // 2. Create the Parent Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'completed',
        ]);

        // 3. Create individual items and clean up
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price_at_purchase' => $item->product->price,
            ]);

            $item->delete();
        }

        session()->flash('message', 'Order #' . $order->id . ' placed successfully!');
        $this->dispatch('cartUpdated');
    }
}

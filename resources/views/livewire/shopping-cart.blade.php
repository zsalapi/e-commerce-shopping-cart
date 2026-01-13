<div> <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Your Cart</h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                {{ $items->sum('quantity') }} Items
            </span>
        </div>

        @if (session()->has('message'))
            <div class="p-3 mb-4 text-sm text-green-800 bg-green-50 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-3 mb-4 text-sm text-red-800 bg-red-50 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="flow-root">
            <ul role="list" class="-my-6 divide-y divide-gray-200">
                @forelse($items as $item)
                    <li class="py-6 flex" wire:key="cart-item-{{ $item->id }}">
                        <div class="flex-1 ml-4 flex flex-col">
                            <div>
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <h3>{{ $item->product->name }}</h3>
                                    <p class="ml-4">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">${{ number_format($item->product->price, 2) }} each</p>
                            </div>
                            <div class="flex-1 flex items-end justify-between text-sm">
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button
                                        wire:click="updateQuantity({{ $item->id }}, -1)"
                                        class="px-2 py-1 hover:bg-gray-100 text-gray-600 border-r border-gray-300">
                                        -
                                    </button>
                                    <span class="px-3 py-1 font-medium">{{ $item->quantity }}</span>
                                    <button
                                        wire:click="updateQuantity({{ $item->id }}, 1)"
                                        class="px-2 py-1 hover:bg-gray-100 text-gray-600 border-l border-gray-300">
                                        +
                                    </button>
                                </div>

                                <div class="flex">
                                    <button
                                        wire:click="removeItem({{ $item->id }})"
                                        type="button"
                                        class="font-medium text-red-600 hover:text-red-500">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-12 text-center">
                        <p class="text-gray-500 italic text-sm">Your cart is currently empty.</p>
                        <p class="text-xs text-gray-400 mt-1">Add items from the product list to see them here.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        @if($items->isNotEmpty())
            <div class="border-t border-gray-200 mt-6 pt-6">
                <div class="flex justify-between text-base font-bold text-gray-900">
                    <p>Total</p>
                    <p>${{ number_format($total, 2) }}</p>
                </div>
                <div class="mt-6">
                    <button
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 transition">

                        <span wire:loading.remove wire:target="checkout">Checkout</span>
                        <span wire:loading wire:target="checkout">Processing Order...</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

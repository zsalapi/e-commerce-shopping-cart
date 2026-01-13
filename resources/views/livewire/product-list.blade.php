<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($products as $product)
        <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                    <span class="text-sm font-semibold text-indigo-600">Qty: {{ $product->stock_quantity }}</span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
                <p class="mt-4 text-2xl font-black text-gray-900">${{ number_format($product->price, 2) }}</p>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <button
                    wire:click="addToCart({{ $product->id }})"
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center px-4 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                        @auth Add to Cart @else Log in to Buy @endauth
                    </span>
                    <span wire:loading wire:target="addToCart({{ $product->id }})">Processing...</span>
                </button>
            </div>
        </div>
    @endforeach
</div>

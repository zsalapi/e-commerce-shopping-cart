<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                <div class="md:col-span-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-2xl font-bold mb-6">Available Products</h2>
                        <livewire:product-list />
                    </div>
                </div>

                <div class="md:col-span-4">
                    <div class="sticky top-6">
                        <livewire:shopping-cart />
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

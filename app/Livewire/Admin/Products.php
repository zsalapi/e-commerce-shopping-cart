<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Products extends Component
{
    use WithPagination;

    public $name, $price, $stock_quantity, $product_id;
    public $isModalOpen = false;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.products', [
            'products' => Product::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->price = '';
        $this->stock_quantity = '';
        $this->product_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
        ]);

        session()->flash('message', $this->product_id ? 'Product Updated Successfully.' : 'Product Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;

        $this->openModal();
    }

    public function delete($id)
    {
        Product::find($id)?->delete();
        session()->flash('message', 'Product Deleted Successfully.');
    }
}

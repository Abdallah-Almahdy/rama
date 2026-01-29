<?php

namespace App\Livewire\Products;

use Livewire\Component;

use App\Models\company;
use App\Models\product;
use App\Models\section;
use Livewire\WithFileUploads;
use App\Models\subSection;

class create  extends Component
{
    use WithFileUploads;

    public $name;
    public $price;
    public $photo;
    public $section;
    public $stock;
    public $stockQnt;
    public $bar_code;
    public $description;

    public function create()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'section' => 'required',
            'photo' => 'image|max:1024',
        ]);

        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'photo' => $this->photo->storeAs('products', rand() . '.jpg', 'my_public'),
            'section_id' => $this->section,
            'active' => $this->stock ?? true,
            'qnt' => $this->stockQnt ?? 5000,
            'bar_code' => $this->bar_code,
            'description' => $this->description,
        ];

        product::create($data);

        session()->flash('done', 'تم إضافة منتج جديد بنجاح');
        $this->reset();
    }

    public function render()
    {
        $sections = subSection::all();
        $companies = company::all();
        return view('livewire.products.create', [
            'sections' => $sections,
            'companies' => $companies,
        ]);
    }
}

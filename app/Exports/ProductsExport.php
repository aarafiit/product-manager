<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'Description',
            'Quantity',
            'Price',
            'Image URL'
        ];
    }

    /**
     * @param $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            optional($product->category)->name,
            $product->description,
            $product->qty,
            $product->price,
            $product->image ? asset('images/' . $product->image) : 'No Image'
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('products.index',compact('products', 'categories'));
    }

    public function create(){
        $categories = Category::all();
        return view('products.view', compact('categories'));
    }

    public function store(Request $request)
        {
                $data = $request->validate([
                        'name' => 'required|string|max:255',
                        'qty' => 'required|numeric',
                        'price' => 'required|numeric',
                        'description' => 'required',
                        'category_id' => 'required|exists:categories,id',
                        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);

                   if ($request->hasFile('image')) {
                       $imageName = time() . '.' . $request->image->extension();
                       $request->image->move(public_path('images'), $imageName);
                       $data['image'] = $imageName;
                   }

                    Product::create($data);

                    return redirect()->route('product.index');
        }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', ['product' => $product, 'categories' => $categories]);
    }

    public function update(Request $request, Product $product)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'qty' => 'required|numeric',
                'price' => 'required|numeric',
                'description' => 'required',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path('images/' . $product->image))) {
                    unlink(public_path('images/' . $product->image));
                }

                $imageName = time() . '.' . $request->image->extension();

                $request->image->move(public_path('images'), $imageName);

                $data['image'] = $imageName;
            }

            $product->update($data);

            return redirect()->route('product.index')->with('success', 'Product updated successfully.');
        }

    public function destroy(Product $product)
     {
         $product->delete();

         return redirect()->route('product.index');
     }

    public function export()
     {
         return Excel::download(new ProductsExport, 'products.xlsx');
     }

    public function search(Request $request)
    {
        $query = Product::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by minimum price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        // Filter by maximum price
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Apply sorting if needed
        if ($request->filled('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }






}

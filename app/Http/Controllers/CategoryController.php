<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function check(Request $request)
    {
        $name = strtolower($request->input('name'));
        $exists = Category::whereRaw('LOWER(name) = ?', [$name])->exists();
        return response()->json(['exists' => $exists]);
    }

    // Store a new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Example of saving to database
        $category = Category::create([
            'name' => $validated['name'],
        ]);

        return response()->json(['id' => $category->id]);
    }
}

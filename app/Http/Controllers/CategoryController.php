<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function store(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::create($v);
        return response()->json([
            'message' => 'create category',
            'category' => $category
        ], 201);
    }
}

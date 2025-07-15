<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $request->slug ?? Str::slug($request->name);

        $imagePath = null;
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = 'images/variants/' . $imageName;
                $imageFile->move(public_path('images/variants'), $imageName);
            }

            $categories = Category::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'description' => $validatedData['description'],
                'parent_id' => $validatedData['parent_id'],
                'is_active' => $validatedData['is_active'],
                'image' => $imagePath,
            ]);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id); 
        $otherCategories = Category::where('id', '!=', $id)->get(); 
        return view('categories.edit', compact('category', 'otherCategories'));
    }

     
     public function show(Category $category)
    {
        $products = $category->products()->where('is_active', true)->paginate(12);
        return view('frontend.category.show', compact('category', 'products'));
    }


    public function update(Request $request, Category $category)
    {
       $validatedData = $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|unique:categories,slug,' . $request->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $request->slug ?? Str::slug($request->name);

         $imagePath = $category->image; 
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $imageFile = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
                $imagePath = 'images/variants/' . $imageName;
                $imageFile->move(public_path('images/variants'), $imageName);
            }
             elseif ($request->input('clear_image')) { 
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $imagePath = null;
            }

        $category = Category::update([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'description' => $validatedData['description'],
                'parent_id' => $validatedData['parent_id'],
                'is_active' => $validatedData['is_active'],
                'image' => $imagePath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy($id)
    {
         $id = Category::find($id);
        $id->delete();
        return redirect()->route('categories.index');
    }
}


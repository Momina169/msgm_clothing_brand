<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Category; 
use App\Models\TopHeadline; 

class HomeController extends Controller
{
    public function index(): View
    {  
        // $categories = Category::where('is_active', true)->get();
         $categories = Category::whereNull('parent_id')->where('is_active', true)->get();

        $parentCategory = Category::with(['children' => function($query) {
                                        $query->where('is_active', true)->orderBy('name');
                                    }])
                                    ->where('slug', 'spring-summer-collection') 
                                    ->where('is_active', true)
                                    ->first();

        $topHeadline = TopHeadline::latest()->first();

        return view('frontend.home', compact('categories', 'topHeadline', 'parentCategory'));
    }
}

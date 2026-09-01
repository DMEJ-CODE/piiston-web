<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administration\CMSPage;
use App\Models\Administration\FAQCategory;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function pages()
    {
        return response()->json(CMSPage::all());
    }

    public function faq()
    {
        return response()->json(FAQCategory::with('items')->get());
    }

    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string|unique:cms_pages,slug',
            'content' => 'required|string',
        ]);

        $page = CMSPage::create($validated);

        return response()->json($page, 201);
    }
}

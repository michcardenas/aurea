<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPageSetting;
use Illuminate\Http\Request;

class AdminBlogPageController extends Controller
{
    public function edit()
    {
        $page = BlogPageSetting::getCurrent();

        return view('admin.pages.blog.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = BlogPageSetting::getCurrent();

        $data = $request->except(['_token', '_method']);

        $page->update($data);

        return redirect()->route('admin.pages.blog.edit')
            ->with('success', 'Página del blog actualizada correctamente.');
    }
}

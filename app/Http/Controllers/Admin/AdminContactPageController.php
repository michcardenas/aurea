<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPageSetting;
use Illuminate\Http\Request;

class AdminContactPageController extends Controller
{
    public function edit()
    {
        $page = ContactPageSetting::getCurrent();

        return view('admin.pages.contact.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = ContactPageSetting::getCurrent();

        $data = $request->except(['_token', '_method']);

        $page->update($data);

        return redirect()->route('admin.pages.contact.edit')
            ->with('success', 'Página de contacto actualizada correctamente.');
    }
}

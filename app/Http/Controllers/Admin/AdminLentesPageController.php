<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LentesPageSetting;
use Illuminate\Http\Request;

class AdminLentesPageController extends Controller
{
    public function edit()
    {
        $page = LentesPageSetting::getCurrent();

        return view('admin.pages.lentes.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = LentesPageSetting::getCurrent();

        $data = $request->except(['_token', '_method']);

        // Product benefits — textarea one per line
        if ($request->has('product_benefits_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('product_benefits_text', ''))));
            $data['product_benefits'] = array_values($items);
            unset($data['product_benefits_text']);
        }

        $page->update($data);

        return redirect()->route('admin.pages.lentes.edit')
            ->with('success', 'Página de lentes actualizada correctamente.');
    }
}

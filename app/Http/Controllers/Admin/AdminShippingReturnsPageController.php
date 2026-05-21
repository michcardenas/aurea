<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingReturnsPageSetting;
use Illuminate\Http\Request;

class AdminShippingReturnsPageController extends Controller
{
    public function edit()
    {
        $page = ShippingReturnsPageSetting::getCurrent();

        return view('admin.pages.shipping-returns.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = ShippingReturnsPageSetting::getCurrent();

        $data = $request->except(['_token', '_method']);

        $page->update($data);

        return redirect()->route('admin.pages.shipping-returns.edit')
            ->with('success', 'Página de envíos y devoluciones actualizada correctamente.');
    }
}

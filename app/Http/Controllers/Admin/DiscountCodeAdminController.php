<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountCodeAdminController extends Controller
{
    public function index(): View
    {
        $codes = DiscountCode::latest()->get();

        return view('admin.discount-codes.index', compact('codes'));
    }

    public function create(): View
    {
        return view('admin.discount-codes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');

        DiscountCode::create($validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Código de descuento creado exitosamente.');
    }

    public function edit(DiscountCode $discountCode): View
    {
        return view('admin.discount-codes.edit', compact('discountCode'));
    }

    public function update(Request $request, DiscountCode $discountCode): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code,' . $discountCode->id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');

        $discountCode->update($validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Código de descuento actualizado exitosamente.');
    }

    public function destroy(DiscountCode $discountCode): RedirectResponse
    {
        if ($discountCode->times_used > 0) {
            $discountCode->update(['is_active' => false]);

            return redirect()->route('admin.discount-codes.index')
                ->with('success', 'El código ha sido desactivado (tiene usos registrados).');
        }

        $discountCode->delete();

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Código de descuento eliminado.');
    }
}

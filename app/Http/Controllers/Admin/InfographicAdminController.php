<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InfographicAdminController extends Controller
{
    public function index(): View
    {
        $infographics = InfographicImage::orderBy('sort_order')->orderByDesc('created_at')->get();

        return view('admin.infographics.index', compact('infographics'));
    }

    public function create(): View
    {
        return view('admin.infographics.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['image'] = $request->file('image')->store('infographics', 'public');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        InfographicImage::create($validated);

        return redirect()->route('admin.infographics.index')
            ->with('success', 'Infografía creada exitosamente.');
    }

    public function edit(InfographicImage $infographic): View
    {
        return view('admin.infographics.edit', compact('infographic'));
    }

    public function update(Request $request, InfographicImage $infographic): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($infographic->image);
            $validated['image'] = $request->file('image')->store('infographics', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $infographic->update($validated);

        return redirect()->route('admin.infographics.index')
            ->with('success', 'Infografía actualizada exitosamente.');
    }

    public function destroy(InfographicImage $infographic): RedirectResponse
    {
        Storage::disk('public')->delete($infographic->image);
        $infographic->delete();

        return redirect()->route('admin.infographics.index')
            ->with('success', 'Infografía eliminada exitosamente.');
    }
}

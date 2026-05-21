<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHeroController extends Controller
{
    public function edit()
    {
        $hero = HeroSetting::getCurrent();

        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $hero = HeroSetting::getCurrent();

        $data = $request->except(['_token', '_method', 'media_file', 'use_gradient', 'media_mode', 'hero_images_files', 'hero_images_existing', 'trust_items', 'trust_items_json']);

        $mode = $request->input('media_mode');

        // Handle single file upload (video or image for background)
        if ($request->hasFile('media_file')) {
            if ($hero->media_path) {
                Storage::disk('public')->delete($hero->media_path);
            }

            $file = $request->file('media_file');
            $ext = strtolower($file->getClientOriginalExtension());
            $data['media_type'] = in_array($ext, ['mp4', 'webm', 'mov']) ? 'video' : 'image';
            $data['media_path'] = $file->store('hero', 'public');
        }

        // If mode is "image" and no single file uploaded but gallery images exist, set type to image
        if ($mode === 'image' && !$request->hasFile('media_file')) {
            $data['media_type'] = 'image';
        }

        // If mode is "video" and no file uploaded, keep current media_type
        if ($mode === 'video' && !$request->hasFile('media_file')) {
            $data['media_type'] = 'video';
        }

        // If "use gradient" checkbox was checked OR radio set to gradient, remove media
        if ($request->use_gradient || $mode === 'gradient') {
            if ($hero->media_path) {
                Storage::disk('public')->delete($hero->media_path);
            }
            $data['media_type'] = 'gradient';
            $data['media_path'] = null;
        }

        // Hero images gallery (multiple uploads)
        $existing = $request->input('hero_images_existing', []);
        $oldImages = $hero->hero_images ?? [];

        // Delete removed images from storage
        foreach ($oldImages as $oldImg) {
            if (!in_array($oldImg, $existing)) {
                Storage::disk('public')->delete($oldImg);
            }
        }

        // Upload new images
        $newPaths = [];
        if ($request->hasFile('hero_images_files')) {
            foreach ($request->file('hero_images_files') as $file) {
                $newPaths[] = $file->store('hero', 'public');
            }
        }

        // Merge existing + new, max 10
        $data['hero_images'] = array_slice(array_merge($existing, $newPaths), 0, 10);

        // Trust items: prefer JSON payload {icon, text}; fallback to legacy textarea (one per line)
        $trustItems = null;
        if ($request->filled('trust_items_json')) {
            $decoded = json_decode($request->input('trust_items_json'), true);
            if (is_array($decoded)) {
                $trustItems = [];
                foreach ($decoded as $item) {
                    $icon = isset($item['icon']) ? trim($item['icon']) : '';
                    $text = isset($item['text']) ? trim($item['text']) : '';
                    if ($text === '') {
                        continue;
                    }
                    $trustItems[] = [
                        'icon' => $icon !== '' ? $icon : '✓',
                        'text' => $text,
                    ];
                }
                $trustItems = array_slice($trustItems, 0, 6);
            }
        } elseif ($request->has('trust_items')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->trust_items)));
            $trustItems = array_map(fn ($t) => ['icon' => '✓', 'text' => $t], array_values($lines));
        }

        if ($trustItems !== null) {
            $data['trust_items'] = $trustItems;
        }

        $hero->update($data);

        return redirect()->route('admin.hero.edit')
            ->with('success', 'Hero actualizado correctamente.');
    }
}

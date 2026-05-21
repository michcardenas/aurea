<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHomePageController extends Controller
{
    public function edit()
    {
        $page = HomePageSetting::getCurrent();

        return view('admin.pages.home.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = HomePageSetting::getCurrent();

        $data = $request->except(['_token', '_method']);

        // Category cards — array of objects (with image upload per card)
        if ($request->has('category_cards')) {
            $cards = [];
            $uploadedFiles = $request->file('category_cards', []);
            foreach ($request->input('category_cards', []) as $i => $card) {
                if (empty($card['name'])) {
                    continue;
                }

                // Resolve image path: previous, removed, or newly uploaded.
                $previousImage = $card['image'] ?? '';
                $finalImage = $previousImage;

                if (!empty($card['image_remove'])) {
                    if ($previousImage) {
                        Storage::disk('public')->delete($previousImage);
                    }
                    $finalImage = '';
                }

                $uploadedFile = $uploadedFiles[$i]['image_file'] ?? null;
                if ($uploadedFile) {
                    if ($previousImage && empty($card['image_remove'])) {
                        Storage::disk('public')->delete($previousImage);
                    }
                    $finalImage = $uploadedFile->store('category_cards', 'public');
                }

                $cards[] = [
                    'name' => $card['name'],
                    'link_param' => $card['link_param'] ?? '',
                    'description' => $card['description'] ?? '',
                    'icon_svg' => $card['icon_svg'] ?? '',
                    'image' => $finalImage,
                ];
            }
            $data['category_cards'] = $cards;
        }

        // Wipes features — textarea, one per line
        if ($request->has('wipes_features_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('wipes_features_text', ''))));
            $data['wipes_features'] = array_values($items);
            unset($data['wipes_features_text']);
        }

        // FAQs — array of {q, a}
        if ($request->has('faqs')) {
            $faqs = [];
            foreach ($request->input('faqs', []) as $faq) {
                if (!empty($faq['q'])) {
                    $faqs[] = ['q' => $faq['q'], 'a' => $faq['a'] ?? ''];
                }
            }
            $data['faqs'] = $faqs;
        }

        // Trust badges — array of objects
        if ($request->has('trust_badges')) {
            $badges = [];
            foreach ($request->input('trust_badges', []) as $badge) {
                if (!empty($badge['title'])) {
                    $badges[] = [
                        'icon_svg' => $badge['icon_svg'] ?? '',
                        'title' => $badge['title'],
                        'description' => $badge['description'] ?? '',
                    ];
                }
            }
            $data['trust_badges'] = $badges;
        }

        // CTA trust items — textarea, one per line
        if ($request->has('cta_trust_items_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('cta_trust_items_text', ''))));
            $data['cta_trust_items'] = array_values($items);
            unset($data['cta_trust_items_text']);
        }

        // Benefits cards — array of objects
        if ($request->has('benefits_cards')) {
            $cards = [];
            foreach ($request->input('benefits_cards', []) as $card) {
                if (!empty($card['title'])) {
                    $cards[] = [
                        'icon_svg' => $card['icon_svg'] ?? '',
                        'title' => $card['title'],
                        'description' => $card['description'] ?? '',
                    ];
                }
            }
            $data['benefits_cards'] = $cards;
        }

        // Comparison items — textareas, one per line
        if ($request->has('comparison_without_items_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('comparison_without_items_text', ''))));
            $data['comparison_without_items'] = array_values($items);
            unset($data['comparison_without_items_text']);
        }
        if ($request->has('comparison_with_items_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('comparison_with_items_text', ''))));
            $data['comparison_with_items'] = array_values($items);
            unset($data['comparison_with_items_text']);
        }

        // Promo background — imagen o video
        if ($request->hasFile('promo_background_file')) {
            if ($page->promo_background) {
                Storage::disk('public')->delete($page->promo_background);
            }
            $data['promo_background'] = $request->file('promo_background_file')->store('promo', 'public');
        }

        if ($request->boolean('promo_background_remove') && !$request->hasFile('promo_background_file')) {
            if ($page->promo_background) {
                Storage::disk('public')->delete($page->promo_background);
            }
            $data['promo_background'] = null;
        }

        unset($data['promo_background_file'], $data['promo_background_remove']);

        $page->update($data);

        return redirect()->route('admin.pages.home.edit')
            ->with('success', 'Secciones del inicio actualizadas correctamente.');
    }
}

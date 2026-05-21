<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlueLightPageSetting;
use Illuminate\Http\Request;

class AdminBlueLightPageController extends Controller
{
    public function edit()
    {
        $page = BlueLightPageSetting::getCurrent();

        return view('admin.pages.blue-light.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = BlueLightPageSetting::getCurrent();

        $data = $request->except(['_token', '_method']);

        // Symptoms cards — array of {icon, title, desc, color, bg}
        if ($request->has('symptoms_cards')) {
            $cards = [];
            foreach ($request->input('symptoms_cards', []) as $card) {
                if (!empty($card['title'])) {
                    $cards[] = [
                        'icon'  => $card['icon'] ?? '',
                        'title' => $card['title'],
                        'desc'  => $card['desc'] ?? '',
                        'color' => $card['color'] ?? 'text-red-500',
                        'bg'    => $card['bg'] ?? 'bg-red-50',
                    ];
                }
            }
            $data['symptoms_cards'] = $cards;
        }

        // Protection benefits — textarea one per line
        if ($request->has('protection_benefits_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('protection_benefits_text', ''))));
            $data['protection_benefits'] = array_values($items);
            unset($data['protection_benefits_text']);
        }

        // Profiles cards — array of {icon, title, desc}
        if ($request->has('profiles_cards')) {
            $cards = [];
            foreach ($request->input('profiles_cards', []) as $card) {
                if (!empty($card['title'])) {
                    $cards[] = [
                        'icon'  => $card['icon'] ?? '',
                        'title' => $card['title'],
                        'desc'  => $card['desc'] ?? '',
                    ];
                }
            }
            $data['profiles_cards'] = $cards;
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

        // Compare "without" items — textarea one per line
        if ($request->has('compare_without_items_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('compare_without_items_text', ''))));
            $data['compare_without_items'] = array_values($items);
            unset($data['compare_without_items_text']);
        }

        // Compare "with" items — textarea one per line
        if ($request->has('compare_with_items_text')) {
            $items = array_filter(array_map('trim', explode("\n", $request->input('compare_with_items_text', ''))));
            $data['compare_with_items'] = array_values($items);
            unset($data['compare_with_items_text']);
        }

        // Compare metrics — array of {number, description, label, type}
        if ($request->has('compare_metrics')) {
            $metrics = [];
            foreach ($request->input('compare_metrics', []) as $m) {
                if (!empty($m['number'])) {
                    $metrics[] = [
                        'number'      => $m['number'],
                        'description' => $m['description'] ?? '',
                        'label'       => $m['label'] ?? '',
                        'type'        => $m['type'] ?? 'red',
                    ];
                }
            }
            $data['compare_metrics'] = $metrics;
        }

        $page->update($data);

        return redirect()->route('admin.pages.blue-light.edit')
            ->with('success', 'Página de luz azul actualizada correctamente.');
    }
}

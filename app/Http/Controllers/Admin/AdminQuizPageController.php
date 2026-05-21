<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\QuizPageSetting;
use Illuminate\Http\Request;

class AdminQuizPageController extends Controller
{
    public function edit()
    {
        $page = QuizPageSetting::getCurrent();
        $products = Product::active()->orderBy('name')->get(['id', 'name']);

        return view('admin.pages.quiz.edit', compact('page', 'products'));
    }

    public function update(Request $request)
    {
        $page = QuizPageSetting::getCurrent();

        $data = $request->except(['_token', '_method', 'questions', 'questions_json', 'recommendation_rules', 'recommendation_rules_json']);

        // ── Recommendation rules ─────────────────────────────────────────
        // Prefer the JSON payload (more reliable across dynamic repeaters);
        // fall back to the nested array form if present.
        $rawRules = $this->decodeJsonInput($request, 'recommendation_rules_json')
            ?? $request->input('recommendation_rules', []);

        $rules = [];
        foreach ((array) $rawRules as $rule) {
            if (!empty($rule['product_id'])) {
                $rules[] = [
                    'condition_field' => $rule['condition_field'] ?? 'usage',
                    'condition_value' => $rule['condition_value'] ?? '',
                    'product_id' => (int) $rule['product_id'],
                    'reason' => $rule['reason'] ?? '',
                ];
            }
        }
        $data['recommendation_rules'] = $rules;

        // ── Questions ────────────────────────────────────────────────────
        $rawQuestions = $this->decodeJsonInput($request, 'questions_json')
            ?? $request->input('questions', []);

        $questions = [];
        foreach ((array) $rawQuestions as $q) {
            if (empty($q['key']) || empty($q['label'])) {
                continue;
            }
            $options = [];
            foreach ($q['options'] ?? [] as $opt) {
                if (!empty($opt['value']) && !empty($opt['label'])) {
                    $options[] = [
                        'value' => $opt['value'],
                        'label' => $opt['label'],
                        'desc' => $opt['desc'] ?? '',
                    ];
                }
            }
            if (empty($options)) {
                continue;
            }
            $questions[] = [
                'key' => preg_replace('/[^a-z0-9_]/i', '_', strtolower($q['key'])),
                'label' => $q['label'],
                'subtitle' => $q['subtitle'] ?? '',
                'options' => $options,
            ];
        }
        $data['questions'] = $questions;

        // Default product
        $data['default_product_id'] = !empty($data['default_product_id']) ? (int) $data['default_product_id'] : null;

        $page->update($data);

        return redirect()->route('admin.pages.quiz.edit')
            ->with('success', 'Página del quiz actualizada correctamente.');
    }

    /**
     * Decode a JSON hidden input coming from the admin form.
     * Returns null if the key is missing or invalid — in which case the caller
     * should fall back to the nested array form input.
     */
    private function decodeJsonInput(Request $request, string $key): ?array
    {
        if (! $request->filled($key)) {
            return null;
        }

        $decoded = json_decode($request->input($key), true);

        return is_array($decoded) ? $decoded : null;
    }
}

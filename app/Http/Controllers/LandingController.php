<?php

namespace App\Http\Controllers;

use App\Mail\LeadWelcome;
use App\Models\Lead;
use App\Models\Product;
use App\Models\QuizPageSetting;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __construct(
        private SeoService $seo,
    ) {}

    /**
     * Generic campaign landing page.
     */
    public function index(): View
    {
        $featuredProducts = Product::active()
            ->featured()
            ->where(function ($q) {
                $q->where('stock', '>', 0)
                  ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
            })
            ->with('category', 'variants')
            ->limit(3)
            ->get()
            ->filter(fn ($p) => $p->hasStock())
            ->values();

        return view('storefront.landing.index', compact('featuredProducts'));
    }

    /**
     * Quiz: "¿Qué lentes son para ti?"
     */
    public function quiz(): View
    {
        $quizPage = QuizPageSetting::getCurrent();
        $questions = $quizPage->getQuestionsOrDefault();

        return view('storefront.landing.quiz', compact('quizPage', 'questions'));
    }

    /**
     * Store quiz result as lead (AJAX).
     */
    public function quizResult(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'answers' => 'required|array',
        ]);

        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'source' => 'quiz',
            ],
        );

        if ($lead->wasRecentlyCreated) {
            Mail::to($lead->email)->send(new LeadWelcome($lead));
        }

        // Determine recommendation based on answers
        $recommendation = $this->getRecommendation($validated['answers']);

        return response()->json([
            'success' => true,
            'recommendation' => $recommendation,
        ]);
    }

    /**
     * Determine product recommendation from quiz answers.
     * Uses admin-configured rules, with fallback to default product.
     */
    private function getRecommendation(array $answers): array
    {
        $quizPage = QuizPageSetting::getCurrent();
        $product = null;
        $reason = null;

        // 1. Try admin-configured rules (first match wins)
        foreach ($quizPage->recommendation_rules ?? [] as $rule) {
            $field = $rule['condition_field'] ?? null;
            $value = $rule['condition_value'] ?? null;

            if ($field && $value && ($answers[$field] ?? null) === $value) {
                $product = Product::active()->find($rule['product_id'] ?? null);
                if ($product) {
                    $reason = $rule['reason'] ?? '';
                    break;
                }
            }
        }

        // 2. Fallback to default product configured in admin
        if (! $product && $quizPage->default_product_id) {
            $product = Product::active()->find($quizPage->default_product_id);
            $reason = $quizPage->default_reason ?: 'Este es nuestro modelo más popular y versátil.';
        }

        // 3. Final fallback: first featured product
        if (! $product) {
            $product = Product::active()->featured()->first() ?? Product::active()->first();
            $reason = $quizPage->default_reason ?: 'Este es nuestro modelo más popular y versátil.';
        }

        return [
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'product_price' => $product->price,
            'product_image' => $product->images[0] ?? null,
            'product_url' => route('products.show', $product->slug),
            'reason' => $reason,
        ];
    }
}

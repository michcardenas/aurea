@extends('layouts.app')

@section('body_class', 'bg-bg text-text')

@section('title', $quizPage->meta_title ?? '¿Qué lentes necesitas? — Quiz | Nuvion Glass')
@section('meta_description', $quizPage->meta_description ?? 'Responde algunas preguntas y descubre qué lentes con protección de luz azul son perfectos para ti. Quiz interactivo de Nuvion Glass.')

@section('content')

    <section class="min-h-[80vh] flex items-center py-12">
        <div class="w-full max-w-2xl mx-auto px-4 sm:px-6"
             x-data="quizApp()"
             x-cloak>

            {{-- Hero (only on first step) --}}
            <div class="text-center mb-8" x-show="step === 1">
                <h1 class="font-brand text-3xl md:text-4xl font-bold mb-3">{{ $quizPage->hero_title ?? '¿Qué lentes son para ti?' }}</h1>
                <p class="text-muted/60">{{ $quizPage->hero_subtitle ?? 'Responde unas preguntas rápidas y te recomendamos los mejores lentes para ti.' }}</p>
            </div>

            {{-- Progress bar --}}
            <div class="mb-8" x-show="step <= totalSteps">
                <div class="flex justify-between text-xs text-muted/50 mb-2">
                    <span>Pregunta <span x-text="step"></span> de <span x-text="totalSteps"></span></span>
                    <span x-text="Math.round((step / totalSteps) * 100) + '%'"></span>
                </div>
                <div class="h-1.5 bg-surface rounded-full overflow-hidden">
                    <div class="h-full bg-secondary rounded-full transition-all duration-500"
                         :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
                </div>
            </div>

            {{-- Dynamic questions --}}
            <template x-for="(question, qIdx) in questions" :key="question.key">
                <div x-show="step === qIdx + 1"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0">
                    <h2 class="font-brand text-2xl md:text-3xl font-bold mb-2" x-text="question.label"></h2>
                    <p class="text-muted/60 mb-8" x-text="question.subtitle" x-show="question.subtitle"></p>

                    <div class="grid gap-4"
                         :class="question.options.length === 2 ? 'grid-cols-1 sm:grid-cols-2' : (question.options.length <= 4 ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1')">
                        <template x-for="option in question.options" :key="option.value">
                            <button type="button" @click="answerQuestion(question.key, option.value)"
                                    class="group bg-surface border-2 rounded-2xl p-6 text-left transition-all hover:border-secondary"
                                    :class="answers[question.key] === option.value ? 'border-secondary' : 'border-border'">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                         :class="answers[question.key] === option.value ? 'border-secondary bg-secondary' : 'border-muted/30'">
                                        <svg x-show="answers[question.key] === option.value"
                                             class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold" x-text="option.label"></h3>
                                        <p class="text-sm text-muted/50 mt-1" x-text="option.desc" x-show="option.desc"></p>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Lead capture step (after all questions) --}}
            <div x-show="step === leadStep"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                    </div>
                    <h2 class="font-brand text-2xl md:text-3xl font-bold">¡Tenemos tu recomendación!</h2>
                    <p class="mt-2 text-muted/60">Ingresa tus datos para ver el lente perfecto para ti.</p>
                </div>

                <form @submit.prevent="submitQuiz()" class="space-y-4 max-w-sm mx-auto">
                    <div>
                        <label for="quiz-name" class="block text-sm font-medium text-muted/70 mb-1">Tu nombre</label>
                        <input type="text" id="quiz-name" x-model="form.name" required
                               class="w-full bg-surface border border-border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent placeholder-muted/30"
                               placeholder="Ej: María García">
                    </div>
                    <div>
                        <label for="quiz-email" class="block text-sm font-medium text-muted/70 mb-1">Tu email</label>
                        <input type="email" id="quiz-email" x-model="form.email" required
                               class="w-full bg-surface border border-border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent placeholder-muted/30"
                               placeholder="tu@email.com">
                    </div>
                    <button type="submit" :disabled="loading"
                            class="w-full bg-secondary hover:bg-secondary/90 text-white py-3.5 rounded-xl font-semibold transition-colors disabled:opacity-50">
                        <span x-show="!loading">Ver mi recomendación</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            Procesando...
                        </span>
                    </button>
                    <p x-show="error" x-text="error" class="text-danger text-sm text-center"></p>
                </form>
            </div>

            {{-- Result step --}}
            <div x-show="step === resultStep"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="text-center mb-8">
                    <span class="inline-block text-secondary text-sm font-medium tracking-wider uppercase mb-2">Tu resultado</span>
                    <h2 class="font-brand text-2xl md:text-3xl font-bold">{{ $quizPage->result_title ?? 'Te recomendamos' }}</h2>
                </div>

                <div class="bg-surface rounded-2xl border border-border overflow-hidden max-w-md mx-auto">
                    <div class="aspect-[4/3] bg-bg flex items-center justify-center">
                        <template x-if="result.product_image">
                            <img :src="'/storage/' + result.product_image" :alt="result.product_name" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!result.product_image">
                            <span class="text-muted/30 text-sm" x-text="result.product_name"></span>
                        </template>
                    </div>
                    <div class="p-6">
                        <h3 class="font-brand text-xl font-bold" x-text="result.product_name"></h3>
                        <p class="mt-2 text-muted/60 text-sm" x-text="result.reason"></p>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-secondary">$<span x-text="parseFloat(result.product_price).toFixed(2)"></span></span>
                        </div>
                        <a :href="result.product_url"
                           class="mt-6 block w-full bg-secondary hover:bg-secondary/90 text-white text-center py-3.5 rounded-xl font-semibold transition-colors">
                            {{ $quizPage->result_cta_text ?? 'Ver este producto' }}
                        </a>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="{{ route('products.index') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium">
                        O ver todo el catálogo →
                    </a>
                </div>
            </div>

            {{-- Back button --}}
            <div class="mt-8" x-show="step > 1 && step <= leadStep">
                <button @click="prevStep()" class="text-sm text-muted/50 hover:text-muted/80 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                    Anterior
                </button>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
<script>
    function quizApp() {
        return {
            questions: @json($questions),
            step: 1,
            loading: false,
            error: '',
            answers: {},
            form: { name: '', email: '' },
            result: {},

            get totalSteps() { return this.questions.length; },
            get leadStep() { return this.questions.length + 1; },
            get resultStep() { return this.questions.length + 2; },

            init() {
                // Initialize answers with empty values for each question key
                this.questions.forEach(q => {
                    this.answers[q.key] = '';
                });
            },

            answerQuestion(key, value) {
                this.answers[key] = value;
                this.nextStep();
            },

            nextStep() {
                if (this.step < this.leadStep) {
                    this.step++;
                }
            },
            prevStep() {
                if (this.step > 1) this.step--;
            },

            async submitQuiz() {
                this.loading = true;
                this.error = '';
                try {
                    const response = await fetch('{{ route("landing.quiz.result") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name: this.form.name,
                            email: this.form.email,
                            answers: this.answers,
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.result = data.recommendation;
                        this.step = this.resultStep;
                    } else {
                        this.error = data.message || 'Ocurrió un error. Intenta de nuevo.';
                    }
                } catch (e) {
                    this.error = 'Error de conexión. Verifica tu internet e intenta de nuevo.';
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
@endpush

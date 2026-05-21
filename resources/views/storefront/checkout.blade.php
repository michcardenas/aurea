@extends('layouts.app')

@section('title', 'Checkout | Nuvion Glass')

@push('head')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')

    {{-- Page header --}}
    <section class="relative bg-bg overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-12">
            <nav class="flex items-center gap-2 text-sm text-muted/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
                <a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Carrito</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
                <span class="text-white/80">Checkout</span>
            </nav>
            <h1 class="font-brand text-3xl md:text-4xl font-bold text-white">Finalizar compra</h1>
        </div>
    </section>

    {{-- Checkout form --}}
    <section class="py-12 md:py-16 bg-bg-light">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8"
             x-data="checkoutForm()" x-cloak>

            <form @submit.prevent="handleSubmit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

                {{-- LEFT COLUMN: Customer data + Payment --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Shipping Info --}}
                    <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
                        <h2 class="font-brand text-xl font-semibold text-text-dark mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Datos de envío
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Name --}}
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-medium text-text-muted mb-1.5">Nombre completo *</label>
                                <input type="text" id="name" x-model="form.name"
                                       class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors"
                                       placeholder="Tu nombre completo">
                                <p x-show="errors.name" x-text="errors.name" class="text-danger text-xs mt-1"></p>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-text-muted mb-1.5">Email *</label>
                                <input type="email" id="email" x-model="form.email"
                                       class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors"
                                       placeholder="tu@email.com">
                                <p x-show="errors.email" x-text="errors.email" class="text-danger text-xs mt-1"></p>
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-text-muted mb-1.5">Teléfono</label>
                                <input type="tel" id="phone" x-model="form.phone"
                                       class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors"
                                       placeholder="10 dígitos">
                            </div>

                            {{-- Address --}}
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-text-muted mb-1.5">Dirección *</label>
                                <input type="text" id="address" x-model="form.address"
                                       class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors"
                                       placeholder="Calle, número, colonia">
                                <p x-show="errors.address" x-text="errors.address" class="text-danger text-xs mt-1"></p>
                            </div>

                            {{-- State --}}
                            <div>
                                <label for="state" class="block text-sm font-medium text-text-muted mb-1.5">Estado *</label>
                                <select id="state" x-model="form.state"
                                        class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors"
                                        :class="!form.state ? 'text-text-muted/40' : ''">
                                    <option value="" disabled>Selecciona un estado</option>
                                    <option value="Aguascalientes">Aguascalientes</option>
                                    <option value="Baja California">Baja California</option>
                                    <option value="Baja California Sur">Baja California Sur</option>
                                    <option value="Campeche">Campeche</option>
                                    <option value="Chiapas">Chiapas</option>
                                    <option value="Chihuahua">Chihuahua</option>
                                    <option value="Coahuila">Coahuila</option>
                                    <option value="Colima">Colima</option>
                                    <option value="Durango">Durango</option>
                                    <option value="Estado de México">Estado de México</option>
                                    <option value="Guanajuato">Guanajuato</option>
                                    <option value="Guerrero">Guerrero</option>
                                    <option value="Hidalgo">Hidalgo</option>
                                    <option value="Jalisco">Jalisco</option>
                                    <option value="Michoacán">Michoacán</option>
                                    <option value="Morelos">Morelos</option>
                                    <option value="Nayarit">Nayarit</option>
                                    <option value="Nuevo León">Nuevo León</option>
                                    <option value="Oaxaca">Oaxaca</option>
                                    <option value="Puebla">Puebla</option>
                                    <option value="Querétaro">Querétaro</option>
                                    <option value="Quintana Roo">Quintana Roo</option>
                                    <option value="San Luis Potosí">San Luis Potosí</option>
                                    <option value="Sinaloa">Sinaloa</option>
                                    <option value="Sonora">Sonora</option>
                                    <option value="Tabasco">Tabasco</option>
                                    <option value="Tamaulipas">Tamaulipas</option>
                                    <option value="Tlaxcala">Tlaxcala</option>
                                    <option value="Veracruz">Veracruz</option>
                                    <option value="Yucatán">Yucatán</option>
                                    <option value="Zacatecas">Zacatecas</option>
                                </select>
                                <p x-show="errors.state" x-text="errors.state" class="text-danger text-xs mt-1"></p>
                            </div>

                            {{-- Zip code --}}
                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-text-muted mb-1.5">Código postal *</label>
                                <input type="text" id="zip_code" x-model="form.zip_code"
                                       class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors"
                                       placeholder="00000" maxlength="10">
                                <p x-show="errors.zip_code" x-text="errors.zip_code" class="text-danger text-xs mt-1"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
                        <h2 class="font-brand text-xl font-semibold text-text-dark mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                            Método de pago
                        </h2>

                        <div class="space-y-3">
                            {{-- Card --}}
                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all duration-200"
                                   :class="form.payment_method === 'card' ? 'border-secondary bg-secondary/5 shadow-sm' : 'border-border-light hover:border-secondary/30'">
                                <input type="radio" x-model="form.payment_method" value="card" class="text-secondary focus:ring-secondary">
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-text-dark">Tarjeta de crédito / débito</span>
                                    <p class="text-xs text-text-muted mt-0.5">Pago seguro con Stripe</p>
                                </div>
                                <svg class="w-8 h-5 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                            </label>

                            {{-- Transfer --}}
                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all duration-200"
                                   :class="form.payment_method === 'transfer' ? 'border-secondary bg-secondary/5 shadow-sm' : 'border-border-light hover:border-secondary/30'">
                                <input type="radio" x-model="form.payment_method" value="transfer" class="text-secondary focus:ring-secondary">
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-text-dark">Transferencia bancaria</span>
                                    <p class="text-xs text-text-muted mt-0.5">Recibirás los datos bancarios por correo</p>
                                </div>
                                <svg class="w-5 h-5 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21"/></svg>
                            </label>

                        </div>

                        {{-- Stripe Card Element --}}
                        <div x-show="form.payment_method === 'card'" x-transition class="mt-6">
                            <label class="block text-sm font-medium text-text-muted mb-2">Datos de la tarjeta</label>
                            <div id="card-element" class="border border-border-light rounded-xl px-4 py-3.5 bg-white focus-within:border-secondary/50 focus-within:ring-2 focus-within:ring-secondary/10 transition-colors"></div>
                            <p x-show="cardError" x-text="cardError" class="text-danger text-xs mt-2"></p>
                            <div class="mt-3 flex items-center gap-2 text-xs text-text-muted/60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                Pago seguro encriptado con Stripe
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
                        <label for="notes" class="block text-sm font-medium text-text-muted mb-1.5">Notas adicionales (opcional)</label>
                        <textarea id="notes" x-model="form.notes" rows="3"
                                  class="w-full border border-border-light rounded-xl px-4 py-3 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors resize-none"
                                  placeholder="Instrucciones especiales de entrega, referencias..."></textarea>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm sticky top-24">
                        <h2 class="font-brand text-xl font-semibold text-text-dark mb-6">Resumen del pedido</h2>

                        {{-- 2x1 hint: odd number of eligible lenses --}}
                        @if($eligibleLensCount > 0 && $eligibleLensCount % 2 !== 0)
                        <div class="mb-4 flex items-center gap-2 px-2.5 py-2 rounded-lg bg-blue-50/70 border border-blue-100">
                            <svg class="w-3.5 h-3.5 text-secondary/70 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            <p class="text-[11px] text-text-muted leading-tight">Tus lentes son 2×1 — <a href="{{ route('products.index') }}" class="text-secondary hover:underline">escoge otro</a> y va gratis</p>
                        </div>
                        @endif

                        {{-- 2x1 applied --}}
                        @if($discount2x1 > 0)
                        <div class="mb-4 flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-green-50/70 border border-green-100">
                            <svg class="w-3 h-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                            <p class="text-[11px] text-green-700 leading-tight">2×1 aplicado — {{ implode(', ', $freeItems) }} gratis</p>
                        </div>
                        @endif

                        {{-- Cart items --}}
                        <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                            @foreach($items as $item)
                            <div class="flex gap-3 pb-4 border-b border-border-light last:border-0 last:pb-0">
                                @php $img = $item['product']->images[0] ?? null; @endphp
                                <div class="w-16 h-16 rounded-lg bg-bg-light border border-border-light flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    @if($img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-contain p-1">
                                    @else
                                    <span class="text-text-muted/30 text-xs">{{ Str::limit($item['product']->name, 10) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-text-dark truncate">{{ $item['product']->name }}</p>
                                    @if($item['variant'])
                                        @if($item['variant']->value)
                                        <p class="text-xs text-text-muted/60">{{ $item['variant']->value }}</p>
                                        @endif
                                        @if($item['variant']->graduation)
                                        @php
                                            $gradTypeLabels = ['miopia' => 'Miopía', 'lectura' => 'Lectura', 'sin_graduacion' => 'Sin graduación'];
                                            $gradTypeLabel = $gradTypeLabels[$item['variant']->graduation_type] ?? null;
                                        @endphp
                                        <p class="text-xs text-text-muted/60">
                                            Graduación: <span class="font-medium text-text-dark">{{ $item['variant']->graduation }}</span>@if($gradTypeLabel) <span class="text-text-muted/50">({{ $gradTypeLabel }})</span>@endif
                                        </p>
                                        @endif
                                    @endif
                                    <p class="text-xs text-text-muted/60">Cant: {{ $item['qty'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-text-dark">${{ number_format($item['total'], 2) }}</p>
                            </div>
                            @endforeach
                        </div>

                        {{-- Coupon code --}}
                        <div class="mt-6 pt-4 border-t border-border-light">
                            <template x-if="!coupon.code">
                                <div>
                                    <label class="block text-sm font-medium text-text-muted mb-2">Código de descuento</label>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="couponInput" placeholder="Ej: NUVION20"
                                               style="text-transform: uppercase"
                                               @keydown.enter.prevent="applyCoupon()"
                                               class="flex-1 border border-border-light rounded-lg px-3 py-2 text-sm text-text-dark placeholder-text-muted/40 focus:outline-none focus:border-secondary/50 focus:ring-2 focus:ring-secondary/10 transition-colors">
                                        <button type="button" @click="applyCoupon()" :disabled="applyingCoupon || !couponInput.trim()"
                                                class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span x-show="!applyingCoupon">Aplicar</span>
                                            <span x-show="applyingCoupon" class="flex items-center">
                                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                    <p x-show="couponError" x-text="couponError" class="text-danger text-xs mt-1.5"></p>
                                </div>
                            </template>
                            <template x-if="coupon.code">
                                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg px-3 py-2.5">
                                    <div>
                                        <span class="text-sm font-mono font-semibold text-green-800" x-text="coupon.code"></span>
                                        <p class="text-xs text-green-600" x-text="coupon.description"></p>
                                    </div>
                                    <button type="button" @click="removeCoupon()" class="text-green-600 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- Totals --}}
                        <div class="mt-4 pt-4 border-t border-border-light space-y-2.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-text-muted">Subtotal</span>
                                <span class="text-text-dark">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount2x1 > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Descuento 2×1</span>
                                <span>-${{ number_format($discount2x1, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-text-muted">Envío</span>
                                <span class="text-text-dark"
                                      x-text="currentShipping > 0 ? '$' + Number(currentShipping).toFixed(2) : 'Gratis'"></span>
                            </div>
                            <template x-if="coupon.discount_amount > 0">
                                <div class="flex justify-between text-green-600">
                                    <span>Cupón</span>
                                    <span x-text="'-$' + Number(coupon.discount_amount).toFixed(2)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between font-bold text-lg pt-3 border-t border-border-light">
                                <span class="text-text-dark">Total</span>
                                <span class="text-primary" x-text="'$' + Number(currentTotal).toFixed(2)"></span>
                            </div>
                        </div>

                        {{-- Submit button --}}
                        <button type="submit" :disabled="processing"
                                class="mt-6 w-full bg-secondary hover:bg-secondary/90 text-white py-3.5 rounded-xl font-semibold text-base
                                       transition-all duration-200 shadow-md shadow-secondary/20
                                       hover:-translate-y-0.5 hover:shadow-lg hover:shadow-secondary/30
                                       disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-md">
                            <span x-show="!processing" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                <span x-text="'Confirmar pedido — $' + Number(currentTotal).toFixed(2)"></span>
                            </span>
                            <span x-show="processing" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </button>

                        {{-- General error --}}
                        <p x-show="generalError" x-text="generalError" class="text-danger text-sm mt-3 text-center"></p>

                        {{-- Benefits --}}
                        @php
                            $benefits = !empty($productBenefits) ? $productBenefits : [
                                'Pago seguro',
                                'Envío gratis +$' . number_format($freeThreshold ?? 999, 0),
                            ];
                        @endphp
                        <div class="mt-5 pt-4 border-t border-border-light grid grid-cols-2 gap-2 text-xs text-text-muted/70">
                            @foreach($benefits as $benefit)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                {{ $benefit }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@push('scripts')
<script>
function checkoutForm() {
    return {
        form: {
            name: '',
            email: '',
            phone: '',
            address: '',
            state: '',
            zip_code: '',
            payment_method: 'card',
            stripe_payment_intent_id: '',
            notes: '',
        },
        errors: {},
        cardError: '',
        generalError: '',
        processing: false,
        stripe: null,
        cardElement: null,
        cardMounted: false,
        couponInput: '',
        couponError: '',
        applyingCoupon: false,
        coupon: {
            code: {!! json_encode($appliedCoupon) !!},
            description: {!! json_encode($discount['amount'] > 0 ? ($discount['code'] ? 'Descuento aplicado' : '') : '') !!},
            discount_amount: {{ $discount['amount'] ?? 0 }},
        },
        currentShipping: {{ $shipping }},
        currentTotal: {{ $total }},

        init() {
            this.stripe = Stripe('{{ config("services.stripe.key") }}');
            const elements = this.stripe.elements();
            this.cardElement = elements.create('card', {
                style: {
                    base: {
                        fontFamily: '"IBM Plex Sans", sans-serif',
                        fontSize: '16px',
                        color: '#1A1A2E',
                        '::placeholder': { color: '#9CA3AF' },
                    },
                    invalid: { color: '#EF4444' },
                },
                hidePostalCode: true,
            });

            this.$watch('form.payment_method', (val) => {
                if (val === 'card') {
                    this.$nextTick(() => this.mountCard());
                }
            });

            // Recalculate shipping when state changes
            this.$watch('form.state', (val) => {
                if (val) this.recalculateShipping();
            });

            this.$nextTick(() => {
                if (this.form.payment_method === 'card') {
                    this.mountCard();
                }
            });
        },

        async recalculateShipping() {
            try {
                const res = await fetch('{{ route("checkout.calculateShipping") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ state: this.form.state }),
                });
                const data = await res.json();
                if (res.ok) {
                    this.currentShipping = data.shipping;
                    this.currentTotal = data.total;
                }
            } catch (e) {
                console.error('Error recalculating shipping:', e);
            }
        },

        mountCard() {
            const el = document.getElementById('card-element');
            if (el && !this.cardMounted) {
                this.cardElement.mount('#card-element');
                this.cardMounted = true;
                this.cardElement.on('change', (event) => {
                    this.cardError = event.error ? event.error.message : '';
                });
            }
        },

        async applyCoupon() {
            this.couponError = '';
            if (!this.couponInput.trim()) return;
            this.applyingCoupon = true;

            try {
                const res = await fetch('{{ route("checkout.applyCoupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ code: this.couponInput.trim() }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.couponError = data.message || 'Código no válido.';
                    this.applyingCoupon = false;
                    return;
                }

                this.coupon = {
                    code: data.code,
                    description: data.description,
                    discount_amount: data.discount_amount,
                };
                if (typeof data.shipping !== 'undefined') {
                    this.currentShipping = data.shipping;
                }
                this.currentTotal = data.new_total;
                this.couponInput = '';
            } catch (e) {
                this.couponError = 'Error al aplicar el código.';
            }
            this.applyingCoupon = false;
        },

        async removeCoupon() {
            try {
                const res = await fetch('{{ route("checkout.removeCoupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({}),
                });

                const data = await res.json();
                if (res.ok) {
                    this.coupon = { code: null, description: '', discount_amount: 0 };
                    if (typeof data.shipping !== 'undefined') {
                        this.currentShipping = data.shipping;
                    }
                    this.currentTotal = data.new_total;
                }
            } catch (e) {
                console.error('Error removing coupon:', e);
            }
        },

        async handleSubmit() {
            this.processing = true;
            this.errors = {};
            this.cardError = '';
            this.generalError = '';

            if (!this.validate()) {
                this.processing = false;
                return;
            }

            if (this.form.payment_method === 'card') {
                await this.handleCardPayment();
            } else {
                await this.submitOrder();
            }
        },

        validate() {
            const required = { name: 'Nombre', email: 'Email', address: 'Dirección', state: 'Estado', zip_code: 'Código postal' };
            for (const [field, label] of Object.entries(required)) {
                if (!this.form[field]?.trim()) {
                    this.errors[field] = `${label} es requerido.`;
                }
            }
            if (this.form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                this.errors.email = 'Email inválido.';
            }
            if (Object.keys(this.errors).length > 0) {
                const firstError = document.querySelector('[x-show="errors.' + Object.keys(this.errors)[0] + '"]');
                if (firstError) firstError.closest('div')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return Object.keys(this.errors).length === 0;
        },

        async handleCardPayment() {
            try {
                const res = await fetch('{{ route("checkout.createPaymentIntent") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ state: this.form.state }),
                });

                if (!res.ok) {
                    const data = await res.json();
                    this.generalError = data.message || 'Error al preparar el pago.';
                    this.processing = false;
                    return;
                }

                const { clientSecret } = await res.json();

                const { error, paymentIntent } = await this.stripe.confirmCardPayment(
                    clientSecret,
                    { payment_method: { card: this.cardElement } }
                );

                if (error) {
                    this.cardError = error.message;
                    this.processing = false;
                    return;
                }

                if (paymentIntent.status === 'succeeded' || paymentIntent.status === 'processing') {
                    this.form.stripe_payment_intent_id = paymentIntent.id;
                    await this.submitOrder();
                } else {
                    this.generalError = 'El pago no fue completado. Intenta de nuevo.';
                    this.processing = false;
                }
            } catch (e) {
                this.generalError = 'Error inesperado. Intenta de nuevo.';
                this.processing = false;
            }
        },

        async submitOrder() {
            try {
                const res = await fetch('{{ route("checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.form),
                });

                if (res.status === 422) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                    this.generalError = data.message || '';
                    this.processing = false;
                    return;
                }

                const data = await res.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                this.generalError = 'Error al crear el pedido.';
                this.processing = false;
            } catch (e) {
                this.generalError = 'Error al crear el pedido. Intenta de nuevo.';
                this.processing = false;
            }
        },
    };
}
</script>
@endpush

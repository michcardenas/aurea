<footer class="bg-black border-t border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div class="md:col-span-1">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Nuvion Glass" style="height:56px;width:auto;">
                </a>
                <p class="mt-4 text-sm text-muted/70 leading-relaxed">
                    Protege tus ojos de la luz azul. Diseño moderno, tecnología que cuida tu visión.
                </p>
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="font-brand text-sm font-semibold text-white uppercase tracking-wider mb-4">Tienda</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('products.index') }}" class="text-sm text-muted/70 hover:text-white transition-colors">Todos los lentes</a></li>
                    <li><a href="{{ route('blue-light') }}" class="text-sm text-muted/70 hover:text-white transition-colors">¿Qué es la luz azul?</a></li>
                </ul>
            </div>

            {{-- Info --}}
            <div>
                <h4 class="font-brand text-sm font-semibold text-white uppercase tracking-wider mb-4">Información</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-muted/70 hover:text-white transition-colors">Blog</a></li>
                    <li><a href="{{ route('shipping-returns') }}" class="text-sm text-muted/70 hover:text-white transition-colors">Envíos y devoluciones</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm text-muted/70 hover:text-white transition-colors">Contacto</a></li>
                </ul>
            </div>

            {{-- Newsletter / Lead capture --}}
            <div>
                <h4 class="font-brand text-sm font-semibold text-white uppercase tracking-wider mb-4">Mantente informado</h4>
                <p class="text-sm text-muted/70 mb-3">Recibe consejos para cuidar tu visión.</p>
                <form action="{{ route('leads.store') }}" method="POST" class="flex">
                    @csrf
                    <input type="hidden" name="source" value="footer">
                    <input type="email" name="email" placeholder="Tu email" required
                           class="flex-1 bg-surface border border-border rounded-l-lg px-4 py-2 text-sm text-white placeholder-muted/50 focus:outline-none focus:border-secondary">
                    <button type="submit"
                            class="bg-secondary hover:bg-secondary/90 text-white px-4 py-2 rounded-r-lg text-sm font-medium transition-colors">
                        Enviar
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-border flex flex-col sm:flex-row justify-between items-center">
            <p class="text-xs text-muted/50">&copy; {{ date('Y') }} Nuvion Glass. Todos los derechos reservados.</p>
            <p class="text-xs text-muted/50 mt-2 sm:mt-0">Desarrollado por MyTech Solutions</p>
        </div>
    </div>
</footer>

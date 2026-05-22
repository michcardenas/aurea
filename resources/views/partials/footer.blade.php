<footer style="background:#2E2A26;border-top:1px solid rgba(232,204,146,0.15);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            {{-- Brand --}}
            <div class="md:col-span-1">
                <a href="{{ route('home') }}" style="display:inline-block;">
                    <img src="{{ asset('img/brand/logo-transparent.png') }}" alt="Belleza Áurea" style="height:80px;width:auto;filter:brightness(1.05);">
                </a>
                <p class="mt-5 text-sm" style="color:rgba(247,243,237,0.65);line-height:1.7;max-width:280px;">
                    Belleza natural, elegante y atemporal. Skincare, fragancias y rituales premium con ingredientes botánicos.
                </p>
            </div>

            {{-- Tienda --}}
            <div>
                <h4 class="text-sm font-semibold uppercase mb-5" style="color:#D9B56D;letter-spacing:0.12em;font-family:'Playfair Display',serif;">Tienda</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('products.index') }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Todos los productos</a></li>
                    <li><a href="{{ route('products.index', ['type' => 'sin_graduacion']) }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Skincare</a></li>
                    <li><a href="{{ route('products.index', ['type' => 'toallitas']) }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Sets &amp; Rituales</a></li>
                    <li><a href="{{ route('landing.quiz') }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Quiz de piel</a></li>
                </ul>
            </div>

            {{-- Información --}}
            <div>
                <h4 class="text-sm font-semibold uppercase mb-5" style="color:#D9B56D;letter-spacing:0.12em;font-family:'Playfair Display',serif;">Información</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('blog.index') }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Blog &amp; rituales</a></li>
                    <li><a href="{{ route('shipping-returns') }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Envíos y devoluciones</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm transition-colors" style="color:rgba(247,243,237,0.7);" onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='rgba(247,243,237,0.7)'">Contacto</a></li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div>
                <h4 class="text-sm font-semibold uppercase mb-5" style="color:#D9B56D;letter-spacing:0.12em;font-family:'Playfair Display',serif;">Mantente al día</h4>
                <p class="text-sm mb-4" style="color:rgba(247,243,237,0.65);line-height:1.6;">Recibe rituales, lanzamientos y un 10% en tu primera compra.</p>
                <form action="{{ route('leads.store') }}" method="POST" class="flex">
                    @csrf
                    <input type="hidden" name="source" value="footer">
                    <input type="email" name="email" placeholder="tu@correo.com" required
                           class="flex-1 rounded-l-lg px-4 py-2.5 text-sm focus:outline-none"
                           style="background:rgba(247,243,237,0.08);border:1px solid rgba(232,204,146,0.25);color:#F7F3ED;">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-r-lg text-sm font-semibold transition-colors"
                            style="background:#D9B56D;color:#2E2A26;"
                            onmouseover="this.style.background='#E8CC92'"
                            onmouseout="this.style.background='#D9B56D'">
                        Suscribirme
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-14 pt-8 flex flex-col sm:flex-row justify-between items-center gap-2" style="border-top:1px solid rgba(232,204,146,0.12);">
            <p class="text-xs" style="color:rgba(247,243,237,0.45);">&copy; {{ date('Y') }} Belleza Áurea. Todos los derechos reservados.</p>
            <p class="text-xs" style="color:rgba(247,243,237,0.45);">Belleza natural · elegante · atemporal</p>
        </div>
    </div>
</footer>

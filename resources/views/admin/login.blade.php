<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login | Nuvion Glass</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;600&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-bg text-text font-body min-h-screen flex items-center justify-center antialiased">
    <div class="w-full max-w-md px-6">
        <div class="text-center mb-8">
            <img src="{{ asset('img/isotipo.png') }}" alt="Nuvion Glass" class="h-14 w-14 object-contain mx-auto mb-3">
            <div>
                <span class="font-brand text-3xl text-secondary">nuvion</span>
                <span class="font-brand text-sm text-muted uppercase tracking-[0.3em] ml-1">glass</span>
            </div>
            <p class="mt-2 text-sm text-muted/50">Panel de administración</p>
        </div>

        <div class="bg-surface border border-border rounded-2xl p-8">
            <h1 class="font-brand text-xl font-semibold text-center">Iniciar sesión</h1>

            @if($errors->any())
                <div class="mt-4 bg-danger/10 border border-danger/30 text-danger px-4 py-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-muted/70 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-bg border border-border rounded-lg px-4 py-2.5 text-white placeholder-muted/30 focus:outline-none focus:border-secondary transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-muted/70 mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-bg border border-border rounded-lg px-4 py-2.5 text-white placeholder-muted/30 focus:outline-none focus:border-secondary transition-colors">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 rounded border-border bg-bg text-secondary focus:ring-secondary">
                    <label for="remember" class="ml-2 text-sm text-muted/70">Recordarme</label>
                </div>

                <button type="submit"
                        class="w-full bg-secondary hover:bg-secondary/90 text-white py-2.5 rounded-lg font-medium transition-colors">
                    Entrar
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-muted/30">&copy; {{ date('Y') }} Nuvion Glass</p>
    </div>
</body>
</html>

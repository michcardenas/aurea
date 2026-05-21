<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nuvion Glass')</title>
</head>
<body style="margin:0;padding:0;background-color:#F4F6F9;font-family:Arial,Helvetica,sans-serif;-webkit-font-smoothing:antialiased;">
    {{-- Wrapper --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F6F9;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                {{-- Header --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
                    <tr>
                        <td align="center" style="padding:24px 0;">
                            <a href="{{ url('/') }}" style="text-decoration:none;">
                                <img src="{{ asset('img/logo.png') }}" alt="Nuvion Glass" width="160" style="width:160px;height:auto;display:block;margin:0 auto;">
                            </a>
                        </td>
                    </tr>
                </table>

                {{-- Content card --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                    {{-- Accent bar --}}
                    <tr>
                        <td style="height:4px;background:linear-gradient(90deg,#002F6D,#3A8DDE);font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:40px 32px;">
                            @yield('content')
                        </td>
                    </tr>
                </table>

                {{-- Footer --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
                    <tr>
                        <td align="center" style="padding:32px 0 16px;">
                            <p style="margin:0;font-size:13px;color:#4B5563;">
                                <a href="{{ route('products.index') }}" style="color:#3A8DDE;text-decoration:none;">Ver catálogo</a>
                                &nbsp;&middot;&nbsp;
                                <a href="{{ route('blog.index') }}" style="color:#3A8DDE;text-decoration:none;">Blog</a>
                                &nbsp;&middot;&nbsp;
                                <a href="{{ route('blue-light') }}" style="color:#3A8DDE;text-decoration:none;">Luz azul</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:0 0 32px;">
                            <p style="margin:0;font-size:12px;color:#9CA3AF;">
                                &copy; {{ date('Y') }} Nuvion Glass. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>

@extends('layouts.email')

@section('title', 'Bienvenido a nuvion glass')

@section('content')
    {{-- Heading --}}
    <h1 style="margin:0 0 8px;font-size:24px;font-weight:700;color:#1A1A2E;">
        ¡Bienvenido{{ $lead->name ? ', ' . $lead->name : '' }}!
    </h1>
    <p style="margin:0 0 24px;font-size:15px;color:#4B5563;line-height:1.6;">
        Gracias por suscribirte a nuvion glass. Ahora recibirás consejos para proteger tu visión y ofertas exclusivas.
    </p>

    {{-- Benefits --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F6F9;border-radius:8px;margin-bottom:32px;">
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 16px;font-size:14px;font-weight:700;color:#002F6D;text-transform:uppercase;letter-spacing:0.5px;">
                    ¿Sabías que...?
                </p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="24" valign="top" style="padding:4px 8px 12px 0;font-size:16px;color:#3A8DDE;">&#10003;</td>
                        <td style="padding:4px 0 12px;font-size:14px;color:#4B5563;line-height:1.5;">
                            Pasamos en promedio <strong>7+ horas al día</strong> frente a pantallas
                        </td>
                    </tr>
                    <tr>
                        <td width="24" valign="top" style="padding:4px 8px 12px 0;font-size:16px;color:#3A8DDE;">&#10003;</td>
                        <td style="padding:4px 0 12px;font-size:14px;color:#4B5563;line-height:1.5;">
                            La luz azul causa <strong>fatiga visual, dolores de cabeza y problemas de sueño</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="24" valign="top" style="padding:4px 8px 0;font-size:16px;color:#3A8DDE;">&#10003;</td>
                        <td style="padding:4px 0 0;font-size:14px;color:#4B5563;line-height:1.5;">
                            Los lentes nuvion filtran <strong>30-50% de la luz azul dañina</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- CTA --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
        <tr>
            <td align="center" style="padding:8px 0;">
                <a href="{{ route('products.index') }}"
                   style="display:inline-block;background-color:#3A8DDE;color:#FFFFFF;font-size:15px;font-weight:600;text-decoration:none;padding:14px 32px;border-radius:8px;">
                    Ver catálogo de lentes
                </a>
            </td>
        </tr>
    </table>

    {{-- Secondary CTA --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:0 0 8px;">
                <a href="{{ route('landing.quiz') }}"
                   style="display:inline-block;color:#3A8DDE;font-size:14px;font-weight:600;text-decoration:none;padding:10px 24px;border:1px solid #3A8DDE;border-radius:8px;">
                    ¿Qué lentes necesito? — Hacer quiz
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:24px 0 0;font-size:13px;color:#9CA3AF;text-align:center;">
        ¿Tienes preguntas? Responde a este correo y te ayudaremos.
    </p>
@endsection

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChatSoporteController extends Controller
{
    /**
     * Recibe el mensaje del chatbox y lo envía por correo al administrador.
     * Ruta: POST /chat/soporte  (route name: chat.soporte)
     */
    public function enviar(Request $request)
    {
        $request->validate([
            'asunto'  => 'required|string|max:100',
            'mensaje' => 'required|string|max:800',
        ]);

        $datos = [
            'usuario_nombre' => $request->input('usuario_nombre', 'Invitado'),
            'usuario_email'  => $request->input('usuario_email',  'sin-correo'),
            'asunto'         => $request->input('asunto'),
            'mensaje'        => $request->input('mensaje'),
            'enviado_en'     => now()->format('d/m/Y H:i'),
        ];

        // Correo del administrador principal — ajusta en .env como ADMIN_EMAIL
        $adminEmail = config('ubicatec.admin_email', env('ADMIN_EMAIL', 'admin@ubicatec.mx'));

        Mail::send([], [], function ($mail) use ($datos, $adminEmail) {
            $mail->to($adminEmail)
                 ->subject("[UbicaTEC Soporte] {$datos['asunto']}")
                 ->html("
                    <div style='font-family:sans-serif;max-width:560px;margin:auto;border:1px solid #e2e5ea;border-radius:8px;overflow:hidden'>
                        <div style='background:#01696f;color:#fff;padding:20px 24px'>
                            <h2 style='margin:0;font-size:1rem'>📬 Nueva solicitud de soporte — UbicaTEC</h2>
                        </div>
                        <div style='padding:20px 24px;background:#f7f8fa'>
                            <table style='width:100%;border-collapse:collapse;font-size:.88rem'>
                                <tr><td style='padding:6px 0;color:#6b7280;width:140px'>Usuario:</td>
                                    <td style='padding:6px 0;font-weight:600'>{$datos['usuario_nombre']}</td></tr>
                                <tr><td style='padding:6px 0;color:#6b7280'>Correo:</td>
                                    <td style='padding:6px 0'>{$datos['usuario_email']}</td></tr>
                                <tr><td style='padding:6px 0;color:#6b7280'>Asunto:</td>
                                    <td style='padding:6px 0;font-weight:600'>{$datos['asunto']}</td></tr>
                                <tr><td style='padding:6px 0;color:#6b7280'>Fecha:</td>
                                    <td style='padding:6px 0'>{$datos['enviado_en']}</td></tr>
                            </table>
                            <hr style='border:none;border-top:1px solid #e2e5ea;margin:16px 0'>
                            <p style='color:#374151;font-size:.9rem;line-height:1.6'>{$datos['mensaje']}</p>
                        </div>
                        <div style='padding:12px 24px;background:#fff;text-align:right'>
                            <small style='color:#9ca3af'>Enviado desde el chatbox de UbicaTEC</small>
                        </div>
                    </div>
                ");
        });

        return response()->json(['success' => true]);
    }
}
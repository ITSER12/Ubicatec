<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;
use App\Mail\CodigoAcceso;

class LoginController extends Controller
{
    public function mostrarLogin()
    {
        return view('cpanel.login');
    }

    public function validarLogin(Request $request)
    {
        $usuario = DB::table('usuarios')
            ->where('no_control', $request->no_control)
            ->where('password', $request->password)
            ->first();

        if (!$usuario) {
            return back()->with('error', 'Número de control o contraseña incorrectos.');
        }

        $codigo = rand(100000, 999999);

        Session::put('2fa_codigo',  $codigo);
        Session::put('2fa_usuario', $usuario->no_control);
        Session::put('2fa_expira',  now()->addMinutes(5));

        try {
            Mail::to($usuario->correo)
                ->send(new CodigoAcceso($codigo, $usuario->nombre));
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo enviar el código. Verifica tu correo.');
        }

        return redirect()->route('verificar.codigo');
    }

    public function mostrarVerificarCodigo()
    {
        return view('cpanel.verificar');
    }

    public function verificarCodigo(Request $request)
    {
        if (!Session::has('2fa_expira') || now()->gt(Session::get('2fa_expira'))) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Código expirado. Inicia sesión de nuevo.');
        }

        if ((string) $request->codigo !== (string) Session::get('2fa_codigo')) {
            return back()->with('error', 'Código incorrecto. Intenta de nuevo.');
        }

        $usuario = Usuario::where('no_control', Session::get('2fa_usuario'))->first();

        Session::put('usuario', $usuario);
        Session::forget(['2fa_codigo', '2fa_expira', '2fa_usuario']);

        return redirect('/');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
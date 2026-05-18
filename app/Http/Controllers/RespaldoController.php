<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RespaldoController extends Controller
{
   public function descargar()
{
    $db      = config('database.connections.mysql.database');
    $user    = config('database.connections.mysql.username');
    $pass    = config('database.connections.mysql.password');
    $host    = config('database.connections.mysql.host');
    $fecha   = now()->format('Y-m-d_H-i');
    $archivo = "respaldo_{$db}_{$fecha}.sql";

    $mysqldump = 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe';

    $comando = "\"{$mysqldump}\" --user={$user} --host={$host} --password={$pass} {$db}";

    return response()->streamDownload(function () use ($comando) {
        passthru($comando);
    }, $archivo, [
        'Content-Type' => 'application/sql',
    ]);
}
}
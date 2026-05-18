<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
    // Mostrar vista
    public function index()
    {
        return view('cpanel.backup');
    }

    // Generar respaldo
 public function generar()
{
    $dbHost = env('DB_HOST');
    $dbName = env('DB_DATABASE');
    $dbUser = env('DB_USERNAME');
    $dbPass = env('DB_PASSWORD');

    $filename = "backup_" . date('Y-m-d_H-i-s') . ".sql";
    $path = storage_path("app/" . $filename);

    // ✅ RUTA CORRECTA (la que sí funciona)
    $mysqldump = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe';

    // 🔥 SIN contraseña (porque tú no tienes)
    $command = "\"$mysqldump\" -u$dbUser -h$dbHost $dbName --result-file=\"$path\"";

    exec($command, $output, $result);

    if ($result !== 0) {
        return response()->json([
            'error' => 'Error al generar respaldo',
            'comando' => $command
        ]);
    }

    return response()->download($path)->deleteFileAfterSend(true);
}
}
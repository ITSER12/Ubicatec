<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HorariosImport;

class HorariosController extends Controller
{
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone'));
    }
    public function importarUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::table('horarios')->truncate(); // limpia y reinicia ID
        Excel::import(new HorariosImport, $request->file('file'));

        return redirect()->route('aulas.index')
            ->with('success', 'Horarios importados correctamente (tabla reiniciada).');
    }
}

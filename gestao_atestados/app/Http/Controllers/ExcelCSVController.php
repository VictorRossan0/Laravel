<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ColaboradorExport;
use App\Imports\ColaboradorImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Colaborador;

class ExcelCSVController extends Controller
{
    /**
     * Show the form to upload Excel/CSV.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('lote.excel-csv-import');
    }

    /**
     * Import Excel/CSV file into the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importExcelCSV(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new ColaboradorImport, $request->file('file'));
            return redirect('excel-csv-file')->with('status', 'Arquivo importado com sucesso');
        } catch (\Exception $e) {
            return redirect('excel-csv-file')->with('status', 'Erro na importação do arquivo: ' . $e->getMessage());
        }
    }

    /**
     * Export data to Excel/CSV.
     *
     * @param  string  $slug
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelCSV($slug)
    {
        return Excel::download(new ColaboradorExport, 'colaborador.' . $slug);
    }
}

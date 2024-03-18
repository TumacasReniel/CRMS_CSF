<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $data = ['content' => 'Hello, this is a PDF!'];

        $pdf = PDF::loadView('pdf.document', $data);
        $pdf->save('example.pdf'); // Save the PDF to a file (optional)
        $ex_data = $pdf->download('example.pdf');
        return $ex_data;
    }
}
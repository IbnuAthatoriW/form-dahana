<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Generate PDF from submission.
     */
    public function generatePdf(FormSubmission $submission)
    {
        // Load relationships
        $submission->load('template.sections.fields', 'values.field');
        
        $template = $submission->template;

        // Custom config for dompdf to support images and CSS correctly
        $pdf = Pdf::loadView('forms.pdf', compact('submission', 'template'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = 'Change_Request_' . str_replace('-', '_', $submission->submission_code) . '.pdf';
        
        return $pdf->stream($filename);
    }
}

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
    public function generatePdf(Request $request, FormSubmission $submission)
    {
        // Query terbaru langsung dari database untuk menghindari cache memory/laravel model cache
        $submission = FormSubmission::where('id', $submission->id)->firstOrFail();
        
        // Load relationships
        $submission->load([
            'template.sections.fields',
            'values.field',
            'approvals.approverUser',
            'approvalLogs'
        ]);
        
        $template = $submission->template;

        // Custom config for dompdf to support images and CSS correctly
        // Prepare data for the view
        $data = compact('submission', 'template');

        // If explicit download requested, keep existing PDF download behavior
        if ($request->query('action') === 'download') {
            $pdf = Pdf::loadView('forms.pdf', $data)
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true)
                ->setOption('isHtml5ParserEnabled', true);
            $filename = 'Change_Request_' . str_replace('-', '_', $submission->submission_code) . '.pdf';
            return $pdf->download($filename);
        }

        // Default: render printable HTML view that triggers browser print dialog
        return view('forms.print', $data);
    }
}

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
        $pdf = Pdf::loadView('forms.pdf', compact('submission', 'template'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = 'Change_Request_' . str_replace('-', '_', $submission->submission_code) . '.pdf';
        
        if ($request->query('action') === 'download') {
            return $pdf->download($filename);
        }

        // Stream PDF dengan HTTP headers pencegah cache di browser
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}


<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ApprovalStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public FormSubmission $submission;
    public string $statusAction; // 'approved', 'rejected', 'revision', 'submitted'
    public ?string $comment;
    public ?string $approverName;

    public function __construct(
        FormSubmission $submission,
        string $statusAction,
        ?string $comment = null,
        ?string $approverName = null
    ) {
        $this->submission = $submission;
        $this->statusAction = $statusAction;
        $this->comment = $comment;
        $this->approverName = $approverName;
    }

    public function envelope(): Envelope
    {
        $statusLabels = [
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi',
            'submitted' => 'Dikirim',
            'progress' => 'Progres Approval',
            'fully_approved' => 'Semua Approval Selesai',
        ];

        $label = $statusLabels[$this->statusAction] ?? ucfirst($this->statusAction);

        return new Envelope(
            subject: '[' . $label . '] ' . $this->submission->template->title . ' - ' . $this->submission->submission_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-status',
            with: [
                'submission' => $this->submission,
                'template' => $this->submission->template,
                'statusAction' => $this->statusAction,
                'comment' => $this->comment,
                'approverName' => $this->approverName,
                'pdfUrl' => route('form.pdf', $this->submission->submission_code),
            ],
        );
    }

    public function attachments(): array
    {
        // Attach PDF for all status emails except 'submitted' (no need to re-attach for initial submit)
        if ($this->statusAction === 'submitted') {
            return [];
        }

        try {
            $submission = $this->submission->load([
                'template.sections.fields',
                'values',
                'approvals.approverUser',
            ]);
            $template = $submission->template;

            $pdf = Pdf::loadView('forms.pdf', compact('submission', 'template'))
                ->setPaper('a4', 'portrait');

            $filename = 'Dokumen_' . $submission->submission_code . '.pdf';

            return [
                Attachment::fromData(fn () => $pdf->output(), $filename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal melampirkan PDF ke ApprovalStatusMail: ' . $e->getMessage());
            return [];
        }
    }
}

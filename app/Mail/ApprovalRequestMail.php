<?php

namespace App\Mail;

use App\Models\FormSubmission;
use App\Models\DocumentApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public FormSubmission $submission;
    public DocumentApproval $approval;

    public function __construct(FormSubmission $submission, DocumentApproval $approval)
    {
        $this->submission = $submission;
        $this->approval = $approval;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Approval Required] ' . $this->submission->template->title . ' - ' . $this->submission->submission_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-request',
            with: [
                'submission' => $this->submission,
                'approval' => $this->approval,
                'template' => $this->submission->template,
                'approvalUrl' => route('approval.show', $this->submission->id),
            ],
        );
    }
}

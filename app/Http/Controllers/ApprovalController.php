<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\DocumentApproval;
use App\Models\ApprovalLog;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalRequestMail;
use App\Mail\ApprovalStatusMail;

class ApprovalController extends Controller
{
    protected QrCodeService $qrService;

    public function __construct(QrCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    // ─────────────────────────────────────────────────────────────
    // Show — Halaman review dokumen untuk approver
    // ─────────────────────────────────────────────────────────────

    public function show(FormSubmission $submission)
    {
        $submission->load([
            'approvals.approverUser',
            'template.sections.fields',
            'values.field',
            'user',
        ]);

        return view('approval.show', compact('submission'));
    }

    // ─────────────────────────────────────────────────────────────
    // Approve
    // ─────────────────────────────────────────────────────────────

    public function approve(Request $request, FormSubmission $submission)
    {
        $request->validate([
            'comment' => 'nullable|string|max:1000',
        ]);

        $submission->load(['approvals.approverUser', 'user', 'template']);

        $approval = $submission->approvals
            ->where('step', $submission->current_step)
            ->first();

        if (!$approval) {
            return back()->with('error', 'Approval tidak ditemukan.');
        }

        // Security: hanya approver aktif yang bisa approve
        if (auth()->user()->email !== $approval->approver_email) {
            abort(403, 'Anda bukan approver yang ditugaskan untuk dokumen ini pada langkah ini.');
        }

        $actingUser = auth()->user();

        try {
            DB::transaction(function () use ($submission, $approval, $actingUser, $request) {

                // Update approval record
                $approval->update([
                    'status'            => 'approved',
                    'approved_by'       => $actingUser->id,
                    'approver_name'     => $actingUser->name,
                    'approver_position' => $actingUser->position ?? $approval->approver_position,
                    'comment'           => $request->comment,
                    'acted_at'          => now(),
                ]);

                // Generate QR Code untuk approval ini
                try {
                    $qrPath = $this->qrService->generateForApproval($approval);
                    $approval->update(['qr_code_path' => $qrPath]);
                } catch (\Exception $e) {
                    Log::error('[ApprovalController] Gagal generate QR Code: ' . $e->getMessage());
                }

                // Catat log
                ApprovalLog::create([
                    'submission_id' => $submission->id,
                    'user_id'       => $actingUser->id,
                    'action'        => 'approved',
                    'step'          => $approval->step,
                    'comment'       => $request->comment,
                ]);

                // Cari approval berikutnya yang pending
                $next = $submission->approvals
                    ->where('step', '>', $submission->current_step)
                    ->where('status', 'pending')
                    ->sortBy('step')
                    ->first();

                $submitterEmail = optional($submission->user)->email;

                if ($next) {
                    // Masih ada step berikutnya
                    $submission->update([
                        'current_step'    => $next->step,
                        'workflow_status' => 'waiting',
                        'status'          => 'submitted',
                    ]);

                    Log::info('[ApprovalController] Approved step ' . $approval->step . ', moving to step ' . $next->step, [
                        'submission_id' => $submission->id,
                    ]);

                    // Email ke approver berikutnya
                    if ($next->approver_email) {
                        try {
                            Mail::to($next->approver_email)->send(new ApprovalRequestMail($submission, $next));
                        } catch (\Exception $e) {
                            Log::error('[ApprovalController] Gagal kirim email ke approver berikutnya: ' . $e->getMessage());
                        }
                    }

                    // Email progress ke pengaju
                    if ($submitterEmail) {
                        try {
                            Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'progress', $request->comment, $actingUser->name));
                        } catch (\Exception $e) {
                            Log::error('[ApprovalController] Gagal kirim email progress ke pengaju: ' . $e->getMessage());
                        }
                    }

                } else {
                    // Semua approval selesai
                    $submission->update([
                        'current_step'    => 0,
                        'workflow_status' => 'approved',
                        'status'          => 'approved',
                    ]);

                    Log::info('[ApprovalController] All approvals completed for submission ' . $submission->id);

                    // Email fully approved ke pengaju
                    if ($submitterEmail) {
                        try {
                            Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'fully_approved', null, $actingUser->name));
                        } catch (\Exception $e) {
                            Log::error('[ApprovalController] Gagal kirim email fully_approved ke pengaju: ' . $e->getMessage());
                        }
                    }
                }
            });
        } catch (\Exception $e) {

            Log::error('[ApprovalController] Transaction gagal saat approve', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Dokumen berhasil disetujui. QR Code verifikasi telah digenerate.');
    }

    // ─────────────────────────────────────────────────────────────
    // Reject
    // ─────────────────────────────────────────────────────────────

    public function reject(Request $request, FormSubmission $submission)
    {
        $request->validate([
            'comment' => 'required|string|min:5',
        ], [
            'comment.required' => 'Alasan penolakan wajib diisi.',
            'comment.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $submission->load(['approvals', 'user', 'template']);

        $approval = $submission->approvals
            ->where('step', $submission->current_step)
            ->first();

        if (!$approval) {
            return back()->with('error', 'Approval tidak ditemukan.');
        }

        if (auth()->user()->email !== $approval->approver_email) {
            abort(403, 'Anda bukan approver yang ditugaskan untuk dokumen ini pada langkah ini.');
        }

        $actingUser = auth()->user();

        try {
            DB::transaction(function () use ($submission, $approval, $actingUser, $request) {

                $approval->update([
                    'status'            => 'rejected',
                    'comment'           => $request->comment,
                    'approved_by'       => $actingUser->id,
                    'approver_name'     => $actingUser->name,
                    'approver_position' => $actingUser->position ?? $approval->approver_position,
                    'acted_at'          => now(),
                ]);

                // Generate QR Code untuk approval ini
                try {
                    $qrPath = $this->qrService->generateForApproval($approval);
                    $approval->update(['qr_code_path' => $qrPath]);
                } catch (\Exception $e) {
                    Log::error('[ApprovalController] Gagal generate QR Code (Reject): ' . $e->getMessage());
                }

                $submission->update([
                    'workflow_status' => 'rejected',
                    'status'          => 'rejected',
                ]);

                ApprovalLog::create([
                    'submission_id' => $submission->id,
                    'user_id'       => $actingUser->id,
                    'action'        => 'rejected',
                    'step'          => $approval->step,
                    'comment'       => $request->comment,
                ]);

                // Email penolakan ke pengaju
                $submitterEmail = optional($submission->user)->email;
                if ($submitterEmail) {
                    try {
                        Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'rejected', $request->comment, $actingUser->name));
                    } catch (\Exception $e) {
                        Log::error('[ApprovalController] Gagal kirim email rejected ke pengaju: ' . $e->getMessage());
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('[ApprovalController] Transaction gagal saat reject: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses penolakan. Silakan coba lagi.');
        }

        return back()->with('success', 'Dokumen telah ditolak. Notifikasi telah dikirim ke pengaju.');
    }

    // ─────────────────────────────────────────────────────────────
    // Revision
    // ─────────────────────────────────────────────────────────────

    public function revision(Request $request, FormSubmission $submission)
    {
        $request->validate([
            'comment' => 'required|string|min:5',
        ], [
            'comment.required' => 'Alasan permintaan revisi wajib diisi.',
            'comment.min'      => 'Alasan revisi minimal 5 karakter.',
        ]);

        $submission->load(['approvals', 'user', 'template']);

        $approval = $submission->approvals
            ->where('step', $submission->current_step)
            ->first();

        if (!$approval) {
            return back()->with('error', 'Approval tidak ditemukan.');
        }

        if (auth()->user()->email !== $approval->approver_email) {
            abort(403, 'Anda bukan approver yang ditugaskan untuk dokumen ini pada langkah ini.');
        }

        $actingUser = auth()->user();

        try {
            DB::transaction(function () use ($submission, $approval, $actingUser, $request) {

                $approval->update([
                    'status'            => 'revision',
                    'comment'           => $request->comment,
                    'approved_by'       => $actingUser->id,
                    'approver_name'     => $actingUser->name,
                    'approver_position' => $actingUser->position ?? $approval->approver_position,
                    'acted_at'          => now(),
                ]);

                // Generate QR Code untuk approval ini
                try {
                    $qrPath = $this->qrService->generateForApproval($approval);
                    $approval->update(['qr_code_path' => $qrPath]);
                } catch (\Exception $e) {
                    Log::error('[ApprovalController] Gagal generate QR Code (Revision): ' . $e->getMessage());
                }

                $submission->update([
                    'workflow_status' => 'revision',
                    'status'          => 'revision',
                ]);

                ApprovalLog::create([
                    'submission_id' => $submission->id,
                    'user_id'       => $actingUser->id,
                    'action'        => 'revision',
                    'step'          => $approval->step,
                    'comment'       => $request->comment,
                ]);

                // Email revisi ke pengaju
                $submitterEmail = optional($submission->user)->email;
                if ($submitterEmail) {
                    try {
                        Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'revision', $request->comment, $actingUser->name));
                    } catch (\Exception $e) {
                        Log::error('[ApprovalController] Gagal kirim email revision ke pengaju: ' . $e->getMessage());
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('[ApprovalController] Transaction gagal saat revision: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan revisi. Silakan coba lagi.');
        }

        return back()->with('success', 'Permintaan revisi berhasil dikirim. Notifikasi telah dikirim ke pengaju.');
    }

    // ─────────────────────────────────────────────────────────────
    // Verify — Halaman verifikasi QR Code (PUBLIK, tanpa login)
    // ─────────────────────────────────────────────────────────────

    public function verify(string $uuid)
    {
        $approval = DocumentApproval::with([
            'submission.template',
            'submission.user',
            'approverUser',
        ])->where('approval_uuid', $uuid)->first();

        if (!$approval) {
            return view('approval.verify', [
                'valid'    => false,
                'approval' => null,
            ]);
        }

        // Catat waktu pertama kali QR dipindai
        if (!$approval->verified_at) {
            $approval->update(['verified_at' => now()]);
        }

        return view('approval.verify', [
            'valid'    => true,
            'approval' => $approval,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Timeline — JSON endpoint untuk modal AJAX
    // ─────────────────────────────────────────────────────────────

    public function timeline(FormSubmission $submission)
    {
        $submission->load(['approvals' => function ($q) {
            $q->orderBy('step');
        }, 'template', 'user']);

        return response()->json([
            'submission_code' => $submission->submission_code,
            'title'           => optional($submission->template)->title,
            'pemohon_nama'    => $submission->pemohon_nama,
            'created_at'      => $submission->created_at->format('d M Y H:i'),
            'status'          => $submission->trackingStatus(),
            'workflow_status' => $submission->workflow_status,
            'current_step'    => $submission->current_step,
            'approvals'       => $submission->approvals->map(function ($app) {
                return [
                    'step'       => $app->step,
                    'name'       => $app->approver_name     ?: '-',
                    'position'   => $app->approver_position ?: '-',
                    'email'      => $app->approver_email    ?: '-',
                    'status'     => $app->status,
                    'comment'    => $app->comment,
                    'acted_at'   => $app->acted_at ? $app->acted_at->format('d M Y H:i') : null,
                    'verify_url' => $app->status === 'approved' ? $app->verifyUrl() : null,
                ];
            }),
        ]);
    }
}
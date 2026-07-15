<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\DocumentApproval;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalRequestMail;
use App\Mail\ApprovalStatusMail;

class ApprovalController extends Controller
{
    /**
     * Menampilkan halaman approval.
     */
    public function show(FormSubmission $submission)
    {
        $submission->load(['approvals.approverUser', 'template.sections.fields', 'values.field']);

        return view('approval.show', compact('submission'));
    }

    /**
     * Approve dokumen.
     */
    public function approve(FormSubmission $submission)
    {
        $approval = $submission->approvals()
            ->where('step', $submission->current_step)
            ->first();

        if (!$approval) {
            return back()->with('error', 'Approval tidak ditemukan.');
        }

        if (auth()->user()->email != $approval->approver_email) {
            abort(403, 'Anda bukan approver dokumen ini.');
        }

        // Ambil signature path dari profile user yang login
        $signaturePath = auth()->user()->signature;

        // Approval sekarang
        $approval->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'signature_path' => $signaturePath,
            'acted_at' => now(),
        ]);

        ApprovalLog::create([
            'submission_id' => $submission->id,
            'user_id'       => auth()->id(),
            'action'        => 'approved',
            'step'          => $approval->step,
        ]);

        // Cari approval berikutnya
        $next = $submission->approvals()
            ->where('step', '>', $submission->current_step)
            ->orderBy('step')
            ->first();

        $submitterEmail = $submission->user ? $submission->user->email : null;

        if ($next) {
            // Masih ada approval berikutnya
            $submission->update([
                'current_step' => $next->step,
                'workflow_status' => 'waiting',
                'status' => 'submitted',
            ]);

            // Kirim email ke approver berikutnya
            if ($next->approver_email) {
                try {
                    Mail::to($next->approver_email)->send(new ApprovalRequestMail($submission, $next));
                } catch (\Exception $e) {
                    logger()->error('Gagal mengirim email ke approver berikutnya: ' . $e->getMessage());
                }
            }

            // Kirim email progress ke pengaju
            if ($submitterEmail) {
                try {
                    Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'progress', null, auth()->user()->name));
                } catch (\Exception $e) {
                    logger()->error('Gagal mengirim email progress ke pengaju: ' . $e->getMessage());
                }
            }

        } else {
            // Semua approval selesai
            $submission->update([
                'current_step' => 0,
                'workflow_status' => 'approved',
                'status' => 'approved',
            ]);

            // Kirim email penutup (fully approved) ke pengaju
            if ($submitterEmail) {
                try {
                    Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'fully_approved', null, auth()->user()->name));
                } catch (\Exception $e) {
                    logger()->error('Gagal mengirim email fully approved ke pengaju: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Dokumen berhasil disetujui.');
    }

    /**
     * Tolak dokumen.
     */
    public function reject(Request $request, FormSubmission $submission)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $approval = $submission->approvals()
            ->where('step', $submission->current_step)
            ->first();

        if (!$approval) {
            return back()->with('error', 'Approval tidak ditemukan.');
        }

        if (auth()->user()->email != $approval->approver_email) {
            abort(403, 'Anda bukan approver dokumen ini.');
        }

        $approval->update([
            'status' => 'rejected',
            'comment' => $request->comment,
            'approved_by' => auth()->id(),
            'acted_at' => now(),
        ]);

        $submission->update([
            'workflow_status' => 'rejected',
            'status' => 'rejected',
        ]);

        ApprovalLog::create([
            'submission_id' => $submission->id,
            'user_id'       => auth()->id(),
            'action'        => 'rejected',
            'step'          => $approval->step,
            'comment'       => $request->comment,
        ]);

        // Kirim email penolakan ke pengaju
        $submitterEmail = $submission->user ? $submission->user->email : null;
        if ($submitterEmail) {
            try {
                Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'rejected', $request->comment, auth()->user()->name));
            } catch (\Exception $e) {
                logger()->error('Gagal mengirim email penolakan ke pengaju: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Dokumen ditolak.');
    }

    /**
     * Minta revisi.
     */
    public function revision(Request $request, FormSubmission $submission)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $approval = $submission->approvals()
            ->where('step', $submission->current_step)
            ->first();

        if (!$approval) {
            return back()->with('error', 'Approval tidak ditemukan.');
        }

        if (auth()->user()->email != $approval->approver_email) {
            abort(403, 'Anda bukan approver dokumen ini.');
        }

        $approval->update([
            'status' => 'revision',
            'comment' => $request->comment,
            'approved_by' => auth()->id(),
            'acted_at' => now(),
        ]);

        $submission->update([
            'workflow_status' => 'revision',
            'status' => 'revision',
        ]);

        ApprovalLog::create([
            'submission_id' => $submission->id,
            'user_id'       => auth()->id(),
            'action'        => 'revision',
            'step'          => $approval->step,
            'comment'       => $request->comment,
        ]);

        // Kirim email permintaan revisi ke pengaju
        $submitterEmail = $submission->user ? $submission->user->email : null;
        if ($submitterEmail) {
            try {
                Mail::to($submitterEmail)->send(new ApprovalStatusMail($submission, 'revision', $request->comment, auth()->user()->name));
            } catch (\Exception $e) {
                logger()->error('Gagal mengirim email revisi ke pengaju: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Permintaan revisi berhasil dikirim.');
    }

    /**
     * Get timeline data via JSON
     */
    public function timeline(FormSubmission $submission)
    {
        $submission->load(['approvals.approverUser', 'template']);

        return response()->json([
            'submission_code' => $submission->submission_code,
            'title' => $submission->template->title,
            'pemohon_nama' => $submission->pemohon_nama,
            'created_at' => $submission->created_at->format('d M Y H:i'),
            'status' => $submission->trackingStatus(),
            'approvals' => $submission->approvals->map(function ($app) {
                return [
                    'step' => $app->step,
                    'name' => $app->approver_name,
                    'position' => $app->approver_position,
                    'status' => $app->status,
                    'comment' => $app->comment,
                    'acted_at' => $app->acted_at ? $app->acted_at->format('d M Y H:i') : null,
                ];
            })
        ]);
    }
}
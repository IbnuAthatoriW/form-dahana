<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\DocumentApproval;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Menampilkan halaman approval.
     */
    public function show(FormSubmission $submission)
    {
        $submission->load('approvals');

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

        if(auth()->user()->email != $approval->approver_email){
            abort(403,'Anda bukan approver dokumen ini.');
        }

        // Approval sekarang
        $approval->update([

            'status'=>'approved',

            'approved_by'=>auth()->id(),

            'acted_at'=>now(),

        ]);

        ApprovalLog::create([
            'submission_id' => $submission->id,
            'user_id'       => auth()->id(),
            'action'        => 'approved',
        ]);

        // Cari approval berikutnya
        $next = $submission->approvals()
            ->where('step', '>', $submission->current_step)
            ->orderBy('step')
            ->first();

        if ($next) {

            // Masih ada approval berikutnya
            $submission->update([

                'current_step' => $next->step,

                'workflow_status' => 'waiting',

                'status' => 'submitted',

            ]);

            // Nanti di sini kirim email ke approver berikutnya

        } else {

            // Semua approval selesai
            $submission->update([

                'current_step' => 0,

                'workflow_status' => 'approved',

                'status' => 'approved',

            ]);

            // Nanti kirim email ke pengaju
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

        $approval->update([
            'status' => 'rejected',
            'comment' => $request->comment,
            'acted_at' => now(),
        ]);

        $submission->update([
            'workflow_status' => 'rejected',
            'status' => 'rejected',
        ]);

        ApprovalLog::create([
            'submission_id' => $submission->id,
            'user_id' => auth()->id(),
            'action' => 'rejected',
            'comment' => $request->comment,
        ]);

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

        $approval->update([
            'status' => 'revision',
            'comment' => $request->comment,
            'acted_at' => now(),
        ]);

        $submission->update([
            'workflow_status' => 'revision',
            'status' => 'revision',
        ]);

        ApprovalLog::create([
            'submission_id' => $submission->id,
            'user_id' => auth()->id(),
            'action' => 'revision',
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Permintaan revisi berhasil dikirim.');
    }
}
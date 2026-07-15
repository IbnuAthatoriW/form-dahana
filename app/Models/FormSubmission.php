<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class FormSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'form_template_id',
        'submission_code',
        'pemohon_nama',
        'pemohon_jabatan',
        'pemohon_departemen',
        'pemohon_tgl_pengajuan',
        'peruntukan_nama',
        'peruntukan_jabatan',
        'peruntukan_departemen',
        'peruntukan_sla_deadline',
        'status',
        'workflow_status',
        'current_step',
    ];

    protected $casts = [
        'pemohon_tgl_pengajuan' => 'date',
    ];

    // ─────────────────────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(SubmissionValue::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class, 'submission_id')
            ->orderBy('step');
    }

    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class, 'submission_id');
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers — gunakan in-memory collection bila sudah di-load
    // untuk menghindari N+1 query di dashboard/loop
    // ─────────────────────────────────────────────────────────────

    /**
     * Ambil value berdasarkan field ID.
     */
    public function getValueForField(int $fieldId)
    {
        $valObj = $this->values()
            ->where('template_field_id', $fieldId)
            ->first();

        if (!$valObj) {
            return null;
        }

        $decoded = json_decode($valObj->value, true);

        return json_last_error() === JSON_ERROR_NONE
            ? $decoded
            : $valObj->value;
    }

    /**
     * Approval yang sedang aktif (step saat ini).
     * Menggunakan in-memory collection bila sudah di-load.
     */
    public function currentApproval(): ?DocumentApproval
    {
        if ($this->relationLoaded('approvals')) {
            return $this->approvals
                ->where('step', $this->current_step)
                ->first();
        }

        return $this->approvals()
            ->where('step', $this->current_step)
            ->first();
    }

    /**
     * Alias untuk currentApproval() — approver yang sedang menunggu.
     */
    public function currentReceiver(): ?DocumentApproval
    {
        return $this->currentApproval();
    }

    /**
     * Approval terakhir yang sudah dieksekusi (ada acted_at).
     * Menggunakan in-memory collection bila sudah di-load.
     */
    public function latestApproval(): ?DocumentApproval
    {
        if ($this->relationLoaded('approvals')) {
            return $this->approvals
                ->whereNotNull('acted_at')
                ->sortByDesc('acted_at')
                ->first();
        }

        return $this->approvals()
            ->whereNotNull('acted_at')
            ->latest('acted_at')
            ->first();
    }

    /**
     * Jabatan approver yang sedang aktif.
     */
    public function currentReceiverPosition(): string
    {
        $approval = $this->currentApproval();
        return $approval ? ($approval->approver_position ?? '-') : '-';
    }

    /**
     * Progress persentase approval (0–100).
     * Menggunakan in-memory collection bila sudah di-load.
     */
    public function progress(): int
    {
        if ($this->relationLoaded('approvals')) {
            $total = $this->approvals->count();
            if ($total === 0) return 100; // Tidak ada workflow = langsung selesai
            $approved = $this->approvals->where('status', 'approved')->count();
            return (int) round(($approved / $total) * 100);
        }

        $total = $this->approvals()->count();
        if ($total === 0) return 100;

        $approved = $this->approvals()->where('status', 'approved')->count();
        return (int) round(($approved / $total) * 100);
    }

    /**
     * Total jumlah approver.
     */
    public function totalApprovers(): int
    {
        if ($this->relationLoaded('approvals')) {
            return $this->approvals->count();
        }
        return $this->approvals()->count();
    }

    /**
     * Jumlah approval yang sudah disetujui.
     */
    public function approvedCount(): int
    {
        if ($this->relationLoaded('approvals')) {
            return $this->approvals->where('status', 'approved')->count();
        }
        return $this->approvals()->where('status', 'approved')->count();
    }

    /**
     * Label status tracking yang human-readable.
     * Menggunakan in-memory collection bila sudah di-load.
     */
    public function trackingStatus(): string
    {
        return match($this->workflow_status) {
            'approved'  => 'Dokumen Disetujui',
            'rejected'  => 'Dokumen Ditolak',
            'revision'  => 'Perlu Revisi',
            default     => $this->_pendingTrackingStatus(),
        };
    }

    private function _pendingTrackingStatus(): string
    {
        $approval = $this->currentApproval();

        if (!$approval) {
            return 'Selesai';
        }

        return 'Menunggu: ' . ($approval->approver_name ?: 'Approver Step ' . $approval->step);
    }

    /**
     * Waktu update terakhir workflow (acted_at approval terakhir atau created_at).
     */
    public function trackingDate(): \Carbon\Carbon|\Illuminate\Support\Carbon
    {
        $latest = $this->latestApproval();
        return $latest ? $latest->acted_at : $this->created_at;
    }

    /**
     * Riwayat approval log.
     */
    public function trackingHistory()
    {
        return $this->approvalLogs()
            ->with('user')
            ->latest()
            ->get();
    }
}
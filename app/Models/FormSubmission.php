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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'pemohon_tgl_pengajuan' => 'date',
    ];

    /**
     * Template formulir
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    /**
     * Nilai field formulir
     */
    public function values(): HasMany
    {
        return $this->hasMany(SubmissionValue::class);
    }

    /**
     * Workflow approval
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class, 'submission_id');
    }

    /**
     * Log approval
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class, 'submission_id');
    }

    /**
     * Ambil value berdasarkan field
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
     * Approval yang sedang aktif
     */
    public function currentApproval()
    {
        return $this->approvals()
            ->where('step', $this->current_step)
            ->first();
    }

    /**
     * Progress approval (%)
     */
    public function progress()
    {
        $total = $this->approvals()->count();

        if ($total == 0) {
            return 0;
        }

        $approved = $this->approvals()
            ->where('status', 'approved')
            ->count();

        return round(($approved / $total) * 100);
    }

        /**
     * Approval yang sedang ditunggu
     */
    public function currentReceiver()
    {
        return $this->approvals()
            ->where('step', $this->current_step)
            ->first();
    }

    /**
     * Update terakhir workflow
     */
    public function latestApproval()
    {
        return $this->approvals()
            ->whereNotNull('acted_at')
            ->latest('acted_at')
            ->first();
    }

    /**
     * Posisi approver yang sedang aktif
     */
    public function currentReceiverPosition()
    {
        $approval = $this->approvals()
            ->where('step', $this->current_step)
            ->first();

        return $approval ? $approval->approver_position : '-';
    }

    /**
     * Status tracking terbaru
     */
    public function trackingStatus()
    {
        if ($this->workflow_status == 'approved') {
            return 'Dokumen Disetujui';
        }

        if ($this->workflow_status == 'rejected') {
            return 'Dokumen Ditolak';
        }

        if ($this->workflow_status == 'revision') {
            return 'Perlu Revisi';
        }

        $approval = $this->approvals()
            ->where('step', $this->current_step)
            ->first();

        if (!$approval) {
            return 'Selesai';
        }

        return 'Menunggu Approval ' . $approval->approver_name;
    }

    /**
     * Waktu tracking terakhir
     */
    public function trackingDate()
    {
        $approval = $this->approvals()
            ->whereNotNull('acted_at')
            ->latest('acted_at')
            ->first();

        return $approval
            ? $approval->acted_at
            : $this->created_at;
    }

    /**
     * Riwayat approval
     */
    public function trackingHistory()
    {
        return $this->approvalLogs()
            ->with('user')
            ->latest()
            ->get();
    }
}
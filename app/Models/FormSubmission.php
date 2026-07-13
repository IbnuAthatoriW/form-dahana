<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormSubmission extends Model
{
    protected $fillable = [
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

        // Workflow
        'status',
        'user_id',
        'workflow_status',
        'current_step',
    ];

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
}
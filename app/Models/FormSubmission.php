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
        'status',
    ];

    protected $casts = [
        'pemohon_tgl_pengajuan' => 'date',
    ];

    /**
     * Get the template this submission belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    /**
     * Get the values for this submission.
     */
    public function values(): HasMany
    {
        return $this->hasMany(SubmissionValue::class);
    }

    /**
     * Get the value of a specific field by its field ID or label.
     */
    public function getValueForField(int $fieldId)
    {
        $valObj = $this->values()->where('template_field_id', $fieldId)->first();
        if (!$valObj) {
            return null;
        }

        // Try decoding if it is JSON (like checkbox groups or tables)
        $val = $valObj->value;
        $decoded = json_decode($val, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return $val;
    }
}

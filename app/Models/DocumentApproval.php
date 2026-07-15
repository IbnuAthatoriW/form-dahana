<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use App\Models\User;

class DocumentApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'step',
        'approver_user_id',
        'approver_name',
        'approver_position',
        'approver_email',
        'approved_by',
        'approval_uuid',
        'qr_code_path',
        'verified_at',
        'status',
        'comment',
        'acted_at',
    ];

    protected $casts = [
        'acted_at'    => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Auto-assign UUID when creating a new record.
     */
    protected static function booted(): void
    {
        static::creating(function (DocumentApproval $approval) {
            if (empty($approval->approval_uuid)) {
                $approval->approval_uuid = (string) Str::uuid();
            }
        });
    }

    // ─────────────────────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────────────────────

    public function submission()
    {
        return $this->belongsTo(FormSubmission::class, 'submission_id');
    }

    /**
     * User yang di-assign sebagai approver (dari template)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }

    /**
     * User yang melakukan aksi approve (yang login)
     */
    public function approverUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────

    /**
     * URL halaman verifikasi QR Code untuk approval ini.
     */
    public function verifyUrl(): string
    {
        return $this->approval_uuid
            ? route('approval.verify', $this->approval_uuid)
            : '#';
    }

    /**
     * Apakah approval ini sudah memiliki QR Code.
     */
    public function hasQrCode(): bool
    {
        return !empty($this->qr_code_path);
    }
}
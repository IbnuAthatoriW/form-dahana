<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'signature_path',
        'status',
        'comment',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

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
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\TemplateApproval;
use App\Models\DocumentApproval;
use App\Models\FormSubmission;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'nip',
    'phone',
    'position',
    'department',
    'address',
    'photo',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function approvals()
    {
        return $this->hasMany(DocumentApproval::class, 'approver_user_id');
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function templateApprovals()
    {
        return $this->hasMany(TemplateApproval::class, 'approver_user_id');
    }

    /**
     * Semua approval yang pernah dilakukan oleh user ini (via approved_by).
     * Digunakan untuk Riwayat Approval di profil.
     */
    public function approvalHistory()
    {
        return $this->hasMany(DocumentApproval::class, 'approved_by')
            ->whereIn('status', ['approved', 'rejected', 'revision'])
            ->with(['submission.template'])
            ->orderByDesc('acted_at');
    }
}

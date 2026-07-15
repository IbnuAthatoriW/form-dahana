<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TemplateApproval extends Model
{
    protected $fillable = [
        'form_template_id',
        'step',
        'approver_user_id',
    ];

    public function template()
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    /**
     * User yang menjadi approver
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
}
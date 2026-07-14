<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateApproval extends Model
{
    protected $fillable = [
        'form_template_id',
        'step',
        'name',
        'position',
        'email',
    ];

    public function template()
    {
        return $this->belongsTo(FormTemplate::class,'form_template_id');
    }
}
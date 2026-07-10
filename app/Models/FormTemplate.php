<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormTemplate extends Model
{
    protected $fillable = [
        'title',
        'author',
        'created_date',
        'status',
        'revision',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_date' => 'datetime',
    ];


    /**
     * Get sections of the template.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class)->orderBy('order');
    }

    /**
     * Get submissions of the template.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}

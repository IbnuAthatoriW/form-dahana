<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateField extends Model
{
    protected $fillable = [
        'template_section_id',
        'label',
        'type',
        'options',
        'is_required',
        'order',
        'config',
    ];

    protected $casts = [
        'options' => 'array',
        'config' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * Get the section this field belongs to.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(TemplateSection::class, 'template_section_id');
    }

    /**
     * Get submission values for this field.
     */
    public function values(): HasMany
    {
        return $this->hasMany(SubmissionValue::class);
    }
}

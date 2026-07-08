<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSection extends Model
{
    protected $fillable = [
        'form_template_id',
        'title',
        'order',
        'is_static',
    ];

    protected $casts = [
        'is_static' => 'boolean',
    ];

    /**
     * Get the template this section belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    /**
     * Get fields in the section.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class)->orderBy('order');
    }
}

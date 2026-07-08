<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('template_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_section_id')->constrained('template_sections')->onDelete('cascade');
            $table->string('label');
            $table->string('type'); // text, textarea, select, checkbox, checkbox_group, table, signature
            $table->text('options')->nullable(); // JSON array for choice types (e.g. High, Medium, Low)
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->text('config')->nullable(); // JSON config for columns, row layout, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_fields');
    }
};

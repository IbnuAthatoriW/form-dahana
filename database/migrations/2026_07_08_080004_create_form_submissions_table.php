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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_template_id')->constrained('form_templates')->onDelete('cascade');
            $table->string('submission_code')->unique();
            
            // Section 1: Identitas (Pemohon)
            $table->string('pemohon_nama');
            $table->string('pemohon_jabatan');
            $table->string('pemohon_departemen');
            $table->date('pemohon_tgl_pengajuan');
            
            // Section 1: Identitas (Peruntukan)
            $table->string('peruntukan_nama');
            $table->string('peruntukan_jabatan');
            $table->string('peruntukan_departemen');
            $table->string('peruntukan_sla_deadline')->nullable();
            
            $table->string('status')->default('submitted'); // submitted, draft
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_approvals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('submission_id')
                ->constrained('form_submissions')
                ->cascadeOnDelete();

            $table->integer('step');

            $table->string('approver_name');

            $table->string('approver_position');

            $table->string('approver_email');

            $table->enum('status',[
                'pending',
                'approved',
                'rejected',
                'revision'
            ])->default('pending');

            $table->text('comment')->nullable();

            $table->timestamp('acted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
    }
};
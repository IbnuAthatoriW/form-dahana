<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_approvals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('form_template_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('step');

            $table->foreignId('approver_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_approvals');
    }
};
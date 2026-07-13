<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            $table->string('workflow_status')
                ->default('submitted')
                ->after('status');

            $table->integer('current_step')
                ->default(1)
                ->after('workflow_status');

        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {

            $table->dropForeign(['user_id']);

            $table->dropColumn([
                'user_id',
                'workflow_status',
                'current_step'
            ]);

        });
    }
};
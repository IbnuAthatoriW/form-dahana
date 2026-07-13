<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {

            $table->string('workflow_status')
                ->default('submitted');

            $table->integer('current_step')
                ->default(1);

        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {

            $table->dropColumn([
                'workflow_status',
                'current_step'
            ]);

        });
    }
};
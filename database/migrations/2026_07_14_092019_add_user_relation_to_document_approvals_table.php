<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {

            $table->foreignId('approver_user_id')
                ->nullable()
                ->after('step')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('signature_path')
                ->nullable()
                ->after('approver_email');

        });
    }

    public function down(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {

            $table->dropForeign(['approver_user_id']);

            $table->dropColumn([
                'approver_user_id',
                'signature_path'
            ]);

        });
    }
};
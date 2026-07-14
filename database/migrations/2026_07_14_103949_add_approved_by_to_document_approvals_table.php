<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {

            $table->foreignId('approved_by')
                ->nullable()
                ->after('approver_email')
                ->constrained('users')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {

            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');

        });
    }
};

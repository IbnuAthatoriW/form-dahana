<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {
            $table->string('approver_name')->nullable()->change();
            $table->string('approver_position')->nullable()->change();
            $table->string('approver_email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {
            $table->string('approver_name')->nullable(false)->change();
            $table->string('approver_position')->nullable(false)->change();
            $table->string('approver_email')->nullable(false)->change();
        });
    }
};

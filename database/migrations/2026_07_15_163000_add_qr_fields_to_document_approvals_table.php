<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {
            // UUID unik per approval — diisi saat record dibuat
            $table->uuid('approval_uuid')->nullable()->unique()->after('id');

            // Path file QR Code SVG yang tersimpan di storage
            $table->string('qr_code_path')->nullable()->after('acted_at');

            // Timestamp pertama kali QR dipindai/diverifikasi
            $table->timestamp('verified_at')->nullable()->after('qr_code_path');

            // Hapus signature_path yang tidak lagi dipakai
            if (Schema::hasColumn('document_approvals', 'signature_path')) {
                $table->dropColumn('signature_path');
            }
        });

        // Isi approval_uuid untuk record yang sudah ada
        DB::table('document_approvals')
            ->whereNull('approval_uuid')
            ->get()
            ->each(function ($row) {
                DB::table('document_approvals')
                    ->where('id', $row->id)
                    ->update(['approval_uuid' => \Illuminate\Support\Str::uuid()]);
            });
    }

    public function down(): void
    {
        Schema::table('document_approvals', function (Blueprint $table) {
            $table->dropColumn(['approval_uuid', 'qr_code_path', 'verified_at']);
            $table->string('signature_path')->nullable();
        });
    }
};

<?php

namespace App\Services;

use App\Models\DocumentApproval;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR Code SVG untuk approval yang berhasil dilakukan.
     * Simpan ke storage dan kembalikan path file.
     */
    public function generateForApproval(DocumentApproval $approval): string
    {
        // Pastikan UUID sudah ada
        if (!$approval->approval_uuid) {
            $approval->approval_uuid = (string) Str::uuid();
            $approval->save();
        }

        $verifyUrl = route('approval.verify', $approval->approval_uuid);

        // Generate QR Code as SVG string (tidak butuh Imagick)
        $svgContent = QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($verifyUrl);

        $path = 'qrcodes/' . $approval->approval_uuid . '.svg';

        Storage::disk('public')->put($path, $svgContent);

        return $path;
    }

    /**
     * Kembalikan SVG string inline untuk embed langsung di PDF / Blade.
     * Jika file tidak ada, generate on-the-fly tanpa menyimpan.
     */
    public function getQrSvgInline(DocumentApproval $approval): string
    {
        if ($approval->qr_code_path && Storage::disk('public')->exists($approval->qr_code_path)) {
            return Storage::disk('public')->get($approval->qr_code_path);
        }

        if (!$approval->approval_uuid) {
            return '';
        }

        $verifyUrl = route('approval.verify', $approval->approval_uuid);

        return (string) QrCode::format('svg')
            ->size(150)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($verifyUrl);
    }

    /**
     * Kembalikan path absolut file QR Code untuk embed di Dompdf.
     * Dompdf tidak bisa render SVG inline, gunakan PNG base64 atau
     * embed sebagai data URI.
     */
    public function getQrBase64ForPdf(DocumentApproval $approval): ?string
    {
        if (!$approval->approval_uuid) {
            return null;
        }

        $verifyUrl = route('approval.verify', $approval->approval_uuid);

        // Generate sebagai SVG string dan encode ke base64 data URI
        $svg = (string) QrCode::format('svg')
            ->size(120)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($verifyUrl);

        // Encode ke base64 untuk embed di PDF (Dompdf supports data URIs for SVG via img)
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

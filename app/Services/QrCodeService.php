<?php

namespace App\Services;

use App\Models\DocumentApproval;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Helper untuk mendapatkan teks detail approval yang akan di-encode ke QR Code.
     */
    private function buildQrContent(DocumentApproval $approval): string
    {
        return route('approval.verify', $approval->approval_uuid);
    }

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

        $content = $this->buildQrContent($approval);

        // Generate QR Code as SVG string (tidak butuh Imagick)
        $svgContent = QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($content);

        $path = 'qrcodes/' . $approval->approval_uuid . '.svg';

        Storage::disk('public')->put($path, $svgContent);

        return $path;
    }

    /**
     * Kembalikan SVG string inline untuk embed langsung di PDF / Blade.
     * Generate on-the-fly tanpa menyimpan di cache statis agar selalu terbaru.
     */
    public function getQrSvgInline(DocumentApproval $approval): string
    {
        if (!$approval->approval_uuid) {
            return '';
        }

        $content = $this->buildQrContent($approval);

        return (string) QrCode::format('svg')
            ->size(150)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($content);
    }

    /**
     * Kembalikan path absolut file QR Code untuk embed di Dompdf.
     * Dompdf tidak bisa render SVG inline, gunakan PNG base64 atau
     * embed sebagai data URI.
     */
    public function getQrBase64ForPdf(DocumentApproval $approval): ?string
    {
        // QR hanya untuk approval yang benar-benar approved
        if ($approval->status !== 'approved') {
            return null;
        }

        if (!$approval->approval_uuid) {
            return null;
        }

        $content = $this->buildQrContent($approval);

        $svg = (string) QrCode::format('svg')
            ->size(120)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($content);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}


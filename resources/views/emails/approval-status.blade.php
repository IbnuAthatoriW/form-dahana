<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Dokumen</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }
        .header {
            padding: 32px 32px 24px;
            text-align: center;
        }
        .header-approved {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        .header-rejected {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .header-revision {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }
        .header-progress {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }
        .header-submitted {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        }
        .header-fully_approved {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            margin: 0 0 6px;
            font-weight: 700;
        }
        .header p {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            margin: 0;
        }
        .badge {
            display: inline-block;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            background: rgba(255,255,255,0.2);
        }
        .body-content {
            padding: 32px;
        }
        .greeting {
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px 14px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-table td:first-child {
            color: #64748b;
            width: 40%;
            font-weight: 600;
        }
        .info-table td:last-child {
            color: #1e293b;
            font-weight: 500;
        }
        .comment-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
            font-size: 13px;
            line-height: 1.6;
        }
        .comment-box.rejected {
            background: #fee2e2;
            border-left-color: #dc2626;
        }
        .comment-box strong {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .actions {
            text-align: center;
            padding: 24px 32px 32px;
        }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            margin: 6px;
            background: #1e3a5f;
            color: #ffffff !important;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 32px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">

        @php
            $statusLabels = [
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'revision' => 'Perlu Revisi',
                'submitted' => 'Berhasil Dikirim',
                'progress' => 'Progres Approval',
                'fully_approved' => 'Semua Approval Selesai',
            ];
            $label = $statusLabels[$statusAction] ?? ucfirst($statusAction);
        @endphp

        <div class="header header-{{ $statusAction }}">
            <div class="badge">{{ $label }}</div>
            <h1>{{ $template->title }}</h1>
            <p>{{ $submission->submission_code }}</p>
        </div>

        <div class="body-content">
            <div class="greeting">
                Yth. <strong>{{ $submission->pemohon_nama }}</strong>,<br><br>

                @if($statusAction === 'submitted')
                    Dokumen Anda telah berhasil dikirim dan sedang menunggu approval.
                @elseif($statusAction === 'progress')
                    Dokumen Anda telah disetujui oleh <strong>{{ $approverName }}</strong> dan sedang menunggu approval tahap berikutnya.
                @elseif($statusAction === 'fully_approved')
                    Selamat! Seluruh proses approval dokumen Anda telah selesai. Dokumen telah disetujui oleh semua pihak.
                @elseif($statusAction === 'approved')
                    Dokumen Anda telah disetujui oleh <strong>{{ $approverName }}</strong>.
                @elseif($statusAction === 'rejected')
                    Mohon maaf, dokumen Anda telah ditolak oleh <strong>{{ $approverName }}</strong>.
                @elseif($statusAction === 'revision')
                    Dokumen Anda memerlukan revisi dari <strong>{{ $approverName }}</strong>.
                @endif
            </div>

            <table class="info-table">
                <tr>
                    <td>Nomor Dokumen</td>
                    <td>{{ $submission->submission_code }}</td>
                </tr>
                <tr>
                    <td>Judul</td>
                    <td>{{ $template->title }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><strong>{{ $label }}</strong></td>
                </tr>
                @if($approverName)
                <tr>
                    <td>Oleh</td>
                    <td>{{ $approverName }}</td>
                </tr>
                @endif
            </table>

            @if($comment)
                <div class="comment-box {{ $statusAction === 'rejected' ? 'rejected' : '' }}">
                    <strong>
                        @if($statusAction === 'rejected')
                            Alasan Penolakan:
                        @elseif($statusAction === 'revision')
                            Catatan Revisi:
                        @else
                            Komentar:
                        @endif
                    </strong>
                    {{ $comment }}
                </div>
            @endif
        </div>

        <div class="actions">
            <a href="{{ $pdfUrl }}" class="btn">📄 Lihat Dokumen PDF</a>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem Change Request PT Dahana.</p>
            <p>&copy; {{ date('Y') }} PT Dahana (Persero) - Sistem Teknologi Informasi</p>
        </div>

    </div>
</body>
</html>

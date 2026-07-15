<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Required</title>
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
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            padding: 32px 32px 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            margin: 0 0 6px;
            font-weight: 700;
        }
        .header p {
            color: #94a3b8;
            font-size: 13px;
            margin: 0;
        }
        .badge {
            display: inline-block;
            background: #f59e0b;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
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
            transition: all 0.2s;
        }
        .btn-primary {
            background: #1e3a5f;
            color: #ffffff !important;
        }
        .btn-approve {
            background: #059669;
            color: #ffffff !important;
        }
        .btn-revision {
            background: #d97706;
            color: #ffffff !important;
        }
        .btn-reject {
            background: #dc2626;
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

        <div class="header">
            <div class="badge">Menunggu Approval Anda</div>
            <h1>{{ $template->title }}</h1>
            <p>{{ $submission->submission_code }}</p>
        </div>

        <div class="body-content">
            <div class="greeting">
                Yth. <strong>{{ $approval->approver_name }}</strong>,<br><br>
                Sebuah dokumen membutuhkan persetujuan Anda. Berikut detail dokumen:
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
                    <td>Pengaju</td>
                    <td>{{ $submission->pemohon_nama }}</td>
                </tr>
                <tr>
                    <td>Jabatan Pengaju</td>
                    <td>{{ $submission->pemohon_jabatan }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>{{ $submission->pemohon_departemen }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pengajuan</td>
                    <td>{{ $submission->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <td>Step Approval</td>
                    <td>Step {{ $approval->step }}</td>
                </tr>
            </table>
        </div>

        <div class="actions">
            <a href="{{ $approvalUrl }}" class="btn btn-primary">
                📄 Lihat Dokumen & Review
            </a>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem Change Request PT Dahana.</p>
            <p>Silakan login ke sistem untuk melakukan approval.</p>
            <p>&copy; {{ date('Y') }} PT Dahana (Persero) - Sistem Teknologi Informasi</p>
        </div>

    </div>
</body>
</html>

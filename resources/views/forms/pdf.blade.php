<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Change_Request_{{ $submission->submission_code }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a1a1a;
            font-size: 10.5px;
            line-height: 1.4;
        }

        /* Table styles */
        table.border-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.border-table th,
        table.border-table td {
            border: 1px solid #111111;
            padding: 5px 8px;
            vertical-align: top;
            text-align: left;
        }

        .header-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            line-height: 1.3;
        }

        .section-header {
            font-weight: bold;
            font-size: 11px;
            margin-top: 14px;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #111111;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center !important;
        }

        .bg-slate {
            background-color: #f1f5f9;
        }

        .pre-wrap {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .checkbox-container {
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .checkbox-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #111111;
            text-align: center;
            line-height: 10px;
            font-size: 8px;
            font-weight: bold;
            margin-right: 4px;
            vertical-align: middle;
        }

        .checkbox-label {
            vertical-align: middle;
            font-weight: bold;
        }

        /* Signature Layout */
        .approval-table {
            width: 100%;
            border-collapse: collapse;
        }

        .approval-table td {
            border: 1px solid #111111;
            padding: 0;
            vertical-align: top;
        }

        .approval-title-bar {
            background-color: #f8fafc;
            border-bottom: 1px solid #111111;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            padding: 3px;
            text-transform: uppercase;
        }

        .approval-grid-cols {
            width: 100%;
            border-collapse: collapse;
        }

        .approval-grid-cols td {
            border: none;
            width: 50%;
            padding: 8px;
            text-align: center;
            height: 100px;
            vertical-align: space-between;
        }

        .approval-grid-cols td.border-right {
            border-right: 1px solid #111111;
        }

        .approval-role-title {
            font-size: 9px;
            font-weight: bold;
            display: block;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .approval-name {
            font-weight: bold;
            text-decoration: underline;
            display: block;
            margin-top: 15px;
        }

        .approval-subtitle {
            font-size: 8.5px;
            color: #4a5568;
            display: block;
        }

        .digital-sign {
            font-family: monospace;
            font-size: 8px;
            border: 1px dashed #718096;
            padding: 2px 4px;
            background-color: #f7fafc;
            color: #2b6cb0;
        }
    </style>
</head>

<body>

    <!-- Document Header Table (Exact image style) -->
    <table class="border-table">
        <tr>
            <!-- Logo area -->
            <td style="width: 22%; text-align: center; vertical-align: middle; padding: 10px; border-right: 1px solid #111111;">
                <img
                    src="{{ public_path('images/logo-dahana.png') }}"
                    alt="PT Dahana"
                    style="height: 50px; width: auto;">
            </td>
            <!-- Title -->
            <td style="width: 48%; text-align: center; vertical-align: middle; padding: 10px;">
                <div class="header-title">
                    {!! nl2br(e($template->title)) !!}
                </div>
            </td>
            <!-- Meta details -->
            <td style="width: 30%; padding: 0; font-size: 8.5px;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="border: none; border-bottom: 1px solid #111111; border-right: 1px solid #111111; padding: 3px 5px; width: 50%;">
                            <div style="color: #718096; font-weight: bold;">Author</div>
                            <div class="text-bold">{{ $template->author ?? '-' }}</div>
                        </td>
                        <td style="border: none; border-bottom: 1px solid #111111; padding: 3px 5px; width: 50%;">
                            <div style="color: #718096; font-weight: bold;">Tanggal Pembuatan</div>
                            <div class="text-bold">
                                {{ \Carbon\Carbon::parse($template->created_date)->translatedFormat('d F Y') }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; border-right: 1px solid #111111; padding: 3px 5px;">
                            <div style="color: #718096; font-weight: bold;">Status</div>
                            <div class="text-bold">{{ $template->status ?? 'Aktif' }}</div>
                        </td>
                        <td style="border: none; padding: 3px 5px;">
                            <div style="color: #718096; font-weight: bold;">Revisi</div>
                            <div class="text-bold">{{ $template->revision ?? '01' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Description -->
    @if($template->description)
    <div style="font-size: 9.5px; color: #4a5568; margin-bottom: 12px; font-style: italic; line-height: 1.3;">
        {{ $template->description }}
    </div>
    @endif

    <!-- Section 1: Identitas -->
    <div class="section-header">1. Identitas</div>
    <table class="border-table">
        <tr>
            <!-- Pemohon -->
            <td style="width: 50%; padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td colspan="2" style="border: none; border-bottom: 1px solid #111111; font-weight: bold; background-color: #f8fafc; padding: 4px 6px;">Pemohon</td>
                    </tr>
                    <tr>
                        <td style="border: none; width: 32%; padding: 4px 6px; color: #4a5568;">Nama</td>
                        <td style="border: none; padding: 4px 6px;" class="text-bold">: {{ $submission->pemohon_nama }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 6px; color: #4a5568;">Jabatan</td>
                        <td style="border: none; padding: 4px 6px;">: {{ $submission->pemohon_jabatan }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 6px; color: #4a5568;">Departemen</td>
                        <td style="border: none; padding: 4px 6px;">: {{ $submission->pemohon_departemen }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 6px; color: #4a5568;">Tgl Pengajuan</td>
                        <td style="border: none; padding: 4px 6px;">: {{ $submission->pemohon_tgl_pengajuan ? $submission->pemohon_tgl_pengajuan->format('d-m-Y') : '-' }}</td>
                    </tr>
                </table>
            </td>
            <!-- Peruntukan -->
            <td style="width: 50%; padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td colspan="2" style="border: none; border-bottom: 1px solid #111111; font-weight: bold; background-color: #f8fafc; padding: 4px 6px;">Peruntukan</td>
                    </tr>
                    <tr>
                        <td style="border: none; width: 32%; padding: 4px 6px; color: #4a5568;">Nama</td>
                        <td style="border: none; padding: 4px 6px;" class="text-bold">: {{ $submission->peruntukan_nama }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 6px; color: #4a5568;">Jabatan</td>
                        <td style="border: none; padding: 4px 6px;">: {{ $submission->peruntukan_jabatan }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 6px; color: #4a5568;">Departemen</td>
                        <td style="border: none; padding: 4px 6px;">: {{ $submission->peruntukan_departemen }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 6px; color: #4a5568;">SLA Deadline</td>
                        <td style="border: none; padding: 4px 6px;" class="text-bold">: {{ $submission->peruntukan_sla_deadline ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="font-size: 7.5px; color: #718096; margin-top: -8px; margin-bottom: 10px;">
        *) SLA Deadline diisi berdasarkan kesepakatan pemohon dan tim IT
    </div>

    <!-- Dynamic Sections & Fields -->
    @php
    $approvalFields = [];
    @endphp

    @foreach($template->sections as $sec)
    @if(str_contains(strtolower($sec->title), 'approval'))
    @php
    $approvalFields = $sec->fields;
    @endphp
    @continue
    @endif

    <div class="section-header">{{ $sec->title }}</div>

    <!-- Render Fields inside Section -->
    <table class="border-table">
        @foreach($sec->fields as $field)
        @php
        $val = $submission->getValueForField($field->id);
        @endphp

        <!-- Text or Textarea fields (Row with field label and description below it) -->
        @if(in_array($field->type, ['text', 'textarea']))
        <tr>
            <td class="bg-slate text-bold" style="width: 100%; border-bottom: none; font-size: 9.5px; padding: 4px 8px;">
                {{ $field->label }}
            </td>
        </tr>
        <tr>
            <td class="pre-wrap" style="width: 100; border-top: none; padding: 6px 8px 10px 8px;">{{ $val ?: '-' }}</td>
        </tr>

        <!-- Checkbox field (Centang tunggal) -->
        @elseif($field->type === 'checkbox')
        <tr>
            <td style="width: 100%; padding: 6px 8px;">
                <div class="checkbox-container">
                    <span class="checkbox-box">{{ $val ? '✓' : '' }}</span>
                    <span class="checkbox-label">{{ $field->label }}</span>
                </div>
            </td>
        </tr>

        <!-- Checkbox Group field -->
        @elseif($field->type === 'checkbox_group')
        <tr>
            <td style="width: 100%; padding: 6px 8px;">
                <span class="text-bold" style="font-size: 9.5px; display: block; margin-bottom: 4px;">{{ $field->label }} :</span>
                <div style="margin-left: 10px;">
                    @foreach($field->options as $opt)
                    @php
                    $checked = is_array($val) && in_array($opt, $val);
                    @endphp
                    <span style="display: inline-block; margin-right: 25px; margin-top: 2px; margin-bottom: 2px;">
                        <span class="checkbox-box">{{ $checked ? '✓' : '' }}</span>
                        <span class="checkbox-label" style="font-weight: normal; font-size: 10px;">{{ $opt }}</span>
                    </span>
                    @endforeach
                </div>
            </td>
        </tr>

        <!-- Table field (Biaya, Waktu, Lainnya) -->
        @elseif($field->type === 'table' && isset($field->config['columns']))
        <tr>
            <td style="width: 100%; padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <thead>
                        <tr class="bg-slate" style="font-weight: bold; font-size: 9.5px;">
                            <th style="border: none; border-bottom: 1px solid #111111; border-right: 1px solid #111111; width: 10%; text-align: center; padding: 4px;">Pilih</th>
                            <th style="border: none; border-bottom: 1px solid #111111; border-right: 1px solid #111111; width: 25%; padding: 4px 6px;">{{ $field->config['columns'][0] }}</th>
                            <th style="border: none; border-bottom: 1px solid #111111; padding: 4px 6px;">{{ $field->config['columns'][1] }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($field->config['rows'] as $row)
                        @php
                        $rowVal = $val[$row['id']] ?? null;
                        $checked = !empty($rowVal['checked']);
                        $text = $rowVal['text'] ?? '';
                        @endphp
                        <tr>
                            <td style="border: none; border-bottom: 1px solid #111111; border-right: 1px solid #111111; text-align: center; padding: 4px; vertical-align: middle;">
                                <span class="checkbox-box" style="margin-right: 0;">{{ $checked ? '✓' : '' }}</span>
                            </td>
                            <td style="border: none; border-bottom: 1px solid #111111; border-right: 1px solid #111111; font-weight: bold; padding: 4px 6px; vertical-align: middle;">
                                {{ $row['label'] }}
                            </td>
                            <td style="border: none; border-bottom: 1px solid #111111; padding: 4px 6px;">
                                {{ $text ?: '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @endif
        @endforeach
    </table>
    @endforeach

    <!-- Section Approval -->
@if($submission->approvals->count())

<div class="section-header">
    Approval
</div>

<table class="border-table">

    <tr class="bg-slate">

        <th>Jabatan</th>

        <th>Nama</th>

        <th>Status</th>

        <th>Tanggal</th>

        <th>Tanda Tangan</th>

    </tr>

@foreach($submission->approvals as $approval)

<tr>

    <td width="22%">
        {{ $approval->approver_position }}
    </td>

    <td width="20%">
        {{ $approval->approver_name }}
    </td>

    <td width="15%">
        {{ ucfirst($approval->status) }}
    </td>

    <td width="20%">

        @if($approval->acted_at)

            {{ $approval->acted_at->format('d-m-Y H:i') }}

        @else

            -

        @endif

    </td>

    <td width="23%" class="text-center">

        @if(
            $approval->status == 'approved'
            && $approval->approverUser
            && $approval->approverUser->signature
        )

            <img
                src="{{ public_path('storage/'.$approval->approverUser->signature) }}"
                style="height:70px;">
        @else
            ------------------------
        @endif
    </td>
</tr>

@endforeach
</table>
@endif
</body>

</html>
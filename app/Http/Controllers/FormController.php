<?php

namespace App\Http\Controllers;

use App\Models\TemplateApproval;
use App\Models\DocumentApproval;
use App\Models\FormTemplate;
use App\Models\FormSubmission;
use App\Models\SubmissionValue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalRequestMail;
use App\Mail\ApprovalStatusMail;

class FormController extends Controller
{
    /**
     * Display landing page with active templates.
     */
    public function index()
    {
        $templates = FormTemplate::where('is_active', true)->get();

        $mySubmissions = collect();
        $totalMySubmissions = 0;

        if (auth()->check()) {
            $mySubmissions = FormSubmission::with([
                'template',
                'user',
                'approvals' => fn($q) => $q->orderBy('step'),
            ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

            $totalMySubmissions = $mySubmissions->count();
        }

        return view('welcome', compact(
            'templates',
            'mySubmissions',
            'totalMySubmissions'
        ));
    }

    /**
     * Display the form to fill.
     */
    public function fill(FormTemplate $template)
    {
        if (!$template->is_active) {
            abort(404, 'Form template tidak aktif atau tidak ditemukan.');
        }

        $template->load('sections.fields');

        $users = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('forms.fill', compact(
            'template',
            'users'
        ));
    }

    /**
     * Handle the form submission.
     */
    public function store(FormTemplate $template, Request $request)
    {
        if (!$template->is_active) {
            abort(404);
        }

        // Validate static Identity fields (Section 1)
        $rules = [
            'pemohon_nama' => 'required|string|max:255',
            'pemohon_jabatan' => 'required|string|max:255',
            'pemohon_departemen' => 'required|string|max:255',
            'pemohon_tgl_pengajuan' => 'required|date',
            'peruntukan_nama' => 'required|string|max:255',
            'peruntukan_jabatan' => 'required|string|max:255',
            'peruntukan_departemen' => 'required|string|max:255',
            'peruntukan_sla_deadline' => 'nullable|string|max:255',
        ];

        // Dynamic validation rules
        $template->load('sections.fields');
foreach ($template->sections as $section) {

    // Skip section Approval karena diisi melalui workflow
    if (str_contains(strtolower($section->title), 'approval')) {
        continue;
    }

    foreach ($section->fields as $field) {
                $fieldName = 'fields.' . $field->id;

                if ($field->is_required) {
                    if ($field->type === 'checkbox_group') {
                        $rules[$fieldName] = 'required|array|min:1';
                    } elseif ($field->type === 'table') {
                        $rules[$fieldName] = 'required|array';
                    } else {
                        $rules[$fieldName] = 'required|string';
                    }
                } else {
                    $rules[$fieldName] = 'nullable';
                }
            }
        }

        // Custom validation messages
        $messages = [
            'required' => ':attribute wajib diisi.',
            'fields.*.required' => 'Kolom ini wajib diisi.',
            'fields.*.array' => 'Format kolom ini harus berupa pilihan.',
            'fields.*.min' => 'Pilih minimal satu opsi.',
        ];

        // Set readable names for attributes
        $attributes = [
            'pemohon_nama' => 'Nama Pemohon',
            'pemohon_jabatan' => 'Jabatan Pemohon',
            'pemohon_departemen' => 'Departemen Pemohon',
            'pemohon_tgl_pengajuan' => 'Tanggal Pengajuan',
            'peruntukan_nama' => 'Nama Peruntukan',
            'peruntukan_jabatan' => 'Jabatan Peruntukan',
            'peruntukan_departemen' => 'Departemen Peruntukan',
            'peruntukan_sla_deadline' => 'SLA Deadline',
        ];

        foreach ($template->sections as $section) {

            if (str_contains(strtolower($section->title), 'approval')) {
                continue;
            }

            foreach ($section->fields as $field) {
                $attributes['fields.' . $field->id] = $field->label;
            }
        }

        $validated = $request->validate($rules, $messages, $attributes);

        // Create the submission
        $submission = FormSubmission::create([
            'user_id' => auth()->id(),

            'form_template_id' => $template->id,
            'submission_code' => 'CR-' . strtoupper(Str::random(10)),
            'pemohon_nama' => $validated['pemohon_nama'],
            'pemohon_jabatan' => $validated['pemohon_jabatan'],
            'pemohon_departemen' => $validated['pemohon_departemen'],
            'pemohon_tgl_pengajuan' => $validated['pemohon_tgl_pengajuan'],
            'peruntukan_nama' => $validated['peruntukan_nama'],
            'peruntukan_jabatan' => $validated['peruntukan_jabatan'],
            'peruntukan_departemen' => $validated['peruntukan_departemen'],
            'peruntukan_sla_deadline' => $validated['peruntukan_sla_deadline'],
            'status'                  => 'submitted',
            'workflow_status'         => 'submitted',
            'current_step'            => 0,
        ]);

        // Save dynamic field values
        $inputFields = $request->input('fields', []);

        foreach ($template->sections as $section) {

            if (str_contains(strtolower($section->title), 'approval')) {
                continue;
            }

            foreach ($section->fields as $field) {
                $value = $inputFields[$field->id] ?? null;

                // Format values for storage
                if (is_array($value)) {
                    $value = json_encode($value);
                }

                SubmissionValue::create([
                    'form_submission_id' => $submission->id,
                    'template_field_id'  => $field->id,
                    'value'              => $value,
                ]);
            }
        }

        // Copy workflow approval dari seksi Approval di template
        $approvalSection = $template->sections
            ->first(fn($s) => str_contains(strtolower($s->title), 'approval'));
        
        $approvalFields = $approvalSection ? $approvalSection->fields()->orderBy('order')->get() : collect();

        Log::info('[FormController] Dynamic approval fields check', [
            'submission_id'  => $submission->id,
            'template_id'    => $template->id,
            'fields_count'   => $approvalFields->count(),
        ]);

        if ($approvalFields->isEmpty()) {
            // Tidak ada approval sama sekali → status langsung Completed/Approved
            $submission->update([
                'status'         => 'approved',
                'workflow_status'=> 'approved',
                'current_step'   => 0,
            ]);

            Log::info('[FormController] No approval fields found, submission completed immediately');
        } else {
            $step = 1;
            $firstPendingStep = null;
            
            foreach ($approvalFields as $field) {
                $config = $field->config;
                $jenisApproval = $config['jenis_approval'] ?? 'user_tertentu';
                $isPemohon = ($jenisApproval === 'pemohon');
                
                if ($isPemohon) {
                    $approverUserId = auth()->id();
                    $approverName   = auth()->user()->name;
                    $approverPosition = auth()->user()->position ?? 'Pemohon';
                    $approverEmail  = auth()->user()->email;
                } else {
                    $approverUserId = $config['approver_user_id'] ?? null;
                    $approverName   = $config['approver_name'] ?? $field->label;
                    $approverPosition = $config['approver_position'] ?? ($config['subtitle'] ?? $field->label);
                    $approverEmail  = $config['approver_email'] ?? null;
                }

                $payload = [
                    'submission_id'    => $submission->id,
                    'step'             => $step,
                    'approver_user_id' => $approverUserId,
                    'approver_name'    => $approverName,
                    'approver_position'=> $approverPosition,
                    'approver_email'   => $approverEmail,
                    'status'           => $isPemohon ? 'approved' : 'pending',
                    'approved_by'      => $isPemohon ? auth()->id() : null,
                    'acted_at'         => $isPemohon ? now() : null,
                ];

                Log::info('[FormController] Creating DocumentApproval for step ' . $step, $payload);
                DocumentApproval::create($payload);

                if (!$isPemohon && $firstPendingStep === null) {
                    $firstPendingStep = $step;
                }

                $step++;
            }

            if ($firstPendingStep === null) {
                // Semua langkah sudah ter-approve otomatis (misal semua langkah adalah 'pemohon')
                $submission->update([
                    'status'         => 'approved',
                    'workflow_status'=> 'approved',
                    'current_step'   => 0,
                ]);
            } else {
                // Atur ke step pending pertama
                $submission->update([
                    'current_step'    => $firstPendingStep,
                    'workflow_status' => 'waiting',
                    'status'          => 'submitted',
                ]);

                // Kirim email ke approver pertama yang pending
                $firstPendingApproval = $submission->approvals()->where('step', $firstPendingStep)->first();
                if ($firstPendingApproval && $firstPendingApproval->approver_email) {
                    try {
                        Log::info('Mengirim email approval', [
                        'to' => $firstPendingApproval->approver_email,
                        'submission' => $submission->submission_code,
                    ]);
                        Mail::to($firstPendingApproval->approver_email)->send(new ApprovalRequestMail($submission, $firstPendingApproval));

                        Log::info('Email approval berhasil dikirim');
                    } catch (\Exception $e) {
                        Log::error('Gagal mengirim email ke approver pending pertama: ' . $e->getMessage());
                    }
                }
            }

            // Kirim notifikasi email ke pengaju
            if (auth()->user()->email) {
                try {
                    Mail::to(auth()->user()->email)->send(new ApprovalStatusMail($submission, 'submitted'));
                } catch (\Exception $e) {
                    logger()->error('Gagal mengirim email progress submit ke pengaju: ' . $e->getMessage());
                }
            }
        }

return redirect()->route('form.success', $submission->submission_code)
    ->with('success', 'Formulir Change Request berhasil dikirim!');
    }

    /**
     * Display success page.
     */
    public function success($code)
    {
        $submission = FormSubmission::with('template')
            ->where('submission_code', $code)
            ->firstOrFail();

        return view('forms.success', compact('submission'));
    }
}

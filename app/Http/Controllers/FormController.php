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

        $mySubmissions = [];

        if (auth()->check()) {
            $mySubmissions = FormSubmission::with([
            'template',
            'approvals',
            'user',
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
        }

        return view('welcome', compact('templates', 'mySubmissions'));
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
            'status' => 'submitted',
            'workflow_status' => 'submitted',
            'current_step' => 1,
        ]);

        // Save dynamic field values
        $inputFields = $request->input('fields', []);

        foreach ($template->sections as $section) {
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

        // Copy workflow approval dari template
        $template->load('approvalWorkflow.approver');
        $workflows = $template->approvalWorkflow;

        if ($workflows->isEmpty()) {
            $submission->update([
                'status' => 'approved',
                'workflow_status' => 'approved',
                'current_step' => 0,
            ]);
        } else {
            foreach ($workflows as $workflow) {
                if (!$workflow->approver) {
                    continue;
                }

                DocumentApproval::create([
                    'submission_id'      => $submission->id,
                    'step'               => $workflow->step,
                    'approver_user_id'   => $workflow->approver->id,
                    'approver_name'      => $workflow->approver->name,
                    'approver_position'  => $workflow->approver->position,
                    'approver_email'     => $workflow->approver->email,
                    'status'             => 'pending',
                ]);
            }

            // Kirim email ke approver pertama (step 1)
            $firstApproval = $submission->approvals()->where('step', 1)->first();
            if ($firstApproval && $firstApproval->approver_email) {
                try {
                    Mail::to($firstApproval->approver_email)->send(new ApprovalRequestMail($submission, $firstApproval));
                } catch (\Exception $e) {
                    logger()->error('Gagal mengirim email approval pertama: ' . $e->getMessage());
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

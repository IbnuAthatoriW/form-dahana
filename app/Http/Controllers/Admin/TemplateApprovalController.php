<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormTemplate;
use App\Models\TemplateApproval;
use App\Models\User;
use Illuminate\Http\Request;

class TemplateApprovalController extends Controller
{
    /**
     * Halaman workflow template
     */
    public function edit(FormTemplate $template)
    {
        $template->load('approvalWorkflow.approver');

        $users = User::orderBy('name')->get();

        return view(
            'admin.templates.workflow',
            compact('template', 'users')
        );
    }

    /**
     * Simpan workflow
     */
    public function update(Request $request, FormTemplate $template)
    {
        $template->approvalWorkflow()->delete();

        $approvers = $request->approver_user_id ?? [];

        $step = 1;

        foreach ($approvers as $userId) {

            if (!$userId) {
                continue;
            }

            TemplateApproval::create([

                'form_template_id' => $template->id,

                'step' => $step,

                'approver_user_id' => $userId,

            ]);

            $step++;
        }

        return redirect()
            ->route('admin.templates.workflow', $template)
            ->with('success', 'Workflow berhasil disimpan.');
    }
}
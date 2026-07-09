<?php

namespace App\Http\Controllers;

use App\Models\FormTemplate;
use App\Models\TemplateSection;
use App\Models\TemplateField;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class AdminTemplateController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        $templatesCount = FormTemplate::count();
        $submissionsCount = FormSubmission::count();
        $recentSubmissions = FormSubmission::with('template')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('templatesCount', 'submissionsCount', 'recentSubmissions'));
    }

    /**
     * Display list of form templates.
     */
    public function index()
    {
        $templates = FormTemplate::withCount('submissions')->get();
        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show create template form.
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store new template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
            'status' => 'required|string',
            'revision' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $template = FormTemplate::create($validated + ['is_active' => true]);

        return redirect()->route('admin.templates.edit', $template->id)
            ->with('success', 'Template berhasil dibuat! Sekarang Anda dapat menyusun kolom form.');
    }

    /**
     * Show template editor / builder.
     */
    public function edit(FormTemplate $template)
    {
        $template->load('sections.fields');
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update template metadata.
     */
    public function update(FormTemplate $template, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
            'status' => 'required|string',
            'revision' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $template->update($validated + ['is_active' => $request->has('is_active')]);

        return back()->with('success', 'Metadata template berhasil diperbarui.');
    }

    /**
     * Delete template.
     */
    public function destroy(FormTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Add section to template.
     */
    public function addSection(FormTemplate $template, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxOrder = $template->sections()->max('order') ?? 0;

        $section = $template->sections()->create([
            'title' => $validated['title'],
            'order' => $maxOrder + 1,
            'is_static' => false,
        ]);

        return back()->with('success', "Bagian (Section) '{$section->title}' berhasil ditambahkan.");
    }

    /**
     * Update section title or order.
     */
    public function updateSection(TemplateSection $section, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $section->update($validated);

        return back()->with('success', 'Bagian (Section) berhasil diperbarui.');
    }

    /**
     * Delete section.
     */
    public function destroySection(TemplateSection $section)
    {
        $section->delete();
        return back()->with('success', 'Bagian (Section) berhasil dihapus.');
    }

    /**
     * Add field to section.
     */
    public function addField(TemplateSection $section, Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,checkbox,checkbox_group,table',
        ]);

        $maxOrder = $section->fields()->max('order') ?? 0;

        // Default config/options based on type
        $options = null;
        $config = null;

        if ($validated['type'] === 'checkbox_group') {
            $options = ['Opsi 1', 'Opsi 2'];
        } elseif ($validated['type'] === 'table') {
            $config = [
                'columns' => ['Kolom 1', 'Kolom 2'],
                'rows' => [
                    ['id' => 'row_1', 'label' => 'Baris 1'],
                    ['id' => 'row_2', 'label' => 'Baris 2']
                ]
            ];
        }

        $field = $section->fields()->create([
            'label' => $validated['label'],
            'type' => $validated['type'],
            'options' => $options,
            'config' => $config,
            'is_required' => false,
            'order' => $maxOrder + 1,
        ]);

        return back()->with('success', "Kolom '{$field->label}' berhasil ditambahkan.");
    }

    /**
     * Update template field.
     */
    public function updateField(TemplateField $field, Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,checkbox,checkbox_group,table',
            'options' => 'nullable|string', // Comma separated values for checkbox group options
            'is_required' => 'nullable',
            'order' => 'nullable|integer',
            'table_columns' => 'nullable|string', // Comma separated for table columns
            'table_rows' => 'nullable|string', // Comma separated for table rows
        ]);

        $options = null;
        if ($validated['type'] === 'checkbox_group' && !empty($validated['options'])) {
            $options = array_map('trim', explode(',', $validated['options']));
        }

        $config = $field->config;
        if ($validated['type'] === 'table') {
            $cols = !empty($validated['table_columns']) ? array_map('trim', explode(',', $validated['table_columns'])) : ['Kolom 1', 'Kolom 2'];
            $rowsRaw = !empty($validated['table_rows']) ? array_map('trim', explode(',', $validated['table_rows'])) : ['Baris 1', 'Baris 2'];

            $rows = [];
            foreach ($rowsRaw as $index => $r) {
                $rows[] = [
                    'id' => 'row_' . ($index + 1),
                    'label' => $r
                ];
            }

            $config = [
                'columns' => $cols,
                'rows' => $rows
            ];
        }

        // Handle approval fields configs if already defined
        if (isset($config['group'])) {
            $config['subtitle'] = $request->input('subtitle', $config['subtitle'] ?? '');
            $config['group'] = $request->input('group', $config['group'] ?? '');
            $config['position'] = $request->input('position', $config['position'] ?? 'left');
        }

        $field->update([
            'label' => $validated['label'],
            'type' => $validated['type'],
            'options' => $options,
            'is_required' => $request->has('is_required'),
            'order' => $validated['order'] ?? $field->order,
            'config' => $config,
        ]);

        return back()->with('success', 'Kolom berhasil diperbarui.');
    }

    /**
     * Delete field.
     */
    public function destroyField(TemplateField $field)
    {
        $field->delete();
        return back()->with('success', 'Kolom berhasil dihapus.');
    }

    /**
     * List all form submissions.
     */
    public function submissions(Request $request)
    {
        $query = FormSubmission::with('template');

        if ($request->has('template_id')) {
            $query->where('form_template_id', $request->template_id);
        }

        $submissions = $query->latest()->get();
        $templates = FormTemplate::all();

        return view('admin.submissions.index', compact('submissions', 'templates'));
    }

    /**
     * Show detail of a single submission.
     */
    public function submissionShow(FormSubmission $submission)
    {
        $submission->load('template.sections.fields', 'values.field');
        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Delete submission.
     */
    public function submissionDestroy(FormSubmission $submission)
    {
        $submission->delete();
        return redirect()->route('admin.submissions.index')->with('success', 'Data pengajuan berhasil dihapus.');
    }
}

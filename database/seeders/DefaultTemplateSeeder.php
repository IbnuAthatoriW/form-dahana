<?php

namespace Database\Seeders;

use App\Models\FormTemplate;
use App\Models\TemplateSection;
use App\Models\TemplateField;
use Illuminate\Database\Seeder;

class DefaultTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the template
        $template = FormTemplate::create([
            'title' => 'Form Pengajuan Change Request Infrastructure',
            'author' => 'Sistem Teknologi Informasi',
            'created_date' => '2025-09-15',
            'status' => 'Aktif',
            'revision' => '01',
            'description' => 'Untuk membantu kami memenuhi kebutuhan perubahan, mohon untuk memberikan informasi secara detail mengenai perubahan yang akan dilakukan dengan mengisi formulir berikut :',
            'is_active' => true,
        ]);

        // 2. Section 2: Detail Perubahan
        $sectionDetail = TemplateSection::create([
            'form_template_id' => $template->id,
            'title' => '2. Detail Perubahan',
            'order' => 1,
            'is_static' => false,
        ]);

        TemplateField::create([
            'template_section_id' => $sectionDetail->id,
            'label' => 'Prioritas Perubahan',
            'type' => 'checkbox_group',
            'options' => ['High', 'Medium', 'Low'],
            'is_required' => true,
            'order' => 1,
        ]);

        TemplateField::create([
            'template_section_id' => $sectionDetail->id,
            'label' => 'Deskripsi Perubahan',
            'type' => 'textarea',
            'is_required' => true,
            'order' => 2,
            'config' => ['placeholder' => "1. Permintaan pembuatan Virtual Machine (VM) untuk Website Peraturan Perusahaan pada Server Production.\n2. Melakukan deployment Website Peraturan Perusahaan ke Server Production.\n3. Melakukan konfigurasi dan publikasi Website Peraturan Perusahaan agar dapat diakses oleh pengguna."],
        ]);

        TemplateField::create([
            'template_section_id' => $sectionDetail->id,
            'label' => 'Alasan Perubahan',
            'type' => 'textarea',
            'is_required' => true,
            'order' => 3,
            'config' => ['placeholder' => 'Server development tidak cocok untuk digunakan sebagai server Production'],
        ]);

        TemplateField::create([
            'template_section_id' => $sectionDetail->id,
            'label' => 'Dampak Jika Tidak dilakukan Perubahan',
            'type' => 'textarea',
            'is_required' => true,
            'order' => 4,
            'config' => ['placeholder' => 'Website Peraturan Perusahaan berpotensi mengalami gangguan layanan, penurunan performa, serta ketidakstabilan akses karena masih berjalan pada server Development yang tidak dirancang untuk kebutuhan operasional Production.'],
        ]);

        // 3. Rollback Plan Section
        $sectionRollback = TemplateSection::create([
            'form_template_id' => $template->id,
            'title' => 'Rollback Plan/ Rencana Pemulihan Perubahan',
            'order' => 2,
            'is_static' => false,
        ]);

        TemplateField::create([
            'template_section_id' => $sectionRollback->id,
            'label' => 'Rencana Pemulihan',
            'type' => 'textarea',
            'is_required' => true,
            'order' => 1,
            'config' => ['placeholder' => 'Jika terjadi kegagalan atau gangguan layanan setelah migrasi ke server Production, maka website akan dikembalikan ke server Development sebagai server sebelumnya, kemudian dilakukan pengecekan konfigurasi aplikasi, file, dan koneksi database hingga layanan dapat berjalan normal kembali.'],
        ]);

        // 4. Section 3: Dampak Perubahan
        $sectionDampak = TemplateSection::create([
            'form_template_id' => $template->id,
            'title' => '3. Dampak Perubahan',
            'order' => 3,
            'is_static' => false,
        ]);

        TemplateField::create([
            'template_section_id' => $sectionDampak->id,
            'label' => 'Dampak Perubahan',
            'type' => 'table',
            'is_required' => true,
            'order' => 1,
            'config' => [
                'columns' => ['Dampak', 'Penjelasan Dampak'],
                'rows' => [
                    ['id' => 'biaya', 'label' => 'Biaya'],
                    ['id' => 'waktu', 'label' => 'Waktu'],
                    ['id' => 'lainnya', 'label' => 'Lainnya']
                ]
            ],
        ]);

        // 5. Section 4: Approval
        $sectionApproval = TemplateSection::create([
            'form_template_id' => $template->id,
            'title' => '4. Approval',
            'order' => 4,
            'is_static' => false,
        ]);

        TemplateField::create([
            'template_section_id' => $sectionApproval->id,
            'label' => 'Diajukan oleh:',
            'type' => 'text',
            'is_required' => true,
            'order' => 1,
            'config' => [
                'group' => 'Pemohon',
                'position' => 'left',
                'subtitle' => 'Pemohon'
            ],
        ]);

        TemplateField::create([
            'template_section_id' => $sectionApproval->id,
            'label' => 'Mengetahui:',
            'type' => 'text',
            'is_required' => true,
            'order' => 2,
            'config' => [
                'group' => 'Pemohon',
                'position' => 'right',
                'subtitle' => 'Manager Pengembangan Aplikasi & Tata Kelola'
            ],
        ]);

        TemplateField::create([
            'template_section_id' => $sectionApproval->id,
            'label' => 'Diterima oleh:',
            'type' => 'text',
            'is_required' => true,
            'order' => 3,
            'config' => [
                'group' => 'Penerima (Tim IT)',
                'position' => 'left',
                'subtitle' => 'Penerima (Tim IT)'
            ],
        ]);

        TemplateField::create([
            'template_section_id' => $sectionApproval->id,
            'label' => 'Diketahui oleh:',
            'type' => 'text',
            'is_required' => true,
            'order' => 4,
            'config' => [
                'group' => 'Penerima (Tim IT)',
                'position' => 'right',
                'subtitle' => 'Manager Operasional dan Infrastruktur IT'
            ],
        ]);

        TemplateField::create([
            'template_section_id' => $sectionApproval->id,
            'label' => 'Disetujui oleh:',
            'type' => 'text',
            'is_required' => true,
            'order' => 5,
            'config' => [
                'group' => 'Disetujui oleh:',
                'position' => 'center',
                'subtitle' => 'SM Sistem Teknologi Informasi'
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\PublicationReport;
use Illuminate\Database\Seeder;

class PublicationReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            [
                'title' => 'Laporan Publikasi 30 September 2024',
                'category' => 'TRIWULAN III - 2024',
                'category_color' => 'blue',
                'description' => 'Neraca keuangan triwulanan posisi September 2024 terverifikasi OJK.',
                'file_size' => '1.8 MB',
                'period' => 'Triwulan III 2024',
                'file_path' => '/assets/docs/laporan-publikasi-q3-2024.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Laporan Publikasi 30 Juni 2024',
                'category' => 'TRIWULAN II - 2024',
                'category_color' => 'blue',
                'description' => 'Laporan posisi keuangan semester I 2024 dan rasio kecukupan modal BPR.',
                'file_size' => '1.6 MB',
                'period' => 'Triwulan II 2024',
                'file_path' => '/assets/docs/laporan-publikasi-q2-2024.pdf',
                'sort_order' => 2,
            ],
            [
                'title' => 'Laporan Penerapan GCG 2023',
                'category' => 'TATA KELOLA (GCG)',
                'category_color' => 'emerald',
                'description' => 'Transparansi pelaksanaan GCG, kepatuhan, serta manajemen risiko direksi & dewan pengawas.',
                'file_size' => '3.2 MB',
                'period' => 'Tahun 2023',
                'file_path' => '/assets/docs/laporan-gcg-2023.pdf',
                'sort_order' => 3,
            ],
            [
                'title' => 'Laporan Tahunan Audited 2023',
                'category' => 'AUDIT TAHUNAN',
                'category_color' => 'amber',
                'description' => 'Laporan keuangan audited oleh Kantor Akuntan Publik (KAP) independen.',
                'file_size' => '4.5 MB',
                'period' => 'Tahun 2023',
                'file_path' => '/assets/docs/laporan-audited-2023.pdf',
                'sort_order' => 4,
            ],
            [
                'title' => 'Rencana Aksi Keuangan Berkelanjutan (RAKB)',
                'category' => 'KEUANGAN BERKELANJUTAN',
                'category_color' => 'purple',
                'description' => 'Penerapan prinsip keberlanjutan ekonomi, sosial, dan lingkungan pembangunan Majalengka.',
                'file_size' => '2.1 MB',
                'period' => 'Tahun 2024 - 2025',
                'file_path' => '/assets/docs/laporan-rakb-2024.pdf',
                'sort_order' => 5,
            ],
            [
                'title' => 'Piagam Audit Internal (Audit Charter)',
                'category' => 'PENGAWASAN INTERNAL',
                'category_color' => 'slate',
                'description' => 'Standar dan pedoman kerja satuan pengawasan internal sesuai regulasi OJK.',
                'file_size' => '1.2 MB',
                'period' => 'Edisi 2024',
                'file_path' => '/assets/docs/piagam-audit-internal.pdf',
                'sort_order' => 6,
            ],
        ];

        foreach ($reports as $report) {
            PublicationReport::updateOrCreate(
                ['title' => $report['title']],
                $report
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General & Profil
            ['key' => 'app_name', 'value' => 'Bank Majalengka', 'group' => 'general', 'label' => 'Nama Aplikasi'],
            ['key' => 'company_name', 'value' => 'Perumda BPR Majalengka', 'group' => 'general', 'label' => 'Nama Badan Usaha'],
            ['key' => 'company_tagline', 'value' => 'Mitra Kerja Membangun Ekonomi Rakyat', 'group' => 'general', 'label' => 'Tagline Perusahaan'],
            ['key' => 'hero_headline', 'value' => 'Langkah kecil hari ini. Masa depan lebih berarti.', 'group' => 'general', 'label' => 'Headline Hero Utama'],
            ['key' => 'hero_subheadline', 'value' => 'Mitra Keuangan Tepercaya Kebanggaan Masyarakat Majalengka. Simpanan dijamin LPS hingga Rp 2 Miliar dan diawasi oleh OJK.', 'group' => 'general', 'label' => 'Subheadline Hero'],
            ['key' => 'about_title', 'value' => 'BUMD Kebanggaan Masyarakat Majalengka', 'group' => 'profile', 'label' => 'Judul Tentang Kami'],
            ['key' => 'about_description', 'value' => 'Perusahaan Umum Daerah (Perumda) Bank Perekonomian Rakyat Majalengka merupakan Badan Usaha Milik Daerah yang 100% kepemilikannya dimiliki oleh Pemerintah Kabupaten Majalengka. Berdiri sebagai pilar penggerak ekonomi kerakyatan dengan menghimpun simpanan masyarakat dan menyalurkannya kembali dalam bentuk permodalan usaha bagi pelaku UMKM lokal.', 'group' => 'profile', 'label' => 'Deskripsi Profil BPR'],
            ['key' => 'sejarah_singkat', 'value' => 'Sesuai regulasi Otoritas Jasa Keuangan (OJK) dan komitmen Pemkab Majalengka untuk memperkuat permodalan serta tata kelola, Bank Majalengka merupakan hasil penggabungan (merger) 8 BPR berintegritas di Kabupaten Majalengka. Penyatuan ini melahirkan institusi keuangan yang jauh lebih kokoh, profesional, efisien, dan memiliki daya jangkau yang luas melalui 12 kantor kas aktif.', 'group' => 'profile', 'label' => 'Sejarah Singkat Merger'],
            ['key' => 'vision', 'value' => 'Menjadi Bank Perekonomian Rakyat yang terpercaya, sehat, mandiri, dan berdaya saing tinggi dalam mendorong pertumbuhan ekonomi daerah Majalengka.', 'group' => 'profile', 'label' => 'Visi Perusahaan'],
            ['key' => 'mission', 'value' => 'Menyelenggarakan pelayanan perbankan yang prima, memberdayakan UMKM pedesaan, serta memberikan kontribusi nyata bagi Pendapatan Asli Daerah (PAD).', 'group' => 'profile', 'label' => 'Misi Perusahaan'],
            
            // Kontak & Operasional
            ['key' => 'address_central', 'value' => 'Jl. Raya K.H. Abdul Halim No. 338, Majalengka Wetan, Kec. Majalengka, Kabupaten Majalengka, Jawa Barat 45411', 'group' => 'contact', 'label' => 'Alamat Kantor Pusat'],
            ['key' => 'phone_central', 'value' => '(0233) 281144', 'group' => 'contact', 'label' => 'Telepon Kantor Pusat'],
            ['key' => 'whatsapp_official', 'value' => '0811-1111-6526', 'group' => 'contact', 'label' => 'WhatsApp Layanan Nasabah'],
            ['key' => 'whatsapp_url', 'value' => 'https://wa.me/6281111116526', 'group' => 'contact', 'label' => 'Tautan WhatsApp'],
            ['key' => 'email_official', 'value' => 'kontak@bankmajalengka.co.id', 'group' => 'contact', 'label' => 'Email Resmi'],
            ['key' => 'operational_hours', 'value' => 'Senin - Jumat: 08:00 - 15:30 WIB', 'group' => 'contact', 'label' => 'Jam Operasional Layanan'],
            
            // Regulasi & Kepedulian
            ['key' => 'lps_guarantee_text', 'value' => 'Simpanan Anda dijamin oleh Lembaga Penjamin Simpanan (LPS) hingga Rp 2.000.000.000,- per nasabah.', 'group' => 'regulator', 'label' => 'Pernyataan Penjaminan LPS'],
            ['key' => 'ojk_supervision_text', 'value' => 'Perumda BPR Majalengka berizin dan diawasi oleh Otoritas Jasa Keuangan (OJK).', 'group' => 'regulator', 'label' => 'Pernyataan Pengawasan OJK'],
            ['key' => 'anti_fraud_notice', 'value' => 'WASPADA PENIPUAN: Petugas Bank Majalengka tidak pernah meminta kode OTP, PIN rekening, password, maupun transfer biaya verifikasi ke rekening perorangan pribadi.', 'group' => 'security', 'label' => 'Pita Peringatan Keamanan'],
            ['key' => 'wbs_email', 'value' => 'wbs@bankmajalengka.co.id', 'group' => 'security', 'label' => 'Whistleblowing System Email'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

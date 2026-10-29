<?php

namespace Database\Seeders;

use App\Models\LoanApplication;
use Illuminate\Database\Seeder;

class LoanApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $samples = [
            [
                'reference_code' => 'BM-20261028-1001',
                'nik' => '3210123456780001',
                'nama' => 'Ahmad Suhendar',
                'alamat' => 'Blok Pon RT 02/01, Desa Kadipaten, Kec. Kadipaten, Majalengka',
                'hp' => '081234567890',
                'afiliasi' => 'KAS-KADIPATEN',
                'nominal' => 25000000,
                'tenor' => 24,
                'tujuan' => 'Penambahan persediaan toko sembako pasar',
                'sumber_pendapatan' => 'Usaha Dagang Sembako',
                'pendapatan_tetap' => 6500000,
                'pendapatan_lain' => '1500000',
                'deskripsi_lain' => 'Hasil kebun mangga gedong gincu',
                'agunan' => 'BPKB Sepeda Motor Honda Vario 160 Tahun 2023',
                'status' => 'pending',
                'notes' => 'Menunggu verifikasi lapangan tim analis kas Kadipaten.',
            ],
            [
                'reference_code' => 'BM-20261028-1002',
                'nik' => '3210156789010002',
                'nama' => 'Siti Nurhayati, S.Pd.',
                'alamat' => 'Jl. Siti Armilah No. 14, Majalengka Kulon',
                'hp' => '085712345678',
                'afiliasi' => 'PGRI-MAJALENGKA',
                'nominal' => 50000000,
                'tenor' => 36,
                'tujuan' => 'Renovasi hunian dan biaya kuliah anak',
                'sumber_pendapatan' => 'Gaji PNS & Tunjangan Sertifikasi Guru',
                'pendapatan_tetap' => 8200000,
                'pendapatan_lain' => '0',
                'deskripsi_lain' => '-',
                'agunan' => 'SK Sertifikasi Pendidik & Karpeg Taspen',
                'status' => 'approved',
                'notes' => 'Telah dihubungi, berkas lengkap dan disetujui komite pembiayaan.',
            ],
            [
                'reference_code' => 'BM-20261028-1003',
                'nik' => '3210081234560003',
                'nama' => 'Dadan Ramdani',
                'alamat' => 'Dusun Mekarsari RT 05/02, Jatiwangi',
                'hp' => '087812345678',
                'afiliasi' => 'UMKM-GENTENG',
                'nominal' => 10000000,
                'tenor' => 12,
                'tujuan' => 'Kredit PERAK untuk modal tungku pembakaran genteng',
                'sumber_pendapatan' => 'Pengrajin Genteng Jatiwangi',
                'pendapatan_tetap' => 4000000,
                'pendapatan_lain' => '500000',
                'deskripsi_lain' => 'Jasa angkut armada',
                'agunan' => 'BPKB Motor Honda Beat 2021',
                'status' => 'contacted',
                'notes' => 'Jadwal survei lokasi usaha besok pagi jam 10:00.',
            ],
        ];

        foreach ($samples as $sample) {
            LoanApplication::updateOrCreate(
                ['reference_code' => $sample['reference_code']],
                $sample
            );
        }
    }
}

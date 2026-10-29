<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'slug' => 'mulai-dana-darurat-dari-kebiasaan-sederhana-sehari-hari',
                'title' => 'Mulai Dana Darurat dari Kebiasaan Sederhana Sehari-hari',
                'tag' => 'Perencanaan Finansial',
                'tag_color' => 'blue',
                'image' => 'assets/img/perencanaan-keluarga.jpg',
                'excerpt' => 'Langkah awal yang realistis untuk memisahkan dana kebutuhan pokok dan cadangan darurat keluarga demi ketenangan hidup masa depan.',
                'content' => '<p class="mb-4">Banyak keluarga di Kabupaten Majalengka merasa bahwa membentuk dana darurat membutuhkan penghasilan dalam jumlah besar. Padahal, kunci utama pembentukan dana darurat bukanlah besarnya nominal sekaligus, melainkan konsistensi memisahkan porsi kecil di awal setiap kali menerima pemasukan.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">1. Pisahkan Rekening Kebutuhan dan Rekening Cadangan</h4>
<p class="mb-4">Mencampurkan dana belanja pasar harian dengan simpanan adalah pemicu utama dana darurat sering terpakai tanpa sadar. Dengan membuka rekening tabungan khusus seperti <strong>SIDASI (Simpanan Dana Siaga)</strong> di Bank Majalengka yang bebas potongan administrasi bulanan memberatkan, uang cadangan Anda tetap utuh dan bertumbuh dengan bunga harian.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">2. Mulai dari Aturan 10%</h4>
<p class="mb-4">Cukup sisihkan 10% dari penghasilan tetap atau keuntungan dagang Anda setiap minggu. Jika penghasilan mingguan Anda Rp 1.000.000,-, sisihkan Rp 100.000,- langsung ke rekening simpanan. Petugas Bank Majalengka bahkan menyediakan fasilitas <em>Pickup Service</em> jemput setoran langsung ke lokasi Anda sehingga menabung tidak merepotkan waktu bekerja.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">3. Dana Terjamin Aman di BPR</h4>
<p class="mb-4">Simpanan Anda di Perumda BPR Majalengka dijamin 100% oleh <strong>Lembaga Penjamin Simpanan (LPS)</strong> hingga Rp 2.000.000.000,- per nasabah. Ketenangan pikiran keluarga Anda adalah prioritas kami.</p>',
                'author' => 'Tim Literasi Finansial Bank Majalengka',
                'read_time' => '4 menit baca',
                'published_at' => '2026-10-24',
                'is_published' => true,
            ],
            [
                'slug' => 'pisahkan-uang-usaha-warung-dan-uang-dapur-pribadi',
                'title' => 'Pisahkan Uang Usaha Warung dan Uang Dapur Pribadi',
                'tag' => 'Panduan UMKM',
                'tag_color' => 'amber',
                'image' => 'assets/img/warung-sembako.jpg',
                'excerpt' => 'Pencatatan kas harian yang tertib menghindarkan pedagang kecil dari modal yang tergerus kebutuhan konsumtif.',
                'content' => '<p class="mb-4">Bagi para pemilik toko kelontong, pedagang pasar Kadipaten, dan warung makan di pelosok Majalengka, salah satu tantangan terbesar adalah menjaga modal usaha tidak tergerus kebutuhan dapur rumah tangga sendiri.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">1. Gaji Diri Sendiri Secara Tetap</h4>
<p class="mb-4">Tentukan nominal "gaji" mingguan atau bulanan yang wajar untuk kebutuhan rumah tangga Anda. Ambil hanya sejumlah tersebut dari keuntungan bersih toko, dan jangan mengambil uang kembalian di laci kasir secara acak untuk keperluan pribadi.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">2. Miliki Buku Kas Catatan Harian Sederhana</h4>
<p class="mb-4">Cukup sediakan buku tulis bertanggal dengan tiga kolom: Uang Masuk, Uang Keluar (Kulakan), dan Sisa Saldo Kas. Setiap malam sebelum menutup toko, luangkan 5 menit untuk mencocokkan uang fisik dengan catatan buku.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">3. Dukungan Modal Usaha BPR Majalengka</h4>
<p class="mb-4">Bila usaha warung Anda membutuhkan suntikan permodalan untuk memperbanyak stok dagangan grosir menjelang masa panen raya atau hari raya, Bank Majalengka menyediakan fasilitas <strong>Kredit Modal Kerja (KMK)</strong> dan program <strong>Kredit PERAK</strong> dengan angsuran ringan dan persyaratan yang sangat bersahabat.</p>',
                'author' => 'Divisi Kredit UMKM',
                'read_time' => '5 menit baca',
                'published_at' => '2026-10-18',
                'is_published' => true,
            ],
            [
                'slug' => 'kenali-pesan-mencurigakan-jaga-kerahasiaan-data-perbankan',
                'title' => 'Kenali Pesan Mencurigakan, Jaga Kerahasiaan Data Perbankan',
                'tag' => '#AwasModus',
                'tag_color' => 'red',
                'image' => 'assets/img/pensiunan-hp.jpg',
                'excerpt' => 'Petugas Bank Majalengka tidak pernah meminta kode OTP, PIN, password, atau biaya transfer ke rekening pribadi pegawai.',
                'content' => '<p class="mb-4">Seiring berkembangnya transaksi digital, modus penipuan berkedok undian berhadiah, undangan pernikahan digital (file APK), dan pesan WhatsApp palsu yang mengatasnamakan bank kian marak mengincar masyarakat.</p>
<h4 class="font-bold text-slate-800 text-lg mb-2">Prinsip Aman Transaksi Bank Majalengka:</h4>
<ul class="list-disc pl-5 mb-4 space-y-2">
  <li><strong>JANGAN PERNAH</strong> memberitahukan nomor PIN, password, atau kode OTP kepada siapa pun, termasuk oknum yang mengaku sebagai pegawai bank.</li>
  <li><strong>JANGAN TRANSFER</strong> dana biaya administrasi pinjaman atau undian ke rekening bank atas nama perorangan atau nomor rekening pribadi petugas. Seluruh transaksi resmi disetorkan langsung di loket kantor kas atau rekening resmi Perumda BPR Majalengka.</li>
  <li><strong>JANGAN KLIK</strong> tautan file unduhan dengan ekstensi <code>.APK</code> yang dikirimkan melalui nomor WhatsApp asing.</li>
</ul>
<p class="mb-4">Bila Anda mendapati aktivitas mencurigakan atau dihubungi nomor tidak dikenal, segera konfirmasi ke Call Center resmi Bank Majalengka di <strong>(0233) 281144</strong> atau WhatsApp resmi <strong>0811-1111-6526</strong>.</p>',
                'author' => 'Satuan Kerja Manajemen Risiko & Kepatuhan',
                'read_time' => '3 menit baca',
                'published_at' => '2026-10-10',
                'is_published' => true,
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}

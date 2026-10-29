/**
 * Bank Majalengka - Frontend Application Script
 * Mengacu pada Sketsa Mockup (13 Halaman) dan Figma Corporate UI Design
 */

document.addEventListener('DOMContentLoaded', () => {
  if (window.DYNAMIC_PRODUCTS) {
    if (window.DYNAMIC_PRODUCTS.tabungan) PRODUCTS_DATA.tabungan = window.DYNAMIC_PRODUCTS.tabungan;
    if (window.DYNAMIC_PRODUCTS.deposito) PRODUCTS_DATA.deposito = window.DYNAMIC_PRODUCTS.deposito;
    if (window.DYNAMIC_PRODUCTS.kredit) PRODUCTS_DATA.kredit = window.DYNAMIC_PRODUCTS.kredit;
  }
  if (window.DYNAMIC_BRANCHES && Array.isArray(window.DYNAMIC_BRANCHES)) {
    BRANCHES_DATA.length = 0;
    BRANCHES_DATA.push(...window.DYNAMIC_BRANCHES);
  }
  if (window.DYNAMIC_ARTICLES && Array.isArray(window.DYNAMIC_ARTICLES)) {
    ARTICLES_DATA.length = 0;
    ARTICLES_DATA.push(...window.DYNAMIC_ARTICLES);
  }

  initNavbar();
  initKolomTanya();
  initProductShowcase();
  initSimulator();
  initFormPengajuan();
  initProductModals();
  initNewsModal();
  initFAQ();
  initKantorKas();
  initLaporanPublikasi();
  initGlobalModals();
});

// Global Function Declarations & Exposures (Strictly matching Mockup Sketches and BCA Product Showcase)
function resolveProduct(product) {
  if (!product) return null;
  if (typeof product === 'object') return product;
  const searchKey = String(product).toLowerCase().trim();
  for (const cat of ['tabungan', 'deposito', 'kredit']) {
    const list = PRODUCTS_DATA[cat] || [];
    const found = list.find(p => {
      const codeMatch = p.code && p.code.toLowerCase() === searchKey;
      const idMatch = p.id && String(p.id).toLowerCase() === searchKey;
      const slugMatch = p.slug && p.slug.toLowerCase() === searchKey;
      const nameMatch = p.name && p.name.toLowerCase() === searchKey;
      return codeMatch || idMatch || slugMatch || nameMatch;
    });
    if (found) return found;
  }
  return null;
}

let currentCatalogCategory = 'all';

function showProductDetail(product) {
  const prod = resolveProduct(product);
  const detailModal = document.getElementById('modal-product-detail');
  if (!detailModal || !prod) return;

  const shortDesc = prod.shortDesc || prod.short_desc || '';
  const heroImage = prod.heroImage || prod.hero_image || 'assets/img/hero-batik.jpg';
  const ctaText = prod.ctaText || prod.cta_text || (prod.category === 'kredit' ? 'Form Pengajuan Kredit' : 'Buka Rekening via WhatsApp');
  const ctaType = prod.ctaType || prod.cta_type || (prod.category === 'kredit' ? 'form' : 'whatsapp');
  const ketentuanList = Array.isArray(prod.ketentuan) ? prod.ketentuan : [];
  const syaratList = Array.isArray(prod.syarat) ? prod.syarat : [];

  const tEl = document.getElementById('detail-product-title'); if (tEl) tEl.textContent = prod.name || '';
  const bEl = document.getElementById('detail-product-badge'); if (bEl) bEl.textContent = prod.badge || prod.code || '';
  const htEl = document.getElementById('detail-product-hero-title'); if (htEl) htEl.textContent = prod.name || '';
  const sEl = document.getElementById('detail-product-slogan'); if (sEl) sEl.textContent = prod.slogan || '';
  const dEl = document.getElementById('detail-product-short-desc'); if (dEl) dEl.textContent = shortDesc;
  const hiEl = document.getElementById('detail-product-hero-img'); if (hiEl) hiEl.src = heroImage;

  const defaultWa = '6281111116526';
  const waOfficial = (window.DYNAMIC_SETTINGS && window.DYNAMIC_SETTINGS.whatsapp_official ? window.DYNAMIC_SETTINGS.whatsapp_official : defaultWa).replace(/[^0-9]/g, '');

  const heroCta = document.getElementById('detail-product-hero-cta');
  if (heroCta) {
    if (prod.category === 'kredit' || ctaType === 'form' || ctaType === 'form_pengajuan') {
      heroCta.classList.remove('hidden');
      heroCta.innerHTML = `
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Form Pengajuan Kredit</span>
      `;
      heroCta.onclick = () => {
        detailModal.classList.remove('active');
        const catModal = document.getElementById('modal-product-catalog');
        if (catModal) catModal.classList.remove('active');
        openFormPengajuan(null, null, prod.name);
      };
    } else {
      heroCta.classList.remove('hidden');
      heroCta.innerHTML = `
        <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
        <span>Buka Rekening via WhatsApp</span>
      `;
      const waMsg = encodeURIComponent(`Halo Perumda BPR Majalengka, saya tertarik untuk membuka rekening / informasi produk ${prod.name} (${prod.code}). Mohon bantuannya.`);
      heroCta.onclick = () => {
        window.open(`https://wa.me/${waOfficial}?text=${waMsg}`, '_blank');
      };
    }
  }

  const ketContainer = document.getElementById('detail-product-ketentuan');
  if (ketContainer) {
    ketContainer.innerHTML = ketentuanList.map(item => `
      <li class="flex items-start gap-2.5 text-sm text-slate-700 leading-relaxed">
        <span class="w-1.5 h-1.5 rounded-full bg-bpr-blue mt-2 flex-shrink-0"></span>
        <span>${item}</span>
      </li>
    `).join('');
  }

  const syrContainer = document.getElementById('detail-product-syarat');
  if (syrContainer) {
    syrContainer.innerHTML = syaratList.map(item => `
      <li class="flex items-start gap-2.5 text-sm text-slate-700 leading-relaxed">
        <span class="w-1.5 h-1.5 rounded-full bg-bpr-gold mt-2 flex-shrink-0"></span>
        <span>${item}</span>
      </li>
    `).join('');
  }

  const ctaBtn = document.getElementById('detail-product-cta-btn');
  if (ctaBtn) {
    ctaBtn.textContent = ctaText;
    if (prod.category === 'kredit' || ctaType === 'form' || ctaType === 'form_pengajuan') {
      ctaBtn.onclick = () => {
        detailModal.classList.remove('active');
        const catModal = document.getElementById('modal-product-catalog');
        if (catModal) catModal.classList.remove('active');
        openFormPengajuan(null, null, prod.name);
      };
    } else {
      const waMsg = encodeURIComponent(`Halo Perumda BPR Majalengka, saya tertarik untuk membuka / konsultasi produk ${prod.name} (${prod.code}). Mohon info jadwal dan syarat pendaftarannya.`);
      ctaBtn.onclick = () => {
        window.open(`https://wa.me/${waOfficial}?text=${waMsg}`, '_blank');
      };
    }
  }

  detailModal.classList.add('active');
  document.body.classList.add('overflow-hidden');
}

function renderCatalogCards(category = 'all', filterText = '') {
  const container = document.getElementById('catalog-cards-container');
  if (!container) return;

  let allItems = [];
  if (category === 'all' || category === 'tabungan') allItems.push(...PRODUCTS_DATA.tabungan);
  if (category === 'all' || category === 'deposito') allItems.push(...PRODUCTS_DATA.deposito);
  if (category === 'all' || category === 'kredit') allItems.push(...PRODUCTS_DATA.kredit);

  if (filterText) {
    const q = filterText.toLowerCase();
    allItems = allItems.filter(item => 
      (item.name || '').toLowerCase().includes(q) || 
      ((item.shortDesc || item.short_desc) || '').toLowerCase().includes(q) ||
      (item.code || '').toLowerCase().includes(q)
    );
  }

  if (allItems.length === 0) {
    container.innerHTML = `
      <div class="col-span-full py-12 text-center text-slate-500">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-semibold">Produk tidak ditemukan</p>
        <p class="text-sm">Silakan ubah kata kunci pencarian atau ganti kategori produk.</p>
      </div>
    `;
    return;
  }

  container.innerHTML = allItems.map(prod => `
    <div onclick="window.catalogModalCardClick && window.catalogModalCardClick('${prod.code}')" class="bg-white rounded-2xl p-6 card-shadow card-shadow-hover flex flex-col justify-between cursor-pointer group">
      <div>
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold px-2.5 py-1 rounded-lg ${prod.category === 'tabungan' ? 'bg-blue-50 text-bpr-blue font-semibold' : prod.category === 'deposito' ? 'bg-amber-50 text-amber-700 font-semibold' : 'bg-emerald-50 text-emerald-700 font-semibold'}">${prod.badge || (prod.category === 'tabungan' ? 'Tabungan' : prod.category === 'deposito' ? 'Deposito' : 'Kredit')}</span>
          <span class="text-xs text-slate-400 font-mono">${prod.code}</span>
        </div>
        <div class="h-32 rounded-xl bg-slate-50 flex items-center justify-center p-3 mb-4 overflow-hidden group-hover:scale-[1.02] transition-transform">
          <img src="${prod.image}" alt="${prod.name}" class="max-h-full max-w-full object-contain" onerror="this.src='assets/img/logo.png'">
        </div>
        <h4 class="font-bold text-slate-900 group-hover:text-bpr-blue transition-colors text-base mb-1.5 leading-snug">${prod.name}</h4>
        <p class="text-slate-500 text-xs line-clamp-3 mb-4 leading-relaxed">${prod.shortDesc || prod.short_desc || ''}</p>
      </div>
      <div class="pt-3 mt-auto flex items-center justify-between">
        <span class="text-xs font-semibold text-bpr-blue group-hover:underline">
          Selengkapnya
        </span>
        <span class="text-xs text-slate-400 font-medium">BPR Majalengka</span>
      </div>
    </div>
  `).join('');
}

window.catalogModalCardClick = function(code) {
  const modal = document.getElementById('modal-product-catalog');
  if (modal) modal.classList.add('was-open');
  showProductDetail(code);
};

function openProductCatalog(category = 'all') {
  currentCatalogCategory = category;
  const modal = document.getElementById('modal-product-catalog');
  if (!modal) return;

  const tabButtons = document.querySelectorAll('.catalog-tab-btn');
  tabButtons.forEach(btn => {
    if (btn.dataset.category === category) {
      btn.classList.add('bg-bpr-navy', 'text-white');
      btn.classList.remove('bg-slate-100', 'text-slate-600');
    } else {
      btn.classList.remove('bg-bpr-navy', 'text-white');
      btn.classList.add('bg-slate-100', 'text-slate-600');
    }
  });

  const searchInput = document.getElementById('catalog-search-input');
  if (searchInput) searchInput.value = '';

  renderCatalogCards(category, '');

  modal.classList.add('active');
  document.body.classList.add('overflow-hidden');
}

function readArticle(id) {
  const modal = document.getElementById('modal-news-reader');
  if (!modal) return;
  const article = ARTICLES_DATA.find(a => a.id == id || a.slug === id);
  if (!article) return;

  const articleDate = article.date || (article.published_at ? new Date(article.published_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '');
  const articleReadTime = article.readTime || article.read_time || '3 menit baca';

  const tagEl = document.getElementById('news-modal-tag'); if (tagEl) tagEl.textContent = article.tag || article.category || 'Berita';
  const titleEl = document.getElementById('news-modal-title'); if (titleEl) titleEl.textContent = article.title;
  const dateEl = document.getElementById('news-modal-date'); if (dateEl) dateEl.textContent = `${articleDate} • ${articleReadTime}`;
  const imgEl = document.getElementById('news-modal-img'); if (imgEl) imgEl.src = article.image || 'assets/img/perencanaan-keluarga.jpg';
  const bodyEl = document.getElementById('news-modal-body'); if (bodyEl) bodyEl.innerHTML = article.content;

  modal.classList.add('active');
  document.body.classList.add('overflow-hidden');
}

function showAllArticles() {
  const modal = document.getElementById('modal-all-news');
  if (modal) {
    modal.classList.add('active');
    document.body.classList.add('overflow-hidden');
  }
}

function resetProductFilter() {
  if (typeof window.applyShowcaseCategoryTab === 'function') {
    window.applyShowcaseCategoryTab('all', true);
  }
}

function filterProductShowcase(category = 'all') {
  if (typeof window.applyShowcaseCategoryTab === 'function') {
    window.applyShowcaseCategoryTab(category);
  }
}

// Explicit top-level global exposures to guarantee availability across ES module scopes
window.openFormPengajuan = openFormPengajuan;
window.showPengajuanSuccessModal = showPengajuanSuccessModal;
window.openProfilBpr = openProfilBpr;
window.openContactOptions = openContactOptions;
window.openProductCatalog = openProductCatalog;
window.showProductDetail = showProductDetail;
window.readArticle = readArticle;
window.showAllArticles = showAllArticles;
window.resetProductFilter = resetProductFilter;
window.filterProductShowcase = filterProductShowcase;

/* ==========================================================================
   1. DATABASE PRODUK (TABUNGAN, DEPOSITO, KREDIT)
   ========================================================================== */
const PRODUCTS_DATA = {
  tabungan: [
    {
      id: 'tahara',
      code: 'TAB-01',
      name: 'Tabungan Hari Raya (TAHARA)',
      badge: 'Favorit Keluarga',
      category: 'tabungan',
      image: 'assets/img/produk/tahara.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Persiapan menyambut hari raya Idul Fitri yang tenang, khidmat, dan penuh berkah.',
      shortDesc: 'Tabungan berencana dengan setoran bulanan tetap untuk persiapan kebutuhan hari raya Idul Fitri keluarga, mudik, dan parcel lebaran tanpa membebani arus kas bulanan.',
      ketentuan: [
        'Jangka waktu penempatan 10 s/d 12 bulan (pencairan menjelang Idul Fitri).',
        'Setoran bulanan terjangkau mulai dari Rp 50.000,- hingga Rp 5.000.000,- per bulan.',
        'Suku bunga kompetitif di atas tabungan reguler dan bebas biaya administrasi bulanan.',
        'Dana tidak dapat ditarik sebelum jatuh tempo demi kedisiplinan rencana ibadah.',
        'Dijamin sepenuhnya oleh Lembaga Penjamin Simpanan (LPS) hingga Rp 2 Miliar.'
      ],
      syarat: [
        'Fotokopi KTP / Identitas diri pemohon yang masih berlaku (Majalengka/Jawa Barat).',
        'Mengisi dan menandatangani formulir pembukaan rekening TAHARA.',
        'Menyetorkan setoran awal paket pertama sesuai nominal rencana yang dipilih.',
        'Menyertakan nomor WhatsApp aktif untuk konfirmasi mutasi bulanan.'
      ],
      ctaText: 'Buka Rekening TAHARA',
      ctaType: 'whatsapp'
    },
    {
      id: 'tiara',
      code: 'TAB-02',
      name: 'Tabungan Idul Adha (TIARA)',
      badge: 'Ibadah Terencana',
      category: 'tabungan',
      image: 'assets/img/produk/design_tabungan_tapaqur-removebg-preview.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Wujudkan niat suci ibadah qurban setiap tahun dengan angsuran ringan teratur.',
      shortDesc: 'Fasilitas tabungan terencana bagi individu, keluarga, maupun jamaah masjid untuk pengadaan hewan qurban sapi atau kambing saat Idul Adha.',
      ketentuan: [
        'Jangka waktu simpanan fleksibel 12 bulan mengacu pada kalender Hijriah.',
        'Pilihan paket tabungan setara nilai 1 ekor kambing atau 1/7 bagian sapi.',
        'Bebas biaya pemeliharaan rekening bulanan.',
        'Dapat disalurkan langsung melalui panitia kurban mitra BPR Majalengka atau dicairkan tunai.',
        'Aman dan dijamin 100% oleh LPS.'
      ],
      syarat: [
        'Fotokopi KTP/e-KTP pemohon yang masih berlaku.',
        'Mengisi formulir permohonan pembukaan rekening TIARA.',
        'Setoran awal ringan mulai Rp 100.000,-.',
        'Kartu Keluarga (jika atas nama perwakilan keluarga).'
      ],
      ctaText: 'Daftar Tabungan TIARA',
      ctaType: 'whatsapp'
    },
    {
      id: 'kotak-mas',
      code: 'TAB-03',
      name: 'Tabungan KOTAK MAS',
      badge: 'Berhadiah Langsung',
      category: 'tabungan',
      image: 'assets/img/produk/IMG_2909.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Kotak masa depan sejahtera dengan bunga optimal dan bonus hadiah peralatan usaha.',
      shortDesc: 'Program tabungan spesial dengan saldo mengendap berhadiah langsung peralatan elektronik, peralatan usaha, atau logam mulia tanpa diundi.',
      ketentuan: [
        'Komitmen saldo mengendap dengan pilihan periode 1, 2, atau 3 tahun.',
        'Hadiah langsung diserahkan di awal pembukaan rekening sesuai tier saldo.',
        'Bunga tabungan tetap dibayarkan setiap bulan masuk ke rekening utama.',
        'Tersedia untuk nasabah perorangan maupun lembaga swasta daerah.',
        'Simpanan dijamin LPS sesuai ketentuan yang berlaku.'
      ],
      syarat: [
        'e-KTP asli & fotokopi pemohon.',
        'NPWP (apabila ada).',
        'Menandatangani surat perjanjian komitmen program KOTAK MAS.',
        'Penempatan saldo awal sesuai paket hadiah yang dipilih.'
      ],
      ctaText: 'Pilih Hadiah KOTAK MAS',
      ctaType: 'whatsapp'
    },
    {
      id: 'sidasi',
      code: 'TAB-04',
      name: 'Simpanan Dana Siaga (SIDASI)',
      badge: 'Fleksibel Likuid',
      category: 'tabungan',
      image: 'assets/img/produk/IMG_2911.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Dana darurat keluarga yang likuid, aman, dan siap ditarik kapan saja dibutuhkan.',
      shortDesc: 'Tabungan reguler serbaguna dengan perhitungan bunga harian. Setoran dan penarikan sangat mudah dilayani di seluruh 12 jaringan kantor kas dan via Pickup Service.',
      ketentuan: [
        'Bebas setor dan tarik setiap hari kerja di semua kantor kas BPR Majalengka.',
        'Tersedia layanan jemput tabungan langsung ke toko/rumah nasabah.',
        'Perhitungan bunga harian dihitung berdasarkan saldo harian efektif.',
        'Bebas biaya administrasi bulanan untuk saldo di atas ambang minimal.',
        'Dijamin LPS hingga Rp 2 Miliar.'
      ],
      syarat: [
        'Fotokopi KTP / SIM yang masih berlaku.',
        'Mengisi formulir pembukaan rekening perorangan.',
        'Setoran awal sangat ringan, mulai Rp 20.000,-.',
        'Setoran lanjutan minimal Rp 10.000,-.'
      ],
      ctaText: 'Buka Simpanan SIDASI',
      ctaType: 'whatsapp'
    },
    {
      id: 'tapa-qur',
      code: 'TAB-05',
      name: 'Tabungan Paket Qurban (TAPA QUR)',
      badge: 'Kolektif & Masjid',
      category: 'tabungan',
      image: 'assets/img/produk/design_tabungan_tapaqur-removebg-preview.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Kolektif qurban masjid, komunitas, dan rukun warga yang tertib dan transparan.',
      shortDesc: 'Fasilitas tabungan qurban kolektif untuk DKM masjid, majelis taklim, instansi kantor, dan kelompok masyarakat dengan pelaporan berkala transparan.',
      ketentuan: [
        'Dapat dibuka atas nama bendahara panitia qurban masjid atau kelompok per 7 orang.',
        'Laporan rekapitulasi mutasi dapat dicetak sewaktu-waktu untuk pengurus.',
        'Kerjasama resmi dengan peternak lokal Majalengka bersertifikasi kesehatan dinas.',
        'Bebas biaya administrasi dan bebas biaya transfer antar kantor kas BPR.',
        'Dijamin penuh oleh LPS.'
      ],
      syarat: [
        'Fotokopi KTP pengurus (Ketua & Bendahara DKM/Komunitas).',
        'Surat penunjukan kepengurusan DKM atau berita acara kelompok.',
        'Setoran awal kelompok mulai Rp 100.000,-.'
      ],
      ctaText: 'Daftar Paket TAPA QUR',
      ctaType: 'whatsapp'
    },
    {
      id: 'tada-bur',
      code: 'TAB-06',
      name: 'Tabungan Pelajar (TADA BUR)',
      badge: 'Generasi Cerdas',
      category: 'tabungan',
      image: 'assets/img/produk/tadabur.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Membentuk karakter gemar menabung dan kemandirian finansial sejak dini.',
      shortDesc: 'Tabungan khusus siswa PAUD, SD, SMP, hingga SMA di Kabupaten Majalengka dengan buku tabungan atas nama siswa sendiri didampingi orang tua.',
      ketentuan: [
        'Buku tabungan resmi bertuliskan nama siswa pemegang rekening.',
        'Bebas biaya administrasi bulanan seumur hidup siswa.',
        'Kemudahan jemput setoran rutin ke sekolah-sekolah yang bekerjasama.',
        'Sertifikat apresiasi penabung cilik berprestasi setiap akhir tahun ajaran.',
        'Dijamin aman oleh LPS.'
      ],
      syarat: [
        'Fotokopi Kartu Pelajar / Kartu Identitas Anak (KIA) / Akta Kelahiran.',
        'Fotokopi KTP salah satu orang tua / wali.',
        'Fotokopi Kartu Keluarga.',
        'Setoran awal sangat terjangkau hanya Rp 10.000,-.'
      ],
      ctaText: 'Buka Tabungan TADA BUR',
      ctaType: 'whatsapp'
    },
    {
      id: 't-askara',
      code: 'TAB-07',
      name: 'Tabungan T-ASKARA',
      badge: 'Aparatur Desa',
      category: 'tabungan',
      image: 'assets/img/produk/IMG_2914.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Kesejahteraan dan ketahanan masa depan bagi pamong desa dan RT/RW Majalengka.',
      shortDesc: 'Fasilitas tabungan terintegrasi untuk penerimaan tunjangan, siltap, dan insentif perangkat desa serta pengurus lingkungan RT/RW se-Kabupaten Majalengka.',
      ketentuan: [
        'Terhubung langsung dengan mekanisme penyaluran siltap desa.',
        'Kemudahan fasilitas auto-debet untuk tabungan persiapan purna jabatan.',
        'Bebas biaya administrasi pengelolaan rekening.',
        'Akses prioritas pada fasilitas pembiayaan kredit lunak BPR Majalengka.',
        'Dijamin LPS.'
      ],
      syarat: [
        'Fotokopi KTP / e-KTP pamong desa atau pengurus RT/RW.',
        'Salinan Surat Keputusan (SK) Pengangkatan yang masih berlaku.',
        'Surat rekomendasi dari Kepala Desa / Kelurahan setempat.',
        'Setoran awal Rp 50.000,-.'
      ],
      ctaText: 'Informasi T-ASKARA',
      ctaType: 'whatsapp'
    },
    {
      id: 'ciremaiku',
      code: 'TAB-08',
      name: 'Tabungan CIREMAIKU',
      badge: 'Wisata Lokal',
      category: 'tabungan',
      image: 'assets/img/produk/IMG_2922.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Rencanakan liburan indah bersama keluarga menjelajahi eksotisme Tanah Sindangkasih.',
      shortDesc: 'Tabungan tematik wisata untuk merencanakan liburan keluarga di destinasi unggulan Majalengka (Terasering Panyaweuyan, Curug Muara Jaya, Situ Cipanten) dengan bonus voucher tiket masuk.',
      ketentuan: [
        'Target menabung fleksibel 6 s/d 12 bulan.',
        'Mendapatkan voucher diskon tiket masuk objek wisata mitra BPR Majalengka.',
        'Bebas biaya penarikan saat target waktu liburan tiba.',
        'Suku bunga menarik dan terproteksi penjaminan LPS.',
        'Setoran dapat diatur secara harian, mingguan, atau bulanan.'
      ],
      syarat: [
        'Fotokopi KTP pemohon.',
        'Mengisi form pembukaan rekening CIREMAIKU.',
        'Setoran awal mulai Rp 50.000,-.'
      ],
      ctaText: 'Mulai Tabungan Wisata',
      ctaType: 'whatsapp'
    },
    {
      id: 'simpel',
      code: 'TAB-09',
      name: 'Simpanan Pelajar (SimPel) OJK',
      badge: 'Program Nasional',
      category: 'tabungan',
      image: 'assets/img/produk/tadabur.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Gerakan Indonesia Menabung resmi berstandar Otoritas Jasa Keuangan (OJK).',
      shortDesc: 'Produk tabungan nasional inisiasi OJK khusus siswa PAUD s/d SMA dengan fitur serba sederhana, syarat mudah, dan bebas biaya admin bulanan.',
      ketentuan: [
        'Bebas biaya administrasi bulanan.',
        'Setoran awal hanya Rp 5.000,- dan setoran berikutnya mulai Rp 1.000,-.',
        'Buku tabungan diterbitkan langsung atas nama siswa.',
        'Dilindungi penuh oleh Lembaga Penjamin Simpanan (LPS).'
      ],
      syarat: [
        'Fotokopi Kartu Pelajar / Surat Keterangan Sekolah.',
        'Fotokopi Akta Kelahiran dan Kartu Keluarga.',
        'Fotokopi KTP orang tua / wali.',
        'Mengisi formulir pembukaan SimPel di loket atau saat mobil kas keliling ke sekolah.'
      ],
      ctaText: 'Buka Rekening SimPel',
      ctaType: 'whatsapp'
    },
    {
      id: 'simarmas-co',
      code: 'TAB-10',
      name: 'SIMARMAS GO (Mitra Karyawan)',
      badge: 'Payroll Industri',
      category: 'tabungan',
      image: 'assets/img/produk/IMG_2926.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Layanan payroll dan tabungan kolektif pekerja industri manufaktur Majalengka.',
      shortDesc: 'Solusi pembayaran upah kerja (payroll) dan simpanan terencana bagi para karyawan pabrik garmen, industri aviasi Kertajati, dan perusahaan swasta di Majalengka.',
      ketentuan: [
        'Penyaluran gaji tepat waktu zero-fee transfer.',
        'Fasilitas penarikan tunai mudah di seluruh jaringan kantor kas.',
        'Kemudahan pengajuan kredit pegawai (KAPESTA) dengan proses satu pintu.',
        'Dijamin aman oleh LPS hingga Rp 2 Miliar per nasabah.'
      ],
      syarat: [
        'Perjanjian Kerjasama (PKS) antara perusahaan/HRD dengan BPR Majalengka.',
        'Fotokopi KTP dan ID Card Karyawan.',
        'Daftar nominatif karyawan dari perusahaan.'
      ],
      ctaText: 'Kerjasama Payroll',
      ctaType: 'whatsapp'
    }
  ],

  deposito: [
    {
      id: 'deposito-reguler',
      code: 'DEP-01',
      name: 'Deposito Berjangka Bank Majalengka',
      badge: 'Bunga Maksimal LPS',
      category: 'deposito',
      image: 'assets/img/produk/IMG_2928.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Investasi berjangka terpercaya dengan suku bunga kompetitif dijamin penuh LPS.',
      shortDesc: 'Pilihan investasi berjangka yang aman dan memberikan imbal hasil maksimal bagi perorangan maupun korporasi, dengan fleksibilitas jangka waktu penempatan 1, 3, 6, dan 12 bulan.',
      ketentuan: [
        'Pilihan jangka waktu penempatan: 1 Bulan, 3 Bulan, 6 Bulan, dan 12 Bulan.',
        'Suku bunga penjaminan LPS sangat bersaing di atas rata-rata bunga deposito bank umum.',
        'Fasilitas Automatic Roll Over (ARO) untuk perpanjangan pokok otomatis atau pokok + bunga.',
        'Bunga dapat ditransfer otomatis setiap bulan ke rekening tabungan nasabah di bank mana pun.',
        'Dapat dijadikan agunan pinjaman kredit dengan proses cepat jika mendadak butuh likuiditas.',
        'Dijamin 100% oleh Lembaga Penjamin Simpanan (LPS) hingga Rp 2.000.000.000,-.'
      ],
      syarat: [
        'Fotokopi KTP / SIM / Paspor yang masih berlaku (perorangan).',
        'Legalitas pendirian, NIB, dan KTP Pengurus (khusus badan usaha/yayasan).',
        'Memiliki rekening tabungan aktif di BPR Majalengka untuk penampungan bunga.',
        'Penempatan nominal deposito awal minimal Rp 1.000.000,-.'
      ],
      ctaText: 'Simulasi Bunga Deposito',
      ctaType: 'whatsapp'
    },
    {
      id: 'deposito-suka-suka',
      code: 'DEP-02',
      name: 'Deposito Suka-Suka (Hadiah Langsung)',
      badge: 'Hadiah Tanpa Diundi',
      category: 'deposito',
      image: 'assets/img/produk/IMG_2928.jpeg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Simpan dana deposito hari ini, langsung bawa pulang hadiah impian tanpa diundi!',
      shortDesc: 'Program deposito istimewa di mana nasabah langsung menentukan hadiah favoritnya di muka (Sepeda Motor, Smart TV, Lemari Es, Smartphone, hingga Emas Batangan Logam Mulia Antam) begitu dana ditempatkan.',
      ketentuan: [
        'Hadiah langsung diserahkan di muka tanpa proses undian apa pun.',
        'Pilihan tenor penempatan 6, 12, 18, atau 24 bulan sesuai tier hadiah yang dipilih.',
        'Pokok deposito tetap utuh 100% dan dikembalikan penuh saat jatuh tempo.',
        'Pilihan hadiah bergaransi resmi dari distributor terpercaya.',
        'Dana tersimpan aman dan terdaftar pada program penjaminan simpanan LPS.'
      ],
      syarat: [
        'Fotokopi KTP / e-KTP yang masih berlaku.',
        'Menandatangani formulir keikutsertaan program Deposito Hadiah Langsung.',
        'Menempatkan dana baru (fresh fund) sesuai ketentuan paket hadiah.',
        'Komitmen dana mengendap hingga masa jatuh tempo berakhir.'
      ],
      ctaText: 'Katalog Hadiah Deposito',
      ctaType: 'whatsapp'
    }
  ],

  kredit: [
    {
      id: 'kapesta',
      code: 'KRD-01',
      name: 'KAPESTA (Kredit Pegawai Swasta)',
      badge: 'Bunga Ringan',
      category: 'kredit',
      image: 'assets/img/produk/KAPESTA.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Fasilitas pinjaman multiguna cepat untuk karyawan swasta dan pekerja pabrik.',
      shortDesc: 'Pinjaman kebutuhan keluarga atau modal sampingan bagi karyawan tetap maupun kontrak rekanan perusahaan swasta di Kabupaten Majalengka dan wilayah Ciayumajakuning.',
      ketentuan: [
        'Plafon pinjaman mulai Rp 3.000.000,- hingga Rp 50.000.000,-.',
        'Jangka waktu pinjaman (tenor) fleksibel mulai 12 hingga 36 bulan.',
        'Skema angsuran tetap (flat) setiap bulan atau mekanisme potong gaji.',
        'Proses verifikasi dan persetujuan cepat (1 - 3 hari kerja).',
        'Dilindungi oleh asuransi jiwa kredit.'
      ],
      syarat: [
        'Fotokopi KTP pemohon dan suami/istri.',
        'Fotokopi Kartu Keluarga dan Surat Nikah.',
        'Slip gaji asli 3 bulan terakhir atau surat keterangan penghasilan dari bendahara/HRD.',
        'Surat rekomendasi / surat keterangan bekerja dari pimpinan perusahaan.',
        'Agunan fleksibel (BPKB motor/mobil atau SHM/AJB).'
      ],
      ctaText: 'Ajukan KAPESTA Sekarang',
      ctaType: 'form'
    },
    {
      id: 'kan',
      code: 'KRD-02',
      name: 'KAN (Kredit Aparatur Negara)',
      badge: 'Plafon Tinggi',
      category: 'kredit',
      image: 'assets/img/produk/KAN.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Pembiayaan khusus PNS dan Pegawai Pemerintah di Kabupaten Majalengka.',
      shortDesc: 'Solusi pembiayaan konsumtif serbaguna bagi ASN, pejabat fungsional daerah, dan PPPK dengan plafon tinggi dan suku bunga preferensial paling ringan.',
      ketentuan: [
        'Plafon pinjaman hingga Rp 250.000.000,- sesuai kemampuan angsuran gaji.',
        'Tenor sangat panjang hingga 10 s/d 15 tahun (menyesuaikan batas usia pensiun).',
        'Suku bunga khusus ASN dengan proses persetujuan express 1 hari kerja cair.',
        'Pembayaran angsuran terintegrasi potong gaji langsung dari bendahara dinas.',
        'Perlindungan asuransi jiwa kredit penuh.'
      ],
      syarat: [
        'Fotokopi KTP pemohon dan pasangan.',
        'Fotokopi Kartu Keluarga.',
        'SK Pengangkatan CPNS & SK PNS/PPPK terakhir (asli disimpan sebagai agunan utama).',
        'Kartu Pegawai (Karpeg) & Taspen.',
        'Daftar rincian gaji terakhir yang ditandatangani bendahara pengeluaran.'
      ],
      ctaText: 'Ajukan KAN Sekarang',
      ctaType: 'form'
    },
    {
      id: 'kredit-konstruksi',
      code: 'KRD-03',
      name: 'Kredit Pembiayaan Konstruksi & Proyek',
      badge: 'Rekanan Pengadaan',
      category: 'kredit',
      image: 'assets/img/produk/multiguna.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Dukungan modal kerja kontraktor pengerjaan proyek infrastruktur daerah.',
      shortDesc: 'Fasilitas pinjaman modal kerja bagi kontraktor, pengembang rekanan APBD Majalengka / APBN untuk kelancaran pembelian material dan upah tenaga kerja proyek.',
      ketentuan: [
        'Plafon pembiayaan hingga 70% dari nilai Surat Perintah Kerja (SPK) / Kontrak.',
        'Pola pengembalian menyesuaikan termin pencairan termin fisik proyek (turnkey).',
        'Pilihan agunan tambahan tanah/bangunan atau jaminan piutang proyek.',
        'Pendampingan analisa teknis lapangan oleh analis perbankan berpengalaman.'
      ],
      syarat: [
        'Legalitas badan usaha (Akta Pendirian PT/CV, NIB, SIUJK, NPWP Perusahaan).',
        'Salinan SPK / Kontrak Kerja Asli yang masih berlaku dari instansi pemberi kerja.',
        'Rekening koran perusahaan 6 bulan terakhir.',
        'Dokumen agunan tambahan berupa SHM/SHGB.'
      ],
      ctaText: 'Konsultasi Kredit Proyek',
      ctaType: 'form'
    },
    {
      id: 'kredit-pensiunan',
      code: 'KRD-04',
      name: 'Kredit Pensiunan Sejahtera',
      badge: 'Masa Purna Tenang',
      category: 'kredit',
      image: 'assets/img/produk/pensiunan.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Bakti penghormatan bagi purnabakti untuk membuka usaha dan kebutuhan keluarga.',
      shortDesc: 'Fasilitas pinjaman bagi para pensiunan PNS, TNI, POLRI, dan BUMN untuk memenuhi renovasi hunian, hajatan anak, maupun modal usaha produktif di masa tua.',
      ketentuan: [
        'Plafon pinjaman hingga Rp 100.000.000,-.',
        'Batas usia saat kredit lunas mencapai 75 tahun.',
        'Suku bunga ringan dan angsuran terpotong otomatis dari uang pensiun bulanan.',
        'Wajib dilindungi asuransi jiwa purnabakti sehingga tidak membebani ahli waris.'
      ],
      syarat: [
        'Fotokopi KTP pemohon dan pasangan.',
        'SK Pensiun asli (Karip / SKEP).',
        'Buku tabungan gaji pensiun Taspen / Asabri.',
        'Surat Keterangan Kematian pasangan (apabila pensiun janda/duda).'
      ],
      ctaText: 'Ajukan Kredit Pensiunan',
      ctaType: 'form'
    },
    {
      id: 'kredit-modal-kerja',
      code: 'KRD-05',
      name: 'Kredit Modal Kerja (KMK)',
      badge: 'Solusi Arus Kas',
      category: 'kredit',
      image: 'assets/img/produk/multiguna.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Perkuat persediaan barang dagang dan percepat perputaran omzet usaha Anda.',
      shortDesc: 'Pinjaman modal usaha untuk pedagang toko grosir, distributor sembako, pemilik bengkel, dan industri olahan di seluruh penjuru Majalengka.',
      ketentuan: [
        'Plafon pinjaman mulai Rp 10.000.000,- s/d Rp 500.000.000,-.',
        'Tenor pinjaman mulai 12 bulan hingga 60 bulan (5 tahun).',
        'Pilihan skema angsuran pokok dan bunga flat atau bunga menurun (efektif).',
        'Agunan sertifikat tanah (SHM/SHGB) atau BPKB kendaraan niaga.'
      ],
      syarat: [
        'Fotokopi KTP suami/istri dan Kartu Keluarga.',
        'Surat Keterangan Usaha (SKU) dari Kantor Desa/Kelurahan atau NIB.',
        'Usaha telah berjalan aktif minimal 1 tahun.',
        'Dokumen bukti kepemilikan agunan asli.'
      ],
      ctaText: 'Ajukan Modal Kerja',
      ctaType: 'form'
    },
    {
      id: 'kredit-pertanian',
      code: 'KRD-06',
      name: 'Kredit Pertanian & Musiman',
      badge: 'Bayar Pasca Panen',
      category: 'kredit',
      image: 'assets/img/produk/KAN.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Mitra setia petani Majalengka: modal tanam mudah, bayar saat panen raya.',
      shortDesc: 'Pembiayaan khusus petani padi, budidaya bawang merah, cabai, dan palawija dengan skema pembayaran fleksibel Yarnen (Bayar Setelah Panen) tanpa beban angsuran bulanan.',
      ketentuan: [
        'Pola pelunasan berjangka 3, 4, atau 6 bulan sesuai masa panen komoditas.',
        'Plafon pinjaman dihitung berdasarkan estimasi luas lahan tanam dan kebutuhan bibit/pupuk.',
        'Suku bunga terjangkau untuk menunjang ketahanan pangan daerah Majalengka.'
      ],
      syarat: [
        'Fotokopi KTP dan Kartu Keluarga petani pemohon.',
        'Surat keterangan penggarap / kepemilikan lahan sawah/kebun.',
        'Rekomendasi dari Ketua Kelompok Tani (Poktan) setempat.',
        'Agunan sertifikat tanah darat/sawah atau BPKB.'
      ],
      ctaText: 'Ajukan Kredit Pertanian',
      ctaType: 'form'
    },
    {
      id: 'kredit-talangan',
      code: 'KRD-07',
      name: 'Kredit Talangan (Bridging Fund)',
      badge: 'Pencairan Kilat',
      category: 'kredit',
      image: 'assets/img/produk/multiguna.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Solusi dana tunai cepat untuk kebutuhan mendesak dan bridging pembayaran bisnis.',
      shortDesc: 'Pinjaman jangka pendek berkecepatan tinggi bagi nasabah yang membutuhkan dana talangan segera sebelum dana pelunasan utama masuk.',
      ketentuan: [
        'Jangka waktu penempatan singkat 1 s/d 6 bulan.',
        'Proses analisa dan persetujuan kilat dalam 24 jam kerja.',
        'Pelunasan sewaktu-waktu tanpa penalti berlebih apabila dana pelunasan telah tersedia.'
      ],
      syarat: [
        'Fotokopi KTP dan Kartu Keluarga.',
        'Bukti sumber pengembalian pasti (invoice, SPK, sertifikat deposito, dll).',
        'Dokumen jaminan likuid atau aset tanah/kendaraan.'
      ],
      ctaText: 'Konsultasi Dana Talangan',
      ctaType: 'form'
    },
    {
      id: 'kredit-kios',
      code: 'KRD-08',
      name: 'Kredit Pemilikan Kios & Los Pasar',
      badge: 'Pedagang Pasar',
      category: 'kredit',
      image: 'assets/img/warung-sembako.jpg',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Miliki kios dan tempat dasaran usaha mandiri di pasar-pasar Kabupaten Majalengka.',
      shortDesc: 'Pembiayaan pembelian hak guna pakai, renovasi, atau sewa jangka panjang kios dan los di pasar Kadipaten, Jatiwangi, Majalengka Kota, Talaga, dan Cikijing.',
      ketentuan: [
        'Plafon hingga 80% dari harga kios/los pasar.',
        'Tenor pinjaman panjang hingga 5 tahun agar angsuran harian/bulanan ringan bagi pedagang.',
        'Petugas pickup service siap menjemput angsuran langsung di lapak pedagang setiap hari.'
      ],
      syarat: [
        'KTP pemohon dan Kartu Keluarga.',
        'Surat Izin Pemakaian Tempat Dasaran (SIPTD) / Bukti kepemilikan kios dari dinas pengelola pasar.',
        'Usaha perdagangan di pasar telah aktif minimal 6 bulan.'
      ],
      ctaText: 'Ajukan Kredit Kios',
      ctaType: 'form'
    },
    {
      id: 'kredit-perak',
      code: 'KRD-09',
      name: 'Kredit Raharja (PERAK - Peduli Usaha Mikro)',
      badge: 'Subsidi Bunga',
      category: 'kredit',
      image: 'assets/img/produk/perak.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Program BUMD Majalengka memberantas rentenir dan pinjaman online ilegal.',
      shortDesc: 'Kredit mikro berbunga sangat rendah yang disubsidi untuk membantu pedagang keliling, pengrajin anyaman bambu, penjahit, dan pelaku usaha ultra mikro rumahan.',
      ketentuan: [
        'Plafon pinjaman mulai Rp 1.000.000,- s/d Rp 10.000.000,-.',
        'Bunga ultra-ringan tanpa potongan tersembunyi.',
        'Syarat administrasi sangat mudah dan ramah pelaku usaha kecil.',
        'Tenor 6 hingga 24 bulan.'
      ],
      syarat: [
        'KTP warga domisili Kabupaten Majalengka.',
        'Kartu Keluarga.',
        'Foto usaha aktif di rumah / tempat jualan.',
        'Surat keterangan domisili usaha dari RT/RW setempat.'
      ],
      ctaText: 'Ajukan Kredit PERAK',
      ctaType: 'form'
    },
    {
      id: 'kredit-ksg',
      code: 'KRD-10',
      name: 'KSG (Kredit Sertifikasi Guru)',
      badge: 'Khusus Guru',
      category: 'kredit',
      image: 'assets/img/produk/KSG.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Kredit kesejahteraan guru bersertifikasi pendidik di seluruh Majalengka.',
      shortDesc: 'Fasilitas pinjaman perorangan bagi para tenaga pendidik (Guru ASN & Swasta) penerima Tunjangan Profesi Guru (TPG) untuk kebutuhan keluarga terencana.',
      ketentuan: [
        'Plafon pinjaman hingga Rp 100.000.000,-.',
        'Pola angsuran dapat disesuaikan dengan jadwal pencairan tunjangan sertifikasi triwulan atau bulanan.',
        'Bunga bersaing dengan proses persetujuan cepat.'
      ],
      syarat: [
        'Fotokopi KTP pemohon dan pasangan.',
        'Fotokopi Sertifikat Pendidik (Sertifikasi Guru).',
        'Rekening koran / buku tabungan penerima tunjangan profesi guru 6 bulan terakhir.',
        'Surat rekomendasi dari Kepala Sekolah.'
      ],
      ctaText: 'Ajukan KSG Guru',
      ctaType: 'form'
    },
    {
      id: 'kredit-non-asn',
      code: 'KRD-11',
      name: 'Kredit Non-ASN & Pegawai PPPK',
      badge: 'Pegawai Daerah',
      category: 'kredit',
      image: 'assets/img/produk/non asn.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Pinjaman resmi terpercaya bagi honorer, tenaga kontrak, dan PPPK daerah.',
      shortDesc: 'Pembiayaan multiguna bagi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK) dan tenaga honorer di OPD, Puskesmas, dan sekolah negeri di lingkungan Pemkab Majalengka.',
      ketentuan: [
        'Plafon pinjaman proporsional disesuaikan dengan sisa masa kontrak kerja PPPK.',
        'Angsuran tetap bulanan dipotong melalui koordinasi bendahara dinas.',
        'Dilindungi asuransi jiwa kredit.'
      ],
      syarat: [
        'Fotokopi KTP pemohon dan pasangan serta Kartu Keluarga.',
        'Surat Keputusan (SK) Pengangkatan PPPK / Honorer asli yang masih berlaku.',
        'Slip gaji terakhir yang ditandatangani bendahara.',
        'Surat rekomendasi pimpinan instansi / dinas.'
      ],
      ctaText: 'Ajukan Kredit PPPK',
      ctaType: 'form'
    },
    {
      id: 'kredit-multiguna',
      code: 'KRD-12',
      name: 'Kredit Multiguna Keluarga',
      badge: 'Kebutuhan Bebas',
      category: 'kredit',
      image: 'assets/img/produk/multiguna.png',
      heroImage: 'assets/img/hero-batik.jpg',
      slogan: 'Satu solusi keuangan terpercaya untuk aneka rencana penting keluarga Anda.',
      shortDesc: 'Pinjaman multiguna beragunan untuk membiayai renovasi rumah impian, dana pendidikan perguruan tinggi anak, biaya pernikahan, hingga fasilitas kesehatan darurat.',
      ketentuan: [
        'Plafon fleksibel mulai Rp 10.000.000,- hingga Rp 300.000.000,-.',
        'Jangka waktu pinjaman hingga 5 tahun (60 bulan).',
        'Agunan berupa sertifikat tanah (SHM/SHGB) atau BPKB kendaraan roda empat / roda dua.',
        'Proses taksasi nilai agunan transparan dan profesional.'
      ],
      syarat: [
        'Fotokopi KTP suami dan istri.',
        'Fotokopi Kartu Keluarga dan Surat Nikah.',
        'Bukti penghasilan tetap atau slip gaji / catatan usaha.',
        'Sertifikat tanah asli atau BPKB kendaraan beserta STNK atas nama pribadi/keluarga.'
      ],
      ctaText: 'Ajukan Kredit Multiguna',
      ctaType: 'form'
    }
  ]
};

/* ==========================================================================
   2. DATABASE KANTOR KAS & LOKASI (12 KANTOR KAS)
   ========================================================================== */
const BRANCHES_DATA = [
  {
    name: 'Kantor Pusat Operasional',
    type: 'Pusat',
    address: 'Jl. Raya K.H. Abdul Halim No. 338, Majalengka Wetan, Kec. Majalengka, Jawa Barat 45411',
    phone: '(0233) 281144',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB',
    isMain: true
  },
  {
    name: 'Kantor Kas Kadipaten',
    type: 'Kas',
    address: 'Jl. Raya Kadipaten No. 45, Kec. Kadipaten (Dekat Pasar Kadipaten)',
    phone: '(0233) 282510',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Jatiwangi',
    type: 'Kas',
    address: 'Jl. Pos No. 12, Sutawangi, Kec. Jatiwangi (Kawasan Genteng & Keramik)',
    phone: '(0233) 881230',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Talaga',
    type: 'Kas',
    address: 'Jl. Jenderal Ahmad Yani No. 88, Talaga Kulon, Kec. Talaga',
    phone: '(0233) 811090',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Cikijing',
    type: 'Kas',
    address: 'Jl. Raya Kasturi No. 15, Kec. Cikijing (Pusat Konveksi Celana Jeans)',
    phone: '(0233) 891340',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Rajagaluh',
    type: 'Kas',
    address: 'Jl. Pangeran Muhamad No. 34, Kec. Rajagaluh (Sentra Anyaman Bambu)',
    phone: '(0233) 511450',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Leuwimunding',
    type: 'Kas',
    address: 'Jl. Raya Mirat No. 22, Kec. Leuwimunding (Dekat Pertigaan Parapatan)',
    phone: '(0233) 512670',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Sukahaji',
    type: 'Kas',
    address: 'Jl. Raya Sukahaji No. 10, Cikalong, Kec. Sukahaji',
    phone: '(0233) 882190',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Maja',
    type: 'Kas',
    address: 'Jl. Pasukan Sindangkasih No. 05, Kec. Maja (Akses Wisata Panyaweuyan)',
    phone: '(0233) 283450',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Kertajati',
    type: 'Kas',
    address: 'Jl. Kertajati-Kadipaten Km 3, Kec. Kertajati (Kawasan Bandara Internasional Kertajati)',
    phone: '(0233) 883100',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Ligung',
    type: 'Kas',
    address: 'Jl. Raya Ligung No. 78, Kec. Ligung (Sentra Industri Pabrik)',
    phone: '(0233) 884520',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  },
  {
    name: 'Kantor Kas Sumberjaya',
    type: 'Kas',
    address: 'Jl. Raya Cirebon-Bandung Km 28, Kec. Sumberjaya (Gerbang Perbatasan Timur)',
    phone: '(0233) 885670',
    whatsapp: '0811-1111-6526',
    hours: 'Senin - Jumat: 08:00 - 15:30 WIB'
  }
];

/* ==========================================================================
   3. DATABASE ARTIKEL & LITERASI KEUANGAN
   ========================================================================== */
const ARTICLES_DATA = [
  {
    id: 1,
    tag: 'Perencanaan Finansial',
    tagColor: 'blue',
    title: 'Mulai Dana Darurat dari Kebiasaan Sederhana Sehari-hari',
    image: 'assets/img/perencanaan-keluarga.jpg',
    excerpt: 'Langkah awal yang realistis untuk memisahkan dana kebutuhan pokok dan cadangan darurat keluarga demi ketenangan hidup masa depan.',
    date: '24 Oktober 2026',
    readTime: '4 menit baca',
    content: `
      <p class="mb-4">Banyak keluarga di Kabupaten Majalengka merasa bahwa membentuk dana darurat membutuhkan penghasilan dalam jumlah besar. Padahal, kunci utama pembentukan dana darurat bukanlah besarnya nominal sekaligus, melainkan konsistensi memisahkan porsi kecil di awal setiap kali menerima pemasukan.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">1. Pisahkan Rekening Kebutuhan dan Rekening Cadangan</h4>
      <p class="mb-4">Mencampurkan dana belanja pasar harian dengan simpanan adalah pemicu utama dana darurat sering terpakai tanpa sadar. Dengan membuka rekening tabungan khusus seperti <strong>SIDASI (Simpanan Dana Siaga)</strong> di Bank Majalengka yang bebas potongan administrasi bulanan memberatkan, uang cadangan Anda tetap utuh dan bertumbuh dengan bunga harian.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">2. Mulai dari Aturan 10%</h4>
      <p class="mb-4">Cukup sisihkan 10% dari penghasilan tetap atau keuntungan dagang Anda setiap minggu. Jika penghasilan mingguan Anda Rp 1.000.000,-, sisihkan Rp 100.000,- langsung ke rekening simpanan. Petugas Bank Majalengka bahkan menyediakan fasilitas <em>Pickup Service</em> jemput setoran langsung ke lokasi Anda sehingga menabung tidak merepotkan waktu bekerja.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">3. Dana Terjamin Aman di BPR</h4>
      <p class="mb-4">Simpanan Anda di Perumda BPR Majalengka dijamin 100% oleh <strong>Lembaga Penjamin Simpanan (LPS)</strong> hingga Rp 2.000.000.000,- per nasabah. Ketenangan pikiran keluarga Anda adalah prioritas kami.</p>
    `
  },
  {
    id: 2,
    tag: 'Panduan UMKM',
    tagColor: 'amber',
    title: 'Pisahkan Uang Usaha Warung dan Uang Dapur Pribadi',
    image: 'assets/img/warung-sembako.jpg',
    excerpt: 'Pencatatan kas harian yang tertib menghindarkan pedagang kecil dari modal yang tergerus kebutuhan konsumtif.',
    date: '18 Oktober 2026',
    readTime: '5 menit baca',
    content: `
      <p class="mb-4">Bagi para pemilik toko kelontong, pedagang pasar Kadipaten, dan warung makan di pelosok Majalengka, salah satu tantangan terbesar adalah menjaga modal usaha tidak tergerus kebutuhan dapur rumah tangga sendiri.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">1. Gaji Diri Sendiri Secara Tetap</h4>
      <p class="mb-4">Tentukan nominal "gaji" mingguan atau bulanan yang wajar untuk kebutuhan rumah tangga Anda. Ambil hanya sejumlah tersebut dari keuntungan bersih toko, dan jangan mengambil uang kembalian di laci kasir secara acak untuk keperluan pribadi.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">2. Miliki Buku Kas Catatan Harian Sederhana</h4>
      <p class="mb-4">Cukup sediakan buku tulis bertanggal dengan tiga kolom: Uang Masuk, Uang Keluar (Kulakan), dan Sisa Saldo Kas. Setiap malam sebelum menutup toko, luangkan 5 menit untuk mencocokkan uang fisik dengan catatan buku.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">3. Dukungan Modal Usaha BPR Majalengka</h4>
      <p class="mb-4">Bila usaha warung Anda membutuhkan suntikan permodalan untuk memperbanyak stok dagangan grosir menjelang masa panen raya atau hari raya, Bank Majalengka menyediakan fasilitas <strong>Kredit Modal Kerja (KMK)</strong> dan program <strong>Kredit PERAK</strong> dengan angsuran ringan dan persyaratan yang sangat bersahabat.</p>
    `
  },
  {
    id: 3,
    tag: '#AwasModus',
    tagColor: 'red',
    title: 'Kenali Pesan Mencurigakan, Jaga Kerahasiaan Data Perbankan',
    image: 'assets/img/pensiunan-hp.jpg',
    excerpt: 'Petugas Bank Majalengka tidak pernah meminta kode OTP, PIN, password, atau biaya transfer ke rekening pribadi pegawai.',
    date: '10 Oktober 2026',
    readTime: '3 menit baca',
    content: `
      <p class="mb-4">Seiring berkembangnya transaksi digital, modus penipuan berkedok undian berhadiah, undangan pernikahan digital (file APK), dan pesan WhatsApp palsu yang mengatasnamakan bank kian marak mengincar masyarakat.</p>
      <h4 class="font-bold text-slate-800 text-lg mb-2">Prinsip Aman Transaksi Bank Majalengka:</h4>
      <ul class="list-disc pl-5 mb-4 space-y-2">
        <li><strong>JANGAN PERNAH</strong> memberitahukan nomor PIN, password, atau kode OTP kepada siapa pun, termasuk oknum yang mengaku sebagai pegawai bank.</li>
        <li><strong>JANGAN TRANSFER</strong> dana biaya administrasi pinjaman atau undian ke rekening bank atas nama perorangan atau nomor rekening pribadi petugas. Seluruh transaksi resmi disetorkan langsung di loket kantor kas atau rekening resmi Perumda BPR Majalengka.</li>
        <li><strong>JANGAN KLIK</strong> tautan file unduhan dengan ekstensi <code>.APK</code> yang dikirimkan melalui nomor WhatsApp asing.</li>
      </ul>
      <p class="mb-4">Bila Anda mendapati aktivitas mencurigakan atau dihubungi nomor tidak dikenal, segera konfirmasi ke Call Center resmi Bank Majalengka di <strong>(0233) 281144</strong> atau WhatsApp resmi <strong>0811-1111-6526</strong>.</p>
    `
  }
];

/* ==========================================================================
   4. NAVIGATION & MOBILE DRAWER LOGIC
   ========================================================================== */
function initNavbar() {
  const header = document.getElementById('main-header');
  const mobileToggle = document.getElementById('mobile-menu-toggle');
  const mobileDrawer = document.getElementById('mobile-drawer');
  const mobileDrawerClose = document.getElementById('mobile-drawer-close');
  const mobileBackdrop = document.getElementById('mobile-drawer-backdrop');

  // Scroll effect
  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      header.classList.add('glass-header-scrolled');
    } else {
      header.classList.remove('glass-header-scrolled');
    }
  });

  // Mobile drawer controls
  const openDrawer = () => {
    mobileDrawer.classList.remove('translate-x-full');
    mobileBackdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  };

  const closeDrawer = () => {
    mobileDrawer.classList.add('translate-x-full');
    mobileBackdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };

  if (mobileToggle) mobileToggle.addEventListener('click', openDrawer);
  if (mobileDrawerClose) mobileDrawerClose.addEventListener('click', closeDrawer);
  if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeDrawer);

  // Close drawer on clicking links
  document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', closeDrawer);
  });
}

/* ==========================================================================
   5. INTERACTIVE FINANCIAL LOAN CALCULATOR ENGINE
   (Sesuai Sketsa Halaman 10 & Figma Section 6)
   ========================================================================== */
function initSimulator() {
  const plafondRange = document.getElementById('sim-plafond-range');
  const plafondInput = document.getElementById('sim-plafond-input');
  const tenorButtons = document.querySelectorAll('.sim-tenor-btn');
  const rateSchemeSelect = document.getElementById('sim-rate-scheme');
  const presetButtons = document.querySelectorAll('.sim-preset-btn');

  // Display fields
  const displayMonthlyInstallment = document.getElementById('sim-result-monthly');
  const displayPrincipalMonthly = document.getElementById('sim-result-principal');
  const displayInterestMonthly = document.getElementById('sim-result-interest');
  const displayTotalLoan = document.getElementById('sim-result-total');
  const displayPlafondSummary = document.getElementById('sim-result-plafond');
  const displayTenorSummary = document.getElementById('sim-result-tenor');
  const displayTotalInterest = document.getElementById('sim-result-total-interest');
  const btnKonsultasiWa = document.getElementById('sim-btn-wa');
  const btnAjukanKredit = document.getElementById('sim-btn-ajukan');

  if (!plafondRange || !plafondInput) return;

  let currentPlafond = 25000000;
  let currentTenor = 24; // bulan
  let currentScheme = 'flat'; // flat vs effective
  const flatAnnualRate = 0.06; // 6% per tahun sesuai Figma & Sketsa Halaman 10 (Rp 1.166.667 / bln)
  const effectiveAnnualRate = 0.105; // ~10.5% per tahun suku bunga efektif

  // Format Helper
  const formatRupiah = (num) => {
    return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  };

  const calculateLoan = () => {
    let monthlyInstallment = 0;
    let principalMonthly = 0;
    let interestMonthly = 0;
    let totalRepayment = 0;

    if (currentScheme === 'flat') {
      principalMonthly = currentPlafond / currentTenor;
      interestMonthly = (currentPlafond * flatAnnualRate) / 12;
      monthlyInstallment = principalMonthly + interestMonthly;
      totalRepayment = monthlyInstallment * currentTenor;
    } else {
      // Anuitas / Efektif
      const monthlyRate = effectiveAnnualRate / 12;
      monthlyInstallment = (currentPlafond * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -currentTenor));
      totalRepayment = monthlyInstallment * currentTenor;
      interestMonthly = (totalRepayment - currentPlafond) / currentTenor;
      principalMonthly = monthlyInstallment - interestMonthly;
    }

    // Update UI elements
    if (displayMonthlyInstallment) displayMonthlyInstallment.textContent = formatRupiah(monthlyInstallment);
    if (displayPrincipalMonthly) displayPrincipalMonthly.textContent = formatRupiah(principalMonthly);
    if (displayInterestMonthly) displayInterestMonthly.textContent = formatRupiah(interestMonthly);
    if (displayTotalInterest) displayTotalInterest.textContent = formatRupiah(totalRepayment - currentPlafond);
    if (displayTotalLoan) displayTotalLoan.textContent = formatRupiah(totalRepayment);
    if (displayPlafondSummary) displayPlafondSummary.textContent = formatRupiah(currentPlafond);
    if (displayTenorSummary) displayTenorSummary.textContent = `${currentTenor} Bulan`;

    // Update WhatsApp Consultation Link
    if (btnKonsultasiWa) {
      const waText = encodeURIComponent(
        `Halo Perumda BPR Majalengka, saya ingin konsultasi simulasi kredit:\n\n` +
        `• Plafon Pinjaman: ${formatRupiah(currentPlafond)}\n` +
        `• Jangka Waktu: ${currentTenor} Bulan\n` +
        `• Skema Bunga: ${currentScheme === 'flat' ? 'Bunga Flat Tetap (6% p.a)' : 'Bunga Efektif'}\n` +
        `• Estimasi Angsuran: ${formatRupiah(monthlyInstallment)} / bulan\n` +
        `• Total Pengembalian: ${formatRupiah(totalRepayment)}\n\n` +
        `Mohon informasi persyaratan dan prosedur pengajuannya. Terima kasih.`
      );
      btnKonsultasiWa.href = `https://wa.me/6281111116526?text=${waText}`;
    }
  };

  // Sync inputs
  plafondRange.addEventListener('input', (e) => {
    currentPlafond = parseInt(e.target.value, 10);
    plafondInput.value = formatRupiah(currentPlafond);
    calculateLoan();
  });

  plafondInput.addEventListener('change', (e) => {
    const rawVal = parseInt(e.target.value.replace(/[^0-9]/g, ''), 10) || 5000000;
    currentPlafond = Math.min(Math.max(rawVal, 1000000), 100000000);
    plafondRange.value = currentPlafond;
    plafondInput.value = formatRupiah(currentPlafond);
    calculateLoan();
  });

  // Preset Buttons
  presetButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      presetButtons.forEach(b => b.classList.remove('bg-bpr-blue', 'text-white'));
      btn.classList.add('bg-bpr-blue', 'text-white');
      currentPlafond = parseInt(btn.dataset.amount, 10);
      plafondRange.value = currentPlafond;
      plafondInput.value = formatRupiah(currentPlafond);
      calculateLoan();
    });
  });

  // Tenor Buttons
  tenorButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      tenorButtons.forEach(b => {
        b.classList.remove('bg-bpr-navy', 'text-white', 'shadow-sm');
        b.classList.add('bg-slate-100', 'text-slate-700');
      });
      btn.classList.remove('bg-slate-100', 'text-slate-700');
      btn.classList.add('bg-bpr-navy', 'text-white', 'shadow-sm');
      currentTenor = parseInt(btn.dataset.tenor, 10);
      calculateLoan();
    });
  });

  // Rate Scheme Select
  if (rateSchemeSelect) {
    rateSchemeSelect.addEventListener('change', (e) => {
      currentScheme = e.target.value;
      calculateLoan();
    });
  }

  // Ajukan Pinjaman Button -> Opens Form Modal with pre-filled numbers
  if (btnAjukanKredit) {
    btnAjukanKredit.addEventListener('click', () => {
      openFormPengajuan(currentPlafond, currentTenor);
    });
  }

  // Initial Calculation
  calculateLoan();
}

/* ==========================================================================
   6. FORM PENGAJUAN KREDIT INTERAKTIF
   (Sesuai Sketsa Halaman 9 & Ketentuan Perbankan)
   ========================================================================== */
function initFormPengajuan() {
  const modal = document.getElementById('modal-pengajuan-kredit');
  const closeBtn = document.getElementById('modal-pengajuan-close');
  const cancelBtn = document.getElementById('modal-pengajuan-cancel');
  const form = document.getElementById('form-pengajuan-kredit');
  const captchaQuestion = document.getElementById('captcha-question');
  const captchaRefresh = document.getElementById('captcha-refresh');
  const captchaAnswerInput = document.getElementById('form-captcha-answer');

  if (!modal || !form) return;

  let currentCaptchaResult = 0;

  const generateCaptcha = () => {
    const num1 = Math.floor(Math.random() * 8) + 2; // 2 - 9
    const num2 = Math.floor(Math.random() * 8) + 1; // 1 - 8
    currentCaptchaResult = num1 + num2;
    if (captchaQuestion) {
      captchaQuestion.textContent = `${num1} + ${num2} = ?`;
    }
    if (captchaAnswerInput) captchaAnswerInput.value = '';
  };

  generateCaptcha();
  if (captchaRefresh) captchaRefresh.addEventListener('click', generateCaptcha);

  // Close handlers
  const closeModal = () => {
    modal.classList.remove('active');
    document.body.classList.remove('overflow-hidden');
  };

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // Submit Handler
  form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Validasi NIK (16 digit)
    const nikInput = document.getElementById('form-nik');
    const nikVal = nikInput.value.trim();
    if (!/^\d{16}$/.test(nikVal)) {
      alert('Nomor Induk Kependudukan (NIK) harus terdiri dari 16 digit angka.');
      nikInput.focus();
      return;
    }

    // Validasi Captcha
    const userCaptcha = parseInt(captchaAnswerInput.value.trim(), 10);
    if (userCaptcha !== currentCaptchaResult) {
      alert('Hasil perhitungan CAPTCHA salah. Silakan coba lagi.');
      generateCaptcha();
      captchaAnswerInput.focus();
      return;
    }

    // Ambil data form
    const formData = {
      nik: nikVal,
      nama: document.getElementById('form-nama').value.trim(),
      alamat: document.getElementById('form-alamat').value.trim(),
      hp: document.getElementById('form-hp').value.trim(),
      afiliasi: document.getElementById('form-afiliasi').value.trim() || '-',
      nominal: document.getElementById('form-nominal').value.trim(),
      tenor: document.getElementById('form-tenor').value.trim(),
      tujuan: document.getElementById('form-tujuan').value.trim(),
      sumber: document.getElementById('form-sumber').value.trim(),
      pendapatanTetap: document.getElementById('form-pendapatan-tetap').value.trim(),
      pendapatanLain: document.getElementById('form-pendapatan-lain').value.trim() || '-',
      deskripsiLain: document.getElementById('form-deskripsi-lain').value.trim() || '-',
      agunan: document.getElementById('form-agunan').value.trim()
    };

    // AJAX submit to Laravel Backend
    fetch('/api/pengajuan-kredit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(formData)
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));
      return { ok: res.ok, status: res.status, data };
    })
    .then(({ ok, data }) => {
      if (!ok || !data.success) {
        alert(data.message || 'Gagal mengirim pengajuan kredit. Mohon periksa kembali formulir Anda.');
        modal.classList.add('active');
        document.body.classList.add('overflow-hidden');
        return;
      }
      closeModal();
      const serverRefCode = data?.data?.reference_code;
      showPengajuanSuccessModal(formData, serverRefCode);
      form.reset();
      generateCaptcha();
    })
    .catch(() => {
      // Graceful fallback for offline / standalone preview
      closeModal();
      showPengajuanSuccessModal(formData);
      form.reset();
      generateCaptcha();
    });
  });
}

// Global opener for Form Pengajuan
function openFormPengajuan(defaultPlafond, defaultTenor, defaultProduct) {
  const modal = document.getElementById('modal-pengajuan-kredit');
  if (!modal) return;

  if (defaultPlafond) {
    const nominalField = document.getElementById('form-nominal');
    if (nominalField) nominalField.value = defaultPlafond;
  }
  if (defaultTenor) {
    const tenorField = document.getElementById('form-tenor');
    if (tenorField) tenorField.value = defaultTenor;
  }
  if (defaultProduct) {
    const tujuanField = document.getElementById('form-tujuan');
    if (tujuanField) tujuanField.value = `Pengajuan Produk: ${defaultProduct}`;
  }

  modal.classList.add('active');
  document.body.classList.add('overflow-hidden');
}

// Success Modal Handler
function showPengajuanSuccessModal(data, serverRefCode) {
  const successModal = document.getElementById('modal-pengajuan-success');
  if (!successModal) return;

  const refCode = serverRefCode || `BPR-MJL-${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}-${Math.floor(1000 + Math.random() * 9000)}`;
  
  const refCodeEl = document.getElementById('success-ref-code');
  const detailsEl = document.getElementById('success-details-summary');
  const waBtnEl = document.getElementById('success-btn-wa-confirm');

  if (refCodeEl) refCodeEl.textContent = refCode;
  if (detailsEl) {
    detailsEl.innerHTML = `
      <div class="grid grid-cols-2 gap-2.5 text-sm text-left p-4 rounded-2xl bg-slate-50">
        <span class="text-slate-500">Nama Pemohon:</span>
        <span class="font-semibold text-slate-800">${data.nama}</span>
        <span class="text-slate-500">NIK KTP:</span>
        <span class="font-semibold text-slate-800">${data.nik}</span>
        <span class="text-slate-500">Besar Pengajuan:</span>
        <span class="font-bold text-bpr-navy">Rp ${parseInt(data.nominal, 10).toLocaleString('id-ID')}</span>
        <span class="text-slate-500">Jangka Waktu:</span>
        <span class="font-semibold text-slate-800">${data.tenor} Bulan</span>
        <span class="text-slate-500">Agunan:</span>
        <span class="font-semibold text-slate-800">${data.agunan}</span>
      </div>
    `;
  }

  if (waBtnEl) {
    const waText = encodeURIComponent(
      `Halo Customer Service BPR Majalengka, saya telah mengisi formulir permohonan kredit online:\n\n` +
      `• No. Registrasi: ${refCode}\n` +
      `• Nama: ${data.nama}\n` +
      `• NIK: ${data.nik}\n` +
      `• Nomor HP: ${data.hp}\n` +
      `• Plafon Diajukan: Rp ${parseInt(data.nominal, 10).toLocaleString('id-ID')}\n` +
      `• Tenor: ${data.tenor} Bulan\n` +
      `• Agunan: ${data.agunan}\n\n` +
      `Mohon dibantu konfirmasi tindak lanjut verifikasi berkas saya. Terima kasih.`
    );
    waBtnEl.href = `https://wa.me/6281111116526?text=${waText}`;
  }

  successModal.classList.add('active');
  document.body.classList.add('overflow-hidden');

  const closeSuccess = document.getElementById('modal-success-close');
  if (closeSuccess) {
    closeSuccess.onclick = () => {
      successModal.classList.remove('active');
      document.body.classList.remove('overflow-hidden');
    };
  }
}

/* ==========================================================================
   6B. BCA-STYLE INTERACTIVE PRODUCT SHOWCASE (TABS, SEARCH & GRID)
   ========================================================================== */
function initProductShowcase() {
  const tabs = document.querySelectorAll('.product-showcase-tab');
  const searchInput = document.getElementById('product-showcase-search');
  const clearBtn = document.getElementById('product-showcase-search-clear');
  const grid = document.getElementById('product-showcase-grid');
  const emptyState = document.getElementById('product-showcase-empty');

  if (!grid) return;

  let activeCategory = 'all';
  let searchQuery = '';

  const getWaUrl = (prod) => {
    const defaultWa = '6281111116526';
    const waNumber = (window.DYNAMIC_SETTINGS && window.DYNAMIC_SETTINGS.whatsapp_official ? window.DYNAMIC_SETTINGS.whatsapp_official : defaultWa).replace(/[^0-9]/g, '');
    const waMsg = encodeURIComponent(`Halo Perumda BPR Majalengka, saya tertarik untuk membuka / konsultasi produk ${prod.name} (${prod.code}). Mohon informasi persyaratan dan pembukaan rekening.`);
    return `https://wa.me/${waNumber}?text=${waMsg}`;
  };

  const createCardHtml = (prod) => {
    const isKredit = prod.category === 'kredit';
    const isDeposito = prod.category === 'deposito';
    const badgeCategory = isDeposito ? 'Deposito Berjangka' : (isKredit ? 'Kredit Usaha' : 'Tabungan');
    const badgeClass = isDeposito 
      ? 'bg-amber-50 text-amber-700' 
      : (isKredit 
        ? 'bg-emerald-50 text-emerald-700' 
        : 'bg-blue-50 text-bpr-blue');
    
    const benefit1 = Array.isArray(prod.ketentuan) && prod.ketentuan[0] 
      ? prod.ketentuan[0] 
      : 'Layanan cepat dan proses mudah di 12 kantor kas';
    const benefit2 = Array.isArray(prod.ketentuan) && prod.ketentuan[1] 
      ? prod.ketentuan[1] 
      : 'Aman dan dijamin Lembaga Penjamin Simpanan (LPS)';

    const actionPrimary = isKredit
      ? `<button type="button" onclick="openFormPengajuan(null, null, '${prod.name.replace(/'/g, "\\'")}')" class="w-full py-2.5 px-4 bg-bpr-blue hover:bg-bpr-blue-hover text-white font-bold text-xs rounded-xl shadow-sm hover:shadow transition-all text-center flex items-center justify-center gap-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>Ajukan Pinjaman</span>
        </button>`
      : `<a href="${getWaUrl(prod)}" target="_blank" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow transition-all text-center flex items-center justify-center gap-1.5">
          <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
          <span>Buka Rekening</span>
        </a>`;

    return `
      <div class="product-showcase-card bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-bpr-blue/50 transition-all flex flex-col justify-between group"
           data-category="${prod.category}"
           data-name="${(prod.name + ' ' + prod.code + ' ' + (prod.shortDesc || prod.short_desc || '')).toLowerCase()}">
        <div>
          <div class="flex items-center justify-between gap-2 mb-2">
            <span class="${badgeClass} font-bold text-xs px-2.5 py-1 rounded-lg">${badgeCategory}</span>
            <span class="font-mono text-xs text-slate-400 font-medium">${prod.code}</span>
          </div>

          <div class="h-44 rounded-xl bg-slate-50 flex items-center justify-center p-4 my-4 overflow-hidden group-hover:scale-[1.02] transition-transform">
            <img src="${prod.image}" alt="${prod.name}" class="object-contain max-h-full max-w-full" onerror="this.src='assets/img/logo.png'">
          </div>

          <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-bpr-blue transition-colors line-clamp-1" title="${prod.name}">
            ${prod.name}
          </h3>

          <p class="text-xs text-slate-600 line-clamp-2 mt-1 mb-4 leading-relaxed">
            ${prod.shortDesc || prod.short_desc || ''}
          </p>

          <div class="space-y-2 mb-6 pt-1 border-t border-slate-100/80">
            <div class="text-xs text-slate-700 flex items-start gap-2">
              <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              <span class="line-clamp-1">${benefit1}</span>
            </div>
            <div class="text-xs text-slate-700 flex items-start gap-2">
              <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              <span class="line-clamp-1">${benefit2}</span>
            </div>
          </div>
        </div>

        <div class="pt-4 mt-auto border-t border-slate-100 flex flex-col gap-2.5">
          ${actionPrimary}
          <button type="button" onclick="showProductDetail('${prod.code}')" class="w-full py-1.5 text-xs font-bold text-bpr-blue hover:text-bpr-navy transition-colors flex items-center justify-center gap-1 group-hover:underline">
            <span>Pelajari Lebih Lanjut</span>
            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
    `;
  };

  const existingCards = grid.querySelectorAll('.product-showcase-card');
  if (existingCards.length === 0) {
    const allProds = [
      ...PRODUCTS_DATA.tabungan,
      ...PRODUCTS_DATA.deposito,
      ...PRODUCTS_DATA.kredit
    ];
    grid.innerHTML = allProds.map(createCardHtml).join('');
  }

  const filterCards = () => {
    const cards = grid.querySelectorAll('.product-showcase-card');
    let visibleCount = 0;
    const searchTerms = searchQuery ? searchQuery.split(/\s+/).filter(Boolean) : [];

    cards.forEach(card => {
      const cardCategory = card.dataset.category;
      const cardName = card.dataset.name || '';

      const matchCategory = (activeCategory === 'all' || cardCategory === activeCategory);
      const matchSearch = searchTerms.length === 0 || searchTerms.every(term => cardName.includes(term));

      if (matchCategory && matchSearch) {
        card.classList.remove('hidden');
        visibleCount++;
      } else {
        card.classList.add('hidden');
      }
    });

    if (emptyState) {
      if (visibleCount === 0) {
        emptyState.classList.remove('hidden');
      } else {
        emptyState.classList.add('hidden');
      }
    }
  };

  const applyCategoryTab = (category) => {
    activeCategory = category || 'all';
    tabs.forEach(t => {
      const isSelected = (t.dataset.category || 'all') === activeCategory;
      if (isSelected) {
        t.classList.add('bg-bpr-blue', 'text-white', 'shadow-sm', 'font-bold');
        t.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60', 'font-semibold');
        t.setAttribute('aria-selected', 'true');
      } else {
        t.classList.remove('bg-bpr-blue', 'text-white', 'shadow-sm', 'font-bold');
        t.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60', 'font-semibold');
        t.setAttribute('aria-selected', 'false');
      }
    });
    filterCards();
  };

  // Tab click listeners
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      applyCategoryTab(tab.dataset.category || 'all');
    });
  });

  // Search input listeners
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      searchQuery = e.target.value.trim().toLowerCase();
      if (clearBtn) {
        if (searchQuery.length > 0) {
          clearBtn.classList.remove('hidden');
        } else {
          clearBtn.classList.add('hidden');
        }
      }
      filterCards();
    });

    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        searchInput.value = '';
        searchQuery = '';
        if (clearBtn) clearBtn.classList.add('hidden');
        filterCards();
      }
    });
  }

  // Clear button listener
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      if (searchInput) searchInput.value = '';
      searchQuery = '';
      clearBtn.classList.add('hidden');
      filterCards();
      if (searchInput) searchInput.focus();
    });
  }

  window.applyShowcaseCategoryTab = (category, clearSearch = false) => {
    if (clearSearch) {
      searchQuery = '';
      if (searchInput) searchInput.value = '';
      if (clearBtn) clearBtn.classList.add('hidden');
    }
    applyCategoryTab(category);
  };

  // Global reset helper
  window.resetProductFilter = function() {
    window.applyShowcaseCategoryTab('all', true);
  };

  // Programmatic tab filter helper
  window.filterProductShowcase = function(category = 'all') {
    window.applyShowcaseCategoryTab(category, false);
  };
}

/* ==========================================================================
   7. PRODUCT CATALOG & DETAIL MODAL
   (Sesuai Sketsa Halaman 3, 4, 5, 6, 7, 8)
   ========================================================================== */
function initProductModals() {
  const catalogModal = document.getElementById('modal-product-catalog');
  const catalogClose = document.getElementById('modal-catalog-close');
  const detailModal = document.getElementById('modal-product-detail');
  const detailClose = document.getElementById('modal-detail-close');
  const tabButtons = document.querySelectorAll('.catalog-tab-btn');
  const catalogSearch = document.getElementById('catalog-search-input');
  const catalogContainer = document.getElementById('catalog-cards-container');

  let activeCategory = 'all';

  // Render product cards inside catalog modal
  const renderCatalog = (filterText = '') => {
    if (!catalogContainer) return;
    catalogContainer.innerHTML = '';

    let allItems = [];
    if (activeCategory === 'all' || activeCategory === 'tabungan') allItems.push(...PRODUCTS_DATA.tabungan);
    if (activeCategory === 'all' || activeCategory === 'deposito') allItems.push(...PRODUCTS_DATA.deposito);
    if (activeCategory === 'all' || activeCategory === 'kredit') allItems.push(...PRODUCTS_DATA.kredit);

    if (filterText) {
      const q = filterText.toLowerCase();
      allItems = allItems.filter(item => 
        item.name.toLowerCase().includes(q) || 
        item.shortDesc.toLowerCase().includes(q) ||
        item.code.toLowerCase().includes(q)
      );
    }

    if (allItems.length === 0) {
      catalogContainer.innerHTML = `
        <div class="col-span-full py-12 text-center text-slate-500">
          <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p class="font-semibold">Produk tidak ditemukan</p>
          <p class="text-sm">Silakan ubah kata kunci pencarian atau ganti kategori produk.</p>
        </div>
      `;
      return;
    }

    allItems.forEach(prod => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded-2xl p-6 card-shadow card-shadow-hover flex flex-col justify-between cursor-pointer group';
      card.onclick = () => showProductDetail(prod);

      const categoryLabel = prod.category === 'tabungan' ? 'Tabungan' : prod.category === 'deposito' ? 'Deposito' : 'Kredit';
      const badgeColor = prod.category === 'tabungan' ? 'bg-blue-50 text-bpr-blue font-semibold' : prod.category === 'deposito' ? 'bg-amber-50 text-amber-700 font-semibold' : 'bg-emerald-50 text-emerald-700 font-semibold';

      card.innerHTML = `
        <div>
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold px-2.5 py-1 rounded-lg ${badgeColor}">${prod.badge || categoryLabel}</span>
            <span class="text-xs text-slate-400 font-mono">${prod.code}</span>
          </div>
          <div class="h-32 rounded-xl bg-slate-50 flex items-center justify-center p-3 mb-4 overflow-hidden group-hover:scale-[1.02] transition-transform">
            <img src="${prod.image}" alt="${prod.name}" class="max-h-full max-w-full object-contain" onerror="this.src='assets/img/logo.png'">
          </div>
          <h4 class="font-bold text-slate-900 group-hover:text-bpr-blue transition-colors text-base mb-1.5 leading-snug">${prod.name}</h4>
          <p class="text-slate-500 text-xs line-clamp-3 mb-4 leading-relaxed">${prod.shortDesc || prod.short_desc || ''}</p>
        </div>
        <div class="pt-3 mt-auto flex items-center justify-between">
          <span class="text-xs font-semibold text-bpr-blue group-hover:underline">
            Selengkapnya
          </span>
          <span class="text-xs text-slate-400 font-medium">BPR Majalengka</span>
        </div>
      `;
      catalogContainer.appendChild(card);
    });
  };

  // Tab switcher
  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      tabButtons.forEach(b => {
        b.classList.remove('bg-bpr-navy', 'text-white');
        b.classList.add('bg-slate-100', 'text-slate-600');
      });
      btn.classList.remove('bg-slate-100', 'text-slate-600');
      btn.classList.add('bg-bpr-navy', 'text-white');
      activeCategory = btn.dataset.category;
      renderCatalog(catalogSearch ? catalogSearch.value : '');
    });
  });

  // Search input
  if (catalogSearch) {
    catalogSearch.addEventListener('input', (e) => {
      renderCatalog(e.target.value);
    });
  }

  // Close catalog
  if (catalogClose) {
    catalogClose.addEventListener('click', () => {
      catalogModal.classList.remove('active');
      const anyActive = document.querySelector('.modal-backdrop.active');
      if (!anyActive) {
        document.body.classList.remove('overflow-hidden');
      }
    });
  }

  // Back button from detail to catalog
  const detailBack = document.getElementById('modal-detail-back');
  if (detailBack) {
    detailBack.addEventListener('click', () => {
      detailModal.classList.remove('active');
      if (catalogModal && catalogModal.classList.contains('was-open')) {
        catalogModal.classList.add('active');
        catalogModal.classList.remove('was-open');
        document.body.classList.add('overflow-hidden');
      } else {
        const anyActive = document.querySelector('.modal-backdrop.active');
        if (!anyActive) {
          document.body.classList.remove('overflow-hidden');
        }
      }
    });
  }

  // Close detail
  if (detailClose) {
    detailClose.addEventListener('click', () => {
      detailModal.classList.remove('active');
      if (catalogModal) catalogModal.classList.remove('was-open');
      const anyActive = document.querySelector('.modal-backdrop.active');
      if (!anyActive) {
        document.body.classList.remove('overflow-hidden');
      }
    });
  }
}

/* ==========================================================================
   8. NEWS & LITERASI DETAIL MODAL
   ========================================================================== */
function initNewsModal() {
  const newsModal = document.getElementById('modal-news-reader');
  const newsClose = document.getElementById('modal-news-close');

  if (newsClose && newsModal) {
    newsClose.addEventListener('click', () => {
      newsModal.classList.remove('active');
      const anyActive = document.querySelector('.modal-backdrop.active');
      if (!anyActive) {
        document.body.classList.remove('overflow-hidden');
      }
    });
  }

  const allNewsClose = document.getElementById('modal-all-news-close');
  if (allNewsClose) {
    allNewsClose.addEventListener('click', () => {
      const allNewsModal = document.getElementById('modal-all-news');
      if (allNewsModal) allNewsModal.classList.remove('active');
      const anyActive = document.querySelector('.modal-backdrop.active');
      if (!anyActive) {
        document.body.classList.remove('overflow-hidden');
      }
    });
  }
}

/* ==========================================================================
   9. ACCORDION FAQ LOGIC (Sesuai Sketsa Halaman 12)
   ========================================================================== */
function initFAQ() {
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const trigger = item.querySelector('.faq-trigger');
    const answer = item.querySelector('.faq-answer');
    const icon = item.querySelector('.faq-icon');

    if (!trigger || !answer) return;

    trigger.addEventListener('click', () => {
      const isOpen = !answer.classList.contains('hidden');

      // Close all other accordions
      document.querySelectorAll('.faq-answer').forEach(ans => ans.classList.add('hidden'));
      document.querySelectorAll('.faq-icon').forEach(ic => {
        ic.classList.remove('rotate-45');
      });

      if (!isOpen) {
        answer.classList.remove('hidden');
        if (icon) icon.classList.add('rotate-45');
      }
    });
  });
}

/* ==========================================================================
   10. KANTOR KAS DIRECTORY & SEARCH
   ========================================================================== */
function initKantorKas() {
  const container = document.getElementById('kantor-kas-grid');
  const searchInput = document.getElementById('kantor-kas-search');

  if (!container) return;

  const existingCards = container.querySelectorAll('.kantor-kas-card');

  const filterExisting = (query = '') => {
    const q = query.trim().toLowerCase();
    let visibleCount = 0;
    existingCards.forEach(card => {
      const name = card.dataset.name || '';
      const address = card.dataset.address || '';
      if (!q || name.includes(q) || address.includes(q)) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    let noResultsEl = document.getElementById('kantor-kas-no-results');
    if (visibleCount === 0) {
      if (!noResultsEl) {
        noResultsEl = document.createElement('div');
        noResultsEl.id = 'kantor-kas-no-results';
        noResultsEl.className = 'col-span-1 sm:col-span-2 lg:col-span-3 text-center py-12 text-slate-400 text-sm';
        noResultsEl.textContent = 'Tidak ditemukan kantor kas untuk pencarian "' + query + '".';
        container.appendChild(noResultsEl);
      }
      noResultsEl.style.display = '';
    } else if (noResultsEl) {
      noResultsEl.style.display = 'none';
    }
  };

  const renderFromData = (query = '') => {
    container.innerHTML = '';
    let filtered = BRANCHES_DATA;
    if (query) {
      const q = query.toLowerCase();
      filtered = BRANCHES_DATA.filter(b => 
        (b.name || '').toLowerCase().includes(q) || 
        (b.address || '').toLowerCase().includes(q)
      );
    }

    if (filtered.length === 0) {
      container.innerHTML = `<div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-12 text-slate-400 text-sm">Tidak ditemukan kantor kas untuk pencarian "${query}".</div>`;
      return;
    }

    filtered.forEach(branch => {
      const isMain = branch.isMain || branch.is_main || false;
      const branchType = branch.type || (isMain ? 'Pusat' : 'Kas');
      const branchHours = branch.hours || branch.operational_hours || 'Senin - Jumat: 08:00 - 15:30 WIB';
      const branchPhone = branch.phone || '(0233) 281144';
      const waNumber = (branch.whatsapp || '081111116526').replace(/[^0-9]/g, '');

      const card = document.createElement('div');
      card.className = `kantor-kas-card bg-white rounded-2xl p-6 ${isMain ? 'ring-2 ring-bpr-blue/20 bg-blue-50/20' : ''} card-shadow hover:shadow-lg transition-all flex flex-col justify-between`;
      card.innerHTML = `
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold px-2.5 py-1 rounded-lg ${isMain ? 'bg-bpr-blue text-white' : 'bg-slate-100 text-slate-700'}">${branchType}</span>
            <span class="text-xs text-emerald-700 font-semibold">Buka Layanan</span>
          </div>
          <h4 class="font-bold text-slate-900 text-base mb-1">${branch.name}</h4>
          <p class="text-slate-600 text-xs mb-3 leading-relaxed flex items-start gap-1.5">
            <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>${branch.address}</span>
          </p>
          <div class="text-xs text-slate-500 space-y-1 mb-4 pt-2">
            <p><strong>Telepon:</strong> ${branchPhone}</p>
            <p><strong>Jam Buka:</strong> ${branchHours}</p>
          </div>
        </div>
        <div class="flex items-center gap-2 pt-2">
          <a href="https://wa.me/${waNumber}?text=${encodeURIComponent('Halo ' + branch.name + ', saya butuh informasi layanan BPR.')}" target="_blank" class="flex-1 text-center py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
            Hubungi Kas
          </a>
          <a href="https://maps.google.com/?q=${encodeURIComponent(branch.address)}" target="_blank" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-xl flex items-center justify-center transition-colors" title="Buka di Google Maps">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        </div>
      `;
      container.appendChild(card);
    });
  };

  if (existingCards.length > 0) {
    if (searchInput) {
      searchInput.addEventListener('input', (e) => filterExisting(e.target.value));
    }
  } else {
    renderFromData();
    if (searchInput) {
      searchInput.addEventListener('input', (e) => renderFromData(e.target.value));
    }
  }
}

/* ==========================================================================
   11. KOLOM TANYA & PENCARIAN CEPAT (Sesuai Sketsa Halaman 1)
   ========================================================================== */
function initKolomTanya() {
  const searchInput = document.getElementById('hero-search-input');
  const searchBtn = document.getElementById('hero-search-btn');
  const resultsDropdown = document.getElementById('hero-search-results');
  if (!searchInput || !resultsDropdown) return;

  const performSearch = (query) => {
    const q = query.trim().toLowerCase();
    if (q.length < 2) {
      resultsDropdown.classList.add('hidden');
      resultsDropdown.innerHTML = '';
      return;
    }

    const allProducts = [
      ...PRODUCTS_DATA.tabungan,
      ...PRODUCTS_DATA.deposito,
      ...PRODUCTS_DATA.kredit
    ];

    const matchedProducts = allProducts.filter(p => 
      (p.name || '').toLowerCase().includes(q) ||
      (p.slogan || '').toLowerCase().includes(q) ||
      ((p.shortDesc || p.short_desc) || '').toLowerCase().includes(q) ||
      (p.code || '').toLowerCase().includes(q) ||
      (p.category || '').toLowerCase().includes(q)
    );

    const matchedBranches = BRANCHES_DATA.filter(b => 
      (b.name || '').toLowerCase().includes(q) || 
      (b.address || '').toLowerCase().includes(q)
    );

    const matchedArticles = ARTICLES_DATA.filter(a =>
      (a.title || '').toLowerCase().includes(q) ||
      (a.tag || a.category || '').toLowerCase().includes(q)
    );

    let html = '';

    if (matchedProducts.length > 0) {
      html += `<div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 rounded-lg mb-1">Produk Perbankan (${matchedProducts.length})</div>`;
      matchedProducts.slice(0, 4).forEach(p => {
        html += `
          <div class="search-result-item flex items-center justify-between p-2.5 hover:bg-blue-50/70 rounded-xl cursor-pointer transition-colors" data-type="product" data-id="${p.id}">
            <div class="flex items-center gap-2.5">
              <span class="w-7 h-7 rounded-lg bg-blue-100 text-bpr-blue text-xs font-bold flex items-center justify-center">${p.category === 'tabungan' ? 'TAB' : p.category === 'deposito' ? 'DEP' : 'KRD'}</span>
              <div>
                <p class="text-xs font-bold text-slate-800">${p.name}</p>
                <p class="text-[11px] text-slate-500">${p.badge || p.code} • ${p.category.toUpperCase()}</p>
              </div>
            </div>
            <span class="text-xs text-bpr-blue font-semibold">Lihat Detail</span>
          </div>
        `;
      });
    }

    if (matchedBranches.length > 0) {
      html += `<div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 rounded-lg my-1">Kantor Kas (${matchedBranches.length})</div>`;
      matchedBranches.slice(0, 3).forEach(b => {
        html += `
          <div class="search-result-item flex items-center justify-between p-2.5 hover:bg-emerald-50/70 rounded-xl cursor-pointer transition-colors" data-type="branch" data-name="${b.name}">
            <div class="flex items-center gap-2.5">
              <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center">KAS</span>
              <div>
                <p class="text-xs font-bold text-slate-800">${b.name}</p>
                <p class="text-[11px] text-slate-500">${b.address.slice(0, 45)}...</p>
              </div>
            </div>
            <span class="text-xs text-emerald-600 font-semibold">Lihat Lokasi</span>
          </div>
        `;
      });
    }

    if (matchedArticles.length > 0) {
      html += `<div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 rounded-lg my-1">Edukasi Finansial (${matchedArticles.length})</div>`;
      matchedArticles.slice(0, 2).forEach(a => {
        html += `
          <div class="search-result-item flex items-center justify-between p-2.5 hover:bg-amber-50/70 rounded-xl cursor-pointer transition-colors" data-type="article" data-id="${a.id}">
            <div class="flex items-center gap-2.5">
              <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 text-xs font-bold flex items-center justify-center">EDU</span>
              <div>
                <p class="text-xs font-bold text-slate-800">${a.title}</p>
                <p class="text-[11px] text-slate-500">${a.tag || a.category || 'Literasi'}</p>
              </div>
            </div>
            <span class="text-xs text-amber-600 font-semibold">Baca Artikel</span>
          </div>
        `;
      });
    }

    if (!html) {
      html = `
        <div class="py-6 text-center text-slate-500">
          <p class="text-xs font-semibold">Tidak ada hasil untuk "${query}"</p>
          <p class="text-[11px] text-slate-400 mt-1">Coba kata kunci: TAHARA, Deposito, Kredit PERAK, Kadipaten, atau Dana Darurat.</p>
        </div>
      `;
    }

    resultsDropdown.innerHTML = html;
    resultsDropdown.classList.remove('hidden');

    resultsDropdown.querySelectorAll('.search-result-item').forEach(item => {
      item.addEventListener('click', () => {
        const type = item.dataset.type;
        if (type === 'product') {
          const prodId = item.dataset.id;
          const prod = allProducts.find(p => p.id == prodId || p.slug === prodId || p.code === prodId);
          if (prod) {
            resultsDropdown.classList.add('hidden');
            showProductDetail(prod);
          }
        } else if (type === 'branch') {
          resultsDropdown.classList.add('hidden');
          const branchSection = document.getElementById('kantor-kas');
          if (branchSection) {
            branchSection.scrollIntoView({ behavior: 'smooth' });
            const kasSearch = document.getElementById('kantor-kas-search');
            if (kasSearch) {
              kasSearch.value = item.dataset.name.replace('Kantor Kas ', '');
              kasSearch.dispatchEvent(new Event('input'));
            }
          }
        } else if (type === 'article') {
          const artId = item.dataset.id;
          const art = ARTICLES_DATA.find(a => a.id == artId || a.slug === artId);
          resultsDropdown.classList.add('hidden');
          if (art) {
            readArticle(art.id);
          }
        }
      });
    });
  };

  searchInput.addEventListener('input', (e) => {
    performSearch(e.target.value);
  });

  if (searchBtn) {
    searchBtn.addEventListener('click', () => {
      performSearch(searchInput.value);
    });
  }

  document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
      resultsDropdown.classList.add('hidden');
    }
  });
}

/* ==========================================================================
   12. LAPORAN PUBLIKASI FILTER SEARCH (Sesuai Figma Section 9)
   ========================================================================== */
function initLaporanPublikasi() {
  const searchInput = document.getElementById('laporan-search');
  const reportCards = document.querySelectorAll('.laporan-card');
  if (!searchInput || reportCards.length === 0) return;

  searchInput.addEventListener('input', (e) => {
    const q = e.target.value.toLowerCase().trim();
    reportCards.forEach(card => {
      const text = card.textContent.toLowerCase();
      if (!q || text.includes(q)) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  });
}

/* ==========================================================================
   13. GLOBAL MODAL EVENT DELEGATION & SHORTCUTS
   ========================================================================== */
function initGlobalModals() {
  // Backdrop click dismissal for all modal elements
  const allModals = document.querySelectorAll('.modal-backdrop');
  allModals.forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('active');
        const anyActive = document.querySelector('.modal-backdrop.active');
        if (!anyActive) {
          document.body.classList.remove('overflow-hidden');
        }
      }
    });
  });

  // ESC Key listener to close top active modal
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const activeModals = document.querySelectorAll('.modal-backdrop.active');
      if (activeModals.length > 0) {
        activeModals[activeModals.length - 1].classList.remove('active');
        if (activeModals.length === 1) {
          document.body.classList.remove('overflow-hidden');
        }
      }
    }
  });

  // Event delegation for data-open-pengajuan
  document.querySelectorAll('[data-open-pengajuan]').forEach(btn => {
    btn.addEventListener('click', () => {
      openFormPengajuan();
    });
  });

  // Event delegation for data-open-catalog
  document.querySelectorAll('[data-open-catalog]').forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.openCatalog || 'all';
      openProductCatalog(cat);
    });
  });

  // Profile BPR modal close
  const profilClose = document.getElementById('modal-profil-close');
  if (profilClose) {
    profilClose.addEventListener('click', () => {
      const modal = document.getElementById('modal-profil-bpr');
      if (modal) modal.classList.remove('active');
      const anyActive = document.querySelector('.modal-backdrop.active');
      if (!anyActive) {
        document.body.classList.remove('overflow-hidden');
      }
    });
  }

  // Contact modal close
  const contactClose = document.getElementById('modal-kontak-close');
  if (contactClose) {
    contactClose.addEventListener('click', () => {
      const modal = document.getElementById('modal-kontak');
      if (modal) modal.classList.remove('active');
      const anyActive = document.querySelector('.modal-backdrop.active');
      if (!anyActive) {
        document.body.classList.remove('overflow-hidden');
      }
    });
  }
}

// Global opener for BPR Profile modal (Sesuai Sketsa Halaman 11: "Kenali BPR ↗")
function openProfilBpr() {
  const modal = document.getElementById('modal-profil-bpr');
  if (modal) {
    modal.classList.add('active');
    document.body.classList.add('overflow-hidden');
  } else {
    const aboutSection = document.getElementById('tentang-kami');
    if (aboutSection) aboutSection.scrollIntoView({ behavior: 'smooth' });
  }
}

// Global opener for Contact Modal (Sesuai Sketsa Halaman 13: "Kontak ↗")
function openContactOptions() {
  const modal = document.getElementById('modal-kontak');
  if (modal) {
    modal.classList.add('active');
    document.body.classList.add('overflow-hidden');
  } else {
    window.open('https://wa.me/6281111116526', '_blank');
  }
}

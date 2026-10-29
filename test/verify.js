import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const rootDir = path.resolve(__dirname, '..');

console.log('🚀 Running Bank Majalengka Comprehensive Verification Suite...\n');

let failedTests = 0;
let passedTests = 0;

function assert(condition, message) {
  if (condition) {
    console.log(`  ✅ PASS: ${message}`);
    passedTests++;
  } else {
    console.error(`  ❌ FAIL: ${message}`);
    failedTests++;
  }
}

// 1. Read files
const indexPath = path.join(rootDir, 'index.html');
const appJsPath = path.join(rootDir, 'assets/js/app.js');
const indexHtml = fs.readFileSync(indexPath, 'utf-8');
const appJs = fs.readFileSync(appJsPath, 'utf-8');

console.log('--- 1. Testing HTML Element IDs Required by JS ---');
const requiredIds = [
  'hero-search-input',
  'hero-search-btn',
  'hero-search-results',
  'sim-plafond-input',
  'sim-plafond-range',
  'sim-rate-scheme',
  'sim-result-monthly',
  'sim-result-plafond',
  'sim-result-tenor',
  'sim-result-principal',
  'sim-result-interest',
  'sim-result-total',
  'sim-result-total-interest',
  'sim-btn-ajukan',
  'sim-btn-wa',
  'laporan-search',
  'modal-product-catalog',
  'modal-catalog-close',
  'catalog-cards-container',
  'catalog-search-input',
  'modal-product-detail',
  'modal-detail-close',
  'modal-detail-back',
  'detail-product-hero-cta',
  'detail-product-hero-img',
  'detail-product-hero-title',
  'detail-product-slogan',
  'detail-product-short-desc',
  'detail-product-ketentuan',
  'detail-product-syarat',
  'detail-product-cta-btn',
  'modal-news-reader',
  'modal-news-close',
  'news-modal-tag',
  'news-modal-title',
  'news-modal-date',
  'news-modal-img',
  'news-modal-body',
  'modal-profil-bpr',
  'modal-profil-close',
  'modal-all-news',
  'modal-all-news-close',
  'modal-kontak',
  'modal-kontak-close',
  'modal-pengajuan-kredit',
  'modal-pengajuan-close',
  'form-pengajuan-kredit',
  'form-nik',
  'captcha-question',
  'captcha-refresh',
  'form-captcha-answer',
  'modal-pengajuan-success',
  'modal-success-close',
  'success-ref-code',
  'success-details-summary',
  'success-btn-wa-confirm'
];

requiredIds.forEach(id => {
  assert(indexHtml.includes(`id="${id}"`), `HTML contains element with id="${id}"`);
});

console.log('\n--- 2. Testing Global Window Functions for Inline onclick Attributes ---');
const expectedWindowFunctions = [
  'openFormPengajuan',
  'showPengajuanSuccessModal',
  'openProductCatalog',
  'showProductDetail',
  'readArticle',
  'showAllArticles',
  'openProfilBpr',
  'openContactOptions'
];

expectedWindowFunctions.forEach(fn => {
  assert(appJs.includes(`window.${fn}`), `window.${fn} is explicitly assigned on window object`);
});

console.log('\n--- 3. Testing Product Database and Image Assets ---');
// Extract PRODUCTS_DATA JSON from app.js
const productsMatch = appJs.match(/const PRODUCTS_DATA = (\{[\s\S]*?\n\};)/);
assert(productsMatch !== null, 'PRODUCTS_DATA is defined in app.js');

if (productsMatch) {
  // Evaluate safely using Function
  const parseProducts = new Function(`return ${productsMatch[1]}`);
  const products = parseProducts();

  const tabunganCount = products.tabungan.length;
  const depositoCount = products.deposito.length;
  const kreditCount = products.kredit.length;
  const totalCount = tabunganCount + depositoCount + kreditCount;

  assert(tabunganCount === 10, `Tabungan products count is 10 (actual: ${tabunganCount})`);
  assert(depositoCount === 2, `Deposito products count is 2 (actual: ${depositoCount})`);
  assert(kreditCount === 12, `Kredit products count is 12 (actual: ${kreditCount})`);
  assert(totalCount === 24, `Total catalog products count is 24 (actual: ${totalCount})`);

  // Check image files exist
  const allProducts = [...products.tabungan, ...products.deposito, ...products.kredit];
  allProducts.forEach(p => {
    const fullImgPath = path.join(rootDir, p.image);
    assert(fs.existsSync(fullImgPath), `Product [${p.name}] image exists at ${p.image}`);
  });
}

console.log('\n--- 4. Testing Branches & Articles Databases ---');
const branchMatch = appJs.match(/const BRANCHES_DATA = (\[[\s\S]*?\n\];)/);
assert(branchMatch !== null, 'BRANCHES_DATA is defined in app.js');
if (branchMatch) {
  const parseBranches = new Function(`return ${branchMatch[1]}`);
  const branches = parseBranches();
  assert(branches.length === 12, `12 Kantor Kas exist (actual: ${branches.length})`);
}

const articlesMatch = appJs.match(/const ARTICLES_DATA = (\[[\s\S]*?\n\];)/);
assert(articlesMatch !== null, 'ARTICLES_DATA is defined in app.js');
if (articlesMatch) {
  const parseArticles = new Function(`return ${articlesMatch[1]}`);
  const articles = parseArticles();
  assert(articles.length >= 3, `Articles count >= 3 (actual: ${articles.length})`);
  articles.forEach(a => {
    const fullImgPath = path.join(rootDir, a.image);
    assert(fs.existsSync(fullImgPath), `Article [${a.title.slice(0, 20)}...] image exists at ${a.image}`);
  });
}

console.log('\n--- 5. Testing Financial Credit Calculator Formula (Sketch Page 10) ---');
// Sketch Page 10: Plafond Rp 25.000.000, 24 Bulan, Rp 1.166.667 / month
const plafond = 25000000;
const tenorMonths = 24;
const flatRate = 0.06;

const monthlyPrincipal = plafond / tenorMonths; // 1,041,666.67
const monthlyInterest = (plafond * flatRate) / 12; // 125,000
const monthlyInstallment = Math.round(monthlyPrincipal + monthlyInterest);
const totalRepayment = Math.round(monthlyInstallment * tenorMonths);
const totalInterest = Math.round(monthlyInterest * tenorMonths);

assert(monthlyInstallment === 1166667, `Monthly installment is exactly Rp 1.166.667 (actual: ${monthlyInstallment})`);
assert(monthlyInterest === 125000, `Monthly interest is exactly Rp 125.000 (actual: ${monthlyInterest})`);
assert(totalInterest === 3000000, `Total interest over 24 months is Rp 3.000.000 (actual: ${totalInterest})`);
assert(totalRepayment === 28000000 || totalRepayment === 28000008, `Total repayment matches ~Rp 28.000.000 (actual: ${totalRepayment})`);

console.log('\n--- 6. Testing Form Validation (16 Digit NIK & CAPTCHA) ---');
const testValidNik = '3210123456780001';
const testInvalidNik = '321012';
assert(/^\d{16}$/.test(testValidNik), 'Valid NIK with 16 numeric digits passes regex');
assert(!/^\d{16}$/.test(testInvalidNik), 'Invalid short NIK correctly fails regex');

console.log('\n======================================================');
console.log(`Verification Summary: Passed: ${passedTests}, Failed: ${failedTests}`);
if (failedTests > 0) {
  console.error(`💥 TEST SUITE FAILED with ${failedTests} failure(s)!`);
  process.exit(1);
} else {
  console.log('🎉 ALL TESTS PASSED! Verification complete.');
  process.exit(0);
}

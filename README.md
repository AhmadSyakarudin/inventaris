# QA Automation Portfolio - Inventory Management System

![CI/CD Status](https://img.shields.io/badge/CI%2FCD-GitHub_Actions-2088FF?style=for-the-badge&logo=github-actions&logoColor=white)
![Playwright](https://img.shields.io/badge/E2E_Testing-Playwright-2EAD33?style=for-the-badge&logo=playwright&logoColor=white)
![PHPUnit](https://img.shields.io/badge/Backend_Testing-PHPUnit-4F5B93?style=for-the-badge&logo=php&logoColor=white)
![Artillery](https://img.shields.io/badge/Performance_Testing-Artillery-F22F46?style=for-the-badge)

Repository ini berisi _source code_ aplikasi Inventaris dari Laravel 12 dan sekaligus implementasi lengkap dari pengujian QA (Manual & Automation). Project ini dibuat untuk mendemonstrasikan end-to-end testing, backend testing, performance testing, dan CI/CD setup.

## 🛠️ Tech Stack & Testing Tools
- **App Framework:** Laravel 12 (PHP), MySQL
- **UI / E2E Automation:** Playwright (JavaScript)
- **Backend / Integration Testing:** PHPUnit
- **Performance Testing:** Artillery (Node.js CLI)
- **CI/CD:** GitHub Actions
- **Bug Reporting & Test Case:** Trello, Google Sheets

## 1. UI & End-to-End (E2E) Testing
Script E2E dibuat menggunakan **Playwright**. Berlokasi di `tests/playwright/`. 
Beberapa case utama yang di-cover:
- Validasi login dan session.
- Role-based access control (Admin vs Staff).
- CRUD Category, Item, dan Lending.
- Database constraint testing (Mencegah user menghapus barang yang sedang dalam status dipinjam).


## 2. Backend & Integration Testing
Pengujian backend logic tanpa browser menggunakan **PHPUnit**. Berlokasi di `tests/Feature/`.
Fokus pada:
- Response HTTP status codes (200, 302, 403).
- Validasi logika permission (Staff di-redirect jika mengakses halaman Admin).
- `AssertDatabaseHas` untuk memvalidasi perubahan data real-time.

## 3. Performance & Load Testing
Script performance testing berlokasi di `tests/performance/artillery.yml`.
- **Target:** Endpoint `/login`
- **Load:** 20 concurrent users/sec selama 20 detik (Total 450 request).
- **Result:** Menemukan _bottleneck_ server (PHP built-in server) yang mengalami `ECONNREFUSED` dan timeout pada beban tinggi. Laporan lengkap bisa dilihat di Trello.

> **Note on Tooling:** Load test di-setup menggunakan Artillery agar mudah diintegrasikan dengan Node.js/CI pipeline. Namun secara fundamental, konsep Virtual Users (VU), ramp-up, dan analisis _metric_ yang digunakan sama persis jika diimplementasikan menggunakan **JMeter**.


## 4. Manual Testing, BDD, & Bug Reports
Selain automation, dokumentasi QA manual juga disertakan:
- 🔗 **[Trello Board - Bug Reports & Tasks](https://trello.com/invite/b/66f0d3211dd003fded17c37a/ATTIf330dc8eb3ff94aee89c0ea1affb12c28D0AD4EC/qa-testing-project-inventaris)**
- 🔗 **[Google Sheets - Manual Test Cases](https://docs.google.com/spreadsheets/d/1WB5XbluOL0tkOqLF8a8qq9_fdpABgrqWFROGTQ6ZxKQ/edit?usp=sharing)**
- 📄 **BDD (Gherkin):** Contoh penulisan requirement berbasis BDD ada di `tests/bdd/features/login.feature` dan `barang.feature`.

---

## 🚀 How to Run Locally

Prerequisites: PHP 8.2+, Node.js 20+, Composer

```bash
# 1. Setup App
composer install
npm install
npm run build
php artisan migrate:fresh --seed
php artisan serve

# 2. Run Playwright Tests (di terminal baru)
npx playwright test --workers=1

# 3. Run PHPUnit Tests
php artisan test

# 4. Run Performance Test
npx artillery run tests/performance/artillery.yml
```

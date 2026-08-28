// tests/playwright/login.spec.js
import { test, expect } from "@playwright/test";
test("User bisa login", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    await page.fill('input[name="email"]', "staff@gmail.com");
    await page.fill('input[name="password"]', "password");
    await page.click('button:has-text("Login")');
    await expect(page).toHaveURL(/.*\/dashboard/);
});

test("User gagal login jika password salah", async ({ page }) => {
    // 1. Pergi ke halaman utama
    await page.goto("http://localhost:8000/login");
    
    // 2. Isi email benar, tapi passwordnya SALAH
    await page.fill('input[name="email"]', "staff@gmail.com");
    await page.fill('input[name="password"]', "password_salah_123");
    
    // 3. Klik tombol Login
    await page.click('button:has-text("Login")');
    
    // 4. Pastikan URL-nya TIDAK berubah ke dashboard
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

test("User gagal login jika format email tidak valid", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    
    // Isi email dengan format yang salah (tanpa @ dan domain)
    await page.fill('input[name="email"]', "hanyasebuahnamatanpadomain");
    await page.fill('input[name="password"]', "password");
    
    await page.click('button:has-text("Login")');
    
    // Pastikan tetap berada di halaman login (tidak ke dashboard)
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

test("User gagal login jika email tidak terdaftar", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    
    // Isi email dengan format yang benar, tapi TIDAK ADA di database
    await page.fill('input[name="email"]', "tidakada@domainapapun.com");
    await page.fill('input[name="password"]', "password");
    
    await page.click('button:has-text("Login")');
    
    // Pastikan tetap berada di halaman login
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

test("User gagal login jika form dibiarkan kosong", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    // Langsung klik login tanpa mengisi apapun
    await page.click('button:has-text("Login")');
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

test("User gagal login jika email kosong (hanya isi password)", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    // Email dikosongkan
    await page.fill('input[name="password"]', "password123");
    await page.click('button:has-text("Login")');
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

test("User gagal login jika password kosong (hanya isi email)", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    await page.fill('input[name="email"]', "staff@gmail.com");
    // Password dikosongkan
    await page.click('button:has-text("Login")');
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

test("Keamanan: Sistem kebal terhadap serangan SQL Injection dasar", async ({ page }) => {
    await page.goto("http://localhost:8000/login");
    // Memasukkan script peretasan standar
    await page.fill('input[name="email"]', "' OR '1'='1");
    await page.fill('input[name="password"]', "' OR '1'='1");
    
    await page.click('button:has-text("Login")');
    
    // Pastikan peretasan ditolak dan tidak masuk ke dashboard
    await expect(page).not.toHaveURL(/.*\/dashboard/);
});

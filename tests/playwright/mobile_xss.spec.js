import { test, expect, devices } from '@playwright/test';
import { loginAs } from './utils.js';

const xssPayload = `<script>alert('HACKED!')</script><img src="x" onerror="alert('HACKED!')">`;

// Paksa Playwright menyamar jadi HP Android (Pixel 5) buat semua tes di bawah
test.use({ ...devices['Pixel 5'] });

test.describe.serial("Pengujian Lanjutan: Mobile & Keamanan (XSS)", () => {

    test.describe("1. Mode Layar Handphone (Mobile Responsiveness)", () => {

        test("Cek tampilan Dashboard dan Menu Navigasi di layar sempit", async ({ page }) => {
            await loginAs(page, "admin@gmail.com", "password");
            await expect(page).toHaveURL(/.*\/dashboard/);

            // Coba cari dan tekan tombol "Hamburger" (menu samping khas HP)
            const menuBtn = page.locator('button.navbar-toggler, .navbar-toggler-icon, button:has-text("MENU"), button:has-text("☰")').first();
            
            if (await menuBtn.isVisible()) {
                await menuBtn.click();
            }

            // Pindah ke halaman items lewat HP
            await page.goto("http://localhost:8000/items");
            
            // Pastikan tabel ngga hilang/hancur
            const table = page.locator('table').first();
            await expect(table).toBeVisible();
        });
    });

    test.describe("2. Serangan Injeksi Script (XSS Attack)", () => {

        test("Sistem kebal terhadap injeksi tag HTML/Javascript nakal", async ({ page }) => {
            // Pasang jebakan pendeteksi Hacker
            let isHacked = false;
            page.on('dialog', async dialog => {
                if (dialog.message() === 'HACKED!') {
                    isHacked = true;
                }
                await dialog.dismiss();
            });

            await loginAs(page, "admin@gmail.com", "password");
            await page.goto("http://localhost:8000/categories/create");
            
            // Masukkan script peretas ke dalam input nama
            await page.fill('input[name="name"]', xssPayload);
            await page.selectOption('select[name="division_pj"]', { label: "Sarpras" });
            await page.click('button:has-text("Save")');

            // Cek apakah web meledak memunculkan pop-up HACKED di halaman daftar
            await page.goto("http://localhost:8000/categories");
            await page.waitForTimeout(2000); // Tunggu bentar buat ngeliat efek scriptnya

            // Pastikan web kebal (isHacked harus tetep FALSE)
            expect(isHacked).toBeFalsy();
        });
    });

});

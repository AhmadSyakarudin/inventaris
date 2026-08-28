import { test, expect } from '@playwright/test';
import { loginAs } from './utils.js';

test.describe.serial("Pengujian Lanjutan: Middleware (Hak Akses) & Fitur Download", () => {

    test.describe("1. Keamanan Middleware (Pembatasan Role)", () => {
        
        test("Staff dilarang masuk ke halaman pembuatan Barang (Items)", async ({ page }) => {
            // Login sebagai Staff
            await loginAs(page, "staff@gmail.com", "password");

            // Staff nakal mencoba meretas URL secara paksa lewat address bar
            await page.goto("http://localhost:8000/items/create");

            // Middleware Laravel harusnya langsung menendang Staff keluar dari URL itu
            await expect(page).not.toHaveURL("http://localhost:8000/items/create");
        });

        test("Admin dilarang masuk ke halaman Peminjaman (Lendings)", async ({ page }) => {
            // Login sebagai Admin
            await loginAs(page, "admin@gmail.com", "password");

            // Admin iseng nyoba masuk ke URL punya Staff
            await page.goto("http://localhost:8000/lendings/create");

            // Admin harus ditendang oleh middleware
            await expect(page).not.toHaveURL("http://localhost:8000/lendings/create");
        });
    });

    test.describe("2. Uji Coba Fitur Unduhan (Export Excel)", () => {

        test("Admin bisa mengunduh file Excel Data Kategori", async ({ page }) => {
            await loginAs(page, "admin@gmail.com", "password");
            await page.goto("http://localhost:8000/categories");

            // Suruh Playwright bersiap nangkap event file terdownload
            const downloadPromise = page.waitForEvent('download');
            
            // Klik tombol export
            await page.click('a:has-text("Export Excel")');
            
            // Tangkap filenya!
            const download = await downloadPromise;

            // Pastikan format filenya beneran Excel (.xlsx)
            const suggestedName = download.suggestedFilename();
            expect(suggestedName).toContain('.xlsx');
        });

    });

});

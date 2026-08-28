import { test, expect } from '@playwright/test';
import { loginAs } from './utils.js';

test.describe("Petugas Kebersihan (Cleanup Dummy Data)", () => {

    test("Sapu bersih semua data dummy (Items & Categories)", async ({ page }) => {
        // Kasih waktu agak lama (2 menit) karena tugas ngapus data bisa makan waktu
        test.setTimeout(120000); 

        await loginAs(page, "admin@gmail.com", "password");

        // Otomatis Accept semua pop-up konfirmasi hapus
        page.on('dialog', async dialog => {
            await dialog.accept();
        });

        // 1. Bersihin Items duluan (wajib, biar ngga kena error DB Constraint pas ngapus kategori)
        await page.goto("http://localhost:8000/items");
        
        let hasMoreItems = true;
        while (hasMoreItems) {
            // Target pencarian: Data yang namanya ada "Test", "Bahaya", atau angka panjang timestamp (17...)
            const dummyRow = page.locator('tr').filter({ hasText: /(17\d{11}|Test|Bahaya)/ }).first();
            
            if (await dummyRow.isVisible()) {
                await dummyRow.locator('button:has-text("Delete")').click();
                await page.waitForTimeout(1000); // Jeda dikit biar server ngga kaget
            } else {
                // Kalau di halaman ini udah ngga ada data sampah, coba klik Next ke halaman berikutnya
                const nextBtn = page.locator('a[aria-label="Next \\&raquo;"], a[rel="next"], [aria-label="Next"]').filter({ state: 'visible' }).first();
                
                if (await nextBtn.isVisible()) {
                    await nextBtn.click();
                    await page.waitForTimeout(1000);
                } else {
                    hasMoreItems = false; // Udah mentok halaman terakhir dan bersih!
                }
            }
        }


        // 2. Bersihin Categories (Logika sama persis kayak di atas)
        await page.goto("http://localhost:8000/categories");
        
        let hasMoreCategories = true;
        while (hasMoreCategories) {
            const dummyRow = page.locator('tr').filter({ hasText: /(17\d{11}|Test|Bahaya)/ }).first();
            
            if (await dummyRow.isVisible()) {
                await dummyRow.locator('button:has-text("Delete")').click();
                await page.waitForTimeout(1000);
            } else {
                const nextBtn = page.locator('a[aria-label="Next \\&raquo;"], a[rel="next"], [aria-label="Next"]').filter({ state: 'visible' }).first();
                
                if (await nextBtn.isVisible()) {
                    await nextBtn.click();
                    await page.waitForTimeout(1000);
                } else {
                    hasMoreCategories = false; // Bersih!
                }
            }
        }
    });

});

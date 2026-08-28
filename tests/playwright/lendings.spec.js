import { test, expect } from "@playwright/test";
import { loginAs } from "./utils.js";

// Bikin nama barang dummy buat dipinjam
const itemName = `Proyektor Test ${Date.now()}`;

test.describe.serial("Skenario Peminjaman (Lendings)", () => {
    
    test("1. Persiapan: Admin bikin barang dengan stok 5", async ({ page }) => {
        await loginAs(page, "admin@gmail.com", "password");

        await page.goto("http://localhost:8000/items/create");
        await page.fill('input[name="name"]', itemName);
        await page.locator('select[name="category_id"]').selectOption({ index: 1 });
        await page.fill('input[name="total"]', "5");
        await page.click('button:has-text("Save")');
        
        await expect(page).toHaveURL(/.*\/items/);
    });

    test("2. Negative Test: Gagal pinjam kalau lebih dari stok", async ({ page }) => {
        // Cuma Staff yang bisa minjem!
        await loginAs(page, "staff@gmail.com", "password");
        await page.goto("http://localhost:8000/lendings/create");
        
        await page.fill('input[name="borrower_name"]', "Siswa Budi");
        await page.locator('select.item-select').selectOption({ label: itemName });
        
        // Serakah pinjam 10 (padahal stok cuma 5)
        await page.fill('input.total-input', "10");
        await page.click('body'); // nge-blur biar JS ngitung
        
        // Alert merah harus nongol dan tombol Save harus kekunci (disabled)
        const errorMessage = page.locator('div.total-alert', { hasText: 'Total exceeds available stock!' });
        await expect(errorMessage).toBeVisible();
        await expect(page.locator('button:has-text("Save")')).toBeDisabled();
    });

    test("3. Positive Test: Bisa pinjam kalau stok cukup", async ({ page }) => {
        await loginAs(page, "staff@gmail.com", "password");
        await page.goto("http://localhost:8000/lendings/create");
        
        await page.fill('input[name="borrower_name"]', "Siswa Siti");
        await page.locator('select.item-select').selectOption({ label: itemName });
        
        // Pinjam 3 (masuk akal)
        await page.fill('input.total-input', "3");
        await page.click('body');
        
        // Tombol Save harus kebuka
        const errorMessage = page.locator('div.total-alert', { hasText: 'Total exceeds available stock!' });
        await expect(errorMessage).toBeHidden();
        
        const saveButton = page.locator('button:has-text("Save")');
        await expect(saveButton).toBeEnabled();
        
        await saveButton.click();
        await expect(page).toHaveURL(/.*\/lendings/);
    });

    test("4. Sukses: Balikin barang pinjaman (Status jadi Returned)", async ({ page }) => {
        await loginAs(page, "staff@gmail.com", "password");
        await page.goto("http://localhost:8000/lendings");
        
        // Otomatis klik OK kalau ada pop-up konfirmasi
        page.on('dialog', async dialog => {
            await dialog.accept();
        });

        // Cari baris barang yang lagi dipinjam
        const row = page.locator('tr', { hasText: itemName }).first();
        
        // Status awal harusnya masih belum balik
        await expect(row.locator('.badge')).toContainText('Not Returned');
        
        // Tekan tombol pengembalian
        await row.locator('button:has-text("Returned")').click();
        
        // Cek lagi setelah halamannya me-refresh
        const updatedRow = page.locator('tr', { hasText: itemName }).first();
        await expect(updatedRow.locator('.badge')).toContainText('Returned');
    });
    
});

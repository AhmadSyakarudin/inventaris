import { test, expect } from '@playwright/test';
import { loginAs, findRowAcrossPages } from './utils.js';

// Bikin nama unik biar datanya ngga bentrok pas ditest ulang
const categoryName = `Kategori Bahaya ${Date.now()}`;
const itemName = `Laptop Bahaya ${Date.now()}`;

// Tes Constraint: Mastiin barang yang lagi dipinjem ngga bisa dihapus admin.
test.describe.serial("Pengujian Keamanan Hapus Data (Constraint Testing)", () => {

    test("Tahap 1: Admin bikin barang, terus Staff minjem", async ({ page }) => {
        
        // 1. Admin bikin kategori
        await loginAs(page, "admin@gmail.com", "password");
        await page.goto("http://localhost:8000/categories");
        await page.click('a:has-text("+ Tambah Category")');
        await page.fill('input[name="name"]', categoryName);
        await page.selectOption('select[name="division_pj"]', { label: "Tata Usaha" });
        await page.click('button:has-text("Save")');

        // 2. Admin masukin barang baru ke kategori tadi
        await page.goto("http://localhost:8000/items/create");
        await page.fill('input[name="name"]', itemName);
        await page.selectOption('select[name="category_id"]', { label: categoryName });
        await page.fill('input[name="total"]', "10");
        await page.click('button:has-text("Save")');
        await expect(page).toHaveURL(/.*\/items/);

        // Logout paksa biar bisa ganti user
        await page.context().clearCookies();

        // 3. Staff minjem barang tersebut
        await loginAs(page, "staff@gmail.com", "password");
        await page.goto("http://localhost:8000/lendings/create");
        await page.fill('input[name="borrower_name"]', "Budi Sang Peminjam");
        
        // Trik: Ambil ID barang dari opsi dropdown, lalu select ID-nya
        const itemOption = page.locator(`select.item-select option`, { hasText: itemName });
        const itemValue = await itemOption.getAttribute('value');
        await page.locator('select.item-select').selectOption(itemValue);
        
        // Pinjem 2 unit dan pancing kalkulasi sisa stok (blur)
        await page.fill('input.total-input', "2"); 
        await page.locator('input.total-input').blur(); 
        await page.click('button:has-text("Save")');
    });

    test("Tahap 2: Admin nyoba ngapus barang yang masih dipinjem (Harusnya gagal & web aman)", async ({ page }) => {
        
        await loginAs(page, "admin@gmail.com", "password");
        await page.goto("http://localhost:8000/items");

        // Otomatis klik OK kalau ada pop-up konfirmasi hapus
        page.on('dialog', async dialog => {
            await dialog.accept();
        });

        // Cari barangnya di tabel dan klik Delete
        const row = await findRowAcrossPages(page, itemName);
        await row.locator('button:has-text("Delete")').click();
        
        // Pastiin ngga muncul layar error database bawaan Laravel
        await expect(page.locator('text=QueryException')).toBeHidden();
        await expect(page.locator('text=Integrity constraint violation')).toBeHidden();
        
        // Pastiin web tetep normal dan nge-redirect balik ke halaman items
        await expect(page).toHaveURL(/.*\/items/);
    });

});

import { test, expect } from '@playwright/test';
import { loginAs, findRowAcrossPages } from './utils.js';

// Bikin nama unik biar ngga bentrok
const categoryName = `Kategori Test ${Date.now()}`;
const categoryEditName = `Kategori Edit ${Date.now()}`;

test.beforeEach(async ({ page }) => {
    // Login otomatis sebagai admin
    await loginAs(page, "admin@gmail.com", "password");
});

test.describe.serial("Fitur CRUD Kategori (Categories)", () => {

    test("1. CREATE - Bisa menambahkan kategori baru", async ({ page }) => {
        await page.goto("http://localhost:8000/categories");
        await page.click('a:has-text("+ Tambah Category")');
        
        // Isi form kategori
        await page.fill('input[name="name"]', categoryName);
        await page.selectOption('select[name="division_pj"]', { label: "Tata Usaha" });
        await page.click('button:has-text("Save")');
        
        // Pastikan sukses redirect
        await expect(page).toHaveURL(/.*\/categories/);
        
        // Pastikan muncul notifikasi hijau
        await expect(page.locator('text=Category berhasil ditambahkan')).toBeVisible();
    });

    test("2. READ & UPDATE - Bisa mengedit kategori", async ({ page }) => {
        await page.goto("http://localhost:8000/categories");
        
        // Cari baris kategori di tabel (tembus paginasi) dan klik Edit
        const row = await findRowAcrossPages(page, categoryName);
        await row.locator('a:has-text("Edit")').click();
        
        // Edit nama kategori
        await page.fill('input[name="name"]', categoryEditName);
        await page.click('button:has-text("Update")'); 
        
        // Pastikan sukses redirect dan muncul notif
        await expect(page).toHaveURL(/.*\/categories/);
        await expect(page.locator('text=Category berhasil diupdate')).toBeVisible();
    });

    test("3. DELETE - Bisa menghapus kategori", async ({ page }) => {
        await page.goto("http://localhost:8000/categories");

        // Otomatis klik OK kalau ada pop-up konfirmasi
        page.on('dialog', async dialog => {
            await dialog.accept();
        });

        // Cari kategori dan klik Delete
        const row = await findRowAcrossPages(page, categoryEditName);
        await row.locator('button:has-text("Delete")').click();

        // Pastikan sukses terhapus
        await expect(page).toHaveURL(/.*\/categories/);
        await expect(page.locator('text=Category berhasil dihapus')).toBeVisible();
    });
});
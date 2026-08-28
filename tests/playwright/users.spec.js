import { test, expect } from "@playwright/test";
import { loginAs } from "./utils.js";

// Bikin email unik biar ngga nabrak pas dibikin ulang
const uniqueEmail = `staff_${Date.now()}@test.com`;
const userName = "Staf Penguji Cepat";

test.describe.serial("Skenario Manajemen Akun (Users)", () => {
    
    test.beforeEach(async ({ page }) => {
        // Harus login sebagai Admin buat ngelola user
        await loginAs(page, "admin@gmail.com", "password");
    });

    test("1. Admin bisa menambahkan user Staff baru", async ({ page }) => {
        await page.goto("http://localhost:8000/users");
        await page.click('a:has-text("+ Tambah User")');
        
        // Isi form nambah user
        await page.fill('input[name="name"]', userName);
        await page.fill('input[name="email"]', uniqueEmail);
        
        // Pilih akses sebagai staff
        await page.locator('select[name="role"]').selectOption('staff');
        await page.click('button:has-text("Simpan")');
        
        // Pastikan balik ke index dan nama staf muncul di tabel
        await expect(page).toHaveURL(/.*\/users/);
        await expect(page.locator('tr', { hasText: uniqueEmail }).first()).toBeVisible();
    });

    test("2. Admin bisa me-reset password Staff", async ({ page }) => {
        await page.goto("http://localhost:8000/users");
        
        // Otomatis klik OK kalau ada pop-up konfirmasi
        page.on('dialog', async dialog => {
            await dialog.accept();
        });

        // Cari baris staf yang baru kita bikin
        const row = page.locator('tr', { hasText: uniqueEmail }).first();
        
        // Tombol Reset cuma ada buat staff, klik!
        await row.locator('button:has-text("Reset Password")').click();
        
        // Pastikan aman ngga error 500
        await expect(page).toHaveURL(/.*\/users/);
    });

    test("3. Admin bisa menghapus akun Staff", async ({ page }) => {
        await page.goto("http://localhost:8000/users");
        
        page.on('dialog', async dialog => {
            await dialog.accept();
        });

        // Cari staf tersebut dan hapus
        const row = page.locator('tr', { hasText: uniqueEmail }).first();
        await row.locator('button:has-text("Hapus")').click();
        
        // Pastikan lenyap dari tabel
        await expect(page.locator('tr', { hasText: uniqueEmail })).toBeHidden();
    });

});

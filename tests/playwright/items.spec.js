import { test, expect } from "@playwright/test";
import { loginAs, findRowAcrossPages } from "./utils.js"; 

// Bikin nama unik biar ngga bentrok
const uniqueItemName = `Laptop Test ${Date.now()}`;

test.beforeEach(async ({ page }) => {
    // Login otomatis sebagai admin sebelum tiap tes
    await loginAs(page, "admin@gmail.com", "password");
});

test.describe.serial("Fitur CRUD Barang (Items)", () => {
    test("1. CREATE - Bisa menambahkan barang baru", async ({ page }) => {
        await page.goto("http://localhost:8000/items");
        await page.click('a:has-text("+ Add Item")');
        
        // Isi form barang
        await page.fill('input[name="name"]', uniqueItemName);
        await page.locator('select[name="category_id"]').selectOption({ index: 1 });
        await page.fill('input[name="total"]', "10");
        await page.click('button:has-text("Save")');
        
        // Pastikan sukses redirect dan barang muncul di tabel
        await expect(page).toHaveURL(/.*\/items/);
        const row = await findRowAcrossPages(page, uniqueItemName);
        await expect(row).toBeVisible();
    });

    test("2. READ & UPDATE - Bisa mengedit barang", async ({ page }) => {
        await page.goto("http://localhost:8000/items");
        
        // Cari barang di tabel dan klik Edit
        const row = await findRowAcrossPages(page, uniqueItemName);
        await row.locator('a:has-text("Edit")').click();
        
        // Edit stok jadi 15
        await page.fill('input[name="total"]', "15");
        await page.click('button:has-text("Update")'); 
        
        // Pastikan sukses redirect balik
        await expect(page).toHaveURL(/.*\/items/);
    });

    test("3. DELETE - Bisa menghapus barang", async ({ page }) => {
        await page.goto("http://localhost:8000/items");
        
        // Otomatis klik OK kalau ada pop-up konfirmasi
        page.on('dialog', async dialog => {
            await dialog.accept();
        });
        
        // Cari barang dan klik Delete
        const row = await findRowAcrossPages(page, uniqueItemName);
        await row.locator('button:has-text("Delete")').click();
        
        // Pastikan barang hilang dari tabel
        await expect(page.locator(`text=${uniqueItemName}`).first()).toBeHidden();
    });
});

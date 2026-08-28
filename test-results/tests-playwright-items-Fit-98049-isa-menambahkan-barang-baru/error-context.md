# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: tests\playwright\items.spec.js >> Fitur CRUD Barang (Items) >> 1. CREATE - Bisa menambahkan barang baru
- Location: tests\playwright\items.spec.js:13:5

# Error details

```
Test timeout of 30000ms exceeded.
```

```
Error: page.click: Test timeout of 30000ms exceeded.
Call log:
  - waiting for locator('a:has-text("+ Add Item")')

```

# Page snapshot

```yaml
- generic [ref=f2e3]:
  - generic [ref=f2e4]: Welcome
  - generic [ref=f2e9]:
    - heading "Login" [level=3] [ref=f2e10]
    - generic [ref=f2e11]:
      - generic [ref=f2e12]:
        - generic [ref=f2e13]: Email
        - textbox [ref=f2e14]
      - generic [ref=f2e15]:
        - generic [ref=f2e16]: Password
        - textbox [ref=f2e17]
      - button "Login" [ref=f2e18] [cursor=pointer]
```

# Test source

```ts
  1  | import { test, expect } from "@playwright/test";
  2  | import { loginAs, findRowAcrossPages } from "./utils.js"; 
  3  | 
  4  | // Bikin nama unik biar ngga bentrok
  5  | const uniqueItemName = `Laptop Test ${Date.now()}`;
  6  | 
  7  | test.beforeEach(async ({ page }) => {
  8  |     // Login otomatis sebagai admin sebelum tiap tes
  9  |     await loginAs(page, "admin@gmail.com", "password");
  10 | });
  11 | 
  12 | test.describe.serial("Fitur CRUD Barang (Items)", () => {
  13 |     test("1. CREATE - Bisa menambahkan barang baru", async ({ page }) => {
  14 |         await page.goto("http://localhost:8000/items");
> 15 |         await page.click('a:has-text("+ Add Item")');
     |                    ^ Error: page.click: Test timeout of 30000ms exceeded.
  16 |         
  17 |         // Isi form barang
  18 |         await page.fill('input[name="name"]', uniqueItemName);
  19 |         await page.locator('select[name="category_id"]').selectOption({ index: 1 });
  20 |         await page.fill('input[name="total"]', "10");
  21 |         await page.click('button:has-text("Save")');
  22 |         
  23 |         // Pastikan sukses redirect dan barang muncul di tabel
  24 |         await expect(page).toHaveURL(/.*\/items/);
  25 |         const row = await findRowAcrossPages(page, uniqueItemName);
  26 |         await expect(row).toBeVisible();
  27 |     });
  28 | 
  29 |     test("2. READ & UPDATE - Bisa mengedit barang", async ({ page }) => {
  30 |         await page.goto("http://localhost:8000/items");
  31 |         
  32 |         // Cari barang di tabel dan klik Edit
  33 |         const row = await findRowAcrossPages(page, uniqueItemName);
  34 |         await row.locator('a:has-text("Edit")').click();
  35 |         
  36 |         // Edit stok jadi 15
  37 |         await page.fill('input[name="total"]', "15");
  38 |         await page.click('button:has-text("Update")'); 
  39 |         
  40 |         // Pastikan sukses redirect balik
  41 |         await expect(page).toHaveURL(/.*\/items/);
  42 |     });
  43 | 
  44 |     test("3. DELETE - Bisa menghapus barang", async ({ page }) => {
  45 |         await page.goto("http://localhost:8000/items");
  46 |         
  47 |         // Otomatis klik OK kalau ada pop-up konfirmasi
  48 |         page.on('dialog', async dialog => {
  49 |             await dialog.accept();
  50 |         });
  51 |         
  52 |         // Cari barang dan klik Delete
  53 |         const row = await findRowAcrossPages(page, uniqueItemName);
  54 |         await row.locator('button:has-text("Delete")').click();
  55 |         
  56 |         // Pastikan barang hilang dari tabel
  57 |         await expect(page.locator(`text=${uniqueItemName}`).first()).toBeHidden();
  58 |     });
  59 | });
  60 | 
```
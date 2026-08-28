// File ini berisi fungsi-fungsi utilitas yang bisa dipake di berbagai test case Playwright

/**
 * Otomatis login pakai email & password yang disuplai
 * @param {import('@playwright/test').Page} page
 * @param {string} email
 * @param {string} password
 */
export async function loginAs(page, email, password) {
    await page.goto("http://localhost:8000/login");
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button:has-text("Login")');
    // Tunggu sampai beneran masuk ke dashboard biar proses login ngga kepotong (Race Condition)
    await page.waitForURL(/.*\/dashboard/);
}

/**
 * Nyari baris data spesifik di tabel, sekalipun datanya ngumpet di halaman 2 atau 3.
 * Dia bakal nge-klik tombol "Next" terus sampe datanya ketemu atau halamannya abis.
 * @param {import('@playwright/test').Page} page
 * @param {string} textToFind
 * @returns {import('@playwright/test').Locator}
 */
export async function findRowAcrossPages(page, textToFind) {
    let row = page.locator('tr', { hasText: textToFind }).first();
    
    while (await row.count() === 0) {
        // Kalau datanya ngga ketemu di halaman ini, coba klik Next
        const nextBtn = page.locator('a[aria-label="Next \\&raquo;"], a[rel="next"], [aria-label="Next"]').filter({ state: 'visible' }).first();
        
        if (await nextBtn.isVisible()) {
            await nextBtn.click();
            await page.waitForTimeout(1000); // Kasih jeda loading tabel
        } else {
            break; // Udah mentok halaman terakhir
        }
    }
    return row;
}

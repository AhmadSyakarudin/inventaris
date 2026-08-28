Feature: Manajemen Barang (Items)
  Sebagai seorang Admin Gudang
  Saya ingin mengelola data barang
  Agar inventaris tercatat dengan rapi dan aman dari kesalahan penghapusan

  Scenario: Menambahkan barang baru dengan stok valid
    Given saya login sebagai "Admin"
    And saya berada di halaman "Daftar Barang"
    When saya menekan tombol "Tambah Barang"
    And saya mengisi nama barang "Laptop Asus"
    And saya mengisi stok dengan angka "10"
    And saya menekan tombol "Simpan"
    Then saya melihat pesan sukses "Item berhasil ditambahkan"
    And barang "Laptop Asus" muncul di dalam tabel

  Scenario: Menolak input penambahan barang dengan stok minus (Negative Test)
    Given saya login sebagai "Admin"
    And saya berada di halaman form "Tambah Barang"
    When saya mengisi nama barang "Laptop Rusak"
    And saya mengisi stok dengan angka "-5"
    And saya menekan tombol "Simpan"
    Then sistem menolak penyimpanan
    And saya melihat pesan error "The total field must be at least 0"

  Scenario: Mencegah Admin menghapus barang yang sedang dipinjam (Constraint)
    Given terdapat barang "Proyektor" di dalam database
    And barang "Proyektor" tersebut sedang dalam status dipinjam oleh "Staff"
    And saya login sebagai "Admin"
    When saya menekan tombol "Delete" pada barang "Proyektor"
    And saya mengkonfirmasi pop-up penghapusan
    Then sistem membatalkan proses penghapusan
    And saya melihat pesan error "Gagal menghapus! Kategori ini memiliki barang yang sedang dipinjam"

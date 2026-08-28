Feature: Fitur Login Aplikasi Inventaris
  Sebagai seorang pengguna aplikasi (Admin atau Staff)
  Saya ingin bisa login ke dalam sistem
  Agar saya bisa mengakses fitur-fitur inventaris sesuai dengan role saya

  Scenario: User berhasil login dengan kredensial yang valid
    Given Saya berada di halaman login
    When Saya memasukkan email "admin@gmail.com"
    And Saya memasukkan password "password"
    And Saya menekan tombol "Login"
    Then Saya harus diarahkan ke halaman Dashboard
    And Saya melihat pesan "Selamat datang kembali"

  Scenario: User gagal login karena password salah
    Given Saya berada di halaman login
    When Saya memasukkan email "admin@gmail.com"
    And Saya memasukkan password "salah123"
    And Saya menekan tombol "Login"
    Then Saya tetap berada di halaman login
    And Saya melihat pesan error "Kredensial tidak valid"

  Scenario: Staff mencoba mengakses halaman yang tidak diizinkan
    Given Saya sudah login sebagai "staff"
    When Saya mencoba mengakses URL "/users"
    Then Saya ditolak dan dialihkan kembali
    And Saya melihat pesan peringatan "Anda tidak memiliki akses"

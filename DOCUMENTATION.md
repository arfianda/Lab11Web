# Lab11Web - Dokumentasi Lengkap

## 📋 Daftar Isi

1. [Deskripsi Project](#deskripsi-project)
2. [Struktur Folder](#struktur-folder)
3. [Sistem Routing](#sistem-routing)
4. [Core Classes](#core-classes)
5. [Modul-Modul](#modul-modul)
6. [Template & Layout](#template--layout)
7. [Konfigurasi Database](#konfigurasi-database)
8. [Panduan Penggunaan](#panduan-penggunaan)
9. [Penjelasan Fitur](#penjelasan-fitur)

---

## 🎯 Deskripsi Project

**Lab11Web** adalah sebuah framework PHP berbasis OOP (Object-Oriented Programming) yang sederhana namun lengkap. Framework ini dirancang untuk memenuhi kebutuhan praktikum pemrograman web dengan menerapkan konsep-konsep penting dalam PHP modern.

### Fitur Utama:

- ✅ Sistem routing dinamis berbasis URL
- ✅ Object-Oriented Architecture
- ✅ Database abstraction layer
- ✅ Dynamic form generator
- ✅ Modular design pattern
- ✅ Template management (Header, Footer, Sidebar)
- ✅ URL helper functions

---

## 📁 Struktur Folder

```
Lab11Web/
├── .git/                      # Git repository
├── .htaccess                  # Apache URL rewriting configuration
├── config.php                 # Database configuration
├── index.php                  # Front controller (routing center)
├── README.md                  # Project overview
├── DOCUMENTATION.md           # This file
│
├── class/                     # Core classes library
│   ├── Database.php          # Database abstraction class
│   └── Form.php              # Dynamic form generator class
│
├── module/                    # Application modules
│   ├── home/
│   │   └── index.php         # Home page
│   └── artikel/
│       ├── index.php         # List all users
│       ├── tambah.php        # Add new user form
│       └── ubah.php          # Edit user form
│
├── template/                  # Layout templates
│   ├── header.php            # HTML header & navigation
│   ├── sidebar.php           # Sidebar with module navigation
│   └── footer.php            # HTML footer
│
└── includes/                  # Helper functions
    └── url_helper.php        # URL generation helpers
```

### Penjelasan Setiap Folder:

| Folder      | Fungsi                                                                   |
| ----------- | ------------------------------------------------------------------------ |
| `class/`    | Berisi class-class utama untuk database dan form handling                |
| `module/`   | Berisi modul-modul aplikasi yang dapat diakses melalui routing           |
| `template/` | Berisi file-file template untuk layout halaman (header, footer, sidebar) |
| `includes/` | Berisi helper functions untuk memudahkan development                     |

---

## 🔀 Sistem Routing

### Bagaimana Routing Bekerja?

Framework menggunakan **single entry point routing** yang berpusat di `index.php`.

#### 1. Flow Routing

```
User Request (URL)
    ↓
.htaccess (URL Rewriting)
    ↓
index.php (Front Controller)
    ↓
Parse Path → Identify Module & Page
    ↓
Load Template (Header)
    ↓
Load Module File
    ↓
Load Template (Footer)
    ↓
Output to Browser
```

#### 2. URL Rewriting (.htaccess)

File `.htaccess` mengatur URL rewriting untuk Apache:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /Lab11Web/

# Jangan process jika request adalah file atau folder yang ada
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rewrite semua request ke index.php dengan path sebagai query string
RewriteRule ^(.+)$ index.php?path=$1 [QSA,L]
</IfModule>
```

**Penjelasan:**

- `RewriteEngine On`: Mengaktifkan mod_rewrite
- `RewriteBase /Lab11Web/`: Base path aplikasi
- `!-f` dan `!-d`: Bypass jika file/folder sudah ada
- `RewriteRule ^(.+)$`: Menangkap semua request dan redirect ke index.php

#### 3. Contoh URL dan Mapping

| URL                        | Path Parameter   | Module  | Page   | File                        |
| -------------------------- | ---------------- | ------- | ------ | --------------------------- |
| `/Lab11Web/`               | `home/index`     | home    | index  | `module/home/index.php`     |
| `/Lab11Web/home/index`     | `home/index`     | home    | index  | `module/home/index.php`     |
| `/Lab11Web/artikel/index`  | `artikel/index`  | artikel | index  | `module/artikel/index.php`  |
| `/Lab11Web/artikel/tambah` | `artikel/tambah` | artikel | tambah | `module/artikel/tambah.php` |
| `/Lab11Web/artikel/ubah`   | `artikel/ubah`   | artikel | ubah   | `module/artikel/ubah.php`   |

#### 4. Parsing Logic di index.php

```php
// 1. Ambil path dari query string
$path = isset($_GET['path']) ? $_GET['path'] : 'home/index';

// 2. Pecah path menjadi array (contoh: "artikel/tambah" → ["artikel", "tambah"])
$segments = explode('/', trim($path, '/'));

// 3. Tentukan module dan page
$mod = isset($segments[0]) ? $segments[0] : 'home';      // Module name
$page = isset($segments[1]) ? $segments[1] : 'index';    // Page name

// 4. Konstruksi path file
$file = "module/{$mod}/{$page}.php";  // Hasil: "module/artikel/tambah.php"

// 5. Load file jika ada, atau tampilkan error
if (file_exists($file)) {
    include $file;
} else {
    echo "Modul tidak ditemukan: $mod/$page";
}
```

---

## 🛠️ Core Classes

### 1. Database Class

**File:** `class/Database.php`

Class `Database` menangani semua operasi database menggunakan MySQLi.

#### Constructor

```php
$db = new Database();
// Otomatis membaca config dari config.php dan connect ke database
```

#### Methods

##### `query($sql)`

Menjalankan query SQL custom.

```php
$result = $db->query("SELECT * FROM users");
```

##### `get($table, $where = null)`

Mengambil data dari tabel dengan optional WHERE clause.

```php
// Ambil semua data dari tabel users
$all_users = $db->get('users');

// Ambil dengan kondisi WHERE
$user = $db->get('users', "id = 1");
```

**Return Value:**

- Array asosiatif jika 1 baris data
- Array of arrays jika lebih dari 1 baris
- `false` jika tidak ada data

##### `insert($table, $data)`

Menambah data baru ke tabel.

```php
$data = [
    'nama' => 'John Doe',
    'email' => 'john@example.com',
    'jenis_kelamin' => 'L'
];
$result = $db->insert('users', $data);

if ($result) {
    echo "Data berhasil disimpan";
} else {
    echo "Gagal menyimpan data";
}
```

##### `update($table, $data, $where)`

Mengupdate data yang sudah ada.

```php
$data = [
    'nama' => 'Jane Doe',
    'email' => 'jane@example.com'
];
$result = $db->update('users', $data, "id = 1");

if ($result) {
    echo "Data berhasil diupdate";
}
```

#### Contoh Penggunaan Lengkap

```php
<?php
$db = new Database();

// SELECT
$user = $db->get('users', "id = 5");
echo $user['nama']; // Akses field

// INSERT
$new_user = [
    'nama' => 'Ahmad',
    'email' => 'ahmad@mail.com',
    'jenis_kelamin' => 'L'
];
$db->insert('users', $new_user);

// UPDATE
$update_data = ['nama' => 'Ahmad Rizki'];
$db->update('users', $update_data, "id = 1");

// DELETE (via query)
$db->query("DELETE FROM users WHERE id = 2");
?>
```

---

### 2. Form Class

**File:** `class/Form.php`

Class `Form` menghubungkan form HTML dengan data secara otomatis. Mendukung berbagai tipe input field.

#### Constructor

```php
$form = new Form($action, $submit_button_text);
// Contoh:
$form = new Form(url('artikel/tambah'), "Simpan Data");
```

#### Methods

##### `addField($name, $label, $type, $options)`

Menambahkan field ke form.

**Parameters:**

- `$name` (string): Nama atribut input
- `$label` (string): Label untuk field
- `$type` (string): Tipe input (text, password, textarea, select, radio, checkbox)
- `$options` (array): Opsi untuk select/radio/checkbox, format: `['value' => 'Label']`

```php
// Text input
$form->addField("nama", "Nama Lengkap");

// Password input
$form->addField("password", "Password", "password");

// Radio buttons
$form->addField("jenis_kelamin", "Jenis Kelamin", "radio", [
    'L' => 'Laki-laki',
    'P' => 'Perempuan'
]);

// Select/Dropdown
$form->addField("agama", "Agama", "select", [
    'Islam' => 'Islam',
    'Kristen' => 'Kristen',
    'Katolik' => 'Katolik'
]);

// Checkbox (multiple)
$form->addField("hobi", "Hobi", "checkbox", [
    'Membaca' => 'Membaca',
    'Coding' => 'Coding',
    'Traveling' => 'Traveling'
]);

// Textarea
$form->addField("alamat", "Alamat", "textarea");
```

##### `displayForm()`

Menampilkan form ke output.

```php
$form->displayForm();
```

#### Contoh Penggunaan Lengkap

```php
<?php
$form = new Form("/Lab11Web/user/register", "Daftar");

$form->addField("nama", "Nama Lengkap");
$form->addField("email", "Email");
$form->addField("pass", "Password", "password");
$form->addField("gender", "Jenis Kelamin", "radio", [
    'M' => 'Pria',
    'F' => 'Wanita'
]);
$form->addField("hobi", "Hobi", "checkbox", [
    'Reading' => 'Membaca',
    'Gaming' => 'Gaming',
    'Sports' => 'Olahraga'
]);
$form->addField("alamat", "Alamat", "textarea");

$form->displayForm();
?>
```

**Output HTML:**

```html
<form action="/Lab11Web/user/register" method="POST">
  <table width="100%" border="0">
    <tr>
      <td align="right">Nama Lengkap</td>
      <td><input type="text" name="nama" /></td>
    </tr>
    <!-- ... form fields ... -->
    <tr>
      <td colspan="2">
        <input type="submit" value="Daftar" />
      </td>
    </tr>
  </table>
</form>
```

---

## 📦 Modul-Modul

### 1. Home Module

**File:** `module/home/index.php`

Halaman beranda/dashboard aplikasi. Berfungsi sebagai entry point yang menampilkan sambutan kepada user.

**Deskripsi:**

- Menampilkan welcome message
- Tidak melakukan akses database
- Berfungsi sebagai halaman default

**Output:**

```
Halaman Utama Aplikasi
Selamat datang di Framework Sederhana PHP OOP Anda.
```

---

### 2. Artikel Module

#### 2.1 `module/artikel/index.php` - Daftar Pengguna

**Fungsi:** Menampilkan tabel semua pengguna dari database dengan fitur delete.

**Fitur:**

- 📋 Menampilkan daftar semua users
- 🗑️ Delete user dengan konfirmasi
- ➕ Link "Tambah Data" untuk form input baru
- ✏️ Link "Edit" untuk mengubah data

**Workflow:**

```
1. Ambil request GET parameter 'hapus'
2. Jika ada, delete record dengan ID tersebut
3. Ambil semua data dari tabel users via Database::get()
4. Loop dan display dalam tabel HTML
5. Untuk setiap row, tampilkan tombol Edit & Delete
```

**Kode:**

```php
<?php
$db = new Database();

// Proses delete
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $hapus = $db->query("DELETE FROM users WHERE id = $id");
    if ($hapus) {
        echo "<div style='color:green'>Data berhasil dihapus</div>";
    }
}

// Ambil semua data
$data_users = $db->get('users');

// Display dalam tabel
?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>
    <?php foreach($data_users as $row): ?>
    <tr>
        <td><?php echo $row['nama']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td>
            <a href="ubah?id=<?php echo $row['id']; ?>">Edit</a>
            <a href="index?hapus=<?php echo $row['id']; ?>">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
```

---

#### 2.2 `module/artikel/tambah.php` - Form Tambah User

**Fungsi:** Menampilkan form untuk menambah user baru dan menyimpan ke database.

**Fitur:**

- 📝 Form input dengan berbagai tipe field
- 💾 Validasi dan penyimpanan data
- ✨ Support untuk checkbox array (hobi)
- 🔄 Feedback success/error

**Form Fields:**
| Field | Type | Tujuan |
|-------|------|--------|
| Nama Lengkap | Text | Nama pengguna |
| Email | Text | Email address |
| Password | Password | User password |
| Jenis Kelamin | Radio | L/P |
| Agama | Select | Islam, Kristen, dll |
| Hobi | Checkbox | Multiple selection |
| Alamat | Textarea | Alamat lengkap |

**Workflow:**

```
1. Create Form instance dengan action & submit button
2. Add fields ke form (nama, email, password, dll)
3. Cek apakah form di-submit ($_POST)
4. Jika POST, ambil data dan simpan via Database::insert()
5. Tampilkan success/error message
6. Display form untuk input
```

**Kode Form Creation:**

```php
<?php
$db = new Database();
$form = new Form(url('artikel/tambah'), "Simpan Data");

// Jika form di-submit
if ($_POST) {
    $data = [
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        'pass' => $_POST['pass'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'agama' => $_POST['agama'],
        'hobi' => isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '',
        'alamat' => $_POST['alamat'],
    ];

    $simpan = $db->insert('users', $data);
    if ($simpan) {
        echo "<div style='color:green'>Data berhasil disimpan!</div>";
    } else {
        echo "<div style='color:red'>Gagal menyimpan data</div>";
    }
}

// Buat form
$form->addField("nama", "Nama Lengkap");
$form->addField("email", "Email");
$form->addField("pass", "Password", "password");
// ... tambah fields lainnya
$form->displayForm();
?>
```

---

#### 2.3 `module/artikel/ubah.php` - Form Edit User

**Fungsi:** Menampilkan form edit dengan data lama user yang sudah terisi otomatis.

**Fitur:**

- 📝 Form pre-filled dengan data existing
- 💾 Update data ke database
- ✅ Validasi ID parameter
- 🔄 Feedback success/error

**Workflow:**

```
1. Ambil ID dari query string (?id=...)
2. Validasi ID (harus numeric dan ada di database)
3. Ambil data lama dari database
4. Pre-fill form dengan data lama
5. Jika POST (form submit), update data
6. Tampilkan success/error message
```

**Kode:**

```php
<?php
$db = new Database();

// 1. Ambil ID dari URL
$id_ubah = isset($_GET['id']) ? $_GET['id'] : 0;

if (!$id_ubah || !is_numeric($id_ubah)) {
    echo "ID tidak valid";
    die();
}

// 2. Ambil data lama
$data_lama = $db->get('users', "id = $id_ubah");
if (!$data_lama) {
    echo "Data tidak ditemukan";
    die();
}

// 3. Create form
$form = new Form("ubah?id=$id_ubah", "Simpan Perubahan");

// 4. Jika POST, update data
if ($_POST) {
    $data_baru = [
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        'pass' => $_POST['pass'],
        // ... field lainnya
    ];

    $update = $db->update('users', $data_baru, "id = $id_ubah");
    if ($update) {
        echo "<div style='color:green'>Data berhasil diupdate</div>";
    }
}

// 5. Display form dengan pre-filled values
$form->addField("nama", "Nama", "text");
// Pre-fill value jika ada data lama
echo "<input value='" . $data_lama['nama'] . "'>";
?>
```

---

## 🎨 Template & Layout

### Struktur Layout

Framework menggunakan template system dengan 3 bagian utama:

```
┌─────────────────────────────┐
│        header.php           │  <!-- HTML head, navigation -->
├──────────────┬──────────────┤
│  sidebar.php │  Module      │  <!-- Sidebar nav + Main content -->
│              │  Content     │
├──────────────┴──────────────┤
│        footer.php           │  <!-- Footer -->
└─────────────────────────────┘
```

### 1. Header Template

**File:** `template/header.php`

Menampilkan bagian atas halaman (HTML head tag, styling, dan main header).

**Fitur:**

- 📄 HTML5 DOCTYPE
- 🎨 CSS styling untuk layout
- 📱 Responsive design structure
- 🔤 Meta charset UTF-8

**CSS Classes:**

- `#wrapper`: Container utama
- `header`: Header section
- `#main-container`: Flex container untuk sidebar + content
- `#sidebar`: Navigation sidebar
- `#content`: Main content area
- `footer`: Footer section

---

### 2. Sidebar Template

**File:** `template/sidebar.php`

Navigation menu untuk mengakses berbagai modul.

**Konten:**

```php
<div id="sidebar">
    <h3>Navigasi Modul</h3>
    <ul>
        <li><a href="<?php echo url('home/index'); ?>">Home</a></li>
        <li><a href="<?php echo url('artikel/index'); ?>">Artikel</a></li>
        <li><a href="<?php echo url('artikel/tambah'); ?>">Tambah Artikel</a></li>
    </ul>
</div>
```

**Penggunaan URL Helper:**

- `url('home/index')` → `/Lab11Web/home/index`
- `url('artikel/index')` → `/Lab11Web/artikel/index`
- `url('artikel/tambah')` → `/Lab11Web/artikel/tambah`

---

### 3. Footer Template

**File:** `template/footer.php`

Menampilkan bagian bawah halaman dengan informasi copyright.

**Konten:**

```php
<footer>
    <p>&copy; <?php echo date("Y"); ?> Praktikum Pemrograman Web</p>
</footer>
</body>
</html>
```

---

## ⚙️ Konfigurasi Database

**File:** `config.php`

Mengandung konfigurasi koneksi database yang digunakan oleh class `Database`.

```php
<?php
$config = [
    'host' => 'localhost',      // Host MySQL
    'username' => 'root',        // Username MySQL
    'password' => '',            // Password MySQL (kosong untuk default)
    'db_name' => 'latihan_oop'  // Nama database
];
?>
```

### Cara Mengubah Konfigurasi

1. **Lokasi Database:**

   - Edit value `host` jika MySQL bukan di localhost
   - Default: `localhost`

2. **Username & Password:**

   - Default Laravel/Laragon: `root` tanpa password
   - Sesuaikan dengan konfigurasi MySQL Anda

3. **Database Name:**
   - Edit `db_name` untuk menggunakan database yang berbeda
   - Database harus sudah ada sebelumnya di MySQL

### Struktur Tabel yang Diperlukan

**Tabel: `users`**

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    jenis_kelamin CHAR(1),
    agama VARCHAR(20),
    hobi VARCHAR(255),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Field Description:**
| Field | Type | Purpose |
|-------|------|---------|
| id | INT | Primary key, auto increment |
| nama | VARCHAR(100) | Nama pengguna |
| email | VARCHAR(100) | Email address |
| pass | VARCHAR(255) | Password (hashed/plain) |
| jenis_kelamin | CHAR(1) | L atau P |
| agama | VARCHAR(20) | Agama pengguna |
| hobi | VARCHAR(255) | Hobi (comma-separated) |
| alamat | TEXT | Alamat lengkap |
| created_at | TIMESTAMP | Waktu record dibuat |

---

## 🚀 Panduan Penggunaan

### 1. Setup Initial

#### Prerequisites:

- PHP 7.0+ dengan MySQLi extension
- Apache dengan mod_rewrite enabled
- MySQL/MariaDB

#### Steps:

1. Copy project ke folder `htdocs` atau `www` (misal: `c:\laragon\www\Lab11Web`)
2. Create database baru:

   ```sql
   CREATE DATABASE latihan_oop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Create tabel users:

   ```sql
   USE latihan_oop;

   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       nama VARCHAR(100) NOT NULL,
       email VARCHAR(100) NOT NULL,
       pass VARCHAR(255),
       jenis_kelamin CHAR(1),
       agama VARCHAR(20),
       hobi VARCHAR(255),
       alamat TEXT
   );
   ```

4. Update `config.php` sesuai konfigurasi MySQL Anda
5. Akses: `http://localhost/Lab11Web/`

### 2. Menambah Module Baru

#### Step 1: Create Folder

```bash
mkdir module/nama_modul
```

#### Step 2: Create PHP File

File: `module/nama_modul/index.php`

```php
<?php
// Logika modul di sini
echo "Konten modul";
?>
```

#### Step 3: Access

URL: `http://localhost/Lab11Web/nama_modul/index`

#### Contoh: Membuat Module "Produk"

1. Create folder: `module/produk/`
2. Create `module/produk/index.php`:

```php
<?php
$db = new Database();
$produk = $db->get('produk');
?>
<h2>Daftar Produk</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Harga</th>
    </tr>
    <?php foreach($produk as $p): ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo $p['nama']; ?></td>
        <td><?php echo $p['harga']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
```

3. Access: `http://localhost/Lab11Web/produk/index`

### 3. Membuat Form Input

```php
<?php
$form = new Form(url('produk/tambah'), "Tambah Produk");

$form->addField("nama", "Nama Produk");
$form->addField("harga", "Harga");
$form->addField("kategori", "Kategori", "select", [
    'Elektronik' => 'Elektronik',
    'Pakaian' => 'Pakaian',
    'Makanan' => 'Makanan'
]);
$form->addField("deskripsi", "Deskripsi", "textarea");

$form->displayForm();
?>
```

### 4. Handling Form Submission

```php
<?php
if ($_POST) {
    // Validate
    if (empty($_POST['nama'])) {
        echo "Nama harus diisi";
    } else {
        // Insert
        $data = [
            'nama' => $_POST['nama'],
            'harga' => $_POST['harga'],
            'kategori' => $_POST['kategori'],
            'deskripsi' => $_POST['deskripsi']
        ];

        $result = $db->insert('produk', $data);

        if ($result) {
            echo "<div style='color:green'>Data berhasil disimpan</div>";
        } else {
            echo "<div style='color:red'>Gagal menyimpan data</div>";
        }
    }
}
?>
```

---

## ✨ Penjelasan Fitur

### 1. Dynamic Routing

**Keuntungan:**

- ✅ URL-friendly dan SEO-friendly
- ✅ Modular structure yang mudah diperluas
- ✅ Satu entry point (index.php) untuk semua request
- ✅ Automatic module loading

**Contoh Flow:**

```
User klik link: /Lab11Web/artikel/tambah
    ↓
.htaccess menangkap request
    ↓
Rewrite ke: index.php?path=artikel/tambah
    ↓
index.php parse path → modul=artikel, page=tambah
    ↓
Load file: module/artikel/tambah.php
    ↓
Display dengan header & footer
```

### 2. Object-Oriented Database Access

**Keuntungan:**

- ✅ Abstraction layer untuk database operations
- ✅ Reusable code (DRY principle)
- ✅ Mudah untuk di-extend ke query yang lebih complex
- ✅ Error handling yang consistent

**Penggunaan:**

```php
// Traditional way
$conn = mysqli_connect("localhost", "root", "", "db");
$result = mysqli_query($conn, "SELECT * FROM users");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['nama'];
}

// OOP way (Lab11Web)
$db = new Database();
$users = $db->get('users');
foreach ($users as $user) {
    echo $user['nama'];
}
```

### 3. Dynamic Form Generator

**Keuntungan:**

- ✅ Tidak perlu menulis form HTML secara manual
- ✅ Consistent form styling dan structure
- ✅ Easy to validate dan process
- ✅ Support berbagai tipe input

**Penggunaan:**

```php
$form = new Form($action, $submit_text);
$form->addField("field_name", "Field Label", "field_type", $options);
$form->displayForm();
```

### 4. URL Helper Functions

**Keuntungan:**

- ✅ Consistent URL generation
- ✅ Tidak perlu hardcode base path
- ✅ Easy to refactor jika struktur berubah
- ✅ Support untuk link generation

**Fungsi:**

```php
base_url()      // "/Lab11Web/"
url($path)      // "/Lab11Web/" . $path
current_path()  // Path dari query string
```

### 5. Template System

**Keuntungan:**

- ✅ Separation of concerns (HTML & Logic)
- ✅ Reusable header & footer
- ✅ Consistent layout di semua halaman
- ✅ Mudah untuk styling & branding

**Flow:**

```
header.php (load HTML + CSS)
  ↓
sidebar.php (load navigation)
  ↓
module/*/page.php (load content)
  ↓
footer.php (close HTML)
```

### 6. Modular Design

**Keuntungan:**

- ✅ Mudah menambah fitur baru (tambah module)
- ✅ Independent modules (tidak saling depend)
- ✅ Scalable (bisa handle banyak module)
- ✅ Easy to maintain & debug

**Struktur:**

```
module/
├── home/       (independen)
├── artikel/    (independen)
├── produk/     (independen - bisa ditambah)
├── user/       (independen - bisa ditambah)
└── admin/      (independen - bisa ditambah)
```

---

## 🔐 Security Notes

### ⚠️ Perhatian:

1. **SQL Injection Risk:**

   - Database class saat ini tidak menggunakan prepared statements
   - Gunakan `mysqli_real_escape_string()` atau prepared statements untuk production
   - Example:

   ```php
   $name = mysqli_real_escape_string($conn, $_POST['nama']);
   ```

2. **Password Security:**

   - Jangan simpan password plain text
   - Gunakan `password_hash()` dan `password_verify()`

   ```php
   $hashed = password_hash($_POST['pass'], PASSWORD_BCRYPT);
   $db->insert('users', ['pass' => $hashed]);
   ```

3. **Input Validation:**

   - Validasi semua input dari user
   - Sanitize sebelum display (XSS prevention)

   ```php
   echo htmlspecialchars($_POST['nama'], ENT_QUOTES, 'UTF-8');
   ```

4. **CSRF Protection:**
   - Implementasi CSRF tokens untuk form
   - Validasi origin dari request

---

## 📝 Troubleshooting

### Problem: "Modul tidak ditemukan"

**Solusi:**

- Cek apakah folder module sudah ada
- Cek apakah file page.php sudah dibuat
- Verifikasi URL yang diakses

### Problem: "Connection failed"

**Solusi:**

- Cek config.php (host, user, password, db_name)
- Cek MySQL sudah running
- Cek database sudah created

### Problem: Form tidak submit

**Solusi:**

- Cek form action URL benar
- Cek method="POST" di form tag
- Cek file modul bisa handle $\_POST

### Problem: 404 Not Found (.htaccess)

**Solusi:**

- Cek mod_rewrite aktif di Apache
- Cek RewriteBase sesuai dengan path aplikasi
- Cek .htaccess di root folder project

---

## 🎓 Learning Resources

### OOP Concepts Used:

1. **Class & Object** - Database & Form
2. **Encapsulation** - Private properties & methods
3. **Abstraction** - Database abstract layer
4. **Method Overloading** - Database::get() dengan optional WHERE
5. **Array Handling** - Processing form data

### Design Patterns Used:

1. **MVC Pattern** - Module structure
2. **Front Controller** - Single entry point (index.php)
3. **Template Pattern** - Header/Footer
4. **Factory Pattern** - Database connection

### PHP Concepts:

1. **Session** - `session_start()`
2. **Query String** - `$_GET['path']`
3. **Form Submission** - `$_POST`
4. **File Inclusion** - `include/require`
5. **String Functions** - `explode()`, `implode()`, `trim()`

---

## 📞 Support & Contribution

Untuk issue atau pertanyaan:

1. Check DOCUMENTATION.md ini
2. Review file-file source code dengan comment
3. Debug menggunakan browser developer tools

---

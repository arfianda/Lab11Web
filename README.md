# Lab11Web - Framework PHP OOP Modular

Framework PHP berbasis Object-Oriented Programming (OOP) yang sederhana, modular, dan mudah digunakan untuk pembelajaran dan pengembangan aplikasi web.

## 🎯 Fitur Utama

- ✅ **Dynamic Routing** - URL yang SEO-friendly dengan automatic module loading
- ✅ **Object-Oriented Architecture** - Menggunakan class untuk Database & Form
- ✅ **Database Abstraction Layer** - Simplified database operations
- ✅ **Dynamic Form Generator** - Buat form HTML dengan mudah tanpa hard-coding
- ✅ **Template System** - Separation of concerns dengan Header/Footer/Sidebar
- ✅ **Modular Design** - Tambah fitur baru dengan mudah
- ✅ **URL Helper Functions** - Consistent URL generation

## 📁 Struktur Quick Overview

```
Lab11Web/
├── index.php              ← Front Controller (Routing Center)
├── config.php             ← Database Configuration
├── .htaccess              ← URL Rewriting
├── class/
│   ├── Database.php       ← Database operations
│   └── Form.php           ← Form generator
├── module/                ← Application modules
│   ├── home/              ← Homepage
│   └── artikel/           ← User management (CRUD)
├── template/              ← Layout templates
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
└── includes/
    └── url_helper.php     ← URL helpers
```

## 🚀 Quick Start

### 1. Prerequisites

- PHP 7.0+
- MySQL/MariaDB
- Apache with mod_rewrite enabled

### 2. Installation

1. Copy folder ke `htdocs` atau `www` (misal: `C:\laragon\www\Lab11Web\`)
2. Create database:
   ```sql
   CREATE DATABASE latihan_oop CHARACTER SET utf8mb4;
   ```
3. Create tabel users:
   ```sql
   USE latihan_oop;
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       nama VARCHAR(100),
       email VARCHAR(100),
       pass VARCHAR(255),
       jenis_kelamin CHAR(1),
       agama VARCHAR(20),
       hobi VARCHAR(255),
       alamat TEXT
   );
   ```
4. Update `config.php` sesuai konfigurasi database Anda
5. Access: `http://localhost/Lab11Web/`

## 🔀 Routing System

Lab11Web menggunakan **single entry point routing**:

```
User URL Request
    ↓ (.htaccess rewriting)
index.php?path=module/page
    ↓ (Parse routing)
Load: module/module/page.php
    ↓ (With templates)
Display: header → sidebar → content → footer
```

### Contoh URL:

| URL                        | Maps to        | File                        |
| -------------------------- | -------------- | --------------------------- |
| `/Lab11Web/`               | home/index     | `module/home/index.php`     |
| `/Lab11Web/artikel/index`  | artikel/index  | `module/artikel/index.php`  |
| `/Lab11Web/artikel/tambah` | artikel/tambah | `module/artikel/tambah.php` |

## 💻 Core Classes

### Database Class

```php
$db = new Database();

// SELECT
$users = $db->get('users');
$user = $db->get('users', "id = 1");

// INSERT
$db->insert('users', ['nama' => 'John', 'email' => 'john@mail.com']);

// UPDATE
$db->update('users', ['nama' => 'Jane'], "id = 1");

// CUSTOM QUERY
$result = $db->query("SELECT * FROM users WHERE id = 1");
```

### Form Class

```php
$form = new Form(url('artikel/tambah'), "Simpan");

$form->addField("nama", "Nama Lengkap");
$form->addField("email", "Email");
$form->addField("pass", "Password", "password");
$form->addField("gender", "Jenis Kelamin", "radio", [
    'L' => 'Laki-laki',
    'P' => 'Perempuan'
]);
$form->addField("hobi", "Hobi", "checkbox", [
    'Membaca' => 'Membaca',
    'Gaming' => 'Gaming'
]);
$form->addField("alamat", "Alamat", "textarea");

$form->displayForm();
```

## 🎨 Template System

Framework menggunakan 3 template utama:

1. **header.php** - HTML head, CSS styling, main header
2. **sidebar.php** - Navigation menu
3. **footer.php** - Footer, close HTML tags

Ketiga template itu kemudian digabungkan sehingga membentuk menjadi tampilan seperti ini:

![](/docs/frontview.png)

Setiap module content otomatis di-wrap dengan template ini.

## 🔧 Menambah Module Baru

1. Create folder: `module/nama_modul/`
2. Create file: `module/nama_modul/index.php`
3. Buat konten/logika PHP
4. Access via URL: `/Lab11Web/nama_modul/index`

Contoh sederhana:

```php
<?php
// module/hello/index.php
echo "Halo, ini module baru!";
?>
```

Access: `http://localhost/Lab11Web/hello/index`

## 📚 Full Documentation

Untuk dokumentasi lengkap, baca file **`DOCUMENTATION.md`** yang berisi:

- 📋 Penjelasan detail struktur folder
- 🔀 Cara kerja routing system
- 🛠️ Reference lengkap untuk semua class & methods
- 📖 Panduan membuat module & form
- ⚙️ Database configuration
- 🔐 Security notes & best practices
- 🐛 Troubleshooting guide

## 🎓 Learning Path

1. **Basic:** Pahami routing di `index.php`
2. **Intermediate:** Pelajari Database class usage
3. **Advanced:** Buat module baru, implement form handling
4. **Expert:** Extend class dengan method baru, implement validation

## 📝 Project Structure Details

### Configuration Layer

- `config.php` - Database credentials

### Core Classes

- `class/Database.php` - OOP database wrapper
- `class/Form.php` - Dynamic form generator

### Application Logic

- `module/*/` - Module-specific logic & views

### Presentation Layer

- `template/` - Reusable layout templates

### Helper Layer

- `includes/url_helper.php` - URL generation utilities

## ⚠️ Security Reminders

- ❌ NOT production-ready (lacks prepared statements, input validation)
- ✅ Good for learning OOP & web development concepts
- 🔐 For production: Add SQL injection protection, password hashing, CSRF tokens

## 🔗 Quick Links

- **Database Docs** → See `class/Database.php`
- **Form Docs** → See `class/Form.php`
- **Module Example** → See `module/artikel/`
- **Templates** → See `template/`
- **Routing Logic** → See `index.php`

## 💡 Tips & Tricks

1. Use `url()` helper for all links:

   ```php
   <a href="<?php echo url('module/page'); ?>">Link</a>
   ```

2. Always check if data exists before displaying:

   ```php
   $user = $db->get('users', "id = 1");
   if ($user) {
       echo $user['nama'];
   }
   ```

3. Handle checkbox arrays with `implode()`:

   ```php
   $hobi = isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '';
   ```

4. Use `htmlspecialchars()` to prevent XSS:
   ```php
   echo htmlspecialchars($user['nama'], ENT_QUOTES, 'UTF-8');
   ```



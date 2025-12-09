# Lab11Web - Quick Reference Guide

## 🎯 Routing Examples

### Accessing Modules

```
URL: http://localhost/Lab11Web/home/index
├── Path parameter: home/index
├── Module: home
├── Page: index
└── File loaded: module/home/index.php

URL: http://localhost/Lab11Web/artikel/tambah
├── Path parameter: artikel/tambah
├── Module: artikel
├── Page: tambah
└── File loaded: module/artikel/tambah.php
```

## 💻 Code Snippets

### 1. Display Data from Database

```php
<?php
$db = new Database();
$users = $db->get('users');

if ($users) {
    foreach ($users as $user) {
        echo $user['nama'] . " (" . $user['email'] . ")<br>";
    }
} else {
    echo "Tidak ada data";
}
?>
```

### 2. Create & Display Form

```php
<?php
$form = new Form(url('artikel/tambah'), "Simpan");
$form->addField("nama", "Nama", "text");
$form->addField("email", "Email", "text");
$form->displayForm();

if ($_POST) {
    $data = ['nama' => $_POST['nama'], 'email' => $_POST['email']];
    $result = $db->insert('users', $data);
    echo $result ? "Sukses!" : "Gagal!";
}
?>
```

### 3. Display Single Record

```php
<?php
$db = new Database();
$user = $db->get('users', "id = 1");

if ($user) {
    echo "Nama: " . $user['nama'];
    echo "Email: " . $user['email'];
} else {
    echo "Data tidak ditemukan";
}
?>
```

### 4. Update Data

```php
<?php
$id = 1;
$data = ['nama' => 'Budi', 'email' => 'budi@mail.com'];
$result = $db->update('users', $data, "id = $id");

echo $result ? "Berhasil diupdate" : "Gagal update";
?>
```

### 5. Delete Data

```php
<?php
$id = 1;
$result = $db->query("DELETE FROM users WHERE id = $id");

echo $result ? "Berhasil dihapus" : "Gagal hapus";
?>
```

### 6. Generate Links

```php
<!-- Using url() helper -->
<a href="<?php echo url('module/page'); ?>">Link</a>
<a href="<?php echo url('artikel/index'); ?>">Daftar Artikel</a>
<a href="<?php echo url('artikel/tambah'); ?>">Tambah Artikel</a>

<!-- Results in -->
<a href="/Lab11Web/module/page">Link</a>
<a href="/Lab11Web/artikel/index">Daftar Artikel</a>
<a href="/Lab11Web/artikel/tambah">Tambah Artikel</a>
```

## 🔧 Form Field Types

### Text Input

```php
$form->addField("nama", "Nama Lengkap");
// HTML: <input type="text" name="nama">
```

### Password Input

```php
$form->addField("pass", "Password", "password");
// HTML: <input type="password" name="pass">
```

### Radio Buttons

```php
$form->addField("gender", "Jenis Kelamin", "radio", [
    'L' => 'Laki-laki',
    'P' => 'Perempuan'
]);
// HTML: <input type="radio" name="gender" value="L"> Laki-laki
//       <input type="radio" name="gender" value="P"> Perempuan
```

### Checkbox (Multiple)

```php
$form->addField("hobi", "Hobi", "checkbox", [
    'Membaca' => 'Membaca',
    'Gaming' => 'Gaming',
    'Olahraga' => 'Olahraga'
]);
// HTML: <input type="checkbox" name="hobi[]" value="Membaca"> Membaca
//       <input type="checkbox" name="hobi[]" value="Gaming"> Gaming
//       <input type="checkbox" name="hobi[]" value="Olahraga"> Olahraga

// Access in PHP:
$hobi = isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '';
// Result: "Membaca,Gaming"
```

### Select/Dropdown

```php
$form->addField("agama", "Agama", "select", [
    'Islam' => 'Islam',
    'Kristen' => 'Kristen',
    'Budha' => 'Budha'
]);
// HTML: <select name="agama">
//           <option value="Islam">Islam</option>
//           <option value="Kristen">Kristen</option>
//           <option value="Budha">Budha</option>
//       </select>
```

### Textarea

```php
$form->addField("alamat", "Alamat", "textarea");
// HTML: <textarea name="alamat" cols="30" rows="4"></textarea>
```

## 📊 Database Methods Reference

### get($table, $where = null)

```php
// Get all records
$all = $db->get('users');

// Get with condition
$one = $db->get('users', "id = 5");

// Multiple conditions
$some = $db->get('users', "agama = 'Islam' AND jenis_kelamin = 'L'");
```

### insert($table, $data)

```php
$data = [
    'nama' => 'Ahmad',
    'email' => 'ahmad@mail.com',
    'jenis_kelamin' => 'L'
];
$result = $db->insert('users', $data);
// Returns: true/false
```

### update($table, $data, $where)

```php
$data = ['nama' => 'Ahmad Budi'];
$result = $db->update('users', $data, "id = 1");
// Returns: true/false
```

### query($sql)

```php
$result = $db->query("SELECT * FROM users WHERE id = 1");
$result = $db->query("DELETE FROM users WHERE id = 5");
$result = $db->query("UPDATE users SET nama = 'Budi' WHERE id = 1");
// Returns: MySQLi result object or true/false
```

## 🎨 HTML Structure in Templates

### Header Template Structure

```html
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <title>Praktikum 11</title>
    <style>
      body {
        ...;
      }
      #wrapper {
        width: 90%;
        margin: 0 auto;
      }
      header {
        background: #333;
        color: white;
      }
      #main-container {
        display: flex;
      }
      #sidebar {
        width: 25%;
        ...;
      }
      #content {
        width: 75%;
        ...;
      }
      footer {
        ...;
      }
    </style>
  </head>
  <body>
    <div id="wrapper">
      <header>
        <h1>Praktikum 11 - Modular MVC</h1>
      </header>
      <div id="main-container"></div>
    </div>
  </body>
</html>
```

### Sidebar Menu

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

### Content Area

```php
<div id="content">
    <!-- Module content loaded here -->
    <h2>Page Title</h2>
    <p>Content dari module...</p>
</div>
```

### Footer

```html
    </div><!-- end main-container -->
</div><!-- end wrapper -->
<footer>
    <p>&copy; 2024 Praktikum Pemrograman Web</p>
</footer>
</body>
</html>
```

## 🐛 Common Issues & Solutions

### Issue: "Modul tidak ditemukan"

```
Penyebab: File module tidak ada atau path salah
Solusi:
1. Pastikan folder module/[nama]/ sudah ada
2. Pastikan file [nama].php ada di folder tersebut
3. Cek routing path di URL
```

### Issue: Form tidak submit

```
Penyebab: Form action URL tidak sesuai
Solusi:
1. Pastikan menggunakan url() helper: url('module/page')
2. Cek method="POST" di form tag
3. Cek $_POST handling di module
```

### Issue: Database connection error

```
Penyebab: config.php tidak sesuai
Solusi:
1. Update config.php dengan kredensial yang benar
2. Pastikan MySQL sudah running
3. Pastikan database sudah exist
```

### Issue: Data tidak tampil

```
Penyebab: Tabel belum ada atau query error
Solusi:
1. Cek apakah tabel sudah dicreate di database
2. Cek struktur tabel sesuai dengan query
3. Test query manual di phpMyAdmin
```

## 📋 Checklist Membuat Module Baru

- [ ] Create folder: `module/[nama]/`
- [ ] Create file: `module/[nama]/index.php`
- [ ] Write PHP logic (database access, form handling, etc)
- [ ] Test akses URL: `http://localhost/Lab11Web/[nama]/index`
- [ ] Update sidebar.php jika ingin menambah menu link
- [ ] Test semua fitur (create, read, update, delete)

## 🔐 Security Checklist

- [ ] Use `htmlspecialchars()` untuk output
- [ ] Use `mysqli_real_escape_string()` untuk input (atau prepared statements)
- [ ] Use `password_hash()` untuk menyimpan password
- [ ] Implement CSRF tokens untuk form
- [ ] Validate semua input dari user
- [ ] Use prepared statements untuk query kompleks

## 🚀 Performance Tips

1. **Cache database queries** jika data tidak sering berubah
2. **Use indexes** untuk kolom yang sering di-WHERE
3. **Minimize file includes** - load hanya yang diperlukan
4. **Combine CSS/JS** - reduce HTTP requests
5. **Enable compression** di Apache untuk response

## 📚 File Reference

| File                      | Purpose                          |
| ------------------------- | -------------------------------- |
| `index.php`               | Front controller & routing logic |
| `config.php`              | Database configuration           |
| `.htaccess`               | URL rewriting rules              |
| `class/Database.php`      | Database operations              |
| `class/Form.php`          | Form generation                  |
| `includes/url_helper.php` | URL helpers                      |
| `template/header.php`     | Page header & CSS                |
| `template/sidebar.php`    | Navigation menu                  |
| `template/footer.php`     | Page footer                      |
| `module/*/`               | Application logic                |

---

<?php
// Catatan: Asumsikan class Database dan Form sudah di-include di index.php
// Instance objek
$db = new Database();
// Action form diarahkan ke URL modul ini sendiri: artikel/tambah
$form = new Form(url('artikel/tambah'), "Simpan Data");

// Logika penyimpanan data jika tombol submit ditekan
if ($_POST) {
    // Siapkan data, termasuk penanganan array untuk checkbox 'hobi'
    $data = [
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        'pass' => $_POST['pass'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'agama' => $_POST['agama'],
        // Handle checkbox (diubah dari array menjadi string koma-separated)
        'hobi' => isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '',
        'alamat' => $_POST['alamat'],
    ];

    // Simpan ke tabel 'users'
    $simpan = $db->insert('users', $data);

    if ($simpan) {
        echo "<div style='color:green'>Data berhasil disimpan!</div>";
    } else {
        // Periksa apakah tabel 'users' sudah ada di database 'latihan_oop'
        echo "<div style='color:red'>Gagal menyimpan data. Pastikan tabel 'users' sudah ada.</div>";
    }
}
?>

<h3>Form Input User (OOP)</h3>
<?php
// Menampilkan Form
// 1. Input Text Biasa [cite: 267-269]
$form->addField("nama", "Nama Lengkap");
$form->addField("email", "Email");

// 2. Input Password [cite: 270-271]
$form->addField("pass", "Password", "password");

// 3. Input Radio Button (Jenis Kelamin) [cite: 272-277]
$form->addField("jenis_kelamin", "Jenis Kelamin", "radio", [
    'L' => 'Laki-laki',
    'P' => 'Perempuan'
]);

// 4. Input Select / Dropdown (Agama) [cite: 278-285]
$form->addField("agama", "Agama", "select", [
    'Islam' => 'Islam',
    'Kristen' => 'Kristen',
    'Katolik' => 'Katolik',
    'Hindu' => 'Hindu',
    'Budha' => 'Budha'
]);

// 5. Input Checkbox (Hobi) [cite: 286-291]
$form->addField("hobi", "Hobi", "checkbox", [
    'Membaca' => 'Membaca',
    'Coding' => 'Coding',
    'Traveling' => 'Traveling'
]);

// 6. Input Textarea (Alamat) [cite: 292-293]
$form->addField("alamat", "Alamat Lengkap", "textarea");

// Tampilkan Form [cite: 295]
$form->displayForm();
?>
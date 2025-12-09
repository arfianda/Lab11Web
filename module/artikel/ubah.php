<?php
// Asumsikan class Database dan Form sudah di-include di index.php
$db = new Database();

// 1. Ambil ID dari URL (segmen ketiga setelah index.php/artikel/ubah/123)
// Karena kita menggunakan parameter query (?id=...), kita tangkap dari $_GET
$id_ubah = isset($_GET['id']) ? $_GET['id'] : 0;

if (!$id_ubah || !is_numeric($id_ubah)) {
    echo "<div style='color:red;'>ID data tidak valid.</div>";
    // Hentikan eksekusi jika ID tidak valid
    die();
}

// 2. Ambil data lama berdasarkan ID
$data_lama = $db->get('users', "id = $id_ubah");

if (!$data_lama) {
    echo "<div style='color:red;'>Data tidak ditemukan.</div>";
    die();
}

// Action form diarahkan ke URL modul ini sendiri, plus parameter ID-nya
$form = new Form("ubah?id=$id_ubah", "Simpan Perubahan");

// 3. Logika Update Data
if ($_POST) {
    $data_baru = [
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        // Password tidak diubah jika kosong (logika sederhana)
        'pass' => !empty($_POST['pass']) ? $_POST['pass'] : $data_lama['pass'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'agama' => $_POST['agama'],
        'hobi' => isset($_POST['hobi']) ? implode(',', $_POST['hobi']) : '',
        'alamat' => $_POST['alamat'],
    ];

    // Lakukan Update ke tabel 'users'
    $update = $db->update('users', $data_baru, "id = $id_ubah");

    if ($update) {
        echo "<div style='color:green; padding: 10px; border: 1px solid green;'>Data berhasil diubah!</div>";
        // Refresh data_lama agar form menampilkan data terbaru
        $data_lama = $db->get('users', "id = $id_ubah");
    } else {
        echo "<div style='color:red; padding: 10px; border: 1px solid red;'>Gagal mengubah data.</div>";
    }
}
?>

<h2>Ubah Data Pengguna (ID: <?php echo $id_ubah; ?>)</h2>
<?php
// Fungsi internal untuk pre-fill nilai form
function get_value($field, $data)
{
    return isset($_POST[$field]) ? $_POST[$field] : $data[$field];
}

// 4. Menampilkan Form dengan nilai lama

// Nama Lengkap (Pre-filled)
$form->addField("nama", "Nama Lengkap", "text", [], get_value('nama', $data_lama));

// Email (Pre-filled)
$form->addField("email", "Email", "text", [], get_value('email', $data_lama));

// Password (Dikosongkan, user input hanya jika ingin ganti)
$form->addField("pass", "Password Baru", "password");

// Jenis Kelamin (Radio Button)
$jk_options = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
$selected_jk = get_value('jenis_kelamin', $data_lama);
// Note: Class Form yang kita buat belum mendukung pre-selection, jadi ini hanya display basic.
$form->addField("jenis_kelamin", "Jenis Kelamin", "radio", $jk_options);

// Agama (Select / Dropdown)
$agama_options = ['Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik', 'Hindu' => 'Hindu', 'Budha' => 'Budha'];
$selected_agama = get_value('agama', $data_lama);
$form->addField("agama", "Agama", "select", $agama_options);

// Hobi (Checkbox)
$hobi_options = ['Membaca' => 'Membaca', 'Coding' => 'Coding', 'Traveling' => 'Traveling'];
$selected_hobi = explode(',', get_value('hobi', $data_lama));
$form->addField("hobi", "Hobi", "checkbox", $hobi_options);

// Alamat (Textarea)
$form->addField("alamat", "Alamat Lengkap", "textarea", [], get_value('alamat', $data_lama));

// Tampilkan Form (Catatan: Anda mungkin perlu memodifikasi class Form.php 
// untuk mendukung pre-filling nilai default pada input)
$form->displayForm();
?>
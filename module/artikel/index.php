<?php
// Asumsikan class Database sudah di-include di index.php
$db = new Database();

// Logika Hapus Data (jika ada parameter 'hapus' di URL)
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $hapus = $db->query("DELETE FROM users WHERE id = $id");

    if ($hapus) {
        echo "<div style='color:green; padding: 10px; border: 1px solid green;'>Data dengan ID $id berhasil dihapus.</div>";
    } else {
        echo "<div style='color:red; padding: 10px; border: 1px solid red;'>Gagal menghapus data.</div>";
    }
}

// Ambil semua data dari tabel 'users'
$data_users = $db->get('users');
?>

<h2>Daftar Pengguna (Tabel Users)</h2>
<p><a href="tambah" style="padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px;">+ Tambah Data</a></p>

<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Jenis Kelamin</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($data_users): ?>
            <?php
            // Karena fungsi get() mengembalikan array jika data lebih dari satu,
            // dan array asosiatif jika hanya satu, kita harus pastikan itu array
            if (!isset($data_users[0])) {
                $data_users = [$data_users];
            }
            $no = 1;
            foreach ($data_users as $row):
            ?>
            <tr>
                <td align="center"><?php echo $no++; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td align="center"><?php echo $row['jenis_kelamin']; ?></td>
                <td align="center">
                    <a href="ubah?id=<?php echo $row['id']; ?>">Ubah</a> |
                    <a href="index?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" align="center">Tidak ada data pengguna ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
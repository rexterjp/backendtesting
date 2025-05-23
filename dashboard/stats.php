<?php
require_once '../config/database.php';
require_once '../auth/jwt.php';

verifyToken();

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    sendError('Koneksi database gagal', 500);
}

try {
    // Total barang
    $query = "SELECT COUNT(*) as total FROM data_barang";
    $result = $db->query($query);
    $totalBarang = $result->fetch_assoc()['total'];

    // Total barang masuk
    $query = "SELECT COUNT(*) as total FROM barang_masuk";
    $result = $db->query($query);
    $totalBarangMasuk = $result->fetch_assoc()['total'];

    // Total barang keluar
    $query = "SELECT COUNT(*) as total FROM barang_keluar";
    $result = $db->query($query);
    $totalBarangKeluar = $result->fetch_assoc()['total'];

    // Total jenis barang
    $query = "SELECT COUNT(*) as total FROM jenis_barang";
    $result = $db->query($query);
    $totalJenisBarang = $result->fetch_assoc()['total'];

    // Total satuan
    $query = "SELECT COUNT(*) as total FROM satuan";
    $result = $db->query($query);
    $totalSatuan = $result->fetch_assoc()['total'];

    // Total users
    $query = "SELECT COUNT(*) as total FROM users";
    $result = $db->query($query);
    $totalUsers = $result->fetch_assoc()['total'];

    // Barang stok minimum
    $query = "SELECT COUNT(*) as total FROM data_barang WHERE stok_current <= batas_minimum";
    $result = $db->query($query);
    $stokMinimum = $result->fetch_assoc()['total'];

    // List barang stok minimum
    $query = "SELECT db.nama_barang, db.stok_current, db.batas_minimum, s.nama_satuan 
              FROM data_barang db 
              JOIN satuan s ON db.satuan_id = s.id 
              WHERE db.stok_current <= db.batas_minimum 
              ORDER BY db.nama_barang";
    $result = $db->query($query);
    $barangStokMinimum = [];
    while ($row = $result->fetch_assoc()) {
        $barangStokMinimum[] = $row;
    }

    sendSuccess('Data dashboard berhasil diambil', [
        'totalBarang' => $totalBarang,
        'totalBarangMasuk' => $totalBarangMasuk,
        'totalBarangKeluar' => $totalBarangKeluar,
        'totalJenisBarang' => $totalJenisBarang,
        'totalSatuan' => $totalSatuan,
        'totalUsers' => $totalUsers,
        'stokMinimum' => $stokMinimum,
        'barangStokMinimum' => $barangStokMinimum
    ]);

} catch (Exception $e) {
    sendError('Terjadi kesalahan saat mengambil data: ' . $e->getMessage(), 500);
}

$database->closeConnection();
?>

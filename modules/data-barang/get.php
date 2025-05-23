<?php
require_once '../../config/database.php';
require_once '../../auth/jwt.php';

verifyToken();

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    sendError('Koneksi database gagal', 500);
}

try {
    $query = "SELECT db.*, jb.nama_jenis, s.nama_satuan 
              FROM data_barang db 
              JOIN jenis_barang jb ON db.jenis_barang_id = jb.id 
              JOIN satuan s ON db.satuan_id = s.id 
              ORDER BY db.nama_barang";
    
    $result = $db->query($query);
    $dataBarang = [];
    
    while ($row = $result->fetch_assoc()) {
        $dataBarang[] = $row;
    }
    
    sendSuccess('Data barang berhasil diambil', $dataBarang);
    
} catch (Exception $e) {
    sendError('Terjadi kesalahan saat mengambil data: ' . $e->getMessage(), 500);
}

$database->closeConnection();
?>

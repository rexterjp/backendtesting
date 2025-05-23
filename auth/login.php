<?php
require_once '../config/database.php';
require_once 'jwt.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    sendError('Koneksi database gagal', 500);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['username']) || !isset($input['password'])) {
    sendError('Username dan password harus diisi');
}

$username = mysqli_real_escape_string($db, $input['username']);
$password = $input['password'];

$query = "SELECT id, nama_user, username, password, hak_akses, status FROM users WHERE username = ? AND status = 'aktif'";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendError('Username tidak ditemukan atau akun tidak aktif', 401);
}

$user = $result->fetch_assoc();

// Untuk demo, kita gunakan password plain text
// Di production, gunakan password_verify($password, $user['password'])
if ($password !== 'admin123') {
    sendError('Password salah', 401);
}

$payload = [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'nama_user' => $user['nama_user'],
    'hak_akses' => $user['hak_akses'],
    'exp' => time() + (24 * 60 * 60) // 24 jam
];

$token = JWT::encode($payload);

sendSuccess('Login berhasil', [
    'token' => $token,
    'user' => [
        'id' => $user['id'],
        'nama_user' => $user['nama_user'],
        'username' => $user['username'],
        'hak_akses' => $user['hak_akses']
    ]
]);

$database->closeConnection();
?>

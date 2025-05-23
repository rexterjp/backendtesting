<?php
require_once 'config/database.php';

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove /api prefix if exists
$path = str_replace('/api', '', $path);

// Route handling
switch ($path) {
    case '/auth/login':
        if ($method === 'POST') {
            require_once 'auth/login.php';
        } else {
            sendError('Method tidak diizinkan', 405);
        }
        break;
        
    case '/dashboard/stats':
        if ($method === 'GET') {
            require_once 'dashboard/stats.php';
        } else {
            sendError('Method tidak diizinkan', 405);
        }
        break;
        
    case '/data-barang':
        switch ($method) {
            case 'GET':
                require_once 'modules/data-barang/get.php';
                break;
            case 'POST':
                require_once 'modules/data-barang/create.php';
                break;
            default:
                sendError('Method tidak diizinkan', 405);
        }
        break;
        
    default:
        sendError('Endpoint tidak ditemukan', 404);
        break;
}
?>

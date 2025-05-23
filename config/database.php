<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://www.mustikasembuluhlabs.my.id');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Database {
    private $host = 'localhost';
    private $db_name = 'mustikas_sembuluh_labs';
    private $username = 'mustikas_sembuluh_labs';
    private $password = 'WwWLHd$758]l';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset("utf8");
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            return null;
        }
        
        return $this->conn;
    }
    
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Response helper functions
function sendResponse($success, $message, $data = null, $code = 200) {
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

function sendError($message, $code = 400) {
    sendResponse(false, $message, null, $code);
}

function sendSuccess($message, $data = null) {
    sendResponse(true, $message, $data);
}
?>

<?php
class JWT {
    private static $secret_key = "mustika_sembuluh_labs_secret_key_2024";
    
    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $headerEncoded = self::base64urlEncode($header);
        $payloadEncoded = self::base64urlEncode($payload);
        
        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, self::$secret_key, true);
        $signatureEncoded = self::base64urlEncode($signature);
        
        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }
    
    public static function decode($jwt) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) != 3) {
            return false;
        }
        
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];
        
        $expiration = json_decode($payload)->exp;
        if ($expiration < time()) {
            return false;
        }
        
        $base64Header = self::base64urlEncode($header);
        $base64Payload = self::base64urlEncode($payload);
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, self::$secret_key, true);
        $base64Signature = self::base64urlEncode($signature);
        
        if ($base64Signature === $signatureProvided) {
            return json_decode($payload);
        }
        
        return false;
    }
    
    private static function base64urlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

function verifyToken() {
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    
    if (empty($authHeader)) {
        sendError('Token tidak ditemukan', 401);
    }
    
    $token = str_replace('Bearer ', '', $authHeader);
    $decoded = JWT::decode($token);
    
    if (!$decoded) {
        sendError('Token tidak valid', 401);
    }
    
    return $decoded;
}
?>

<?php
/**
 * Security Utilities Class
 * Handles password hashing, CSRF token generation, and input validation
 */
class Security {
    /**
     * Hash a password using bcrypt
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify a password against a hash
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Generate a CSRF token and store in session
     */
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token from POST request
     */
    public static function verifyCSRFToken($token = null) {
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? '';
        }
        
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    /**
     * Escape output to prevent XSS
     */
    public static function escape($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate and sanitize file upload
     */
    public static function validateFileUpload($file, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']) {
        $errors = [];

        // Check if file exists
        if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error.";
            return ['valid' => false, 'errors' => $errors];
        }

        // Get file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validate extension
        if (!in_array($ext, $allowedExtensions)) {
            $errors[] = "File type not allowed. Allowed types: " . implode(', ', $allowedExtensions);
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif'
        ];

        if (!array_key_exists($mime, $allowedMimes)) {
            $errors[] = "Invalid MIME type: $mime";
        }

        // Validate file size (max 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $errors[] = "File size exceeds maximum of 5MB.";
        }

        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }

        // Generate safe filename
        $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

        return [
            'valid' => true,
            'filename' => $filename,
            'original_name' => $file['name'],
            'mime' => $mime
        ];
    }

    /**
     * Validate integer input
     */
    public static function validateInt($value) {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * Validate email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
?>

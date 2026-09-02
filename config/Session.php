<?php
/**
 * Secure Session Configuration
 * Initialize session with secure settings
 */
class SessionManager {
    public static function init() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure cookie parameters
            session_set_cookie_params([
                'lifetime' => 3600,        // 1 hour
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',  // HTTPS only
                'httponly' => true,        // No JavaScript access
                'samesite' => 'Strict'     // CSRF protection
            ]);

            session_start();

            // Regenerate session ID to prevent fixation
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }
        }
    }

    public static function requireLogin() {
        self::init();
        if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
            header("Location: login.php");
            exit;
        }
    }

    public static function logout() {
        self::init();
        $_SESSION = [];
        session_destroy();
    }
}
?>

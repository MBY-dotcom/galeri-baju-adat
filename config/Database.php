<?php
/**
 * Secure Database Configuration Class
 * Centralizes database connection with prepared statements support
 */
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "db_bajuadat";
    private $koneksi;

    public function __construct() {
        $this->koneksi = new mysqli($this->host, $this->user, $this->password, $this->database);
        
        if ($this->koneksi->connect_error) {
            die("Connection failed: " . $this->koneksi->connect_error);
        }
        
        // Set charset to UTF-8
        $this->koneksi->set_charset("utf8mb4");
    }

    public function getConnection() {
        return $this->koneksi;
    }

    public function prepare($query) {
        return $this->koneksi->prepare($query);
    }

    public function close() {
        if ($this->koneksi) {
            $this->koneksi->close();
        }
    }

    public function __destruct() {
        $this->close();
    }
}
?>

<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json'); 

class Database {
    public $path = __DIR__ . '/.env';
    private $dbh; 

    public function __construct() {}

    public function loadEnv($path) {
        if (!file_exists($path)) {
            echo json_encode(['error' => 'Env file not found']);
            exit;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            [$name, $value] = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value);

            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }

    public function connect() {
        $dbHost = getenv('DB_HOST'); 
        $dbName = getenv('DB_NAME'); 
        $dbUser = getenv('DB_USER'); 
        $dbPass = getenv('DB_PASS'); 

        try {
            $this->dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
            return $this->dbh; 
        } catch (PDOException $e) {
            return false; 
        }
    }

    public function getDbUser() {
        return getenv('DB_USER');
    }
}
?>

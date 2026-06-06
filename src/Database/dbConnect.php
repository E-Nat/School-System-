<?php
function connectDB() {
    $host = '127.0.0.1';
    $db = 'school_system';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        ensureSchemaExists($pdo, $host, $user, $pass, $charset);
        return $pdo;
    } catch (PDOException $e) {
        if ($e->getCode() === 1049) {
            createDatabaseIfMissing($host, $user, $pass, $charset);
            $pdo = new PDO($dsn, $user, $pass, $options);
            ensureSchemaExists($pdo, $host, $user, $pass, $charset);
            return $pdo;
        }
        die('Database connection failed: ' . $e->getMessage());
    }
}

function ensureSchemaExists(PDO $pdo, $host, $user, $pass, $charset) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'audit_logs'");
        $tableExists = $stmt && $stmt->fetchColumn() !== false;
    } catch (PDOException $e) {
        $tableExists = false;
    }

    if (!$tableExists) {
        createDatabaseIfMissing($host, $user, $pass, $charset);
    }
}

function createDatabaseIfMissing($host, $user, $pass, $charset) {
    $file = __DIR__ . '/../database.sql';
    if (!file_exists($file)) {
        die('Database not found and database.sql is missing.');
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        die('Unable to read database.sql.');
    }

    // Remove UTF-8 BOM if present and normalize line endings.
    $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql);
    $sql = str_replace(["\r\n", "\r"], "\n", $sql);

    $mysqli = new mysqli($host, $user, $pass);
    if ($mysqli->connect_error) {
        die('MySQL connection failed: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset($charset);

    if (!$mysqli->multi_query($sql)) {
        die('Database creation failed: ' . $mysqli->error);
    }

    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

    $mysqli->close();
}

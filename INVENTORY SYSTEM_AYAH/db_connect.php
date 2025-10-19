<?php
// Hardened MySQLi connection with env support and utf8mb4 charset
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$name = getenv('DB_NAME') ?: 'inventory_db';

try {
    $conn = mysqli_connect($host, $user, $pass, $name);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('Database connection error.');
}

// Simple HTML escaping helper available to all included scripts
if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
?>

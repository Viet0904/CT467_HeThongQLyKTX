<?php
// DB credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PORT', '3306');
define('DB_PASS', '');
define('DB_NAME', 'htqlktx');
define("APPNAME", "Hệ thống quản lý KTX");
// Establish database connection.
try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

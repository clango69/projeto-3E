<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = getenv('MYSQLHOST') ?: 'localhost';
$db   = getenv('MYSQLDATABASE') ?: 'educonnect';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se o banco falhar, ele vai cuspir o erro real na tela em vez de dar erro 500 genérico
    die("Falha na conexão com o banco Linux: " . $e->getMessage());
}
?>

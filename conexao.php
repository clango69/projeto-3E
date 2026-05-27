<?php
// Lê as variáveis oficiais de ambiente geradas pelo MySQL do Railway
$host = getenv('MYSQLHOST') ?: '127.0.0.1';
$db   = getenv('MYSQLDATABASE') ?: 'railway';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    // Configura tempo limite curto para evitar que o servidor trave em loop (Timeout Erro 500)
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3, 
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ];
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, $options);
} catch (PDOException $e) {
    // Evita Erro 500: Se o banco falhar, imprime o erro de rede de forma limpa na tela
    echo "<div style='background:#fee2e2;color:#991b1b;padding:15px;font-family:sans-serif;margin:10px;border-radius:6px;'>";
    echo "<strong>Aviso do Sistema (EduConnect):</strong> Não foi possível conectar ao banco MySQL interno do Railway.<br>";
    echo "<em>Motivo técnico:</em> " . htmlspecialchars($e->getMessage()) . "<br><br>";
    echo "Certifique-se de que as Tabelas foram geradas no painel do Railway.";
    echo "</div>";
    $pdo = null; // Impede que o interpretador quebre o restante da página
}
?>

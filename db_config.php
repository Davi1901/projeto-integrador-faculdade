<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- CONFIGURAÇÃO DA URL BASE ---
$document_root = $_SERVER['DOCUMENT_ROOT'];
$dir_path = __DIR__;
$document_root = str_replace('\\', '/', $document_root);
$dir_path = str_replace('\\', '/', $dir_path);
$project_path = str_replace($document_root, '', $dir_path);
define('BASE_URL', $project_path);

// --- Configurações do Banco de Dados ---
define('DB_HOST', 'duhype-pi-zangadoboss21-e4fa.e.aivencloud.com');
define('DB_NAME', 'defaultdb');
define('DB_USER', 'avnadmin');
define('DB_PASS', 'AVNS_odJDXaOQE5PjgudajdR');
define('DB_CHARSET', 'utf8mb4');


define('DB_PORT', '21469'); 


define('DB_SSL_CA', __DIR__ . '/ca.pem'); 

// --- Conexão PDO ---

$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    
    PDO::MYSQL_ATTR_SSL_CA       => DB_SSL_CA,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false 
];

try {
     $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
     
} catch (\PDOException $e) {
     
     die('Falha na conexão com o banco de dados: ' . $e->getMessage());
}
?>
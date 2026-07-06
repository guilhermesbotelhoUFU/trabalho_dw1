<?php
// $db_host = 'localhost';
// $db_nome = 'vendacarros';
// $db_usuario = 'root';
// $db_senha = 'root';
$db_host = getenv('DB_HOST') ?: 'sql107.infinityfree.com';
$db_nome = getenv('DB_NOME') ?: 'if0_42448271_vendacarros';
$db_usuario = getenv('DB_USUARIO') ?: 'if0_42448271';
$db_senha = getenv('DB_SENHA') ?: 'VendaCarros123';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_nome;charset=utf8mb4",
        $db_usuario,
        $db_senha
    );
    // $pdo->exec('SET NAMES utf8');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // die('Erro ao conectar: ' . $e->getMessage());
    die('Erro ao conectar ao banco de dados.');
}

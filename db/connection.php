<?php
// Configurações gerais
$server = "localhost"; // server db
$user = "root"; // user db
$pwd = ""; // senha db
$bank = "crud"; // nome db

// Conexão
try {
    $pdo = new PDO("mysql:host=$server;dbname=$bank",$user,$pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Conectado com sucesso!";
} catch(PDOExcpetion $err) {
    echo "Falha ao se conectar com o banco " .$err->getMessage();
}

// Função para sanitizar (LIMPAR) entradas
function clearPost($dado) {
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);

    return $dado;
}
?>
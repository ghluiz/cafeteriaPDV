<?php
session_start();
require 'connection.php';

$cpf   = $_POST['CPFLog'];
$senha = $_POST['senhaLog'];

$sql = "SELECT id, nome, cpf, senha, imagem_perfil FROM usuarios WHERE cpf = '$cpf' AND senha = '$senha'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Salvando os dados essenciais na sessão
    $_SESSION['id_funcionario'] = $row['id'];
    $_SESSION['nome_funcionario'] = $row['nome'];
    $_SESSION['cpf'] = $row['cpf'];
    $_SESSION['nome'] = $row['nome'];
    $_SESSION['imagem_perfil'] = $row['imagem_perfil'];

    header("Location: paginaprincipal.php");
    exit();
} else {
    echo "<script>alert('CPF ou senha incorretos!'); window.history.back();</script>";
}
?>




?>
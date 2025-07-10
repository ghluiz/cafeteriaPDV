<?php
session_start();

if (!isset($_SESSION['senha'])) {
    header("Location: login.php");
    exit();
}

// Exemplo de uso:
echo "CPF logado: " . $_SESSION['cpf'];
echo "Nome: " . $_SESSION['nome'];
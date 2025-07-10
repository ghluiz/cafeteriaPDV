<?php
session_start();

// Simulação de login
$_SESSION['nome'] = $_SESSION['nome'] ?? 'Cliente Anônimo';

echo json_encode(['nome' => $_SESSION['nome']]);
?>

<?php
session_start();
$cpf = $_SESSION['cpf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['novaImagem']) && $_FILES['novaImagem']['error'] === 0) {
    $imagem = file_get_contents($_FILES['novaImagem']['tmp_name']);
    $base64 = base64_encode($imagem);

    $stmt = $conn->prepare("UPDATE usuarios SET imagem_perfil = ? WHERE cpf = ?");
    $stmt->bind_param("ss", $base64, $cpf);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$imagemSrc = 'imagens/perfil-padrao.png';
$stmt = $conn->prepare("SELECT imagem_perfil FROM usuarios WHERE cpf = ?");
$stmt->bind_param("s", $cpf);
$stmt->execute();
$stmt->bind_result($imagem_perfil);
$stmt->fetch();
$stmt->close();

if (!empty($imagem_perfil)) {
    $imagemSrc = 'data:image/jpeg;base64,' . $imagem_perfil;
}
?>
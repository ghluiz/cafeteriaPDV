<?php
session_start();

if (!isset($_SESSION['nome']) || !isset($_SESSION['cpf'])) {
    header("Location:login.php");
    exit();
}

$nome = $_SESSION['nome'];
$cpf = $_SESSION['cpf'];

require 'connection.php';

// ✅ SALVAR IMAGEM AO ENVIAR
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

// ✅ CARREGAR IMAGEM DO BANCO
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas informações </title>
    <link rel="stylesheet" href="pagsperfis.css">
</head>
<body> 
    <div class="grid">
        <header class="header">
            <nav id="nav">
                <img src="" alt="">
                <h1 class = "titulo"> Meu Perfil</h1>
            </nav>
            <div class="botoes">
                <a href="Pagperfil1.php" class="botao-header"> Minhas informações</a>
                <a href="Pagperfil2.php" class="botao-header"> Histórico de vendas</a>
                <a href="pagestoque.php" class="botao-header">Gestão de estoque</a>
            </div>
        </header>
        <div class="perfil-box">
            <form method="POST" enctype="multipart/form-data" id="formImagem">
                <label for="novaImagem" class="foto-perfil">
                    <img id="previewImagem" src="<?= $imagemSrc ?>" alt="Foto de perfil" />
                </label>
                <input type="file" name="novaImagem" id="novaImagem" accept="image/*" style="display: none;" onchange="previewImagem(event)">
            </form>

            <div class="info-horizontal">
                <div class="caixa-info" id="nomeUsuario"><?php echo $nome; ?></div>
                <div class="caixa-info" id="cpfUsuario"><?php echo $cpf; ?></div>
            </div>
        </div>
    </div>
   <script src="ativo.js"></script>
   <script src="inputfoto.js"></script>
</body>
</html>
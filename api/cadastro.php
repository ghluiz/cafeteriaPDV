<?php
require 'connection.php';
?>

<?php
require 'connection.php';

$nome    = $_POST['userNome'];
$cpf     = $_POST['userCPF'];
$senha   = $_POST['userSenha'];
$senhacon= $_POST['userSenhaCon'];

$sql = "INSERT INTO usuarios (nome, cpf, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql); // use $conn, não $conectar
if ($stmt === false) {
    die('Erro na preparação da consulta: ' . $conn->error);
}

$stmt->bind_param("sss", $nome, $cpf, $senha);




if (empty($cpf) || empty($nome) ||empty($senha)) {
    echo "<script>alert('Todos os campos são obrigatórios!'); window.history.back();</script>";
    exit;//se algum campo nao tiver nada vai rodar um alert em javascript, esse "window.history.back()" é pra voltar dps do alert
}
 elseif( ($senha) !== ($senhacon)){
    echo "<script>alert('As senha não estão iguais!'); window.history.back();</script>";
    exit;//se algum campo nao tiver nada vai rodar um alert em javascript, esse "window.history.back()" é pra voltar dps do alert

 }
 elseif ($stmt->execute()) {// se tudo tiver preenchido, tenta executar a consulta
    echo "<script>alert('Cadastro realizado com sucesso!'); window.history.back();</script>";//alert se o cadastro deu certo
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

// fechar a declaração
$stmt->close();
?>





<?php
// Define os dados de conexão com o banco de dados
$localhost = "mysql-sistemacafe.alwaysdata.net"; // Endereço do servidor MySQL
$user = "408159";                                // Nome de usuário do banco
$passw = "pdvcafeteria";                         // Senha do banco
$database = "sistemacafe_pdv";                   // Nome do banco de dados

// Cria a conexão com o banco de dados usando mysqli
$concetar = mysqli_connect($localhost, $user, $passw, $database);

// Verifica se a conexão falhou
if (!$concetar) {
    // Se falhou, encerra o script e mostra o erro
    die("Erro na conexão: " . mysqli_connect_error());
}

// Define o tipo de retorno da resposta como JSON e codificação UTF-8
header('Content-Type: application/json; charset=utf-8');

// Verifica se o parâmetro 'id' foi passado via GET
if (isset($_GET['id'])) {
    // Converte o parâmetro recebido em inteiro para evitar injeção
    $id = (int) $_GET['id'];

    // Prepara a query SQL com parâmetro (evita SQL Injection)
    $stmt = $concetar->prepare("SELECT Nome AS nome, preco, imagprod FROM Produto WHERE id = ?");
    
    // Liga o valor do ID ao parâmetro da query, especificando que é inteiro ("i")
    $stmt->bind_param("i", $id);
    
    // Executa a query
    $stmt->execute();
    
    // Armazena o resultado da consulta
    $res = $stmt->get_result();

    // Verifica se encontrou algum produto com o ID fornecido
    if ($res->num_rows > 0) {
        // Retorna os dados do produto em formato JSON
        echo json_encode($res->fetch_assoc());
    } else {
        // Caso não encontre o produto, retorna nome e preço padrão
        echo json_encode(["nome" => "Produto não encontrado", "preco" => "0.00"]);
    }

    // Fecha a consulta preparada e a conexão
    $stmt->close();
    $concetar->close();
} else {
    // Se o ID não foi informado na URL, retorna erro padrão
    echo json_encode(["nome" => "ID não informado", "preco" => "0.00"]);
}

?>

<?php
session_start();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$acao = $_GET['acao'] ?? '';

switch ($acao) {
    case 'listar':
        echo json_encode(['carrinho' => $_SESSION['carrinho']]);
        break;

    case 'adicionar':
        $id    = $_POST['id'];
        $nome  = $_POST['nome'];
        $preco = floatval($_POST['preco']);

        if (!isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = ['nome' => $nome, 'preco' => $preco, 'qty' => 1];
        } else {
            $_SESSION['carrinho'][$id]['qty']++;
        }

        echo json_encode(['carrinho' => $_SESSION['carrinho']]);
        break;

    case 'alterar':
        $id    = $_GET['id'];
        $delta = intval($_GET['delta']);

        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]['qty'] += $delta;

            if ($_SESSION['carrinho'][$id]['qty'] <= 0) {
                unset($_SESSION['carrinho'][$id]);
            }
        }

        echo json_encode(['carrinho' => $_SESSION['carrinho']]);
        break;

    default:
        echo json_encode(['erro' => 'Ação inválida']);
        break;
}
?>

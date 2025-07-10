<?php
session_start();

$localhost = "mysql-sistemacafe.alwaysdata.net";
$user      = "408159";
$passw     = "pdvcafeteria";
$database  = "sistemacafe_pdv";

$conn = new mysqli($localhost, $user, $passw, $database);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$id_funcionario   = $_SESSION['id_funcionario'] ?? null;
$nome_funcionario = $_SESSION['nome_funcionario'] ?? null;
$carrinho         = $_SESSION['carrinho'] ?? [];
$total            = 0;

// Calcular o total do carrinho
foreach ($carrinho as $item) {
    if (!is_array($item)) continue;
    $subtotal = floatval($item['preco']) * intval($item['qty']);
    $total += $subtotal;
}

// Salvar no banco se funcionário estiver logado
if ($id_funcionario && $nome_funcionario) {
    // Inserir a venda no histórico
    $stmt = $conn->prepare("INSERT INTO histórico_vendas (id_funcionario, nome_funcionario, data, valor) VALUES (?, ?, NOW(), ?)");
    $stmt->bind_param("isd", $id_funcionario, $nome_funcionario, $total);

    if (!$stmt->execute()) {
        die("Erro no INSERT da venda: " . $stmt->error);
    }

    $id_venda = $conn->insert_id;
    $stmt->close();

    // Inserir os itens da venda
    $stmtItem = $conn->prepare("INSERT INTO itens_venda (id_venda, id_produto, nome, preco, quantidade, subtotal) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($carrinho as $id_prod => $item) {
        if (!is_array($item)) continue;

        $nome     = $item['nome'];
        $preco    = floatval($item['preco']);
        $qty      = intval($item['qty']);
        $subtotal = $preco * $qty;

        $stmtItem->bind_param("iisdid", $id_venda, $id_prod, $nome, $preco, $qty, $subtotal);
        if (!$stmtItem->execute()) {
            die("Erro no INSERT do item: " . $stmtItem->error);
        }
    }

    $stmtItem->close();
    unset($_SESSION['carrinho']); // limpa carrinho após salvar

} else {
    die("Funcionário não logado ou dados incompletos na sessão.");
}

$conn->close();
?>
<?php include("vendafinalizada.html"); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Venda Finalizada</title>
</head>
<body>

<h2>Resumo do Pedido</h2>

<div class="resumo">
  <?php if (!empty($carrinho)): ?>
    <ul>
      <?php foreach ($carrinho as $id => $item): ?>
        <?php if (!is_array($item)) continue; ?>
        <?php
          $nome     = htmlspecialchars($item['nome']);
          $qtd      = intval($item['qty']);
          $preco    = floatval($item['preco']);
          $subtotal = $preco * $qtd;
        ?>
        <li>
          <strong><?= $nome ?></strong> x<?= $qtd ?> – R$ <?= number_format($subtotal, 2, ',', '.') ?>
          <?php if ($id == 2 && isset($item['personalizacao'])): ?>
            <ul>
              <?php foreach ($item['personalizacao'] as $ing => $qtdIng): ?>
                <?php if ($qtdIng > 0): ?>
                  <li><?= htmlspecialchars($ing) ?> x<?= $qtdIng ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>
  <?php else: ?>
    <p>Seu carrinho está vazio.</p>
  <?php endif; ?>
</div>

</body>
</html>
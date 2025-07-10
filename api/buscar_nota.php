<?php
require 'connection.php';

$id_venda = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_venda <= 0) {
    echo "<p>Venda inválida.</p>";
    exit;
}

// Busca dados da venda
$stmtVenda = $conn->prepare("SELECT id, nome_funcionario, data, valor FROM histórico_vendas WHERE id = ?");
$stmtVenda->bind_param("i", $id_venda);
$stmtVenda->execute();
$resultVenda = $stmtVenda->get_result();

if ($resultVenda->num_rows === 0) {
    echo "<p>Venda não encontrada.</p>";
    exit;
}

$venda = $resultVenda->fetch_assoc();
$stmtVenda->close();

// Busca itens da venda
$stmtItens = $conn->prepare("SELECT nome, preco, quantidade, subtotal FROM itens_venda WHERE id_venda = ?");
$stmtItens->bind_param("i", $id_venda);
$stmtItens->execute();
$resultItens = $stmtItens->get_result();

echo "<h2>Nota Fiscal - Venda #{$venda['id']}</h2>";
echo "<p><strong>Data:</strong> " . date('d/m/Y H:i', strtotime($venda['data'])) . "</p>";
echo "<p><strong>Funcionário:</strong> " . htmlspecialchars($venda['nome_funcionario']) . "</p>";

echo "<table border='1' cellpadding='5' cellspacing='0' style='width:100%; border-collapse:collapse;'>";
echo "<thead><tr>
        <th>Produto</th>
        <th>Qtd</th>
        <th>Preço</th>
        <th>Subtotal</th>
      </tr></thead><tbody>";

while ($item = $resultItens->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($item['nome']) . "</td>";
    echo "<td style='text-align:center;'>" . intval($item['quantidade']) . "</td>";
    echo "<td style='text-align:right;'>R$ " . number_format($item['preco'], 2, ',', '.') . "</td>";
    echo "<td style='text-align:right;'>R$ " . number_format($item['subtotal'], 2, ',', '.') . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";

echo "<p style='text-align:right; font-weight:bold; font-size:1.2em;'>Total: R$ " . number_format($venda['valor'], 2, ',', '.') . "</p>";

$stmtItens->close();
$conn->close();
?>
<?php
$conn = new mysqli("mysql-sistemacafe.alwaysdata.net", "408159", "pdvcafeteria", "sistemacafe_pdv");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'], $_POST['acao'])) {
    $id = (int)$_POST['id_produto'];
    $acao = $_POST['acao'];

    if ($acao === 'add') {
        $updateSql = "UPDATE estoque SET quantidade_em_estoque = quantidade_em_estoque + 1 WHERE ID = ?";
    } elseif ($acao === 'remove') {
        $updateSql = "UPDATE estoque SET quantidade_em_estoque = quantidade_em_estoque - 1 WHERE ID = ? AND quantidade_em_estoque > 0";
    }

    if (!empty($updateSql)) {
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
        $stmt->close();
    }
}

$sql = "SELECT ID, Nome, Unidade, quantidade_em_estoque, proxima_entrada, gasto_semanal FROM estoque WHERE quantidade_em_estoque >= 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Estoque</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #FAEED1;
      margin: 0;
      padding: 0;
    }

    .grid {
      display: grid;
    }

    #nav {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      padding-top: 20px;
      padding-bottom: 20px;
      background-color: #607274;
      align-items: center;
      justify-items: center;
    }

    .titulo {
      grid-column: 2 / 3;
      color: white;
      font-size: 36px;
      margin: 0;
    }

    .header {
      width: 100%;
    }

    .botoes {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      background-color: #607274;
      padding: 10px 0;
    }

    .botao-header {
      background-color: #607274;
      color: #FAEED1;
      font-size: 22px;
      border: none;
      border-radius: 5px;
      padding: 12px 30px;
      text-decoration: none;
      text-align: center;
      font-weight: bold;
    }

    .botao-header:hover {
      background-color:: #FAEED1;
      color:: #FAEED1;
    }

    .botao-header.ativa {
      background-color: #607274;
      color: #FAEED1;
    }

    h2 {
      text-align: center;
      margin-top: 30px;
      color: #607274;
    }

    table {
      margin: 20px auto;
      width: 90%;
      border-collapse: collapse;
      background-color: #fff8e7;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ccc;
      color: #607274;
    }

    th {
      background-color: #DED0B6;
    }

    form {
      display: inline;
    }

    .btn {
      background: #607274;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 6px;
      cursor: pointer;
      margin: 0 2px;
    }

    .remove {
      background: #a83232;
    }
  </style>
</head>
<body>

<div class="grid">
  <header class="header">
    <nav id="nav">
      <img src="" alt="">
      <h1 class="titulo">Meu Perfil</h1>
    </nav>
    <div class="botoes">
      <a href="Pagperfil1.php" class="botao-header">Minhas informações</a>
      <a href="Pagperfil2.php" class="botao-header">Histórico de vendas</a>
      <a href="pagestoque.php" class="botao-header ativa">Gestão de estoque</a>
    </div>
  </header>

  <h2>Gestão de estoque</h2>

  <table>
    <tr>
      <th>ID</th>
      <th>Produto</th>
      <th>Unidade</th>
      <th>Próx. Entrada</th>
      <th>Gasto semanal</th>
      <th>Quantidade</th>
      <th>Ações</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID'] ?></td>
          <td><?= htmlspecialchars($row['Nome']) ?></td>
          <td><?= htmlspecialchars($row['Unidade']) ?></td>
          <td><?= htmlspecialchars($row['proxima_entrada']) ?></td>
          <td><?= htmlspecialchars($row['gasto_semanal']) ?></td>
          <td><?= $row['quantidade_em_estoque'] ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="id_produto" value="<?= $row['ID'] ?>">
              <input type="hidden" name="acao" value="add">
              <button type="submit" class="btn">Adicionar</button>
            </form>
            <form method="post">
              <input type="hidden" name="id_produto" value="<?= $row['ID'] ?>">
              <input type="hidden" name="acao" value="remove">
              <button type="submit" class="btn remove">Remover</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">Nenhum item em estoque.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>
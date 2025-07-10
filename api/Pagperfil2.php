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

$sql = "SELECT id , nome_funcionario, data, valor FROM histórico_vendas ORDER BY data ";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Histórico de vendas</title>
    <link rel="stylesheet" href="pagsperfis.css" />
</head>
<body>
    <div class="grid">
        <header class="header">
            <nav id="nav">
                <img src="" alt="" />
                <h1 class="titulo">Meu Perfil</h1>
            </nav>
            <div class="botoes">
                <a href="Pagperfil1.php" class="botao-header">Minhas informações</a>
                <a href="Pagperfil2.php" class="botao-header">Histórico de vendas</a>
                <a href="pagestoque.php" class="botao-header">Gestão de estoque</a>
            </div>
        </header>
        <table class="tabela-historico">
            <caption>Histórico de vendas</caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Funcionário</th>
                    <th>Data</th>
                    <th>Valor (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick='mostrarNotaFiscal(" . $row['id'] . ")' style='cursor:pointer;'>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nome_funcionario'] . "</td>";
                         echo "<td>" . $row['data'] . "</td>";
                         echo "<td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
                         echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum registro encontrado</td></tr>";
                }
                ?>

                <!-- Modal da Nota Fiscal -->
                <div id="notaFiscalModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); z-index:1000;">
                    <div style="background-color:#a3937a; width:350px; margin:5% auto; padding:20px; border-radius:20px; position:relative;">
                        <button onclick="fecharModal()" style="position:absolute; top:10px; right:10px; font-size:16px;">✖</button>
                    <div id="conteudoNotaFiscal"></div>
                    </div>
                </div>

                <script src="jsnotafiscal.js"></script>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
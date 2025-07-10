<?php

// Define os dados de conexão com o banco de dados
$localhost = "mysql-sistemacafe.alwaysdata.net"; // Endereço do servidor MySQL
$user      = "408159"; // Nome de usuário do banco
$passw     = "pdvcafeteria"; // Senha do banco
$database  = "sistemacafe_pdv"; // Nome do banco de dados

// Cria a conexão com o banco de dados usando mysqli
$concetar = mysqli_connect($localhost, $user, $passw, $database);

// Verifica se a conexão falhou
if ($concetar->connect_error) {
    // Se falhou, encerra o script e mostra o erro
    die ("Erro na conexão: " . $conexao->connect_error);
}
    

session_start(); // Inicia a sessão para acessar $_SESSION

// Cria conexão com o banco de dados MySQL (host, usuário, senha, banco)
// Obtém os itens do carrinho da sessão, ou array vazio se não existir
$itens = $_SESSION['carrinho'] ?? [];

$total = 0;
$dados = [];

foreach ($_SESSION['carrinho'] as $id => $item) {
    if (!is_array($item)) continue;

    $nome  = $item['nome'];
    $preco = $item['preco'];
    $qtd   = $item['qty'];
    $subtotal = $preco * $qtd;

    $dados[] = [
        'id' => $id,
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => $qtd,
        'subtotal' => $subtotal,
        'personalizacao' => $item['personalizacao'] ?? []
    ];

    $total += $subtotal;
}





?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pagamento</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    /* Estilos gerais da página */
    body {
      font-family: 'Inter', sans-serif;
      background: #FAEED1;
      color: #607274;
      padding: 20px;
    }
    .continuar
{
 border: none;
  border-radius: 8px;
  background-color: #FAEED1;
  color: #607274;
  height: 40px;
  font-size: medium;
  width: 60%;
  cursor: pointer;
  position: relative; /* <- importante */
  bottom: 0;
  margin-top: auto;
}

    .resumo {
      background: #DED0B6;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      max-width: 67%;
    }

    .pagamento button {
      background: #607274;
      color: white;
      padding: 10px;
      border: none;
      margin: 5px 0;
      border-radius: 8px;
    }

    /* Barra lateral fixa à direita */
    .sidebar {
      position: fixed;
      top: 0;
      right: 0;
      width: 28%;
      height: 92vh;
      background-color: #607274;
      color: #FFF;
      border-top-left-radius: 100px;
      border-bottom-left-radius: 100px;
      padding: 40px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

 
    
    .linha1 {
  border: none;
  height: 1px;
  background-color: #FAEED1;
  margin: 20px ;
  width: 100% ; 
  top: 7%;
  position: absolute;
}
.linha2 {
  border: none;
  height: 1px;
  background-color: #FAEED1;
  margin: 20px ;
  width: 104.3% ; 
  bottom: -500%;
  left: -8%;
  position: absolute;
}

    /* Lista de produtos na sidebar */
    #listaProdutos {
      margin-top: 10px;
      text-align: center;
      width: 100%;
      position: absolute;
      top: 10%;
      font-size: large;
    }

    /* Preço total exibido na sidebar */
    #precoProduto {
      font-size: large;
      font-weight: bold;
      margin-top: 10px;
      position: absolute;
      bottom: -600%;
      left: 10%;
    }

   

    /* Nome do usuário no top da sidebar */
    #nomeUser {
      position: absolute;
      top: 0%;
    }

    .contpagamento{
      background-color:#DED0B6;
      border-radius:10px;
      display:flex;
      flex-direction:column;
      font-size:20px;
      max-width: 67%;
      padding:15px;
      
    }


    input[type="radio"] {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #ccc;
    border-radius: 50%;
    background-color: #FAEED1;
    position: relative;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
    }

    input[type="radio"]::before {
      content: "";
      position: absolute;
      top: 50%; left: 50%;
      width: 8px; height: 8px;
      background-color:#607274;
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0);
      transition: transform 0.2s ease-in-out;
    }

    input[type="radio"]:checked {
      border-color:#607274;
      background-color: #607274;
    }

    input[type="radio"]:checked::before {
      transform: translate(-50%, -50%) scale(1);
    }
    .scroll-container {
  overflow-y: auto;
  max-height: 60vh; /* ou ajuste conforme layout */
  width: 100%;
  padding-right: 10px;
  margin-bottom: 10px;
}

    
  </style>
</head>
<body>
  <h2>Resumo do Pedido</h2>
 <div class="resumo">
  <?php foreach ($dados as $d): ?>
  <p><?= htmlspecialchars($d['nome']) ?> x<?= $d['quantidade'] ?> – R$ <?= number_format($d['subtotal'], 2, ',', '.') ?></p>

  <?php if ($d['id'] == 2 && !empty($d['personalizacao'])): ?>
    <ul>
      <?php foreach ($d['personalizacao'] as $ing => $qtd): ?>
        <?php if ($qtd > 0): ?>
          <li><?= $ing ?> x<?= $qtd ?></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
<?php endforeach; ?>

</div>



  <h2>Forma de Pagamento</h2>
  <div class="pagamento">
  <form method="post" class="contpagamento" action="finalizar.php">
  <div class="opcao">
    <input type="radio" name="forma" id="cartao" value="cartao">
    <label for="cartao"><b>Pagar com o Cartão</b></label>
  </div>
  <div class="opcao">
    <input type="radio" name="forma" id="pix" value="pix">
    <label for="pix"><b>Pagar com o PIX</b></label>
  </div>
  <div class="opcao">
    <input type="radio" name="forma" id="dinheiro" value="dinheiro">
    <label for="dinheiro"><b>Pagar com dinheiro</b></label>
  </div>
</form>
  </div>

  <!-- Sidebar fixa mostrando usuário, produtos e fim -->
  
   <div class="sidebar">
  <h1 id="nomeUser">
    <?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Visitante'; ?>
  </h1>

  <hr class="linha1" />
  <div class="scroll-container">
 <div id="listaProdutos">
 <?php
   if (!empty($_SESSION['carrinho'])): ?>
            <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>
                <?php if (is_array($item)): ?>
                    <li>
                        <strong><?= htmlspecialchars($item['nome']) ?></strong> (x<?= $item['qty'] ?>)<br>
                        <?php if ($id == 2 && isset($item['personalizacao'])): ?>
                            <ul>
                                <?php foreach ($item['personalizacao'] as $ing => $qtdIng): ?>
                                    <?php if ($qtdIng > 0): ?>
                                        <li><?= $ing ?> x<?= $qtdIng ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <h3 id="precoProduto">Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            Seu carrinho está vazio
        <?php endif; ?>
        <form id="formFinalizar" action="finalizar.php" method="POST">
      <input type="hidden" name="nome" id="inputProdutos">
      <input type="hidden" name="total" id="inputTotal">
      <hr class="linha2" />
      <h3 id="precoProduto">Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

      <button type="submit" class="continuar">FINALIZAR</button>
    </form>
 </div>
</div>
 
</body>
</html>



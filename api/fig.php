<?php
session_start();

// Ingredientes do Mocha com preço
$ingredientes = [
    'Espresso' => 2.00,
    'Leite vaporizado' => 1.50,
    'Chantilly' => 1.00,
    'Ganache de chocolate' => 3.00
];

// Preço base do Mocha
$precoMocha = 18.00;
$idMocha = 2;
$nomeMocha = "Mocha";

// Inicializa carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Inicializa ingredientes do Mocha na sessão se não existir
if (!isset($_SESSION['mocha_ingredientes'])) {
    $_SESSION['mocha_ingredientes'] = [];
    foreach ($ingredientes as $nome => $valor) {
        $_SESSION['mocha_ingredientes'][$nome] = 0;
    }
}

// Se o formulário foi enviado
// Sempre processa adição/remoção de ingredientes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_ing'])) {
        $nome = $_POST['add_ing'];
        if (isset($_SESSION['mocha_ingredientes'][$nome])) {
            $_SESSION['mocha_ingredientes'][$nome]++;
        }
    }

    if (isset($_POST['sub_ing'])) {
        $nome = $_POST['sub_ing'];
        if (isset($_SESSION['mocha_ingredientes'][$nome])) {
            $_SESSION['mocha_ingredientes'][$nome] = max(0, $_SESSION['mocha_ingredientes'][$nome] - 1);
        }
    }

    // Só atualiza carrinho se clicou no botão "ADICIONAR"
    if (isset($_POST['add_mocha'])) {
        $qtd = intval($_POST['quantidade']);
        if ($qtd > 0) {
            $adicional = 0;
            foreach ($_SESSION['mocha_ingredientes'] as $nome => $qtdIng) {
                $adicional += $qtdIng * $ingredientes[$nome];
            }

            $precoFinal = $precoMocha + $adicional;

            $_SESSION['carrinho'][$idMocha] = [
                'nome' => $nomeMocha,
                'preco' => $precoFinal,
                'qty' => $qtd,
                'personalizacao' => $_SESSION['mocha_ingredientes']
            ];
        } else {
            unset($_SESSION['carrinho'][$idMocha]);
        }

        header('Location: cafeteria.html');
        exit;
    }
}

$total = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        if (is_array($item) && isset($item['preco'], $item['qty'])) {
            $total += $item['preco'] * $item['qty'];
        }
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mocha</title>
    <style>
     body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            flex-direction: row;
            height: 100vh;
            overflow: hidden;
            background-color: #faeed1;
        }

        .left-panel {
            width: 65%;
            background-color:  #faeed1;;
            padding: 30px;
            box-sizing: border-box;
            color:#607274;
        }

        .right-panel {

            color: white;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            position: fixed;
            top: 0;
            right: 0;
            width: 26%;
            height: 100%;
            background-color: #607274;
            border-top-left-radius: 100px;
            border-bottom-left-radius: 100px;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
         
        }

        h2 {
            margin-top: 0;
            font-size: 28px;
        }

        img {
            width: 150px;
            border-radius: 8px;
        }

        .ingredientes {
            margin-top: 20px;
            color:  #607274;
            font-size:25px;
        }

        .ingrediente-linha {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #ccc;
        }

        .controle-qtd {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .controle-qtd button {
            background-color: #607274;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 16px;
            color: white;
            cursor: pointer;
        }

        .controle-qtd input {
            width: 30px;
            text-align: center;
            border: none;
            background: none;
            font-size: 16px;
        }

        input[type="number"] {
            width: 50px;
            padding: 5px;
        }

        button[type="submit"] {
            background-color: #607274;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color:#b2a59b;
        }

        .carrinho-itens {
        font-size: 18px; /* aumente de 10px para 18px */
        padding-left: 0;
        margin: 0;
        }

        .carrinho-itens li {
            font-size: 18px;
            margin-bottom: 10px;
            padding: 10px;
            
        }

        .total {
            font-size: 35px;
            font-weight: bold;
            margin-top: 20px;
        }

        .continuar-btn {
            align-self: center;
        }
        ul{font-size:25px;}

        #botaodentro{background-color:#faeed1;
        color: #607274;        }

        .linha1 {
  border: none;
  height: 1px;
  background-color: #FAEED1;
  width: 100% ; 
  top: 7%;
  position: absolute;
  left:1%;
}
.linha2 {
  border: none;
  height: 1px;
  background-color: #FAEED1;
  margin: 20px ;
  width: 104.3% ; 
  top: 70%;
  left: -8%;
  position: absolute;
}
h3{font-size:40px;
position: absolute;
top:-3%;
right:30%;}

.imagemmocha{
border-radius:10px;
height:25%;
width: 25%;

}

.descmocha{
font-size:27px;
display:flex;
flex-direction:column;
position:absolute;
top:10%;
left:18%;

}
#nomeUser {
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 20px;
    width: 100%;
    text-align: center;
    margin-left: 45px; 
}

.quantidade-e-botao {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    color: #607274;
    font-size: 24px;
    line-height: 1; 
}
.quantidade-e-botao input[type="number"] {
    width: 60px;
    padding: 5px;
    font-size: 18px;
}

.quantidade-e-botao button {
    background-color: #607274;
    color: white;
    border: none;
    padding: 10px 16px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 2px; 
    line-height: 1.2;  
}

.quantidade-e-botao button:hover {
    background-color: #b2a59b;
}
    </style>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <!-- Seu conteúdo original sem alterações -->
        <h2>Mocha</h2>
        <img class="imagemmocha" src="https://perfectdailygrind.com/pt/wp-content/uploads/sites/5/2022/01/pexels-%D0%BA%D1%81%D0%B5%D0%BD%D0%B8%D1%8F-%D0%BC%D0%B0%D1%80%D0%BA%D0%BE%D0%B2%D0%B0-9823577-edited.jpg" alt="Mocha">
        <div class="descmocha"><p>Espresso, leite vaporizado ou chantilly e ganache de chocolate. <br> 380ml</p></div>
        <p style="font-size:20px"><strong>R$ <?= number_format($precoMocha, 2, ',', '.') ?></strong></p>

        <form method="post">
            <div class="ingredientes">
                <?php foreach ($ingredientes as $nome => $preco): ?>
                    <div class="ingrediente-linha">
                        <span><?= $nome ?> (R$ <?= number_format($preco, 2, ',', '.') ?>)</span>
                        <div class="controle-qtd">
                           <button type="submit" name="sub_ing" value="<?= $nome ?>">-</button>
                            <input type="text" name="qtd_ing[<?= $nome ?>]" value="<?= $_SESSION['mocha_ingredientes'][$nome] ?>" readonly>
                            <button type="submit" name="add_ing" value="<?= $nome ?>">+</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="quantidade-e-botao">
                <label>Quantidade: </label>
                    <input type="number" name="quantidade" value="<?= $_SESSION['carrinho'][2]['qty'] ?? 1 ?>" min="1">
                    <button type="submit" name="add_mocha"><b>ADICIONAR</b></button>
        </div>
<?php
$total = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        if (is_array($item) && isset($item['preco'], $item['qty'])) {
            $total += $item['preco'] * $item['qty'];
        }
    }
}
?>
    <div class="right-panel">
        <div>
           <h3 id="nomeUser"><?= htmlspecialchars($_SESSION['nome'] ?? 'Visitante') ?></h3>
        </div>
        <div>
            <hr class="linha1" />
<div id="listaProdutos">
    <ul class="carrinho-itens">
        <?php if (!empty($_SESSION['carrinho'])): ?>
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
                        <p>Subtotal: R$ <?= number_format($item['preco'] * $item['qty'], 2, ',', '.') ?></p>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            Seu carrinho está vazio
        <?php endif; ?>
    </ul>
</div>



            <hr class="linha2" />
        </div>
       <form action="fig11.php" method="post" class="continuar-btn">
    <p class="total">Total: R$ <?= number_format($total, 2, ',', '.') ?></p>
    <button id="botaodentro" type="submit"><b>CONTINUAR</b></button>
</form>
    </div>
</div>
</body>
</html>
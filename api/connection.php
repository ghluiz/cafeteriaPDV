<?php 

$localhost = "mysql-sistemacafe.alwaysdata.net"; //define onde ta instalado o banco de dados, no caso na própria máquina
$user = "408159";//define o usuario q conecta no banco de dado
$passw = "pdvcafeteria";//zerado pq nao tem senha
$database = "sistemacafe_pdv";//nome do banco

$conn= mysqli_connect($localhost, $user, $passw, $database); //recebendo uma estruturav mysql com os parametro


if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}//verificando a conexão




?>
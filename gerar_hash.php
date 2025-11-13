<?php
// Coloque a senha em $senha e rode. Apague o arquivo após usar.

$senha = '123456'; // <- substitua aqui pela senha desejada

//Gerar o hash seguro (bcrypt via password_hash)
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "Hash gerado: <br><b>";
echo $hash;

?>
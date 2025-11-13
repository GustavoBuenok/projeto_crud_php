<?php

session_start(); // inicia ou retoma a sessão para poder ler $_SESSION

// se NÃO existir usuário logado na sessão...

if(!isset($_SESSION['usuario'])) {
    // manda o navegador pra tela de login com um aviso na URL
    header('Location: login.php?msg=login');
    exit; // para o script aqui (impede que a pagina continue carregando)

}

?>

<?php  

// Mostra o nome do usuario logado e o link de sair (se houver sessão ativa)

$u = $_SESSION['usuario'] ?? null;
if ($u): ?>
    <p>Logado como: <b><?= htmlspecialchars($u['nome']) ?></b> | <a href="logout.php">Sair</a></p>
<?php endif; ?>

<!-- <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bem vindo, voce esta logado!</h1>
</body>
</html> -->
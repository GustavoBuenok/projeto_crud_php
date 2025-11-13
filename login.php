<?php
include __DIR__ . '/includes/header.php';
?>

<?php

// Login simples (verifica email e senha)

require __DIR__ . '/includes/db.php';

//inicia a sessão para armazenar dados do usuário logado
// session_start();

// variavel que guardara mensagens de erro (se houver)
$erro = '';

// processamento do formulario de login

// só executa o codigo se o metodo da requisição for POST
// (ou seja, se o formulario foi enviado)

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CAPTURA E LIMPA OS VALORES DIGITADOS

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Verifica se ambos os campos foram preenchidos 
    if ($email === '' || $senha === ''){
        $erro = 'Preencha e-mail e senha';
    }
    else {
        // busca o usuario no banco pelo email digitado

        $sql = 'SELECT id, nome, senha_hash FROM usuarios WHERE email = :email'; //Consulta SQL com placeholder
        $st = db()->prepare($sql); // prepara a consulta
        $st->execute([':email' => $email]); // substitui :email pelo valor digitado 
        $u = $st->fetch(PDO::FETCH_ASSOC);  // busca o resultado (array associativo)

        // verifica se encontrou o usuario e se a senha esta correta

        //se encontrou um usuario e a senha digitada bate com o hash
        if($u && password_verify($senha, $u['senha_hash'])) {

        // guarda os dados básicos do usuário na sessão    
            $_SESSION['usuario'] = [
                'id' => (int)$u['id'],
                'nome' => $u['nome']
            ];

            //redireciona para a pagina principal (pagina_inicial.php)
            header('Location: index.php?msg=logado');
            exit; // finaliza o script para evitar que o restante rode
        }

        else {
            //caso o email não exista ou a senha esteja errada
            $erro = 'E-mail ou senha incorretos';
        }
    }
}
?>

<!-- HTML - Formulario de login -->



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <!-- Se houver erro, exibe mensagem em vermelho -->

<?php if ($erro): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>


 <div class="d-flex justify-content-center align-items-center mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <form method="post" class="p-4 border rounded shadow">
                        <h1>Login</h1>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">E-mail:</label>
                            <input type="email" class="form-control w-100" id="exampleFormControlInput1" placeholder="nome@email.com" name="email" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Senha:</label>
                            <input type="password" class="form-control w-100" id="exampleFormControlInput1" name="senha" autocomplete="new-password" placeholder="*********" required>
                        </div>

                        <button class="btn btn-primary" type="submit">Entrar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    
<?php
include __DIR__ . '/includes/footer.php';
?>

</body>
</html>
<!-- Chamando o arquivo de cabeçalho -->
<?php
include __DIR__ . '/includes/header.php';
?>
<?php

// se NÃO existir usuário logado na sessão...

if (!isset($_SESSION['usuario'])) {
    // manda o navegador pra tela de login com um aviso na URL
    header('Location: login.php?msg=login');
    exit; // para o script aqui (impede que a pagina continue carregando)

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <form action="salvar.php" method="post" enctype="multipart/form-data" class="p-4 border rounded shadow">
                        <h1>Cadastro</h1>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nome:</label>
                            <input type="text" class="form-control w-100" id="exampleFormControlInput1" placeholder="Seu nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">E-mail:</label>
                            <input type="email" class="form-control w-100" id="exampleFormControlInput1" placeholder="nome@email.com" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Telefone:</label>
                            <input type="text" class="form-control w-100" id="exampleFormControlInput1" required placeholder="(11) 91234-5678" name="telefone">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Foto:</label>
                            <input type="file" class="form-control w-100" id="exampleFormControlInput1" name="foto">
                        </div>

                        <button class="btn btn-primary" type="submit">Enviar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Cadastro realizado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Usuário cadastrado com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>





</body>

</html>

<?php
include __DIR__ . '/includes/footer.php';
?>
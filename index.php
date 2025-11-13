<?php

include __DIR__ . '/includes/header.php';

require __DIR__ . '/includes/db.php';


// se NÃO existir usuário logado na sessão...

if (!isset($_SESSION['usuario'])) {
    // manda o navegador pra tela de login com um aviso na URL
    header('Location: login.php?msg=login');
    exit; // para o script aqui (impede que a pagina continue carregando)

}

?>

<?php
// Inicio - logica de busca

// $_GET pega o valor digitando no campo de busca (se existir)
// trim() remove espaços antes e depois do texto;

$busca = trim($_GET['busca'] ?? '');

// get é usado aqui para consultar dados , nao esta salvando nada.

// verifica se o usuario digitou algo 

if ($busca !== '') {
    // se tiver texto na busca , o sql filtra pelo nome ou email
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
            FROM cadastros
            WHERE nome LIKE :busca OR email LIKE :busca
            ORDER BY id DESC'; // ORDENA PELOS IDS DO MAIOR PRO MENOR (CADASTROS MAIS NOVOS PRIMEIRO)
    //prepara o comando SQL 

    $stmt = db()->prepare($sql);

    // executa substituindo o placeholder :busca
    // o % antes e depois permite buscar qualquer parte do nome/email
    $stmt->execute([':busca' => "%$busca%"]);
} else {
    // se o campo de busca estiver vazio, lista tudo
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
    FROM cadastros
    ORDER BY id DESC';

    $stmt = db()->prepare($sql);
    $stmt->execute();
}

// fetchAll() busca todos os resultados e retorna como array associativo
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fim logica de busca

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de cadastros</title>
</head>

<body>
    <div class="container">

        <form method="get" class="p-4">
            <div class="col-md-6 mx-auto">
                <h1 class="text-center mb-3">Lista de cadastros</h1>

                <div class="d-flex align-items-center mb-3">
                    <input type="text"
                        class="form-control me-2"
                        placeholder="Pesquisar..."
                        name="busca"
                        value="<?= htmlspecialchars($busca) ?>">

                    <button class="btn btn-primary me-2" type="submit">Enviar</button>

                    <a href="index.php" class="btn text-primary border  " style="background-color: transparent; ">Limpar</a>
                </div>

            </div>
        </form>


        </form>

        <?php if (!$registros): ?>
            <!-- Se não houver resultados -->
            <p>Nenhum cadastro encontrado.</p>

        <?php else: ?>



            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="formulario.php" class="btn btn-success">+ Novo cadastro</a>
                </div>
                <div>
                    <a class="btn btn-secondary" href="exportar_csv.php" target="_blank">Exportar CSV</a>
                    <a class="btn btn-secondary ms-2" href="exportar_xls.php" target="_blank">Exportar XLS</a>
                </div>
            </div>
            <table id="minhaTabela" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Data de cadastro</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // foreach -> estrutura que percorre todos os registros do banco
                    // $registros -> lista com todos os cadastros vindos do banco
                    // $r -> representa UM registro por vez dentro do loop

                    foreach ($registros as $r):
                    ?>

                        <tr>
                            <td scope="row"><?= (int)$r['id'] ?></td>
                            <td scope="row"><?= htmlspecialchars($r['nome']) ?></td>
                            <td scope="row"><?= htmlspecialchars($r['email']) ?></td>
                            <td scope="row"><?= htmlspecialchars($r['telefone']) ?></td>

                            <td scope="row">
                                <?php if (!empty($r['foto'])): ?>
                                    <img class="img-thumbnail rounded" src="<?= htmlspecialchars($r['foto']) ?>" alt="Foto" style="max-width:80px; max-height:80px;">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td scope="row">
                                <!-- Exibe data, se existir -->
                                <?= htmlspecialchars($r['data_cadastro'] ?? '') ?>
                            </td>
                            <!-- Links pra editar ou excluir -->
                            <td scope="row">
                                <a class="text-white btn btn-primary link-primary link-underline-opacity-0" href="editar.php?id=<?= (int)$r['id'] ?>">Editar</a> |
                                <a class="btn btn-danger" class="link-primary link-underline-opacity-0" href="deletar.php?id=<?= (int)$r['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este registro?');">
                                    Excluir</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>


</body>

</html>

<?php
            include __DIR__ . '/includes/footer.php';
?>

<?php endif; ?>
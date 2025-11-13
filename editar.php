<?php
// Inicio - conexão e captura do ID

require __DIR__ . '/includes/db.php';

//Pega o ID que veio pela URL (ex: editar.php?=3)
// se não existir ou for invalido (0,texto, etc), volta pra pagina de listagem

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fim - conexão e captura do ID

// Inicio - busca do registro (para preencher o formulario)


$sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
        FROM cadastros
        WHERE id = :id';

$stmt = db()->prepare($sql);
$stmt->execute([':id' => $id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

//Se não encontrou o registro, volta para a lista

if (!$registro) {
    header('Location: index.php');
    exit;
}

// guarda a foto atual do registro (vinda do banco)
// senão enviar uma nova foto do formulario 
// esse aqui continua sendo usada (pra não apagar a existente)
$fotoAtual = $registro['foto'] ?? null;

// fim - busca do registro

// Inicio - processamento do POST (quando clicar em salvar)

$erro = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //CAPTURA DOS DADOS 

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $fotoAtual = $_POST['foto_atual'] ?? null;





    // validações básicas (iguais às usadas no salvar.php)

    if ($nome === '' || mb_strlen($nome) < 3) {
        $erro = 'Nome é obrigatorio (min. 3 caracteres).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) < 8) {
        $erro = 'Telefone invalido.';
    }

    // upload da nova foto (se enviada)

    $novaFoto = null; //se nao enviar, vamos manter a foto atual
    if ($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Erro ao enviar a imagem.';
        } else {
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $erro = 'Imagem muito grande (máx. 2MB)';
            }

            // valida tipo real (MIME)
            if ($erro === '') {
                $finfo = new finfo(FILEINFO_MIME_TYPE); // classe nativa pra detectar o MIME
                $mime = $finfo->file($_FILES['foto']['tmp_name']); // tipo real do arquivo 
                $permitidos = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif'
                ];
                if (!isset($permitidos[$mime])) {
                    $erro = 'Formato de imagem invalido. Use JPG, PNG, ou GIF';
                }
            }

            // garante a existencia da pasta e move o arquivo

            if ($erro === '') {
                $dirUpload = __DIR__ . '/uploads';
                if (!is_dir($dirUpload)) {
                    mkdir($dirUpload, 0755, true);
                }
                $novoNome = uniqid('img_', true) . '.' . $permitidos[$mime]; // nome único
                $destino = $dirUpload . '/' . $novoNome;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    $novaFoto = 'uploads/' . $novoNome; // salva caminho relativo
                } else {
                    $erro = 'Falha ao salvar a imagem no servidor.';
                }
            }
        }
    }

    // se tudo estiver ok, faz o UPDATE

    if ($erro === '') {
        try {
            $fotoParaSalvar = $novaFoto !== null ? $novaFoto : $fotoAtual;

            $sql = 'UPDATE cadastros
                SET nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    foto = :foto
                WHERE id = :id';

            $stmt = db()->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':telefone' => $telefone,
                ':foto' => $fotoParaSalvar,
                ':id' => $id,
            ]);
            // se trocou a foto, apaga a antiga do disco (se existir)
            if ($novaFoto !== null && !empty($fotoAtual) && file_exists(__DIR__ . '/' . $fotoAtual)) {
                unlink(__DIR__ . '/' . $fotoAtual);
            }
            $ok = true;

            // redireciona para a lista após atualizar (fluxo que voce quer)
            header('Location: index.php?msg=atualizado');
            exit;
        } //chave do try
        catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erro = 'Este e-mail já esta cadastrado.';
            } else {
                $erro = 'Erro ao atualizar:' . $e->getMessage();
            }
        }
    } // if antes do try 

}

?>

<?php
include __DIR__ . '/includes/header.php';
?>


<!doctype html>
<meta charset="utf-8">
<title>Editar Cadastro</title>


<?php if ($erro): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<!-- Inicio - formulario de edição (pré preenchido) -->


<div class="d-flex justify-content-center align-items-center mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <form method="post" enctype="multipart/form-data" class="p-4 border rounded shadow">
                    <h1>Editar Cadastro</h1>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Nome:</label>
                        <input type="text" class="form-control w-100" id="exampleFormControlInput1" placeholder="Seu nome" name="nome" required minlength="3" value="<?= htmlspecialchars($registro['nome'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">E-mail:</label>
                        <input type="email" class="form-control w-100" id="exampleFormControlInput1" placeholder="nome@email.com" name="email" required value="<?= htmlspecialchars($registro['email'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Telefone:</label>
                        <input type="text" class="form-control w-100" id="exampleFormControlInput1" required placeholder="(11) 91234-5678" name="telefone" value="<?= htmlspecialchars($registro['telefone'] ?? '') ?> ">
                    </div>

                    Foto atual:
                    <?php if (!empty($fotoAtual)): ?>
                        <br>
                        <img class="rounded mt-3" src="<?= htmlspecialchars($fotoAtual) ?> " alt="Foto atual" style="max-width:120px;">
                    <?php else: ?>
                        (sem foto)
                    <?php endif; ?>
                    </p>
                    <p>

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Trocar foto (opcional):</label>
                        <input type="file" class="form-control w-100" id="exampleFormControlInput1" name="foto">
                    </div>
                    <div>
                        <input type="hidden" class="form-control w-100" id="exampleFormControlInput1" name="foto_atual" value="<?= htmlspecialchars($fotoAtual ?? '') ?>">
                    </div>

                    <button class="btn btn-primary" type="submit">Salvar alterações</button>
                    <a class="btn btn-danger ms-5" href="index.php">Cancelar</a>
                </form>
            </div>
        </div>

    </div>
</div>

<?php
include __DIR__ . '/includes/footer.php';
?>
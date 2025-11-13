<?php
// CARREGA A FUNÇÃO db() para conectar ao MySQL
require __DIR__ . '/includes/db.php';

// GUARDARA MENSAGENS DE ERRO (SE HOUVER)
$erro = '';

//INDICA SE SALVOU COM SUCESSO
$ok = false;

//Só processa se o metodo da requisição for POST (veio do formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    //validações simples (evita dados incorretos antes de gravar no banco)
    // verifica se o nome foi preenchido e tem pelo menos 3 caracteres
    // mb_strlen() função nativa do PHP que conta caracteres corretamente, incluindo acentos (ex: "Jose" = 4)

    if ($nome === '' || mb_strlen($nome) < 3) {
        $erro = 'Nome é obrigatorio (min. 3 caracteres).';
    }
    // verifica se o email esta em formato valido (ex: nome@dominio.com)
    // filter_var() -> função nativa do PHP usada para validar ou filtrar valores
    // FILTER_VALIDATE_EMAIL -> contante nativa do PHP que valida o formato de email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail invalido.';
    }
    // verifica se o telefone foi preenchido e tem pelo menos 8 digitos
    // preg_replace() -> função nativa do PHP que substitui partes de texto usando expressoes regulares
    // aqui ela remove tudo que nao for numero (/D+ significa qualquer caractere nao numerico)
    // depois usamos mb_strlen() pra contar quantos digitos sobraram
    elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) < 8) {
        $erro = 'Telefone inválido.';
    }
    //Upload da foto - só executa se não houve erro anterior
    // unicio do bloco (foto)
    $foto = null; //valor padrão: sem foto

    // se não teve erro de validação e o campo "foto" veio no POST
    if ($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Erro ao enviar a imagem.';
        } else {
            //opcional - limite de tamanho: até 2mb
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $erro = 'Imagem muito grande (máx. 2MB).';
            }
            // valida o tipo real do arquivo (pra garantir que é uma imagem de verdade)
            if ($erro === '') {
                // finfo -> classe nativa do PHP usada pra descobrir o tipo real do arquivo
                // (MIME = tipo do arquivo, ex: image/jpeg, image/png, application/pdf, etc.)
                $finfo = new finfo(FILEINFO_MIME_TYPE);

                //$_FILES['foto']['tmp_name'] -> caminho temporario 
                $mime = $finfo->file($_FILES['foto']['tmp_name']);

                $permitidos = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif'
                ];

                if (!isset($permitidos[$mime])) {
                    $erro = 'Formato de imagem invalido. Use JPG, PNG ou GIF';
                }
            }

            // cria a pasta "uploads" se ainda não existir 
            if ($erro === '') {
                $dirUpload = __DIR__ . '/uploads'; //__DIR__ mostra a pasta atual do arquivo

                if (!is_dir($dirUpload)) {
                    // is_dir() verifica se a pasta existe
                    // mkdir() cria pastas
                    // 0755 = permissão padrão (dono pode tudo)
                    //true = criar subpastas se for preciso   

                    mkdir($dirUpload, 0755, true);
                }
            }

            $novoNome = uniqid('img_', true) . '.' . $permitidos[$mime];

            $destino = $dirUpload . '/' . $novoNome;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $foto = 'uploads/' . $novoNome;
            } else {
                $erro = 'Falha ao salvar a imagem no servidor.';
            }
        }
    }
}

if ($erro === '') {
    try {
        // SQL com placeholders nomeados (evita SQL Injection)
        // os dois pontos (:) indicam variaveis que serão substituidas depois
        $sql = 'INSERT INTO cadastros (nome, email, telefone, foto)
                VALUES (:nome, :email, :telefone, :foto)';

        //db() -> funçao personalizada que retorna a conexão PDO com o banco 
        // prepare() -> meotodo nativo do PDO que pre compila o SQL no servidor
        // isso aumenta a segurança e o desempenho, pois separa o comando SQL dos dados    


        $ok = true; //marca que o cadastro foi salvo com sucesso                

        $query = db()->prepare($sql);

        $query->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':foto' => $foto,
        ]);
    } catch (PDOException $e) {

        if ($e->getCode() === '23000') {
            //mensagem amigavel pro usuario
            $erro = 'Este email já esta cadastrado.';
            // Qualquer outro erro mostra a mensagem tecnica (util para depuração ou aula)
        } else {
            $erro = 'erro ao salvar:' . $e->getMessage();
            //getMessage() -> metodo nativo da classe exception que devolve o texto do erro
        }
    }
}

?>
<?php
include __DIR__ . '/includes/header.php';
?>

<!doctype html>
<meta charset="utf-8">
<title>Salvar</title>

<!-- Se deu tudo certo no cadastro, mostra mensagem de sucesso -->
<?php if ($ok): ?>
    <!-- <p>Dados salvos com sucesso!</p>
    <p><a href="/formulario.php">Voltar</a></p> -->

    <div class="d-flex justify-content-center align-items-center ">
        <div class="mt-3 col-md-4 p-4 border rounded shadow">
            <h4 class="text-center">Dados salvos com sucesso!</h4>
            <div class="text-end">
                <a class="btn btn-secondary" href="formulario.php">Voltar</a>
            </div>
        </div>
    </div>


    <!-- Se não deu certo, entra aqui -->
<?php else: ?>

    <!-- Se existe mensagem de erro, exibe em vermelho -->
    <?php if ($erro): ?>
        <!-- htmlspecialchars() → função nativa do PHP que converte caracteres especiais em HTML seguro -->
        <!-- Evita que alguém insira tags HTML ou scripts maliciosos dentro da mensagem -->
        <!-- <p style="color:red;"><?= htmlspecialchars($erro) ?></p> -->

        <div class="d-flex justify-content-center align-items-center ">
        <div class="mt-3 col-md-4 p-4 border rounded shadow">
            <h4 class="text-center">Nada enviado.</h4>
            <div class="text-end">
                <a class="btn btn-secondary" href="formulario.php">Voltar</a>
            </div>
        </div>
        </div>

        <!-- Se chegou aqui sem erro e sem POST, o usuário acessou a página diretamente -->
    <!-- <?php else: ?>
        <p>Nada enviado.</p>
    <?php endif; ?> -->

    <!-- Link pra voltar pro formulário -->
    <p><a href="/formulario.php">Voltar</a></p>

<?php endif; ?>

<?php
include __DIR__ . '/includes/footer.php';
?>


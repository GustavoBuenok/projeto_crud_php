<?php

// Inicio - conexão com o banco e verificação do ID

require __DIR__ . '/includes/db.php';

//verifica se veio o ID pela URL (GET)
$id = (int)($_GET['id'] ?? '');

if ($id <= 0) {
    header('Location: listar.php');
    exit;
}

// Fim - conexão com o banco e verificação do ID

// BUSCA DO REGISTRO PARA EXCLUIR 

$sql = 'SELECT * FROM cadastros WHERE id = :id';
$stmt = db()->prepare($sql);
$stmt->execute([':id' => $id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

// SE NAO ENCONTRAR NADA, VOLTA PARA LISTA

if (!$registro){
    header('Location: index.php');
    exit;

}
// ==================================================================
// Fim - Busca do registro

// Exclusão do registro

try {
    if(!empty($registro['foto']) && file_exists(__DIR__ . '/' . $registro['foto'])) {
        unlink(__DIR__ . '/' . $registro['foto']);
    }

    // if (!empty($registro['foto'])
    // verifica se o campo foto no banco não esta vazio 

    // file_exists(__DIR__ . '/' . $registro['foto'])
    // confirma se o arquivo realmente existe naa pasta do servidor antes de tentar apagar

// Comando SQL pra excluir o registro 
$sql = 'DELETE FROM cadastros WHERE id = :id';
$stmt = db()->prepare($sql);
$stmt->execute([':id' => $id]);

// redireciona de volta pra lista apos excluir
header('Location: index.php?msg=excluido');
exit;    
} 

catch (PDOException $e){
    //Se der erro no banco, mostra mensagem
    echo '<p style="color:red;">Erro ao excluir: ' . htmlspecialchars($e->getMessage()) . '</php>';
}









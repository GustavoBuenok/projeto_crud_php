<?php

require __DIR__ . '/includes/db.php';

// Consulta dos dados do banco

$sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
        FROM cadastros
        ORDER BY id ASC'; // ASC ordem crescente

// prepara a consulta no banco 
$st = db()->prepare($sql);

// executa a consulta no banco 
$st->execute();

//fetchAll() -> busca todos os registros e retorna como um array associativo
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

// Cabeçalhos http para download

//aqui definimos o nome do arquivo que sera baixado 

$arquivo = 'cadastros.csv';

// informa ao navegador que o conteudo é CSV e esta em UTF-8
header('Content-Type: text/csv; charset-UTF-8');

//força o dowload do arquivo, com o nome definido acima
header('Content-Disposition: attachment; filename="' . $arquivo . '"');

//ajuda o excel e outros programas reconhecer o arquivo como UTF-8
//sem isso, acentos podem aparecer errados (ex: "João" vira "JoÃEo")
echo "\xEF\xBB\xBF";

//Geração do arquivo CSV

// fopen('php://output', 'w') abre a saída do PHP pra enviar o conteudo direto pro navegador
// ou seja, o arquivo é gerado "ao vivo" sem salvar nada no servidor 
$out = fopen('php://output', 'w');

//cabecalho do CSV (nomes das colunas)
fputcsv($out, ['ID', 'Nome', 'E-mail', 'Telefone', 'Cidade', 'Data de cadastro']);

//loop para escrever cada linha da tabela
foreach ($rows as $r){
    //fputcsv() converte o array em uma linha CSV (separada por virgulas)
    fputcsv($out, [
        $r['id'],
        $r['nome'],
        $r['email'],
        $r['telefone'],
        $r['foto'],
        $r['data_cadastro'],
    ]);
}

// finalização do arquivo

//fecha a saida de escrita
fclose($out);

//encerra o script imediatamente
exit;

?>



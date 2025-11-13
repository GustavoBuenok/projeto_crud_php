<?php
 
require __DIR__ . '/includes/db.php';
 
// 1) Busca
$sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
        FROM cadastros ORDER BY id ASC';
$st = db()->prepare($sql);
$st->execute();
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
 
 
// 2) Cabeçalhos pra forçar download como .xls
$arquivo = 'contatos_demo.xls';
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$arquivo.'"');
 
echo "\xEF\xBB\xBF";
 
// 3) Imprime uma tabela HTML — o Excel abre como planilha
echo "<table border='1'>";
echo "<tr>
        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th>Foto</th>
        <th>Data de Cadastro</th>
      </tr>";
 
foreach ($rows as $r) {
  echo "<tr>";
  echo "<td>".(int)$r['id']."</td>";
  echo "<td>".htmlspecialchars($r['nome'])."</td>";
  echo "<td>".htmlspecialchars($r['email'])."</td>";
  echo "<td>".htmlspecialchars($r['telefone'])."</td>";
  echo "<td>".htmlspecialchars($r['foto'])."</td>";
  echo "<td>".htmlspecialchars($r['data_cadastro'])."</td>";
  echo "</tr>";
}
echo "</table>";
exit;
 
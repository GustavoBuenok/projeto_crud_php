<?php
function db(): PDO {

    static $pdo;
    //A variavel %pdo é static , ou seja, se a função for chamada de novo, ela reaproveita a mesma conexão (não cria uma nova a cada vez)

    if (!$pdo){
        //verifica se ainda não existe conexão ativa

        try {
            // Tenta executar o bloco abaixo (se der erro, o catch vai tratar)

            $dsn = 'mysql:host=sql313.infinityfree.com;dbname=if0_40403246_gustavo_db;charset=utf8mb4';
            // Define a string de conexão (DNS) dizendo o tipo de banco (mysql)
            // o servidor (127.0.0.1 -> local), o nome do banco (seunome_db)
            // e o conjunto de caracteres (utf8mb4)

            $pdo = new PDO(
                $dsn, //caminho do banco
                'if0_40403246', // usuario do banco 
                'LH1PIXLd7bUPfVT', // senha do banco 

                [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                //DEFINE QUE SE DER ERRO, O PDO VAI LANÇAR UMA EXCECAO(ERRO VISIVEL)    
                
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                //DEFINE QUE QUANDO BUSCAR DADOS, ELES VIRÃO COMO ARRAYS ASSOCIATIVOS (OU SEJA, COM NOMES DAS COLUNAS, E NÃO NUMEROS)
                ]

            
            );

         //  echo "<b> Conectado com sucesso ao banco!</b>";
            // Mostra a mensagem direto na tela se a conexão der certo
        
        } catch (PDOException $e){
            //se der algum erro no bloco try (acima), cai aqui.

            echo "<b> Erro ao conectar ao banco: </b>" . $e->getMessage();
            //mostra a mensagem de erro diretamente na tela

            exit;
            // Encerra a execução do script (opcional)


        }
    }
    return $pdo;
    //Retorna o objeto de conexão PDO pra ser usado em outras partes do sistema
}

//chama a função automaticamente se o arquivo for aberto direto no navegador
if(basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])){
    db(); //executa a conexão e mostra a mensagem na tela
}
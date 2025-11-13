<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>


     <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Projeto CRUD</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="formulario.php">Cadastro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php">Lista</a>
          </li>
        </ul>

        <!-- Bloco do usuário logado -->
        <?php
        $u = $_SESSION['usuario'] ?? null;
        if ($u): ?>
          <span class="navbar-text ms-auto">
            Logado como: <b><?= htmlspecialchars($u['nome']) ?></b>
            | <a href="logout.php" class="text-white text-decoration-none">Sair</a>
          </span>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  </header>
  <main>
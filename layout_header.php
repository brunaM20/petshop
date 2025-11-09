<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pet Shop - Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8fff8; }
    nav.navbar { background-color: #2e7d32; }
    nav a.nav-link, nav .navbar-brand { color: #fff !important; }
    nav a.nav-link:hover { text-decoration: underline; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <a class="navbar-brand" href="index.php">🐾 Pet Shop</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <?php if ($usuario): ?>
        <li class="nav-item">
          <span class="nav-link">Olá, <?= htmlspecialchars($usuario['nome']) ?>!</span>
        </li>
        <?php if ($usuario['tipo'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="painel_admin.php">Painel Admin</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
          <li class="nav-item"><a class="nav-link" href="produtos.php">Produtos</a></li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Sair</a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Entrar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cadastro.php">Cadastrar</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

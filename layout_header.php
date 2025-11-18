<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pet Shop Online</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap e fontes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fffdf8;
      color: #333;
    }

    /* 🌿 Navbar moderna */
    .navbar {
      background: linear-gradient(90deg, #2e7d32, #388e3c);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      padding: 0.75rem 1rem;
    }

    .navbar-brand {
      font-weight: 700;
      color: #fff !important;
      font-size: 1.7rem;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .navbar-brand:hover {
      color: #c8e6c9 !important;
      text-shadow: 0 0 8px rgba(255,255,255,0.3);
    }

    .navbar-nav .nav-link {
      color: #e8f5e9 !important;
      font-weight: 500;
      margin: 0 6px;
      border-radius: 20px;
      transition: all 0.3s ease;
      padding: 8px 14px;
    }

    .navbar-nav .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.15);
      color: #fff !important;
    }

    .nav-item .btn-login {
      background-color: #43a047;
      color: #fff !important;
      font-weight: 600;
      border-radius: 25px;
      padding: 8px 16px;
      transition: all 0.3s;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .btn-login:hover {
      background-color: #2e7d32;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.25);
    }

    .dropdown-menu {
      border-radius: 12px;
      border: none;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .dropdown-item:hover {
      background-color: #c8e6c9;
      color: #2e7d32 !important;
    }

    footer {
      background-color: #2e7d32;
      color: #fff;
      text-align: center;
      padding: 20px 10px;
      font-size: 0.9rem;
      margin-top: 60px;
    }

    @media (max-width: 991px) {
      .navbar-nav {
        text-align: center;
        background-color: #2e7d32;
        border-radius: 10px;
        padding: 10px;
      }
      .nav-link {
        margin: 5px 0;
      }
    }
  </style>
</head>

<body>

<!-- 🌿 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">🐾 PetShop</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="produtos.php">Produtos</a></li>
        <li class="nav-item"><a class="nav-link" href="agendamento.php">Agendar</a></li>

        <?php if (isset($_SESSION['usuario'])): ?>
          <?php if ($_SESSION['usuario']['tipo'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link fw-bold text-warning" href="painel_admin.php">Painel Admin</a>
            </li>
          <?php endif; ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              👤 <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
            </ul>
          </li>

        <?php else: ?>
          <li class="nav-item"><a class="nav-link btn-login ms-2" href="login.php">Entrar</a></li>
          <li class="nav-item"><a class="nav-link btn-login ms-2" href="cadastro.php">Cadastrar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

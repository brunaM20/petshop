<?php
session_start();
require_once __DIR__ . '/db/conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        // Cadastra o novo usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:n, :e, :s)");
        $stmt->execute([':n' => $nome, ':e' => $email, ':s' => $senha]);

        // Recupera o ID do usuário recém-criado
        $usuario_id = $pdo->lastInsertId();

        // Login automático com ID
        $_SESSION['usuario'] = [
            'id' => $usuario_id,
            'nome' => $nome,
            'email' => $email
        ];

        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'UNIQUE')) {
            $mensagem = "❌ Este e-mail já está cadastrado.";
        } else {
            $mensagem = "Erro no cadastro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - Pet Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8fff8;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-success {
      background-color: #1e8449;
      border: none;
      color: #fff;
      font-weight: 600;
    }
    .btn-success:hover {
      background-color: #166d3b;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="max-width: 400px; width: 100%;">
      <h3 class="text-center text-success fw-bold mb-3">🐾 Criar Conta</h3>

      <?php if ($mensagem): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($mensagem) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" class="form-control" name="nome" placeholder="Nome completo" required>
        </div>
        <div class="mb-3">
          <input type="email" class="form-control" name="email" placeholder="E-mail" required>
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" name="senha" placeholder="Senha" required>
        </div>
        <button class="btn btn-success w-100">Cadastrar e Continuar</button>
      </form>

      <p class="text-center mt-3">
        Já tem conta? <a href="login.php" class="text-success fw-semibold">Entrar</a>
      </p>
    </div>
  </div>
</body>
</html>

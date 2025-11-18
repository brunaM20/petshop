<?php
session_start();
require_once __DIR__ . '/db/conexao.php';

// 🔒 Bloqueia o acesso se não for admin
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['tipo'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$mensagem = "";

// === ALTERAR DISPONIBILIDADE DO PRODUTO ===
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];

    try {
        $pdo->exec("
            UPDATE produtos 
            SET disponivel = CASE disponivel WHEN 1 THEN 0 ELSE 1 END 
            WHERE id = $id
        ");
        $mensagem = "🔄 Status do produto atualizado!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar status: " . $e->getMessage();
    }
}

// === CADASTRO DE PRODUTO ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'produto') {
    $nome = trim($_POST['nome']);
    $preco = floatval($_POST['preco']);
    $descricao = trim($_POST['descricao']);
    $imagem = trim($_POST['imagem']);

    try {
        $stmt = $pdo->prepare("
            CREATE TABLE IF NOT EXISTS produtos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                preco REAL NOT NULL,
                descricao TEXT,
                imagem TEXT,
                disponivel INTEGER DEFAULT 1
            )
        ");
        $stmt->execute();

        $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, descricao, imagem) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $preco, $descricao, $imagem]);

        $mensagem = "✅ Produto cadastrado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar produto: " . $e->getMessage();
    }
}

// === CANCELAR AGENDAMENTO ===
if (isset($_GET['cancelar'])) {
    $id = (int)$_GET['cancelar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id = ?");
        $stmt->execute([$id]);
        $mensagem = "❌ Agendamento cancelado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao cancelar agendamento: " . $e->getMessage();
    }
}

// === LISTAGENS ===
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$agendamentos = $pdo->query("
    SELECT a.id, u.nome AS cliente, a.pet_nome, a.servico, a.telefone, a.data_agendada
    FROM agendamentos a
    JOIN usuarios u ON a.usuario_id = u.id
    ORDER BY a.data_agendada DESC
")->fetchAll(PDO::FETCH_ASSOC);

$admin_nome = htmlspecialchars($_SESSION['usuario']['nome']);
?>

<?php include 'layout_header.php'; ?>

<div class="header-admin text-center py-4 text-white" style="background-color: #2e7d32;">
    <h2>🐾 Painel do Administrador</h2>
    <p>Bem-vindo, <strong><?= $admin_nome ?></strong>!</p>
    <a href="logout.php" class="btn btn-light btn-sm mt-2">Sair</a>
</div>

<div class="container py-5">

    <?php if ($mensagem): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <!-- CADASTRAR PRODUTO -->
    <div class="card p-4 mb-4 shadow-sm">
        <h4 class="text-success mb-3">Cadastrar Novo Produto</h4>
        <form method="POST">
            <input type="hidden" name="acao" value="produto">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="nome" class="form-control" placeholder="Nome do produto" required>
                </div>
                <div class="col-md-6">
                    <input type="number" step="0.01" name="preco" class="form-control" placeholder="Preço (R$)" required>
                </div>
                <div class="col-12">
                    <textarea name="descricao" class="form-control" placeholder="Descrição" rows="2"></textarea>
                </div>
                <div class="col-12">
                    <input type="text" name="imagem" class="form-control" placeholder="URL da imagem (opcional)">
                </div>
                <div class="col-12 text-center mt-3">
                    <button class="btn btn-success w-50">Cadastrar Produto</button>
                </div>
            </div>
        </form>
    </div>

    <!-- LISTA DE PRODUTOS -->
    <div class="card p-4 mb-4 shadow-sm">
        <h4 class="text-success mb-3">Produtos Cadastrados</h4>
        <?php if (empty($produtos)): ?>
            <p class="text-center">Nenhum produto cadastrado ainda.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($p['descricao']) ?></td>

                                <td>
                                    <?php if (($p['disponivel'] ?? 1) == 1): ?>
                                        <span class="badge bg-success">Disponível</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Indisponível</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="?toggle=<?= $p['id'] ?>" class="btn btn-outline-success btn-sm">
                                        Alternar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- LISTA DE AGENDAMENTOS -->
    <div class="card p-4 mb-5 shadow-sm">
        <h4 class="text-success mb-3">Agendamentos de Clientes</h4>
        <?php if (empty($agendamentos)): ?>
            <p class="text-center">Nenhum agendamento encontrado.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Pet</th>
                            <th>Serviço</th>
                            <th>Telefone</th>
                            <th>Data</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $a): ?>
                            <tr>
                                <td><?= $a['id'] ?></td>
                                <td><?= htmlspecialchars($a['cliente']) ?></td>
                                <td><?= htmlspecialchars($a['pet_nome']) ?></td>
                                <td><?= htmlspecialchars($a['servico']) ?></td>
                                <td><?= htmlspecialchars($a['telefone']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($a['data_agendada'])) ?></td>
                                <td>
                                    <a href="?cancelar=<?= $a['id'] ?>" class="btn btn-danger btn-sm"
                                       onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')">Cancelar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'layout_footer.php'; ?>

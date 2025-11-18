<?php
require_once __DIR__ . '/db/conexao.php';

try {
    $pdo->exec("ALTER TABLE produtos ADD COLUMN disponivel INTEGER DEFAULT 1;");
    echo "✅ Coluna 'disponivel' adicionada com sucesso!";
} catch (PDOException $e) {
    echo "⚠ Erro: " . $e->getMessage();
}

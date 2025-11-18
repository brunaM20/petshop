<?php
require_once __DIR__ . '/db/conexao.php';
include 'layout_header.php';

try {
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar produtos: " . $e->getMessage());
}
?>

<div class="container py-5">
  <h2 class="text-center text-success mb-4">🛍️ Nossos Produtos</h2>

  <?php if (count($produtos) === 0): ?>
    <p class="text-center">Nenhum produto cadastrado ainda.</p>
  <?php else: ?>
    <div class="row">
      <?php foreach ($produtos as $p): ?>
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="card shadow-sm border-0">
            <img 
              src="<?php echo !empty($p['imagem']) ? htmlspecialchars($p['imagem']) : 'https://via.placeholder.com/400x250?text=Sem+Imagem'; ?>" 
              class="card-img-top" 
              alt="<?php echo htmlspecialchars($p['nome']); ?>"
              style="height: 220px; object-fit: cover;"
            >
            <div class="card-body text-center">
              <h5 class="card-title text-success"><?php echo htmlspecialchars($p['nome']); ?></h5>
              <p class="card-text" style="min-height: 60px;">
                <?php echo htmlspecialchars($p['descricao']); ?>
              </p>
              <p class="fw-bold fs-5 text-success">
                R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
              </p>
<?php if (isset($p['disponivel']) && $p['disponivel'] == 0): ?>

  <!-- Botão indicando indisponível -->
  <button class="btn btn-secondary w-100" onclick="abrirPopup(<?= $p['id'] ?>)">
      Indisponível
  </button>

  <!-- POPUP -->
  <div class="overlay" id="popupOverlay<?= $p['id'] ?>" style="
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
  ">
      <div class="popup" style="
          background: #fff;
          padding: 25px;
          border-radius: 10px;
          width: 300px;
          text-align: center;
          box-shadow: 0 0 12px rgba(0,0,0,0.3);
          animation: aparecer .3s ease;
      ">
          <h3 style="color:#d60000; font-weight:bold;">Produto Indisponível</h3>
          <p>Desculpe, este produto não está disponível no momento.</p>
          <button onclick="fecharPopup(<?= $p['id'] ?>)" style="
              padding: 10px 16px;
              background:#d60000;
              border:none;
              color:#fff;
              border-radius:6px;
              cursor:pointer;
          ">Fechar</button>
      </div>
  </div>

<?php else: ?>

  <!-- Botão normal para comprar -->
  <button class="btn btn-outline-success w-100">Comprar</button>

<?php endif; ?>

<script>
function abrirPopup(id) {
  document.getElementById("popupOverlay" + id).style.display = "flex";
}
function fecharPopup(id) {
  document.getElementById("popupOverlay" + id).style.display = "none";
}
</script>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include 'layout_footer.php'; ?>

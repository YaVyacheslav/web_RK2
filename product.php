<?php
declare(strict_types=1);

$title = 'Товар';

require __DIR__ . '/inc/header.php';
require __DIR__ . '/config/db.php';

$user = $_SESSION['user'] ?? null;

$id = (int) ($_GET['id'] ?? 0);
$from = $_GET['from'] ?? null;

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    http_response_code(404);
    exit('Товар не найден');
}

$specs = [];

if (!empty($p['specs'])) {
    $specs = json_decode((string) $p['specs'], true) ?: [];
}

$qty = 0;

if ($user) {
    $stmt = $pdo->prepare(
        '
        SELECT qty
        FROM wishlist
        WHERE user_id = ? AND product_id = ?
        '
    );
    $stmt->execute([(int) $user['id'], (int) $p['id']]);
    $qty = (int) ($stmt->fetchColumn() ?: 0);
}
?>

<section class="container section">
    <?php if ($from === 'wishlist'): ?>
        <a class="back-link" href="wishlist.php">← Назад в список покупок</a>
    <?php else: ?>
        <a class="back-link" href="shop.php">← Назад в магазин</a>
    <?php endif; ?>

    <div class="product-page">
        <div class="product-page__image">
            <img src="<?= htmlspecialchars($p['image']) ?>" alt="">
        </div>

        <div class="product-page__info">
            <h1><?= htmlspecialchars($p['title']) ?></h1>

            <div class="product-page__price">
                <?= number_format((float) $p['price'], 2, '.', ' ') ?> ₽
            </div>

            <div class="product-page__stock <?= (int) $p['stock'] > 0 ? 'in-stock' : 'out-stock' ?>">
                <?= (int) $p['stock'] > 0 ? 'В наличии' : 'Нет в наличии' ?>
            </div>

            <p class="product-page__desc">
                <?= nl2br(htmlspecialchars($p['full_desc'])) ?>
            </p>

            <?php if ($specs): ?>
                <div class="product-specs">
                    <h3>Характеристики</h3>

                    <ul>
                        <?php foreach ($specs as $k => $v): ?>
                            <li>
                                <span><?= htmlspecialchars($k) ?>:</span>
                                <?= htmlspecialchars($v) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($user): ?>
                <button
                    class="btn btn--wide <?= $qty > 0 ? 'hidden' : '' ?>"
                    data-product-add="<?= (int) $p['id'] ?>"
                >
                    Добавить в список покупок
                </button>

                <div class="qty-control <?= $qty === 0 ? 'hidden' : '' ?>">
                    <button
                        class="qty-btn"
                        data-qty-dec="<?= (int) $p['id'] ?>"
                    >
                        −
                    </button>

                    <span
                        class="qty-value"
                        data-qty-value="<?= (int) $p['id'] ?>"
                    >
                        <?= max(1, $qty) ?>
                    </span>

                    <button
                        class="qty-btn"
                        data-qty-inc="<?= (int) $p['id'] ?>"
                    >
                        +
                    </button>
                </div>
            <?php else: ?>
                <div class="muted">Войдите, чтобы добавить товар</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>

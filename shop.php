<?php
$title = 'Магазин';
require __DIR__ . '/inc/header.php';
require __DIR__ . '/config/db.php';

$rows = $pdo->query(
    '
    SELECT id, title, short_desc, price, image, stock
    FROM products
    ORDER BY id DESC
    '
)->fetchAll();
?>

<section class="container section">
    <h2>Магазин мем-плакатов</h2>

    <div class="table-wrap">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Цена</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $p): ?>
                    <tr
                        class="product-row"
                        onclick="window.location='product.php?id=<?= (int) $p['id'] ?>'"
                    >
                        <td class="table-image">
                            <img
                                src="<?= htmlspecialchars($p['image']) ?>"
                                alt="<?= htmlspecialchars($p['title']) ?>"
                            >
                        </td>

                        <td class="table-title">
                            <?= htmlspecialchars($p['title']) ?>

                            <?php if ($p['stock'] <= 0): ?>
                                <div class="muted">Нет в наличии</div>
                            <?php endif; ?>
                        </td>

                        <td class="table-desc">
                            <?= htmlspecialchars($p['short_desc']) ?>
                        </td>

                        <td class="table-price">
                            <?= number_format((int) $p['price'], 0, '.', ' ') ?> ₽
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>

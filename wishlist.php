<?php
$title = 'Список покупок';
require __DIR__ . '/inc/header.php';
require __DIR__ . '/config/db.php';

$user = $_SESSION['user'] ?? null;

if (!$user) {
    http_response_code(403);
    exit('Требуется авторизация');
}

$stmt = $pdo->prepare(
    '
    SELECT w.product_id, w.qty, p.title, p.price, p.image
    FROM wishlist w
    JOIN products p ON p.id = w.product_id
    WHERE w.user_id = ?
    ORDER BY w.created_at DESC
    '
);
$stmt->execute([(int) $user['id']]);
$items = $stmt->fetchAll();
?>

<section class="container section">
    <h2>Список покупок</h2>

    <?php if (!$items): ?>
        <p class="muted">Пока пусто. Добавь товары из «Магазина».</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="products-table cart-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Количество и цена</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($items as $it): ?>
                        <tr class="cart-row">
                            <td class="cart-product">
                                <a
                                    href="product.php?id=<?= (int) $it['product_id'] ?>&from=wishlist"
                                >
                                    <img
                                        class="cart-image"
                                        src="<?= htmlspecialchars($it['image']) ?>"
                                        alt="<?= htmlspecialchars($it['title']) ?>"
                                    >
                                </a>

                                <div class="cart-info">
                                    <a
                                        href="product.php?id=<?= (int) $it['product_id'] ?>&from=wishlist"
                                        class="cart-title"
                                    >
                                        <?= htmlspecialchars($it['title']) ?>
                                    </a>
                                </div>
                            </td>

                            <td>
                                <div class="cart-right">
                                    <div class="qty-control">
                                        <button
                                            class="qty-btn"
                                            data-qty-dec="<?= (int) $it['product_id'] ?>"
                                        >
                                            −
                                        </button>

                                        <span
                                            class="qty-value"
                                            data-qty-value="<?= (int) $it['product_id'] ?>"
                                        >
                                            <?= (int) $it['qty'] ?>
                                        </span>

                                        <button
                                            class="qty-btn"
                                            data-qty-inc="<?= (int) $it['product_id'] ?>"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <div
                                        class="cart-price"
                                        data-unit-price="<?= (int) $it['price'] ?>"
                                    >
                                        <div class="muted">
                                            <?= number_format((int) $it['price'], 0, '.', ' ') ?> ₽ / шт
                                        </div>

                                        <strong class="item-total">
                                            <?= number_format((int) $it['price'] * (int) $it['qty'], 0, '.', ' ') ?> ₽
                                        </strong>
                                    </div>

                                    <button
                                        class="btn btn--small btn--ghost"
                                        data-remove-from-wishlist="<?= (int) $it['product_id'] ?>"
                                    >
                                        Удалить
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-total" id="payment">
            <div class="cart-total__row">
                <span>Итого:</span>
                <strong id="cartTotal">0 ₽</strong>
            </div>

            <button class="btn btn--pay" id="payBtn">
                Перейти к оплате
            </button>
        </div>
    <?php endif; ?>
</section>

<div class="modal hidden" id="paymentModal">
    <div class="modal__overlay"></div>

    <div class="modal__card">
        <h3>Оплата заказа</h3>

        <p class="pay-total">
            К списанию: <strong id="payAmount">0 ₽</strong>
        </p>

        <div class="pay-field">
            <label for="cardNumber">Номер карты</label>
            <input
                id="cardNumber"
                inputmode="numeric"
                autocomplete="cc-number"
                placeholder="0000 0000 0000 0000"
                maxlength="19"
            >
        </div>

        <div class="pay-field">
            <label for="cardName">Имя на карте</label>
            <input
                id="cardName"
                autocomplete="cc-name"
                placeholder="IVAN IVANOV"
            >
        </div>

        <div class="modal__row">
            <div class="pay-field">
                <label for="cardExp">Срок действия</label>
                <input
                    id="cardExp"
                    inputmode="numeric"
                    autocomplete="cc-exp"
                    placeholder="MM/YY"
                    maxlength="5"
                >
            </div>

            <div class="pay-field">
                <label for="cardCvc">CVC</label>
                <input
                    id="cardCvc"
                    inputmode="numeric"
                    autocomplete="cc-csc"
                    placeholder="123"
                    maxlength="3"
                >
            </div>
        </div>

        <button class="btn btn--wide" id="confirmPayBtn">
            Оплатить
        </button>

        <button class="btn btn--ghost btn--wide" id="closePayBtn">
            Отмена
        </button>
    </div>
</div>

<a href="#payment" class="pay-anchor">
    К оплате
</a>

<?php require __DIR__ . '/inc/footer.php'; ?>

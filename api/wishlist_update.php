<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$user = $_SESSION['user'] ?? null;

if (!$user) {
    http_response_code(401);
    echo json_encode(['ok' => false]);
    exit;
}

require __DIR__ . '/../config/db.php';

$productId = (int) ($_POST['product_id'] ?? 0);
$delta = (int) ($_POST['delta'] ?? 0);

if ($productId <= 0 || !in_array($delta, [1, -1], true)) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
$stmt->execute([$productId]);
$stock = (int) $stmt->fetchColumn();

if ($stock <= 0) {
    echo json_encode(['ok' => false, 'error' => 'out_of_stock']);
    exit;
}

$stmt = $pdo->prepare(
    '
    SELECT qty
    FROM wishlist
    WHERE user_id = ? AND product_id = ?
    '
);
$stmt->execute([(int) $user['id'], $productId]);
$currentQty = (int) $stmt->fetchColumn();

$newQty = $currentQty + $delta;

if ($newQty > $stock) {
    echo json_encode(['ok' => false, 'error' => 'out_of_stock']);
    exit;
}

if ($newQty <= 0) {
    $pdo->prepare(
        '
        DELETE FROM wishlist
        WHERE user_id = ? AND product_id = ?
        '
    )->execute([(int) $user['id'], $productId]);

    echo json_encode(['ok' => true, 'qty' => 0]);
    exit;
}

$pdo->prepare(
    '
    INSERT INTO wishlist (user_id, product_id, qty)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE qty = VALUES(qty)
    '
)->execute([(int) $user['id'], $productId, $newQty]);

echo json_encode([
    'ok' => true,
    'qty' => $newQty,
]);

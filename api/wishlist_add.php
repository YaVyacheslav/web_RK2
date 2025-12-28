<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$user = $_SESSION['user'] ?? null;

if (!$user) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'auth']);
    exit;
}

require __DIR__ . '/../config/db.php';

$productId = (int) ($_POST['product_id'] ?? 0);

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'bad_id']);
    exit;
}

$stmt = $pdo->prepare(
    '
    INSERT INTO wishlist (user_id, product_id, qty)
    VALUES (?, ?, 1)
    ON DUPLICATE KEY UPDATE qty = qty + 1
    '
);
$stmt->execute([(int) $user['id'], $productId]);

$stmt = $pdo->prepare(
    '
    SELECT qty
    FROM wishlist
    WHERE user_id = ? AND product_id = ?
    '
);
$stmt->execute([(int) $user['id'], $productId]);

$qty = (int) $stmt->fetchColumn();

echo json_encode([
    'ok' => true,
    'qty' => $qty,
]);

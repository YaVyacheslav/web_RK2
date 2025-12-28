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

$stmt = $pdo->prepare(
    'DELETE FROM wishlist WHERE user_id = ? AND product_id = ?'
);
$stmt->execute([(int) $user['id'], $productId]);

echo json_encode(['ok' => true]);

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

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        '
        SELECT w.product_id, w.qty, p.stock
        FROM wishlist w
        JOIN products p ON p.id = w.product_id
        WHERE w.user_id = ?
        FOR UPDATE
        '
    );
    $stmt->execute([(int) $user['id']]);
    $items = $stmt->fetchAll();

    foreach ($items as $it) {
        if ((int) $it['qty'] > (int) $it['stock']) {
            throw new Exception('Недостаточно товара на складе');
        }

        $pdo->prepare(
            '
            UPDATE products
            SET stock = stock - ?
            WHERE id = ?
            '
        )->execute([(int) $it['qty'], (int) $it['product_id']]);
    }

    $pdo->prepare(
        '
        DELETE FROM wishlist
        WHERE user_id = ?
        '
    )->execute([(int) $user['id']]);

    $pdo->commit();

    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(400);

    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage(),
    ]);
}

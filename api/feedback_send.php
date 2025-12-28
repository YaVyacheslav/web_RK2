<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/../config/db.php';

$user = $_SESSION['user'] ?? null;

$message = trim((string) ($_POST['message'] ?? ''));

if ($message === '') {
    header('Location: ../contact.php?sent=0');
    exit;
}

if ($user) {
    $userId = (int) $user['id'];
    $name = $user['name'];
    $email = $user['email'];
} else {
    $userId = null;
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));

    if ($name === '' || $email === '') {
        header('Location: ../contact.php?sent=0');
        exit;
    }
}

$stmt = $pdo->prepare(
    '
    INSERT INTO feedback (user_id, name, email, message)
    VALUES (?, ?, ?, ?)
    '
);

$stmt->execute([
    $userId,
    $name,
    $email,
    $message,
]);

header('Location: ../contact.php?sent=1');
exit;

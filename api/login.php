<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/../config/db.php';

$email = trim((string) ($_POST['email'] ?? ''));
$pass = (string) ($_POST['password'] ?? '');

$stmt = $pdo->prepare(
    'SELECT id, email, pass_hash, name FROM users WHERE email = ? LIMIT 1'
);
$stmt->execute([$email]);
$u = $stmt->fetch();

if (!$u || !password_verify($pass, $u['pass_hash'])) {
    header('Location: ../index.php?login=fail');
    exit;
}

$_SESSION['user'] = [
    'id' => (int) $u['id'],
    'email' => $u['email'],
    'name' => $u['name'],
];

header('Location: ../index.php?login=ok');
exit;

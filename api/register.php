<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/../config/db.php';

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$pass = (string) ($_POST['password'] ?? '');

if ($name === '' || $email === '' || $pass === '') {
    header('Location: ../register.php?err=Заполните%20все%20поля');
    exit;
}

$hash = password_hash($pass, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, pass_hash) VALUES (?, ?, ?)'
    );
    $stmt->execute([$name, $email, $hash]);
} catch (PDOException $e) {
    header('Location: ../register.php?err=Email%20уже%20занят');
    exit;
}

$_SESSION['user'] = [
    'id' => (int) $pdo->lastInsertId(),
    'name' => $name,
    'email' => $email,
];

header('Location: ../index.php');
exit;

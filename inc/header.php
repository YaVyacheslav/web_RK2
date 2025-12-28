<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

$BASE_URL = '/web_rgr/';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'MemePosters') ?></title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/style.css">
    <script defer src="<?= $BASE_URL ?>assets/js/app.js"></script>
</head>
<body>

<header class="header">
    <div class="container header__row">
        <a class="brand" href="<?= $BASE_URL ?>index.php">
            <span class="brand__logo">
                <img src="<?= $BASE_URL ?>assets/img/logo.png" alt="MemePosters logo">
            </span>
            <span class="brand__name">MemePosters</span>
        </a>

        <nav class="nav">
            <a href="<?= $BASE_URL ?>shop.php">Магазин</a>
            <a href="<?= $BASE_URL ?>contact.php">Контакты</a>

            <?php if ($user): ?>
                <a href="<?= $BASE_URL ?>wishlist.php">Список покупок</a>
            <?php endif; ?>
        </nav>

        <div class="auth">
            <?php if (!$user): ?>
                <div class="auth__row">
                    <form
                        class="auth__form"
                        method="post"
                        action="<?= $BASE_URL ?>api/login.php"
                    >
                        <input
                            type="email"
                            name="email"
                            placeholder="email"
                            required
                        >
                        <input
                            type="password"
                            name="password"
                            placeholder="пароль"
                            required
                        >
                        <button type="submit" class="btn btn--small">
                            Войти
                        </button>
                    </form>

                    <a
                        class="btn btn--ghost btn--small"
                        href="<?= $BASE_URL ?>register.php"
                    >
                        Регистрация
                    </a>
                </div>
            <?php else: ?>
                <div class="auth__user">
                    <div class="user-badge">
                        <span class="user-icon">👤</span>
                        <span class="user-name">
                            <?= htmlspecialchars($user['name']) ?>
                        </span>
                    </div>

                    <form method="post" action="<?= $BASE_URL ?>api/logout.php">
                        <button
                            type="submit"
                            class="btn btn--ghost btn--small"
                        >
                            Выйти
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="main">

<?php
$title = 'Регистрация — MemePosters';
require __DIR__ . '/inc/header.php';
?>

<section class="container section auth-page">
    <div class="auth-card">
        <h2>Создать аккаунт</h2>

        <p class="muted auth-subtitle">
            Зарегистрируйся, чтобы сохранять понравившиеся плакаты
            и управлять списком покупок.
        </p>

        <?php if (!empty($_GET['err'])): ?>
            <div class="notice notice--bad">
                <?= htmlspecialchars((string) $_GET['err']) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="api/register.php" class="auth-form">
            <input
                name="name"
                placeholder="Имя"
                required
            >

            <input
                name="email"
                type="email"
                placeholder="Email"
                required
            >

            <input
                name="password"
                type="password"
                placeholder="Пароль (минимум 4 символа)"
                required
                minlength="4"
            >

            <button class="btn btn--wide" type="submit">
                Зарегистрироваться
            </button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>

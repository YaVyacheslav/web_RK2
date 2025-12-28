<?php
$title = 'MemePosters — Главная';
require __DIR__ . '/inc/header.php';
require __DIR__ . '/config/db.php';

$slides = $pdo->query("
    SELECT id, title, price, image
    FROM products
    ORDER BY id DESC
    LIMIT 5
")->fetchAll();
?>

<section class="hero-section">
    <div class="container hero">
        <div class="hero__left">
            <h1>Мемы на стену — чтобы жить легче</h1>

            <p class="muted">
                Плакаты: матовая или глянцевая бумага, понятные характеристики и наличие на складе.
            </p>

            <div class="hero__actions">
                <a class="btn" href="shop.php">Открыть магазин</a>
                <a class="btn btn--ghost" href="#about">Подробнее</a>
            </div>
        </div>

        <div class="hero__right">
            <div class="slider" id="topSlider">
                <div class="slides-track">
                    <?php foreach ($slides as $p): ?>
                        <div class="slide">
                            <a href="product.php?id=<?= (int) $p['id'] ?>" class="slide-link">
                                <img
                                    src="<?= htmlspecialchars($p['image']) ?>"
                                    alt="<?= htmlspecialchars($p['title']) ?>"
                                >
                            </a>

                            <div class="slide-info">
                                <h3><?= htmlspecialchars($p['title']) ?></h3>

                                <div class="slide-price">
                                    <?= number_format((int) $p['price'], 0, '.', ' ') ?> ₽
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="slider-btn prev" id="sliderPrev">‹</button>
                <button class="slider-btn next" id="sliderNext">›</button>
            </div>
        </div>
    </div>
</section>

<section id="about" class="container section">
    <h2>О магазине</h2>

    <div class="grid-2">
        <div class="card">
            <h3>Что здесь можно купить</h3>

            <p class="muted">
                Плакаты с мемами в разных форматах.
                В разделе «Магазин» представлена таблица со всеми товарами.
            </p>
        </div>

        <div class="card">
            <h3>Почему мы это делаем</h3>

            <p class="muted">
                Мы верим, что пространство вокруг человека влияет на настроение и продуктивность.
                Немного юмора на стене может сделать рабочий день легче,
                а дом — уютнее и живее.
            </p>

            <p class="muted">
                MemePosters — это способ добавить в интерьер характер,
                самоиронию и связь с интернет-культурой, которую мы все понимаем без слов.
            </p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>

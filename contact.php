<?php
$title = 'Контакты';
require __DIR__ . '/inc/header.php';
?>

<section class="container section">
    <h2>Контакты</h2>

    <div class="grid-2">
        <div class="card">
            <h3>Свяжитесь с нами</h3>

            <p class="muted">
                MemePosters — небольшой независимый проект.
                Мы всегда открыты к вопросам, предложениям и обратной связи.
            </p>

            <p class="muted">
                Если у тебя есть идея для нового плаката, вопрос по заказу
                или просто хочется написать — используй форму обратной связи.
            </p>

            <p class="muted">
                Мы читаем все сообщения и стараемся отвечать как можно быстрее.
            </p>
        </div>

        <div class="card">
            <h3>Обратная связь</h3>

            <form class="feedback-form" method="post" action="<?= $BASE_URL ?>api/feedback_send.php">
                <?php if (!$user): ?>
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="email" name="email" placeholder="Email" required>
                <?php endif; ?>

                <textarea
                    name="message"
                    placeholder="Напишите ваше сообщение или идею для плаката"
                    required
                ></textarea>

                <button class="btn btn--wide" type="submit">
                    Отправить сообщение
                </button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>

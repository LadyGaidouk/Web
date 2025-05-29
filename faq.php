<?php include 'header.php'; ?>
    <div class="text">
        <div class="sub-text2">
            <a href="contact.php" class="clickable scaleX"><</a>
            <h1>Заказать ли IT проект?</h1>
            <a href="contact.php" class="clickable scaleX">></a>
        </div>
    </div>
    <div class="description">
        <div class="sub-description">
            <h2>Инновации бизнеса начинаются с Вашего запроса.</h2>
        </div>
    </div>
</section>
<section class="container2">
    <form class="container2" method="POST" action="bot.php">
        <div class="big_words">
            <p>БУДУЩЕЕ СЕЙЧАС</p>
        </div>
        <div class="zhizha3">
            <img src="assets/Picture/zhizha10.webp" class="float-element" decoding="async" alt="Заказать UX/UI дизайн" width ="100%" height="auto">
        </div>
        <div class="forms-wide-container">
            <div class="forms wide-form">
                <p>Что ж, рассказывайте:</p>
                <input type="text" name="username" placeholder="Как к Вам обращаться?" required>
                <input type="text" name="contact" placeholder="Номер телефона/@username" required>
                <textarea name="message" aria-label="Расскажите о Вашем бизнесе" required>Первым делом - знакомство. Расскажите о Вашей нише, оставьте ссылку на сайт, группу. Проведём оценку и дадим рекомендации</textarea>
                <label>
                     <input type='checkbox' name='policy' style="opacity: 0.5; margin-left: 20px;" required>
                        <h6>При отправке заявки я соглашаюсь только с 
                           <a style="color: var(--color-accent); text-decoration: none;" href="/policy.php">
                                <span class="clickable">
                                    этим
                                </span>
                            </a>
                        </h6>
                </label>
                        
                <!-- Honeypot -->
                <input type="text" name="email_confirm" class="honeypot" autocomplete="off">

                <!-- Скрытая дата/время -->
                <input type="hidden" name="form_timestamp" value="<?= date('c') ?>">
            </div>
            <div class="button-container">
                <button type="submit" class="action-button clickable">Сделать шаг
                    <img src="assets/Picture/plane.png" alt="Заказать IT решение" class="button-icon">
                </button>
                <p class="npm-text">npm start</p>
            </div>
        </div>
    </form>
</section>

<script src="main.js"></script>
</body>
</html>
 

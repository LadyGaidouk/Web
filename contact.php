<?php include 'header.php'; ?>
    <div class="text">
        <div class="sub-text">
            <a href="faq.php" class="clickable"><img src="assets/Picture/<.svg" alt="Заказать сайт"></a>
            <h1>Заказать IT проект</h1>
            <a href="faq.php" class="clickable"><img src="assets/Picture/>.svg" alt="Заказать разработку сайта"></a>
        </div>
    </div>
    <div class="description">
        <div class="sub-description">
            <h2>Делаем бизнес инструменты, повышающие конверсию на 30%</h2>
        </div>
    </div>
</div>
</section>
<section class="container2">
    <form method="POST" action="bot.php">
        <div style="min-height: 100vh;">
            <div class="zhizha">
                <img src="assets/Picture/zhizha3.webp" class="float-element" width ="100%" height="auto" decoding="async" alt="Заказать UX/UI дизайн для сайта">
            </div>
            <div class="big_words1">
                <p>НАЧНЕМ С БЛАНКА</p>
            </div>
            <div class="forms-container">
                <div class="forms">
                    <p>Тип проекта:</p>
                    <?php
                    $projects = [
                        "UX/UI" => "UX/UI дизайн",
                        "website" => "Веб-сайт на коде",
                        "Tilda" => "Веб-сайт на Tilda",
                        "ai" => "Интеллектуальные системы",
                        "design" => "Графический дизайн",
                        "chatbot" => "Чат-бот",
                        "other" => "Другое"
                    ];
                    foreach ($projects as $key => $value) {
                        echo "<label><input type='checkbox' name='project[]' value='$key'> $value</label>";
                    }
                    ?>
                </div>

                <div class="forms">
                    <p>Желаемый бюджет ~</p>
                    <?php
                    $budgets = [
                        "40k" => "до 40 000",
                        "40k-120k" => "от 40 000 до 120 000",
                        "120k-320k" => "от 120 000 до 320 000",
                        "320k-650k" => "от 320 000 до 660 000",
                        "650k-1200k" => "от 650 000 до 1 200 000",
                        "1200k-3600k" => "от 1 200 000 до 3 600 000",
                        "3600k" => "от 3 600 000"
                    ];
                    foreach ($budgets as $key => $value) {
                        echo "<label><input type='checkbox' name='budget[]' value='$key'> $value</label>";
                    }
                    ?>
                </div>
            </div>
           <div class="bg2">
                <div class="big_words2">
                    <p>ТЕПЕРЬ МЫСЛИ</p>
                </div>
                <div class="zhizha2">
                    <img src="assets/Picture/zhizha4.webp" class="float-element" width ="100%" height="auto" decoding="async" alt="Заказать дизайн карточек для маркетплейсов">
                </div>

                <div class="forms-wide-container">
                    <div class="forms wide-form">
                        <p>Пожелания:</p>
                        <input type="text" name="username" placeholder="Как к Вам обращаться?" required>
                        <input type="text" name="contact" placeholder="Номер телефона/@username" required>
                        <textarea name="message" aria-label="Опишите Ваш запрос IT проекта" required>Нужен сайт и чат-бот для оптимизации и сокращения расходов... Разработка нового логотипа, прикольного и привлекающего дизайна на сайт и графического дизайна для визиток, рекламных постеров на Озон.</textarea>
                        <label>
                            <input type='checkbox' name='policy' style="opacity: 0.5; margin-left: 20px;" required>
                                <h6>При отправке заявки я соглашаюсь с 
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
                            <img src="assets/Picture/plane.png" alt="Заказать IT услугу" class="button-icon">
                        </button>
                        <p class="npm-text">npm start</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<script src="main.js"></script>
</body>
</html>

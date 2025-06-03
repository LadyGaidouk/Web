<?php
header('Content-Type: text/html; charset=utf-8');
include 'header.php';
?>
<section>
    <div class="text thanks">
        <h1>Благодарим за доверие, вернемся с обратной связью.</h1>
        <p style="opacity: 0.5;">Через 2 секунды запустится игра. Задача: собрать двоичный код.</p>
        <p style="opacity: 0.5;">Предлагаем между делом заглянуть в <a href="https://t.me/notes_ITBureau" class="notes clickable">заметки</a> от IT Bureau.</p>
        <canvas id="gameCanvas"></canvas>
        <div id="word-progress">Слово: _________</div>
        <div id="score">Очки: 0</div>
        <div class="game-over" id="gameOver">
            <h2>Поздравляем!</h2>
            <p>Вы собрали секретное слово в двоичном коде!</p>
            <p>Ваше кодовое слово для скидки 10%: <strong>CODE2025</strong></p>
            <p>Сохраните код и укажите его при заказе на email: [ваша_почта] или Telegram: @ваш_логин.</p>
            <button class="button-play" onclick="copyCode()">Скопировать код</button>
            <button class="button-play" onclick="continueGame()">Продолжить игру</button>
        </div>
    </div>
</section>
<script src="game.js" defer></script>
</body>
</html>

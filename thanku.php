<?php include 'header.php'; ?>
    <div class="text thanks">
        <h1>Благодарим за доверие, вернемся с обратной связью.</h1>
        <p style="opacity: 0.5;">Через 2 секунды запустится игра. Задача: собрать двоичный код.</p>
        <p style="opacity: 0.5;">Предлагаем между делом заглянуть в <a href="https://t.me/notes_ITBureau" class="notes clickable">заметки</a> от IT Bureau.</p>
        <canvas id="gameCanvas"></canvas>
        <div id="word-progress">Слово: _________</div>
        <div id="score">Очки: 0</div>
        <div class="game-over" id="gameOver">
        </div>
    </div>
</section>
<script src="game.js" defer></script>
</body>
</html>

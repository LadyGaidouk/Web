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
            <button onclick="copyCode()">Скопировать код</button>
            <button onclick="continueGame()">Продолжить игру</button>
        </div>
    </div>
</section>
<script>
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');
    const thanks = document.querySelector('.thanks');
    const gameOver = document.getElementById('gameOver');
    const wordProgress = document.getElementById('word-progress');
    const scoreDisplay = document.getElementById('score');

    // Настройки игры
    const gridSize = 20; // Чуть больше 1rem (18px) для точного попадания
    let canvasWidth, canvasHeight;
    let snake = [{ x: 5, y: 5 }];
    let target = { x: 10, y: 10, value: '0' };
    let direction = { x: 0, y: 0 };
    let score = 0;
    let speed = 0.1;
    let gameActive = false;
    let word = '';
    let binaryITBureau = '010010010101010000100000010000100111010101110010011001010110000101110101'; // IT Bureau
    let binaryIndex = 0;
    let completed = false;

    // Адаптация канваса
    function resizeCanvas() {
        const thanksRect = thanks.getBoundingClientRect();
        canvasWidth = Math.min(thanksRect.width, 1280);
        canvasHeight = Math.min(thanksRect.height, 720);
        canvas.width = Math.floor(canvasWidth / gridSize) * gridSize;
        canvas.height = Math.floor(canvasHeight / gridSize) * gridSize;
        canvas.style.display = 'block';
    }

    // Запуск игры через 2 секунды
    setTimeout(() => {
        resizeCanvas();
        gameActive = true;
        spawnTarget();
        gameLoop();
    }, 2000);

    // Управление курсором/пальцем
    let mousePos = { x: snake[0].x * gridSize, y: snake[0].y * gridSize };
    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        mousePos = {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    });
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const rect = canvas.getBoundingClientRect();
        mousePos = {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top
        };
    });

    // Управление клавишами
    document.addEventListener('keydown', (e) => {
        switch (e.key) {
            case 'ArrowUp': case 'w': direction = { x: 0, y: -1 }; break;
            case 'ArrowDown': case 's': direction = { x: 0, y: 1 }; break;
            case 'ArrowLeft': case 'a': direction = { x: -1, y: 0 }; break;
            case 'ArrowRight': case 'd': direction = { x: 1, y: 0 }; break;
        }
    });

    // Спавн цели
    function spawnTarget() {
        if (!completed && binaryIndex < binaryITBureau.length) {
            target.value = binaryITBureau[binaryIndex];
        } else {
            target.value = Math.random() < 0.5 ? '0' : '1';
        }
        target.x = Math.floor(Math.random() * (canvasWidth / gridSize));
        target.y = Math.floor(Math.random() * (canvasHeight / gridSize));
    }

    // Игровой цикл
    function gameLoop() {
        if (!gameActive) return;

        // Движение к курсору
        const head = snake[0];
        const targetX = mousePos.x / gridSize;
        const targetY = mousePos.y / gridSize;
        if (Math.abs(targetX - head.x) > 0.1 || Math.abs(targetY - head.y) > 0.1) {
            direction.x = (targetX - head.x) * speed;
            direction.y = (targetY - head.y) * speed;
        }

        // Обновление позиции
        const newHead = {
            x: head.x + direction.x,
            y: head.y + direction.y
        };

        // Границы
        if (newHead.x < 0) newHead.x = 0;
        if (newHead.x > canvasWidth / gridSize - 1) newHead.x = canvasWidth / gridSize - 1;
        if (newHead.y < 0) newHead.y = 0;
        if (newHead.y > canvasHeight / gridSize - 1) newHead.y = canvasHeight / gridSize - 1;

        snake.unshift(newHead);
        snake.pop();

        // Проверка столкновения с целью
        if (Math.abs(newHead.x - target.x) < 0.5 && Math.abs(newHead.y - target.y) < 0.5) {
            snake.push(snake[snake.length - 1]);
            score++;
            scoreDisplay.textContent = `Очки: ${score}`;
            if (!completed && binaryIndex < binaryITBureau.length) {
                word += target.value;
                binaryIndex++;
                const displayWord = '_'.repeat(9 - Math.floor(word.length / 8));
                wordProgress.textContent = `Word: ${displayWord}`;
                if (binaryIndex >= binaryITBureau.length) {
                    completed = true;
                    gameActive = false;
                    gameOver.style.display = 'block';
                }
            }
            spawnTarget();
            if (score % 10 === 0) speed *= 1.05; // Увеличение сложности
        }

        // Отрисовка
        ctx.fillStyle = 'var(--color-blur-bg)';
        ctx.fillRect(0, 0, canvasWidth, canvasHeight);
        ctx.fillStyle = 'var(--color-accent)';
        snake.forEach((segment, i) => {
            ctx.globalAlpha = 1 - i * 0.05;
            ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
        });
        ctx.globalAlpha = 1;
        ctx.fillStyle = 'var(--color-white)';
        ctx.font = '1rem var(--font-main)';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(target.value, (target.x + 0.5) * gridSize, (target.y + 0.5) * gridSize);

        requestAnimationFrame(gameLoop);
    }

    // Продолжить игру
    function continueGame() {
        gameOver.style.display = 'none';
        gameActive = true;
        gameLoop();
    }

    // Скопировать кодовое слово
    function copyCode() {
        navigator.clipboard.writeText('CODE2025').then(() => {
            alert('Кодовое слово скопировано!');
        });
    }

    // Сохранение рекорда
    if (localStorage.getItem('snakeHighScore')) {
        scoreDisplay.textContent = `Очки: ${score} (Рекорд: ${localStorage.getItem('snakeHighScore')})`;
    }
    window.addEventListener('beforeunload', () => {
        const highScore = localStorage.getItem('snakeHighScore') || 0;
        if (score > highScore) {
            localStorage.setItem('snakeHighScore', score);
        }
    });

    window.addEventListener('resize', resizeCanvas);
</script>
</body>
</html>

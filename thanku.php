<?php include 'header.php'; ?>

    <div class="game-section text thanks">
        <h1>Благодарим за доверие, вернемся с обратной связью.</h1>
        <p class="game-description">Оставили для Вас небольшую игру для отдыха. Управляйте змейкой, собирая двоичный код.</p>
        
        <div class="game-container">
            <div class="game-stats">
                <div id="score" class="game-stat">Очки: <span>0</span></div>
                <div id="speed" class="game-stat">Скорость: <span>1x</span></div>
            </div>
            
            <div class="canvas-wrapper">
                <canvas id="gameCanvas"></canvas>
            </div>
            
            <div class="game-controls">
                <button id="restartBtn" class="game-button">
                    <svg class="button-icon" viewBox="0 0 24 24">
                        <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                    </svg>
                    Может еще разок?
                </button>
            </div>
        </div>
        
        <div class="game-instructions">
            <h3>Как играть:</h3>
            <p>Управляйте змейкой с помощью клавиш <kbd>WASD</kbd> или стрелок</p>
            <p>На мобильных устройствах используйте свайпы</p>
            <h3>Задача:</h3>
            <p>Поглощать двоичный код <span class="binary-0">0</span> и <span class="binary-1">1</span>, чтобы увеличивать длину змейки</p>
        </div>
    </div>
</section>

</body>
</html>

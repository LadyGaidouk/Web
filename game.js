const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const thanks = document.querySelector('.thanks');
const gameOver = document.getElementById('gameOver');

const gridSize = 20;
let canvasWidth, canvasHeight;
let snake = [{ x: 5, y: 5 }];
let target = { x: 10, y: 10, value: '0' };
let direction = { x: 0, y: 0 };
let gameActive = false;
let binaryITBureau = '010010010101010000100000010000100111010101110010011001010110000101110101';
let binaryIndex = 0;
let completed = false;

function resizeCanvas() {
    const thanksRect = thanks.getBoundingClientRect();
    canvasWidth = Math.min(thanksRect.width, 1280);
    canvasHeight = Math.min(thanksRect.height, 720);
    canvas.width = Math.floor(canvasWidth / gridSize) * gridSize;
    canvas.height = Math.floor(canvasHeight / gridSize) * gridSize;
    canvas.style.display = 'block';
}

function spawnTarget() {
    if (!completed && binaryIndex < binaryITBureau.length) {
        target.value = binaryITBureau[binaryIndex];
    } else {
        target.value = Math.random() < 0.5 ? '0' : '1';
    }
    target.x = Math.floor(Math.random() * (canvasWidth / gridSize));
    target.y = Math.floor(Math.random() * (canvasHeight / gridSize));
}

let mousePos = { x: snake[0].x * gridSize, y: snake[0].y * gridSize };
canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    mousePos = { x: e.clientX - rect.left, y: e.clientY - rect.top };
});
canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    const rect = canvas.getBoundingClientRect();
    mousePos = { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
});

setTimeout(() => {
    resizeCanvas();
    gameActive = true;
    spawnTarget();
    setInterval(gameLoop, 33.33); // 30 FPS
}, 2000);

function gameLoop() {
    if (!gameActive) return;

    const head = snake[0];
    const targetX = mousePos.x / gridSize;
    const targetY = mousePos.y / gridSize;
    if (Math.abs(targetX - head.x) > 0.1 || Math.abs(targetY - head.y) > 0.1) {
        direction.x = (targetX - head.x) * 0.1;
        direction.y = (targetY - head.y) * 0.1;
    }

    const newHead = { x: head.x + direction.x, y: head.y + direction.y };
    if (newHead.x < 0) newHead.x = 0;
    if (newHead.x > canvasWidth / gridSize - 1) newHead.x = canvasWidth / gridSize - 1;
    if (newHead.y < 0) newHead.y = 0;
    if (newHead.y > canvasHeight / gridSize - 1) newHead.y = canvasHeight / gridSize - 1;

    snake.unshift(newHead);
    snake.pop();

    if (Math.abs(newHead.x - target.x) < 0.5 && Math.abs(newHead.y - target.y) < 0.5) {
        snake.push(snake[snake.length - 1]);
        if (!completed && binaryIndex < binaryITBureau.length) {
            binaryIndex++;
            if (binaryIndex >= binaryITBureau.length) {
                completed = true;
                gameActive = false;
                gameOver.style.display = 'block';
            }
        }
        spawnTarget();
    }

    ctx.fillStyle = 'rgba(0, 1, 5, 0.5)';
    ctx.fillRect(0, 0, canvasWidth, canvasHeight);
    ctx.fillStyle = '#ff7eae';
    snake.forEach(segment => {
        ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
    });
    ctx.fillStyle = '#fff';
    ctx.font = '18px Montserrat';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(target.value, (target.x + 0.5) * gridSize, (target.y + 0.5) * gridSize);
}

window.copyCode = function() {
    navigator.clipboard.writeText('CODE2025').then(() => alert('Код скопирован!'));
};

window.continueGame = function() {
    gameOver.style.display = 'none';
    gameActive = true;
};

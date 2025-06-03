// Получаем элементы DOM
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.querySelector('#score span');
const speedDisplay = document.querySelector('#speed span');
const restartBtn = document.getElementById('restartBtn');

// Игровые константы
const gridSize = 20;
const colors = {
    snake: '#ff7eae',
    head: '#ff5c9d',
    target0: '#4a86e8',
    target1: '#e74c3c',
    bg: 'rgba(0, 1, 5, 0.3)',
    grid: 'rgba(255, 255, 255, 0.05)'
};

// Игровые переменные
let canvasWidth, canvasHeight;
let gridCellsX, gridCellsY;
let snake = [];
let target = { x: 0, y: 0, value: '0' };
let nextDirection = { x: 0, y: 0 };
let currentDirection = { x: 0, y: 0 };
let score = 0;
let moveInterval = 500; // Начальный интервал (мс)
let gameActive = false;
let lastMoveTime = 0;
let animationFrameId;
let snakePositions = new Set();
let touchStartX = 0;
let touchStartY = 0;

// Инициализация игры
function initGame() {
    // Рассчитываем размеры
    canvasWidth = canvas.offsetWidth;
    canvasHeight = canvas.offsetHeight;
    
    // Устанавливаем размеры canvas
    canvas.width = canvasWidth;
    canvas.height = canvasHeight;
    
    // Рассчитываем размеры сетки
    gridCellsX = Math.floor(canvasWidth / gridSize);
    gridCellsY = Math.floor(canvasHeight / gridSize);
    
    // Корректируем размеры под сетку
    canvas.width = gridCellsX * gridSize;
    canvas.height = gridCellsY * gridSize;
    
    // Сбрасываем игровые переменные
    resetGame();
}

// Сброс игры
function resetGame() {
    gameActive = true;
    score = 0;
    moveInterval = 500;
    
    // Обновляем отображение
    scoreDisplay.textContent = '0';
    speedDisplay.textContent = '1x';
    
    // Инициализация змейки
    const startX = Math.floor(gridCellsX / 4);
    const startY = Math.floor(gridCellsY / 2);
    snake = [
        { x: startX, y: startY },
        { x: startX - 1, y: startY },
        { x: startX - 2, y: startY }
    ];
    
    nextDirection = { x: 1, y: 0 };
    currentDirection = { x: 1, y: 0 };
    
    // Создаем первую цель
    spawnTarget();
    
    lastMoveTime = performance.now();
    
    // Запускаем игровой цикл
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
    }
    animationFrameId = requestAnimationFrame(gameLoop);
}

// Генерация цели
function spawnTarget() {
    // Обновляем позиции змейки
    updateSnakePositions();
    
    // Список свободных позиций
    const freePositions = [];
    for (let x = 0; x < gridCellsX; x++) {
        for (let y = 0; y < gridCellsY; y++) {
            const posKey = `${x},${y}`;
            if (!snakePositions.has(posKey)) {
                freePositions.push({ x, y });
            }
        }
    }
    
    // Если есть свободные позиции
    if (freePositions.length > 0) {
        const randomPos = freePositions[Math.floor(Math.random() * freePositions.length)];
        target = {
            x: randomPos.x,
            y: randomPos.y,
            value: Math.random() < 0.5 ? '0' : '1'
        };
    }
}

// Обновление позиций змейки
function updateSnakePositions() {
    snakePositions.clear();
    snake.forEach(segment => {
        snakePositions.add(`${segment.x},${segment.y}`);
    });
}

// Движение змейки
function moveSnake() {
    const head = { ...snake[0] };
    currentDirection = { ...nextDirection };
    
    // Обновление позиции головы
    head.x += currentDirection.x;
    head.y += currentDirection.y;
    
    // Телепортация через границы
    if (head.x < 0) head.x = gridCellsX - 1;
    if (head.x >= gridCellsX) head.x = 0;
    if (head.y < 0) head.y = gridCellsY - 1;
    if (head.y >= gridCellsY) head.y = 0;
    
    // Проверка столкновения с собой
    const headKey = `${head.x},${head.y}`;
    if (snakePositions.has(headKey)) {
        resetGame(); // Рестарт при столкновении
        return;
    }
    
    snake.unshift(head);
    updateSnakePositions();
    
    // Проверка сбора цели
    if (head.x === target.x && head.y === target.y) {
        score++;
        scoreDisplay.textContent = score;
        
        // Ускорение каждые 5 очков
        if (score % 5 === 0) {
            moveInterval = Math.max(80, moveInterval * 0.8);
            const speedFactor = (500 / moveInterval).toFixed(1);
            speedDisplay.textContent = `${speedFactor

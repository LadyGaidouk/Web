const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('score');
const startBtn = document.getElementById('startBtn');
const gridSize = 20;

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
let bufferCanvas;
let bufferCtx;

// Адаптация размера канваса
function resizeCanvas() {
  canvasWidth = Math.min(window.innerWidth * 0.9, 800);
  canvasHeight = Math.min(window.innerHeight * 0.7, 600);
  
  canvas.width = canvasWidth;
  canvas.height = canvasHeight;
  
  // Рассчитываем размеры сетки
  gridCellsX = Math.floor(canvasWidth / gridSize);
  gridCellsY = Math.floor(canvasHeight / gridSize);
  
  // Корректируем размеры канваса под сетку
  canvas.width = gridCellsX * gridSize;
  canvas.height = gridCellsY * gridSize;
  
  // Инициализируем буферный канвас
  if (bufferCanvas) {
    bufferCanvas.width = canvas.width;
    bufferCanvas.height = canvas.height;
    initBuffer();
  }
}

// Инициализация буферного канваса
function initBuffer() {
  // Статичный фон
  bufferCtx.fillStyle = '#f0f0f0';
  bufferCtx.fillRect(0, 0, bufferCanvas.width, bufferCanvas.height);
  
  // Сетка (опционально)
  bufferCtx.strokeStyle = '#e0e0e0';
  bufferCtx.lineWidth = 0.5;
  for (let x = 0; x <= gridCellsX; x++) {
    bufferCtx.beginPath();
    bufferCtx.moveTo(x * gridSize, 0);
    bufferCtx.lineTo(x * gridSize, canvas.height);
    bufferCtx.stroke();
  }
  for (let y = 0; y <= gridCellsY; y++) {
    bufferCtx.beginPath();
    bufferCtx.moveTo(0, y * gridSize);
    bufferCtx.lineTo(canvas.width, y * gridSize);
    bufferCtx.stroke();
  }
}

// Запуск игры
function startGame() {
  // Создаем буферный канвас
  bufferCanvas = document.createElement('canvas');
  bufferCtx = bufferCanvas.getContext('2d');
  
  resizeCanvas();
  gameActive = true;
  score = 0;
  moveInterval = 500;
  
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
  
  // Обновление отображения счета
  scoreDisplay.textContent = `Очки: ${score}`;
  
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

// Обновление позиций змейки в Set
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
    startGame(); // Рестарт при столкновении
    return;
  }
  
  snake.unshift(head);
  updateSnakePositions();
  
  // Проверка сбора цели
  if (head.x === target.x && head.y === target.y) {
    score++;
    scoreDisplay.textContent = `Очки: ${score}`;
    
    // Ускорение каждые 5 очков
    if (score % 5 === 0) {
      moveInterval = Math.max(80, moveInterval * 0.8);
    }
    
    spawnTarget();
  } else {
    snake.pop();
    updateSnakePositions();
  }
}

// Отрисовка игры
function drawGame() {
  // Отрисовка статичного фона из буфера
  ctx.drawImage(bufferCanvas, 0, 0);
  
  // Отрисовка змейки
  ctx.fillStyle = '#4a86e8';
  snake.forEach((segment, index) => {
    const isHead = index === 0;
    const size = isHead ? gridSize : gridSize - 2;
    const offset = isHead ? 0 : 1;
    
    ctx.fillRect(
      segment.x * gridSize + offset,
      segment.y * gridSize + offset,
      size,
      size
    );
    
    // Глаза для головы
    if (isHead) {
      ctx.fillStyle = '#000';
      const eyeSize = gridSize / 8;
      const eyeOffset = gridSize / 4;
      
      // Правый глаз
      ctx.beginPath();
      ctx.arc(
        segment.x * gridSize + gridSize - eyeOffset,
        segment.y * gridSize + eyeOffset,
        eyeSize,
        0,
        Math.PI * 2
      );
      ctx.fill();
      
      // Левый глаз
      ctx.beginPath();
      ctx.arc(
        segment.x * gridSize + eyeOffset,
        segment.y * gridSize + eyeOffset,
        eyeSize,
        0,
        Math.PI * 2
      );
      ctx.fill();
      
      ctx.fillStyle = '#4a86e8';
    }
  });
  
  // Отрисовка цели
  ctx.fillStyle = '#e74c3c';
  ctx.beginPath();
  ctx.arc(
    (target.x + 0.5) * gridSize,
    (target.y + 0.5) * gridSize,
    gridSize * 0.4,
    0,
    Math.PI * 2
  );
  ctx.fill();
  
  // Текст цели
  ctx.fillStyle = '#fff';
  ctx.font = `bold ${gridSize * 0.5}px Arial`;
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  ctx.fillText(
    target.value, 
    (target.x + 0.5) * gridSize, 
    (target.y + 0.5) * gridSize
  );
}

// Игровой цикл
function gameLoop(timestamp) {
  if (!gameActive) return;
  
  // Движение с фиксированным интервалом
  if (timestamp - lastMoveTime > moveInterval) {
    moveSnake();
    lastMoveTime = timestamp;
  }
  
  drawGame();
  animationFrameId = requestAnimationFrame(gameLoop);
}

// Управление клавиатурой
document.addEventListener('keydown', (e) => {
  if (!gameActive) return;
  
  switch (e.key) {
    case 'ArrowUp': case 'w': 
      if (currentDirection.y === 0) nextDirection = { x: 0, y: -1 }; 
      break;
    case 'ArrowDown': case 's': 
      if (currentDirection.y === 0) nextDirection = { x: 0, y: 1 }; 
      break;
    case 'ArrowLeft': case 'a': 
      if (currentDirection.x === 0) nextDirection = { x: -1, y: 0 }; 
      break;
    case 'ArrowRight': case 'd': 
      if (currentDirection.x === 0) nextDirection = { x: 1, y: 0 }; 
      break;
  }
});

// Управление касаниями (свайпы)
canvas.addEventListener('touchstart', (e) => {
  if (!gameActive) return;
  touchStartX = e.touches[0].clientX;
  touchStartY = e.touches[0].clientY;
  e.preventDefault();
});

canvas.addEventListener('touchmove', (e) => {
  if (!gameActive) return;
  e.preventDefault();
});

canvas.addEventListener('touchend', (e) => {
  if (!gameActive) return;
  
  const touchEndX = e.changedTouches[0].clientX;
  const touchEndY = e.changedTouches[0].clientY;
  
  const diffX = touchEndX - touchStartX;
  const diffY = touchEndY - touchStartY;
  
  // Определяем направление свайпа
  if (Math.abs(diffX) > Math.abs(diffY)) {
    // Горизонтальный свайп
    if (diffX > 30 && currentDirection.x === 0) {
      nextDirection = { x: 1, y: 0 }; // Вправо
    } else if (diffX < -30 && currentDirection.x === 0) {
      nextDirection = { x: -1, y: 0 }; // Влево
    }
  } else {
    // Вертикальный свайп
    if (diffY > 30 && currentDirection.y === 0) {
      nextDirection = { x: 0, y: 1 }; // Вниз
    } else if (diffY < -30 && currentDirection.y === 0) {
      nextDirection = { x: 0, y: -1 }; // Вверх
    }
  }
  
  e.preventDefault();
});

// Инициализация игры
window.addEventListener('load', () => {
  resizeCanvas();
  startBtn.addEventListener('click', startGame);
});

// Оптимизация ресайза
let resizeTimeout;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(() => {
    resizeCanvas();
    if (gameActive) drawGame();
  }, 100);
});

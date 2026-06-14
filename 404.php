<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Halaman Tidak Ditemukan</title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpolygon points='60,6 107,33 107,87 60,114 13,87 13,33' fill='%23060608' stroke='%2300ff88' stroke-width='4'/%3E%3Ccircle cx='60' cy='60' r='20' stroke='%2300ff88' stroke-width='3' fill='none'/%3E%3Cpolygon points='60,42 75,53 69,71 51,71 45,53' fill='%2300ff88'/%3E%3Cpath d='M60 42 L60 10 M75 53 L104 39 M69 71 L92 92 M51 71 L28 92 M45 53 L16 39' stroke='%2300ff88' stroke-width='3' stroke-linecap='round'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Anton&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --black:  #060608;
      --dark:   #0c0d10;
      --green:  #00ff88;
      --white:  #eceef2;
      --gray:   #6b7080;
    }
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html, body {
      width: 100%; height: 100%;
      background-color: var(--black);
      color: var(--white);
      font-family: 'Plus Jakarta Sans', sans-serif;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
      opacity: 0.03;
      pointer-events: none;
      z-index: 90;
    }
    
    body::after {
      content: '';
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(0,255,136,0.018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,255,136,0.018) 1px, transparent 1px);
      background-size: 80px 80px;
      pointer-events: none;
      z-index: 2;
    }
   
    .scanlines {
      position: fixed; inset: 0;
      background: repeating-linear-gradient(
        0deg,
        transparent, transparent 2px,
        rgba(0,0,0,0.03) 2px, rgba(0,0,0,0.03) 4px
      );
      pointer-events: none; z-index: 91;
    }

    #site-preloader {
      position: fixed; inset: 0;
      background: var(--black);
      z-index: 9999;
      display: flex; align-items: center; justify-content: center;
      flex-direction: column; gap: 20px;
      transition: opacity 0.6s ease, visibility 0.6s ease;
    }
    #site-preloader.preloader-hidden { opacity: 0; visibility: hidden; }
    .hexagon-spinner {
      width: 56px; height: 56px;
      animation: spin-preloader 2s linear infinite;
    }
    .hexagon-spinner polygon {
      fill: none;
      stroke: var(--green);
      stroke-width: 2.5;
      stroke-dasharray: 160;
      stroke-dashoffset: 160;
      animation: drawHexagon 2s ease-in-out infinite;
      stroke-linecap: round;
      filter: drop-shadow(0 0 10px var(--green));
    }
    .preloader-label {
      font-family: monospace;
      font-size: 0.7rem;
      letter-spacing: 3px;
      color: var(--gray);
      text-transform: uppercase;
      animation: blink 1.2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100% { opacity: 0.4; } 50% { opacity: 1; } }
    @keyframes spin-preloader { to { transform: rotate(360deg); } }
    @keyframes drawHexagon {
      0%,100% { stroke-dashoffset: 160; }
      50%      { stroke-dashoffset: 0; }
    }
    
    #cursor-glow {
      position: fixed;
      width: 360px; height: 360px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(0,255,136,0.045) 0%, transparent 70%);
      pointer-events: none; z-index: 1;
      transform: translate(-50%,-50%);
      filter: blur(8px);
      transition: left 0.06s linear, top 0.06s linear;
    }
    
    .ambient-glow {
      position: fixed;
      bottom: -25vh; left: 50%;
      transform: translateX(-50%);
      width: 80vw; height: 60vh;
      background: radial-gradient(ellipse at center, rgba(0,255,136,0.14) 0%, transparent 68%);
      pointer-events: none; z-index: 4;
      filter: blur(24px);
      animation: glow-breathe 4s ease-in-out infinite;
    }
    @keyframes glow-breathe {
      0%,100% { opacity: 0.8; transform: translateX(-50%) scaleX(1); }
      50%      { opacity: 1;   transform: translateX(-50%) scaleX(1.06); }
    }

    .container {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 80px 40px;
      z-index: 10;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      gap: 36px;
    }
  
    .badge    { animation: fadeUp 0.7s cubic-bezier(0.25,0.8,0.25,1) 1.1s both; }
    .headline { animation: fadeUp 0.7s cubic-bezier(0.25,0.8,0.25,1) 1.28s both; }
    .btn-home { animation: fadeUp 0.7s cubic-bezier(0.25,0.8,0.25,1) 1.46s both; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .badge {
      display: flex; align-items: center; gap: 8px;
      font-family: monospace;
      font-size: 0.75rem;
      letter-spacing: 2px;
      color: var(--gray);
      text-transform: uppercase;
      margin-top: 20px;
    }
    .badge-dot {
      display: inline-block;
      width: 6px; height: 6px;
      background-color: var(--green);
      border-radius: 50%;
      box-shadow: 0 0 8px var(--green), 0 0 22px rgba(0,255,136,0.5);
      animation: pulse-dot 2.2s ease-in-out infinite;
      flex-shrink: 0;
    }
    @keyframes pulse-dot {
      0%,100% { box-shadow: 0 0 6px var(--green), 0 0 18px rgba(0,255,136,0.4); transform: scale(1); }
      50%      { box-shadow: 0 0 12px var(--green), 0 0 30px rgba(0,255,136,0.7); transform: scale(1.15); }
    }

    .headline {
      font-size: clamp(2rem, 5vw, 3.5rem);
      font-weight: 800;
      line-height: 1.15;
      max-width: 780px;
      letter-spacing: -1px;
    }

    .btn-home {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      background-color: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      color: var(--white);
      text-decoration: none;
      padding: 16px 32px;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.25,0.8,0.25,1);
      width: max-content;
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
    }
    
    .btn-home::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(105deg, transparent 40%, rgba(0,255,136,0.06) 50%, transparent 60%);
      transform: translateX(-100%);
      transition: transform 0.5s ease;
    }
    .btn-home:hover::after { transform: translateX(100%); }
    .btn-home svg {
      width: 16px; height: 16px;
      fill: none; stroke: currentColor;
      stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
      transition: transform 0.3s ease;
    }
    .btn-home:hover {
      background-color: rgba(0,255,136,0.07);
      border-color: rgba(0,255,136,0.38);
      color: var(--green);
      box-shadow: 0 8px 28px rgba(0,255,136,0.1), 0 0 0 1px rgba(0,255,136,0.12);
      transform: translateY(-2px);
    }
    .btn-home:hover svg { transform: translate(2px,-2px); }
    .btn-home:active { transform: translateY(0); }
    
    .graphic-container {
      position: fixed;
      bottom: 0; left: 0;
      width: 100%; height: 55vh;
      z-index: 3;
      pointer-events: none; user-select: none;
    }
    .text-404 {
      font-family: 'Orbitron', sans-serif;
      font-size: 32vw;
      font-weight: 900;
      line-height: 1;
      letter-spacing: -0.8vw;
      text-align: center;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      will-change: transform;
    }
       
    .layer-top {
      color: rgba(0,255,136,0.055);
      bottom: 12vw; z-index: 1;
      filter: blur(1px);
    }
    .layer-mid {
      color: rgba(0,255,136,0.2);
      bottom: 2vw; z-index: 2;
    }
    .layer-bot {
      color: rgba(0,255,136,0.62);
      bottom: -8vw; z-index: 3;
      filter:
        drop-shadow(0 0 40px rgba(0,255,136,0.35))
        drop-shadow(0 0 100px rgba(0,255,136,0.15));
    }
   
    @media (max-width: 768px) {
      .container  { padding: 40px 24px; gap: 24px; }
      .badge      { margin-top: 10px; }
      .headline   { font-size: 2.2rem; }
      .btn-home   { padding: 14px 24px; }
      .text-404   { font-size: 36vw; letter-spacing: -1vw; }
      .layer-top  { bottom: 15vw; }
      .layer-mid  { bottom: 4vw; }
      .layer-bot  { bottom: -7vw; }
      #cursor-glow { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
    }
  </style>
</head>
<body>
  <div id="site-preloader">
    <svg class="hexagon-spinner" viewBox="0 0 60 60">
      <polygon points="30,4 52.5,17 52.5,43 30,56 7.5,43 7.5,17"/>
    </svg>
    <p class="preloader-label">Loading&hellip;</p>
  </div>
  <div class="scanlines"></div>
  <div id="cursor-glow"></div>
  <div class="ambient-glow"></div>
  <div class="container">
    <div class="badge">
      <span class="badge-dot"></span>
      404 Error. Page Not Found
    </div>
    <h1 class="headline">
      If you're reading this, something has gone terribly, terribly wrong.
    </h1>
    <a href="index.php" class="btn-home">
      Return home
      <svg viewBox="0 0 24 24">
        <line x1="7" y1="17" x2="17" y2="7"/>
        <polyline points="7 7 17 7 17 17"/>
      </svg>
    </a>
  </div>
  <div class="graphic-container">
    <div class="text-404 layer-top"  id="l1">404</div>
    <div class="text-404 layer-mid"  id="l2">404</div>
    <div class="text-404 layer-bot"  id="l3">404</div>
  </div>
  <script>
    
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('site-preloader')?.classList.add('preloader-hidden');
      }, 1000);
    });
    
    const cursorGlow = document.getElementById('cursor-glow');
    if (window.matchMedia('(pointer: fine)').matches) {
      document.addEventListener('mousemove', e => {
        cursorGlow.style.left = e.clientX + 'px';
        cursorGlow.style.top  = e.clientY + 'px';
      });
    }
  
    const l1 = document.getElementById('l1');
    const l2 = document.getElementById('l2');
    const l3 = document.getElementById('l3');
    const cx = () => window.innerWidth  / 2;
    const cy = () => window.innerHeight / 2;
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      document.addEventListener('mousemove', e => {
        const dx = (e.clientX - cx()) / cx();
        const dy = (e.clientY - cy()) / cy();
        l1.style.transform = `translateX(calc(-50% + ${dx * 20}px)) translateY(${dy * 12}px)`;
        l2.style.transform = `translateX(calc(-50% + ${dx * 11}px)) translateY(${dy * 7}px)`;
        l3.style.transform = `translateX(calc(-50% + ${dx *  5}px)) translateY(${dy * 3}px)`;
      });
    }
  </script>
</body>
</html>
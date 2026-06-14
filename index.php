<?php
// ── AUTENTIKASI: Hanya pelanggan yang sudah login yang bisa akses halaman ini ──
require_once __DIR__ . '/config.php';
startSecureSession();
if (!isLoggedIn()) {
    header('Location: auth/login.php');
    exit;
}

// Ambil data foto profil terbaru dari database berdasarkan ID pelanggan di session
$user_foto = null;
if (isset($_SESSION['user_id'])) {
    try {
        $db = getDB();
        $stmt_user = $db->prepare("SELECT FOTO_PROFIL FROM Pelanggan WHERE ID_PELANGGAN = ?");
        $stmt_user->execute([$_SESSION['user_id']]);
        $user_foto = $stmt_user->fetchColumn();
    } catch (PDOException $e) {
        // Gagal mengambil data, fallback otomatis ke inisial huruf nanti
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow">
<title>MiniFut — Premium Mini Soccer Arena Yogyakarta</title>
<meta name="description" content="MiniFut is adalah arena mini soccer outdoor premium di Yogyakarta. Rumput sintetis berkualitas tinggi, pencahayaan LED penuh, and facilities terlengkap. Buka setiap hari 08.00–24.00 WIB.">

<!-- Open Graph / Social Media Preview -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://minifut.id/">
<meta property="og:title" content="MiniFut — Premium Mini Soccer Arena Yogyakarta">
<meta property="og:description" content="Arena mini soccer outdoor premium di Yogyakarta. Rumput sintetis berkualitas, LED penuh, fasilitas lengkap. Booking sekarang!">
<meta property="og:image" content="https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=1200&q=80">
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="MiniFut — Premium Mini Soccer Arena Yogyakarta">
<meta property="twitter:description" content="Arena mini soccer outdoor premium di Yogyakarta. Booking sekarang!">
<meta property="twitter:image" content="https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=1200&q=80">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpolygon points='60,6 107,33 107,87 60,114 13,87 13,33' fill='%23060608' stroke='%2300ff88' stroke-width='4'/%3E%3Ccircle cx='60' cy='60' r='20' stroke='%2300ff88' stroke-width='3' fill='none'/%3E%3Cpolygon points='60,42 75,53 69,71 51,71 45,53' fill='%2300ff88'/%3E%3Cpath d='M60 42 L60 10 M75 53 L104 39 M69 71 L92 92 M51 71 L28 92 M45 53 L16 39' stroke='%2300ff88' stroke-width='3' stroke-linecap='round'/%3E%3C/svg%3E">

<!-- Google Fonts Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Anton&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>

:root {
  --black:   #060608; --dark:    #0c0d10; --card:    #111318; --card2:   #161820;
  --border:  rgba(255,255,255,0.06); --border2: rgba(255,255,255,0.1);
  --green:   #00ff88; --green2:  var(--green); --glow:    rgba(0,255,136,0.18); --glow-sm: rgba(0,255,136,0.07);
  --gray:    #6b7080; --gray2:   #9aa0b0; --white:   #eceef2; --red:     #ff3b5c;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{background:var(--black);color:var(--white);font-family:'Plus Jakarta Sans',sans-serif;overflow-x:hidden;min-height:100vh;}

#noise{position:fixed;inset:0;opacity:.018;pointer-events:none;z-index:8000;
  background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");}

nav {
  position: fixed;
  top: 24px;
  left: 50%;
  transform: translateX(-50%);
  width: calc(100% - 128px);
  max-width: 1400px;
  height: 70px;
  background: rgba(6, 6, 8, 0.4);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 16px;
  padding: 0 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 1001;
  transition: all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
}
nav.stuck {
  background: rgba(6, 6, 8, 0.85);
  backdrop-filter: blur(28px) saturate(180%);
}

nav.shrunk {
  top: 36px;
  width: calc(100% - 160px);
  max-width: 780px;
  height: 54px;
  border-radius: 100px;
  background: rgba(12, 13, 16, 0.75);
  border: 1px solid rgba(0, 255, 136, 0.2);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(0, 255, 136, 0.05);
  padding: 0 24px;
}

@keyframes logo-wiggle {
  0% { transform: rotate(0deg); }
  25% { transform: rotate(-1deg); }
  75% { transform: rotate(1deg); }
  100% { transform: rotate(0deg); }
}

.logo {
  font-family: 'Orbitron', monospace;
  font-size: 1.5rem;
  font-weight: 900;
  color: var(--green);
  letter-spacing: 5px;
  text-decoration: none;
  transition: all 0.3s ease;
  display: inline-block;
  transform-origin: center;
}
.logo em { color: var(--white); font-style: normal; }
.logo:hover { 
  animation: logo-wiggle 0.25s ease-in-out 1;
  text-shadow: 0 0 15px var(--green);
}

nav.shrunk .logo {
  font-size: 1.15rem;
  letter-spacing: 3px;
}

/* Revamped Navbar Elements */
.nav-left {
  display: flex;
  align-items: center;
}
.nav-slash {
  margin: 0 16px;
  color: rgba(255, 255, 255, 0.15);
  font-size: 1.1rem;
  font-weight: 300;
  user-select: none;
}
.nav-booking-link {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.72rem;
  font-weight: 700;
  color: rgba(255, 255, 255, 0.6);
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  position: relative;
  display: inline-block;
  overflow: hidden;
  height: 24px;
  line-height: 24px;
  vertical-align: middle;
}
.nav-booking-orig, .nav-booking-clone {
  display: inline-block;
  transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
}
.nav-booking-clone {
  position: absolute;
  left: 0;
  top: 100%;
  color: var(--green);
}
.nav-booking-link:hover {
  text-shadow: 0 0 8px rgba(0, 255, 136, 0.4);
}
.nav-booking-link:hover .nav-booking-orig {
  transform: translateY(-100%);
}
.nav-booking-link:hover .nav-booking-clone {
  transform: translateY(-100%);
}

.nav-instagram-link, .nav-whatsapp-link, .nav-tiktok-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
  color: rgba(255, 255, 255, 0.6);
  font-size: 1.1rem;
  text-decoration: none;
  transition: all 0.3s ease;
  flex-shrink: 0;
}
.nav-instagram-link {
  margin-left: 14px;
}
.nav-whatsapp-link, .nav-tiktok-link {
  margin-left: 8px;
}
.nav-instagram-link:hover {
  background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
  border-color: transparent;
  color: var(--white);
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 5px 15px rgba(220, 39, 67, 0.4);
}
.nav-whatsapp-link:hover {
  background: linear-gradient(45deg, #25D366 0%, #128C7E 100%);
  border-color: transparent;
  color: var(--white);
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
}
.nav-tiktok-link:hover {
  background: #000000;
  border-color: transparent;
  color: var(--white);
  transform: translateY(-3px) scale(1.05);
  box-shadow: -3px -3px 8px rgba(0, 242, 254, 0.6), 3px 3px 8px rgba(254, 9, 121, 0.6);
}
nav.shrunk .nav-instagram-link, nav.shrunk .nav-whatsapp-link, nav.shrunk .nav-tiktok-link {
  width: 32px;
  height: 32px;
  font-size: 0.95rem;
}
nav.shrunk .nav-instagram-link {
  margin-left: 10px;
}
nav.shrunk .nav-whatsapp-link, nav.shrunk .nav-tiktok-link {
  margin-left: 6px;
}
nav.shrunk .nav-booking-link {
  font-size: 0.68rem;
  letter-spacing: 1.8px;
}
nav.shrunk .nav-slash {
  margin: 0 12px;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 12px;
}
.nav-btn-dark {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 30px;
  padding: 8px 18px;
  color: rgba(255, 255, 255, 0.8);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  text-decoration: none;
  transition: all 0.3s ease;
}
.nav-btn-dark:hover {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.2);
  color: var(--white);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
}

.nav-btn-glow {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #00ff88 0%, #00b359 100%);
  border: none;
  border-radius: 30px;
  padding: 8px 20px;
  color: var(--black);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  text-decoration: none;
  box-shadow: 0 0 15px rgba(0, 255, 136, 0.35);
  transition: all 0.3s ease;
}
.nav-btn-glow:hover {
  transform: translateY(-1.5px) scale(1.02);
  box-shadow: 0 0 25px rgba(0, 255, 136, 0.6);
}

nav.shrunk .nav-btn-dark, nav.shrunk .nav-btn-glow {
  padding: 6px 14px;
  font-size: 0.68rem;
}

.nav-user-wrapper {
  display: flex;
  align-items: center;
  gap: 12px;
}
.nav-avatar-link {
  display: block;
  flex-shrink: 0;
  text-decoration: none;
}
.nav-avatar-link img, .nav-avatar-placeholder {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--green);
  box-shadow: 0 0 10px var(--glow);
  transition: all 0.3s ease;
}
.nav-avatar-placeholder {
  background: rgba(0, 255, 136, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--green);
}
.nav-avatar-link:hover img, .nav-avatar-link:hover .nav-avatar-placeholder {
  transform: scale(1.08);
  box-shadow: 0 0 16px var(--green);
}
.nav-btn-logout {
  background: rgba(255, 59, 92, 0.06);
  border: 1px solid rgba(255, 59, 92, 0.25);
  color: #ff7096;
  padding: 6px 14px;
  border-radius: 30px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  text-decoration: none;
  transition: all 0.3s ease;
}
.nav-btn-logout:hover {
  color: var(--white) !important;
  background: var(--red) !important;
  box-shadow: 0 0 16px rgba(255, 59, 92, 0.4);
  transform: translateY(-1px);
}
nav.shrunk .nav-avatar-link img, nav.shrunk .nav-avatar-placeholder {
  width: 30px;
  height: 30px;
}
nav.shrunk .nav-btn-logout {
  padding: 4px 10px;
  font-size: 0.62rem;
}

@media (max-width: 767px) {
  .nav-slash, .nav-booking-link, .nav-instagram-link, .nav-whatsapp-link, .nav-tiktok-link {
    display: none;
  }
  .nav-btn-dark {
    display: none;
  }
  nav { 
    padding: 0 20px; 
    width: calc(100% - 32px);
    top: 16px;
    height: 64px;
    border-radius: 16px;
  }
  .nav-btn-glow {
    padding: 6px 14px;
    font-size: 0.68rem;
  }
  
  nav.shrunk {
    top: 14px;
    width: calc(100% - 64px);
    max-width: none;
    height: 54px;
    border-radius: 12px;
    padding: 0 16px;
    background: rgba(12, 13, 16, 0.85);
    border: 1px solid rgba(0, 255, 136, 0.25);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 15px rgba(0, 255, 136, 0.05);
  }
  nav.shrunk .logo {
    font-size: 1.05rem;
    letter-spacing: 2px;
  }
}

.container{max-width:1200px;margin:0 auto;padding:0 64px;}
@media (max-width: 1024px) { .container{padding:0 24px;} }
.sec-label{font-family:'Plus Jakarta Sans',sans-serif;font-size:.68rem;font-weight:700;letter-spacing:5px;text-transform:uppercase;color:var(--green);margin-bottom:10px;}
.sec-title{font-family:'Anton',sans-serif;font-size:clamp(2.2rem,4.5vw,3.8rem);font-weight:400;line-height:0.95;color:var(--white);text-transform:uppercase;letter-spacing:0.5px;}
.sec-sub{font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;color:var(--gray2);margin-top:12px;line-height:1.75;}

.reveal{opacity:0;}

/* Hero Section */
.hero{position:relative;height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden;}

/* Z-INDEX LAYER UNTUK HERO CANVAS */
#shader-canvas{position:absolute;inset:0;width:100%!important;height:100%!important;z-index:0;}
#three-canvas{position:absolute;inset:0;width:100%!important;height:100%!important;z-index:4;opacity:1;}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(6,6,8,.85) 0%,rgba(6,6,8,.3) 60%,rgba(6,6,8,.7) 100%);z-index:2;pointer-events:none;}
.hero-grid{display:none;}

/* ══ HERO CONTENT — Heading Premium di Atas ══ */
.hero-content {
  position: absolute;
  top: 36vh;
  left: 0;
  right: 0;
  z-index: 5;
  pointer-events: none;
  opacity: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 0 24px;
}

/* Halo gelap lembut di belakang heading agar tetap kontras di atas canvas 3D */
.hero-content::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: min(920px, 130vw);
  height: 320px;
  background: radial-gradient(ellipse, rgba(6,6,8,.55) 0%, transparent 72%);
  z-index: -1;
  pointer-events: none;
}

/* Location label */
.hero-location {
  position: absolute;
  bottom: 40px;
  left: 40px;
  z-index: 20;
  display: inline-flex;
  align-items: flex-start;
  gap: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.66rem;
  font-weight: 700;
  letter-spacing: 4px;
  text-transform: uppercase;
  color: var(--green);
  opacity: 0; /* GSAP */
  max-width: 180px;
  line-height: 1.6;
}
.hero-location-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--green);
  flex-shrink: 0;
  margin-top: 2px;
  animation: heroDotPulse 2.4s ease-in-out infinite;
}
@keyframes heroDotPulse {
  0%, 100% { box-shadow: 0 0 6px rgba(0,255,136,.45); transform: scale(1); }
  50%      { box-shadow: 0 0 16px rgba(0,255,136,.85), 0 0 28px rgba(0,255,136,.2); transform: scale(1.3); }
}
.hero-location-short { display: none; }

.hero-title {
  font-family: 'Anton', sans-serif;
  font-weight: 400;
  line-height: 0.9;
  text-transform: uppercase;
  margin: 0;
  display: flex;
  flex-direction: row;
  align-items: baseline;
  gap: 16px;
  flex-wrap: wrap;
  justify-content: center;
  perspective: 700px;
}
.hero-line-wrap {
  display: block;
  overflow: hidden;
  line-height: 0.98;
  padding: 0 2px;
}
.hero-line {
  display: block;
  font-size: clamp(2rem, 5vw, 4.2rem);
  letter-spacing: -1px;
  will-change: transform, filter;
}
.hero-line-1 {
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255,255,255,0.22);
  animation: heroOutlinePulse 5s ease-in-out infinite;
}
@keyframes heroOutlinePulse {
  0%, 100% { -webkit-text-stroke-color: rgba(255,255,255,.18); }
  50%      { -webkit-text-stroke-color: rgba(0,255,136,.4); }
}
.hero-line-2 {
  color: var(--white);
  text-shadow: 0 4px 30px rgba(0,0,0,.35);
}
.hero-line-3 {
  color: transparent;
  -webkit-text-fill-color: transparent;
  background: linear-gradient(110deg, var(--green) 25%, #d4ffec 50%, var(--green) 75%);
  background-size: 220% 100%;
  -webkit-background-clip: text;
  background-clip: text;
  animation: heroShine 6s linear infinite;
}
@keyframes heroShine {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
.hero-title-accent {
  color: var(--green);
  display: inline-block;
  transform-origin: center;
  animation: heroAccentPulse 2.4s ease-in-out infinite;
}
@keyframes heroAccentPulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50%      { transform: scale(1.4); opacity: .65; }
}

/* Garis aksen hijau di bawah judul */
.hero-underline {
  width: clamp(70px, 9vw, 130px);
  height: 3px;
  margin-top: 0;
  border-radius: 2px;
  background: linear-gradient(90deg, transparent, var(--green), transparent);
  transform: scaleX(0);
  opacity: 0; /* GSAP */
  animation: heroUnderlinePulse 3s ease-in-out infinite;
}
@keyframes heroUnderlinePulse {
  0%, 100% { box-shadow: 0 0 10px rgba(0,255,136,.3); }
  50%      { box-shadow: 0 0 22px rgba(0,255,136,.6); }
}

@media (max-width: 1024px) {
  .hero-content { top: 38vh; }
  .hero-content::before { height: 260px; }
  .hero-title {
    gap: 12px;
  }
  .hero-line {
    font-size: clamp(1.8rem, 6vw, 3rem);
  }
  .hero-eyebrow { font-size: 0.6rem; letter-spacing: 3px; padding: 7px 16px; }
}
@media (max-width: 600px) {
  .hero-title {
    gap: 8px;
    flex-direction: row;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
  }
  .hero-line-wrap:nth-child(2) {
    display: none;
  }
  .hero-location {
    bottom: 24px;
    left: 20px;
    max-width: 140px;
    font-size: 0.55rem;
    letter-spacing: 2.5px;
    gap: 8px;
    padding: 0;
    border: none;
    background: transparent;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    align-items: flex-start;
    text-align: left;
    line-height: 1.6;
  }
  .hero-location-full { display: none; }
  .hero-location-short { display: inline; }
  .hero-line { font-size: clamp(1.8rem, 9vw, 2.8rem); }
  .hero-content::before { width: 100vw; height: 220px; }
  .hero-content { top: 42vh; }
  .hero-underline { margin-top: 0; }
}

.btn-p { clip-path:polygon(0 0, 100% 0, calc(100% - 18px) 100%, 0 100%); padding-right:52px;}
.btn-s { 
  clip-path:polygon(18px 0, 100% 0, 100% 100%, 0 100%); 
  padding-left:52px; 
  margin-left:-4px; 
  border-left:none; 
  position:relative; 
}
.btn-s::before { 
  content:''; position:absolute; top:-1px; left:0; bottom:-1px; width:20px; 
  background:rgba(0,255,136,.35); 
  clip-path:polygon(18px 0, 19.5px 0, 1.5px 100%, 0 100%); 
  transition:background .3s; z-index:1; 
}
.btn-s:hover::before { background:var(--green); }

.btn-p{font-family:'Plus Jakarta Sans',sans-serif;font-size:.88rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--black);background:var(--green);border:none;padding:17px 44px;text-decoration:none;clip-path:polygon(10px 0%,100% 0%,calc(100% - 10px) 100%,0 100%);transition:all .3s;position:relative;overflow:hidden;}
.btn-p::after{content:'';position:absolute;inset:0;background:rgba(255,255,255,.18);transform:translateX(-100%);transition:.3s;}
.btn-p:hover::after{transform:translateX(0);}
.btn-s{font-family:'Plus Jakarta Sans',sans-serif;font-size:.88rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--green);background:transparent;border:1px solid rgba(0,255,136,.35);padding:17px 44px;text-decoration:none;transition:all .3s;}
.btn-s:hover{border-color:var(--green);background:var(--glow-sm);}

/* Soften edge button for main bottom CTA */
.btn-soft {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: .88rem;
  font-weight: 700;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: var(--black);
  background: var(--green);
  border: none;
  padding: 17px 44px;
  text-decoration: none;
  border-radius: 12px;
  transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 255, 136, 0.3);
}
.btn-soft::after {
  content: ''; position: absolute; inset: 0; background: rgba(255, 255, 255, 0.22); transform: translateX(-100%); transition: transform 0.3s ease;
}
.btn-soft:hover::after {
  transform: translateX(0);
}
.btn-soft:hover {
  box-shadow: 0 8px 25px rgba(0, 255, 136, 0.5);
  transform: translateY(-3px);
}

@media (max-width: 1024px) {
  .btn-p, .btn-s, .btn-soft { padding: 12px 24px; font-size: 0.75rem; }
  .btn-p { padding-right: 36px; clip-path:polygon(0 0, 100% 0, calc(100% - 14px) 100%, 0 100%); }
  .btn-s { padding-left: 36px; clip-path:polygon(14px 0, 100% 0, 100% 100%, 0 100%); margin-left: -4px; }
  .btn-s::before { width: 16px; clip-path:polygon(14px 0, 15.5px 0, 1.5px 100%, 0 100%); }
}

.fields-sec {
  padding: 120px 0;
  background: 
    repeating-linear-gradient(
      0deg,
      transparent,
      transparent 38px,
      rgba(0,255,136,0.028) 38px,
      rgba(0,255,136,0.028) 39px
    ),
    repeating-linear-gradient(
      90deg,
      transparent,
      transparent 38px,
      rgba(0,255,136,0.018) 38px,
      rgba(0,255,136,0.018) 39px
    ),
    var(--black);
  position: relative;
  overflow: hidden;
}
.fields-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:56px;}
.fields-title-wrap {
  display: inline-flex;
  align-items: baseline;
  flex-wrap: wrap;
  gap: 12px;
  margin: 0;
}
.fields-bg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(2.2rem, 4.5vw, 3.8rem);
  font-weight: 400;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.22);
  text-transform: uppercase;
  letter-spacing: 2px;
  line-height: 1;
  transition: color 0.3s ease, -webkit-text-stroke 0.3s ease;
  cursor: pointer;
}
.fields-bg-text:hover {
  color: var(--white);
  -webkit-text-stroke: 1.5px transparent;
}
.facilities-bg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(2.2rem, 4.5vw, 3.8rem);
  font-weight: 400;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.22);
  text-transform: uppercase;
  letter-spacing: 12px;
  line-height: 1;
  transition: color 0.3s ease, -webkit-text-stroke 0.3s ease;
  cursor: pointer;
  display: inline-flex;
  align-items: baseline;
}
.facilities-bg-text:hover {
  color: var(--white);
  -webkit-text-stroke: 1.5px transparent;
}
.fields-fg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(2.2rem, 4.5vw, 3.8rem);
  font-weight: 400;
  color: var(--white);
  -webkit-text-stroke: 1.5px var(--white);
  text-transform: uppercase;
  letter-spacing: 1px;
  line-height: 1;
  display: inline-flex;
  align-items: baseline;
}
.fields-green-dot {
  display: inline-block;
  width: clamp(10px, 1.8vw, 14px);
  height: clamp(10px, 1.8vw, 14px);
  background-color: var(--green);
  margin-left: 6px;
  vertical-align: baseline;
  box-shadow: 0 0 12px var(--glow);
}

.title-style-swapped .fields-bg-text {
  color: var(--white);
  -webkit-text-stroke: 1.5px var(--white);
}
.title-style-swapped .fields-fg-text {
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.22);
}
.title-style-italic .fields-bg-text,
.title-style-italic .fields-fg-text {
  font-style: italic;
}
.title-style-mixed-swapped .fields-bg-text {
  color: var(--white);
  -webkit-text-stroke: 1.5px var(--white);
}
.title-style-mixed-swapped .fields-fg-text {
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.22);
  font-style: italic;
}
.title-style-mixed-bg-italic .fields-bg-text {
  font-style: italic;
}
.title-style-all-outline .fields-bg-text,
.title-style-all-outline .fields-fg-text {
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.22);
}

@media(max-width:768px){.fields-head{flex-direction:column;align-items:flex-start;gap:20px;}}
.fields-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:3px;}
.f-card{background:var(--card);border:1px solid var(--border);position:relative;overflow:hidden;transition:transform .4s,border-color .3s;transform-style:preserve-3d;}
.f-card:hover{border-color:rgba(0,255,136,.35);}
.f-card-img{height:270px;overflow:hidden;position:relative;}
.f-card-img img{width:100%;height:100%;object-fit:cover;filter:brightness(.65) saturate(.7);transition:transform .65s ease,filter .65s ease;}
.f-card:hover .f-card-img img{transform:scale(1.08);filter:brightness(.55) saturate(1.1);}
.f-card-img-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(6,6,8,.95) 0%,transparent 55%);}
.f-card-badge-wrap{position:absolute;bottom:18px;left:20px;z-index:1;}
.f-badge{display:inline-block;font-family:'Plus Jakarta Sans',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--green);border:1px solid rgba(0,255,136,.4);padding:3px 10px;background:rgba(0,255,136,.06);margin-bottom:6px;}
.f-card-title{font-family:'Orbitron',sans-serif;font-size:1.4rem;font-weight:700;color:var(--white);letter-spacing:1px;line-height:1;}
.f-card-body{padding:22px;}
.f-tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:18px;}
.f-card-body 
.f-tag{font-family:'Plus Jakarta Sans',sans-serif;font-size:.65rem;letter-spacing:1px;color:var(--gray2);background:rgba(255,255,255,.04);border:1px solid var(--border);padding:3px 9px;}
.f-price-row{display:flex;justify-content:space-between;align-items:center;}
.f-price-val{font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:700;color:var(--green);letter-spacing:1px;}
.f-price-unit{font-family:'Plus Jakarta Sans',sans-serif;font-size:.72rem;color:var(--gray);margin-left:4px;}
.f-book-btn{font-family:'Plus Jakarta Sans',sans-serif;font-size:.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--green);background:transparent;border:1px solid rgba(0,255,136,.35);padding:8px 18px;text-decoration:none;transition:all .3s;}
.f-book-btn:hover{background:var(--green);color:var(--black);}

/* GALLERY */
.gallery-sec{padding:100px 0;background:var(--dark);overflow:hidden;}
.gallery-head{text-align:center;margin-bottom:52px;}
.gallery-title-wrap {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}
.gallery-bg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(4.5rem, 9vw, 7rem);
  font-weight: 400;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.18);
  text-transform: uppercase;
  letter-spacing: 4px;
  line-height: 0.9;
  z-index: 1;
  pointer-events: none;
}
.gallery-fg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(3rem, 6vw, 4.8rem);
  font-weight: 400;
  color: var(--white);
  -webkit-text-stroke: 1.5px var(--white);
  text-transform: uppercase;
  letter-spacing: 2px;
  line-height: 0.9;
  z-index: 2;
  margin: 0;
  margin-top: -8px;
  display: inline-flex;
  align-items: baseline;
}
.gallery-green-dot {
  display: inline-block;
  width: clamp(12px, 2vw, 18px);
  height: clamp(12px, 2vw, 18px);
  background-color: var(--green);
  margin-left: 8px;
  vertical-align: baseline;
  box-shadow: 0 0 15px var(--glow);
}

.pricing-title-wrap {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}
.pricing-fg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(3rem, 6vw, 4.8rem);
  font-weight: 400;
  color: var(--white);
  -webkit-text-stroke: 1.5px var(--white);
  text-transform: uppercase;
  letter-spacing: 2px;
  line-height: 0.9;
  z-index: 2;
  margin: 0;
}
.pricing-bg-text {
  font-family: 'Anton', sans-serif;
  font-size: clamp(4.5rem, 9vw, 7rem);
  font-weight: 400;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.18);
  text-transform: uppercase;
  letter-spacing: 4px;
  line-height: 0.9;
  z-index: 1;
  pointer-events: none;
  margin-top: -8px;
  display: inline-flex;
  align-items: baseline;
}
.pricing-green-dot {
  display: inline-block;
  width: clamp(12px, 2vw, 18px);
  height: clamp(12px, 2vw, 18px);
  background-color: var(--green);
  margin-left: 8px;
  vertical-align: baseline;
  box-shadow: 0 0 15px var(--glow);
}

.gallery-mosaic{display:grid;grid-template-columns:repeat(12,1fr);grid-template-rows:320px 320px;gap:16px;max-width:1400px;margin:0 auto;padding:0 24px;}
.g-item{
  position:relative;
  overflow:hidden;
  border-radius:16px;
  border:1px solid rgba(255,255,255,0.05);
  transition:border-color 0.4s ease, box-shadow 0.4s ease;
}
.g-item:hover {
  border-color:rgba(0,255,136,0.4);
  box-shadow:0 16px 35px rgba(0,255,136,0.15), inset 0 0 15px rgba(0,255,136,0.05);
}
.g-item:nth-child(1){grid-column:1/6;grid-row:1/2;}
.g-item:nth-child(2){grid-column:6/9;grid-row:1/2;}
.g-item:nth-child(3){grid-column:9/13;grid-row:1/3;}
.g-item:nth-child(4){grid-column:1/5;grid-row:2/3;}
.g-item:nth-child(5){grid-column:5/9;grid-row:2/3;}
.g-item img{width:100%;height:100%;object-fit:cover;filter:brightness(0.4) saturate(0.6) contrast(1.1);transition:transform .8s cubic-bezier(0.25, 1, 0.5, 1), filter .5s ease;}
.g-item:hover img{transform:scale(1.1);filter:brightness(0.9) saturate(1.15) contrast(1.0);}
.g-overlay{position:absolute;inset:0;background:radial-gradient(circle at center, rgba(0,255,136,0.08) 0%, rgba(6,6,8,0.6) 100%);opacity:0;transition:opacity 0.4s ease;display:flex;align-items:center;justify-content:center;z-index:2;}
.g-item:hover .g-overlay{opacity:1;}
.g-zoom{
  color:var(--green);
  font-size:3.5rem;
  font-weight:300;
  text-shadow:0 0 10px rgba(0,255,136,0.3);
  transform:scale(0.8) rotate(-45deg);
  transition:transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), text-shadow 0.3s;
}
.g-item:hover .g-zoom{
  transform:scale(1) rotate(0deg);
  text-shadow:0 0 25px rgba(0,255,136,0.8);
}
.g-label{
  position:absolute;
  bottom:20px;
  left:20px;
  font-family:'Orbitron',sans-serif;
  font-size:0.8rem;
  font-weight:700;
  letter-spacing:2px;
  text-transform:uppercase;
  color:var(--green);
  text-shadow:0 2px 10px rgba(0,0,0,0.8);
  opacity:0;
  transform:translateY(15px);
  transition:opacity 0.4s ease, transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
  z-index:3;
}
.g-item:hover .g-label{opacity:1;transform:translateY(0);}

#lb{position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:9999;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s;}
#lb.open{opacity:1;pointer-events:all;}
#lb img{max-width:82vw;max-height:82vh;object-fit:contain;border:1px solid rgba(0,255,136,.2);}
#lb-close{position:absolute;top:28px;right:32px;font-size:1.4rem;color:var(--green);background:none;border:none;font-family:'Orbitron',monospace; transition:all 0.3s;}
#lb-close:hover { color: var(--white); text-shadow: 0 0 10px var(--green); }

.showcase-sec{padding:120px 0;background:var(--dark);overflow:hidden;}
.showcase-head {
  text-align: left;
  margin-bottom: 56px;
  max-width: 1400px;
  margin-left: auto;
  margin-right: auto;
  padding: 0 24px;
}
.pricing-head {
  text-align: center;
  margin-bottom: 56px;
}
.showcase-grid-layout {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 32px;
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
}
@media(max-width: 768px) {
  .showcase-grid-layout {
    grid-template-columns: 1fr;
  }
}

.sc-card {
  height: 340px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  position: relative;
  transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.3s ease, box-shadow 0.4s ease;
  transform-style: preserve-3d;
  display: flex;
  flex-direction: column;
}
.sc-card:hover {
  border-color: rgba(0, 255, 136, 0.35);
  box-shadow: 0 15px 35px rgba(0, 255, 136, 0.08);
}
.sc-card-img-wrap {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 0;
}
.sc-card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.6) saturate(0.85);
  transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1), filter 0.6s ease;
}
.sc-card:hover .sc-card-img {
  transform: scale(1.08);
  filter: brightness(0.7) saturate(1.0);
}
.sc-card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(6, 6, 8, 0.95) 0%, rgba(6, 6, 8, 0.4) 40%, transparent 80%);
  z-index: 1;
}
.sc-card-body {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 24px;
  z-index: 2;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  pointer-events: none;
}
.sc-card-title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.45rem;
  font-weight: 800;
  color: var(--white);
  margin-bottom: 6px;
  letter-spacing: 0.5px;
  line-height: 1.2;
  transition: color 0.3s ease;
}
.sc-card:hover .sc-card-title {
  color: var(--green);
}
.sc-card-desc {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.7);
  line-height: 1.4;
  margin: 0;
}
.sc-card-glow {
  display: none;
}

.pricing-sec{padding:120px 0;position:relative;}
.pricing-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;}

.p-card-wrapper {
  position: relative;
  border-radius: 16px;
  transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
}
.p-card-wrapper:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 20px 45px rgba(0, 255, 136, 0.18);
}

.shine-border-bg {
  position: absolute;
  inset: 0;
  border-radius: 16px;
  overflow: hidden;
  pointer-events: none;
  z-index: 0;
}
.p-card-shine {
  position: absolute;
  inset: -150%;
  background: conic-gradient(
    from 0deg,
    transparent 20%,
    var(--green) 40%,
    var(--green) 60%,
    transparent 80%
  );
  animation: spin-shine 4s linear infinite;
  opacity: 0.5;
}
.featured-shine {
  animation: spin-shine 3s linear infinite;
  opacity: 0.6;
}

@keyframes spin-shine {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.p-card{
  padding:40px;
  background:var(--card);
  border:none;
  position:relative;
  transition:background 0.3s;
  margin: 2px;
  border-radius: 14px;
  height: calc(100% - 4px);
}
.p-card-wrapper:hover .p-card {
  background: rgba(17, 19, 24, 0.95);
}
.p-card.featured{background: #0d1612;}
.p-card.featured:hover {background: #111e18;}
.p-card.featured::before{content:'TERPOPULER';position:absolute;top:0;left:50%;transform:translate(-50%,-50%);font-family:'Plus Jakarta Sans',sans-serif;font-size:.58rem;font-weight:700;letter-spacing:3px;color:var(--black);background:var(--green);padding:4px 16px;white-space:nowrap;z-index:10;}

.p-tag{font-family:'Plus Jakarta Sans',sans-serif;font-size:.68rem;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--green);margin-bottom:6px;}
.p-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.4rem;font-weight:800;color:var(--white);margin-bottom:20px;text-transform:none;letter-spacing:0.5px;line-height:1.1;}
.p-price{display:flex;align-items:baseline;gap:3px;margin-bottom:6px;}
.p-cur{font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;color:var(--gray);}
.p-amount{display:inline-block;font-family:'Orbitron',sans-serif;font-size:2rem;font-weight:700;color:var(--green);transition:transform 0.3s ease, text-shadow 0.3s ease;letter-spacing:1px;}
.p-per{font-family:'Plus Jakarta Sans',sans-serif;font-size:.78rem;color:var(--gray);}
.p-div{height:1px;background:var(--border);margin:24px 0;}
.p-feats{list-style:none;display:flex;flex-direction:column;gap:11px;margin-bottom:28px;}
.p-feats li{font-family:'Plus Jakarta Sans',sans-serif;font-size:.84rem;color:var(--gray2);display:flex;align-items:center;gap:10px;}
.p-feats li::before{content:'✦';color:var(--green);font-size:.58rem;flex-shrink:0;}
.p-btn{font-family:'Plus Jakarta Sans',sans-serif;font-size:.78rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;display:block;text-align:center;text-decoration:none;padding:13px;border:1px solid rgba(0,255,136,.4);color:var(--green);transition:all .3s;}
.p-card:hover .p-amount{display:inline-block;font-family:'Orbitron',sans-serif;font-size:2rem;font-weight:700;color:var(--green);transition:transform 0.3s ease, text-shadow 0.3s ease;letter-spacing:1px;}
.p-card:hover .p-btn { background: var(--green); color: var(--black); box-shadow: 0 0 15px rgba(0, 255, 136, 0.4); border-color: var(--green); }
.p-card:hover .p-btn.solid { background: #00e676; box-shadow: 0 0 20px rgba(0,255,136,0.6); }
.p-btn.solid{background:var(--green);color:var(--black);border-color:var(--green);}

.values-sec{padding:130px 0;position:relative;overflow:hidden;}
.values-sec::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(0,255,136,.3),transparent);}
.values-sec::after{content:'';position:absolute;bottom:-150px;left:-150px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(0,255,136,.04) 0%,transparent 70%);pointer-events:none;}
.values-hex-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:3px;margin-top:56px;}
@media(max-width:768px){.values-hex-grid{grid-template-columns:1fr;}}
.val-card{padding:48px 36px;background:var(--card);border:1px solid var(--border);position:relative;overflow:hidden;transition:all .35s;}
.val-card:hover{border-color:rgba(0,255,136,.25);transform:translateY(-4px);}
.val-card::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at var(--mx,50%) var(--my,50%),rgba(0,255,136,.06) 0%,transparent 65%);opacity:0;transition:opacity .35s;}
.val-card:hover::before{opacity:1;}
.val-num{font-family:'Anton',sans-serif;font-size:4rem;font-weight:400;color:transparent;-webkit-text-stroke:1px rgba(0,255,136,.12);position:absolute;top:16px;right:20px;line-height:1;}
.val-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:1rem;font-weight:700;color:var(--white);margin-bottom:14px;letter-spacing:1px;}
.val-desc{font-family:'Plus Jakarta Sans',sans-serif;font-size:.88rem;color:var(--gray2);line-height:1.75;}

.awards-sec{padding:80px 0;background:var(--dark);overflow:hidden;position:relative;}
.awards-sec::before,.awards-sec::after{content:'';position:absolute;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(0,255,136,.2),transparent);}
.awards-sec::before{top:0;}.awards-sec::after{bottom:0;}
.awards-track{display:flex;gap:0;white-space:nowrap;animation:marqueeMove 30s linear infinite; will-change: transform;}
.awards-sec:hover .awards-track {animation-play-state: paused;}
.awards-track-rev{animation-direction:reverse;animation-duration:35s;margin-top:3px;}
@keyframes marqueeMove{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
.award-item{display:inline-flex;align-items:center;gap:16px;padding:20px 36px;background:var(--card);border:1px solid var(--border);margin-right:3px;flex-shrink:0;transition:border-color .3s;}
.award-item:hover{border-color:rgba(0,255,136,.25);}
.award-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gray2);}
.award-year{font-family:'Orbitron',monospace;font-size:.65rem;color:var(--green);}

.cta-sec{padding:130px 0;background:var(--black);text-align:center;position:relative;overflow:hidden;}
.cta-sec .fields-bg-text, .cta-sec .fields-fg-text { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 800; }
.cta-title{font-family:'Anton',sans-serif;font-size:clamp(2.4rem,6vw,4.8rem);font-weight:400;color:var(--white);line-height:0.95;margin-bottom:20px;text-transform:uppercase;letter-spacing:1px;}
.cta-title span{color:var(--green);}
.cta-sub{font-family:'Plus Jakarta Sans',sans-serif;font-size:.95rem;color:var(--gray2);margin-bottom:40px;}

footer{padding:64px 0 28px;border-top:1px solid var(--border);position:relative;z-index:6;background:var(--black);}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:60px;margin-bottom:56px;}
@media(max-width:900px){.footer-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:480px){.footer-grid{grid-template-columns:1fr;gap:32px;} .footer-bottom{flex-wrap:wrap;gap:12px;justify-content:center;text-align:center;}}
.footer-desc{font-family:'Plus Jakarta Sans',sans-serif;font-size:.84rem;color:var(--gray);line-height:1.75;max-width:270px;margin-top:14px;}
.f-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:.65rem;letter-spacing:3px;text-transform:uppercase;color:var(--gray2);margin-bottom:20px;font-weight:700;}
.f-links{list-style:none;display:flex;flex-direction:column;gap:11px;}
.f-links li, .f-links a{font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;color:var(--gray);text-decoration:none;transition:color .25s;line-height:1.5;}
.f-links a:hover{color:var(--green);}
.f-links li strong {color:var(--white); font-weight:500;}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;padding-top:32px;border-top:1px solid var(--border);}
.footer-copy{font-family:'Plus Jakarta Sans',sans-serif;font-size:.7rem;letter-spacing:1px;color:var(--gray);}
::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-thumb{background:rgba(0,255,136,.15) }

/* Keyboard accessibility: focus-visible ring */
a:focus-visible, button:focus-visible, [role="button"]:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
  outline: 2px solid var(--green);
  outline-offset: 3px;
}

.fields-sec, .gallery-sec, .showcase-sec, .pricing-sec, .cta-sec { position: relative; z-index: 1; }

.gallery-sec::before, .showcase-sec::before, .pricing-sec::before, .cta-sec::before {
  content:''; position:absolute; top:0; left:0; right:0; height:1px;
  background:linear-gradient(90deg, transparent, rgba(0,255,136,.3), transparent);
}
.pricing-sec::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:1px;
  background:linear-gradient(90deg, transparent, rgba(0,255,136,.3), transparent);
}

.cta-sec::after {
  content:''; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
  width:700px; height:350px; border-radius:50%;
  background:radial-gradient(ellipse, rgba(0,255,136,.07) 0%, transparent 70%); pointer-events:none;
}
.showcase-sec::after {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    -55deg,
    rgba(0, 255, 136, 0.06) 0px,
    rgba(0, 255, 136, 0.06) 1px,
    transparent 1px,
    transparent 40px
  );
  mask-image: radial-gradient(circle at center, black 20%, transparent 90%);
  -webkit-mask-image: radial-gradient(circle at center, black 20%, transparent 90%);
  pointer-events: none;
  z-index: -1;
}

@media (max-width: 1024px) { 
  .gallery-mosaic { grid-template-columns: 1fr; grid-template-rows: auto; }
  .g-item:nth-child(n) { grid-column: 1 / -1 !important; grid-row: auto !important; height: 250px; }
}

.premium-showcase-container {
  background: var(--black);
  padding: 0;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 5;
  height: 100vh;
  overflow: hidden;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.showcase-bg-stadium {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at bottom, rgba(0, 255, 136, 0.15) 0%, rgba(6, 6, 8, 0.65) 60%, var(--black) 100%), 
                    url('https://images.unsplash.com/photo-1556056504-517cf015e859?w=1600&q=80');
  background-size: cover;
  background-position: center;
  opacity: 0.22;
  filter: blur(2px) contrast(1.1);
  z-index: 1;
  pointer-events: none;
}

.premium-scroll-sec {
  position: absolute !important;
  inset: 0;
  height: 100vh;
  width: 100%;
  background: transparent !important;
  box-sizing: border-box;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}

.premium-scroll-sec * {
  pointer-events: auto;
}

#scroll-sec-hero {
  background: #060608 !important;
  z-index: 10;
}
#scroll-sec-1 { z-index: 15; }
#scroll-sec-2 { z-index: 20; }
#scroll-sec-3 { z-index: 25; }

/* Container utama untuk kerangka outline overlay (Bingkai Hijau) */
#premium-border-frame {
  position: fixed;
  top: 16px; left: 16px; right: 16px; bottom: 16px;
  border-radius: 40px;
  pointer-events: none;
  opacity: 0;
  z-index: 998;
  box-shadow: 0 0 0 16px var(--green), 0 0 0 100vmax var(--green); 
}

#premium-pitch-bg {
  position: fixed;
  top: 16px; left: 16px; right: 16px; bottom: 16px;
  border-radius: 40px;
  pointer-events: none;
  opacity: 0;
  z-index: 6;
}

.pitch-tactical-svg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 0; 
  opacity: 0.15;
  pointer-events: none;
}

.pitch-white-lines {
  position: absolute;
  inset: 4px;
  border: 4px solid rgba(255, 255, 255, 0.85);
  border-radius: 32px;
  z-index: 1; 
}

.pitch-corner-arc {
  position: absolute;
  width: 60px; height: 60px;
  border-color: rgba(255, 255, 255, 0.85);
  border-style: solid;
  border-width: 0;
  z-index: 1000;
}
.pitch-corner-arc.tl { top: 0; left: 0; border-right-width: 4px; border-bottom-width: 4px; border-bottom-right-radius: 100%; }
.pitch-corner-arc.tr { top: 0; right: 0; border-left-width: 4px; border-bottom-width: 4px; border-bottom-left-radius: 100%; }
.pitch-corner-arc.bl { bottom: 0; left: 0; border-right-width: 4px; border-top-width: 4px; border-top-right-radius: 100%; }
.pitch-corner-arc.br { bottom: 0; right: 0; border-left-width: 4px; border-top-width: 4px; border-top-left-radius: 100%; }

@media (max-width: 1024px) {
  #premium-border-frame {
    top: 8px; left: 8px; right: 8px; bottom: 8px;
    border-radius: 20px;
    box-shadow: 0 0 0 8px var(--green), 0 0 0 100vmax var(--green);
  }
  #premium-pitch-bg {
    top: 8px; left: 8px; right: 8px; bottom: 8px;
    border-radius: 20px;
  }
  .pitch-white-lines {
    border-width: 2px;
    border-radius: 16px;
    inset: 2px;
  }
  .pitch-corner-arc {
    width: 30px; height: 30px;
  }
  .pitch-corner-arc.tl { border-right-width: 2px; border-bottom-width: 2px; }
  .pitch-corner-arc.tr { border-left-width: 2px; border-bottom-width: 2px; }
  .pitch-corner-arc.bl { border-right-width: 2px; border-top-width: 2px; }
  .pitch-corner-arc.br { border-left-width: 2px; border-top-width: 2px; }
}

.sc1-title-container {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10%;
  pointer-events: none;
  z-index: 7;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.sc1-title-left, .sc1-title-right {
  font-family: 'Orbitron', monospace;
  font-size: clamp(4rem, 18vw, 15rem);
  font-weight: 900;
  color: #ffffff;
  letter-spacing: -4px;
  line-height: 1;
  text-transform: uppercase;
  transform: scaleY(1.3);
  display: inline-block;
  transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1), text-shadow 0.4s ease, color 0.4s ease;
  pointer-events: auto; 
  cursor: default;
}
.sc1-title-left:hover {
  transform: scaleY(1.3) scaleX(1.05) translateX(-20px);
  color: var(--green);
  text-shadow: 0 0 30px var(--glow), 0 0 60px rgba(0, 255, 136, 0.4);
}
.sc1-title-right:hover {
  transform: scaleY(1.3) scaleX(1.05) translateX(20px);
  color: var(--green);
  text-shadow: 0 0 30px var(--glow), 0 0 60px rgba(0, 255, 136, 0.4);
}

@media (max-width: 1024px) {
  .sc1-title-container {
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    top: 50% !important;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 0;
    width: 100%;
    height: 48vh;
    gap: 0;
  }
  .sc1-title-left, .sc1-title-right {
    font-size: clamp(4.8rem, 20vw, 8rem);
    transform: scaleY(1.3);
    letter-spacing: -2px;
    display: block;
    text-align: center;
    pointer-events: auto;
  }
  .sc1-title-left:hover, .sc1-title-left:active {
    transform: scaleY(1.3) scaleX(1.05) translateY(-8px);
    color: var(--green);
    text-shadow: 0 0 25px var(--glow), 0 0 50px rgba(0, 255, 136, 0.4);
  }
  .sc1-title-right:hover, .sc1-title-right:active {
    transform: scaleY(1.3) scaleX(1.05) translateY(8px);
    color: var(--green);
    text-shadow: 0 0 25px var(--glow), 0 0 50px rgba(0, 255, 136, 0.4);
  }
}

.ball-section-text { 
  position: absolute; 
  z-index: 10; 
  top: 50%; 
  transform: translateY(-50%); 
  max-width: 380px; 
  opacity: 0; 
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
#bst-left { left: 8%; }
#bst-right { right: 8%; display: flex; flex-direction: column; align-items: flex-end; }
#bst-right .bst-title {
  font-size: clamp(3.6rem, 6.5vw, 5.6rem);
  line-height: 0.9;
  letter-spacing: -1px;
}
.bst-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .85rem; font-weight: 600; letter-spacing: 5px; text-transform: uppercase; color: var(--green); margin-bottom: 10px; }
.bst-label.green-dot {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--green);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 14px;
}
.bst-label.green-dot .dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--green);
  display: inline-block;
  box-shadow: 0 0 10px var(--glow);
}
.bst-label-wrap {
  margin-bottom: 18px;
}
.bst-label-pill {
  display: inline-block;
  padding: 5px 16px;
  border: 1.5px solid rgba(255,255,255,0.4);
  border-radius: 20px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  color: var(--white);
  text-transform: uppercase;
  letter-spacing: 2.5px;
}
.bst-title { 
  font-family: 'Anton', sans-serif; 
  font-size: clamp(3.6rem, 6.5vw, 5.6rem); 
  font-weight: 400; 
  line-height: 0.9; 
  color: var(--white); 
  text-transform: uppercase; 
  letter-spacing: -1px; 
}
.bst-title span {
  transition: color 0.3s ease;
  color: inherit;
}
.bst-title span:hover {
  color: var(--green);
}
.bst-stats-wrap {
  margin-top: 40px;
  display: flex;
  flex-direction: column;
  gap: 32px;
}
.bst-stat-item {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 2px;
  padding-left: 20px;
}
.bst-stat-item::before {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  width: 2px;
  height: 100%;
  background: rgba(255, 255, 255, 0.25);
  transition: all 0.3s ease;
}
.bst-stat-item:hover::before {
  background: var(--green);
  box-shadow: 0 0 8px var(--glow);
}
.bst-stat-val {
  font-family: 'Anton', sans-serif;
  font-size: 2.4rem;
  color: var(--white);
  line-height: 1;
}
.bst-stat-val .val-unit {
  font-size: 1.2rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-weight: 400;
  vertical-align: baseline;
  margin-left: 2px;
}
.bst-stat-sub {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  color: var(--white);
  letter-spacing: 2px;
  text-transform: uppercase;
  transition: all 0.3s ease;
}
.bst-stat-item:hover .bst-stat-sub {
  color: var(--green);
  transform: translateX(4px);
}
.bst-stat-desc {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.92rem;
  font-weight: 300;
  color: var(--gray2);
  line-height: 1.7;
  margin-top: 4px;
  max-width: 320px;
  transition: all 0.3s ease;
}
.bst-stat-item:hover .bst-stat-desc {
  color: var(--white);
  transform: translateX(4px);
}
.bst-stats-right-wrap {
  margin-top: 40px;
  display: flex;
  flex-direction: column;
  gap: 32px;
  align-items: flex-end;
}
.bst-stat-right-item {
  display: flex;
  align-items: flex-start;
  gap: 20px;
}
.bst-stat-right-text {
  text-align: right;
}
.bst-stat-right-val {
  font-family: 'Anton', sans-serif;
  font-size: 2.0rem;
  color: var(--white);
  line-height: 1;
  transition: color 0.3s ease;
}
.bst-stat-right-val:hover {
  color: var(--green);
}
.bst-stat-right-sub {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  color: var(--gray2);
  letter-spacing: 1.5px;
  margin-top: 4px;
}
.bst-stat-right-dot {
  width: 34px;
  height: 34px;
  border: 1.5px solid rgba(255,255,255,0.25);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 0px;
}
.bst-stat-right-dot .inner-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--white);
}
.bst-bottom-desc {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.92rem;
  font-weight: 300;
  color: var(--gray2);
  line-height: 1.7;
  margin-top: 48px;
  text-align: right;
  max-width: 350px;
}
.bst-grid-lines {
  position: absolute;
  inset: 0;
  display: flex;
  justify-content: space-between;
  pointer-events: none;
  z-index: 1;
  padding: 0 10%;
}
.bst-grid-lines .grid-line {
  width: 1px;
  height: 100%;
  background: rgba(255,255,255,0.04);
}
.bst-curved-bg {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0.6;
}
.bst-curved-bg svg {
  width: 100%;
  height: 100%;
  transform: scale(1.4);
}

@media (max-width: 1024px) {
  .ball-section-text {
    max-width: 240px !important;
    width: auto;
    top: 50% !important;
    transform: translateY(-50%) !important;
    text-shadow: 0 2px 10px rgba(0,0,0,0.8);
  }
  #bst-left {
    left: 20px;
    right: auto;
    text-align: left;
  }
  #bst-right {
    right: 20px;
    left: auto;
    text-align: right;
    align-items: flex-end;
  }
  .bst-title {
    font-size: clamp(2.4rem, 8vw, 3.2rem);
    line-height: 1.05;
    margin-bottom: 8px;
  }
  .bst-stats-wrap, .bst-stats-right-wrap, .bst-bottom-desc, .bst-stat-desc {
    display: none !important;
  }
  .bst-grid-lines, .bst-curved-bg {
    display: none !important;
  }
}

@media (max-width: 600px) {
  .ball-section-text {
    max-width: 170px !important;
  }
}

.scroll-ind.center {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  z-index: 5;
  opacity: 1;
}
.scroll-ind.center span {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: .85rem;
  font-weight: 700;
  letter-spacing: 4px;
  color: var(--green);
  text-transform: uppercase;
}
.scroll-bar {
  width: 2px;
  height: 60px;
  background: linear-gradient(to bottom, var(--green), transparent);
  animation: scrollPulse 2.2s ease-in-out infinite;
}
@keyframes scrollPulse{0%,100%{opacity:.25; transform:translateY(0)} 50%{opacity:1; transform:translateY(8px)}}

.fanwall-sec {
  padding: 110px 0;
  background: var(--black);
  position: relative;
  overflow: hidden;
}
.fanwall-bg-stadium {
  position: absolute;
  inset: 0;
  background-image: linear-gradient(to bottom, rgba(6, 6, 8, 1) 0%, rgba(6, 6, 8, 0.45) 40%, rgba(6, 6, 8, 0.45) 60%, rgba(6, 6, 8, 1) 100%), 
                    url('https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=1600&q=80');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  opacity: 0.32;
  filter: blur(2px) grayscale(15%);
  z-index: 0;
  pointer-events: none;
}
.fanwall-divider {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(0, 255, 136, 0.35), transparent);
  z-index: 5;
  pointer-events: none;
}

.fanwall-sec::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    repeating-linear-gradient(
      0deg,
      transparent,
      transparent 38px,
      rgba(0,255,136,0.028) 38px,
      rgba(0,255,136,0.028) 39px
    ),
    repeating-linear-gradient(
      90deg,
      transparent,
      transparent 38px,
      rgba(0,255,136,0.018) 38px,
      rgba(0,255,136,0.018) 39px
    );
  pointer-events: none;
  z-index: 0;
}

.fanwall-sec::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80vw;
  height: 60vh;
  border-radius: 50%;
  background: radial-gradient(ellipse, rgba(0,255,136,0.06) 0%, transparent 68%);
  pointer-events: none;
  z-index: 0;
}

.fanwall-neon-l,
.fanwall-neon-r {
  position: absolute;
  pointer-events: none;
  z-index: 0;
}
.fanwall-neon-l {
  top: -80px; left: -100px;
  width: 520px; height: 2px;
  background: linear-gradient(90deg, transparent, rgba(0,255,136,0.18), transparent);
  transform: rotate(32deg);
  box-shadow: 0 0 18px 3px rgba(0,255,136,0.12);
  animation: neonStreakL 6s ease-in-out infinite alternate;
}
.fanwall-neon-r {
  bottom: -80px; right: -100px;
  width: 520px; height: 2px;
  background: linear-gradient(90deg, transparent, rgba(0,255,136,0.14), transparent);
  transform: rotate(32deg);
  box-shadow: 0 0 18px 3px rgba(0,255,136,0.1);
  animation: neonStreakR 7s ease-in-out infinite alternate;
}
@keyframes neonStreakL {
  0%   { opacity: 0.5; transform: rotate(32deg) translateX(0); }
  100% { opacity: 1;   transform: rotate(32deg) translateX(40px); }
}
@keyframes neonStreakR {
  0%   { opacity: 0.4; transform: rotate(32deg) translateX(0); }
  100% { opacity: 0.9; transform: rotate(32deg) translateX(-40px); }
}

.fanwall-track-wrap {
  position: relative;
  z-index: 1;
  overflow: hidden;
  -webkit-mask-image: linear-gradient(90deg, transparent 0%, black 8%, black 92%, transparent 100%);
  mask-image: linear-gradient(90deg, transparent 0%, black 8%, black 92%, transparent 100%);
}

.fanwall-track {
  display: flex;
  gap: 16px;
  width: max-content;
  animation: fanwallScroll 38s linear infinite;
  will-change: transform;
}
.fanwall-track-rev {
  animation-direction: reverse;
  animation-duration: 44s;
  margin-top: 16px;
}

.fanwall-track-wrap:hover .fanwall-track,
.fanwall-track-wrap:hover .fanwall-track-rev {
  animation-play-state: paused;
}

@keyframes fanwallScroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.fw-card {
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  border: 1px solid rgba(0,255,136,0.08);
  transition: transform 0.4s cubic-bezier(0.25,1,0.5,1), border-color 0.3s, box-shadow 0.3s;
}
.fw-card:hover {
  transform: translateY(-8px) scale(1.03);
  border-color: rgba(0,255,136,0.35);
  box-shadow: 0 12px 40px rgba(0,255,136,0.14), 0 0 0 1px rgba(0,255,136,0.1);
}

.fw-card.sz-s  { width: 200px; height: 260px; }
.fw-card.sz-m  { width: 240px; height: 310px; }
.fw-card.sz-l  { width: 200px; height: 260px; }
.fw-card.sz-p  { width: 220px; height: 290px; }

.fanwall-track-rev .fw-card.sz-s  { height: 240px; }
.fanwall-track-rev .fw-card.sz-m  { height: 288px; }
.fanwall-track-rev .fw-card.sz-l  { height: 240px; }
.fanwall-track-rev .fw-card.sz-p  { height: 268px; }

.fw-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.72) saturate(0.75);
  transition: filter 0.55s ease, transform 0.55s ease;
  display: block;
}
.fw-card:hover img {
  filter: brightness(0.9) saturate(1.1);
  transform: scale(1.07);
}

.fw-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(0,255,136,0.1) 0%, transparent 45%);
  opacity: 0;
  transition: opacity 0.35s;
  z-index: 1;
  pointer-events: none;
}
.fw-card:hover::before { opacity: 1; }

.fw-card::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--green), transparent);
  opacity: 0;
  transition: opacity 0.35s;
  z-index: 2;
}
.fw-card:hover::after { opacity: 1; }

@media (max-width: 1024px) {
  .fanwall-sec { padding: 72px 0; }
  .fw-card.sz-s  { width: 150px; height: 195px; }
  .fw-card.sz-m  { width: 180px; height: 230px; }
  .fw-card.sz-l  { width: 150px; height: 195px; }
  .fw-card.sz-p  { width: 164px; height: 215px; }
  .fanwall-track-rev .fw-card.sz-s  { height: 180px; }
  .fanwall-track-rev .fw-card.sz-m  { height: 212px; }
  .fanwall-track-rev .fw-card.sz-l  { height: 180px; }
  .fanwall-track-rev .fw-card.sz-p  { height: 198px; }
  .fanwall-track { gap: 10px; }
  .fanwall-track-rev { margin-top: 10px; }
}
body, a, button, .f-card, .showcase-item, .g-item, .nav-cta, .nav-btn-dark, .nav-btn-glow, .nav-btn-logout, .nav-instagram-link, .nav-whatsapp-link, .nav-tiktok-link, .btn-p, .btn-s, .btn-soft, .f-book-btn, #lb-close, .val-card, .award-item, .p-btn, .fw-card {
  cursor: auto !important;
}
a, button, [role="button"], input[type="submit"], select, textarea, .btn-p, .btn-s, .btn-soft, .f-book-btn, #lb-close, .nav-cta, .nav-btn-dark, .nav-btn-glow, .nav-btn-logout, .nav-instagram-link, .nav-whatsapp-link, .nav-tiktok-link, .p-btn {
  cursor: pointer !important;
}

.gallery-sec, .pricing-sec, .values-sec {
  position: relative;
  overflow: hidden;
}
.gallery-sec .container, 
.gallery-mosaic,
.pricing-sec .container,
.values-sec .container {
  position: relative;
  z-index: 2;
}

/* 1. Monochrome Radial Spotlight (Galeri) */
.bg-deco-spotlight {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 0;
  overflow: hidden;
}
.spotlight-circle {
  position: absolute;
  top: -250px;
  left: -250px;
  width: 800px;
  height: 800px;
  border-radius: 50%;
  background: radial-gradient(circle, #181818 0%, transparent 70%);
  opacity: 0.8;
  animation: spotlightFloat 20s infinite ease-in-out alternate;
}
@keyframes spotlightFloat {
  0% { transform: translate(10%, 20%); }
  50% { transform: translate(60%, 40%); }
  100% { transform: translate(30%, 80%); }
}

/* 4. Tactile Film Grain / Grunge Noise Overlay (Values) */
.bg-deco-noise {
  position: absolute;
  inset: 0;
  opacity: 0.035;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 1;
}

/* Green glows scattered in values section representing lights refracting through glass cards */
.values-glows {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
  z-index: 1;
}
.v-glow {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(0, 255, 136, 0.05) 0%, transparent 70%);
  filter: blur(50px);
  pointer-events: none;
}
.glow-1 {
  top: -10%;
  right: -5%;
  width: 450px;
  height: 450px;
}
.glow-2 {
  bottom: 10%;
  right: 10%;
  width: 350px;
  height: 350px;
}

/* Green Pricing Background Decorations (Grid + Blurs + Radial Glow) */
.pricing-sec {
  position: relative;
  overflow: hidden;
}
.bg-pricing-grid {
  position: absolute;
  inset: 0;
  background-image: 
    linear-gradient(to right, rgba(0, 255, 136, 0.04) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(0, 255, 136, 0.02) 1px, transparent 1px);
  background-size: 70px 80px;
  pointer-events: none;
  z-index: 1;
  mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black, transparent);
  -webkit-mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black, transparent);
}
.bg-pricing-blurs {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
  z-index: 0;
}
.pricing-blur-ellipse {
  position: absolute;
  top: -15%;
  left: 50%;
  transform: translateX(-50%);
  width: 900px;
  height: 900px;
  border-radius: 50%;
  border: 180px solid rgba(0, 255, 136, 0.05);
  filter: blur(80px);
  -webkit-filter: blur(80px);
}
.pricing-radial-glow {
  position: absolute;
  top: 10%;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  height: 80%;
  background: radial-gradient(circle at center, rgba(0, 255, 136, 0.06) 0%, transparent 70%);
  pointer-events: none;
}

/* ── IMAGE FULL-SCREEN SECTION ── */
.image-sec {
  position: relative;
  width: 100%;
  height: 60vh;
  min-height: 360px;
  overflow: hidden;
}
.image-img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 120%;
  object-fit: cover;
  filter: brightness(0.45) saturate(0.85);
  will-change: transform;
}
.image-overlay {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(to right, rgba(6,6,8,0.85) 0%, rgba(6,6,8,0.2) 40%, rgba(6,6,8,0.2) 60%, rgba(6,6,8,0.85) 100%),
    linear-gradient(to bottom, rgba(6,6,8,0.6) 0%, transparent 30%, transparent 70%, rgba(6,6,8,0.9) 100%);
  z-index: 1;
  pointer-events: none;
}
.image-content {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 0 8%;
  z-index: 2;
}
.image-text-wrap {
  max-width: 480px;
}
.image-label {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 5px;
  text-transform: uppercase;
  color: var(--green);
  margin-bottom: 14px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.image-label .ml-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--green);
  box-shadow: 0 0 10px var(--glow);
}
.image-title {
  font-family: 'Anton', sans-serif;
  font-size: clamp(2.4rem, 5vw, 4rem);
  font-weight: 400;
  color: var(--white);
  text-transform: uppercase;
  line-height: 0.95;
  letter-spacing: 2px;
  margin-bottom: 18px;
}
.image-title span {
  color: var(--green);
}
.image-title span.outline {
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.22);
}

.image-sep-top,
.image-sep-bottom {
  position: absolute;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(0,255,136,0.3), transparent);
  z-index: 3;
}
.image-sep-top { top: 0; }
.image-sep-bottom { bottom: 0; }

@media (max-width: 768px) {
  .image-sec { height: 50vh; min-height: 300px; }
  .image-content { padding: 0 24px; }
  .image-title { font-size: clamp(1.8rem, 6vw, 2.6rem); }
}
/* PRELOADER SPINNER */
#site-preloader {
  position: fixed; inset: 0; background: var(--black); z-index: 99999;
  display: flex; align-items: center; justify-content: center;
  flex-direction: column; gap: 20px;
  transition: opacity 0.6s ease, visibility 0.6s ease;
}
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
.preloader-hidden {
  opacity: 0;
  visibility: hidden;
}

/* ── LOCATION SECTION ── */
.location-sec {
  padding: 120px 0;
  background: var(--dark);
  position: relative;
  overflow: hidden;
}
.location-sec::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(0,255,136,.3), transparent);
}
.location-sec::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(0,255,136,.3), transparent);
}
/* Scanline layer */
.loc-scanlines {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 0;
  background: repeating-linear-gradient(
    0deg,
    rgba(0, 255, 136, 0.022) 0px,
    rgba(0, 255, 136, 0.022) 1px,
    transparent 1px,
    transparent 6px
  );
  mask-image: radial-gradient(ellipse 85% 70% at 50% 50%, black 30%, transparent 100%);
  -webkit-mask-image: radial-gradient(ellipse 85% 70% at 50% 50%, black 30%, transparent 100%);
  animation: scanline-drift 8s linear infinite;
}
@keyframes scanline-drift {
  0%   { background-position: 0 0; }
  100% { background-position: 0 60px; }
}


/* Garis dekorasi vertikal di latar */
.loc-grid-deco {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  opacity: 0.35;
}
.loc-grid-deco::before,
.loc-grid-deco::after {
  content: '';
  position: absolute;
  top: 0; bottom: 0;
  width: 1px;
  background: linear-gradient(to bottom, transparent, rgba(0,255,136,0.15), transparent);
}
.loc-grid-deco::before { left: 25%; }
.loc-grid-deco::after  { right: 25%; }

/* Layout 2 kolom */
.loc-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: start;
}
@media (max-width: 900px) {
  .loc-layout { grid-template-columns: 1fr; gap: 40px; }
}

/* Panel kiri: heading + info */
.loc-left {}

.loc-header {
  margin-bottom: 40px;
}
.loc-sup-label {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 5px;
  text-transform: uppercase;
  color: var(--green);
  margin-bottom: 14px;
}
.loc-sup-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--green);
  box-shadow: 0 0 8px var(--glow);
}
.loc-title-wrap {
  display: flex;
  flex-direction: column;
}
.loc-title-bg {
  font-family: 'Anton', sans-serif;
  font-size: clamp(3.5rem, 7vw, 5.5rem);
  font-weight: 400;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(255,255,255,0.15);
  text-transform: uppercase;
  letter-spacing: 3px;
  line-height: 0.9;
  pointer-events: none;
}
.loc-title-fg {
  font-family: 'Anton', sans-serif;
  font-size: clamp(3.5rem, 7vw, 5.5rem);
  font-weight: 400;
  color: var(--white);
  text-transform: uppercase;
  letter-spacing: 2px;
  line-height: 0.9;
  display: inline-flex;
  align-items: baseline;
}
.loc-green-dot {
  display: inline-block;
  width: clamp(10px, 1.6vw, 14px);
  height: clamp(10px, 1.6vw, 14px);
  background: var(--green);
  margin-left: 8px;
  vertical-align: baseline;
  box-shadow: 0 0 12px var(--glow);
}

/* Tabs info */
.loc-tabs {
  display: flex;
  gap: 0;
  border-bottom: 1px solid var(--border2);
  margin-bottom: 28px;
}
.loc-tab-btn {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--gray);
  background: none;
  border: none;
  padding: 10px 20px 12px;
  cursor: pointer;
  position: relative;
  transition: color 0.25s;
  display: inline-flex;
  align-items: center;
  gap: 7px;
}
.loc-tab-btn::after {
  content: '';
  position: absolute;
  bottom: -1px; left: 0; right: 0;
  height: 2px;
  background: var(--green);
  transform: scaleX(0);
  transition: transform 0.3s ease;
}
.loc-tab-btn.active {
  color: var(--green);
}
.loc-tab-btn.active::after {
  transform: scaleX(1);
}
.loc-tab-btn:hover { color: var(--white); }

/* Tab panels */
.loc-panel { display: none; }
.loc-panel.active { display: block; }

/* Info rows */
.loc-info-rows {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.loc-info-row {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 16px 18px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 10px;
  transition: border-color 0.3s, transform 0.3s;
  position: relative;
  overflow: hidden;
}
.loc-info-row::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at var(--mx,50%) var(--my,50%), rgba(0,255,136,0.06) 0%, transparent 60%);
  opacity: 0;
  transition: opacity 0.3s;
}
.loc-info-row:hover {
  border-color: rgba(0,255,136,0.3);
  transform: translateX(4px);
}
.loc-info-row:hover::before { opacity: 1; }
.loc-info-icon {
  width: 38px; height: 38px;
  border-radius: 8px;
  background: rgba(0,255,136,0.08);
  border: 1px solid rgba(0,255,136,0.2);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  font-size: 1rem;
  color: var(--green);
  line-height: 1;
}
.loc-info-text {}
.loc-info-label {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--green);
  margin-bottom: 3px;
}
.loc-info-val {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.88rem;
  color: var(--white);
  font-weight: 500;
  line-height: 1.4;
}

/* Jam operasional */
.loc-hours-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
@media (max-width: 400px) {
  .loc-hours-grid { grid-template-columns: 1fr; }
}
.loc-hour-item {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 14px 16px;
  transition: border-color 0.3s, transform 0.3s, background 0.3s;
}
.loc-hour-item:hover {
  border-color: rgba(0,255,136,0.3);
  background: rgba(0,255,136,0.02);
  transform: translateY(-3px);
}
.loc-hour-day {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--gray2);
  margin-bottom: 4px;
}
.loc-hour-time {
  font-family: 'Orbitron', monospace;
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--white);
  letter-spacing: 1px;
}
.loc-hour-badge {
  display: inline-block;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.58rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--black);
  background: var(--green);
  padding: 2px 8px;
  border-radius: 20px;
  margin-top: 4px;
}

/* Kontak */
.loc-contact-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.loc-contact-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 16px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 10px;
  text-decoration: none;
  transition: border-color 0.3s, transform 0.3s, background 0.3s;
}
.loc-contact-item:hover {
  border-color: rgba(0,255,136,0.4);
  background: rgba(0,255,136,0.04);
  transform: translateX(4px);
}
.loc-contact-icon {
  font-size: 1.15rem;
  width: 38px;
  height: 38px;
  border-radius: 8px;
  background: rgba(0,255,136,0.08);
  border: 1px solid rgba(0,255,136,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: var(--green);
  line-height: 1;
  transition: background 0.3s, border-color 0.3s;
}
.loc-contact-item:hover .loc-contact-icon {
  background: rgba(0,255,136,0.15);
  border-color: rgba(0,255,136,0.45);
}
.loc-contact-label {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--green);
  margin-bottom: 2px;
}
.loc-contact-val {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.88rem;
  color: var(--white);
  font-weight: 500;
}

/* Panel kanan: Map */
.loc-right {}

.loc-map-wrapper {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid var(--border2);
  background: var(--card);
  transition: border-color 0.4s, box-shadow 0.4s;
  height: 480px;
}
@media (max-width: 900px) {
  .loc-map-wrapper { height: 340px; }
}
.loc-map-wrapper:hover {
  border-color: rgba(0,255,136,0.4);
  box-shadow: 0 20px 50px rgba(0,255,136,0.1);
}

/* Corner accents */
.loc-map-corner {
  position: absolute;
  width: 20px; height: 20px;
  pointer-events: none;
  z-index: 3;
}
.loc-map-corner::before,
.loc-map-corner::after {
  content: '';
  position: absolute;
  background: var(--green);
  border-radius: 1px;
}
.loc-map-corner::before { width: 20px; height: 2px; }
.loc-map-corner::after  { width: 2px; height: 20px; }
.loc-map-corner.tl { top: 10px; left: 10px; }
.loc-map-corner.tr { top: 10px; right: 10px; transform: scaleX(-1); }
.loc-map-corner.bl { bottom: 10px; left: 10px; transform: scaleY(-1); }
.loc-map-corner.br { bottom: 10px; right: 10px; transform: scale(-1,-1); }

.loc-map-iframe {
  width: 100%; height: 100%;
  border: none;
  display: block;
  filter: grayscale(0.85) invert(0.92) hue-rotate(160deg) contrast(0.9) brightness(0.75);
  transition: filter 0.5s ease;
}
.loc-map-wrapper:hover .loc-map-iframe {
  filter: grayscale(0.5) invert(0.85) hue-rotate(160deg) contrast(0.95) brightness(0.85);
}

/* Overlay label atas map */
.loc-map-label {
  position: absolute;
  top: 14px;
  left: 14px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(6,6,8,0.82);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(0,255,136,0.25);
  padding: 7px 14px;
  border-radius: 30px;
  z-index: 4;
  pointer-events: none;
}
.loc-map-label-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--green);
  box-shadow: 0 0 10px var(--glow);
  animation: locPulse 2.2s ease-in-out infinite;
}
@keyframes locPulse {
  0%, 100% { transform: scale(1); box-shadow: 0 0 6px rgba(0,255,136,.5); }
  50%       { transform: scale(1.4); box-shadow: 0 0 18px rgba(0,255,136,.9), 0 0 32px rgba(0,255,136,.2); }
}
.loc-map-label-text {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--green);
}

/* CTA bawah map */
.loc-map-cta {
  position: absolute;
  bottom: 14px;
  right: 14px;
  z-index: 4;
}
.loc-map-open-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(0,255,136,0.9);
  color: var(--black);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 2px;
  text-transform: uppercase;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 8px;
  transition: all 0.3s cubic-bezier(0.25,1,0.5,1);
  box-shadow: 0 4px 16px rgba(0,255,136,0.35);
}
.loc-map-open-btn:hover {
  background: var(--green);
  transform: translateY(-2px) scale(1.04);
  box-shadow: 0 8px 24px rgba(0,255,136,0.5);
}
.loc-map-open-btn svg {
  flex-shrink: 0;
}

/* Status bar bawah */
.loc-status-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
  margin-top: 20px;
  padding: 16px 20px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
}
.loc-status-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.loc-status-indicator {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--green);
  box-shadow: 0 0 8px rgba(0,255,136,.6);
  animation: locPulse 2s ease-in-out infinite;
}
.loc-status-text {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--white);
}
.loc-status-meta {
  font-family: 'Orbitron', monospace;
  font-size: 0.65rem;
  color: var(--green);
  letter-spacing: 1px;
}
@media (max-width: 480px) {
  .loc-status-bar { flex-direction: column; align-items: flex-start; }
  .loc-tabs { overflow-x: auto; }
  .loc-tab-btn { padding: 10px 14px 12px; font-size: 0.65rem; }
}

/* Pindahkan hero-location ke hidden — section lokasi menggantikannya */
.hero-location { display: none !important; }

/* ── WEBGL LIQUID DISTORTION OVERLAY ── */
#hero-gl-canvas {
  position: absolute;
  inset: 0;
  width: 100% !important;
  height: 100% !important;
  z-index: 6;
  pointer-events: auto;
  display: block;
}
/* hero-content tetap di bawah canvas, tidak perlu pointer-events */
#hero-content { pointer-events: none; }
.hero-title { transition: opacity 0.8s ease; }

</style>
</head>
<body>
<!-- PRELOADER SPINNER -->
<div id="site-preloader">
  <svg class="hexagon-spinner" viewBox="0 0 60 60">
    <polygon points="30,4 52.5,17 52.5,43 30,56 7.5,43 7.5,17" />
  </svg>
  <p class="preloader-label">Loading&hellip;</p>
</div>
<div id="noise"></div>
<div id="lb"><button id="lb-close">✕</button><img id="lb-img" src="" alt=""></div>

<!-- OUTLINE BINGKAI OVERLAY HIJAU (SCREEN BORDER MASK DI DEPAN BOLA) -->
<div id="premium-border-frame"></div>

<!-- DIAGRAM TAKTIS LAPANGAN & GARIS PUTIH (LATAR BACKGROUND DI BELAKANG BOLA) -->
<div id="premium-pitch-bg">
  <svg class="pitch-tactical-svg" viewBox="0 0 1000 600" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M50,300 C150,100 350,100 500,300 C650,500 850,500 950,300" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-dasharray="8,8" />
    <path d="M100,50 C300,150 700,150 900,50" stroke="rgba(255,255,255,0.3)" stroke-width="2" stroke-dasharray="6,6" />
    <circle cx="500" cy="300" r="250" stroke="rgba(255,255,255,0.2)" stroke-width="2" stroke-dasharray="10,10" />
  </svg>

  <div class="pitch-white-lines">
    <div class="pitch-corner-arc tl"></div>
    <div class="pitch-corner-arc tr"></div>
    <div class="pitch-corner-arc bl"></div>
    <div class="pitch-corner-arc br"></div>
  </div>
</div>

<!-- CANVAS BOLA FIXED OVERLAY UNTUK ANIMASI SCROLL SHOWCASE (DI-SET VISIBLE OPACITY: 1) -->
<canvas id="ball-scroll-canvas" style="position: fixed; inset: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: 8; opacity: 1; transition: opacity 0.4s;"></canvas>

<nav id="nav">
  <div class="nav-left">
    <a href="#" class="logo">MINI<em>FUT</em></a>
    <span class="nav-slash">/</span>
    <a href="booking.php" class="nav-booking-link">
      <span class="nav-booking-orig">BOOK NOW</span>
      <span class="nav-booking-clone">BOOK NOW</span>
    </a>
    <a href="https://instagram.com/minifut.arena" target="_blank" class="nav-instagram-link" title="Instagram MiniFut Arena">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="https://wa.me/628123456789" target="_blank" class="nav-whatsapp-link" title="WhatsApp MiniFut Arena">
      <i class="bi bi-whatsapp"></i>
    </a>
    <a href="https://tiktok.com/@minifut.id" target="_blank" class="nav-tiktok-link" title="TikTok MiniFut Arena">
      <i class="bi bi-tiktok"></i>
    </a>
  </div>
  
  <div class="nav-right">
    <?php if (isPelanggan()): ?>
      <div class="nav-user-wrapper">
        <?php if (!empty($user_foto)): ?>
          <a href="profile.php" title="Profil Saya" class="nav-avatar-link">
            <img src="admin/uploads/<?= htmlspecialchars($user_foto, ENT_QUOTES, 'UTF-8') ?>" alt="Foto Profil">
          </a>
        <?php else: ?>
          <a href="profile.php" title="Profil Saya" class="nav-avatar-link">
            <div class="nav-avatar-placeholder">
              <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>
          </a>
        <?php endif; ?>
        
        <a href="auth/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari MiniFut Arena?')" class="nav-btn-logout">
          Logout
        </a>
      </div>
    <?php else: ?>
      <a href="auth/login.php" class="nav-btn-dark">LOGIN</a>
      <a href="booking.php" class="nav-btn-glow">BOOK NOW</a>
    <?php endif; ?>
  </div>
</nav>

<!-- PINNED SHOWCASE CONTAINER UTAMA (MENCAKUP HERO DAN SECTION 2-4) -->
<div class="premium-showcase-container">
  <!-- Stadium Background Overlay for depth -->
  <div class="showcase-bg-stadium"></div>
  <!-- SECTION 1: HERO (Pindah ke dalam container agar pin terintegrasi) -->
  <section class="premium-scroll-sec hero" id="scroll-sec-hero">
    <!-- CANVAS ANIMATED GLOWING FLUID SHADER (BACKGROUND LAYER) -->
    <canvas id="shader-canvas"></canvas>
    
    <!-- CANVAS INTERACTIVE SOCCER STADIUM 3D (FOREGROUND LAYER) -->
    <canvas id="three-canvas"></canvas>
    
    <div class="hero-overlay"></div>
    <div class="hero-grid"></div>

    <div class="hero-content" id="hero-content">
      <div class="hero-underline"></div>
    </div>
    <!-- WebGL liquid distortion canvas — renders over hero-content, captures mouse -->
    <canvas id="hero-gl-canvas" aria-hidden="true"></canvas>
    <div class="scroll-ind center">
      <span>Scroll Down</span>
      <div class="scroll-bar"></div>
    </div>
  </section>

  <!-- SECTION 2: MINI FUT TEXT -->
  <section class="premium-scroll-sec" id="scroll-sec-1">
    <div class="sc1-title-container">
      <span class="sc1-title-left">MINI</span>
      <span class="sc1-title-right">FUT</span>
    </div>
  </section>

  <!-- SECTION 3: RUMPUT PREMIUM -->
  <section class="premium-scroll-sec" id="scroll-sec-2">
    <div class="bst-grid-lines">
      <div class="grid-line"></div>
      <div class="grid-line"></div>
      <div class="grid-line"></div>
    </div>
    <div class="ball-section-text" id="bst-left">
      <div class="bst-label green-dot">
        <span class="dot"></span>
        FIFA GRADE
      </div>
      <h2 class="bst-title">PREMIUM<br>FIELD</h2>
      <div class="bst-stats-wrap">
        <div class="bst-stat-item">
          <div class="bst-stat-sub">PENCEGAHAN CEDERA</div>
          <p class="bst-stat-desc">Menyerap benturan secara optimal untuk mencegah risiko cedera sendi dan otot.</p>
        </div>
        <div class="bst-stat-item">
          <div class="bst-stat-sub">BEBAS GENANGAN AIR</div>
          <p class="bst-stat-desc">Sistem drainase superior menjaga lapangan tetap kering dan aman setelah hujan deras.</p>
        </div>
        <div class="bst-stat-item">
          <div class="bst-stat-sub">PERFORMA KONSISTEN</div>
          <p class="bst-stat-desc">Pantulan akurat dan pergerakan mulus memberikan sensasi main layaknya atlet profesional.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SECTION 4: BOLA PREMIUM -->
  <section class="premium-scroll-sec" id="scroll-sec-3">
    <div class="bst-curved-bg">
      <svg viewBox="0 0 1000 600" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M-100,200 C300,220 700,220 1100,200" stroke="rgba(255,255,255,0.05)" stroke-width="1.5" stroke-dasharray="6,6" />
        <path d="M-100,300 C300,330 700,330 1100,300" stroke="rgba(255,255,255,0.05)" stroke-width="1.5" stroke-dasharray="6,6" />
        <path d="M-100,400 C300,440 700,440 1100,400" stroke="rgba(255,255,255,0.05)" stroke-width="1.5" stroke-dasharray="6,6" />
      </svg>
    </div>
    <div class="ball-section-text right" id="bst-right">
      <div class="bst-label-wrap">
        <div class="bst-label-pill">FIFA QUALITY</div>
      </div>
      <h2 class="bst-title">FIFA<br><span>BALL</span></h2>
      <div class="bst-stats-right-wrap">
        <div class="bst-stat-right-item">
          <div class="bst-stat-right-text">
            <div class="bst-stat-right-val">01</div>
            <div class="bst-stat-right-sub">AKURASI PRESISI</div>
          </div>
          <div class="bst-stat-right-dot">
            <span class="inner-dot"></span>
          </div>
        </div>
        <div class="bst-stat-right-item">
          <div class="bst-stat-right-text">
            <div class="bst-stat-right-val">02</div>
            <div class="bst-stat-right-sub">DAYA TAHAN SUPERIOR</div>
          </div>
          <div class="bst-stat-right-dot">
            <span class="inner-dot"></span>
          </div>
        </div>
      </div>
      <p class="bst-bottom-desc">Bola berstandar resmi FIFA menjamin akurasi lintasan presisi, daya tahan superior, dan minim penyerapan air.</p>
    </div>
  </section>
</div>

<!-- ── FAN WALL: DOKUMENTASI CUSTOMER ── -->
<section class="fanwall-sec">
  <div class="fanwall-divider"></div>
  <div class="fanwall-bg-stadium"></div>
  <div class="fanwall-neon-l"></div>
  <div class="fanwall-neon-r"></div>

  <!-- ROW 1 — scroll kiri -->
  <div class="fanwall-track-wrap">
    <div class="fanwall-track">
      <div class="fw-card sz-m"><img src="https://images.unsplash.com/photo-1517927033932-b3d18e61fb3a?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-s"><img src="https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://images.unsplash.com/photo-1560272564-c83b66b1ad12?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1526232761682-d26e03ac148e?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-m"><img src="https://images.unsplash.com/photo-1570498839593-e565b39455fc?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-s"><img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1553778263-73a83bab9b0c?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80" alt="" loading="lazy"></div>
      
      <div class="fw-card sz-m"><img src="https://images.unsplash.com/photo-1517927033932-b3d18e61fb3a?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-s"><img src="https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://images.unsplash.com/photo-1560272564-c83b66b1ad12?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1526232761682-d26e03ac148e?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-m"><img src="https://images.unsplash.com/photo-1570498839593-e565b39455fc?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-s"><img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1553778263-73a83bab9b0c?w=600&q=80" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80" alt="" loading="lazy"></div>
    </div>
  </div>

  <!-- ROW 2 — scroll kanan (reverse) -->
  <div class="fanwall-track-wrap">
    <div class="fanwall-track fanwall-track-rev">
      <div class="fw-card sz-s"><img src="https://i.pinimg.com/736x/4e/ef/d5/4eefd50c21b7c794e4f7f47f455c34c4.jpg" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c29jY2VyfGVufDB8fDB8fHww" alt="" loading="lazy"></div>
      <div class="fw-card sz-m"><img src="https://i.pinimg.com/736x/fe/1c/8e/fe1c8edfb341d4ad913bde066b59fcd1.jpg" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://images.unsplash.com/photo-1632300951015-42d7df909581?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fG1pbmklMjBzb2NjZXJ8ZW58MHx8MHx8fDA%3D" alt="" loading="lazy"></div>
      <div class="fw-card sz-s"><img src="https://images.unsplash.com/photo-1715277331635-386d4a875b25?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fG1pbmklMjBzb2NjZXJ8ZW58MHx8MHx8fDA%3D" alt="" loading="lazy"></div>
      <div class="fw-card sz-m"><img src="https://images.unsplash.com/photo-1486286701208-1d58e9338013?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fHNvY2NlcnxlbnwwfHwwfHx8MA%3D%3D" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://i.pinimg.com/1200x/6b/52/e4/6b52e43d320c6767c098f0822b3f7582.jpg" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1632300873131-1dd749c83f97?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjB8fG1pbmklMjBzb2NjZXJ8ZW58MHx8MHx8fDA%3D" alt="" loading="lazy"></div>
      <!-- duplikat untuk seamless loop -->
      <div class="fw-card sz-s"><img src="https://i.pinimg.com/736x/4e/ef/d5/4eefd50c21b7c794e4f7f47f455c34c4.jpg" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c29jY2VyfGVufDB8fDB8fHww" alt="" loading="lazy"></div>
      <div class="fw-card sz-m"><img src="https://i.pinimg.com/736x/fe/1c/8e/fe1c8edfb341d4ad913bde066b59fcd1.jpg" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://images.unsplash.com/photo-1632300951015-42d7df909581?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fG1pbmklMjBzb2NjZXJ8ZW58MHx8MHx8fDA%3D" alt="" loading="lazy"></div>
      <div class="fw-card sz-s"><img src="https://images.unsplash.com/photo-1715277331635-386d4a875b25?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fG1pbmklMjBzb2NjZXJ8ZW58MHx8MHx8fDA%3D" alt="" loading="lazy"></div>
      <div class="fw-card sz-m"><img src="https://images.unsplash.com/photo-1486286701208-1d58e9338013?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fHNvY2NlcnxlbnwwfHwwfHx8MA%3D%3D" alt="" loading="lazy"></div>
      <div class="fw-card sz-l"><img src="https://i.pinimg.com/1200x/6b/52/e4/6b52e43d320c6767c098f0822b3f7582.jpg" alt="" loading="lazy"></div>
      <div class="fw-card sz-p"><img src="https://images.unsplash.com/photo-1632300873131-1dd749c83f97?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjB8fG1pbmklMjBzb2NjZXJ8ZW58MHx8MHx8fDA%3D" alt="" loading="lazy"></div>
    </div>
  </div>
</section>
<!-- ── END FAN WALL ── -->

<section class="fields-sec" id="lapangan">
  <div class="container">
    <div class="fields-head reveal">
      <div>
        <div class="sec-label">⬡ Pilihan Lapangan</div>
        <h2 class="fields-title-wrap">
          <span class="fields-bg-text">3 LAPANGAN</span>
          <span class="fields-fg-text">OUTDOOR<span class="fields-green-dot"></span></span>
        </h2>
      </div>
    </div>
    <div class="fields-grid">
      <div class="f-card reveal">
        <div class="f-card-img">
          <img src="https://images.unsplash.com/photo-1602432141202-e8b683524997?w=800&auto=format&fit=crop&q=80" alt="Lapangan 1" loading="lazy">
          <div class="f-card-img-overlay"></div>
          <div class="f-card-badge-wrap">
            <div class="f-badge">Rumput Sintetis Pro</div>
            <div class="f-card-title">LAPANGAN 1</div>
          </div>
        </div>
        <div class="f-card-body">
          <div class="f-tags">
            <span class="f-tag">16-20 Orang</span>
            <span class="f-tag">8v8 / 10v10</span>
            <span class="f-tag">Outdoor</span>
          </div>
          <div class="f-price-row">
            <div><span class="f-price-val">1.000K</span><span class="f-price-unit">/jam</span></div>
            <a href="booking.php" class="f-book-btn">Book</a>
          </div>
        </div>
      </div>
      <div class="f-card reveal">
        <div class="f-card-img">
          <img src="https://images.unsplash.com/photo-1502481686408-d428268c24ff?w=800&auto=format&fit=crop&q=80" alt="Lapangan 2" loading="lazy">
          <div class="f-card-img-overlay"></div>
          <div class="f-card-badge-wrap">
            <div class="f-badge">Rumput Sintetis Premium</div>
            <div class="f-card-title">LAPANGAN 2</div>
          </div>
        </div>
        <div class="f-card-body">
          <div class="f-tags">
            <span class="f-tag">Area Tribun</span>
            <span class="f-tag">16-20 Orang</span>
            <span class="f-tag">Outdoor</span>
          </div>
          <div class="f-price-row">
            <div><span class="f-price-val">1.200K</span><span class="f-price-unit">/jam</span></div>
            <a href="booking.php" class="f-book-btn">Book</a>
          </div>
        </div>
      </div>
      <div class="f-card reveal">
        <div class="f-card-img">
          <img src="https://images.unsplash.com/photo-1638868699118-73af322e79c9?w=800&auto=format&fit=crop&q=80" alt="Lapangan 3" loading="lazy">
          <div class="f-card-img-overlay"></div>
          <div class="f-card-badge-wrap">
            <div class="f-badge">Rumput Sintetis Pro</div>
            <div class="f-card-title">LAPANGAN 3</div>
          </div>
        </div>
        <div class="f-card-body">
          <div class="f-tags">
            <span class="f-tag">16-20 Orang</span>
            <span class="f-tag">8v8 / 10v10</span>
            <span class="f-tag">Outdoor</span>
          </div>
          <div class="f-price-row">
            <div><span class="f-price-val">1.000K</span><span class="f-price-unit">/jam</span></div>
            <a href="booking.php" class="f-book-btn">Book</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section class="gallery-sec" id="galeri">
  <div class="bg-deco-spotlight"><div class="spotlight-circle"></div></div>
  <div class="container">
    <div class="gallery-head reveal">
      <div class="gallery-title-wrap">
        <div class="gallery-bg-text">GALERI</div>
        <h2 class="gallery-fg-text">ARENA<span class="gallery-green-dot"></span></h2>
      </div>
    </div>
  </div>
  <div class="gallery-mosaic reveal">
    <div class="g-item" data-full="https://images.unsplash.com/photo-1546717003-caee5f93a9db?w=1400&auto=format&fit=crop&q=90">
      <img src="https://images.unsplash.com/photo-1546717003-caee5f93a9db?w=900&auto=format&fit=crop&q=80" alt="" loading="lazy">
      <div class="g-overlay"><div class="g-zoom">+</div></div>
      <div class="g-label">Lapangan 1</div>
    </div>
    <div class="g-item" data-full="https://images.unsplash.com/photo-1641029185333-7ed62a19d5f0?w=1400&auto=format&fit=crop&q=90">
      <img src="https://images.unsplash.com/photo-1641029185333-7ed62a19d5f0?w=900&auto=format&fit=crop&q=80" alt="" loading="lazy">
      <div class="g-overlay"><div class="g-zoom">+</div></div>
      <div class="g-label">Lapangan 3</div>
    </div>
    <div class="g-item" data-full="assets/izuddin-helmi-adnan-K5ChxJaheKI-unsplash.jpg">
      <img src="assets/izuddin-helmi-adnan-K5ChxJaheKI-unsplash.jpg" alt="" loading="lazy">
      <div class="g-overlay"><div class="g-zoom">+</div></div>
      <div class="g-label">Lapangan 2</div>
    </div>
    <div class="g-item" data-full="assets/tribun.png?w=1400&q=90">
      <img src="assets/tribun.png?w=900&q=80" alt="" loading="lazy">
      <div class="g-overlay"><div class="g-zoom">+</div></div>
      <div class="g-label">Area Tribun</div>
    </div>
    <div class="g-item" data-full="assets/fasilitas.png?w=1400&q=90">
      <img src="assets/fasilitas.png?w=900&q=80" alt="" loading="lazy">
      <div class="g-overlay"><div class="g-zoom">+</div></div>
      <div class="g-label">Fasilitas</div>
    </div>
  </div>
</section>

<section class="showcase-sec" id="fasilitas">
  <div class="container">
    <div class="showcase-head reveal">
      <div class="sec-label">⬡ Fasilitas Pendukung</div>
      <h2 class="fields-title-wrap">
        <span class="fields-fg-text">ELITE</span>
        <span class="facilities-bg-text">FACILITIES<span class="fields-green-dot"></span></span>
      </h2>
    </div>
    <div class="showcase-grid-layout">
      <!-- Card 1 -->
      <div class="sc-card reveal">
        <div class="sc-card-img-wrap">
          <img src="assets/parkir.png" alt="Parkir" class="sc-card-img" loading="lazy">
          <div class="sc-card-overlay"></div>
        </div>
        <div class="sc-card-body">
          <h3 class="sc-card-title">Large Parking Area</h3>
          <p class="sc-card-desc">Secure and spacious parking area monitored 24/7 by CCTV.</p>
        </div>
        <div class="sc-card-glow"></div>
      </div>
      <!-- Card 2 -->
      <div class="sc-card reveal">
        <div class="sc-card-img-wrap">
          <img src="assets/cafe.png" alt="Cafe" class="sc-card-img" loading="lazy">
          <div class="sc-card-overlay"></div>
        </div>
        <div class="sc-card-body">
          <h3 class="sc-card-title">Restaurant & Café</h3>
          <p class="sc-card-desc">Comfortable restaurant area to relax and dine after matches.</p>
        </div>
        <div class="sc-card-glow"></div>
      </div>
      <!-- Card 3 -->
      <div class="sc-card reveal">
        <div class="sc-card-img-wrap">
          <img src="https://i.pinimg.com/736x/5a/09/75/5a0975a1f56266edb107157f7158a6b3.jpg" alt="Ruang Ganti" class="sc-card-img" loading="lazy">
          <div class="sc-card-overlay"></div>
        </div>
        <div class="sc-card-body">
          <h3 class="sc-card-title">Changing Room</h3>
          <p class="sc-card-desc">Clean changing rooms and shower facilities kept it clean .</p>
        </div>
        <div class="sc-card-glow"></div>
      </div>
      <!-- Card 4 -->
      <div class="sc-card reveal">
        <div class="sc-card-img-wrap">
          <img src="https://i.pinimg.com/736x/d3/77/6e/d3776ef7e9e415c7d21822c9ebc1b51f.jpg" alt="Pencahayaan LED" class="sc-card-img" loading="lazy">
          <div class="sc-card-overlay"></div>
        </div>
        <div class="sc-card-body">
          <h3 class="sc-card-title">Full LED Lighting</h3>
          <p class="sc-card-desc">International standard LED spotlights for optimal night play.</p>
        </div>
        <div class="sc-card-glow"></div>
      </div>
    </div>
  </div>
</section>

<!-- ── IMAGE: FULL-SCREEN HORIZONTAL IMAGE ── -->
<section class="image-sec" id="image-banner">
  <div class="image-sep-top"></div>
  <img class="image-img" src="assets/image.jpeg" alt="Mini Soccer Action" loading="lazy">
  <div class="image-overlay"></div>
  <div class="image-content">
    <div class="image-text-wrap">
      <div class="image-label"><span class="ml-dot"></span>THE GAME NEVER STOPS</div>
      <h2 class="image-title">PLAY<br><span>BEYOND</span><br><span class="outline">LIMITS</span></h2>
    </div>
  </div>
  <div class="image-sep-bottom"></div>
</section>

<section class="pricing-sec" id="harga">
  <div class="bg-pricing-blurs">
    <div class="pricing-blur-ellipse"></div>
    <div class="pricing-radial-glow"></div>
  </div>
  <div class="bg-pricing-grid"></div>
  <div class="container">
    <div class="pricing-head reveal">
      <div class="sec-label">⬡ Harga Sewa</div>
      <div class="pricing-title-wrap">
        <h2 class="pricing-fg-text">AFFORDABLE</h2>
        <div class="pricing-bg-text">PRICE<span class="pricing-green-dot"></span></div>
      </div>
      <p class="sec-sub">Tidak ada biaya tersembunyi. Harga sama untuk siang maupun malam.</p>
    </div>
    <div class="pricing-grid">
      <div class="p-card-wrapper reveal">
        <div class="shine-border-bg">
          <div class="p-card-shine"></div>
        </div>
        <div class="p-card">
          <div class="p-tag">Lapangan 1 & 3</div>
          <div class="p-name">Rumput Sintetis Pro</div>
          <div class="p-price">
            <span class="p-cur">Rp</span>
            <span class="p-amount">1.000.000</span>
            <span class="p-per">/jam</span>
          </div>
          <div class="p-div"></div>
          <ul class="p-feats">
            <li>Rumput Sintetis Grade Pro</li>
            <li>Kapasitas 16-20 Orang</li>
            <li>Pencahayaan LED Penuh</li>
            <li>Ruang Ganti & Toilet Bersih</li>
            <li>Akses Restaurant</li>
          </ul>
          <a href="booking.php" class="p-btn">Pilih Lapangan</a>
        </div>
      </div>
      
      <div class="p-card-wrapper featured-wrapper reveal">
        <div class="shine-border-bg">
          <div class="p-card-shine featured-shine"></div>
        </div>
        <div class="p-card featured">
          <div class="p-tag">Lapangan 2</div>
          <div class="p-name">Premium dan Tribun</div>
          <div class="p-price">
            <span class="p-cur">Rp</span>
            <span class="p-amount">1.200.000</span>
            <span class="p-per">/jam</span>
          </div>
          <div class="p-div"></div>
          <ul class="p-feats">
            <li>Rumput Sintetis Premium</li>
            <li>Area Tribun Penonton</li>
            <li>Kapasitas 16-20 Orang</li>
            <li>Ruang Ganti & Toilet Eksklusif</li>
            <li>Akses Restaurant & Cafe</li>
          </ul>
          <a href="booking.php" class="p-btn solid">Pilih Lapangan 2</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── LOCATION SECTION ── -->
<section class="location-sec" id="lokasi">
  <!-- Dekorasi background -->
  <div class="loc-scanlines"></div>
  <div class="loc-grid-deco"></div>

  <div class="container">
    <div class="loc-layout">

      <!-- KOLOM KIRI: Info -->
      <div class="loc-left">
        <div class="loc-header reveal">
          <div class="loc-sup-label">
            <span class="loc-sup-dot"></span>
            Temukan Kami
          </div>
          <div class="loc-title-wrap">
            <span class="loc-title-bg">PINPOINT</span>
            <span class="loc-title-fg">LOKASI<span class="loc-green-dot"></span></span>
          </div>
        </div>

        <!-- Tab navigasi -->
        <div class="loc-tabs reveal" id="locTabs">
          <button class="loc-tab-btn active" data-tab="alamat"><i class="bi bi-geo-alt-fill"></i> Alamat</button>
          <button class="loc-tab-btn" data-tab="jadwal"><i class="bi bi-clock-fill"></i> Jadwal</button>
          <button class="loc-tab-btn" data-tab="kontak"><i class="bi bi-telephone-fill"></i> Kontak</button>
        </div>

        <!-- Panel: Alamat -->
        <div class="loc-panel active reveal" id="tab-alamat">
          <div class="loc-info-rows">
            <div class="loc-info-row">
              <div class="loc-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="loc-info-text">
                <div class="loc-info-label">Alamat Lengkap</div>
                <div class="loc-info-val">Jl. Kaliurang Km 7.5, Sinduharjo, Ngaglik, Sleman, Daerah Istimewa Yogyakarta 55581</div>
              </div>
            </div>
            <div class="loc-info-row">
              <div class="loc-info-icon"><i class="bi bi-building"></i></div>
              <div class="loc-info-text">
                <div class="loc-info-label">Kota & Provinsi</div>
                <div class="loc-info-val">Sleman, Daerah Istimewa Yogyakarta</div>
              </div>
            </div>
            <div class="loc-info-row">
              <div class="loc-info-icon"><i class="bi bi-compass-fill"></i></div>
              <div class="loc-info-text">
                <div class="loc-info-label">Lokasi Strategis</div>
                <div class="loc-info-val">Hanya ±8 menit dari UGM dan ±15 menit dari Malioboro</div>
              </div>
            </div>
            <div class="loc-info-row">
              <div class="loc-info-icon"><i class="bi bi-p-square-fill"></i></div>
              <div class="loc-info-text">
                <div class="loc-info-label">Area Parkir</div>
                <div class="loc-info-val">Parkir Gratis dan Terpantau CCTV 24 jam</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel: Jadwal -->
        <div class="loc-panel" id="tab-jadwal">
          <div class="loc-hours-grid">
            <div class="loc-hour-item">
              <div class="loc-hour-day">Senin – Jumat</div>
              <div class="loc-hour-time">08.00 – 24.00</div>
            </div>
            <div class="loc-hour-item">
              <div class="loc-hour-day">Sabtu – Minggu</div>
              <div class="loc-hour-time">08.00 – 24.00</div>
            </div>
            <div class="loc-hour-item">
              <div class="loc-hour-day">Hari Libur</div>
              <div class="loc-hour-time">TUTUP</div>
            </div>
            <div class="loc-hour-item">
              <div class="loc-hour-day">Booking Terakhir</div>
              <div class="loc-hour-time">Jam 23.00</div>
            </div>
          </div>
          <div class="loc-info-row" style="margin-top:14px;">
            <div class="loc-info-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div class="loc-info-text">
              <div class="loc-info-label">Info Operasional</div>
              <div class="loc-info-val">Buka setiap hari Senin sampai Minggu. Tutup pada Hari Libur Nasional.</div>
            </div>
          </div>
        </div>

        <!-- Panel: Kontak -->
        <div class="loc-panel" id="tab-kontak">
          <div class="loc-contact-list">
            <a href="https://wa.me/6281234567890" target="_blank" class="loc-contact-item">
              <div class="loc-contact-icon"><i class="bi bi-whatsapp"></i></div>
              <div>
                <div class="loc-contact-label">WhatsApp</div>
                <div class="loc-contact-val">+62 812-3456-7890</div>
              </div>
            </a>
            <a href="tel:+62274123456" class="loc-contact-item">
              <div class="loc-contact-icon"><i class="bi bi-telephone-fill"></i></div>
              <div>
                <div class="loc-contact-label">Telepon</div>
                <div class="loc-contact-val">(0274) 123-456</div>
              </div>
            </a>
            <a href="https://instagram.com/minifut.id" target="_blank" class="loc-contact-item">
              <div class="loc-contact-icon"><i class="bi bi-instagram"></i></div>
              <div>
                <div class="loc-contact-label">Instagram</div>
                <div class="loc-contact-val">@minifut.id</div>
              </div>
            </a>
            <a href="https://tiktok.com/@minifut.id" target="_blank" class="loc-contact-item">
              <div class="loc-contact-icon"><i class="bi bi-tiktok"></i></div>
              <div>
                <div class="loc-contact-label">TikTok</div>
                <div class="loc-contact-val">@minifut.id</div>
              </div>
            </a>
            <a href="mailto:info@minifut.id" class="loc-contact-item">
              <div class="loc-contact-icon"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <div class="loc-contact-label">Email</div>
                <div class="loc-contact-val">info@minifut.id</div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- KOLOM KANAN: Map -->
      <div class="loc-right reveal">
        <div class="loc-map-wrapper">
          <!-- Corner accents -->
          <div class="loc-map-corner tl"></div>
          <div class="loc-map-corner tr"></div>
          <div class="loc-map-corner bl"></div>
          <div class="loc-map-corner br"></div>

          <!-- Label overlay -->
          <div class="loc-map-label">
            <span class="loc-map-label-dot"></span>
            <span class="loc-map-label-text">MiniFut · Live</span>
          </div>

          <!-- Google Maps embed — Jl. Kaliurang Km 7.5 Sleman -->
          <iframe
            class="loc-map-iframe"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.4048!2d110.3905!3d-7.7494!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59456e6fcc6d%3A0x6ebce4ad8cc92f5b!2sJl.%20Kaliurang%20No.KM.7%2C%20Sinduharjo%2C%20Kec.%20Ngaglik%2C%20Kabupaten%20Sleman%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi MiniFut Yogyakarta"
          ></iframe>

          <!-- CTA buka Google Maps -->
          <div class="loc-map-cta">
            <a
              href="https://maps.google.com/?q=Jl.+Kaliurang+Km+7.5+Sinduharjo+Ngaglik+Sleman+Yogyakarta"
              target="_blank"
              class="loc-map-open-btn"
            >
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                <circle cx="12" cy="9" r="2.5"/>
              </svg>
              Buka Maps
            </a>
          </div>
        </div>

        <!-- Status bar -->
        <div class="loc-status-bar reveal">
          <div class="loc-status-item">
            <div class="loc-status-indicator"></div>
            <div>
              <div class="loc-status-text">Buka Sekarang</div>
              <div class="loc-status-meta">08.00 – 24.00 WIB</div>
            </div>
          </div>
          <div class="loc-status-item">
            <div class="loc-info-icon"><i class="bi bi-hexagon"></i></div>
            <div>
              <div class="loc-status-text">3 Lapangan Tersedia</div>
              <div class="loc-status-meta">Sleman, Yogyakarta</div>
            </div>
          </div>
          <a href="booking.php" class="loc-map-open-btn" style="position:static;box-shadow:none;font-size:0.68rem;padding:9px 16px;">
            Book Sekarang
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- VALUES SECTION -->
<section class="values-sec">
  <div class="bg-deco-noise"></div>
  <div class="values-glows">
    <div class="v-glow glow-1"></div>
    <div class="v-glow glow-2"></div>
    <div class="v-glow glow-3"></div>
  </div>
  <div class="container">
    <div style="text-align:center;" class="reveal">
      <div class="sec-label" style="letter-spacing: 5px; font-size: 0.7rem; margin-bottom: 6px;">DNA KAMI</div>
      <h2 class="sec-title" style="margin-bottom: 48px; font-family: 'Anton', sans-serif; font-size: clamp(2.4rem, 5.5vw, 4.5rem); font-weight: 400; color: var(--white); line-height: 1.1; text-transform: uppercase; letter-spacing: 6px; word-spacing: 12px;">THE CHAMPION</h2>
    </div>
    <div class="values-hex-grid">
      <div class="val-card reveal">
        <div class="val-num">01</div>
        <div class="val-name">Passion for the Game</div>
        <div class="val-desc">Setiap keputusan kami selalu dimulai dari satu pertanyaan: "Apakah ini akan membuat pengalaman bermain lebih baik?"</div>
        </div>
      <div class="val-card reveal">
        <div class="val-num">02</div>
        <div class="val-name">Quality Standards</div>
        <div class="val-desc">Dari kualitas lapangan hingga fasilitas pendukung, kami menjaga standar agar pengalaman bermain tetap konsisten dan menyenangkan.</div>
      </div>
      <div class="val-card reveal">
        <div class="val-num">03</div>
        <div class="val-name">Customer Experience</div>
        <div class="val-desc">Kemudahan booking, pelayanan yang responsif, dan lingkungan yang nyaman menjadi bagian dari komitmen kami kepada pelanggan.</div>
      </div>
      <div class="val-card reveal">
        <div class="val-num">04</div>
        <div class="val-name">Professional Service</div>
        <div class="val-desc">Kami mengutamakan pelayanan yang profesional, jadwal yang jelas, dan pengalaman booking yang dapat diandalkan.</div>
      </div>
      <div class="val-card reveal">
        <div class="val-num">05</div>
        <div class="val-name">Community & Sportsmanship</div>
        <div class="val-desc">MiniFut menjadi tempat bagi para pemain untuk berkumpul, bertanding, membangun kebersamaan, dan menjunjung sportivitas.</div>
      </div>
      <div class="val-card reveal">
        <div class="val-num">06</div>
        <div class="val-name">Integrity & Transparency</div>
        <div class="val-desc">Harga yang jelas, komunikasi yang terbuka, dan pelayanan yang jujur adalah prinsip yang selalu kami pegang.</div>
      </div>
    </div>
  </div>
</section>

<!-- ── GREEN-THEMED AWARDS MARQUEE DENGAN KONTEN INFORMATIF TENTANG ARENA ── -->
<section class="awards-sec">
  <div class="awards-track">
    <div class="award-item"><div><div class="award-name">RUMPUT FIFA GRADE PRO</div><div class="award-year">Teknologi Sintetis Premium Teruji</div></div></div>
    <div class="award-item"><div><div class="award-name">1200 LUX LED SYSTEM</div><div class="award-year">Sistem Lampu Sorot Tanpa Bayangan</div></div></div>
    <div class="award-item"><div><div class="award-name">EXCLUSIVELY DESIGNED TRIBUN</div><div class="award-year">Area Penonton Nyaman & Luas</div></div></div>
    <div class="award-item"><div><div class="award-name">BOOKING INSTAN ONLINE</div><div class="award-year">Reservasi Cepat & Aman 24/7</div></div></div>
    <div class="award-item"><div><div class="award-name">TOILET BERSIH & HIGIENIS</div><div class="award-year">Fasilitas Higienis & Terawat</div></div></div>
    <div class="award-item"><div><div class="award-name">RESTO & CAFE STRATEGIS</div><div class="award-year">Area Santai & Makan Minum Tim</div></div></div>
    <div class="award-item"><div><div class="award-name">RUMPUT FIFA GRADE A</div><div class="award-year">Teknologi Sintetis Premium Teruji</div></div></div>
    <div class="award-item"><div><div class="award-name">1200 LUX LED SYSTEM</div><div class="award-year">Sistem Lampu Sorot Tanpa Bayangan</div></div></div>
    <div class="award-item"><div><div class="award-name">EXCLUSIVELY DESIGNED TRIBUN</div><div class="award-year">Area Penonton Nyaman & Luas</div></div></div>
    <div class="award-item"><div><div class="award-name">BOOKING INSTAN ONLINE</div><div class="award-year">Reservasi Cepat & Aman 24/7</div></div></div>
    <div class="award-item"><div><div class="award-name">TOILET BERSIH & HIGIENIS</div><div class="award-year">Fasilitas Higienis & Terawat</div></div></div>
    <div class="award-item"><div><div class="award-name">RESTO & CAFE STRATEGIS</div><div class="award-year">Area Santai & Makan Minum Tim</div></div></div>
  </div>
  <div class="awards-track awards-track-rev">
    <div class="award-item"><div><div class="award-name">PARKIR MOTOR & MOBIL AMAN</div><div class="award-year">Kapasitas Luas Terpantau CCTV</div></div></div>
    <div class="award-item"><div><div class="award-name">RUANG GANTI BER-AC</div><div class="award-year">Kesejukan Maksimal Sebelum Laga</div></div></div>
    <div class="award-item"><div><div class="award-name">FASILITAS BOLA KOMPETISI</div><div class="award-year">Peralatan Standar Internasional</div></div></div>
    <div class="award-item"><div><div class="award-name">YOGYAKARTA PREMIER VENUE</div><div class="award-year">Lokasi Strategis Mudah Dijangkau</div></div></div>
    <div class="award-item"><div><div class="award-name">BASECAMP KOMUNITAS BOLA</div><div class="award-year">Wadah Silahturahmi & Sportivitas</div></div></div>
    <div class="award-item"><div><div class="award-name">SHOWER HANGAT & DINGIN</div><div class="award-year">Menyediakan Fasilitas yang Lengkap</div></div></div>
    <div class="award-item"><div><div class="award-name">PARKIR MOTOR & MOBIL AMAN</div><div class="award-year">Kapasitas Luas Terpantau CCTV</div></div></div>
    <div class="award-item"><div><div class="award-name">RUANG GANTI BER-AC</div><div class="award-year">Kesejukan Maksimal Sebelum Laga</div></div></div>
    <div class="award-item"><div><div class="award-name">FASILITAS BOLA KOMPETISI</div><div class="award-year">Peralatan Standar Internasional</div></div></div>
    <div class="award-item"><div><div class="award-name">YOGYAKARTA PREMIER VENUE</div><div class="award-year">Lokasi Strategis Mudah Dijangkau</div></div></div>
    <div class="award-item"><div><div class="award-name">BASECAMP KOMUNITAS BOLA</div><div class="award-year">Wadah Silahturahmi & Sportivitas</div></div></div>
    <div class="award-item"><div><div class="award-name">SHOWER HANGAT & DINGIN</div><div class="award-year">Menyediakan Fasilitas yang Lengkap</div></div></div>
  </div>
</section>

<!-- SECTION CTA DENGAN BACKGROUND HITAM -->
<section class="cta-sec">
  <div id="particles-js" style="position:absolute; width:100%; height:100%; top:0; left:0; z-index:0;"></div>
  <div class="container" style="position:relative;z-index:1">
    <div class="reveal">
      <div class="sec-label" style="display:flex;justify-content:center">⬡ Siap Bermain?</div>
      <h2 class="sec-title" style="margin-bottom: 24px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2.2rem, 5vw, 3.8rem); font-weight: 800; letter-spacing: 1px; text-align: center; color: var(--white); line-height: 1.2; text-transform: none !important;">
        Book Lapangan<br>Sekarang
      </h2>
      <p class="cta-sub">Jangan tunda lagi. Atur jadwal pertandinganmu dan nikmati pengalaman<br>mini soccer terbaik di Yogyakarta.</p>
      <a href="booking.php" class="btn-soft" style="display:inline-block">Mulai Booking</a>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <div class="footer-grid">
      <div>
        <a href="#" class="logo">MINI<em>FUT</em></a>
        <p class="footer-desc">Arena mini soccer outdoor premium di Yogyakarta. Rumput sintetis kualitas tinggi dengan fasilitas terlengkap untuk kenyamanan tim Anda.</p>
      </div>
      <div>
        <div class="f-title">Navigasi</div>
        <ul class="f-links">
          <li><a href="#lapangan">Lapangan</a></li>
          <li><a href="#fasilitas">Fasilitas</a></li>
          <li><a href="#harga">Harga</a></li>
          <li><a href="#lokasi">Lokasi</a></li>
          <li><a href="booking.php">Booking Lapangan</a></li>
        </ul>
      </div>
      <div>
        <div class="f-title">Informasi Operasional</div>
        <ul class="f-links">
          <li><strong>Buka Setiap Hari:</strong></li>
          <li>08.00 – 24.00 WIB</li>
          <li style="margin-top:8px"><strong>Lokasi:</strong></li>
          <li>Jl. Kaliurang Km 7.5, RT 04/RW 12</li>
          <li>Sinduharjo, Ngaglik, Sleman</li>
          <li>D.I. Yogyakarta 55581</li>
        </ul>
      </div>
      <div>
        <div class="f-title">Kontak Kami</div>
        <ul class="f-links">
          <li><strong>Telepon / WhatsApp:</strong></li>
          <li>+62 812-3456-7890</li>
          <li style="margin-top:8px"><strong>Email:</strong></li>
          <li>info@minifut.id</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© 2026 MiniFut</div>
      <div class="f-links" style="flex-direction:row; gap:20px;">
        <a href="https://instagram.com/minifut.id" target="_blank" rel="noopener noreferrer">Instagram</a>
        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer">WhatsApp</a>
        <a href="https://tiktok.com/@minifut.id" target="_blank" rel="noopener noreferrer">TikTok</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<!-- LIBRARY GSAP & SCROLLTRIGGER -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script>
const ballVertS = `
  varying vec3 vNormal;varying vec3 vPos;varying vec3 vWorldPos;
  void main(){
    vNormal=normalize(normalMatrix*normal);
    vPos=position;
    vWorldPos=(modelMatrix*vec4(position,1.)).xyz;
    gl_Position=projectionMatrix*modelViewMatrix*vec4(position,1.);
  }`;
    
const ballFragS = `
  uniform float uTime;
  varying vec3 vNormal;
  varying vec3 vPos;
  varying vec3 vWorldPos;

  // 3D hash for noise
  float hash3(vec3 p) {
    p = fract(p * vec3(127.1, 311.7, 74.7));
    return fract(sin(dot(p, vec3(12.9898, 78.233, 37.719))) * 43758.5453);
  }

  // 3D value noise
  float noise3(vec3 p) {
    vec3 i = floor(p);
    vec3 f = fract(p);
    vec3 u = f * f * (3.0 - 2.0 * f);
    return mix(
      mix(
        mix(hash3(i + vec3(0.0,0.0,0.0)), hash3(i + vec3(1.0,0.0,0.0)), u.x),
        mix(hash3(i + vec3(0.0,1.0,0.0)), hash3(i + vec3(1.0,1.0,0.0)), u.x),
        u.y
      ),
      mix(
        mix(hash3(i + vec3(0.0,0.0,1.0)), hash3(i + vec3(1.0,0.0,1.0)), u.x),
        mix(hash3(i + vec3(0.0,1.0,1.0)), hash3(i + vec3(1.0,1.0,1.0)), u.x),
        u.y
      ),
      u.z
    );
  }

  // Generates curved panel lines/grooves (FIFA star/curved geometry style)
  float getSoccerSeams(vec3 p) {
    float val1 = abs(sin(p.x * 3.14 + sin(p.y * 2.0)) * cos(p.z * 3.14));
    float val2 = abs(cos(p.y * 3.14 + sin(p.z * 2.0)) * sin(p.x * 3.14));
    float val3 = abs(sin(p.z * 3.14 + sin(p.x * 2.0)) * cos(p.y * 3.14));
    
    float combined = val1 * val2 + val2 * val3 + val3 * val1;
    float seam = smoothstep(0.09, 0.04, combined);
    
    float micro = smoothstep(0.03, 0.0, abs(sin(p.x * 8.0) * sin(p.y * 8.0) * sin(p.z * 8.0)) - 0.02);
    return clamp(seam + micro * 0.4, 0.0, 1.0);
  }

  // Modern FIFA sweeping graphics (neon green, dark teal, and black)
  vec3 getGraphics(vec3 p) {
    float angle = atan(p.y, p.x) + p.z * 1.5;
    float sweep1 = smoothstep(0.12, 0.0, abs(sin(angle * 2.0) - 0.35));
    float sweep2 = smoothstep(0.07, 0.0, abs(sin(angle * 2.0 + 1.5) - 0.65));
    
    vec3 neonGreen = vec3(0.0, 1.0, 0.53);
    vec3 darkTeal = vec3(0.0, 0.35, 0.45);
    
    return mix(vec3(0.0), neonGreen * sweep1 + darkTeal * sweep2, clamp(sweep1 + sweep2, 0.0, 1.0));
  }

  void main() {
    vec3 N = normalize(vNormal);
    vec3 pos = normalize(vPos);

    // 1. High-Frequency Pebbled Leather surface (noise-based bump)
    float pebbleCoord = 800.0;
    float pebble = noise3(pos * pebbleCoord);
    
    pebble += 0.5 * noise3(pos * (pebbleCoord * 2.0));
    pebble += 0.25 * noise3(pos * (pebbleCoord * 4.0));
    pebble = pebble / 1.75;
    
    vec3 perturbedN = normalize(N + (pebble - 0.5) * 0.07 * N);

    // 2. Seams mask (grooves)
    float seams = getSoccerSeams(pos);

    // 3. Base Leather color (Pearl White)
    vec3 baseLeather = vec3(0.96, 0.95, 0.92);

    // 4. FIFA sweeping graphic stripes
    vec3 graphics = getGraphics(pos);
    
    vec3 color = mix(baseLeather, graphics, clamp(length(graphics) * 0.85, 0.0, 1.0));
    color = mix(color, vec3(0.15, 0.15, 0.15), seams);

    // 5. Lighting calculations
    vec3 viewDir = normalize(-vWorldPos + vec3(0.0, 0.0, 10.0));
    vec3 lightDir = normalize(vec3(3.0, 5.0, 6.0));
    vec3 halfDir = normalize(lightDir + viewDir);

    float diff = max(dot(perturbedN, lightDir), 0.0);
    float spec = pow(max(dot(perturbedN, halfDir), 0.0), 55.0) * 0.6;
    float rim = pow(1.0 - max(dot(N, viewDir), 0.0), 3.0) * 0.4;

    vec3 finalColor = color * 0.22                           // Ambient
                    + color * vec3(1.0, 0.98, 0.95) * diff * 0.95 // Diffuse
                    + vec3(1.0) * spec                           // Specular
                    + vec3(0.0, 1.0, 0.55) * rim;                // Branding green rim glow

    finalColor = pow(finalColor, vec3(0.9));
    gl_FragColor = vec4(finalColor, 1.0);
  }
`;

// --- SINKRONISASI PROGRESS SCROLL GSAP KE THREEJS ---
let heroScrollProgress = 0;
let targetHeroScrollProgress = 0;

gsap.registerPlugin(ScrollTrigger);

document.querySelectorAll('.btn-p, .btn-s, .btn-soft, .nav-cta, .nav-btn-dark, .nav-btn-glow, .nav-btn-logout, .nav-instagram-link, .nav-whatsapp-link, .nav-tiktok-link').forEach(btn => {
  btn.addEventListener('mousemove', e => {
    const rect = btn.getBoundingClientRect();
    const relX = e.clientX - (rect.left + rect.width / 2);
    const relY = e.clientY - (rect.top + rect.height / 2);
    gsap.to(btn, {x: relX * 0.25, y: relY * 0.25, duration: 0.3, ease: "power2.out"});
  });
  btn.addEventListener('mouseleave', () => {
    gsap.to(btn, {x: 0, y: 0, duration: 0.7, ease: "elastic.out(1, 0.3)"});
  });
});

window.addEventListener('scroll', () => {
  const nav = document.getElementById('nav');
  const showcase = document.querySelector('.premium-showcase-container');
  const showcaseRect = showcase.getBoundingClientRect();
  
  if (window.scrollY > window.innerHeight - 100) {
    nav.classList.add('shrunk');
  } else {
    nav.classList.remove('shrunk');
  }

  if (showcaseRect.top <= 60 && showcaseRect.bottom >= 60) {
    nav.classList.add('showcase-active');
    nav.classList.remove('stuck');
  } else {
    nav.classList.remove('showcase-active');
    nav.classList.toggle('stuck', window.scrollY > 60); 
  }
}, {passive: true});

/* --- GSAP FEATURE 2: SMOOTH SCROLL REVEAL (STAGGER BATCH) --- */
gsap.set(".reveal", { y: 40, opacity: 0 });
ScrollTrigger.batch(".reveal", {
  onEnter: batch => {
    gsap.to(batch, { y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "power3.out", overwrite: true });
  },
  once: true,
  start: "top 85%"
});

/* ══════════════════════════════════════════════
   HERO CONTENT — ENTRANCE ANIMATION
   Eyebrow fade-in → title cinematic flip-up + blur-to-focus (stagger per kata)
   → garis aksen menyala dari tengah
   Delay: 0.5s (memberi ruang shader & stadium 3D untuk init)
   ══════════════════════════════════════════════ */
(function initHeroEntrance() {
  window.heroTextState = [
    { opacity: 0, y: 110, rotateX: -55, blur: 10 },
    { opacity: 0, y: 110, rotateX: -55, blur: 10 },
    { opacity: 0, y: 110, rotateX: -55, blur: 10 }
  ];
  window.triggerHeroTextUpdate = true;

  gsap.set(".hero-underline", { scaleX: 0 });

  const heroEnterTl = gsap.timeline({ delay: 0.5 });
  heroEnterTl
    .to(window.heroTextState, {
      opacity: 1,
      y: 0,
      rotateX: 0,
      blur: 0,
      duration: 1.2,
      ease: "power4.out",
      stagger: 0.12,
      onUpdate: () => {
        window.triggerHeroTextUpdate = true;
      }
    })
    .to(".hero-underline", {
      opacity: 1,
      scaleX: 1,
      duration: 0.9,
      ease: "power3.out"
    }, "-=0.5");
})();
/* ══ END HERO ENTRANCE ══ */

/* ── IMAGE-SEC: Parallax image + text fade ── */
gsap.to(".image-img", {
  scrollTrigger: {
    trigger: ".image-sec",
    start: "top bottom",
    end: "bottom top",
    scrub: 1.2
  },
  y: -80,
  ease: "none"
});

const tlImage = gsap.timeline({
  scrollTrigger: {
    trigger: ".image-sec",
    start: "top 70%",
    toggleActions: "play none none none"
  }
});
tlImage.fromTo(".image-label", 
  { x: -50, opacity: 0 }, 
  { x: 0, opacity: 1, duration: 0.8, ease: "power2.out" }
)
.fromTo(".image-title", 
  { y: 60, opacity: 0 }, 
  { y: 0, opacity: 1, duration: 0.9, ease: "power3.out" }, 
  "-=0.5"
);

/* ══════════════════════════════════════════════
   LOCATION SECTION — GSAP ANIMATIONS
   ══════════════════════════════════════════════ */
(function initLocationSection() {

  /* 1. Title PINPOINT: teks outline masuk dari kiri, teks solid dari kanan */
  const locTl = gsap.timeline({
    scrollTrigger: {
      trigger: ".location-sec",
      start: "top 75%",
      toggleActions: "play none none none"
    }
  });

  locTl
    .fromTo(".loc-title-bg",
      { x: -60, opacity: 0 },
      { x: 0, opacity: 1, duration: 0.9, ease: "power3.out" }
    )
    .fromTo(".loc-title-fg",
      { x: 60, opacity: 0 },
      { x: 0, opacity: 1, duration: 0.9, ease: "power3.out" },
      "-=0.7"
    )
    .fromTo(".loc-sup-label",
      { y: -20, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.6, ease: "power2.out" },
      "<"
    );

  /* 2. Info rows: stagger masuk dari bawah */
  ScrollTrigger.create({
    trigger: ".location-sec",
    start: "top 65%",
    once: true,
    onEnter: () => {
      gsap.fromTo(".loc-info-row",
        { y: 32, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.6, ease: "power3.out", stagger: 0.1 }
      );
      gsap.fromTo(".loc-hour-item",
        { y: 28, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.55, ease: "power2.out", stagger: 0.08 }
      );
      gsap.fromTo(".loc-contact-item",
        { x: -30, opacity: 0 },
        { x: 0, opacity: 1, duration: 0.55, ease: "power2.out", stagger: 0.09 }
      );
    }
  });

  /* 3. Map wrapper: scale + fade masuk */
  gsap.fromTo(".loc-map-wrapper",
    { scale: 0.94, opacity: 0 },
    {
      scale: 1, opacity: 1,
      duration: 1.1, ease: "power3.out",
      scrollTrigger: {
        trigger: ".loc-map-wrapper",
        start: "top 80%",
        toggleActions: "play none none none"
      }
    }
  );

  /* 4. Corner accents: stagger masuk */
  gsap.fromTo(".loc-map-corner",
    { scale: 0, opacity: 0 },
    {
      scale: 1, opacity: 1,
      duration: 0.5, ease: "back.out(2)", stagger: 0.07,
      scrollTrigger: {
        trigger: ".loc-map-wrapper",
        start: "top 78%"
      }
    }
  );

  /* 5. Status bar slide dari bawah */
  gsap.fromTo(".loc-status-bar",
    { y: 30, opacity: 0 },
    {
      y: 0, opacity: 1,
      duration: 0.8, ease: "power3.out",
      scrollTrigger: {
        trigger: ".loc-status-bar",
        start: "top 88%"
      }
    }
  );

  /* 6. Ambient glow parallax */
  gsap.to(".loc-ambient",
    {
      y: -60,
      ease: "none",
      scrollTrigger: {
        trigger: ".location-sec",
        start: "top bottom",
        end: "bottom top",
        scrub: 1.5
      }
    }
  );
  gsap.to(".loc-ambient-r",
    {
      y: 60,
      ease: "none",
      scrollTrigger: {
        trigger: ".location-sec",
        start: "top bottom",
        end: "bottom top",
        scrub: 2
      }
    }
  );

  /* 7. Mouse-hover radial glow pada info-row */
  document.querySelectorAll('.loc-info-row').forEach(row => {
    row.addEventListener('mousemove', e => {
      const rect = row.getBoundingClientRect();
      row.style.setProperty('--mx', `${((e.clientX - rect.left) / rect.width) * 100}%`);
      row.style.setProperty('--my', `${((e.clientY - rect.top) / rect.height) * 100}%`);
    });
  });

})();

/* ── TAB SWITCHING LOGIC untuk Location Section ── */
(function initLocTabs() {
  const tabs = document.querySelectorAll('.loc-tab-btn');
  if (!tabs.length) return;

  tabs.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tab;

      // Update active tab button
      tabs.forEach(t => t.classList.remove('active'));
      btn.classList.add('active');

      // Animate out current panel
      const currentPanel = document.querySelector('.loc-panel.active');
      if (currentPanel) {
        gsap.to(currentPanel, {
          y: -12, opacity: 0, duration: 0.22, ease: "power2.in",
          onComplete: () => {
            currentPanel.classList.remove('active');
            currentPanel.style.display = 'none';

            // Animate in new panel
            const nextPanel = document.getElementById('tab-' + target);
            if (nextPanel) {
              nextPanel.style.display = 'block';
              nextPanel.classList.add('active');
              gsap.fromTo(nextPanel,
                { y: 16, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.35, ease: "power2.out" }
              );
              // Re-trigger stagger for newly shown rows
              if (target === 'alamat') {
                gsap.fromTo(nextPanel.querySelectorAll('.loc-info-row'),
                  { y: 20, opacity: 0 },
                  { y: 0, opacity: 1, duration: 0.4, ease: "power2.out", stagger: 0.07 }
                );
              } else if (target === 'jadwal') {
                gsap.fromTo(nextPanel.querySelectorAll('.loc-hour-item, .loc-info-row'),
                  { y: 20, opacity: 0 },
                  { y: 0, opacity: 1, duration: 0.4, ease: "power2.out", stagger: 0.07 }
                );
              } else if (target === 'kontak') {
                gsap.fromTo(nextPanel.querySelectorAll('.loc-contact-item'),
                  { x: -24, opacity: 0 },
                  { x: 0, opacity: 1, duration: 0.4, ease: "power2.out", stagger: 0.07 }
                );
              }
            }
          }
        });
      }
    });
  });
})();

/* ── FIX: Semua loc-panel non-active dimulai tersembunyi ── */
document.querySelectorAll('.loc-panel:not(.active)').forEach(p => {
  p.style.display = 'none';
});

document.querySelectorAll('.sc-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    card.style.setProperty('--mx', `${(x / rect.width) * 100}%`);
    card.style.setProperty('--my', `${(y / rect.height) * 100}%`);
    
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    const rotateX = ((y - centerY) / centerY) * -8;
    const rotateY = ((x - centerX) / centerX) * 8;
    
    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
    card.style.transition = 'none';
  });
  
  card.addEventListener('mouseleave', () => {
    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
    card.style.transition = 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.3s ease, box-shadow 0.4s ease';
  });
});

/* GALLERY LIGHTBOX LOGIC */
const lb=document.getElementById('lb'),lbImg=document.getElementById('lb-img');
document.querySelectorAll('.g-item').forEach(item=>{
  item.addEventListener('click',()=>{
    lbImg.src=item.dataset.full;
    const label=item.querySelector('.g-label');
    lbImg.alt=label?label.textContent:'Gallery Image';
    lb.classList.add('open');
  });
});
document.getElementById('lb-close').onclick=()=>lb.classList.remove('open');
lb.addEventListener('click',e=>{if(e.target===lb)lb.classList.remove('open');});
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&lb.classList.contains('open'))lb.classList.remove('open');});

const co=new IntersectionObserver(es=>es.forEach(e=>{
  if(!e.isIntersecting)return;
  const el=e.target,target=+el.dataset.target,suffix=el.dataset.suffix||'';
  const startVal=el.dataset.start ? +el.dataset.start : 0;
  let v=startVal;
  const duration = 2500;
  const step=Math.max(1, (target-startVal)/(duration/16));
  const t=setInterval(()=>{
    v+=step;
    if(v>=target){
      v=target;
      clearInterval(t);
    }
    el.textContent=Math.floor(v)+suffix;
  },16);
  co.unobserve(el);
}),{threshold:0.5});
document.querySelectorAll('.counter').forEach(el=>co.observe(el));

/* PARTICLES CTA LOGIC */
if(typeof particlesJS !== 'undefined') {
  particlesJS("particles-js", {
    "particles": {
      "number": { "value": 100, "density": { "enable": true, "value_area": 800 } },
      "color": { "value": "#00ff88" },
      "shape": { "type": "circle" },
      "opacity": { "value": 0.8, "random": true, "anim": { "enable": true, "speed": 1, "opacity_min": 0.2, "sync": false } },
      "size": { "value": 4, "random": true, "anim": { "enable": true, "speed": 2, "size_min": 1, "sync": false } },
      "line_linked": { "enable": false },
      "move": { "enable": true, "speed": 1.5, "direction": "none", "random": true, "straight": false, "out_mode": "out", "bounce": false }
    },
    "interactivity": {
      "detect_on": "window",
      "events": { "onhover": { "enable": true, "mode": "repulse" }, "onclick": { "enable": false }, "resize": true },
      "modes": { "repulse": { "distance": 120, "duration": 0.4 } }
    },
    "retina_detect": true
  });
}

document.querySelectorAll('.val-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const r = card.getBoundingClientRect();
    card.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
    card.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
  });
});

// --- 3D HERO SHADER BACKGROUND LOGIC (INTEGRATED PORT) ---
(function initShaderBackground() {
  const canvas = document.getElementById('shader-canvas');
  if (!canvas) return;

  const _dpr = Math.min(window.devicePixelRatio, window.innerWidth <= 768 ? 1.0 : 1.5);
  const renderer = new THREE.WebGLRenderer({ canvas, antialias: _dpr <= 1, powerPreference: 'high-performance' });
  renderer.setPixelRatio(_dpr);
  renderer.setSize(window.innerWidth, window.innerHeight);

  const scene = new THREE.Scene();
  const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);

  const uniforms = {
    resolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
    time: { value: 0.0 },
    xScale: { value: 1.0 },
    yScale: { value: 0.5 },
    distortion: { value: 0.05 },
  };

  const vertexShader = `
    varying vec2 vUv;
    void main() {
      vUv = uv;
      gl_Position = vec4(position, 1.0);
    }
  `;

  const fragmentShader = `
    uniform vec2 resolution;
    uniform float time;
    uniform float xScale;
    uniform float yScale;
    uniform float distortion;

    void main() {
      vec2 p = (gl_FragCoord.xy * 2.0 - resolution) / min(resolution.x, resolution.y);
      
      float d = length(p) * distortion;
      
      float rx = p.x * (1.0 + d);
      float gx = p.x;
      float bx = p.x * (1.0 - d);

      float w1 = 0.05 / abs(p.y + sin((rx + time) * xScale) * yScale);
      float w2 = 0.05 / abs(p.y + sin((gx + time) * xScale) * yScale);
      float w3 = 0.05 / abs(p.y + sin((bx + time) * xScale) * yScale);
      
      vec3 col1 = vec3(0.0, w1 * 0.5, w1 * 0.15);
      vec3 col2 = vec3(w2 * 0.15, w2 * 0.95, w2 * 0.3);
      vec3 col3 = vec3(0.0, w3 * 0.7, w3 * 0.5);
      
      vec3 finalColor = col1 + col2 + col3;
      
      gl_FragColor = vec4(finalColor, 1.0);
    }
  `;

  const geometry = new THREE.PlaneGeometry(2, 2);
  const material = new THREE.ShaderMaterial({
    vertexShader,
    fragmentShader,
    uniforms,
    depthWrite: false,
    depthTest: false
  });

  const mesh = new THREE.Mesh(geometry, material);
  scene.add(mesh);

  function handleResize() {
    const w = window.innerWidth;
    const h = window.innerHeight;
    renderer.setSize(w, h);
    uniforms.resolution.value.set(w, h);
  }
  window.addEventListener('resize', handleResize);

  let _shaderVisible = true;
  const _shaderObs = new IntersectionObserver(([entry]) => { _shaderVisible = entry.isIntersecting; }, { threshold: 0 });
  _shaderObs.observe(canvas);

  function animate() {
    requestAnimationFrame(animate);
    if (!_shaderVisible) return;
    uniforms.time.value += 0.01;
    renderer.render(scene, camera);
  }
  animate();
})();

// --- 3D HERO CANVAS LOGIC ---
(function initHeroThree() {
  const canvas = document.getElementById('three-canvas');
  if (!canvas) return;
  const _heroDpr = Math.min(window.devicePixelRatio, window.innerWidth <= 768 ? 1.0 : 1.5);
  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: _heroDpr <= 1, powerPreference: 'high-performance' });
  renderer.setPixelRatio(_heroDpr);

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
  let baseY = 8;

  function updateResponsive() {
    const w = canvas.parentElement.offsetWidth, h = canvas.parentElement.offsetHeight;
    renderer.setSize(w, h);
    camera.aspect = w / h;
    if (w < 768) { camera.position.z = 24; baseY = 12; } 
    else { camera.position.z = 14; baseY = 8; }
    camera.updateProjectionMatrix();
  }

  window.addEventListener('resize', updateResponsive);
  updateResponsive();

  camera.position.set(0, baseY, camera.position.z);
  camera.lookAt(0, 0, 0);

  const fieldGeo = new THREE.PlaneGeometry(14, 9, 32, 20);
  const fieldMat = new THREE.MeshStandardMaterial({ color: 0x002211, roughness: 0.8, metalness: 0.05 });
  const field = new THREE.Mesh(fieldGeo, fieldMat);
  field.rotation.x = -Math.PI / 2;
  scene.add(field);

  function addLine(points, color = 0x00ff88, opacity = 0.5) {
    const geo = new THREE.BufferGeometry().setFromPoints(points);
    const mat = new THREE.LineBasicMaterial({ color, transparent: true, opacity });
    scene.add(new THREE.Line(geo, mat));
  }

  const bL = 7, bW = 4.5;
  addLine([new THREE.Vector3(-bL, 0.02, -bW), new THREE.Vector3(bL, 0.02, -bW), new THREE.Vector3(bL, 0.02, bW), new THREE.Vector3(-bL, 0.02, bW), new THREE.Vector3(-bL, 0.02, -bW)]);
  addLine([new THREE.Vector3(0, 0.02, -bW), new THREE.Vector3(0, 0.02, bW)]);
  
  const circPts = [];
  for (let i = 0; i <= 64; i++) { const a = (i / 64) * Math.PI * 2; circPts.push(new THREE.Vector3(Math.cos(a) * 1.8, 0.02, Math.sin(a) * 1.8)); }
  addLine(circPts);
  
  const dotGeo = new THREE.CircleGeometry(0.12, 16);
  const dotMat = new THREE.MeshStandardMaterial({ color: 0x00ff88, emissive: 0x00ff88, emissiveIntensity: 0.8 });
  const dot = new THREE.Mesh(dotGeo, dotMat);
  dot.rotation.x = -Math.PI / 2;
  dot.position.y = 0.02;
  scene.add(dot);
  
  addLine([new THREE.Vector3(-bL, 0.02, -1.5), new THREE.Vector3(-bL + 2, 0.02, -1.5), new THREE.Vector3(-bL + 2, 0.02, 1.5), new THREE.Vector3(-bL, 0.02, 1.5)]);
  addLine([new THREE.Vector3(bL, 0.02, -1.5), new THREE.Vector3(bL - 2, 0.02, -1.5), new THREE.Vector3(bL - 2, 0.02, 1.5), new THREE.Vector3(bL, 0.02, 1.5)]);
  
  for (let side = -1; side <= 1; side += 2) {
    const arcPts = [];
    for (let i = 0; i <= 32; i++) {
      const a = (i / 32) * Math.PI - Math.PI / 2;
      arcPts.push(new THREE.Vector3(side * (bL - 2) + Math.cos(a) * -side * 1.5, 0.02, Math.sin(a) * 1.5));
    }
    addLine(arcPts, 0x00ff88, 0.3);
  }

  const postGeo = new THREE.CylinderGeometry(0.03, 0.03, 1.2, 8);
  const barGeo = new THREE.CylinderGeometry(0.03, 0.03, 2, 8);
  const postMat = new THREE.MeshStandardMaterial({ color: 0x00ff88, emissive: 0x00ff88, emissiveIntensity: 0.8 });

  function addGoal(xPos) {
    const goal = new THREE.Group();
    const post1 = new THREE.Mesh(postGeo, postMat); post1.position.set(0, 0.6, -1); goal.add(post1);
    const post2 = new THREE.Mesh(postGeo, postMat); post2.position.set(0, 0.6, 1); goal.add(post2);
    const crossbar = new THREE.Mesh(barGeo, postMat); crossbar.rotation.x = Math.PI / 2; crossbar.position.set(0, 1.2, 0); goal.add(crossbar);

    const supportGeo = new THREE.CylinderGeometry(0.02, 0.02, 1.4, 8);
    const supportMat = new THREE.MeshStandardMaterial({ color: 0x00ff88, emissive: 0x00ff88, emissiveIntensity: 0.3, transparent: true, opacity: 0.6 });
    const dir = xPos > 0 ? 1 : -1;
    
    const support1 = new THREE.Mesh(supportGeo, supportMat); support1.rotation.z = (Math.PI / 4) * dir; support1.position.set(0.5 * dir, 0.5, -1); goal.add(support1);
    const support2 = new THREE.Mesh(supportGeo, supportMat); support2.rotation.z = (Math.PI / 4) * dir; support2.position.set(0.5 * dir, 0.5, 1); goal.add(support2);
    const backBar = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.02, 2, 8), supportMat); backBar.rotation.x = Math.PI / 2; backBar.position.set(1 * dir, 0, 0); goal.add(backBar);
    const netBottom1 = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.02, 1, 8), supportMat); netBottom1.rotation.z = Math.PI / 2; netBottom1.position.set(0.5 * dir, 0, -1); goal.add(netBottom1);
    const netBottom2 = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.02, 1, 8), supportMat); netBottom2.rotation.z = Math.PI / 2; netBottom2.position.set(0.5 * dir, 0, 1); goal.add(netBottom2);

    goal.position.set(xPos, 0, 0);
    scene.add(goal);
  }

  addGoal(-bL); addGoal(bL);

  scene.add(new THREE.AmbientLight(0x001a08, 2));
  const dirLight = new THREE.DirectionalLight(0x00ff88, 1.2); dirLight.position.set(5, 10, 5); scene.add(dirLight);
  const pointLight1 = new THREE.PointLight(0x00ff88, 2, 12); pointLight1.position.set(-4, 5, 0); scene.add(pointLight1);
  const pointLight2 = new THREE.PointLight(0x00cc66, 1.5, 12); pointLight2.position.set(4, 5, 0); scene.add(pointLight2);
  
  [[-bL + 1, 4, -bW + 1], [bL - 1, 4, -bW + 1], [-bL + 1, 4, bW - 1], [bL - 1, 4, bW - 1]].forEach(([x, y, z]) => {
    const sl = new THREE.PointLight(0xffeedd, 0.6, 8);
    sl.position.set(x, y, z);
    scene.add(sl);
  });

  let mouseX = 0, mouseY = 0;
  document.addEventListener('mousemove', e => { mouseX = (e.clientX / window.innerWidth - 0.5) * 2; mouseY = (e.clientY / window.innerHeight - 0.5) * 2; });

  let t = 0;
  let _heroVisible = true;
  const _heroObs = new IntersectionObserver(([entry]) => { _heroVisible = entry.isIntersecting; }, { threshold: 0 });
  _heroObs.observe(canvas);

  function animate() {
    requestAnimationFrame(animate);
    if (!_heroVisible) return;
    t += 0.01;

    heroScrollProgress += (targetHeroScrollProgress - heroScrollProgress) * 0.1;

    camera.position.x += (mouseX * 3 - camera.position.x) * 0.02;
    camera.position.y += (-mouseY * 1 + baseY - camera.position.y) * 0.02; 
    camera.lookAt(0, 0, 0);

    fieldMat.emissive = new THREE.Color(0x001a08);
    fieldMat.emissiveIntensity = 0.3 + Math.sin(t * 0.5) * 0.1;
    renderer.render(scene, camera);
  }
  animate();
})();

// --- 3D SHOWCASE MESH OVERLAY SCROLLING CANVAS LOGIC ---
(function() {
  const canvasScroll = document.getElementById('ball-scroll-canvas');
  if (!canvasScroll) return;

  const _ballDpr = Math.min(window.devicePixelRatio, window.innerWidth <= 768 ? 1.0 : 1.5);
  const rendererS = new THREE.WebGLRenderer({ canvas: canvasScroll, antialias: _ballDpr <= 1, alpha: true, powerPreference: 'high-performance' });
  rendererS.setPixelRatio(_ballDpr);
  rendererS.toneMapping = THREE.ACESFilmicToneMapping;
  rendererS.toneMappingExposure = 1.2;

  const sceneS = new THREE.Scene();
  const cameraS = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 200);
  cameraS.position.set(0, 0, 10);

  function resizeS() {
    rendererS.setSize(window.innerWidth, window.innerHeight);
    cameraS.aspect = window.innerWidth / window.innerHeight;
    cameraS.updateProjectionMatrix();
  }
  window.addEventListener('resize', resizeS);
  resizeS();

  const _ballSegs = window.innerWidth <= 768 ? 32 : 48;
  const ballSGeo = new THREE.SphereGeometry(1.6, _ballSegs, _ballSegs);
  const ballSMat = new THREE.ShaderMaterial({ vertexShader: ballVertS, fragmentShader: ballFragS, uniforms: { uTime: { value: 0 } } });
  const scrollBall = new THREE.Mesh(ballSGeo, ballSMat);
  
  scrollBall.position.set(0, 0, 0);
  scrollBall.scale.setScalar(1.0);
  sceneS.add(scrollBall);

  sceneS.add(new THREE.AmbientLight(0x001a0d, 2.5));
  const dlS = new THREE.DirectionalLight(0x00ff88, 1.8);
  dlS.position.set(3, 5, 6);
  sceneS.add(dlS);

  // ── INTEGRASI INTERAKSI DRAG BOLA SECARA INTERAKTIF ──
  let isDragging = false;
  let previousMousePosition = { x: 0, y: 0 };
  let targetRotation = { x: 0, y: 0 };

  let currentRotation = { x: 0, y: 0, z: 0 };
  let lastRx = 0;
  let lastRy = 0;
  let lastRz = 0;
  let targetRotX = 0;
  let targetRotY = 0;
  let targetRotZ = 0;

  function lerpAngle(current, target, speed) {
    let diff = target - current;
    diff = Math.atan2(Math.sin(diff), Math.cos(diff));
    return current + diff * speed;
  }

  const containerShowcase = document.querySelector('.premium-showcase-container');

  containerShowcase.addEventListener('mousedown', e => {
    e.preventDefault();
    isDragging = true;
    previousMousePosition = { x: e.clientX, y: e.clientY };
    targetRotation.x = scrollBall.rotation.x;
    targetRotation.y = scrollBall.rotation.y;
  });

  window.addEventListener('mousemove', e => {
    if (!isDragging) return;
    const deltaMove = {
      x: e.clientX - previousMousePosition.x,
      y: e.clientY - previousMousePosition.y
    };
    
    targetRotation.y += deltaMove.x * 0.005;
    targetRotation.x += deltaMove.y * 0.005;
    previousMousePosition = { x: e.clientX, y: e.clientY };
  });

  window.addEventListener('mouseup', () => { isDragging = false; });

  containerShowcase.addEventListener('touchstart', e => {
    if (e.touches.length > 0) {
      e.preventDefault();
      isDragging = true;
      previousMousePosition = { x: e.touches[0].clientX, y: e.touches[0].clientY };
      targetRotation.x = scrollBall.rotation.x;
      targetRotation.y = scrollBall.rotation.y;
    }
  }, { passive: false });

  window.addEventListener('touchmove', e => {
    if (!isDragging || e.touches.length === 0) return;
    const deltaMove = {
      x: e.touches[0].clientX - previousMousePosition.x,
      y: e.touches[0].clientY - previousMousePosition.y
    };
    
    targetRotation.y += deltaMove.x * 0.005;
    targetRotation.x += deltaMove.y * 0.005;
    previousMousePosition = { x: e.touches[0].clientX, y: e.touches[0].clientY };
  }, { passive: true });

  window.addEventListener('touchend', () => { isDragging = false; });

  function getResponsiveParams() {
    const w = window.innerWidth;
    if (w <= 600) {
      return { hero: 0.11, sec2: 0.75, sec3: 0.95, sec4: 0.95, xOffset: 0.45 };
    } else if (w <= 1024) {
      return { hero: 0.12, sec2: 0.80, sec3: 1.3, sec4: 1.3, xOffset: 0.95 };
    } else if (w <= 1280) {
      return { hero: 0.13, sec2: 0.85, sec3: 2.2, sec4: 2.2, xOffset: 1.1 };
    } else {
      return { hero: 0.15, sec2: 0.95, sec3: 3.0, sec4: 3.0, xOffset: 1.3 };
    }
  }

  // Status variabel pergerakan fisik bola
  let initParams = getResponsiveParams();
  const ballState = {
    x: 0,
    y: 0.8,
    scale: initParams.hero,
    opacity: 1,
    rx: 0,
    ry: 0,
    rz: 0
  };

  let responsiveScale = {
    hero: initParams.hero,
    sec2: initParams.sec2,
    sec3: initParams.sec3,
    sec4: initParams.sec4
  };

  let targetX = 3.5;
  function updateTargetX() {
    let params = getResponsiveParams();
    responsiveScale.hero = params.hero;
    responsiveScale.sec2 = params.sec2;
    responsiveScale.sec3 = params.sec3;
    responsiveScale.sec4 = params.sec4;

    const aspect = window.innerWidth / window.innerHeight;
    const visibleWidth = 2.0 * Math.tan((50 * Math.PI) / 360) * 10 * aspect;
    
    targetX = (visibleWidth / 2) - params.xOffset;
  }
  
  window.addEventListener('resize', () => {
    updateTargetX();
    ScrollTrigger.refresh();
  });
  updateTargetX();

  gsap.set(["#premium-border-frame", "#premium-pitch-bg"], { opacity: 0 });
  gsap.set(".bst-curved-bg", { clipPath: "inset(0% 0% 0% 100%)" });

  // Mengatur seksi absolut dalam tumpukan vertikal reel kontinu (Reel)
  gsap.set(".premium-scroll-sec", { position: "absolute", inset: 0, opacity: 1, autoAlpha: 1 });
  gsap.set("#scroll-sec-hero", { yPercent: 0 });
  gsap.set("#scroll-sec-1", { yPercent: 100 });
  gsap.set("#scroll-sec-2", { yPercent: 200 });
  gsap.set("#scroll-sec-3", { yPercent: 300 });

  // Inisialisasi awal koordinat teks specs
  gsap.set("#bst-left", { opacity: 0, yPercent: -50, y: 35 });
  gsap.set("#bst-right", { opacity: 0, yPercent: -50, y: 35 });

  // Timeline GSAP Pinning dengan transisi sliding vertikal bertumpuk
  const ballScrollTl = gsap.timeline({
    scrollTrigger: {
      trigger: ".premium-showcase-container",
      start: "top top",
      end: "+=800%",
      scrub: 1.2,
      pin: true,
      anticipatePin: 1,
      onUpdate: (self) => {
        // Hubungkan scroll progress awal (0% s/d 25%) langsung ke kemiringan / fade stadium Hero Section
        targetHeroScrollProgress = Math.min(1.0, self.progress * 4.0);
        // Pastikan canvas terlihat saat animasi aktif
        if (self.progress > 0 && self.progress < 1) {
          canvasScroll.style.display = 'block';
        }
      },
      onLeave: () => {
        // Force-hide bola saat scroll melewati section (scroll ke bawah)
        canvasScroll.style.opacity = '0';
        ballState.opacity = 0;
        setTimeout(() => { canvasScroll.style.display = 'none'; }, 400);
      },
      onLeaveBack: () => {
        // Force-hide bola saat scroll balik ke atas melewati section
        canvasScroll.style.opacity = '0';
        ballState.opacity = 0;
        setTimeout(() => { canvasScroll.style.display = 'none'; }, 400);
      },
      onEnter: () => {
        canvasScroll.style.display = 'block';
        canvasScroll.style.opacity = '1';
        ballState.opacity = 1;
      },
      onEnterBack: () => {
        canvasScroll.style.display = 'block';
      }
    }
  });

  ballScrollTl
    // ── JEDA DIAM AWAL: Hero tampil, bola diam melayang ──
    .to({}, { duration: 1.0 })

    // ── [1] HERO → SECTION 2 (MINI FUT) ──
    // Sembunyikan heading (zoom-out + blur) dan scroll indicator smooth saat hero mulai transisi
    .to(["#hero-content", "#hero-gl-canvas"], { opacity: 0, y: -40, scale: 0.94, filter: "blur(10px)", duration: 0.6, ease: "power2.in" })
    .to(".scroll-ind.center", { opacity: 0, y: 12, duration: 0.4, ease: "power2.in" }, "<")

    // Geser seluruh tumpukan seksi ke atas secara sinkron (Reel Effect)
    .to("#scroll-sec-hero", { yPercent: -100, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-1", { yPercent: 0, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-2", { yPercent: 100, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-3", { yPercent: 200, duration: 2.0, ease: "power2.inOut" }, "<")
    // Border frame & pitch lines muncul organik
    .to(["#premium-border-frame", "#premium-pitch-bg"], { opacity: 1, duration: 1.8, ease: "sine.out" }, "<0.2")
    // Bola menggelinding masuk ke tengah, membesar alami (tepat 1 putaran maju)
    .to(ballState, {
      x: 0,
      y: 0,
      scale: () => responsiveScale.sec2,
      rx: 2.0 * Math.PI,
      duration: 2.0,
      ease: "power2.out"
    }, "<0.1")

    // JEDA DIAM DI SECTION 2
    .to({}, { duration: 1.8 })

    // ── [2] SECTION 2 → SECTION 3 (RUMPUT PREMIUM) ──
    // Geser reel seksi berikutnya ke atas
    .to("#scroll-sec-hero", { yPercent: -200, duration: 2.0, ease: "power2.inOut" })
    .to("#scroll-sec-1", { yPercent: -100, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-2", { yPercent: 0, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-3", { yPercent: 100, duration: 2.0, ease: "power2.inOut" }, "<")
    // Bola menggelinding secara horizontal ke sisi kanan (tepat 1 putaran ke kanan)
    .to(ballState, {
      x: () => targetX,
      y: 0,
      scale: () => responsiveScale.sec3,
      rx: 2.5 * Math.PI,
      rz: -2.0 * Math.PI,
      duration: 2.0,
      ease: "power2.inOut"
    }, "<")
    // Tampilkan teks spesifikasi kiri
    .to("#bst-left", { opacity: 1, y: 0, yPercent: -50, duration: 1.0, ease: "power2.out" }, "-=0.4")

    // JEDA DIAM DI SECTION 3
    .to({}, { duration: 2.0 })

    // ── [3] SECTION 3 → SECTION 4 (BOLA PREMIUM) ──
    // Sembunyikan teks kiri secara halus
    .to("#bst-left", { opacity: 0, y: -20, yPercent: -50, duration: 0.8, ease: "power2.in" })
    // Geser reel seksi terakhir ke atas
    .to("#scroll-sec-hero", { yPercent: -300, duration: 2.0, ease: "power2.inOut" }, "<0.2")
    .to("#scroll-sec-1", { yPercent: -200, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-2", { yPercent: -100, duration: 2.0, ease: "power2.inOut" }, "<")
    .to("#scroll-sec-3", { yPercent: 0, duration: 2.0, ease: "power2.inOut" }, "<")
    // Bola menggelinding melintasi layar horizontal dari kanan ke kiri (tepat 1 putaran balik ke kiri)
    .to(ballState, {
      x: () => -targetX,
      y: 0,
      scale: () => responsiveScale.sec4,
      rx: 3.0 * Math.PI,
      rz: 0,
      duration: 2.0,
      ease: "power2.inOut"
    }, "<")
    // Animasi garis putus-putus muncul dari kanan ke kiri mengikuti transisi bola
    .to(".bst-curved-bg", {
      clipPath: "inset(0% 0% 0% 0%)",
      duration: 2.0,
      ease: "power2.inOut"
    }, "<")
    // Tampilkan teks spesifikasi kanan
    .to("#bst-right", { opacity: 1, y: 0, yPercent: -50, duration: 1.0, ease: "power2.out" }, "-=0.5")

    // JEDA DIAM DI SECTION 4
    .to({}, { duration: 2.2 })

    // ── [4] EXIT OUTRO ──
    .to("#bst-right", { opacity: 0, y: -30, yPercent: -50, duration: 1.0, ease: "power2.in" })
    .to("#scroll-sec-3", { yPercent: -100, duration: 1.5, ease: "power2.in" }, "<0.2")
    .to(["#premium-border-frame", "#premium-pitch-bg", ".showcase-bg-stadium"], { opacity: 0, duration: 1.5, ease: "power2.in" }, "<")
    // Bola menggelinding keluar ke arah bawah panggung halaman secara perlahan
    .to(ballState, {
      opacity: 0,
      scale: () => window.innerWidth <= 768 ? 1.8 : 2.2,
      y: () => window.innerWidth <= 768 ? -4.5 : -5.5,
      rx: 5.0 * Math.PI,
      rz: -1.0 * Math.PI,
      duration: 1.4,
      ease: "power2.in"
    }, "<");

  // Loop Render Animasi
  let timeS = 0;
  let rotVelX = 0, rotVelY = 0, rotVelZ = 0;

  let _ballVisible = true;
  let _ballExitFrames = 0; // Counter frame ekstra agar bola selesai exit sebelum render berhenti
  const _ballObs = new IntersectionObserver(([entry]) => {
    if (entry.isIntersecting) {
      _ballVisible = true;
      _ballExitFrames = 0;
    } else {
      // Beri 60 frame ekstra untuk menyelesaikan animasi exit
      _ballVisible = false;
      _ballExitFrames = 60;
    }
  }, { threshold: 0 });
  _ballObs.observe(containerShowcase);

  function renderScrollBall() {
    requestAnimationFrame(renderScrollBall);
    // Lanjutkan render jika visible ATAU masih ada frame ekstra untuk exit
    if (!_ballVisible) {
      if (_ballExitFrames > 0) {
        _ballExitFrames--;
      } else {
        return;
      }
    }
    timeS += 0.01;

    let bounceY = 0;
    if (targetHeroScrollProgress < 0.85) {
      let bounceAmp = Math.max(0, 1.0 - targetHeroScrollProgress * 1.8);
      bounceY = (Math.sin(timeS * 2.2) * 0.14 + Math.sin(timeS * 3.7) * 0.04) * bounceAmp;
    }

    let deltaRx = ballState.rx - lastRx;
    let deltaRy = ballState.ry - lastRy;
    let deltaRz = ballState.rz - lastRz;

    lastRx = ballState.rx;
    lastRy = ballState.ry;
    lastRz = ballState.rz;

    rotVelX = rotVelX * 0.82 + deltaRx * 0.18;
    rotVelZ = rotVelZ * 0.82 + deltaRz * 0.18;

    if (!isDragging) {
      targetRotX += rotVelX;
      targetRotY += deltaRy;
      targetRotZ += rotVelZ;

      let isIdle = Math.abs(deltaRx) < 0.0001 && Math.abs(deltaRz) < 0.0001;
      if (isIdle) {
        targetRotX += 0.007 + Math.sin(timeS * 0.3) * 0.002;
        targetRotY += 0.005 + Math.cos(timeS * 0.2) * 0.002;
      }

      let lerpSpeed = isIdle ? 0.10 : 0.16;
      currentRotation.x = lerpAngle(currentRotation.x, targetRotX, lerpSpeed);
      currentRotation.y = lerpAngle(currentRotation.y, targetRotY, lerpSpeed);
      currentRotation.z = lerpAngle(currentRotation.z, targetRotZ, lerpSpeed);

      scrollBall.rotation.x = currentRotation.x;
      scrollBall.rotation.y = currentRotation.y;
      scrollBall.rotation.z = currentRotation.z;
    } else {
      scrollBall.rotation.x += (targetRotation.x - scrollBall.rotation.x) * 0.055;
      scrollBall.rotation.y += (targetRotation.y - scrollBall.rotation.y) * 0.055;

      currentRotation.x = scrollBall.rotation.x;
      currentRotation.y = scrollBall.rotation.y;
      currentRotation.z = scrollBall.rotation.z;
      targetRotX = scrollBall.rotation.x;
      targetRotY = scrollBall.rotation.y;
      targetRotZ = scrollBall.rotation.z;
    }

    // Adaptive lerp: semakin jauh jarak ke target, semakin cepat konvergen
    // Ini mencegah bola "menempel" saat scroll cepat
    let distX = Math.abs(ballState.x - scrollBall.position.x);
    let distY = Math.abs((ballState.y + bounceY) - scrollBall.position.y);
    let distScale = Math.abs(ballState.scale - scrollBall.scale.x);
    let posLerp = 0.18 + Math.min(0.72, (distX + distY) * 0.15 + distScale * 0.3);

    let smoothX = scrollBall.position.x + (ballState.x - scrollBall.position.x) * posLerp;
    let targetPosY = ballState.y + bounceY;
    let smoothY = scrollBall.position.y + (targetPosY - scrollBall.position.y) * posLerp;

    scrollBall.position.x = smoothX;
    scrollBall.position.y = smoothY;
    // Adaptive scale lerp untuk respons cepat
    let scaleLerp = 0.18 + Math.min(0.72, distScale * 0.5);
    let smoothScale = scrollBall.scale.x + (ballState.scale - scrollBall.scale.x) * scaleLerp;
    scrollBall.scale.setScalar(smoothScale);
    canvasScroll.style.opacity = ballState.opacity;

    ballSMat.uniforms.uTime.value = timeS;
    rendererS.render(sceneS, cameraS);
  }
  renderScrollBall();
})();
</script>

<!-- REVISI: SVG Glass Distortion Filter Definition untuk Liquid Glass Button -->
<svg class="hidden" style="position: absolute; width: 0; height: 0;" width="0" height="0">
  <defs>
    <filter
      id="container-glass"
      x="0%"
      y="0%"
      width="100%"
      height="100%"
      color-interpolation-filters="sRGB"
    >
      <feTurbulence
        type="fractalNoise"
        baseFrequency="0.05 0.05"
        numOctaves="1"
        seed="1"
        result="turbulence"
      />
      <feGaussianBlur in="turbulence" stdDeviation="2" result="blurredNoise" />
      <feDisplacementMap
        in="SourceGraphic"
        in2="blurredNoise"
        scale="70"
        xChannelSelector="R"
        yChannelSelector="B"
        result="displaced"
      />
      <feGaussianBlur in="displaced" stdDeviation="4" result="finalBlur" />
      <feComposite in="finalBlur" in2="finalBlur" operator="over" />
    </filter>
  </defs>
</svg>
<script>
window.addEventListener('load', () => {
  const preloader = document.getElementById('site-preloader');
  if (preloader) {
    setTimeout(() => {
      preloader.classList.add('preloader-hidden');
      setTimeout(() => preloader.remove(), 500);
    }, 1000);
  }
});
</script>
<script>
/* ════════════════════════════════════════════════════════════════════
   HERO WEBGL LIQUID DISTORTION + GREEN CHROMATIC ABERRATION
   ─────────────────────────────────────────────────────────────────
   Pipeline:
     1. Render teks hero (PREMIUM / SOCCER / ARENA.) ke offscreen 2D canvas
        dengan gaya persis sama: outline-only, solid white, neon-green gradient
     2. Upload sebagai WebGL texture
     3. Full-screen quad dengan vertex + fragment shader custom:
        • fBm turbulence (4-octave value noise) → fluid displacement field
        • Sinusoidal ripple dari posisi mouse → gelombang memancar
        • Chromatic aberration: R geser kiri, G tidak digeser, B geser kanan
        • Green channel boost pada area aktif → neon glow effect
        • Smooth radial falloff dari kursor
     4. Lerp activity in/out ketika mouse masuk/keluar
   ════════════════════════════════════════════════════════════════════ */
(function initHeroWebGL() {

  /* ── Config ─────────────────────────────────────────────────────── */
  const CFG = {
    distortRadius:   0.24,   // radius pengaruh mouse (fraksi lebar canvas)
    distortStrength: 0.048,  // besaran maksimal displacement UV
    chromaticShift:  0.010,  // offset chromatic aberration (UV space)
    relaxSpeed:      0.048,  // kecepatan fade-out saat mouse pergi
    enterSpeed:      0.12,   // kecepatan fade-in saat mouse masuk
    initDelay:       900,    // ms – beri GSAP entrance selesai dulu
  };

  /* ── Vertex Shader ───────────────────────────────────────────────── */
  const VERT_SRC = `
attribute vec2 a_pos;
varying   vec2 v_uv;
void main(){
  v_uv        = a_pos * 0.5 + 0.5;
  gl_Position = vec4(a_pos, 0.0, 1.0);
}`;

  /* ── Fragment Shader ─────────────────────────────────────────────── */
  const FRAG_SRC = `
precision highp float;

uniform sampler2D u_tex;       /* hero-text texture                    */
uniform vec2      u_mouse;     /* mouse UV (0..1), Y dibalik           */
uniform float     u_radius;    /* influence radius                     */
uniform float     u_strength;  /* displacement magnitude               */
uniform float     u_chroma;    /* chromatic aberration amount          */
uniform float     u_time;      /* elapsed seconds                      */
uniform float     u_active;    /* 0..1, lerped smoothly               */
uniform vec2      u_res;       /* canvas size in pixels                */

varying vec2 v_uv;

/* ── Deterministic pseudo-random ── */
float hash21(vec2 p){
  p = fract(p * vec2(127.1, 311.7));
  p += dot(p, p + 19.19);
  return fract(p.x * p.y);
}

/* ── Value noise dengan cubic hermite ── */
float vnoise(vec2 p){
  vec2 i = floor(p);
  vec2 f = fract(p);
  /* Hermite smoothstep */
  vec2 u = f * f * (3.0 - 2.0 * f);
  float a = hash21(i);
  float b = hash21(i + vec2(1.0, 0.0));
  float c = hash21(i + vec2(0.0, 1.0));
  float d = hash21(i + vec2(1.0, 1.0));
  return mix(mix(a, b, u.x), mix(c, d, u.x), u.y);
}

/* ── fBm: 4-octave fractal Brownian motion → displacement vec2 ── */
vec2 fbm2(vec2 p, float t){
  vec2  d   = vec2(0.0);
  float amp = 0.50;
  float frq = 1.00;
  for(int i = 0; i < 4; i++){
    /* Dua channel independen dengan phase offset berbeda */
    d.x += (vnoise(p * frq + vec2(t * 0.31,  1.70)) * 2.0 - 1.0) * amp;
    d.y += (vnoise(p * frq + vec2(5.20, t * 0.29 + 3.14)) * 2.0 - 1.0) * amp;
    amp *= 0.48;
    frq *= 2.10;
  }
  return d;
}

void main(){
  vec2 uv = v_uv;

  /* Koreksi aspek rasio untuk perhitungan jarak yang benar */
  float aspect = u_res.x / u_res.y;
  vec2 uvA    = vec2(uv.x  * aspect, uv.y);
  vec2 mA     = vec2(u_mouse.x * aspect, u_mouse.y);

  float dist  = length(uvA - mA);

  /* Falloff: smooth dari 0 di radius hingga 1 di pusat mouse */
  float falloff = smoothstep(u_radius, u_radius * 0.05, dist);
  falloff *= u_active;

  /* ── Fluid turbulence displacement ── */
  vec2 turb = fbm2(uv * 3.2, u_time * 0.9);
  vec2 disp = turb * u_strength * falloff;

  /* ── Ripple: gelombang sinus yang memancar dari mouse ── */
  float ripplePhase = dist * 32.0 - u_time * 4.2;
  float ripple      = sin(ripplePhase) * 0.5 + 0.5;
  /* Arah menjauhi mouse (normalize), dikali ripple & falloff */
  vec2  rippleDir = (uvA - mA + 0.0001);
  /* Ubah kembali ke UV space (bagi x dengan aspect) */
  rippleDir = normalize(rippleDir) / vec2(aspect, 1.0);
  disp += rippleDir * ripple * u_strength * 0.40 * falloff;

  /* ── Chromatic Aberration (Green axis) ── */
  /* R geser ke kiri, G tetap (reference), B geser ke kanan */
  float ca    = u_chroma * falloff;
  vec2  offR  = disp + vec2(-ca,  0.0);
  vec2  offG  = disp;                     /* Green: no lateral shift */
  vec2  offB  = disp + vec2( ca,  0.0);

  /* Sample tiap channel secara terpisah */
  float r = texture2D(u_tex, clamp(uv + offR, 0.001, 0.999)).r;
  float g = texture2D(u_tex, clamp(uv + offG, 0.001, 0.999)).g;
  float b = texture2D(u_tex, clamp(uv + offB, 0.001, 0.999)).b;
  float a = texture2D(u_tex, clamp(uv + offG, 0.001, 0.999)).a;

  /* Green channel boost pada area aktif → neon glow khas MiniFut */
  g = mix(g, min(g * 1.35 + 0.05 * falloff, 1.0), falloff * 0.65);

  /* Slight vignette agar tepi chromatic tidak terlihat hard-clip */
  float vign = smoothstep(0.0, 0.15, min(uv.x, 1.0 - uv.x))
             * smoothstep(0.0, 0.12, min(uv.y, 1.0 - uv.y));

  gl_FragColor = vec4(r, g, b, a * vign);
}`;

  /* ── Helper: compile shader ─────────────────────────────────────── */
  function compileShader(gl, type, src) {
    const sh = gl.createShader(type);
    gl.shaderSource(sh, src);
    gl.compileShader(sh);
    if (!gl.getShaderParameter(sh, gl.COMPILE_STATUS)) {
      console.warn('[HeroGL] Shader error:', gl.getShaderInfoLog(sh));
      gl.deleteShader(sh);
      return null;
    }
    return sh;
  }

  /* ── Helper: link program ───────────────────────────────────────── */
  function makeProgram(gl, vs_src, fs_src) {
    const vs   = compileShader(gl, gl.VERTEX_SHADER,   vs_src);
    const fs   = compileShader(gl, gl.FRAGMENT_SHADER, fs_src);
    if (!vs || !fs) return null;
    const prog = gl.createProgram();
    gl.attachShader(prog, vs);
    gl.attachShader(prog, fs);
    gl.linkProgram(prog);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) {
      console.warn('[HeroGL] Link error:', gl.getProgramInfoLog(prog));
      return null;
    }
    return prog;
  }

  /* ── Render hero text ke offscreen 2D canvas ────────────────────── */
  function buildTextTexture(gl, W, H) {

    /* --- Offscreen canvas dengan resolusi sama --- */
    const off = document.createElement('canvas');
    off.width  = W;
    off.height = H;
    const ctx  = off.getContext('2d');
    ctx.clearRect(0, 0, W, H);

    const isMobile = W <= 600;

    const basePx = isMobile
      ? Math.min(W * 0.12, H * 0.08, 48)
      : Math.min(W * 0.07, H * 0.09, 64);

    const targetY = isMobile
      ? Math.max(110, Math.min(H * 0.18, 160)) + 40
      : Math.max(164, Math.min(H * 0.16 + 52, 216)) + 28;

    const words = [
      { text: 'PREMIUM', style: 'outline' },
      { text: 'SOCCER',  style: 'solid'   },
      { text: 'ARENA.',  style: 'green'   }
    ];

    ctx.font         = `400 ${basePx}px Anton, Impact, sans-serif`;
    ctx.textAlign    = 'center';
    ctx.textBaseline = 'middle';

    // Measure widths
    const widths = words.map(w => ctx.measureText(w.text).width);
    const gap = isMobile ? 10 : 18;
    const totalW = widths.reduce((sum, w) => sum + w, 0) + gap * (words.length - 1);

    let currentX = (W - totalW) / 2;

    // Initialize default states if not present
    if (!window.heroTextState) {
      window.heroTextState = [
        { opacity: 0, y: 110, rotateX: -55, blur: 10 },
        { opacity: 0, y: 110, rotateX: -55, blur: 10 },
        { opacity: 0, y: 110, rotateX: -55, blur: 10 }
      ];
    }

    words.forEach((w, i) => {
      const state = window.heroTextState[i] || { opacity: 1, y: 0, rotateX: 0, blur: 0 };
      const wordW = widths[i];
      
      let x, y;
      if (isMobile) {
        x = W / 2;
        // Stack words vertically centered around targetY
        const baseWordY = targetY + (i - 1) * (basePx * 1.15);
        y = baseWordY + (state.y / 100) * basePx;
      } else {
        x = currentX + wordW / 2;
        y = targetY + (state.y / 100) * basePx;
      }

      ctx.save();

      // Apply opacity
      ctx.globalAlpha = state.opacity;

      // Apply blur filter if supported and value is significant
      if (state.blur > 0.05) {
        ctx.filter = `blur(${state.blur}px)`;
      } else {
        ctx.filter = 'none';
      }

      // Apply 3D tilt (rotateX) via Y-scale compression
      const rad = (state.rotateX * Math.PI) / 180;
      const scaleY = Math.cos(rad);
      ctx.translate(x, y + basePx * 0.4);
      ctx.scale(1, Math.max(0, scaleY));
      ctx.translate(-x, -(y + basePx * 0.4));

      if (w.style === 'outline') {
        ctx.strokeStyle = 'rgba(255,255,255,0.26)';
        ctx.lineWidth   = isMobile ? 2.0 : 2.5;
        ctx.strokeText(w.text, x, y);

      } else if (w.style === 'solid') {
        ctx.fillStyle = '#eceef2';
        ctx.fillText(w.text, x, y);

      } else {
        /* green gradient shine */
        const grd = ctx.createLinearGradient(x - wordW / 2, y, x + wordW / 2, y);
        grd.addColorStop(0,    '#00ff88');
        grd.addColorStop(0.45, '#d4ffec');
        grd.addColorStop(1,    '#00ff88');
        ctx.fillStyle = grd;
        ctx.fillText(w.text, x, y);
      }

      ctx.restore();
      currentX += wordW + gap;
    });

    /* Upload ke GPU */
    const tex = gl.createTexture();
    gl.bindTexture(gl.TEXTURE_2D, tex);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S,     gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T,     gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
    gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true); // Flip Y to fix upside-down (terbalik) issue
    gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, off);

    return tex;
  }

  /* ── Main init (delayed) ────────────────────────────────────────── */
  setTimeout(function () {

    const canvas = document.getElementById('hero-gl-canvas');
    if (!canvas) return;

    /* Try WebGL (dengan fallback ke experimental-webgl) */
    const gl = canvas.getContext('webgl', { alpha: true, premultipliedAlpha: false })
            || canvas.getContext('experimental-webgl', { alpha: true, premultipliedAlpha: false });
    if (!gl) {
      console.warn('[HeroGL] WebGL tidak tersedia di browser ini.');
      canvas.style.display = 'none';
      return;
    }

    /* Compile & link shaders */
    const prog = makeProgram(gl, VERT_SRC, FRAG_SRC);
    if (!prog) { canvas.style.display = 'none'; return; }

    /* Full-screen quad buffer */
    const quadBuf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, quadBuf);
    gl.bufferData(gl.ARRAY_BUFFER,
      new Float32Array([-1,-1,  1,-1,  -1,1,  1,1]),
      gl.STATIC_DRAW);

    const posLoc = gl.getAttribLocation(prog, 'a_pos');
    gl.enableVertexAttribArray(posLoc);
    gl.vertexAttribPointer(posLoc, 2, gl.FLOAT, false, 0, 0);

    /* Uniform locations */
    gl.useProgram(prog);
    const uTex      = gl.getUniformLocation(prog, 'u_tex');
    const uMouse    = gl.getUniformLocation(prog, 'u_mouse');
    const uRadius   = gl.getUniformLocation(prog, 'u_radius');
    const uStrength = gl.getUniformLocation(prog, 'u_strength');
    const uChroma   = gl.getUniformLocation(prog, 'u_chroma');
    const uTime     = gl.getUniformLocation(prog, 'u_time');
    const uActive   = gl.getUniformLocation(prog, 'u_active');
    const uRes      = gl.getUniformLocation(prog, 'u_res');

    /* State */
    let mouse      = { x: 0.5, y: 0.5 };
    let active     = 0.0;   /* current lerped value        */
    let targetAct  = 0.0;   /* target: 1 = mouse inside    */
    let texDirty   = true;
    let tex        = null;

    /* Blend */
    gl.enable(gl.BLEND);
    gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA); /* premultiplied output */

    /* ── Resize ── */
    function resize() {
      const hero = document.getElementById('scroll-sec-hero');
      const rect  = hero ? hero.getBoundingClientRect() : {};
      canvas.width  = rect.width  || window.innerWidth;
      canvas.height = rect.height || window.innerHeight;
      gl.viewport(0, 0, canvas.width, canvas.height);
      texDirty = true; /* regenerate texture on resize */
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    /* ── Mouse / Touch tracking ── */
    function updateMouse(clientX, clientY) {
      const r  = canvas.getBoundingClientRect();
      mouse.x  = (clientX - r.left)  / r.width;
      mouse.y  = 1.0 - (clientY - r.top) / r.height;  /* flip Y untuk WebGL */
      targetAct = 1.0;
    }

    canvas.addEventListener('mousemove',  e => updateMouse(e.clientX, e.clientY), { passive: true });
    canvas.addEventListener('mouseleave', () => { targetAct = 0.0; },              { passive: true });
    canvas.addEventListener('touchmove',  e => {
      e.preventDefault();
      updateMouse(e.touches[0].clientX, e.touches[0].clientY);
    }, { passive: false });
    canvas.addEventListener('touchend',   () => { targetAct = 0.0; },              { passive: true });

    /* ── Visibility observer (hemat GPU saat hero tidak terlihat) ── */
    let isVisible = true;
    const observer = new IntersectionObserver(
      ([entry]) => { isVisible = entry.isIntersecting; },
      { threshold: 0 }
    );
    observer.observe(canvas);

    /* ── Render loop ── */
    let rafId = null;

    function render(ms) {
      rafId = requestAnimationFrame(render);

      /* Skip render jika hero tidak di viewport */
      if (!isVisible) return;

      const t = ms * 0.001;

      /* Rebuild texture jika perlu (pertama kali, setelah resize, atau saat update animasi) */
      if (texDirty || window.triggerHeroTextUpdate) {
        if (tex) gl.deleteTexture(tex);
        tex = buildTextTexture(gl, canvas.width, canvas.height);
        texDirty = false;
        window.triggerHeroTextUpdate = false;
      }

      /* Lerp activity: cepat masuk, lambat keluar (efek liquid melambat) */
      const speed  = targetAct > active ? CFG.enterSpeed : CFG.relaxSpeed;
      active      += (targetAct - active) * speed;

      /* Clear */
      gl.clearColor(0, 0, 0, 0);
      gl.clear(gl.COLOR_BUFFER_BIT);

      /* Draw */
      gl.useProgram(prog);

      gl.activeTexture(gl.TEXTURE0);
      gl.bindTexture(gl.TEXTURE_2D, tex);
      gl.uniform1i(uTex,      0);
      gl.uniform2f(uMouse,    mouse.x, mouse.y);
      gl.uniform1f(uRadius,   CFG.distortRadius);
      gl.uniform1f(uStrength, CFG.distortStrength);
      gl.uniform1f(uChroma,   CFG.chromaticShift);
      gl.uniform1f(uTime,     t);
      gl.uniform1f(uActive,   active);
      gl.uniform2f(uRes,      canvas.width, canvas.height);

      gl.bindBuffer(gl.ARRAY_BUFFER, quadBuf);
      gl.enableVertexAttribArray(posLoc);
      gl.vertexAttribPointer(posLoc, 2, gl.FLOAT, false, 0, 0);

      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    }

    // Fade in canvas, fade out original title
    canvas.style.opacity = '1';
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
      heroTitle.style.opacity = '0';
    }

    requestAnimationFrame(render);

  }, CFG.initDelay);

})();
</script>
</body>
</html>
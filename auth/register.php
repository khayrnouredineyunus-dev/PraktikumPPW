<?php
// auth/register.php — Halaman Registrasi Pelanggan MiniFut
require_once __DIR__ . '/../config.php';
startSecureSession();

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

$errors  = [];
$success = false;
$values  = ['nama'=>'','email'=>'','notelp'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $notelp   = trim($_POST['notelp']   ?? '');
    $password = $_POST['password']      ?? '';
    $konfirm  = $_POST['konfirm']       ?? '';
    $values   = compact('nama','email','notelp');

    // ── Validasi ──────────────────────────────────────────
    if (empty($nama))   $errors[] = 'Nama lengkap tidak boleh kosong.';
    elseif (mb_strlen($nama) < 3) $errors[] = 'Nama minimal 3 karakter.';

    if (empty($email))  $errors[] = 'Email tidak boleh kosong.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    if (empty($notelp)) $errors[] = 'Nomor telepon tidak boleh kosong.';
    elseif (!preg_match('/^[0-9+\-\s]{8,20}$/', $notelp)) $errors[] = 'Nomor telepon tidak valid (8-20 digit).';

    if (empty($password))       $errors[] = 'Password tidak boleh kosong.';
    elseif (mb_strlen($password) < 8) $errors[] = 'Password minimal 8 karakter.';
    elseif (!preg_match('/[A-Z]/', $password)) $errors[] = 'Password harus mengandung minimal 1 huruf kapital.';
    elseif (!preg_match('/[0-9]/', $password)) $errors[] = 'Password harus mengandung minimal 1 angka.';

    if ($password !== $konfirm) $errors[] = 'Konfirmasi password tidak cocok.';

    if (empty($errors)) {
        $pdo  = getDB();
        // Cek email duplikat
        $stmt = $pdo->prepare("SELECT ID_PELANGGAN FROM Pelanggan WHERE U_EMAIL = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar. Silakan login atau gunakan email lain.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO Pelanggan (U_NAMA, U_EMAIL, U_PASSWORD, U_NOTELP) VALUES (?,?,?,?)");
            $stmt->execute([$nama, $email, $hash, $notelp]);
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MiniFut — Daftar Akun</title>
<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpolygon points='60,6 107,33 107,87 60,114 13,87 13,33' fill='%23060608' stroke='%2300ff88' stroke-width='4'/%3E%3Ccircle cx='60' cy='60' r='20' stroke='%2300ff88' stroke-width='3' fill='none'/%3E%3Cpolygon points='60,42 75,53 69,71 51,71 45,53' fill='%2300ff88'/%3E%3Cpath d='M60 42 L60 10 M75 53 L104 39 M69 71 L92 92 M51 71 L28 92 M45 53 L16 39' stroke='%2300ff88' stroke-width='3' stroke-linecap='round'/%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Anton&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --black:#060608; --dark:#0c0d10; --card:#111318;
  --border:rgba(255,255,255,0.06); --border2:rgba(255,255,255,0.1);
  --green:#00ff88; --glow:rgba(0,255,136,0.18);
  --gray:#6b7080; --gray2:#9aa0b0; --white:#eceef2; --red:#ff3b5c;
}
*{margin:0;padding:0;box-sizing:border-box;}
html,body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--black);color:var(--white);}
#shader-bg{position:fixed;inset:0;z-index:0;opacity:.55;}
#shader-bg canvas{display:block;width:100%;height:100%;}
.wrap {
  position:relative;z-index:2;
  display:flex;align-items:center;justify-content:center;
  min-height:100vh;padding:32px 24px;
}
.card-wrapper {
  position: relative;
  width: 100%;
  max-width: 460px;
  border-radius: 24px;
  padding: 1.5px;
  z-index: 2;
  box-shadow: 0 32px 80px rgba(0,0,0,.65), 0 0 40px rgba(0,255,136,.02);
  animation: slideUp .6s cubic-bezier(.22,1,.36,1) both;
}
@keyframes slideUp{from{opacity:0;transform:translateY(32px)}to{opacity:1;transform:translateY(0)}}

.card {
  width: 100%;
  background: rgba(12, 13, 16, 0.96);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-radius: 23px;
  padding: 48px 40px 40px;
  position: relative;
  z-index: 1;
}

.shine-border-bg {
  position: absolute;
  inset: 0;
  border-radius: 24px;
  overflow: hidden;
  pointer-events: none;
  z-index: 0;
}
.card-shine {
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
  opacity: 0.45;
}
@keyframes spin-shine {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.logo-wrap { text-align:center; margin-bottom:28px; }
.logo {
  font-family:'Orbitron',monospace;font-size:1.8rem;font-weight:900;
  color:var(--green);letter-spacing:6px;text-decoration:none;
  text-shadow: 0 0 12px rgba(0, 255, 136, 0.3);
  display: inline-block;
}
.logo em{color:var(--white);font-style:normal;}
.tagline {
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.65rem;letter-spacing:3px;
  text-transform:uppercase;color:var(--gray);margin-top:8px;
  font-weight: 600;
}
h2 { font-family:'Orbitron',monospace;font-size:1rem;font-weight:700;color:var(--white);margin-bottom:5px; }
.sub { font-family:'Plus Jakarta Sans',sans-serif;font-size:.84rem;color:var(--gray2);margin-bottom:24px; }

/* Alerts */
.alert-err {
  background:rgba(255,59,92,.06);border:1px solid rgba(255,59,92,.2);
  border-radius:8px;padding:12px 16px;margin-bottom:24px;
}
.alert-err ul { margin:0;padding-left:16px; }
.alert-err li { font-size:.8rem;color:#ff7096;margin-bottom:4px; }
.alert-ok {
  background:rgba(0,255,136,.06);border:1px solid rgba(0,255,136,.2);
  border-radius:8px;padding:16px;margin-bottom:24px;text-align:center;
}
.alert-ok strong { font-family:'Orbitron',monospace;font-size:.9rem;color:var(--green);display:block;margin-bottom:6px; }
.alert-ok p { font-size:.8rem;color:var(--gray2); }

.field { margin-bottom:20px; }
label {
  display:block;font-family:'Plus Jakarta Sans',sans-serif;font-size:.68rem;
  font-weight:700;letter-spacing:2.5px;text-transform:uppercase;
  color:var(--green);margin-bottom:8px;
}
input[type=text],input[type=email],input[type=password],input[type=tel] {
  width:100%;background:rgba(255,255,255,.02);border:1px solid var(--border2);
  border-radius:8px;padding:13px 16px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;color:var(--white);
  outline:none;transition:all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  -webkit-appearance:none;
}
input:focus {
  border-color:var(--green);
  background:rgba(0, 255, 136, 0.01);
  box-shadow:0 0 16px rgba(0,255,136,.12), inset 0 0 4px rgba(0,255,136,.05);
}
input::placeholder { color:var(--gray); }

/* Password strength */
.pw-hint { font-family:'Plus Jakarta Sans',sans-serif;font-size:.72rem;color:var(--gray);margin-top:6px; }

.btn-submit {
  width:100%;margin-top:12px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;font-weight:800;
  letter-spacing:2.5px;text-transform:uppercase;
  color:var(--black);background:var(--green);
  border:none;border-radius:8px;padding:15px;
  cursor:pointer;transition:all .3s cubic-bezier(0.25, 0.8, 0.25, 1);
  box-shadow:0 4px 20px rgba(0,255,136,.2);
  position:relative;overflow:hidden;
}
.btn-submit::after {
  content:'';position:absolute;inset:0;
  background:linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
  transform:translateX(-100%);transition:transform 0.6s ease;
}
.btn-submit:hover::after { transform:translateX(100%); }
.btn-submit:hover { box-shadow:0 8px 30px rgba(0,255,136,.45);transform:translateY(-2px); }
.btn-submit:active { transform:translateY(0); }
.btn-submit.loading { color: transparent !important; pointer-events: none; }
.btn-submit.loading::before {
  content: ''; position: absolute; top: 50%; left: 50%; width: 20px; height: 20px;
  margin-top: -10px; margin-left: -10px; border: 2px solid rgba(0, 0, 0, 0.1);
  border-top-color: var(--black); border-radius: 50%; animation: btn-spin 0.6s linear infinite; z-index: 10;
}
@keyframes btn-spin { to { transform: rotate(360deg); } }

.links {
  text-align:center;
  font-size:.82rem;
  color:var(--gray2);
  margin-top:24px;
  font-family:'Plus Jakarta Sans',sans-serif;
}
.links a {
  color:var(--green);text-decoration:none;
  font-family:'Orbitron',sans-serif;font-size:.78rem;font-weight:700;
  letter-spacing:1.5px;text-transform:uppercase;
  position: relative;
  padding-bottom: 4px;
  transition: color 0.3s ease, text-shadow 0.3s ease;
  display: inline-block;
  margin-left: 6px;
}
.links a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background: var(--green);
  box-shadow: 0 0 8px var(--green);
  transition: width 0.3s ease, left 0.3s ease;
}
.links a:hover {
  color: var(--white);
  text-shadow: 0 0 8px rgba(0, 255, 136, 0.6);
}
.links a:hover::after {
  width: 100%;
  left: 0;
}
@media(max-width:480px){ .card{padding:36px 20px 28px;} }

/* Preloader */
#site-preloader {
  position: fixed; inset: 0; background: #060608; z-index: 9999;
  display: flex; align-items: center; justify-content: center;
  flex-direction: column; gap: 20px;
  transition: opacity 0.6s ease, visibility 0.6s ease;
}
#site-preloader.preloader-hidden {
  opacity: 0; visibility: hidden;
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
  0%, 100% { stroke-dashoffset: 160; }
  50% { stroke-dashoffset: 0; }
}
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
<div id="shader-bg"></div>
<div class="wrap">
  <div class="card-wrapper">
    <div class="shine-border-bg">
      <div class="card-shine"></div>
    </div>
    <div class="card">
    <div class="logo-wrap">
      <a href="../index.php" class="logo">MINI<em>FUT</em></a>
      <div class="tagline">Premium Mini Soccer Arena</div>
    </div>

    <h2>Buat Akun Baru</h2>
    <p class="sub">Booking lebih cepat dan mudah</p>

    <?php if ($success): ?>
    <div class="alert-ok">
      <strong>Akun Berhasil Dibuat!</strong>
      <p>Silakan login dengan email dan password Anda.</p>
    </div>
    <div class="links">
      <a href="login.php">Masuk ke akun Anda</a>
    </div>

    <?php else: ?>

    <?php if (!empty($errors)): ?>
    <div class="alert-err">
      <ul>
        <?php foreach($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="" novalidate>
      <div class="field">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama"
               placeholder="Nama sesuai KTP" value="<?= e($values['nama']) ?>" required>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               placeholder="nama@email.com" value="<?= e($values['email']) ?>" required>
      </div>

      <div class="field">
        <label for="notelp">Nomor Telepon / WhatsApp</label>
        <input type="tel" id="notelp" name="notelp"
               placeholder="08xxxxxxxxxx" value="<?= e($values['notelp']) ?>" required>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Min. 8 karakter" required>
        <div class="pw-hint">Minimal 8 karakter, 1 huruf kapital, dan 1 angka.</div>
      </div>

      <div class="field">
        <label for="konfirm">Konfirmasi Password</label>
        <input type="password" id="konfirm" name="konfirm"
               placeholder="Ulangi password" required>
      </div>

      <button type="submit" class="btn-submit">DAFTAR SEKARANG</button>
    </form>

    <div class="links">
      Sudah punya akun? <a href="login.php">Masuk di sini</a>
    </div>

    <?php endif; ?>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
(function(){
  var c=document.getElementById('shader-bg');if(!c)return;
  var vs='void main(){gl_Position=vec4(position,1.0);}';
  var fs=[
    'precision highp float;',
    'uniform vec2 resolution;uniform float time;',
    'void main(void){',
    '  vec2 uv=(gl_FragCoord.xy*2.0-resolution.xy)/min(resolution.x,resolution.y);',
    '  float t=time*0.05;float lw=0.002;',
    '  vec3 col=vec3(0.0);',
    '  for(int j=0;j<3;j++){for(int i=0;i<5;i++){',
    '    col[j]+=lw*float(i*i)/abs(fract(t-0.01*float(j)+float(i)*0.01)*5.0-length(uv)+mod(uv.x+uv.y,0.2));',
    '  }}',
    '  float b=(col.r+col.g+col.b)/3.0;',
    '  gl_FragColor=vec4(b*0.02,b*0.95,b*0.45,1.0);',
    '}'
  ].join('\n');
  var cam=new THREE.Camera();cam.position.z=1;
  var sc=new THREE.Scene();
  var geo=new THREE.PlaneGeometry(2,2);
  var uni={time:{value:1.0},resolution:{value:new THREE.Vector2()}};
  var mat=new THREE.ShaderMaterial({uniforms:uni,vertexShader:vs,fragmentShader:fs});
  sc.add(new THREE.Mesh(geo,mat));
  var r=new THREE.WebGLRenderer({antialias:true});
  r.setPixelRatio(window.devicePixelRatio);c.appendChild(r.domElement);
  function onR(){r.setSize(window.innerWidth,window.innerHeight);uni.resolution.value.set(r.domElement.width,r.domElement.height);}
  onR();window.addEventListener('resize',onR);
  (function anim(){requestAnimationFrame(anim);uni.time.value+=0.05;r.render(sc,cam);})();
})();

// Form submission loading state
if (document.querySelector('form')) {
  document.querySelector('form').addEventListener('submit', function() {
    const btn = this.querySelector('.btn-submit');
    if (btn) btn.classList.add('loading');
  });
}

// Preloader load logic
window.addEventListener('load', function() {
  setTimeout(function() {
    const preloader = document.getElementById('site-preloader');
    if (preloader) {
      preloader.classList.add('preloader-hidden');
    }
  }, 1000);
});
</script>
</body>
</html>
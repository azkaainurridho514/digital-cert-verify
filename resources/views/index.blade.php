<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OLC Kampung Inggris Kuningan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>

    :root {
      --gold: #c9a84c;
      --gold-light: #e8c97a;
      --dark: #0d0f14;
      --dark-2: #161920;
      --dark-3: #1e2230;
      --text-muted: #8a8f9e;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--dark);
      color: #e8eaf0;
      overflow-x: hidden;
    }

    /* ── Navbar ── */

    .navbar-olc {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      padding: 20px 0;
      transition: all 0.4s ease;
    }

    .navbar-olc.scrolled {
      background: rgba(13, 15, 20, 0.95);
      backdrop-filter: blur(12px);
      padding: 12px 0;
      border-bottom: 1px solid rgba(201,168,76,0.15);
    }

    .navbar-brand {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: white !important;
      letter-spacing: 0.5px;
    }

    .navbar-brand span {
      color: var(--gold);
    }

    .nav-link {
      color: rgba(255,255,255,0.75) !important;
      font-size: 14px;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: color 0.3s;
      padding: 8px 16px !important;
    }

    .nav-link:hover {
      color: white !important;
    }

    .btn-nav {
      background: var(--gold);
      color: var(--dark) !important;
      border-radius: 6px;
      font-weight: 600;
      font-size: 13px;
      letter-spacing: 0.5px;
      padding: 10px 22px !important;
      transition: all 0.3s;
    }

    .btn-nav:hover {
      background: var(--gold-light);
      transform: translateY(-1px);
    }

    /* ── Hero ── */

    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
      padding: 120px 0 80px;
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 60% 40%, rgba(201,168,76,0.08) 0%, transparent 70%),
        radial-gradient(ellipse 50% 50% at 20% 80%, rgba(201,168,76,0.05) 0%, transparent 60%),
        var(--dark);
    }

    .hero-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
      background-size: 60px 60px;
      mask-image: radial-gradient(ellipse 70% 70% at 60% 40%, black 30%, transparent 80%);
    }

    .hero-label {
      display: inline-block;
      background: rgba(201,168,76,0.12);
      border: 1px solid rgba(201,168,76,0.3);
      color: var(--gold);
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 6px 16px;
      border-radius: 20px;
      margin-bottom: 28px;
      animation: fadeUp 0.8s ease both;
    }

    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(48px, 7vw, 88px);
      font-weight: 900;
      line-height: 1.05;
      color: white;
      margin-bottom: 28px;
      animation: fadeUp 0.8s 0.1s ease both;
    }

    .hero-title .gold {
      color: var(--gold);
      font-style: italic;
    }

    .hero-desc {
      font-size: 17px;
      color: var(--text-muted);
      line-height: 1.7;
      max-width: 500px;
      margin-bottom: 44px;
      animation: fadeUp 0.8s 0.2s ease both;
      font-weight: 300;
    }

    .hero-actions {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      animation: fadeUp 0.8s 0.3s ease both;
    }

    .btn-primary-olc {
      background: var(--gold);
      color: var(--dark);
      border: none;
      padding: 15px 36px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 15px;
      text-decoration: none;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary-olc:hover {
      background: var(--gold-light);
      color: var(--dark);
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(201,168,76,0.3);
    }

    .btn-ghost-olc {
      background: transparent;
      color: white;
      border: 1px solid rgba(255,255,255,0.2);
      padding: 15px 36px;
      border-radius: 8px;
      font-weight: 500;
      font-size: 15px;
      text-decoration: none;
      transition: all 0.3s;
    }

    .btn-ghost-olc:hover {
      border-color: rgba(255,255,255,0.5);
      color: white;
      background: rgba(255,255,255,0.05);
    }

    .hero-stats {
      display: flex;
      gap: 48px;
      margin-top: 64px;
      padding-top: 48px;
      border-top: 1px solid rgba(255,255,255,0.07);
      animation: fadeUp 0.8s 0.4s ease both;
      flex-wrap: wrap;
    }

    .stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 38px;
      font-weight: 700;
      color: white;
      line-height: 1;
      margin-bottom: 4px;
    }

    .stat-num span {
      color: var(--gold);
    }

    .stat-label {
      font-size: 13px;
      color: var(--text-muted);
      letter-spacing: 0.5px;
    }

    /* Hero visual side */

    .hero-visual {
        position: relative;
        animation: fadeUp 0.8s 0.2s ease both;
        overflow: visible; /* ← tambah ini */
        padding-top: 30px; /* ← beri ruang atas untuk badge */
    }

    .cert-card {
      background: var(--dark-3);
      border: 1px solid rgba(201,168,76,0.2);
      border-radius: 16px;
      padding: 32px;
      position: relative;
      overflow: hidden;
    }

    .cert-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
    }

    .cert-badge {
      background: rgba(201,168,76,0.12);
      border: 1px solid rgba(201,168,76,0.25);
      color: var(--gold);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 5px 12px;
      border-radius: 4px;
      display: inline-block;
      margin-bottom: 20px;
    }

    .cert-name {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 700;
      color: white;
      margin-bottom: 6px;
    }

    .cert-program {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 24px;
    }

    .cert-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      font-size: 14px;
    }

    .cert-row:last-child {
      border-bottom: none;
    }

    .cert-row-label {
      color: var(--text-muted);
    }

    .cert-row-value {
      font-weight: 500;
      color: white;
    }

    .cert-verified {
      margin-top: 20px;
      background: rgba(16,185,129,0.1);
      border: 1px solid rgba(16,185,129,0.25);
      border-radius: 8px;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      color: #10b981;
      font-weight: 600;
    }

    .float-badge {
      position: absolute;
      background: var(--dark-3);
      border: 1px solid rgba(201,168,76,0.2);
      border-radius: 12px;
      padding: 14px 18px;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.4);
      animation: float 4s ease-in-out infinite;
    }

    .float-badge-1 {
        top: -10px;  
        right: -20px;
        animation-delay: 0s;
    }

    .float-badge-2 {
      bottom: 30px;
      left: -30px;
      animation-delay: 2s;
    }

    .hero-visual .cert-card {
        position: relative;
        z-index: 1;
    }

    .float-badge {
        z-index: 2;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    /* ── About ── */

    .about-section {
      padding: 120px 0;
      background: var(--dark-2);
      position: relative;
    }

    .section-label {
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 16px;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(32px, 5vw, 52px);
      font-weight: 700;
      color: white;
      line-height: 1.15;
      margin-bottom: 20px;
    }

    .section-desc {
      font-size: 16px;
      color: var(--text-muted);
      line-height: 1.8;
      font-weight: 300;
    }

    .feature-item {
      display: flex;
      gap: 20px;
      margin-bottom: 36px;
    }

    .feature-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: rgba(201,168,76,0.1);
      border: 1px solid rgba(201,168,76,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .feature-title {
      font-weight: 600;
      font-size: 15px;
      color: white;
      margin-bottom: 6px;
    }

    .feature-desc {
      font-size: 14px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    /* ── CTA ── */

    .cta-section {
      padding: 120px 0;
      position: relative;
      overflow: hidden;
      background: var(--dark);
    }

    .cta-bg {
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 80% 80% at 50% 50%, rgba(201,168,76,0.07) 0%, transparent 70%);
    }

    .cta-box {
      background: var(--dark-3);
      border: 1px solid rgba(201,168,76,0.2);
      border-radius: 24px;
      padding: 80px 60px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .cta-box::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .cta-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(32px, 5vw, 52px);
      font-weight: 700;
      color: white;
      margin-bottom: 16px;
      line-height: 1.2;
    }

    .cta-desc {
      font-size: 16px;
      color: var(--text-muted);
      max-width: 500px;
      margin: 0 auto 40px;
      line-height: 1.7;
    }

    /* ── Footer ── */

    footer {
      background: var(--dark-2);
      border-top: 1px solid rgba(255,255,255,0.06);
      padding: 28px 0;
    }

    .footer-brand {
      font-family: 'Playfair Display', serif;
      font-size: 16px;
      font-weight: 700;
      color: white;
    }

    .footer-brand span { color: var(--gold); }

    .footer-copy {
      font-size: 13px;
      color: var(--text-muted);
    }

    /* ── Animations ── */

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.7s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-olc" id="navbar">
    <div class="container">
      <a class="navbar-brand" href="#">OLC <span>Kampung Inggris</span></a>
      <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nav" style="filter:invert(1)">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center gap-1">
          <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item">
            <a class="nav-link btn-nav ms-2" href="/scan">Scan Certificate</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="container position-relative">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <div class="hero-label">✦ Official Certificate System</div>
          <h1 class="hero-title">
            English <span class="gold">Mastery</span><br>Starts Here
          </h1>
          <p class="hero-desc">
            Our Learning Center Kampung Inggris Kuningan — lembaga bahasa Inggris dengan sistem verifikasi sertifikat digital yang aman dan instan.
          </p>
          <div class="hero-actions">
            <a href="/scan" class="btn-primary-olc">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6z"/></svg>
              Scan Certificate
            </a>
            <a href="#about" class="btn-ghost-olc">Learn More</a>
          </div>
          <div class="hero-stats">
            <div>
              <div class="stat-num">2K<span>+</span></div>
              <div class="stat-label">Certificates Issued</div>
            </div>
            <div>
              <div class="stat-num">98<span>%</span></div>
              <div class="stat-label">Satisfaction Rate</div>
            </div>
            <div>
              <div class="stat-num">5<span>+</span></div>
              <div class="stat-label">Programs Available</div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="hero-visual">

            <div class="float-badge float-badge-1">
              <span style="color:#10b981;font-size:18px;">✓</span>
              <div>
                <div style="font-size:12px;font-weight:600;color:white;">Verified</div>
                <div style="font-size:11px;color:var(--text-muted);">Just now</div>
              </div>
            </div>

            <div class="cert-card">
              <div class="cert-badge">OLC — Certificate Digital Verification</div>
              <div class="cert-name">Azka Ainurridho</div>
              <div class="cert-program">Intensive English Program</div>
              <div class="cert-row">
                <span class="cert-row-label">No. Sertifikat</span>
                <span class="cert-row-value">OLC-ENG-2026-001</span>
              </div>
              <div class="cert-row">
                <span class="cert-row-label">Nilai</span>
                <span class="cert-row-value" style="color:var(--gold);">A — Excellent</span>
              </div>
              <div class="cert-row">
                <span class="cert-row-label">Tanggal Terbit</span>
                <span class="cert-row-value">12 March 2026</span>
              </div>
              <div class="cert-verified">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Certificate Verified — OLC Official
              </div>
            </div>

            <div class="float-badge float-badge-2">
              <span style="font-size:18px;">⚡</span>
              <div>
                <div style="font-size:12px;font-weight:600;color:white;">Instant Scan</div>
                <div style="font-size:11px;color:var(--text-muted);">QR Verified</div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About -->
  <section id="about" class="about-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5 reveal">
          <div class="section-label">About Us</div>
          <h2 class="section-title">Belajar Bahasa Inggris dengan Serius</h2>
          <p class="section-desc">
            OLC Kampung Inggris Kuningan hadir sebagai lembaga pendidikan bahasa yang berfokus pada peningkatan kemampuan komunikasi bahasa Inggris secara praktis, intensif, dan terstruktur.
          </p>
        </div>
        <div class="col-lg-7 reveal">
          <div class="feature-item">
            <div class="feature-icon">🎓</div>
            <div>
              <div class="feature-title">Program Intensif Bersertifikat</div>
              <div class="feature-desc">Setiap peserta yang menyelesaikan program mendapatkan sertifikat resmi yang dapat diverifikasi secara digital.</div>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">🔐</div>
            <div>
              <div class="feature-title">Verifikasi QR Aman & Instan</div>
              <div class="feature-desc">Sistem verifikasi berbasis QR Code memastikan setiap sertifikat asli dan tidak bisa dipalsukan.</div>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">📱</div>
            <div>
              <div class="feature-title">Akses dari Mana Saja</div>
              <div class="feature-desc">Verifikasi sertifikat dapat dilakukan langsung melalui browser smartphone tanpa perlu aplikasi tambahan.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="cta-bg"></div>
    <div class="container position-relative">
      <div class="cta-box reveal">
        <div class="section-label mb-4">Get Started</div>
        <h2 class="cta-title">Verify Your Certificate<br><span style="color:var(--gold);font-style:italic;">Right Now</span></h2>
        <p class="cta-desc">Scan QR Code pada sertifikat untuk memastikan validitas data secara langsung melalui sistem resmi OLC.</p>
        <a href="/scan" class="btn-primary-olc" style="display:inline-flex;">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6z"/></svg>
          Start Scanning
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="footer-brand">OLC <span>Kampung Inggris</span></div>
      <div class="footer-copy">© 2026 Our Learning Center Kampung Inggris Kuningan</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 30);
    });

    // Scroll reveal
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.15 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  </script>

</body>
</html>
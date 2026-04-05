<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scan Certificate | OLC</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/html5-qrcode"></script>

  <style>

    :root {
      --gold: #c9a84c;
      --gold-light: #e8c97a;
      --dark: #0d0f14;
      --dark-2: #161920;
      --dark-3: #1e2230;
      --text-muted: #8a8f9e;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--dark);
      color: #e8eaf0;
      min-height: 100vh;
    }

    /* ── Navbar ── */

    .navbar-olc {
      background: rgba(13,15,20,0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(201,168,76,0.15);
      padding: 16px 0;
    }

    .navbar-brand {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      color: white !important;
    }

    .navbar-brand span { color: var(--gold); }

    /* ── Page bg ── */

    .page-bg {
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 60% 50% at 50% 0%, rgba(201,168,76,0.06) 0%, transparent 70%),
        var(--dark);
      z-index: 0;
    }

    .page-grid {
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(201,168,76,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201,168,76,0.03) 1px, transparent 1px);
      background-size: 60px 60px;
      z-index: 0;
    }

    /* ── Main ── */

    .scan-section {
      position: relative;
      z-index: 1;
      padding: 70px 0 100px;
    }

    /* ── Header ── */

    .page-label {
      display: inline-block;
      background: rgba(201,168,76,0.1);
      border: 1px solid rgba(201,168,76,0.25);
      color: var(--gold);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 5px 14px;
      border-radius: 20px;
      margin-bottom: 20px;
      animation: fadeUp 0.6s ease both;
    }

    .page-title {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: clamp(32px, 5vw, 48px);
      color: white;
      margin-bottom: 12px;
      animation: fadeUp 0.6s 0.1s ease both;
    }

    .page-title span {
      color: var(--gold);
      font-style: italic;
    }

    .page-subtitle {
      color: var(--text-muted);
      max-width: 480px;
      margin: 0 auto 40px;
      font-size: 15px;
      line-height: 1.7;
      font-weight: 300;
      animation: fadeUp 0.6s 0.2s ease both;
    }

    /* ── Info badges ── */

    .badges-row {
      animation: fadeUp 0.6s 0.3s ease both;
    }

    .info-badge {
      background: var(--dark-3);
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 8px;
      padding: 8px 16px;
      font-size: 13px;
      color: var(--text-muted);
      font-weight: 500;
      transition: all 0.3s;
    }

    .info-badge:hover {
      border-color: rgba(201,168,76,0.3);
      color: var(--gold-light);
    }

    /* ── Scan card ── */

    .scan-card {
      background: var(--dark-3);
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 20px;
      padding: 36px;
      position: relative;
      overflow: hidden;
      animation: fadeUp 0.6s 0.4s ease both;
    }

    .scan-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    .card-title {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      font-weight: 700;
      color: white;
      margin-bottom: 6px;
    }

    .card-subtitle {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 24px;
    }

    /* ── Scanner wrapper ── */

    .scanner-wrapper {
    position: relative;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    background: #0a0c10;
    display: none;
    border: 1px solid rgba(201,168,76,0.15);
    }

    .corner {
    position: absolute;
    width: 32px;
    height: 32px;
    z-index: 10;
    pointer-events: none;
    }

    .corner-tl { top: 12px; left: 12px; border-top: 2.5px solid var(--gold); border-left: 2.5px solid var(--gold); border-radius: 4px 0 0 0; }
    .corner-tr { top: 12px; right: 12px; border-top: 2.5px solid var(--gold); border-right: 2.5px solid var(--gold); border-radius: 0 4px 0 0; }
    .corner-bl { bottom: 12px; left: 12px; border-bottom: 2.5px solid var(--gold); border-left: 2.5px solid var(--gold); border-radius: 0 0 0 4px; }
    .corner-br { bottom: 12px; right: 12px; border-bottom: 2.5px solid var(--gold); border-right: 2.5px solid var(--gold); border-radius: 0 0 4px 0; }

    .scan-line {
    position: absolute;
    left: 12px; right: 12px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    z-index: 9;
    animation: scanMove 2s ease-in-out infinite;
    display: none;
    }

    @keyframes scanMove {
    0%   { top: 12px; opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 1; }
    100% { top: calc(100% - 12px); opacity: 0; }
    }

    #qr-reader {
    width: 100% !important;
    border: none !important;
    }

    #qr-reader video {
    width: 100% !important;
    display: block !important;
    }

    #qr-reader__scan_region,
    #qr-reader > div {
    padding: 0 !important;
    border: none !important;
    }

    #qr-reader__dashboard,
    #qr-reader__status_span,
    #qr-reader__filescan-input {
    display: none !important;
    }

    /* ── Button ── */

    .btn-scan {
      background: var(--gold);
      color: var(--dark);
      padding: 12px 36px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 14px;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
      letter-spacing: 0.3px;
    }

    .btn-scan:hover {
      background: var(--gold-light);
      transform: translateY(-1px);
      box-shadow: 0 8px 25px rgba(201,168,76,0.25);
    }

    .btn-scan.stop {
      background: transparent;
      color: #ef4444;
      border: 1px solid rgba(239,68,68,0.3);
    }

    .btn-scan.stop:hover {
      background: rgba(239,68,68,0.08);
      box-shadow: none;
      transform: none;
    }

    /* ── Results ── */

    .result-valid {
      background: rgba(16,185,129,0.08);
      border: 1px solid rgba(16,185,129,0.25);
      border-left: 4px solid #10b981;
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
      display: none;
    }

    .result-invalid {
      background: rgba(239,68,68,0.08);
      border: 1px solid rgba(239,68,68,0.2);
      border-left: 4px solid #ef4444;
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
      display: none;
    }

    .result-title-valid {
      font-family: 'Playfair Display', serif;
      font-size: 16px;
      font-weight: 700;
      color: #10b981;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .result-title-invalid {
      font-family: 'Playfair Display', serif;
      font-size: 16px;
      font-weight: 700;
      color: #ef4444;
      margin-bottom: 8px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      font-size: 13px;
    }

    .info-row:last-child { border-bottom: none; }

    .info-label {
      color: var(--text-muted);
      font-weight: 400;
    }

    .info-value {
      color: white;
      font-weight: 500;
    }

    .info-value.gold { color: var(--gold); }

    /* ── Footer ── */

    .footer {
      margin-top: 60px;
      font-size: 12px;
      color: var(--text-muted);
      letter-spacing: 0.3px;
    }

    /* ── Animations ── */

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

  </style>

</head>
<body>

  <div class="page-bg"></div>
  <div class="page-grid"></div>

  <nav class="navbar navbar-dark navbar-olc">
    <div class="container">
      <a class="navbar-brand" href="/">OLC <span>Kampung Inggris</span></a>
    </div>
  </nav>

  <section class="scan-section">
    <div class="container text-center">

      <div class="page-label">✦ Certificate Verification</div>

      <h1 class="page-title">Scan <span>QR Code</span></h1>

      <p class="page-subtitle">
        Gunakan kamera untuk memindai QR Code pada sertifikat resmi
        Our Learning Center Kampung Inggris Kuningan
      </p>

      <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap badges-row">
        <div class="info-badge">✔ Secure Verification</div>
        <div class="info-badge">⚡ Instant Result</div>
        <div class="info-badge">🎓 Official Certificate System</div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">

          <div class="scan-card">

            <div class="card-title">Scan QR Certificate</div>
            <div class="card-subtitle">Arahkan kamera ke QR Code pada sertifikat</div>

            <div class="scanner-wrapper" id="scannerWrapper">
              <div class="scan-line" id="scanLine"></div>
              <div class="corner corner-tl"></div>
              <div class="corner corner-tr"></div>
              <div class="corner corner-bl"></div>
              <div class="corner corner-br"></div>
              <div id="qr-reader"></div>
            </div>

            <div class="mt-4">
              <button id="toggleScan" class="btn btn-scan">Start Scan</button>
            </div>

            <!-- VALID -->
            <div class="result-valid text-start" id="validCard">
              <div class="result-title-valid">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Certificate Verified
              </div>
              <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">Azka Ainurridho</span>
              </div>
              <div class="info-row">
                <span class="info-label">Program</span>
                <span class="info-value">Intensive English Program</span>
              </div>
              <div class="info-row">
                <span class="info-label">Nilai</span>
                <span class="info-value gold">A — Excellent</span>
              </div>
              <div class="info-row">
                <span class="info-label">No Sertifikat</span>
                <span class="info-value">OLC-ENG-2026-001</span>
              </div>
              <div class="info-row">
                <span class="info-label">Tanggal Terbit</span>
                <span class="info-value">12 March 2026</span>
              </div>
            </div>

            <!-- INVALID -->
            <div class="result-invalid text-start" id="invalidCard">
              <div class="result-title-invalid">Certificate Invalid ✕</div>
              <div style="font-size:13px;color:var(--text-muted);">QR Code tidak terdaftar dalam sistem verifikasi OLC.</div>
            </div>

          </div>

        </div>
      </div>

      <div class="footer">
        Our Learning Center (OLC) Kampung Inggris Kuningan — Certificate Verification System
      </div>

    </div>
  </section>

  <script>

  let scanner;
  let scanning = false;

  function randomValidation() { return Math.random() < 0.5; }
  function showValid() { $("#invalidCard").hide(); $("#validCard").fadeIn(); }
  function showInvalid() { $("#validCard").hide(); $("#invalidCard").fadeIn(); }

  $(document).ready(function () {

    scanner = new Html5Qrcode("qr-reader");

    $("#toggleScan").click(function () {
      if (!scanning) {

        Html5Qrcode.getCameras().then(devices => {
          if (devices.length) {

            $("#scannerWrapper").show();
            $("#scanLine").show();

            scanner.start(
              devices[0].id,
              { fps: 10, qrbox: 250 },
              function (decodedText) {
                let valid = randomValidation();
                if (valid) { showValid(); } else { showInvalid(); }
                scanner.stop();
                scanning = false;
                $("#toggleScan").text("Start Scan").removeClass("stop");
                $("#scannerWrapper").hide();
                $("#scanLine").hide();
              }
            );

            scanning = true;
            $("#toggleScan").text("Stop Scan").addClass("stop");
          }
        });

      } else {

        scanner.stop();
        scanning = false;
        $("#toggleScan").text("Start Scan").removeClass("stop");
        $("#scannerWrapper").hide();
        $("#scanLine").hide();

      }
    });

  });

  </script>

</body>
</html>
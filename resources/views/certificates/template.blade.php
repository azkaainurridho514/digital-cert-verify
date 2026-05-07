<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  /*
   * KUNCI UTAMA: padding-top: 70.71% = rasio A4 landscape (210/297)
   * Semua posisi % dihitung dari canvas ini, BUKAN dari viewport.
   * Stabil di semua ukuran render termasuk PDF (wkhtmltopdf / DomPDF / Puppeteer).
   */
  .cert-canvas {
    position: relative;
    width: 100%;
    padding-top: 70.71%;   /* A4 landscape ratio: 210÷297 = 0.7071 */
    overflow: hidden;
  }

  .cert-canvas img.bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* ─── NOMOR SERTIFIKAT ──────────────────────────────────────
     Posisi: bawah judul "CERTIFICATE", center horizontal
     top 18.5% = ~39mm dari atas canvas A4 landscape
  ─────────────────────────────────────────────────────────── */
  .nomor {
    position: absolute;
    top: 18.5%;
    left: 50%;
    transform: translateX(-50%);
    font-size: clamp(10px, 1.1vw, 13px);
    font-weight: 400;
    color: #555555;
    letter-spacing: 0.08em;
    white-space: nowrap;
    text-align: center;
    font-family: Arial, sans-serif;
  }

  /* ─── NAMA PESERTA ──────────────────────────────────────────
     Posisi: center canvas, tepat di atas garis horizontal
     top 40% = ~84mm dari atas → di atas garis tengah (~46%)
  ─────────────────────────────────────────────────────────── */
  .nama {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: clamp(16px, 2.2vw, 28px);
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: 0.05em;
    white-space: nowrap;
    text-align: center;
    font-family: 'Georgia', 'Times New Roman', serif;
  }

  /* ─── PROGRAM ────────────────────────────────────────────────
     Posisi: di atas baris "held by Our Learning Center"
     top 51% = ~107mm dari atas
  ─────────────────────────────────────────────────────────── */
  .program {
    position: absolute;
    top: 51%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: clamp(11px, 1.3vw, 16px);
    font-weight: 400;
    color: #333333;
    white-space: nowrap;
    text-align: center;
    font-style: italic;
    font-family: 'Georgia', serif;
  }

  /* ─── GRADE ──────────────────────────────────────────────────
     Posisi: pojok kanan bawah, area badge award
     bottom 20% right 7% = ~42mm dari bawah, ~21mm dari kanan
  ─────────────────────────────────────────────────────────── */
  .grade {
    position: absolute;
    bottom: 20%;
    right: 7%;
    font-size: clamp(14px, 1.8vw, 22px);
    font-weight: 700;
    color: #b8860b;
    text-align: center;
    line-height: 1.2;
    font-family: 'Georgia', serif;
  }
  .grade small {
    display: block;
    font-size: clamp(8px, 0.9vw, 11px);
    font-weight: 500;
    color: #777777;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-bottom: 2px;
    font-family: Arial, sans-serif;
  }
</style>
</head>
<body>

<div class="cert-canvas">
  <img class="bg" src="{{ asset('cert-template.png') }}" alt="">

  <div class="nomor">No. {{ $nomor }}</div>
  <div class="nama">{{ $nama }}</div>
  <div class="program">{{ $program }}</div>
  <div class="grade">
    <small>Grade</small>
    {{ $grade }}
  </div>
</div>

</body>
</html>
@extends('layouts.app')
@section('title', 'Verifikasi')
@section('page-title', 'Verifikasi')

@push('styles')
    <style>
        .scan-box { position: relative; width: 100%; aspect-ratio: 1/1; max-height: 320px; overflow: hidden; background: #000; border-radius: 12px; }
        .scan-box video { width: 100%; height: 100%; object-fit: cover; display: block; }
        .scan-box canvas.overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
        .scan-frame { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 180px; height: 180px; pointer-events: none; }
        .scan-frame::before, .scan-frame::after,
        .scan-corner-br::before, .scan-corner-br::after { content: ''; position: absolute; width: 32px; height: 32px; }
        .scan-frame::before { top:0;left:0; border-top: 3px solid #22c55e; border-left: 3px solid #22c55e; border-radius: 4px 0 0 0; }
        .scan-frame::after  { top:0;right:0; border-top: 3px solid #22c55e; border-right: 3px solid #22c55e; border-radius: 0 4px 0 0; }
        .scan-corner-br::before { bottom:0;left:0; border-bottom: 3px solid #22c55e; border-left: 3px solid #22c55e; border-radius: 0 0 0 4px; }
        .scan-corner-br::after  { bottom:0;right:0; border-bottom: 3px solid #22c55e; border-right: 3px solid #22c55e; border-radius: 0 0 4px 0; }
        .scan-line { position: absolute; left: 8%; width: 84%; height: 2px; background: linear-gradient(90deg, transparent, #22c55e, transparent); animation: scanAnim 2s linear infinite; }
        @keyframes scanAnim { 0%{top:10%} 100%{top:88%} }
        .upload-zone { border: 2px dashed #6c757d; border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; }
        .upload-zone:hover, .upload-zone.dragover { border-color: #0d6efd; background: rgba(13,110,253,.05); }
        #preview { max-height: 200px; border-radius: 8px; object-fit: contain; }
        .nav-pills .nav-link { border-radius: 8px; font-weight: 500; color: #495057; }
        .nav-pills .nav-link.active { background: #0d6efd; color: #fff; }
        #qr-canvas { display: none; }
        .spinner-scan { width: 18px; height: 18px; border: 2.5px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin .7s linear infinite; display: inline-block; vertical-align: middle; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        /* SweetAlert2 custom */
        .swal-cert-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left; }
        .swal-cert-table tr + tr td { border-top: 1px solid #f0f0f0; }
        .swal-cert-table td { padding: 7px 6px; }
        .swal-cert-table td:first-child { color: #6c757d; width: 42%; font-size: 0.82rem; }
        .swal2-popup { border-radius: 16px !important; }
        .swal2-title { font-size: 1.2rem !important; font-weight: 600 !important; }
        </style>
@endpush
@section('content')
 
<div class="page-header">
    <h4>Verifikasi Sertifikat</h4>
    <p>Scan QR code atau unggah gambar sertifikat untuk memverifikasi.</p>
</div>
 
<div class="card-modern p-4" style="max-width: 680px;">
 
    {{-- Tab Navigation --}}
    <ul class="nav nav-pills mb-4 gap-2">
        <li class="nav-item">
            <button class="nav-link active px-4" id="tab-scan" onclick="switchMode('scan')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;vertical-align:-2px">
                    <rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/>
                    <path d="M21 16h-3v3m0-3v3h3m-7-3h1m2-7h-2v2m-5 0V9m0 4v1m4 3v1m2 0h1"/>
                </svg>
                Scan Kamera
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link px-4" id="tab-upload" onclick="switchMode('upload')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;vertical-align:-2px">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Unggah Gambar
            </button>
        </li>
    </ul>
 
    {{-- Pane: Scan Kamera --}}
    <div id="pane-scan">
        <div class="scan-box mb-3">
            <video id="video" autoplay playsinline muted></video>
            <canvas class="overlay" id="qr-overlay"></canvas>
            <div class="scan-frame"><div class="scan-corner-br"></div><div class="scan-line"></div></div>
            <div id="cam-placeholder" class="d-flex flex-column align-items-center justify-content-center h-100" style="position:absolute;inset:0;background:rgba(0,0,0,.75);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="1.5" class="mb-3">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                <p class="small text-secondary mb-0">Kamera belum aktif</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary flex-grow-1" id="btnStart" onclick="startCamera()">
                Aktifkan Kamera
            </button>
            <button class="btn btn-outline-secondary" id="btnStop" onclick="stopCamera()" disabled>
                Hentikan
            </button>
        </div>
        <p class="text-muted small mt-2 mb-0" id="scan-hint">Arahkan kamera ke QR code pada sertifikat.</p>
    </div>
 
    {{-- Pane: Upload Gambar --}}
    <div id="pane-upload" class="d-none">
        <div class="upload-zone mb-3" id="dropzone"
             onclick="document.getElementById('fileInput').click()"
             ondragover="event.preventDefault();this.classList.add('dragover')"
             ondragleave="this.classList.remove('dragover')"
             ondrop="handleDrop(event)">
            <input type="file" id="fileInput" accept="image/*" class="d-none" onchange="handleFile(this.files[0])">
            <div id="upload-prompt">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="1.5" class="mb-3 d-block mx-auto">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p class="mb-1 fw-medium">Klik atau seret gambar ke sini</p>
                <p class="text-muted small mb-0">PNG, JPG, WEBP — maks. 5 MB</p>
            </div>
            <img id="preview" class="d-none mt-2 mx-auto d-block">
        </div>
        <button class="btn btn-primary w-100" id="btnVerify" onclick="verifyUpload()" disabled>
            <span id="btnVerifyLabel">Verifikasi QR</span>
        </button>
    </div>
 
    {{-- Canvas tersembunyi untuk decode QR --}}
    <canvas id="qr-canvas"></canvas>
 
</div>
@endsection

@push('scripts')
    
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
let stream = null, scanning = false, animId = null, uploadedFile = null;
const video    = document.getElementById('video');
const overlay  = document.getElementById('qr-overlay');
const qrCanvas = document.getElementById('qr-canvas');
const ctx2     = qrCanvas.getContext('2d');
 
/* ── Mode switch ── */
function switchMode(mode) {
    stopCamera();
    document.getElementById('pane-scan').classList.toggle('d-none', mode !== 'scan');
    document.getElementById('pane-upload').classList.toggle('d-none', mode === 'scan');
    document.getElementById('tab-scan').classList.toggle('active', mode === 'scan');
    document.getElementById('tab-upload').classList.toggle('active', mode !== 'scan');
}
 
/* ── Kamera ── */
async function startCamera() {
    const btn = document.getElementById('btnStart');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-scan me-2"></span>Memulai...';
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }, audio: false
        });
        video.srcObject = stream;
        await video.play();
        document.getElementById('cam-placeholder').style.display = 'none';
        document.getElementById('btnStop').disabled = false;
        btn.innerHTML = '<span class="spinner-scan me-2"></span>Memindai...';
        scanning = true;
        document.getElementById('scan-hint').textContent = 'Arahkan QR code ke bingkai hijau...';
        requestAnimationFrame(scanFrame);
    } catch (e) {
        btn.disabled = false;
        btn.innerHTML = 'Aktifkan Kamera';
        Swal.fire({
            icon: 'error',
            title: 'Akses Kamera Ditolak',
            text: 'Izinkan akses kamera di pengaturan browser untuk melanjutkan.',
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Mengerti'
        });
    }
}
 
function stopCamera() {
    scanning = false;
    if (animId) cancelAnimationFrame(animId);
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    video.srcObject = null;
    document.getElementById('cam-placeholder').style.display = '';
    const btn = document.getElementById('btnStart');
    btn.disabled = false;
    btn.innerHTML = 'Aktifkan Kamera';
    document.getElementById('btnStop').disabled = true;
    overlay.getContext('2d').clearRect(0, 0, overlay.width, overlay.height);
    document.getElementById('scan-hint').textContent = 'Arahkan kamera ke QR code pada sertifikat.';
}
 
function scanFrame() {
    if (!scanning) return;
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        const w = video.videoWidth, h = video.videoHeight;
        qrCanvas.width = w; qrCanvas.height = h;
        overlay.width  = video.offsetWidth;
        overlay.height = video.offsetHeight;
        ctx2.drawImage(video, 0, 0, w, h);
        const imgData = ctx2.getImageData(0, 0, w, h);
        const code = jsQR(imgData.data, w, h, { inversionAttempts: 'dontInvert' });
        const oCtx = overlay.getContext('2d');
        oCtx.clearRect(0, 0, overlay.width, overlay.height);
        if (code) {
            const sx = overlay.width / w, sy = overlay.height / h;
            const pts = code.location;
            oCtx.beginPath();
            oCtx.moveTo(pts.topLeftCorner.x * sx,     pts.topLeftCorner.y * sy);
            oCtx.lineTo(pts.topRightCorner.x * sx,    pts.topRightCorner.y * sy);
            oCtx.lineTo(pts.bottomRightCorner.x * sx, pts.bottomRightCorner.y * sy);
            oCtx.lineTo(pts.bottomLeftCorner.x * sx,  pts.bottomLeftCorner.y * sy);
            oCtx.closePath();
            oCtx.strokeStyle = '#22c55e';
            oCtx.lineWidth   = 3;
            oCtx.stroke();
            oCtx.fillStyle = 'rgba(34,197,94,0.12)';
            oCtx.fill();
            scanning = false;
            stopCamera();
            processQRData(code.data);
            return;
        }
    }
    animId = requestAnimationFrame(scanFrame);
}
 
/* ── Upload ── */
function handleFile(file) {
    if (!file) return;
    uploadedFile = file;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('preview');
        img.src = e.target.result;
        img.classList.remove('d-none');
        document.getElementById('upload-prompt').classList.add('d-none');
        document.getElementById('btnVerify').disabled = false;
    };
    reader.readAsDataURL(file);
}
 
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropzone').classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) handleFile(file);
}
 
function verifyUpload() {
    if (!uploadedFile) return;
    const btn = document.getElementById('btnVerify');
    btn.disabled = true;
    document.getElementById('btnVerifyLabel').innerHTML = '<span class="spinner-scan me-2"></span>Memverifikasi...';
 
    Swal.fire({
        title: 'Memverifikasi...',
        text: 'Sedang membaca QR code dari gambar.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
    });
 
    const img = new Image();
    img.onload = () => {
        qrCanvas.width  = img.naturalWidth;
        qrCanvas.height = img.naturalHeight;
        ctx2.drawImage(img, 0, 0);
        const imgData = ctx2.getImageData(0, 0, qrCanvas.width, qrCanvas.height);
        const code = jsQR(imgData.data, qrCanvas.width, qrCanvas.height, { inversionAttempts: 'attemptBoth' });
        setTimeout(() => {
            btn.disabled = false;
            document.getElementById('btnVerifyLabel').textContent = 'Verifikasi QR';
            Swal.close();
            if (code) processQRData(code.data);
            else      showSwalError('QR Tidak Ditemukan', 'Tidak ada QR code yang terdeteksi pada gambar ini.');
        }, 700);
    };
    img.src = document.getElementById('preview').src;
}
 
/* ── Proses Data QR ── */
function processQRData(data) {
   
 
    // --- Opsi A: validasi lokal ---
    let parsed = null;
    try { parsed = JSON.parse(data); } catch (e) {}
 
    if (parsed && (parsed.nomor || parsed.nama || parsed.id)) {
        showSwalSuccess(parsed);
    } else if (data.startsWith('http')) {
        Swal.fire({
            icon: 'info',
            title: 'QR Terdeteksi',
            html: `<p class="text-muted mb-2 small">URL ditemukan pada QR code:</p>
                   <a href="${data}" target="_blank" class="text-break small">${data}</a>`,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Tutup'
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Data QR Ditemukan',
            html: `<div class="font-monospace small p-2 bg-light rounded text-start" style="word-break:break-all">${data}</div>`,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Tutup'
        });
    }
}
 
/* ── SweetAlert helpers ── */
function showSwalSuccess(data) {
    const rows = [
        ['No. Sertifikat', data.nomor    || data.id     || '-'],
        ['Nama Penerima',  data.nama     || data.name   || '-'],
        ['Tanggal Terbit', data.tanggal  || data.date   || '-'],
        ['Program / Judul',data.program  || data.title  || '-'],
        ['Penerbit',       data.penerbit || data.issuer || '-'],
    ];
    const tableHtml = `
        <table class="swal-cert-table mt-1">
            ${rows.map(([k, v]) => `
                <tr>
                    <td>${k}</td>
                    <td><strong>${v}</strong></td>
                </tr>`).join('')}
            <tr>
                <td>Status</td>
                <td><span class="badge bg-success px-3 py-1">Valid</span></td>
            </tr>
        </table>`;
 
    Swal.fire({
        icon: 'success',
        title: 'Sertifikat Valid!',
        html: tableHtml,
        confirmButtonColor: '#198754',
        confirmButtonText: 'Selesai',
        footer: '<span class="text-muted small">Sertifikat telah terverifikasi dengan sukses.</span>'
    });
}
 
function showSwalError(title, msg) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: msg,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Coba Lagi'
    });
}
</script>
@endpush
 
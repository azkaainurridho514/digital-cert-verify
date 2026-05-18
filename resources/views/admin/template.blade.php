@extends('layouts.app')
@section('title', 'Template Sertifikat')
@section('page-title', 'Template Sertifikat')

@push('styles')
<style>
    :root {
        --c-blue:       #2563eb;
        --c-blue-soft:  #eff6ff;
        --c-blue-mid:   #93c5fd;
        --c-red:        #dc2626;
        --c-slate-50:   #f8fafc;
        --c-slate-100:  #f1f5f9;
        --c-slate-200:  #e2e8f0;
        --c-slate-400:  #94a3b8;
        --c-slate-500:  #64748b;
        --c-slate-700:  #334155;
        --c-slate-900:  #0f172a;
        --c-white:      #ffffff;
        --radius-xs:    6px;
        --radius-sm:    10px;
        --radius-md:    14px;
        --radius-xl:    22px;
        --shadow-sm:    0 1px 3px rgba(0,0,0,.07), 0 4px 12px rgba(0,0,0,.05);
        --shadow-lg:    0 12px 36px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.06);
        --font-sans:    'DM Sans', sans-serif;
        --font-display: 'Sora', sans-serif;
        --transition:   all .2s ease;
    }

    .page-header { margin-bottom: 24px; }
    .page-header h4 { font-family: var(--font-display); font-size: 1.25rem; font-weight: 700; color: var(--c-slate-900); margin-bottom: 4px; }
    .page-header p  { font-size: .85rem; color: var(--c-slate-500); margin: 0; }

    .cert-card { background: var(--c-white); border-radius: var(--radius-xl); border: 1px solid var(--c-slate-200); box-shadow: var(--shadow-sm); overflow: visible; }
    .cert-card-header { display: flex; align-items: center; gap: 12px; padding: 18px 22px; border-bottom: 1px solid var(--c-slate-100); flex-wrap: wrap; }
    .cert-card-header .header-title { font-family: var(--font-display); font-size: .95rem; font-weight: 700; color: var(--c-slate-900); flex: 1; }

    .cert-table-wrap { overflow-x: auto; }
    .cert-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .cert-table thead tr { background: var(--c-slate-50); border-bottom: 1px solid var(--c-slate-200); }
    .cert-table thead th { font-family: var(--font-display); font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--c-slate-400); padding: 11px 14px; white-space: nowrap; }
    .cert-table thead th:first-child { padding-left: 22px; }
    .cert-table thead th:last-child  { padding-right: 22px; }
    .cert-table tbody tr { border-bottom: 1px solid var(--c-slate-100); transition: background .15s; }
    .cert-table tbody tr:last-child { border-bottom: none; }
    .cert-table tbody tr:hover { background: var(--c-slate-50); }
    .cert-table tbody td { padding: 13px 14px; color: var(--c-slate-700); vertical-align: middle; }
    .cert-table tbody td:first-child { padding-left: 22px; }
    .cert-table tbody td:last-child  { padding-right: 22px; }
    .td-muted { color: var(--c-slate-400); font-size: 12.5px; }

    .row-number { width: 24px; height: 24px; border-radius: 6px; background: var(--c-slate-100); color: var(--c-slate-500); font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; font-family: var(--font-display); }
    .tpl-thumb { width: 64px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid var(--c-slate-200); cursor: pointer; transition: opacity .15s; }
    .tpl-thumb:hover { opacity: .8; }
    .tpl-thumb-empty { width: 64px; height: 44px; border-radius: 6px; border: 1px dashed var(--c-slate-300); display: flex; align-items: center; justify-content: center; color: var(--c-slate-400); font-size: 16px; }

    .pos-badge-wrap { display: flex; flex-wrap: wrap; gap: 4px; }
    .pos-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; background: var(--c-blue-soft); color: var(--c-blue); border-radius: 20px; font-size: 11px; font-weight: 600; font-family: var(--font-display); white-space: nowrap; }

    .action-wrap { display: flex; align-items: center; gap: 5px; }
    .act-btn { width: 30px; height: 30px; border: none; border-radius: var(--radius-xs); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; transition: var(--transition); flex-shrink: 0; }
    .act-btn-edit       { background: #eff6ff; color: #2563eb; }
    .act-btn-edit:hover { background: #2563eb; color: #fff; }
    .act-btn-delete       { background: #fef2f2; color: #dc2626; }
    .act-btn-delete:hover { background: #dc2626; color: #fff; }

    .btn-create { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; background: var(--c-blue); color: var(--c-white); border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: var(--font-sans); cursor: pointer; transition: var(--transition); white-space: nowrap; box-shadow: 0 2px 8px rgba(37,99,235,.25); }
    .btn-create:hover { background: #1d4ed8; transform: translateY(-1px); }

    .pagination-wrap { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 16px 22px; border-top: 1px solid var(--c-slate-100); font-size: 13px; }
    .pagination-info { color: var(--c-slate-500); }
    .pagination-info b { color: var(--c-slate-700); font-weight: 600; }
    .per-page-wrap { display: flex; align-items: center; gap: 8px; color: var(--c-slate-500); }
    .per-page-select { border: 1.5px solid var(--c-slate-200); border-radius: var(--radius-xs); padding: 4px 8px; font-size: 12px; color: var(--c-slate-700); background: var(--c-white); outline: none; cursor: pointer; }
    .pg-btn { min-width: 32px; height: 32px; border: 1.5px solid var(--c-slate-200); border-radius: var(--radius-xs); background: var(--c-white); color: var(--c-slate-600); font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); padding: 0 8px; font-family: var(--font-display); }
    .pg-btn:hover:not(:disabled) { border-color: var(--c-blue); color: var(--c-blue); background: var(--c-blue-soft); }
    .pg-btn:disabled { opacity: .35; cursor: not-allowed; }
    .pg-active { background: var(--c-blue) !important; color: #fff !important; border-color: var(--c-blue) !important; }
    .pg-controls { display: flex; align-items: center; gap: 4px; }

    .empty-state { padding: 52px 20px; text-align: center; color: var(--c-slate-400); }
    .empty-state i { font-size: 2.2rem; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 13.5px; margin: 0; }
    .loading-state { padding: 52px 20px; text-align: center; color: var(--c-slate-400); }

    .modal-content { border: none; border-radius: var(--radius-xl) !important; box-shadow: var(--shadow-lg); overflow: hidden; }
    .modal-header  { padding: 20px 24px 16px; border-bottom: 1px solid var(--c-slate-100); background: var(--c-white); display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
    .modal-main-title { font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--c-slate-900); margin: 0 0 3px; }
    .modal-subtitle   { font-size: 12px; color: var(--c-slate-400); margin: 0; }
    .modal-body   { padding: 20px 24px; background: var(--c-white); max-height: 75vh; overflow-y: auto; }
    .modal-footer { padding: 14px 24px; border-top: 1px solid var(--c-slate-100); background: var(--c-slate-50); display: flex; gap: 10px; justify-content: flex-end; }
    .modal-close-btn { width: 32px; height: 32px; border: 1.5px solid var(--c-slate-200); border-radius: 50%; background: var(--c-white); color: var(--c-slate-500); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 17px; transition: var(--transition); flex-shrink: 0; }
    .modal-close-btn:hover { background: var(--c-slate-100); color: var(--c-slate-900); }

    .btn-modal-cancel { border: 1.5px solid var(--c-slate-200); background: var(--c-white); border-radius: var(--radius-sm); padding: 8px 18px; font-size: 13px; font-family: var(--font-sans); color: var(--c-slate-600); cursor: pointer; font-weight: 500; transition: var(--transition); }
    .btn-modal-cancel:hover { background: var(--c-slate-50); }
    .btn-modal-save { display: inline-flex; align-items: center; gap: 7px; border: none; background: var(--c-blue); border-radius: var(--radius-sm); padding: 9px 22px; font-size: 13px; font-weight: 600; font-family: var(--font-sans); color: #fff; cursor: pointer; transition: var(--transition); box-shadow: 0 2px 8px rgba(37,99,235,.2); }
    .btn-modal-save:hover    { background: #1d4ed8; }
    .btn-modal-save:disabled { opacity: .6; cursor: not-allowed; }

    .upload-zone { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; width: 100%; min-height: 150px; border: 2px dashed var(--c-slate-200); border-radius: var(--radius-md); cursor: pointer; transition: border-color .15s, background .15s; padding: 24px; text-align: center; background: var(--c-slate-50); }
    .upload-zone:hover, .upload-zone.drag-over { border-color: var(--c-blue); background: var(--c-blue-soft); }
    .upload-zone p     { font-size: 13px; color: var(--c-slate-500); margin: 0; }
    .upload-zone small { font-size: 11.5px; color: var(--c-slate-400); }
    .upload-zone input { display: none; }

    /* ── Position Finder ── */
    #pfContainer { display: none; flex-direction: row; gap: 16px; align-items: flex-start; }
    #pfLeft  { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 10px; }
    #pfRight { width: 220px; flex-shrink: 0; }

    .change-img-btn { font-size: 12px; color: var(--c-blue); background: none; border: none; cursor: pointer; padding: 0; text-decoration: underline; align-self: flex-start; }

    #pfImgWrap { position: relative; display: block; width: 100%; border-radius: 6px; overflow: hidden; box-shadow: var(--shadow-sm); cursor: crosshair; }
    #pfImgWrap img { display: block; width: 100%; height: auto; user-select: none; pointer-events: none; }

    .pf-drag-box { position: absolute; border-width: 2px; border-style: solid; border-radius: 3px; cursor: move; touch-action: none; user-select: none; z-index: 10; }
    .pf-drag-box.active-box { z-index: 20; outline: 2px solid #fbbf24; outline-offset: 2px; }
    .pf-resize-handle { position: absolute; right: 0; bottom: 0; width: 14px; height: 14px; cursor: se-resize; background: rgba(255,255,255,.8); border-top: 2px solid currentColor; border-left: 2px solid currentColor; border-radius: 2px 0 0 0; }
    .pf-box-label { position: absolute; top: -20px; left: 0; font-size: 10px; font-weight: 600; font-family: var(--font-display); white-space: nowrap; background: rgba(0,0,0,.55); color: #fff; padding: 1px 5px; border-radius: 3px; pointer-events: none; }

    /* ── Panel kanan: field list ── */
    #pfBoxPanel { background: var(--c-slate-50); border: 1px solid var(--c-slate-200); border-radius: var(--radius-md); padding: 12px 14px; }
    #pfBoxPanel .panel-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--c-slate-400); margin-bottom: 10px; }
    #pfBoxList { display: flex; flex-direction: column; gap: 5px; }

    /* item — tidak ada tombol hapus, tidak bisa add baru */
    .pf-box-item { display: flex; align-items: center; gap: 8px; padding: 7px 9px; border-radius: 6px; border: 1px solid var(--c-slate-200); background: var(--c-white); font-size: 12px; cursor: pointer; transition: background .12s; }
    .pf-box-item:hover   { background: var(--c-slate-100); }
    .pf-box-item.selected { background: var(--c-blue-soft); border-color: var(--c-blue-mid); }
    .pf-box-swatch  { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .pf-box-name    { flex: 1; color: var(--c-slate-700); font-family: var(--font-display); font-weight: 600; }
    .pf-box-coords  { color: var(--c-slate-400); font-size: 11px; font-family: monospace; }

    .pf-hint { font-size: 11.5px; color: var(--c-slate-400); text-align: center; margin: 0; }
    .pf-panel-hint { font-size: 11px; color: var(--c-slate-400); margin-top: 10px; line-height: 1.5; }

    @media (max-width: 640px) {
        #pfContainer { flex-direction: column; }
        #pfRight { width: 100%; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h4>Template Sertifikat</h4>
    <p>Kelola template gambar dan posisi elemen pada sertifikat.</p>
</div>

<div class="cert-card">
    <div class="cert-card-header">
        <div class="header-title">Semua Template</div>
        <button class="btn-create" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Tambah Template
        </button>
    </div>

    <div class="cert-table-wrap">
        <table class="cert-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Preview</th>
                    <th>Ukuran</th>
                    <th>Field Posisi</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="6">
                        <div class="loading-state">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Memuat data...
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="paginationWrap"></div>
</div>


{{-- Modal --}}
<div class="modal fade" id="modalTemplate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-main-title" id="modalTitle">Tambah Template</h5>
                    <p class="modal-subtitle">Upload gambar lalu drag setiap box ke posisi yang sesuai</p>
                </div>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div class="modal-body">

                {{-- Upload Zone --}}
                <div id="uploadStep">
                    <label class="upload-zone" for="inputImage" id="uploadZoneLabel">
                        <input type="file" id="inputImage" accept="image/*">
                        <i class="bi bi-cloud-arrow-up" style="font-size:2rem;color:var(--c-slate-400);"></i>
                        <p>Seret gambar ke sini atau klik untuk memilih</p>
                        <small>PNG · JPG · WebP &nbsp;·&nbsp; Maks 5 MB</small>
                    </label>
                    <div id="existingImgHint" style="display:none;margin-top:8px;font-size:12px;color:var(--c-slate-500);">
                        <i class="bi bi-info-circle"></i> Gambar saat ini sudah dimuat. Upload baru hanya jika ingin menggantinya.
                    </div>
                </div>

                {{-- Position Finder — Fixed boxes --}}
                <div id="pfContainer">
                    <div id="pfLeft">
                        <button class="change-img-btn" type="button" onclick="resetImageUpload()">
                            <i class="bi bi-arrow-left"></i> Ganti Gambar
                        </button>
                        <div id="pfImgWrap">
                            <img id="pfImg" src="" alt="template">
                            {{-- 5 fixed boxes dirender oleh JS --}}
                        </div>
                        <p class="pf-hint">Klik field di kanan untuk fokus · Drag box ke posisi yang tepat · Tarik sudut untuk resize</p>
                    </div>

                    <div id="pfRight">
                        <div id="pfBoxPanel">
                            <div class="panel-title">Field Posisi</div>
                            {{-- Di-render oleh pfRenderList() --}}
                            <div id="pfBoxList"></div>
                            <p class="pf-panel-hint">
                                <i class="bi bi-info-circle"></i>
                                Klik field untuk aktifkan, lalu drag box di gambar ke posisi yang diinginkan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-save" id="btnSave" onclick="handleSubmit()">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnSaveLabel">Simpan Template</span>
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// ── URL Constants ──────────────────────────────────────────────────────────────
const URL_DATA   = "{{ route('template.data') }}";
const URL_STORE  = "{{ route('template.store') }}";
const URL_SHOW   = "{{ url('template') }}";
const URL_UPDATE = "{{ url('template') }}";
const CSRF       = "{{ csrf_token() }}";

// ── 5 Fixed Fields — urutan & warna tetap ─────────────────────────────────────
const PREDEFINED_FIELDS = [
    { key: 'name',         label: 'Name',             color: '#3b82f6' },
    { key: 'cert_number',  label: 'Certificate No.',  color: '#ef4444' },
    { key: 'grade',        label: 'Grade',             color: '#22c55e' },
    { key: 'program_name', label: 'Program Name',      color: '#f59e0b' },
    { key: 'publish_date', label: 'Publish Date',      color: '#8b5cf6' },
];

// ── App State ──────────────────────────────────────────────────────────────────
let currentEditId = null;
let currentPage   = 1;
let totalPages    = 1;
let perPage       = 10;
let modalTemplate;
let selectedFile  = null;

// ── Position Finder State ──────────────────────────────────────────────────────
// pfBoxes: { key, label, color, px, py, bw, bh, el }
// key = 'name' | 'cert_number' | 'grade' | 'program_name' | 'publish_date'
let pfBoxes    = [];
let pfActiveKey = null;
let pfNatW = 0, pfNatH = 0;

// ── Init ───────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    modalTemplate = new bootstrap.Modal(document.getElementById('modalTemplate'));
    initFileInput();
    fetchData();
});

// ─────────────────────────────────────────────────────────────────────────────
// TABLE
// ─────────────────────────────────────────────────────────────────────────────

function renderTable(rows) {
    const tbody = document.getElementById('tableBody');

    if (!rows || rows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">
            <div class="empty-state">
                <i class="bi bi-layout-text-sidebar"></i>
                <p>Belum ada template. Tambah template pertama Anda.</p>
            </div>
        </td></tr>`;
        return;
    }

    tbody.innerHTML = rows.map(t => {
        // Tampilkan 5 fixed badge — selalu ada
        const badges = PREDEFINED_FIELDS.map(f =>
            `<span class="pos-badge" style="background:${f.color}18;color:${f.color};">
                ${f.label}
            </span>`
        ).join('');

        return `<tr>
            <td><span class="row-number">${t.no}</span></td>
            <td>
                ${t.path
                    ? `<img src="${t.path}" class="tpl-thumb" alt="preview" onclick="previewImage('${t.path}')">`
                    : `<div class="tpl-thumb-empty"><i class="bi bi-image"></i></div>`}
            </td>
            <td class="td-muted">${t.width} × ${t.height}</td>
            <td><div class="pos-badge-wrap">${badges}</div></td>
            <td class="td-muted">${t.created_at}</td>
            <td>
                <div class="action-wrap">
                    <button class="act-btn act-btn-edit" onclick="openEditModal('${t.id}')" title="Edit">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="act-btn act-btn-delete" onclick="onDelete('${t.id}')" title="Hapus">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ─────────────────────────────────────────────────────────────────────────────
// FETCH & PAGINATION — tidak berubah
// ─────────────────────────────────────────────────────────────────────────────

async function fetchData(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="6">
        <div class="loading-state">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            Memuat data...
        </div>
    </td></tr>`;

    try {
        const params = new URLSearchParams({ page, per_page: perPage });
        const res    = await fetch(`${URL_DATA}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json   = await res.json();
        renderTable(json.data);
        renderPagination(json.meta);
    } catch {
        tbody.innerHTML = `<tr><td colspan="6">
            <div class="empty-state" style="color:#dc2626;">
                <i class="bi bi-exclamation-circle" style="color:#dc2626;"></i>
                <p>Gagal memuat data. Silakan refresh halaman.</p>
            </div>
        </td></tr>`;
    }
}

function renderPagination(meta) {
    if (!meta) return;
    totalPages = meta.last_page;
    const from = meta.from ?? 0, to = meta.to ?? 0, total = meta.total ?? 0;
    let pages = '';
    const start = Math.max(1, currentPage - 2);
    const end   = Math.min(totalPages, currentPage + 2);
    if (start > 1) { pages += pageBtn(1,'1'); if (start>2) pages+=`<span style="padding:0 4px;color:#94a3b8;">…</span>`; }
    for (let i = start; i <= end; i++) pages += pageBtn(i, i, i === currentPage);
    if (end < totalPages) { if (end<totalPages-1) pages+=`<span style="padding:0 4px;color:#94a3b8;">…</span>`; pages += pageBtn(totalPages, totalPages); }

    document.getElementById('paginationWrap').innerHTML = `
        <div class="pagination-wrap">
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <span class="pagination-info">Menampilkan <b>${from}–${to}</b> dari <b>${total}</b> data</span>
                <div class="per-page-wrap">
                    <span>Baris:</span>
                    <select class="per-page-select" onchange="changePerPage(this.value)">
                        ${[10,25,50].map(n=>`<option value="${n}" ${n===perPage?'selected':''}>${n}</option>`).join('')}
                    </select>
                </div>
            </div>
            <div class="pg-controls">
                <button onclick="fetchData(1)" ${currentPage===1?'disabled':''} class="pg-btn"><i class="bi bi-chevron-double-left"></i></button>
                <button onclick="fetchData(${currentPage-1})" ${currentPage===1?'disabled':''} class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                ${pages}
                <button onclick="fetchData(${currentPage+1})" ${currentPage===totalPages?'disabled':''} class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                <button onclick="fetchData(${totalPages})" ${currentPage===totalPages?'disabled':''} class="pg-btn"><i class="bi bi-chevron-double-right"></i></button>
            </div>
        </div>`;
}

function pageBtn(page, label, active = false) {
    return `<button onclick="fetchData(${page})" class="pg-btn ${active?'pg-active':''}">${label}</button>`;
}
function changePerPage(val) { perPage = parseInt(val); fetchData(1); }

// ─────────────────────────────────────────────────────────────────────────────
// POSITION FINDER — Fixed 5 boxes
// ─────────────────────────────────────────────────────────────────────────────

function pfScale()          { return pfNatW ? document.getElementById('pfImg').getBoundingClientRect().width / pfNatW : 1; }
function pfToReal(v)        { return Math.round(v / pfScale()); }
function pfClamp(v, lo, hi) { return Math.max(lo, Math.min(hi, v)); }
function pfGetBox(key)      { return pfBoxes.find(b => b.key === key); }

function pfApplyDOM(b) {
    const wrap = document.getElementById('pfImgWrap');
    b.px = pfClamp(b.px, 0, wrap.clientWidth  - b.bw);
    b.py = pfClamp(b.py, 0, wrap.clientHeight - b.bh);
    b.el.style.left   = b.px + 'px';
    b.el.style.top    = b.py + 'px';
    b.el.style.width  = b.bw + 'px';
    b.el.style.height = b.bh + 'px';
}

function pfApplyColor(el, color) {
    const r = parseInt(color.slice(1,3),16);
    const g = parseInt(color.slice(3,5),16);
    const bl= parseInt(color.slice(5,7),16);
    el.style.borderColor = color;
    el.style.background  = `rgba(${r},${g},${bl},0.13)`;
}

function pfSetActive(key) {
    pfActiveKey = key;
    pfBoxes.forEach(b => b.el.classList.toggle('active-box', b.key === key));
    pfRenderList();
}

function pfRenderList() {
    const list = document.getElementById('pfBoxList');
    list.innerHTML = '';

    pfBoxes.forEach(b => {
        const item = document.createElement('div');
        item.className = 'pf-box-item' + (b.key === pfActiveKey ? ' selected' : '');

        const sw = document.createElement('div');
        sw.className = 'pf-box-swatch';
        sw.style.background = b.color;

        const name = document.createElement('span');
        name.className   = 'pf-box-name';
        name.textContent = b.label;

        const coords = document.createElement('span');
        coords.className   = 'pf-box-coords';
        coords.textContent = `(${pfToReal(b.px)}, ${pfToReal(b.py)})`;

        item.appendChild(sw);
        item.appendChild(name);
        item.appendChild(coords);
        item.addEventListener('click', () => pfSetActive(b.key));
        list.appendChild(item);
    });
}

// Buat satu fixed box — dipanggil saat spawn 5 boxes
function pfCreateBox(field, px, py, bw, bh) {
    const el = document.createElement('div');
    el.className = 'pf-drag-box';
    pfApplyColor(el, field.color);

    const lbl = document.createElement('div');
    lbl.className   = 'pf-box-label';
    lbl.textContent = field.label;
    el.appendChild(lbl);

    const handle = document.createElement('div');
    handle.className   = 'pf-resize-handle';
    handle.style.color = field.color;
    el.appendChild(handle);

    document.getElementById('pfImgWrap').appendChild(el);

    const b = { key: field.key, label: field.label, color: field.color, px, py, bw, bh, el };
    pfBoxes.push(b);
    pfApplyDOM(b);
    pfAttachEvents(b, handle);
    return b;
}

function pfAttachEvents(b, handle) {
    const wrap = document.getElementById('pfImgWrap');
    let dragging = false, offX = 0, offY = 0;
    let resizing = false, rsX = 0, rsY = 0, rsW = 0, rsH = 0;

    b.el.addEventListener('pointerdown', e => {
        if (e.target === handle) return;
        e.preventDefault();
        pfSetActive(b.key);
        dragging = true;
        const rect = wrap.getBoundingClientRect();
        offX = e.clientX - rect.left - b.px;
        offY = e.clientY - rect.top  - b.py;
        b.el.setPointerCapture(e.pointerId);
    });
    b.el.addEventListener('pointermove', e => {
        if (!dragging) return;
        const rect = wrap.getBoundingClientRect();
        b.px = e.clientX - rect.left - offX;
        b.py = e.clientY - rect.top  - offY;
        pfApplyDOM(b);
        pfRenderList();
    });
    b.el.addEventListener('pointerup',     () => dragging = false);
    b.el.addEventListener('pointercancel', () => dragging = false);

    handle.addEventListener('pointerdown', e => {
        e.preventDefault(); e.stopPropagation();
        pfSetActive(b.key);
        resizing = true;
        rsX = e.clientX; rsY = e.clientY;
        rsW = b.bw;      rsH = b.bh;
        handle.setPointerCapture(e.pointerId);
    });
    handle.addEventListener('pointermove', e => {
        if (!resizing) return;
        b.bw = Math.max(20, rsW + (e.clientX - rsX));
        b.bh = Math.max(20, rsH + (e.clientY - rsY));
        pfApplyDOM(b);
        pfRenderList();
    });
    handle.addEventListener('pointerup',     () => resizing = false);
    handle.addEventListener('pointercancel', () => resizing = false);
}

// Click area → pindah box aktif ke titik klik
document.getElementById('pfImgWrap').addEventListener('click', e => {
    if (e.target.closest('.pf-drag-box')) return;
    const b = pfGetBox(pfActiveKey);
    if (!b) return;
    const rect = document.getElementById('pfImgWrap').getBoundingClientRect();
    b.px = (e.clientX - rect.left) - b.bw / 2;
    b.py = (e.clientY - rect.top)  - b.bh / 2;
    pfApplyDOM(b);
    pfRenderList();
});

window.addEventListener('resize', () => { if (pfNatW) pfBoxes.forEach(pfApplyDOM); });

// Kumpulkan posisi sebagai flat object untuk FormData
function pfCollectFields() {
    const out = {};
    pfBoxes.forEach(b => {
        out[`x_position_${b.key}`] = pfToReal(b.px);
        out[`y_position_${b.key}`] = pfToReal(b.py);
    });
    return out;
}

// ─────────────────────────────────────────────────────────────────────────────
// IMAGE LOAD
// ─────────────────────────────────────────────────────────────────────────────

function initFileInput() {
    const input = document.getElementById('inputImage');
    const zone  = document.getElementById('uploadZoneLabel');
    input.addEventListener('change', e => { if (e.target.files[0]) loadImageFile(e.target.files[0]); });
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag-over');
        if (e.dataTransfer.files[0]) loadImageFile(e.dataTransfer.files[0]);
    });
}

function loadImageFile(file) {
    if (!file.type.startsWith('image/')) return;
    selectedFile = file;
    showWithImage(URL.createObjectURL(file), null);
}

/**
 * Tampilkan position finder + spawn 5 fixed boxes.
 * savedData = object dari server { x_position_name, y_position_name, ... }
 *             atau null untuk create mode (semua box mulai di 0,0)
 */
function showWithImage(src, savedData) {
    const pfImg = document.getElementById('pfImg');
    pfImg.onload = function () {
        pfNatW = pfImg.naturalWidth;
        pfNatH = pfImg.naturalHeight;

        document.getElementById('uploadStep').style.display   = 'none';
        document.getElementById('pfContainer').style.display  = 'flex';

        requestAnimationFrame(() => {
            const s = pfScale();

            // Bersihkan boxes lama dari DOM
            pfBoxes.forEach(b => { if (b.el.parentNode) b.el.parentNode.removeChild(b.el); });
            pfBoxes     = [];
            pfActiveKey = null;

            // Spawn 5 fixed boxes
            PREDEFINED_FIELDS.forEach((field, i) => {
                let px, py;
                if (savedData) {
                    // Edit mode: restore dari server (nilai real → pixel)
                    px = (savedData[`x_position_${field.key}`] ?? 0) * s;
                    py = (savedData[`y_position_${field.key}`] ?? 0) * s;
                } else {
                    // Create mode: susun vertikal agar tidak menumpuk
                    px = 10 * s;
                    py = (10 + i * 30) * s;
                }
                pfCreateBox(field, px, py, 120 * s, 24 * s);
            });

            // Aktifkan box pertama
            pfSetActive(PREDEFINED_FIELDS[0].key);
        });
    };
    pfImg.src = src;
}

function resetImageUpload() {
    selectedFile = null;
    pfNatW = pfNatH = 0;
    pfBoxes.forEach(b => { if (b.el.parentNode) b.el.parentNode.removeChild(b.el); });
    pfBoxes     = [];
    pfActiveKey = null;

    document.getElementById('pfImg').src                     = '';
    document.getElementById('inputImage').value              = '';
    document.getElementById('uploadStep').style.display      = 'block';
    document.getElementById('pfContainer').style.display     = 'none';
    document.getElementById('existingImgHint').style.display = 'none';
    pfRenderList();
}

// ─────────────────────────────────────────────────────────────────────────────
// MODAL OPEN
// ─────────────────────────────────────────────────────────────────────────────

function openCreateModal() {
    currentEditId = null;
    resetImageUpload();
    document.getElementById('modalTitle').textContent = 'Tambah Template';
    modalTemplate.show();
}

async function openEditModal(id) {
    currentEditId = id;
    Swal.fire({ title: 'Memuat data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res  = await fetch(`${URL_SHOW}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (!json.success) throw new Error();
        Swal.close();

        const d = json.data;
        resetImageUpload();
        document.getElementById('modalTitle').textContent = 'Edit Template';
        modalTemplate.show();

        if (d.path) {
            document.getElementById('existingImgHint').style.display = 'block';
            // Kirim seluruh data template sebagai savedData
            showWithImage(d.path, d);
        }
    } catch {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data.' });
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// SUBMIT
// ─────────────────────────────────────────────────────────────────────────────

async function handleSubmit() {
    const isEdit = !!currentEditId;

    if (!isEdit && !selectedFile) {
        Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Gambar template wajib dipilih.' });
        return;
    }
    if (!pfNatW) {
        Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Silakan muat gambar template terlebih dahulu.' });
        return;
    }

    const result = await Swal.fire({
        title: isEdit ? 'Update template?' : 'Simpan template?',
        icon: 'question', showCancelButton: true,
        confirmButtonText: isEdit ? 'Ya, Update!' : 'Ya, Simpan!',
        cancelButtonText: 'Batal',
    });
    if (!result.isConfirmed) return;

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    document.getElementById('btnSaveLabel').textContent = 'Menyimpan...';

    try {
        const fields = pfCollectFields(); // { x_position_name: N, y_position_name: N, ... }
        const form   = new FormData();

        if (selectedFile) form.append('image', selectedFile);
        form.append('width',  pfNatW);
        form.append('height', pfNatH);

        // Append flat fields langsung
        Object.entries(fields).forEach(([k, v]) => form.append(k, v));

        if (isEdit) form.append('_method', 'PUT');

        const url = isEdit ? `${URL_UPDATE}/${currentEditId}` : URL_STORE;
        const res = await fetch(url, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body:    form,
        });
        const json = await res.json();

        if (json.success) {
            modalTemplate.hide();
            Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 1500, showConfirmButton: false });
            fetchData(currentPage);
        } else {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: json.message ?? 'Terjadi kesalahan.' });
        }
    } catch {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan.' });
    } finally {
        btn.disabled = false;
        document.getElementById('btnSaveLabel').textContent = 'Simpan Template';
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// DELETE
// ─────────────────────────────────────────────────────────────────────────────

async function onDelete(id) {
    const confirm = await Swal.fire({
        title: 'Hapus Template?', text: 'Template akan dihapus permanen!',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#ef4444', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus',
    });
    if (!confirm.isConfirmed) return;

    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res  = await fetch(`${URL_UPDATE}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.message || 'Gagal menghapus');

        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Template berhasil dihapus.', timer: 1500, showConfirmButton: false });
        fetchData(currentPage);
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: error.message || 'Terjadi kesalahan.' });
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────────────────────

function previewImage(src) {
    Swal.fire({ imageUrl: src, imageAlt: 'Preview Template', showConfirmButton: false, showCloseButton: true });
}
</script>
@endpush
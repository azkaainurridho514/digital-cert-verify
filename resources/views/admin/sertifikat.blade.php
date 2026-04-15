@extends('layouts.app')
@section('title', 'Manajemen Sertifikat')
@section('page-title', 'Manajemen Sertifikat')

@push('styles')
<style>
    .badge-mode   { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
    .badge-add    { background: #dbeafe; color: #1d4ed8; }
    .badge-edit   { background: #fef3c7; color: #92400e; }
    .photo-area   { display: flex; align-items: center; gap: 14px; padding: 14px; background: #f8fafc; border-radius: 12px; border: 1.5px dashed #e2e8f0; }
    .photo-preview{ width: 58px; height: 58px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .form-label-sm { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; display: block; }
    .fi           { width: 100%; border: 1.5px solid #e2e8f0; border-radius: 9px; padding: 8px 12px; font-size: 13px; color: #374151; background: #fff; outline: none; transition: border-color .18s, box-shadow .18s; }
    .fi:hover     { border-color: #93c5fd; }
    .fi:focus     { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi-icon      { position: relative; }
    .fi-icon .fi  { padding-left: 36px; }
    .fi-icon .icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; z-index: 1; }
    .btn-cancel { border: 1.5px solid #e2e8f0; background: #fff; border-radius: 9px; padding: 8px 18px; font-size: 13px; color: #374151; cursor: pointer; }
    .btn-save   { border: none; background: #3b82f6; border-radius: 9px; padding: 8px 22px; font-size: 13px; font-weight: 600; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-save:hover { background: #2563eb; }
    .close-btn { width: 32px; height: 32px; border: none; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; }
    .close-btn i { font-size: 18px; color: #64748b; }
    .close-btn:hover { background: #e2e8f0; }
    .close-btn:hover i { color: #0f172a; }
    .search-item { padding: 10px 12px; cursor: pointer; transition: all 0.2s ease; }
    .search-item:hover { background: #f8fafc; }
    .si-main { display: flex; align-items: center; gap: 10px; }
    .si-avatar { width: 32px; height: 32px; border-radius: 50%; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 13px; }
    .si-info { display: flex; flex-direction: column; }
    .si-name { font-size: 13px; font-weight: 600; color: #111827; }
    .si-meta { font-size: 11px; color: #6b7280; }
    .search-dropdown { box-shadow: 0 10px 25px rgba(0,0,0,0.08); position: absolute; top: 100%; left: 0; right: 0; background: #fff; border-radius: 10px; margin-top: 5px; max-height: 180px; overflow-y: auto; z-index: 9999; }
    .detail-row { display: flex; gap: 8px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { width: 140px; flex-shrink: 0; color: #6b7280; font-weight: 600; font-size: 12px; }
    .detail-value { color: #111827; }
    .card-modern {overflow: visible !important;}

    .pg-btn {
        min-width: 32px; height: 32px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all .18s;
        padding: 0 8px;
    }
    .pg-btn:hover:not(:disabled) { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
    .pg-btn:disabled { opacity: .4; cursor: not-allowed; }
    .pg-active { background: #3b82f6 !important; color: #fff !important; border-color: #3b82f6 !important; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h4>Manajemen Sertifikat</h4>
    <p>Buat dan kelola sertifikat siswa.</p>
</div>

<div class="card-modern">
    <div class="card-header-modern d-flex align-items-center justify-content-between gap-3">
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold" style="font-size: 15px;">Semua Sertifikat</h6>
        </div>

        {{-- Search --}}
        <div class="search-wrap">
            <span class="search-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
                </svg>
            </span>
            <input type="text" class="search-input" id="searchSertifikat"
                   placeholder="Cari nama, sertifikat..."
                   onkeyup="handleSearch(this)">
            <button class="clear-btn" id="clearSearch" onclick="clearSearchInput()" style="display:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Filter Tahun --}}
        <div class="cs" id="filterTahun">
            <button class="cs-btn" type="button" onclick="tog('filterTahun')" style="min-width: 175px;">
                <svg class="ico" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="#3b82f6">
                    <rect x="3" y="4" width="18" height="18" rx="3"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="lbl" id="filterTahun-lbl">Semua Tahun</span>
                <svg class="arr" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
            <div class="cs-menu" id="filterTahun-m">
                <div class="cs-item on" data-value="" onclick="pick('filterTahun', this)">
                    <span class="idot"></span> Semua Tahun
                    <svg class="chk" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="cs-sep"></div>
                @foreach(['2022 / 2023', '2023 / 2024', '2024 / 2025', '2025 / 2026'] as $tahun)
                <div class="cs-item" data-value="{{ $tahun }}" onclick="pick('filterTahun', this)">
                    <span class="idot"></span> {{ $tahun }}
                    <svg class="chk" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                @endforeach
            </div>
        </div>

        <button class="btn-save" onclick="openModalTambah()">
            <i class="bi bi-plus-lg"></i> Buat Sertifikat
        </button>
    </div>

    <div class="p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 13px;">
                <thead>
                    <tr class="table-light">
                        <th class="px-4 py-3 fw-semibold text-secondary">No</th>
                        <th class="py-3 fw-semibold text-secondary">Nama Siswa</th>
                        <th class="py-3 fw-semibold text-secondary">Nomor Sertifikat</th>
                        <th class="py-3 fw-semibold text-secondary">Nilai</th>
                        <th class="py-3 fw-semibold text-secondary">Nama Program</th>
                        <th class="py-3 fw-semibold text-secondary">Level</th>
                        <th class="py-3 fw-semibold text-secondary">Deskripsi</th>
                        <th class="py-3 fw-semibold text-secondary">Tanggal Terbit</th>
                        <th class="py-3 fw-semibold text-secondary">Status</th>
                        <th class="py-3 fw-semibold text-secondary">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr id="loadingRow">
                        <td colspan="10" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="paginationWrap"></div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════ MODAL TAMBAH ═══ --}}
<div class="modal fade" id="modalSertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="modal-header" style="padding: 18px 22px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="modal-title fw-bold mb-0" style="font-size: 15px;">Tambah Sertifikat</h5>
                        <span class="badge-mode badge-add"><i class="bi bi-plus-lg"></i> Baru</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 12px;">Lengkapi data sertifikat dengan benar</p>
                </div>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>

            <div class="modal-body" style="padding: 20px 22px; display: flex; flex-direction: column; gap: 14px;">

                {{-- Nomor Sertifikat --}}
                <div>
                    <label class="form-label-sm">Nomor Sertifikat</label>
                    <div class="fi-icon">
                        <span class="icon"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" class="fi" id="inputCertificateNumber" placeholder="Auto / Generate">
                    </div>
                </div>

                {{-- Nama Siswa (Searchable) --}}
                <div>
                    <label class="form-label-sm">Nama Siswa <span class="text-danger">*</span></label>
                    <div class="fi-icon position-relative">
                        <span class="icon"><i class="bi bi-person"></i></span>
                        <input type="text" class="fi" id="inputStudent"
                               placeholder="Cari nama siswa..."
                               onkeyup="searchStudent(this.value)" autocomplete="off">
                        <div id="studentResult" class="search-dropdown"></div>
                    </div>
                    <input type="hidden" id="inputStudentId">
                    <div id="studentPreview" class="mt-2 text-muted" style="font-size:12px;"></div>
                </div>

                {{-- Program (Searchable) --}}
                <div>
                    <label class="form-label-sm">Program <span class="text-danger">*</span></label>
                    <div class="fi-icon position-relative">
                        <span class="icon"><i class="bi bi-book"></i></span>
                        <input type="text" class="fi" id="inputProgram"
                               placeholder="Cari program..."
                               onkeyup="searchProgram(this.value)" autocomplete="off">
                        <div id="programResult" class="search-dropdown"></div>
                    </div>
                    <input type="hidden" id="inputProgramId">
                    <div id="programPreview" class="mt-2 text-muted" style="font-size:12px;"></div>
                </div>

                {{-- Nilai & Level --}}
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">Nilai</label>
                        <input type="text" class="fi" placeholder="A / B / C" id="inputGrade">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Level</label>
                        <select class="fi" id="inputLevel">
                            <option value="">-- Pilih Level --</option>
                            <option>Beginner</option>
                            <option>Intermediate</option>
                            <option>Advanced</option>
                        </select>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="form-label-sm">Deskripsi</label>
                    <textarea class="fi" rows="3" placeholder="Deskripsi sertifikat..." id="inputDescription"></textarea>
                </div>

            </div>

            <div class="modal-footer" style="padding: 14px 22px; background: #fafafa; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-save" id="btnSave" onclick="confirmSubmit()">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnSaveLabel">Simpan Sertifikat</span>
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════ MODAL DETAIL ═══ --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="padding: 18px 22px; border-bottom: 1px solid #f1f5f9;">
                <h5 class="modal-title fw-bold mb-0" style="font-size: 15px;">Detail Sertifikat</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>
            <div class="modal-body" style="padding: 20px 22px;" id="detailBody">
                <div class="text-center py-3 text-muted">
                    <div class="spinner-border spinner-border-sm"></div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 14px 22px; background: #fafafa; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-save" id="btnPrintFromDetail" onclick="doPrint()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// ── Konstanta URL ─────────────────────────────────────────────────────────────
const URL_DATA     = "{{ route('admin.sertifikat.data') }}";
const URL_STORE    = "{{ route('admin.sertifikat.store') }}";
const URL_SHOW     = "{{ url('admin/sertifikat') }}";   // /{id}
const URL_PRINT    = "{{ url('admin/sertifikat') }}";   // /{id}/print
const URL_STUDENTS = "{{ route('admin.sertifikat.students') }}";
const URL_PROGRAMS = "{{ route('admin.sertifikat.programs') }}";
const CSRF         = "{{ csrf_token() }}";
const URL_UPDATE   = "{{ url('admin/sertifikat') }}";   // /{id} PUT

// ── State ─────────────────────────────────────────────────────────────────────
let currentDetailId = null;
let activeFilter    = '';
let activeSearch    = '';
let searchTimer     = null;
let studentTimer    = null;
let programTimer    = null;

// ── Bootstrap modals ──────────────────────────────────────────────────────────
let modalSertifikat, modalDetail;
document.addEventListener('DOMContentLoaded', () => {
    modalSertifikat = new bootstrap.Modal(document.getElementById('modalSertifikat'));
    modalDetail     = new bootstrap.Modal(document.getElementById('modalDetail'));
    fetchData();
});

function renderTable(rows) {
    const tbody = document.getElementById('tableBody');
    if (!rows || rows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">
            <i class="bi bi-inbox me-2"></i>Tidak ada sertifikat ditemukan.</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(cert => `
        <tr>
            <td class="px-4 py-3">${cert.no}</td>
            <td class="py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                        ${cert.user_name.substring(0, 2).toUpperCase()}
                    </div>
                    ${cert.user_name}
                </div>
            </td>
            <td class="py-3">${cert.certificate_number}</td>
            <td class="py-3 text-muted">${cert.grade}</td>
            <td class="py-3 text-muted">${cert.program_name}</td>
            <td class="py-3 text-muted">${cert.level}</td>
            <td class="py-3 text-muted">${cert.description}</td>
            <td class="py-3 text-muted">${cert.issued_date}</td>
            <td class="py-3">${renderBadge(cert.status)}</td>
            <td class="py-3">
                <span class="badge text-bg-primary" style="cursor:pointer;" onclick="onChangeStatus(${cert.id},${cert.user_id})" title="Change Status">
                    <i class="bi bi-pencil"></i>
                </span>
                <span class="badge text-bg-info" style="cursor:pointer;" onclick="onDetail(${cert.id})" title="Detail">
                    <i class="bi bi-eye"></i>
                </span>
                ${cert.has_file && cert.status === 'Di Terbitkan'
                    ? `<span class="badge text-bg-warning" style="cursor:pointer;" onclick="onPrint(${cert.id})" title="Cetak">
                           <i class="bi bi-printer"></i>
                       </span>`
                    : `<span class="badge text-bg-secondary" title="File belum tersedia" style="opacity:.5;">
                           <i class="bi bi-printer"></i>
                       </span>`
                }
            </td>
        </tr>
    `).join('');
}

function renderBadge(status) {
    if (status === 'Di Terbitkan') return `<span class="badge bg-success-subtle text-success rounded-pill"><i class="bi bi-check-circle-fill me-1"></i>${status}</span>`;
    if (status === 'Di Proses')   return `<span class="badge bg-warning-subtle text-warning rounded-pill"><i class="bi bi-clock-fill me-1"></i>${status}</span>`;
    return `<span class="badge bg-secondary-subtle text-secondary rounded-pill"><i class="bi bi-pencil-fill me-1"></i>${status}</span>`;
}

let currentPage = 1;
let totalPages  = 1;
let perPage     = 10;

async function fetchData(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">
        <div class="spinner-border spinner-border-sm me-2"></div>Memuat data...</td></tr>`;
    try {
        const params = new URLSearchParams();
        if (activeSearch) params.set('search', activeSearch);
        if (activeFilter) params.set('tahun',  activeFilter);
        params.set('page',     page);
        params.set('per_page', perPage);
        const res  = await fetch(`${URL_DATA}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        renderTable(json.data);
        renderPagination(json.meta);  
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-danger">
            <i class="bi bi-exclamation-circle me-2"></i>Gagal memuat data.</td></tr>`;
    }
}

function renderPagination(meta) {
    if (!meta) return;

    totalPages = meta.last_page;
    const from  = meta.from ?? 0;
    const to    = meta.to   ?? 0;
    const total = meta.total ?? 0;

    let pages = '';

    const range = 2;
    const start = Math.max(1, currentPage - range);
    const end   = Math.min(totalPages, currentPage + range);

    if (start > 1) {
        pages += pageBtn(1, '1');
        if (start > 2) pages += `<span class="px-2 text-muted">…</span>`;
    }

    for (let i = start; i <= end; i++) {
        pages += pageBtn(i, i, i === currentPage);
    }

    if (end < totalPages) {
        if (end < totalPages - 1) pages += `<span class="px-2 text-muted">…</span>`;
        pages += pageBtn(totalPages, totalPages);
    }

    document.getElementById('paginationWrap').innerHTML = `
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-4 py-3"
             style="border-top: 1px solid #f1f5f9; font-size: 13px;">

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">
                    Menampilkan <b>${from}–${to}</b> dari <b>${total}</b> data
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Baris:</span>
                    <select onchange="changePerPage(this.value)"
                            style="border:1.5px solid #e2e8f0; border-radius:8px; padding:4px 8px; font-size:12px; color:#374151; outline:none;">
                        ${[10, 25, 50, 100].map(n =>
                            `<option value="${n}" ${n === perPage ? 'selected' : ''}>${n}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">
                <button onclick="fetchData(1)" ${currentPage === 1 ? 'disabled' : ''}
                        class="pg-btn" title="Halaman pertama">
                    <i class="bi bi-chevron-double-left"></i>
                </button>
                <button onclick="fetchData(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                        class="pg-btn" title="Sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>

                ${pages}

                <button onclick="fetchData(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                        class="pg-btn" title="Berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button onclick="fetchData(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}
                        class="pg-btn" title="Halaman terakhir">
                    <i class="bi bi-chevron-double-right"></i>
                </button>
            </div>
        </div>
    `;
}

function pageBtn(page, label, active = false) {
    return `<button onclick="fetchData(${page})"
                class="pg-btn ${active ? 'pg-active' : ''}">${label}</button>`;
}

function changePerPage(val) {
    perPage = parseInt(val);
    fetchData(1);
}

function handleSearch(el) {
    document.getElementById('clearSearch').style.display = el.value ? 'flex' : 'none';
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => { activeSearch = el.value.trim(); fetchData(); }, 500);
}
function clearSearchInput() {
    document.getElementById('searchSertifikat').value = '';
    document.getElementById('clearSearch').style.display = 'none';
    clearTimeout(searchTimer);
    activeSearch = '';
    fetchData();
}

function tog(id) {
    const btn  = document.querySelector('#' + id + ' .cs-btn');
    const menu = document.getElementById(id + '-m');
    const isOpen = menu.classList.contains('open');
    closeAllCS();
    if (!isOpen) { btn.classList.add('active'); menu.classList.add('open'); }
}
function pick(id, el) {
    document.querySelectorAll('#' + id + '-m .cs-item').forEach(i => i.classList.remove('on'));
    el.classList.add('on');
    const val = el.getAttribute('data-value');
    const lbl = val || 'Semua Tahun';
    document.getElementById(id + '-lbl').textContent = lbl;
    closeAllCS();
    activeFilter = val;
    fetchData();
}
function closeAllCS() {
    document.querySelectorAll('.cs-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.cs-menu').forEach(m => m.classList.remove('open'));
}
document.addEventListener('click', e => { if (!e.target.closest('.cs')) closeAllCS(); });

function openModalTambah() {
    ['inputCertificateNumber','inputStudent','inputProgram','inputGrade','inputDescription','inputStudentId','inputProgramId']
        .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    document.getElementById('inputLevel').selectedIndex = 0;
    document.getElementById('studentPreview').innerHTML = '';
    document.getElementById('programPreview').innerHTML = '';
    document.getElementById('studentResult').innerHTML  = '';
    document.getElementById('programResult').innerHTML  = '';
    modalSertifikat.show();
}

async function confirmSubmit() {
    const userId    = document.getElementById('inputStudentId').value;
    const programId = document.getElementById('inputProgramId').value;

    if (!userId || !programId) {
        await Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Siswa dan Program wajib dipilih.',
            confirmButtonColor: '#3b82f6',
        });
        return;
    }
    const result = await Swal.fire({
        title: 'Simpan data?',
        text: "Pastikan data sudah benar!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        reverseButtons: true,
    });

    if (!result.isConfirmed) return;

    await submitFormSertifikat(userId, programId);
}

async function submitFormSertifikat(userId, programId) {

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    document.getElementById('btnSaveLabel').textContent = 'Menyimpan...';

    try {
        const res  = await fetch(URL_STORE, {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                user_id:            userId,
                program_id:         programId,
                certificate_number: document.getElementById('inputCertificateNumber').value,
                grade:              document.getElementById('inputGrade').value,
                description:        document.getElementById('inputDescription').value,
            }),
        });
        const json = await res.json();
        if (json.success) {
            modalSertifikat.hide();
            fetchData();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: json.message ?? 'Terjadi kesalahan.',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#3b82f6',
                borderRadius: '12px',
            });
        }
    } catch (e) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Gagal menyimpan. Coba lagi.',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#3b82f6',
            borderRadius: '12px',
        });
    } finally {
        btn.disabled = false;
        document.getElementById('btnSaveLabel').textContent = 'Simpan Sertifikat';
    }
}

function searchStudent(keyword) {
    clearTimeout(studentTimer);
    const result = document.getElementById('studentResult');
    if (!keyword) { result.innerHTML = ''; return; }

    studentTimer = setTimeout(async () => {
        const res   = await fetch(`${URL_STUDENTS}?search=${encodeURIComponent(keyword)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data  = await res.json();
        result.innerHTML = data.length
            ? data.map(s => `
                <div class="search-item" onclick="selectStudent(${s.id}, '${s.name}', '${s.email}')">
                    <div class="si-main">
                        <div class="si-avatar">${s.name.charAt(0)}</div>
                        <div class="si-info">
                            <div class="si-name">${s.name}</div>
                            <div class="si-meta">${s.email}</div>
                        </div>
                    </div>
                </div>`).join('')
            : `<div class="search-item text-muted">Tidak ditemukan</div>`;
    }, 300);
}
function selectStudent(id, name, email) {
    document.getElementById('inputStudent').value   = name;
    document.getElementById('inputStudentId').value = id;
    document.getElementById('studentResult').innerHTML = '';
    document.getElementById('studentPreview').innerHTML = `Dipilih: <b>${name}</b> (${email})`;
}

function searchProgram(keyword) {
    clearTimeout(programTimer);
    const result = document.getElementById('programResult');
    if (!keyword) { result.innerHTML = ''; return; }

    programTimer = setTimeout(async () => {
        const res  = await fetch(`${URL_PROGRAMS}?search=${encodeURIComponent(keyword)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        result.innerHTML = data.length
            ? data.map(p => `
                <div class="search-item" onclick="selectProgram(${p.id}, '${p.name}', '${p.code}')">
                    <div class="si-main">
                        <div class="si-avatar">${p.name.charAt(0)}</div>
                        <div class="si-info">
                            <div class="si-name">${p.name}</div>
                            <div class="si-meta">${p.code}</div>
                        </div>
                    </div>
                </div>`).join('')
            : `<div class="search-item text-muted">Tidak ditemukan</div>`;
    }, 300);
}
function selectProgram(id, name, code) {
    document.getElementById('inputProgram').value   = name;
    document.getElementById('inputProgramId').value = id;
    document.getElementById('programResult').innerHTML = '';
    document.getElementById('programPreview').innerHTML = `Dipilih: <b>${name}</b> (${code})`;
}

async function onDetail(id) {
    currentDetailId = id;
    document.getElementById('detailBody').innerHTML = `
        <div class="text-center py-3 text-muted">
            <div class="spinner-border spinner-border-sm"></div>
        </div>`;
    document.getElementById('btnPrintFromDetail').style.display = 'none';
    modalDetail.show();

    try {
        const res  = await fetch(`${URL_SHOW}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (!json.success) throw new Error();
        const d = json.data;

        document.getElementById('detailBody').innerHTML = `
            <div class="detail-row"><div class="detail-label">Nama Siswa</div><div class="detail-value">${d.user_name}</div></div>
            <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${d.user_email}</div></div>
            <div class="detail-row"><div class="detail-label">No. Sertifikat</div><div class="detail-value"><code>${d.certificate_number}</code></div></div>
            <div class="detail-row"><div class="detail-label">Program</div><div class="detail-value">${d.program_name} <span class="text-muted">(${d.program_code})</span></div></div>
            <div class="detail-row"><div class="detail-label">Nilai</div><div class="detail-value">${d.grade}</div></div>
            <div class="detail-row"><div class="detail-label">Deskripsi</div><div class="detail-value">${d.description}</div></div>
            <div class="detail-row"><div class="detail-label">Tanggal Terbit</div><div class="detail-value">${d.issued_date}</div></div>
            <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value">${renderBadge(d.status)}</div></div>
        `;

        if (d.has_file && d.status === 'Di Terbitkan') {
            document.getElementById('btnPrintFromDetail').style.display = 'inline-flex';
        }
    } catch (e) {
        document.getElementById('detailBody').innerHTML =
            `<div class="text-danger text-center py-3">Gagal memuat detail.</div>`;
    }
}
async function generateCertNumber(certId) {
    const btn = document.getElementById('btnGenerate');
    if (btn) { btn.disabled = true; btn.textContent = '...'; }
 
    try {
        const res  = await fetch(`${URL_UPDATE}/${certId}/generate-cert-number`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN':     CSRF,
            },
        });
        const json = await res.json();
        const input = document.getElementById('swalCertNumber');
        if (json.success && input) {
            input.value = json.certificate_number;
        } else {
            Swal.showValidationMessage(json.message ?? 'Gagal generate nomor.');
        }
    } catch {
        Swal.showValidationMessage('Terjadi kesalahan saat generate nomor.');
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = 'Generate'; }
    }
}
 
async function onChangeStatus(id, userId) {
    currentDetailId = id;
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });
 
    try {
        const res  = await fetch(`${URL_SHOW}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (!json.success) throw new Error();
        Swal.close();
 
        const d        = json.data;
        const programs = json.programs ?? [];   
        if (d.status === 'Di Terbitkan') {
            Swal.fire({
                icon: 'info',
                title: 'Tidak Dapat Diubah',
                html: `Sertifikat <b>${d.certificate_number}</b> atas nama <b>${d.user_name}</b>
                       sudah diterbitkan dan tidak dapat diubah kembali.`,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#3b82f6',
                customClass: { popup: 'swal-popup-custom' },
            });
            return;
        }
 
        // ── Radio status ─────────────────────────────────────────────────────
        const statuses     = ['Draft', 'Di Proses', 'Di Terbitkan'];
        const radioOptions = statuses.map(s => `
            <label style="
                display:flex; align-items:center; gap:12px;
                padding:10px 14px; border-radius:10px; cursor:pointer;
                border:1.5px solid ${d.status === s ? '#3b82f6' : '#e2e8f0'};
                background:${d.status === s ? '#eff6ff' : '#fff'};
                margin-bottom:8px; transition:all .2s;
            " onclick="highlightStatus(this)">
                <input type="radio" name="certStatus" value="${s}"
                    ${d.status === s ? 'checked' : ''}
                    style="accent-color:#3b82f6; width:16px; height:16px;">
                <span style="display:flex; flex-direction:column; align-items:flex-start;">
                    <span style="font-weight:600; font-size:13px; color:#111827;">${s}</span>
                    <span style="font-size:11px; color:#6b7280;">${statusDesc(s)}</span>
                </span>
            </label>
        `).join('');
 
        // ── Options program ──────────────────────────────────────────────────
        const programOptions = programs.map(p =>
            `<option value="${p.id}"
                ${p.name === d.program_name ? 'selected' : ''}>
                ${p.name} (${p.code})
             </option>`
        ).join('');
 
        // ── Level options ────────────────────────────────────────────────────
        const levels = ['Beginner', 'Intermediate', 'Advanced'];
        const selectedLevel = (d.level && d.level !== '-' ? d.level : '')
            .trim()
            .toLowerCase();

        const levelOptions = levels.map(lv => {
            const isSelected = selectedLevel === lv.toLowerCase();
            return `<option value="${lv}" ${isSelected ? 'selected' : ''}>${lv}</option>`;
        }).join('');
 
        // ── Style helpers ─────────────────────────────────────────────────────
        const inputStyle = 'width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:8px 12px;font-size:13px;color:#374151;outline:none;background:#fff;';
        const labelStyle = 'font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px;';
        const wrapStyle  = 'margin-bottom:10px;';
 
        Swal.fire({
            title: `<span style="font-size:15px;font-weight:700;">Ubah Sertifikat</span>`,
            html: `
                <p style="font-size:12px;color:#6b7280;margin-bottom:14px;">
                    Siswa: <b>${d.user_name}</b>
                </p>
 
                <!-- Status -->
                <div id="statusOptions" style="margin-bottom:14px;text-align:left;">${radioOptions}</div>
 
                <!-- Fields -->
                <div style="text-align:left;">
 
                    <!-- Nomor Sertifikat -->
                    <div style="${wrapStyle}">
                        <label style="${labelStyle}">
                            Nomor Sertifikat
                            <span style="color:#ef4444;">*</span>
                            <span style="font-weight:400;color:#6b7280;"> (wajib jika Di Terbitkan)</span>
                        </label>
                        <div style="display:flex;gap:6px;">
                            <input id="swalCertNumber" type="text"
                                value="${d.certificate_number !== '-' ? d.certificate_number : ''}"
                                placeholder="Nomor sertifikat..."
                                style="flex:1;border:1.5px solid #e2e8f0;border-radius:9px;padding:8px 12px;font-size:13px;color:#374151;outline:none;">
                            <button id="btnGenerate" type="button"
                                onclick="generateCertNumber(${id})"
                                style="border:none;background:#3b82f6;color:#fff;border-radius:9px;padding:8px 14px;font-size:12px;font-weight:600;cursor:pointer;white-space:nowrap;">
                                Generate
                            </button>
                        </div>
                    </div>
 
                    <!-- Program -->
                    <div style="${wrapStyle}">
                        <label style="${labelStyle}">
                            Program
                            <span style="color:#ef4444;">*</span>
                            <span style="font-weight:400;color:#6b7280;"> (wajib jika Di Terbitkan)</span>
                        </label>
                        <select id="swalProgramId" style="${inputStyle}">
                            <option value="">-- Pilih Program --</option>
                            ${programOptions}
                        </select>
                    </div>
 
                    <!-- Nilai & Level (2 kolom) -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;${wrapStyle}">
                        <div>
                            <label style="${labelStyle}">
                                Nilai
                                <span style="color:#ef4444;">*</span>
                                <span style="font-weight:400;color:#6b7280;"> (wajib Di Terbitkan)</span>
                            </label>
                            <input id="swalGrade" type="text"
                                value="${d.grade !== '-' ? d.grade : ''}"
                                placeholder="A / B / C"
                                style="${inputStyle}">
                        </div>
                        <div>
                            <label style="${labelStyle}">
                                Level
                                <span style="color:#ef4444;">*</span>
                                <span style="font-weight:400;color:#6b7280;"> (wajib Di Terbitkan)</span>
                            </label>
                            <select id="swalLevel" style="${inputStyle}">
                                <option value="">-- Pilih Level --</option>
                                ${levelOptions}
                            </select>
                        </div>
                    </div>
 
                    <!-- Deskripsi -->
                    <div>
                        <label style="${labelStyle}">Deskripsi</label>
                        <textarea id="swalDescription" rows="2"
                            placeholder="Deskripsi sertifikat..."
                            style="${inputStyle}resize:vertical;"
                        >${d.description !== '-' ? d.description : ''}</textarea>
                    </div>
 
                </div>
            `,
            showCancelButton:   true,
            confirmButtonText:  '<i class="bi bi-check-lg"></i> Simpan',
            cancelButtonText:   'Batal',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor:  '#e2e8f0',
            customClass: {
                cancelButton: 'swal-cancel-custom',
                popup:        'swal-popup-custom',
            },
            didOpen: () => {
                const style = document.createElement('style');
                style.innerHTML = `
                    .swal-cancel-custom { color: #374151 !important; font-weight: 600 !important; }
                    .swal-popup-custom  { border-radius: 16px !important; }
                `;
                document.head.appendChild(style);
            },
            preConfirm: async () => {
                const selected = document.querySelector('input[name="certStatus"]:checked');
                if (!selected) {
                    Swal.showValidationMessage('Pilih salah satu status terlebih dahulu.');
                    return false;
                }

                const popup = Swal.getPopup();
 
                // const certNumber  = document.getElementById('swalCertNumber').value.trim();
                // const programId   = document.getElementById('swalProgramId').value;
                // const grade = document.getElementById('swalGrade')?.value ?? '';
                // const level = document.getElementById('swalLevel')?.value ?? '';
                // const description = document.getElementById('swalDescription').value.trim();
                const certNumber  = popup.querySelector('#swalCertNumber')?.value.trim() ?? '';
                const programId   = popup.querySelector('#swalProgramId')?.value ?? '';
                const grade       = popup.querySelector('#swalGrade')?.value ?? '';
                const level       = popup.querySelector('#swalLevel')?.value ?? '';
                const description = popup.querySelector('#swalDescription')?.value.trim() ?? '';
 
                // Validasi wajib jika Di Terbitkan
                if (selected.value === 'Di Terbitkan') {
                    if (!certNumber) {
                        Swal.showValidationMessage('Nomor sertifikat wajib diisi untuk status Di Terbitkan.');
                        return false;
                    }
                    if (!programId) {
                        Swal.showValidationMessage('Program wajib dipilih untuk status Di Terbitkan.');
                        return false;
                    }
                    if (!grade) {
                        Swal.showValidationMessage('Nilai wajib diisi untuk status Di Terbitkan.');
                        return false;
                    }
                    if (!level) {
                        Swal.showValidationMessage('Level wajib dipilih untuk status Di Terbitkan.');
                        return false;
                    }
 
                    // Konfirmasi permanen
                    const konfirmasi = await Swal.fire({
                        icon: 'warning',
                        title: 'Yakin menerbitkan?',
                        html: `Status <b>Di Terbitkan</b> bersifat permanen dan
                               <b>tidak dapat diubah kembali</b>.<br>
                               Pastikan semua data sudah benar sebelum melanjutkan.`,
                        showCancelButton:   true,
                        confirmButtonText:  'Ya, Terbitkan!',
                        cancelButtonText:   'Tinjau Ulang',
                        confirmButtonColor: '#22c55e',
                        cancelButtonColor:  '#e2e8f0',
                        customClass: {
                            cancelButton: 'swal-cancel-custom',
                            popup:        'swal-popup-custom',
                        },
                    });
                    if (!konfirmasi.isConfirmed) return false;
                }
 
                Swal.showLoading();
 
                try {
                    const r = await fetch(`${URL_UPDATE}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type':     'application/json',
                            'X-CSRF-TOKEN':     CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            status:             selected.value,
                            user_id:            userId,
                            program_id:         programId || d.program_id,
                            certificate_number: certNumber,
                            grade:              grade,
                            level:              level,
                            description:        description,
                        }),
                    });
                    const j = await r.json();
                    if (!j.success) {
                        Swal.showValidationMessage(j.message ?? 'Gagal menyimpan perubahan.');
                        return false;
                    }
                    return j;
                } catch {
                    Swal.showValidationMessage('Terjadi kesalahan. Coba lagi.');
                    return false;
                }
            },
        }).then(result => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    icon:              'success',
                    title:             'Berhasil disimpan!',
                    text:              `Status diubah menjadi "${result.value.data?.status ?? ''}"`,
                    timer:             1800,
                    showConfirmButton: false,
                    iconColor:         '#22c55e',
                    customClass:       { popup: 'swal-popup-custom' },
                });
                fetchData();
            }
        });
 
    } catch (e) {
        Swal.fire({
            icon:               'error',
            title:              'Gagal',
            text:               'Tidak dapat memuat data sertifikat.',
            confirmButtonColor: '#3b82f6',
        });
    }
}
 
function statusDesc(s) {
    if (s === 'Draft')        return 'Sertifikat belum diproses';
    if (s === 'Di Proses')    return 'Sedang dalam proses penerbitan';
    if (s === 'Di Terbitkan') return 'Sertifikat resmi telah diterbitkan';
    return '';
}
 
function highlightStatus(label) {
    document.querySelectorAll('#statusOptions label').forEach(l => {
        l.style.borderColor = '#e2e8f0';
        l.style.background  = '#fff';
    });
    label.style.borderColor = '#3b82f6';
    label.style.background  = '#eff6ff';
}

function onPrint(id) {
    currentDetailId = id;
    doPrint();
}
function doPrint() {
    if (!currentDetailId) return;
    window.open(`${URL_PRINT}/${currentDetailId}/print`, '_blank');
}
</script>
@endpush
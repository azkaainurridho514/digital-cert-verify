@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@push('styles')
<style>
    .badge-mode   { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
    .badge-add    { background: #dbeafe; color: #1d4ed8; }
    .badge-edit   { background: #fef3c7; color: #92400e; }
    .photo-area   { display: flex; align-items: center; gap: 14px; padding: 14px; background: #f8fafc; border-radius: 12px; border: 1.5px dashed #e2e8f0; }
    .photo-preview{ width: 58px; height: 58px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .photo-upload-btn { display: inline-block; border: 1.5px solid #e2e8f0; background: #fff; border-radius: 8px; padding: 4px 12px; font-size: 12px; color: #374151; cursor: pointer; }
    .photo-upload-btn:hover { border-color: #93c5fd; }
    .form-label-sm { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; display: block; }
    .fi           { width: 100%; border: 1.5px solid #e2e8f0; border-radius: 9px; padding: 8px 12px; font-size: 13px; color: #374151; background: #fff; outline: none; transition: border-color .18s, box-shadow .18s; }
    .fi:hover     { border-color: #93c5fd; }
    .fi:focus     { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi-icon      { position: relative; }
    .fi-icon .fi  { padding-left: 36px; }
    .fi-icon .icon{ position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .pw-wrap      { position: relative; }
    .pw-wrap .fi  { padding-right: 36px; }
    .pw-toggle    { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; }
    .pw-toggle:hover { color: #6b7280; }
    .btn-cancel   { border: 1.5px solid #e2e8f0; background: #fff; border-radius: 9px; padding: 8px 18px; font-size: 13px; color: #374151; cursor: pointer; }
    .btn-save     { border: none; background: #3b82f6; border-radius: 9px; padding: 8px 22px; font-size: 13px; font-weight: 600; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-save:hover { background: #2563eb; }
    .close-btn    { width: 32px; height: 32px; border: none; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; }
    .close-btn i  { font-size: 18px; color: #64748b; }
    .close-btn:hover { background: #e2e8f0; }
    .close-btn:hover i { color: #0f172a; }

    
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
    <h4>Data Siswa</h4>
    <p>Kelola data seluruh siswa terdaftar.</p>
</div>

<div class="card-modern">
    <div class="card-header-modern d-flex align-items-center justify-content-between gap-3">
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold" style="font-size: 15px;">Semua Siswa</h6>
            <span class="text-muted" style="font-size: 12px;">Dihitung siswa aktif dan siswa yang sudah tidak aktif</span>
        </div>

        {{-- Search --}}
        <div class="search-wrap">
            <span class="search-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
                </svg>
            </span>
            <input type="text" class="search-input" id="searchSiswa"
                   placeholder="Cari nama siswa..."
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
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
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
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    </div>

    <div class="p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 13px;">
                <thead>
                    <tr class="table-light">
                        <th class="px-4 py-3 fw-semibold text-secondary">No</th>
                        <th class="py-3 fw-semibold text-secondary">Nama Siswa</th>
                        <th class="py-3 fw-semibold text-secondary">NIS</th>
                        <th class="py-3 fw-semibold text-secondary">Tanggal Bergabung</th>
                        <th class="py-3 fw-semibold text-secondary">Nomor HP</th>
                        <th class="py-3 fw-semibold text-secondary">Email</th>
                        <th class="py-3 fw-semibold text-secondary">Alamat</th>
                        <th class="py-3 fw-semibold text-secondary">Status</th>
                        <th class="py-3 fw-semibold text-secondary">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm me-2"></div>Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="paginationWrap"></div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════ MODAL TAMBAH / EDIT ══ --}}
<div class="modal fade" id="modalSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">

            <div class="modal-header" style="padding: 18px 22px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="modal-title fw-bold mb-0" id="modalSiswaTitle" style="font-size: 15px;">Tambah Siswa</h5>
                        <span class="badge-mode badge-add" id="modalSiswaBadge"><i class="bi bi-plus-lg"></i> Baru</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 12px;">Lengkapi data siswa dengan benar</p>
                </div>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>

            <div class="modal-body" style="padding: 20px 22px; display: flex; flex-direction: column; gap: 14px;">

                {{-- Upload Foto --}}
                <div class="photo-area">
                    <div class="photo-preview" id="photoPreview">
                        <i class="bi bi-person" style="font-size: 22px; color: #6366f1;"></i>
                    </div>
                    <div class="photo-info">
                        <p class="fw-semibold mb-0" style="font-size: 12px;">Foto Profil</p>
                        <span class="text-muted" style="font-size: 11px;">JPG, PNG maks. 2MB</span><br>
                        <label class="photo-upload-btn mt-1" for="inputFoto">
                            <i class="bi bi-upload me-1"></i> Pilih Foto
                        </label>
                        <input type="file" id="inputFoto" accept="image/*" style="display:none;" onchange="previewFoto(this)">
                    </div>
                </div>

                {{-- NIS & Nama --}}
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">NIS <span class="text-danger">*</span></label>
                        <div class="fi-icon">
                            <span class="icon"><i class="bi bi-card-text"></i></span>
                            <input type="text" class="fi" id="inputNis" placeholder="Contoh: 2024001">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="fi-icon">
                            <span class="icon"><i class="bi bi-person"></i></span>
                            <input type="text" class="fi" id="inputNama" placeholder="Nama lengkap siswa">
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="form-label-sm">Email <span class="text-danger">*</span></label>
                    <div class="fi-icon">
                        <span class="icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="fi" id="inputEmail" placeholder="email@siswa.sch.id">
                    </div>
                </div>

                {{-- Telepon & Password --}}
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">No. Telepon</label>
                        <div class="fi-icon">
                            <span class="icon"><i class="bi bi-telephone"></i></span>
                            <input type="text" class="fi" id="inputPhone" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">
                            Password <span class="text-danger" id="pwRequired">*</span>
                        </label>
                        <div class="pw-wrap">
                            <input type="password" class="fi" id="inputPassword" placeholder="Min. 8 karakter">
                            <button class="pw-toggle" type="button" onclick="togglePw()">
                                <i class="bi bi-eye" id="pwEyeIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="form-label-sm">Alamat</label>
                    <div class="fi-icon">
                        <span class="icon" style="top:10px;transform:none;"><i class="bi bi-geo-alt"></i></span>
                        <textarea class="fi" id="inputAddress" rows="2"
                                  placeholder="Jl. Contoh No. 1, Kota..."
                                  style="padding-left:36px;resize:none;"></textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer" style="padding: 14px 22px; background: #fafafa; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-save" id="btnSave" onclick="submitFormSiswa()">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnSaveLabel">Simpan Siswa</span>
                </button>
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
// ── Konstanta URL ─────────────────────────────────────────────────────────────
const URL_DATA    = "{{ route('admin.siswa.data') }}";
const URL_STORE   = "{{ route('admin.siswa.store') }}";
const URL_SHOW    = "{{ url('admin/siswa') }}";   // /{id}
const URL_UPDATE  = "{{ url('admin/siswa') }}";   // /{id}  PUT
const URL_DELETE  = "{{ url('admin/siswa') }}";   // /{id}  DELETE
const CSRF        = "{{ csrf_token() }}";

// ── State ─────────────────────────────────────────────────────────────────────
let editId       = null;
let activeSearch = '';
let activeFilter = '';
let searchTimer  = null;

// ── Bootstrap modal ───────────────────────────────────────────────────────────
let modalSiswa;
document.addEventListener('DOMContentLoaded', () => {
    modalSiswa = new bootstrap.Modal(document.getElementById('modalSiswa'));
    fetchData();
});

// ══════════════════════════════════════════════════════════════ TABLE RENDER ══
function renderTable(rows) {
    const tbody = document.getElementById('tableBody');
    if (!rows || rows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-muted">
            <i class="bi bi-inbox me-2"></i>Tidak ada siswa ditemukan.</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(s => `
        <tr>
            <td class="px-4 py-3">${s.no}</td>
            <td class="py-3">
                <div class="d-flex align-items-center gap-2">
                    ${s.photo
                        ? `<img src="${s.photo}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">`
                        : `<div class="user-avatar" style="width:28px;height:28px;font-size:11px;">${s.name.substring(0,2).toUpperCase()}</div>`
                    }
                    ${s.name}
                </div>
            </td>
            <td class="py-3 text-muted">${s.nis}</td>
            <td class="py-3 text-muted">${s.joined_at}</td>
            <td class="py-3 text-muted">${s.phone}</td>
            <td class="py-3 text-muted">${s.email}</td>
            <td class="py-3 text-muted">${s.address}</td>
            <td class="py-3">
                ${s.status === 1
                    ? `<span class="badge bg-success-subtle text-success rounded-pill px-3"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>`
                    : `<span class="badge bg-secondary-subtle text-secondary rounded-pill px-3"><i class="bi bi-x-circle-fill me-1"></i>Tidak Aktif</span>`
                }
            </td>
            <td class="py-3">
                <span class="badge text-bg-warning" style="cursor:pointer;" onclick="openModalEdit('${s.id}')" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </span>
                <span class="badge text-bg-danger" style="cursor:pointer;" onclick="onDelete('${s.id}', '${s.name}')" title="Hapus">
                    <i class="bi bi-trash"></i>
                </span>
            </td>
        </tr>
    `).join('');
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
        params.set('page',     currentPage);  
        params.set('per_page', perPage);

        const res  = await fetch(`${URL_DATA}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();

        renderTable(json.data);
        renderPagination(json.meta ?? json); 
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-danger">
            <i class="bi bi-exclamation-circle me-2"></i>Gagal memuat data.</td></tr>`;
    }
}

function renderPagination(meta) {
      console.log('renderPagination called, meta:', meta);
    
    const wrap = document.getElementById('paginationWrap');
    console.log('wrap element:', wrap); 
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
                    Menampilkan <b>${from}-${to}</b> dari <b>${total}</b> data
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


// ════════════════════════════════════════════════════════════════════ SEARCH ══
function handleSearch(el) {
    document.getElementById('clearSearch').style.display = el.value ? 'flex' : 'none';
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => { activeSearch = el.value.trim(); fetchData(); }, 500);
}
function clearSearchInput() {
    document.getElementById('searchSiswa').value = '';
    document.getElementById('clearSearch').style.display = 'none';
    clearTimeout(searchTimer);
    activeSearch = '';
    fetchData();
}

// ══════════════════════════════════════════════════════════════ FILTER TAHUN ══
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
    document.getElementById(id + '-lbl').textContent = val || 'Semua Tahun';
    closeAllCS();
    activeFilter = val;
    fetchData();
}
function closeAllCS() {
    document.querySelectorAll('.cs-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.cs-menu').forEach(m => m.classList.remove('open'));
}
document.addEventListener('click', e => { if (!e.target.closest('.cs')) closeAllCS(); });

// ══════════════════════════════════════════════════════════════ MODAL TAMBAH ══
function openModalTambah() {
    editId = null;
    document.getElementById('modalSiswaTitle').textContent = 'Tambah Siswa';
    document.getElementById('modalSiswaBadge').innerHTML   = '<i class="bi bi-plus-lg"></i> Baru';
    document.getElementById('modalSiswaBadge').className   = 'badge-mode badge-add';
    document.getElementById('btnSaveLabel').textContent    = 'Simpan Siswa';
    document.getElementById('pwRequired').style.display    = '';
    document.getElementById('inputPassword').placeholder   = 'Min. 8 karakter';

    ['inputNis','inputNama','inputEmail','inputPhone','inputPassword','inputAddress']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('inputFoto').value = '';
    document.getElementById('photoPreview').innerHTML =
        '<i class="bi bi-person" style="font-size:22px;color:#6366f1;"></i>';

    modalSiswa.show();
}

// ═══════════════════════════════════════════════════════════════ MODAL EDIT ══
async function openModalEdit(id) {
    editId = id;
    document.getElementById('modalSiswaTitle').textContent = 'Edit Siswa';
    document.getElementById('modalSiswaBadge').innerHTML   = '<i class="bi bi-pencil"></i> Edit';
    document.getElementById('modalSiswaBadge').className   = 'badge-mode badge-edit';
    document.getElementById('btnSaveLabel').textContent    = 'Simpan Perubahan';
    document.getElementById('pwRequired').style.display    = 'none';
    document.getElementById('inputPassword').placeholder   = 'Kosongkan jika tidak diubah';
    document.getElementById('inputPassword').value         = '';

    modalSiswa.show();

    try {
        const res  = await fetch(`${URL_SHOW}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        const d    = json.data;

        document.getElementById('inputNis').value     = d.nis;
        document.getElementById('inputNama').value    = d.name;
        document.getElementById('inputEmail').value   = d.email;
        document.getElementById('inputPhone').value   = d.phone;
        document.getElementById('inputAddress').value = d.address;

        document.getElementById('photoPreview').innerHTML = d.photo
            ? `<img src="${d.photo}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
            : '<i class="bi bi-person" style="font-size:22px;color:#6366f1;"></i>';
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data siswa.', confirmButtonColor: '#3b82f6' });
        modalSiswa.hide();
    }
}

// ════════════════════════════════════════════════════════════════════ SUBMIT ══
async function submitFormSiswa() {
    const nis   = document.getElementById('inputNis').value.trim();
    const nama  = document.getElementById('inputNama').value.trim();
    const email = document.getElementById('inputEmail').value.trim();
    const pass  = document.getElementById('inputPassword').value;

    if (!nis || !nama || !email) {
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'NIS, Nama, dan Email wajib diisi.', confirmButtonColor: '#3b82f6' });
        return;
    }
    if (!editId && !pass) {
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Password wajib diisi untuk siswa baru.', confirmButtonColor: '#3b82f6' });
        return;
    }

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    document.getElementById('btnSaveLabel').textContent = 'Menyimpan...';

    // Gunakan FormData agar bisa kirim file foto
    const formData = new FormData();
    formData.append('nis',     nis);
    formData.append('name',    nama);
    formData.append('email',   email);
    formData.append('phone',   document.getElementById('inputPhone').value);
    formData.append('address', document.getElementById('inputAddress').value);
    if (pass) formData.append('password', pass);

    const fotoFile = document.getElementById('inputFoto').files[0];
    if (fotoFile) formData.append('photo', fotoFile);

    try {
        let url    = URL_STORE;
        let method = 'POST';

        if (editId) {
            url    = `${URL_UPDATE}/${editId}`;
            method = 'POST'; // Laravel PUT via _method
            formData.append('_method', 'PUT');
        }

        const res  = await fetch(url, {
            method,
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });
        const json = await res.json();

        if (json.success) {
            modalSiswa.hide();
            fetchData();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: json.message,
                confirmButtonColor: '#3b82f6',
                timer: 2000,
                timerProgressBar: true,
            });
        } else {
            // Tampilkan error validasi jika ada
            const errors = json.errors
                ? Object.values(json.errors).flat().join('\n')
                : json.message ?? 'Terjadi kesalahan.';
            Swal.fire({ icon: 'error', title: 'Gagal', text: errors, confirmButtonColor: '#3b82f6' });
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan. Coba lagi.', confirmButtonColor: '#3b82f6' });
    } finally {
        btn.disabled = false;
        document.getElementById('btnSaveLabel').textContent = editId ? 'Simpan Perubahan' : 'Simpan Siswa';
    }
}

// ════════════════════════════════════════════════════════════════════ DELETE ══
function onDelete(id, name) {
    Swal.fire({
        icon: 'warning',
        title: 'Hapus Siswa?',
        html: `Yakin ingin menghapus <b>${name}</b>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor:  '#e2e8f0',
    }).then(async result => {
        if (!result.isConfirmed) return;

        try {
            const res  = await fetch(`${URL_DELETE}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':     CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type':     'application/json',
                },
            });
            const json = await res.json();

            if (json.success) {
                fetchData();
                Swal.fire({
                    icon: 'success', title: 'Dihapus!', text: json.message,
                    confirmButtonColor: '#3b82f6', timer: 2000, timerProgressBar: true,
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: json.message, confirmButtonColor: '#3b82f6' });
            }
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghapus. Coba lagi.', confirmButtonColor: '#3b82f6' });
        }
    });
}

// ══════════════════════════════════════════════════════════════════ HELPERS ══
function togglePw() {
    const inp  = document.getElementById('inputPassword');
    const icon = document.getElementById('pwEyeIcon');
    inp.type       = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').innerHTML =
                `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
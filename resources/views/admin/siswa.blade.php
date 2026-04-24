@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@push('styles')
<style>
    /* ============================================================
       PAGE HEADER
    ============================================================ */
    .page-header {
        margin-bottom: 24px;
    }
    .page-header h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--clr-text-primary);
        margin-bottom: 4px;
    }
    .page-header p {
        font-size: .825rem;
        color: var(--clr-text-secondary);
        margin: 0;
    }

    /* ============================================================
       MAIN CARD
    ============================================================ */
    .card-modern {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: 18px;
        box-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 32px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .card-header-modern {
        padding: 18px 22px;
        border-bottom: 1px solid var(--clr-border);
        background: var(--clr-surface);
        flex-wrap: wrap;
        gap: 12px;
    }

    /* ============================================================
       SEARCH BAR
    ============================================================ */
    .search-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-wrap .search-icon {
        position: absolute;
        left: 11px;
        color: var(--clr-text-muted);
        display: flex;
        align-items: center;
        pointer-events: none;
    }
    .search-wrap .search-input {
        height: 36px;
        padding: 0 32px 0 34px;
        border: 1.5px solid var(--clr-border);
        border-radius: 10px;
        background: var(--clr-bg);
        font-size: .82rem;
        color: var(--clr-text-primary);
        outline: none;
        transition: border-color .18s, box-shadow .18s, background .18s;
        width: 220px;
    }
    .search-wrap .search-input::placeholder { color: var(--clr-text-muted); }
    .search-wrap .search-input:focus {
        border-color: var(--clr-primary);
        background: var(--clr-surface);
        box-shadow: 0 0 0 3px rgba(37,99,235,.10);
    }
    .search-wrap .clear-btn {
        position: absolute;
        right: 8px;
        width: 20px;
        height: 20px;
        background: var(--clr-border);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--clr-text-secondary);
        padding: 0;
        transition: background .15s;
    }
    .search-wrap .clear-btn:hover { background: #cbd5e1; }

    /* ============================================================
       CUSTOM SELECT (filter)
    ============================================================ */
    .cs { position: relative; }
    .cs-btn {
        height: 36px;
        padding: 0 12px;
        border: 1.5px solid var(--clr-border);
        border-radius: 10px;
        background: var(--clr-surface);
        font-size: .82rem;
        color: var(--clr-text-primary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: border-color .18s, box-shadow .18s;
        white-space: nowrap;
    }
    .cs-btn .ico { width: 14px; height: 14px; flex-shrink: 0; }
    .cs-btn .lbl { flex: 1; text-align: left; }
    .cs-btn .arr { width: 14px; height: 14px; flex-shrink: 0; transition: transform .2s; }
    .cs-btn.active,
    .cs-btn:hover {
        border-color: var(--clr-primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,.09);
    }
    .cs-btn.active .arr { transform: rotate(180deg); }
    .cs-menu {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 180px;
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,.10);
        z-index: 500;
        padding: 6px;
        overflow: hidden;
    }
    .cs-menu.open { display: block; }
    .cs-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: .82rem;
        color: var(--clr-text-primary);
        cursor: pointer;
        transition: background .15s;
    }
    .cs-item:hover { background: var(--clr-bg); }
    .cs-item.on { color: var(--clr-primary); font-weight: 600; }
    .cs-item .idot {
        width: 7px; height: 7px;
        border-radius: 50%;
        border: 1.5px solid var(--clr-border);
        background: transparent;
        flex-shrink: 0;
        transition: background .15s, border-color .15s;
    }
    .cs-item.on .idot { background: var(--clr-primary); border-color: var(--clr-primary); }
    .cs-item .chk { width: 14px; height: 14px; margin-left: auto; opacity: 0; }
    .cs-item.on .chk { opacity: 1; }
    .cs-sep { height: 1px; background: var(--clr-border); margin: 5px 0; }

    /* ============================================================
       TABLE
    ============================================================ */
    .table { font-size: .82rem; }
    .table thead tr { background: #f8fafc; }
    .table thead th {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--clr-text-muted);
        padding: 11px 16px;
        border-bottom: 1px solid var(--clr-border);
        white-space: nowrap;
    }
    .table tbody td {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: var(--clr-text-primary);
    }
    .table tbody tr:last-child td { border-bottom: none; }
    .table-hover tbody tr { transition: background .13s; }
    .table-hover tbody tr:hover td { background: #f8fafc; }

    /* User avatar */
    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .68rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        letter-spacing: .02em;
    }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
    }
    .status-active   { background: #ecfdf5; color: #059669; }
    .status-inactive { background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; }

    /* Action badges */
    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
        cursor: pointer;
        transition: all .15s;
        border: none;
        text-decoration: none;
    }
    .action-edit  { background: #fef9c3; color: #ca8a04; }
    .action-edit:hover  { background: #fde047; color: #854d0e; transform: scale(1.08); }
    .action-delete { background: #fee2e2; color: #ef4444; }
    .action-delete:hover { background: #fca5a5; color: #b91c1c; transform: scale(1.08); }

    /* ============================================================
       MODAL
    ============================================================ */
    .badge-mode   { display: inline-flex; align-items: center; gap: 5px; font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 20px; letter-spacing: .02em; }
    .badge-add    { background: #dbeafe; color: #1d4ed8; }
    .badge-edit   { background: #fef3c7; color: #92400e; }

    .photo-area {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1.5px dashed #e2e8f0;
        transition: border-color .18s;
    }
    .photo-area:hover { border-color: #93c5fd; }
    .photo-preview {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #e0e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid #c7d2fe;
    }
    .photo-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 11.5px;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
        margin-top: 6px;
    }
    .photo-upload-btn:hover { border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(147,197,253,.15); }

    /* Form inputs in modal */
    .form-label-sm {
        font-size: .75rem;
        font-weight: 600;
        color: var(--clr-text-secondary);
        margin-bottom: 5px;
        display: block;
        letter-spacing: .01em;
    }
    .fi {
        width: 100%;
        height: 38px;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        padding: 0 12px;
        font-size: .825rem;
        color: #1e293b;
        background: #fff;
        outline: none;
        transition: border-color .18s, box-shadow .18s;
    }
    .fi:hover   { border-color: #93c5fd; }
    .fi:focus   { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .fi::placeholder { color: #b0bac5; }
    textarea.fi { height: auto; padding-top: 8px; padding-bottom: 8px; resize: none; }

    .fi-icon   { position: relative; }
    .fi-icon .fi   { padding-left: 36px; }
    .fi-icon .icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #b0bac5;
        display: flex;
        align-items: center;
        font-size: .9rem;
        pointer-events: none;
    }
    .fi-icon textarea.fi + .icon,
    .fi-icon .icon.top { top: 11px; transform: none; }

    .pw-wrap   { position: relative; }
    .pw-wrap .fi { padding-right: 38px; }
    .pw-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #b0bac5;
        padding: 0;
        display: flex;
        align-items: center;
        font-size: .9rem;
        transition: color .15s;
    }
    .pw-toggle:hover { color: #6b7280; }

    /* Modal buttons */
    .btn-cancel {
        height: 38px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        border-radius: 9px;
        padding: 0 18px;
        font-size: .82rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: border-color .15s, background .15s;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }

    .btn-save {
        height: 38px;
        border: none;
        background: var(--clr-primary);
        border-radius: 9px;
        padding: 0 20px;
        font-size: .82rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .15s, box-shadow .15s;
        box-shadow: 0 2px 8px rgba(37,99,235,.18);
    }
    .btn-save:hover { background: var(--clr-primary-hover); box-shadow: 0 4px 16px rgba(37,99,235,.25); }
    .btn-save:disabled { opacity: .65; cursor: not-allowed; }

    .close-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .15s;
        flex-shrink: 0;
    }
    .close-btn:hover { background: #e2e8f0; }
    .close-btn i { font-size: 1.1rem; color: #64748b; }

    /* ============================================================
       PAGINATION
    ============================================================ */
    .pg-btn {
        min-width: 32px;
        height: 32px;
        border: 1.5px solid var(--clr-border);
        border-radius: 8px;
        background: var(--clr-surface);
        color: var(--clr-text-secondary);
        font-size: .78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .15s;
        padding: 0 8px;
    }
    .pg-btn:hover:not(:disabled) {
        border-color: var(--clr-primary);
        color: var(--clr-primary);
        background: var(--clr-primary-soft);
    }
    .pg-btn:disabled { opacity: .35; cursor: not-allowed; }
    .pg-active {
        background: var(--clr-primary) !important;
        color: #fff !important;
        border-color: var(--clr-primary) !important;
        box-shadow: 0 2px 8px rgba(37,99,235,.2);
    }

    /* ============================================================
       RESPONSIVE TWEAKS
    ============================================================ */
    @media (max-width: 768px) {
        .card-header-modern { flex-direction: column; align-items: stretch !important; }
        .search-wrap .search-input { width: 100%; }
        .search-wrap { width: 100%; }
        .cs { width: 100%; }
        .cs-btn { width: 100%; }
        .btn-save { width: 100%; justify-content: center; }
    }
    @media (max-width: 576px) {
        .table thead th:nth-child(4),
        .table tbody td:nth-child(4),
        .table thead th:nth-child(7),
        .table tbody td:nth-child(7) { display: none; }
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <h4>Data Siswa</h4>
    <p>Kelola data seluruh siswa terdaftar.</p>
</div>

{{-- Main Card --}}
<div class="card-modern">

    {{-- Card Header: title + controls --}}
    <div class="card-header-modern d-flex align-items-center justify-content-between gap-3">

        {{-- Title --}}
        <div class="flex-shrink-0">
            <h6 class="mb-0 fw-bold" style="font-size: 14px; color: var(--clr-text-primary);">Semua Siswa</h6>
            <span style="font-size: 11.5px; color: var(--clr-text-muted);">Dihitung siswa aktif dan siswa yang sudah tidak aktif</span>
        </div>

        {{-- Right controls --}}
        <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">

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
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Filter Tahun --}}
            <div class="cs" id="filterTahun">
                <button class="cs-btn" type="button" onclick="tog('filterTahun')" style="min-width: 168px;">
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

            {{-- Tambah --}}
            <button class="btn-save" onclick="openModalTambah()">
                <i class="bi bi-plus-lg"></i> Tambah Siswa
            </button>

        </div>
    </div>

    {{-- Table --}}
    <div class="p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="px-4">No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Tgl. Bergabung</th>
                        <th>Nomor HP</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm me-2" style="opacity:.5;"></div>
                            <span style="font-size:.82rem;">Memuat data...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div id="paginationWrap"></div>
    </div>

</div>

{{-- ══════════════════════════════════ MODAL TAMBAH / EDIT ══ --}}
<div class="modal fade" id="modalSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 18px; border: none; box-shadow: 0 24px 64px rgba(0,0,0,.14); overflow: hidden;">

            {{-- Header --}}
            <div class="modal-header" style="padding: 18px 22px; border-bottom: 1px solid #f1f5f9;">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="modal-title fw-bold mb-0" id="modalSiswaTitle" style="font-size: 15px;">Tambah Siswa</h5>
                        <span class="badge-mode badge-add" id="modalSiswaBadge"><i class="bi bi-plus-lg"></i> Baru</span>
                    </div>
                    <p class="mb-0" style="font-size: 11.5px; color: var(--clr-text-muted);">Lengkapi data siswa dengan benar</p>
                </div>
                <button type="button" class="close-btn ms-auto" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding: 20px 22px; display: flex; flex-direction: column; gap: 16px;">

                {{-- Upload Foto --}}
                <div class="photo-area">
                    <div class="photo-preview" id="photoPreview">
                        <i class="bi bi-person" style="font-size: 22px; color: #6366f1;"></i>
                    </div>
                    <div>
                        <p class="fw-semibold mb-0" style="font-size: 12px; color: var(--clr-text-primary);">Foto Profil</p>
                        <span style="font-size: 11px; color: var(--clr-text-muted);">JPG, PNG — maks. 2 MB</span>
                        <br>
                        <label class="photo-upload-btn mt-1" for="inputFoto">
                            <i class="bi bi-cloud-upload"></i> Pilih Foto
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
                    <div class="fi-icon" style="position:relative;">
                        <span class="icon" style="top:10px; transform:none;"><i class="bi bi-geo-alt"></i></span>
                        <textarea class="fi" id="inputAddress" rows="2"
                                  placeholder="Jl. Contoh No. 1, Kota..."
                                  style="padding-left:36px; padding-top:9px; padding-bottom:9px; resize:none; height:auto;"></textarea>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="padding: 14px 22px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
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
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-muted">
            <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                <i class="bi bi-inbox" style="font-size:2rem;opacity:.3;"></i>
                <span style="font-size:.82rem;">Tidak ada siswa ditemukan.</span>
            </div>
        </td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(s => `
        <tr>
            <td class="px-4">${s.no}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    ${s.photo
                        ? `<img src="${s.photo}" style="width:30px;height:30px;border-radius:50%;object-fit:cover;border:1.5px solid #e0e7ff;">`
                        : `<div class="user-avatar">${s.name.substring(0,2).toUpperCase()}</div>`
                    }
                    <span class="fw-medium" style="font-size:.83rem;">${s.name}</span>
                </div>
            </td>
            <td style="color:var(--clr-text-secondary);font-size:.8rem;">${s.nis}</td>
            <td style="color:var(--clr-text-secondary);font-size:.8rem;">${s.joined_at}</td>
            <td style="color:var(--clr-text-secondary);font-size:.8rem;">${s.phone}</td>
            <td style="color:var(--clr-text-secondary);font-size:.8rem;">${s.email}</td>
            <td style="color:var(--clr-text-secondary);font-size:.8rem;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${s.address}</td>
            <td>
                ${s.status === 1
                    ? `<span class="status-badge status-active"><i class="bi bi-circle-fill" style="font-size:.45rem;"></i>Aktif</span>`
                    : `<span class="status-badge status-inactive"><i class="bi bi-circle" style="font-size:.45rem;"></i>Tidak Aktif</span>`
                }
            </td>
            <td>
                <div class="d-flex align-items-center gap-1">
                    <button class="action-btn action-edit" onclick="openModalEdit('${s.id}')" title="Edit Siswa">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="action-btn action-delete" onclick="onDelete('${s.id}', '${s.name}')" title="Hapus Siswa">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
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
    tbody.innerHTML = `<tr><td colspan="10" class="text-center py-5 text-muted">
        <div class="spinner-border spinner-border-sm me-2" style="opacity:.4;"></div>
        <span style="font-size:.82rem;">Memuat data...</span>
    </td></tr>`;
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
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-5">
            <div style="display:flex;flex-direction:column;align-items:center;gap:8px;color:#ef4444;">
                <i class="bi bi-exclamation-circle" style="font-size:1.8rem;opacity:.5;"></i>
                <span style="font-size:.82rem;">Gagal memuat data.</span>
            </div>
        </td></tr>`;
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
        if (start > 2) pages += `<span class="px-1" style="color:var(--clr-text-muted);font-size:.8rem;">…</span>`;
    }

    for (let i = start; i <= end; i++) {
        pages += pageBtn(i, i, i === currentPage);
    }

    if (end < totalPages) {
        if (end < totalPages - 1) pages += `<span class="px-1" style="color:var(--clr-text-muted);font-size:.8rem;">…</span>`;
        pages += pageBtn(totalPages, totalPages);
    }

    document.getElementById('paginationWrap').innerHTML = `
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-4 py-3"
             style="border-top: 1px solid #f1f5f9; font-size: 12.5px;">

            <div class="d-flex align-items-center gap-3">
                <span style="color:var(--clr-text-secondary);">
                    Menampilkan <b style="color:var(--clr-text-primary);">${from}–${to}</b>
                    dari <b style="color:var(--clr-text-primary);">${total}</b> data
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span style="color:var(--clr-text-muted);">Baris:</span>
                    <select onchange="changePerPage(this.value)"
                            style="height:30px;border:1.5px solid var(--clr-border);border-radius:7px;padding:0 8px;font-size:12px;color:var(--clr-text-primary);background:var(--clr-surface);outline:none;cursor:pointer;">
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
            method = 'POST';
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
        confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor:  '#94a3b8',
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
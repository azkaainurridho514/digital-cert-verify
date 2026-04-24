@extends('layouts.app')
@section('title', 'Manajemen Sertifikat')
@section('page-title', 'Manajemen Sertifikat')

@push('styles')
<style>
    /* ── Design Tokens ─────────────────────────────────── */
    :root {
        --c-blue:       #2563eb;
        --c-blue-soft:  #eff6ff;
        --c-blue-mid:   #93c5fd;
        --c-green:      #16a34a;
        --c-green-soft: #f0fdf4;
        --c-yellow:     #d97706;
        --c-yellow-soft:#fffbeb;
        --c-red:        #dc2626;
        --c-red-soft:   #fef2f2;
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
        --radius-lg:    18px;
        --radius-xl:    22px;
        --shadow-xs:    0 1px 2px rgba(0,0,0,.05);
        --shadow-sm:    0 1px 3px rgba(0,0,0,.07), 0 4px 12px rgba(0,0,0,.05);
        --shadow-md:    0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
        --shadow-lg:    0 12px 36px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.06);
        --font-sans:    'DM Sans', sans-serif;
        --font-display: 'Sora', sans-serif;
        --transition:   all .2s ease;
    }

    /* ── Page Header ───────────────────────────────────── */
    .page-header {
        margin-bottom: 24px;
    }
    .page-header h4 {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--c-slate-900);
        margin-bottom: 4px;
    }
    .page-header p {
        font-size: .85rem;
        color: var(--c-slate-500);
        margin: 0;
    }

    /* ── Main Card ─────────────────────────────────────── */
    .cert-card {
        background: var(--c-white);
        border-radius: var(--radius-xl);
        border: 1px solid var(--c-slate-200);
        box-shadow: var(--shadow-sm);
        overflow: visible;
    }

    /* ── Card Header ───────────────────────────────────── */
    .cert-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--c-slate-100);
        flex-wrap: wrap;
    }
    .cert-card-header .header-title {
        font-family: var(--font-display);
        font-size: .95rem;
        font-weight: 700;
        color: var(--c-slate-900);
        flex: 1;
        min-width: 120px;
    }

    /* ── Search Input ──────────────────────────────────── */
    .search-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-icon {
        position: absolute;
        left: 11px;
        color: var(--c-slate-400);
        display: flex;
        pointer-events: none;
        z-index: 1;
    }
    .search-input {
        border: 1.5px solid var(--c-slate-200);
        border-radius: var(--radius-sm);
        padding: 8px 34px 8px 34px;
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--c-slate-700);
        background: var(--c-slate-50);
        outline: none;
        width: 210px;
        transition: var(--transition);
    }
    .search-input:focus {
        border-color: var(--c-blue);
        background: var(--c-white);
        box-shadow: 0 0 0 3px rgba(37,99,235,.10);
        width: 240px;
    }
    .search-input::placeholder { color: var(--c-slate-400); }
    .clear-btn {
        position: absolute;
        right: 9px;
        border: none;
        background: none;
        cursor: pointer;
        color: var(--c-slate-400);
        display: flex;
        align-items: center;
        padding: 0;
        transition: color .15s;
    }
    .clear-btn:hover { color: var(--c-slate-700); }

    /* ── Custom Select (cs) ────────────────────────────── */
    .cs { position: relative; }
    .cs-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border: 1.5px solid var(--c-slate-200);
        border-radius: var(--radius-sm);
        background: var(--c-slate-50);
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--c-slate-700);
        cursor: pointer;
        white-space: nowrap;
        transition: var(--transition);
        outline: none;
    }
    .cs-btn:hover, .cs-btn.active {
        border-color: var(--c-blue);
        background: var(--c-blue-soft);
        color: var(--c-blue);
    }
    .cs-btn .ico { width: 14px; height: 14px; flex-shrink: 0; }
    .cs-btn .arr { width: 14px; height: 14px; flex-shrink: 0; transition: transform .2s; }
    .cs-btn.active .arr { transform: rotate(180deg); }
    .cs-btn .lbl { flex: 1; }
    .cs-menu {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 180px;
        background: var(--c-white);
        border: 1px solid var(--c-slate-200);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        padding: 6px;
        animation: fadeDown .15s ease;
    }
    .cs-menu.open { display: block; }
    @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .cs-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: var(--radius-xs);
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--c-slate-700);
        cursor: pointer;
        transition: background .15s;
    }
    .cs-item:hover { background: var(--c-slate-50); }
    .cs-item.on { color: var(--c-blue); font-weight: 600; }
    .cs-item.on .idot { background: var(--c-blue); }
    .cs-item .idot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--c-slate-300);
        flex-shrink: 0;
    }
    .cs-item .chk { width: 14px; height: 14px; margin-left: auto; opacity: 0; }
    .cs-item.on .chk { opacity: 1; color: var(--c-blue); }
    .cs-sep { height: 1px; background: var(--c-slate-100); margin: 4px 0; }

    /* ── Btn Save / Create ─────────────────────────────── */
    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--c-blue);
        color: var(--c-white);
        border: none;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 600;
        font-family: var(--font-sans);
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(37,99,235,.25);
    }
    .btn-create:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 14px rgba(37,99,235,.35);
        transform: translateY(-1px);
    }
    .btn-create:active { transform: translateY(0); }

    /* ── Table ─────────────────────────────────────────── */
    .cert-table-wrap { overflow-x: auto; }
    .cert-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .cert-table thead tr {
        background: var(--c-slate-50);
        border-bottom: 1px solid var(--c-slate-200);
    }
    .cert-table thead th {
        font-family: var(--font-display);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--c-slate-400);
        padding: 11px 14px;
        white-space: nowrap;
    }
    .cert-table thead th:first-child { padding-left: 22px; border-radius: var(--radius-xs) 0 0 0; }
    .cert-table thead th:last-child  { padding-right: 22px; border-radius: 0 var(--radius-xs) 0 0; }
    .cert-table tbody tr {
        border-bottom: 1px solid var(--c-slate-100);
        transition: background .15s;
    }
    .cert-table tbody tr:last-child { border-bottom: none; }
    .cert-table tbody tr:hover { background: var(--c-slate-50); }
    .cert-table tbody td {
        padding: 13px 14px;
        color: var(--c-slate-700);
        vertical-align: middle;
    }
    .cert-table tbody td:first-child { padding-left: 22px; }
    .cert-table tbody td:last-child  { padding-right: 22px; }
    .td-muted { color: var(--c-slate-400); font-size: 12.5px; }

    /* ── User Avatar chip ──────────────────────────────── */
    .user-chip {
        display: flex;
        align-items: center;
        gap: 9px;
    }
    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #2563eb);
        color: #fff;
        font-family: var(--font-display);
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        letter-spacing: .03em;
    }
    .user-name { font-weight: 500; color: var(--c-slate-900); font-size: 13px; }

    /* ── Status Badges ─────────────────────────────────── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-badge.published {
        background: var(--c-green-soft);
        color: var(--c-green);
    }
    .status-badge.process {
        background: var(--c-yellow-soft);
        color: var(--c-yellow);
    }
    .status-badge.draft {
        background: var(--c-slate-100);
        color: var(--c-slate-500);
    }
    .status-badge i { font-size: 10px; }

    /* ── Action Buttons ────────────────────────────────── */
    .action-wrap { display: flex; align-items: center; gap: 5px; flex-wrap: nowrap; }
    .act-btn {
        width: 30px; height: 30px;
        border: none;
        border-radius: var(--radius-xs);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: var(--transition);
        text-decoration: none;
        flex-shrink: 0;
    }
    .act-btn-edit   { background: #eff6ff; color: #2563eb; }
    .act-btn-edit:hover   { background: #2563eb; color: #fff; }
    .act-btn-delete { background: #fef2f2; color: #dc2626; }
    .act-btn-delete:hover { background: #dc2626; color: #fff; }
    .act-btn-view   { background: #f0f9ff; color: #0284c7; }
    .act-btn-view:hover   { background: #0284c7; color: #fff; }
    .act-btn-print  { background: #fffbeb; color: #d97706; }
    .act-btn-print:hover  { background: #d97706; color: #fff; }
    .act-btn-disabled { background: var(--c-slate-100); color: var(--c-slate-400); cursor: not-allowed; opacity: .6; }

    /* ── Pagination ────────────────────────────────────── */
    .pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 22px;
        border-top: 1px solid var(--c-slate-100);
        font-size: 13px;
    }
    .pagination-info { color: var(--c-slate-500); }
    .pagination-info b { color: var(--c-slate-700); font-weight: 600; }
    .per-page-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--c-slate-500);
    }
    .per-page-select {
        border: 1.5px solid var(--c-slate-200);
        border-radius: var(--radius-xs);
        padding: 4px 8px;
        font-size: 12px;
        color: var(--c-slate-700);
        background: var(--c-white);
        outline: none;
        cursor: pointer;
    }
    .pg-btn {
        min-width: 32px; height: 32px;
        border: 1.5px solid var(--c-slate-200);
        border-radius: var(--radius-xs);
        background: var(--c-white);
        color: var(--c-slate-600);
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        padding: 0 8px;
        font-family: var(--font-display);
    }
    .pg-btn:hover:not(:disabled) {
        border-color: var(--c-blue);
        color: var(--c-blue);
        background: var(--c-blue-soft);
    }
    .pg-btn:disabled { opacity: .35; cursor: not-allowed; }
    .pg-active {
        background: var(--c-blue) !important;
        color: #fff !important;
        border-color: var(--c-blue) !important;
    }
    .pg-controls { display: flex; align-items: center; gap: 4px; }

    /* ── Empty / Loading State ─────────────────────────── */
    .empty-state {
        padding: 52px 20px;
        text-align: center;
        color: var(--c-slate-400);
    }
    .empty-state i { font-size: 2.2rem; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 13.5px; margin: 0; }
    .loading-state {
        padding: 52px 20px;
        text-align: center;
        color: var(--c-slate-400);
    }

    /* ══════════════ MODAL STYLES ══════════════════════════════════════ */

    /* Shared modal wrapper */
    .modal-content {
        border: none;
        border-radius: var(--radius-xl) !important;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    .modal-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--c-slate-100);
        background: var(--c-white);
    }
    .modal-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 3px;
    }
    .modal-main-title {
        font-family: var(--font-display);
        font-size: 15px;
        font-weight: 700;
        color: var(--c-slate-900);
        margin: 0;
    }
    .modal-subtitle {
        font-size: 12px;
        color: var(--c-slate-400);
        margin: 0;
    }
    .modal-body { padding: 22px 24px; background: var(--c-white); }
    .modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--c-slate-100);
        background: var(--c-slate-50);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* Close button */
    .modal-close-btn {
        width: 32px; height: 32px;
        border: 1.5px solid var(--c-slate-200);
        border-radius: 50%;
        background: var(--c-white);
        color: var(--c-slate-500);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 17px;
        transition: var(--transition);
        flex-shrink: 0;
    }
    .modal-close-btn:hover {
        background: var(--c-slate-100);
        color: var(--c-slate-900);
        border-color: var(--c-slate-300);
    }

    /* Badge mode */
    .badge-mode {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10.5px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    .badge-add  { background: #dbeafe; color: #1d4ed8; }

    /* Form field group */
    .field-group { display: flex; flex-direction: column; gap: 14px; }
    .field-item { display: flex; flex-direction: column; gap: 5px; }
    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--c-slate-500);
        font-family: var(--font-display);
    }
    .field-label .req { color: var(--c-red); }
    .field-input {
        border: 1.5px solid var(--c-slate-200);
        border-radius: var(--radius-sm);
        padding: 9px 13px;
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--c-slate-700);
        background: var(--c-white);
        outline: none;
        transition: var(--transition);
        width: 100%;
    }
    .field-input:hover  { border-color: var(--c-blue-mid); }
    .field-input:focus  { border-color: var(--c-blue); box-shadow: 0 0 0 3px rgba(37,99,235,.10); }
    .field-input::placeholder { color: var(--c-slate-400); }
    .field-icon-wrap { position: relative; }
    .field-icon-wrap .f-icon {
        position: absolute;
        left: 11px; top: 50%;
        transform: translateY(-50%);
        color: var(--c-slate-400);
        font-size: 13px;
        z-index: 1;
        pointer-events: none;
    }
    .field-icon-wrap .field-input { padding-left: 34px; }

    /* Grid 2 col */
    .field-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 480px) { .field-grid-2 { grid-template-columns: 1fr; } }

    /* Textarea */
    textarea.field-input { resize: vertical; min-height: 72px; }

    /* Search dropdown inside modal */
    .search-dropdown {
        position: absolute; top: 100%; left: 0; right: 0;
        background: var(--c-white);
        border: 1px solid var(--c-slate-200);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        margin-top: 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 9999;
        animation: fadeDown .15s ease;
    }
    .search-item {
        padding: 10px 13px;
        cursor: pointer;
        transition: background .15s;
        border-radius: var(--radius-xs);
        margin: 3px;
    }
    .search-item:hover { background: var(--c-slate-50); }
    .si-main { display: flex; align-items: center; gap: 10px; }
    .si-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #a5b4fc, #6366f1);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-display); font-size: 12px; font-weight: 700;
        flex-shrink: 0;
    }
    .si-info  { display: flex; flex-direction: column; gap: 1px; }
    .si-name  { font-size: 13px; font-weight: 600; color: var(--c-slate-900); }
    .si-meta  { font-size: 11px; color: var(--c-slate-400); }

    /* Student / Program preview tag */
    .field-preview {
        font-size: 11.5px;
        color: var(--c-slate-500);
        padding: 5px 10px;
        background: var(--c-slate-50);
        border-radius: var(--radius-xs);
        border: 1px solid var(--c-slate-100);
        display: flex; align-items: center; gap: 5px;
    }
    .field-preview b { color: var(--c-slate-700); }

    /* Modal footer buttons */
    .btn-modal-cancel {
        border: 1.5px solid var(--c-slate-200);
        background: var(--c-white);
        border-radius: var(--radius-sm);
        padding: 8px 18px;
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--c-slate-600);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
    }
    .btn-modal-cancel:hover {
        background: var(--c-slate-50);
        border-color: var(--c-slate-300);
    }
    .btn-modal-save {
        display: inline-flex; align-items: center; gap: 7px;
        border: none;
        background: var(--c-blue);
        border-radius: var(--radius-sm);
        padding: 9px 22px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--font-sans);
        color: #fff;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(37,99,235,.2);
    }
    .btn-modal-save:hover { background: #1d4ed8; }
    .btn-modal-save:disabled { opacity: .6; cursor: not-allowed; }

    /* Detail rows */
    .detail-row {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--c-slate-100);
        font-size: 13px;
        align-items: flex-start;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label {
        width: 130px;
        flex-shrink: 0;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--c-slate-400);
        font-family: var(--font-display);
        padding-top: 1px;
    }
    .detail-value { color: var(--c-slate-700); line-height: 1.5; }

    /* QR / image preview in detail */
    .cert-img-preview {
        width: 110px; height: 110px;
        border-radius: var(--radius-md);
        object-fit: contain;
        border: 1.5px solid var(--c-slate-200);
        padding: 6px;
        background: var(--c-slate-50);
    }
    .cert-no-img {
        width: 110px; height: 110px;
        border-radius: var(--radius-md);
        background: var(--c-slate-100);
        display: flex; align-items: center; justify-content: center;
        color: var(--c-slate-400);
        font-size: 28px;
    }

    /* Number badge */
    .row-number {
        width: 24px; height: 24px;
        border-radius: 6px;
        background: var(--c-slate-100);
        color: var(--c-slate-500);
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center; justify-content: center;
        font-family: var(--font-display);
    }

    /* ── Responsive tweaks ─────────────────────────────── */
    @media (max-width: 640px) {
        .cert-card-header { padding: 14px 16px; gap: 10px; }
        .cert-table thead th, .cert-table tbody td { padding: 10px 10px; }
        .cert-table thead th:first-child, .cert-table tbody td:first-child { padding-left: 14px; }
        .pagination-wrap { padding: 14px 16px; }
        .search-input { width: 160px; }
        .search-input:focus { width: 180px; }
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <h4>Manajemen Sertifikat</h4>
    <p>Buat dan kelola sertifikat siswa secara efisien.</p>
</div>

{{-- ═══════════ MAIN CARD ═══════════ --}}
<div class="cert-card">

    {{-- Card Header --}}
    <div class="cert-card-header">
        <div class="header-title">Semua Sertifikat</div>

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

        <button class="btn-create" onclick="openModalTambah()">
            <i class="bi bi-plus-lg"></i> Buat Sertifikat
        </button>
    </div>

    {{-- Table --}}
    <div class="cert-table-wrap">
        <table class="cert-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>No. Sertifikat</th>
                    <th>Nilai</th>
                    <th>Program</th>
                    <th>Level</th>
                    <th>Deskripsi</th>
                    <th>Tgl. Terbit</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="10">
                        <div class="loading-state">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            <span>Memuat data...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div id="paginationWrap"></div>

</div>


{{-- ══════════════════════════ MODAL TAMBAH SERTIFIKAT ════════════════════════ --}}
<div class="modal fade" id="modalSertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content">

            <div class="modal-header">
                <div style="flex:1;">
                    <div class="modal-title-row">
                        <h5 class="modal-main-title">Tambah Sertifikat</h5>
                        <span class="badge-mode badge-add"><i class="bi bi-plus-lg"></i> Baru</span>
                    </div>
                    <p class="modal-subtitle">Lengkapi data sertifikat dengan benar</p>
                </div>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="field-group">

                    {{-- Nomor Sertifikat --}}
                    <div class="field-item">
                        <label class="field-label">Nomor Sertifikat</label>
                        <div class="field-icon-wrap">
                            <i class="bi bi-upc-scan f-icon"></i>
                            <input type="text" class="field-input" id="inputCertificateNumber"
                                   placeholder="Auto / Generate">
                        </div>
                    </div>

                    {{-- Nama Siswa --}}
                    <div class="field-item">
                        <label class="field-label">Nama Siswa <span class="req">*</span></label>
                        <div class="field-icon-wrap position-relative">
                            <i class="bi bi-person f-icon"></i>
                            <input type="text" class="field-input" id="inputStudent"
                                   placeholder="Cari nama siswa..."
                                   onkeyup="searchStudent(this.value)" autocomplete="off">
                            <div id="studentResult" class="search-dropdown"></div>
                        </div>
                        <input type="hidden" id="inputStudentId">
                        <div id="studentPreview"></div>
                    </div>

                    {{-- Program --}}
                    <div class="field-item">
                        <label class="field-label">Program <span class="req">*</span></label>
                        <div class="field-icon-wrap position-relative">
                            <i class="bi bi-book f-icon"></i>
                            <input type="text" class="field-input" id="inputProgram"
                                   placeholder="Cari program..."
                                   onkeyup="searchProgram(this.value)" autocomplete="off">
                            <div id="programResult" class="search-dropdown"></div>
                        </div>
                        <input type="hidden" id="inputProgramId">
                        <div id="programPreview"></div>
                    </div>

                    {{-- Nilai & Level --}}
                    <div class="field-grid-2">
                        <div class="field-item">
                            <label class="field-label">Nilai</label>
                            <input type="text" class="field-input" placeholder="A / B / C" id="inputGrade">
                        </div>
                        <div class="field-item">
                            <label class="field-label">Level</label>
                            <select class="field-input" id="inputLevel">
                                <option value="">-- Pilih Level --</option>
                                <option>Beginner</option>
                                <option>Intermediate</option>
                                <option>Advanced</option>
                            </select>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="field-item">
                        <label class="field-label">Deskripsi</label>
                        <textarea class="field-input" rows="3" placeholder="Deskripsi sertifikat..." id="inputDescription"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-save" id="btnSave" onclick="confirmSubmit()">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnSaveLabel">Simpan Sertifikat</span>
                </button>
            </div>

        </div>
    </div>
</div>


{{-- ══════════════════════════ MODAL DETAIL ═══════════════════════════════════ --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-main-title">Detail Sertifikat</h5>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div class="modal-body" id="detailBody">
                <div class="loading-state">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-modal-save" id="btnPrintFromDetail" onclick="doPrint()" style="display:none;">
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
        tbody.innerHTML = `<tr><td colspan="10">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Tidak ada sertifikat ditemukan.</p>
            </div>
        </td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(cert => `
        <tr>
            <td><span class="row-number">${cert.no}</span></td>
            <td>
                <div class="user-chip">
                    <div class="user-avatar">${cert.user_name.substring(0, 2).toUpperCase()}</div>
                    <span class="user-name">${cert.user_name}</span>
                </div>
            </td>
            <td><code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;color:#334155;">${cert.certificate_number || "-"}</code></td>
            <td class="td-muted">${cert.grade || "-"}</td>
            <td class="td-muted">${cert.program_name || "-"}</td>
            <td class="td-muted">${cert.level || "-"}</td>
            <td class="td-muted" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${cert.description || ''}">${cert.description || "-"}</td>
            <td class="td-muted">${cert.issued_date || "-"}</td>
            <td>${renderBadge(cert.status)}</td>
            <td>
                <div class="action-wrap">
                    <button class="act-btn act-btn-edit" onclick="onChangeStatus('${cert.id}','${cert.user_id}')" title="Ubah Status">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="act-btn act-btn-delete" onclick="onDelete('${cert.id}','${cert.user_id}')" title="Hapus">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                    <button class="act-btn act-btn-view" onclick="onDetail('${cert.id}')" title="Detail">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    ${cert.has_file && cert.status === 'Di Terbitkan'
                        ? `<button class="act-btn act-btn-print" onclick="onPrint('${cert.id}')" title="Cetak">
                               <i class="bi bi-printer-fill"></i>
                           </button>`
                        : `<button class="act-btn act-btn-disabled" title="File belum tersedia" disabled>
                               <i class="bi bi-printer-fill"></i>
                           </button>`
                    }
                </div>
            </td>
        </tr>
    `).join('');
}

function renderBadge(status) {
    if (status === 'Di Terbitkan') return `<span class="status-badge published"><i class="bi bi-check-circle-fill"></i>${status}</span>`;
    if (status === 'Di Proses')   return `<span class="status-badge process"><i class="bi bi-clock-fill"></i>${status}</span>`;
    return `<span class="status-badge draft"><i class="bi bi-pencil-fill"></i>${status}</span>`;
}

let currentPage = 1;
let totalPages  = 1;
let perPage     = 10;

async function fetchData(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="10">
        <div class="loading-state">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            Memuat data...
        </div>
    </td></tr>`;
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
        tbody.innerHTML = `<tr><td colspan="10">
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
    const from  = meta.from ?? 0;
    const to    = meta.to   ?? 0;
    const total = meta.total ?? 0;

    let pages = '';
    const range = 2;
    const start = Math.max(1, currentPage - range);
    const end   = Math.min(totalPages, currentPage + range);

    if (start > 1) {
        pages += pageBtn(1, '1');
        if (start > 2) pages += `<span style="padding:0 4px;color:#94a3b8;font-size:13px;">…</span>`;
    }
    for (let i = start; i <= end; i++) {
        pages += pageBtn(i, i, i === currentPage);
    }
    if (end < totalPages) {
        if (end < totalPages - 1) pages += `<span style="padding:0 4px;color:#94a3b8;font-size:13px;">…</span>`;
        pages += pageBtn(totalPages, totalPages);
    }

    document.getElementById('paginationWrap').innerHTML = `
        <div class="pagination-wrap">
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <span class="pagination-info">
                    Menampilkan <b>${from}–${to}</b> dari <b>${total}</b> data
                </span>
                <div class="per-page-wrap">
                    <span>Baris:</span>
                    <select class="per-page-select" onchange="changePerPage(this.value)">
                        ${[10, 25, 50, 100].map(n =>
                            `<option value="${n}" ${n === perPage ? 'selected' : ''}>${n}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>
            <div class="pg-controls">
                <button onclick="fetchData(1)" ${currentPage === 1 ? 'disabled' : ''} class="pg-btn" title="Pertama">
                    <i class="bi bi-chevron-double-left"></i>
                </button>
                <button onclick="fetchData(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="pg-btn" title="Sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>
                ${pages}
                <button onclick="fetchData(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="pg-btn" title="Berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button onclick="fetchData(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''} class="pg-btn" title="Terakhir">
                    <i class="bi bi-chevron-double-right"></i>
                </button>
            </div>
        </div>
    `;
}

function pageBtn(page, label, active = false) {
    return `<button onclick="fetchData(${page})" class="pg-btn ${active ? 'pg-active' : ''}">${label}</button>`;
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
                level:              document.getElementById('inputLevel').value,
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
            });
        }
    } catch (e) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Gagal menyimpan. Coba lagi.',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#3b82f6',
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
                <div class="search-item" onclick="selectStudent('${s.id}', '${s.name}', '${s.email}')">
                    <div class="si-main">
                        <div class="si-avatar">${s.name.charAt(0)}</div>
                        <div class="si-info">
                            <div class="si-name">${s.name}</div>
                            <div class="si-meta">${s.email}</div>
                        </div>
                    </div>
                </div>`).join('')
            : `<div class="search-item" style="color:#94a3b8;font-size:13px;">Tidak ditemukan</div>`;
    }, 300);
}
function selectStudent(id, name, email) {
    document.getElementById('inputStudent').value   = name;
    document.getElementById('inputStudentId').value = id;
    document.getElementById('studentResult').innerHTML = '';
    document.getElementById('studentPreview').innerHTML =
        `<div class="field-preview"><i class="bi bi-check-circle-fill text-success" style="font-size:11px;"></i> Dipilih: <b>${name}</b> &mdash; ${email}</div>`;
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
                <div class="search-item" onclick="selectProgram('${p.id}', '${p.name}', '${p.code}')">
                    <div class="si-main">
                        <div class="si-avatar">${p.name.charAt(0)}</div>
                        <div class="si-info">
                            <div class="si-name">${p.name}</div>
                            <div class="si-meta">${p.code}</div>
                        </div>
                    </div>
                </div>`).join('')
            : `<div class="search-item" style="color:#94a3b8;font-size:13px;">Tidak ditemukan</div>`;
    }, 300);
}
function selectProgram(id, name, code) {
    document.getElementById('inputProgram').value   = name;
    document.getElementById('inputProgramId').value = id;
    document.getElementById('programResult').innerHTML = '';
    document.getElementById('programPreview').innerHTML =
        `<div class="field-preview"><i class="bi bi-check-circle-fill text-success" style="font-size:11px;"></i> Dipilih: <b>${name}</b> &mdash; ${code}</div>`;
}

async function onDetail(id) {
    currentDetailId = id;
    document.getElementById('detailBody').innerHTML = `
        <div class="loading-state">
            <div class="spinner-border spinner-border-sm text-primary"></div>
        </div>`;
    document.getElementById('btnPrintFromDetail').style.display = 'none';
    modalDetail.show();

    try {
        const res  = await fetch(`${URL_SHOW}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (!json.success) throw new Error();
        const d = json.data;

        document.getElementById('detailBody').innerHTML = `
            <div style="display:flex;align-items:center;gap:16px;padding-bottom:16px;margin-bottom:16px;border-bottom:1px solid #f1f5f9;">
                ${d.file_path
                    ? `<img src="${d.file_path}" class="cert-img-preview" alt="Sertifikat" />`
                    : `<div class="cert-no-img"><i class="bi bi-award"></i></div>`}
                <div>
                    <div style="font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:#0f172a;margin-bottom:3px;">${d.user_name}</div>
                    <div style="font-size:12px;color:#64748b;">${d.user_email}</div>
                    <div style="margin-top:8px;">${renderBadge(d.status)}</div>
                </div>
            </div>
            <div class="detail-row"><div class="detail-label">No. Sertifikat</div><div class="detail-value"><code style="background:#f1f5f9;padding:2px 8px;border-radius:5px;font-size:12.5px;">${d.certificate_number}</code></div></div>
            <div class="detail-row"><div class="detail-label">Program</div><div class="detail-value">${d.program_name} <span style="color:#94a3b8;">(${d.program_code})</span></div></div>
            <div class="detail-row"><div class="detail-label">Nilai</div><div class="detail-value">${d.grade}</div></div>
            <div class="detail-row"><div class="detail-label">Deskripsi</div><div class="detail-value">${d.description}</div></div>
            <div class="detail-row"><div class="detail-label">Tanggal Terbit</div><div class="detail-value">${d.issued_date}</div></div>
        `;

        if (d.has_file && d.status === 'Di Terbitkan') {
            document.getElementById('btnPrintFromDetail').style.display = 'inline-flex';
        }
    } catch (e) {
        document.getElementById('detailBody').innerHTML =
            `<div class="empty-state" style="color:#dc2626;"><i class="bi bi-exclamation-circle"></i><p>Gagal memuat detail.</p></div>`;
    }
}

async function generateCertNumber(certId) {
    const btn = document.getElementById('btnGenerate');
    if (btn) { btn.disabled = true; btn.textContent = '...'; }

    try {
        const res  = await fetch(`${URL_UPDATE}/generate-cert-number`, {
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

        const programOptions = programs.map(p =>
            `<option value="${p.id}" ${p.name === d.program_name ? 'selected' : ''}>${p.name} (${p.code})</option>`
        ).join('');

        const levels = ['Beginner', 'Intermediate', 'Advanced'];
        const selectedLevel = (d.level && d.level !== '-' ? d.level : '').trim().toLowerCase();
        const levelOptions = levels.map(lv => {
            const isSelected = selectedLevel === lv.toLowerCase();
            return `<option value="${lv}" ${isSelected ? 'selected' : ''}>${lv}</option>`;
        }).join('');

        const inputStyle = 'width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:8px 12px;font-size:13px;color:#374151;outline:none;background:#fff;';
        const labelStyle = 'font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px;';
        const wrapStyle  = 'margin-bottom:10px;';

        Swal.fire({
            title: `<span style="font-size:15px;font-weight:700;">Ubah Sertifikat</span>`,
            html: `
                <p style="font-size:12px;color:#6b7280;margin-bottom:14px;">
                    Siswa: <b>${d.user_name}</b>
                </p>
                <div id="statusOptions" style="margin-bottom:14px;text-align:left;">${radioOptions}</div>
                <div style="text-align:left;">
                    <div style="${wrapStyle}">
                        <label style="${labelStyle}">Nomor Sertifikat <span style="color:#ef4444;">*</span>
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
                    <div style="${wrapStyle}">
                        <label style="${labelStyle}">Program <span style="color:#ef4444;">*</span></label>
                        <select id="swalProgramId" style="${inputStyle}">
                            <option value="">-- Pilih Program --</option>
                            ${programOptions}
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;${wrapStyle}">
                        <div>
                            <label style="${labelStyle}">Nilai <span style="color:#ef4444;">*</span>
                                <span style="font-weight:400;color:#6b7280;">(wajib Di Terbitkan)</span>
                            </label>
                            <input id="swalGrade" type="text"
                                value="${d.grade !== '-' ? d.grade : ''}"
                                placeholder="A / B / C"
                                style="${inputStyle}">
                        </div>
                        <div>
                            <label style="${labelStyle}">Level <span style="color:#ef4444;">*</span>
                                <span style="font-weight:400;color:#6b7280;">(wajib Di Terbitkan)</span>
                            </label>
                            <select id="swalLevel" style="${inputStyle}">
                                <option value="">-- Pilih Level --</option>
                                ${levelOptions}
                            </select>
                        </div>
                    </div>
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
                const certNumber  = popup.querySelector('#swalCertNumber')?.value.trim() ?? '';
                const programId   = popup.querySelector('#swalProgramId')?.value ?? '';
                const grade       = popup.querySelector('#swalGrade')?.value ?? '';
                const level       = popup.querySelector('#swalLevel')?.value ?? '';
                const description = popup.querySelector('#swalDescription')?.value.trim() ?? '';

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

async function onDelete(id, userId) {
    currentDetailId = id;

    const confirm = await Swal.fire({
        title: 'Hapus Data?',
        text: "Data sertifikat akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#e2e8f0',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'swal-popup-custom' }
    });

    if (!confirm.isConfirmed) return;

    Swal.fire({
        title: 'Menghapus...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    try {
        const res = await fetch(`${URL_UPDATE}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        const json = await res.json();

        if (!json.success) {
            throw new Error(json.message || 'Gagal menghapus data');
        }

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil dihapus',
            timer: 1500,
            showConfirmButton: false
        });

        fetchData();

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: error.message || 'Terjadi kesalahan saat menghapus data'
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
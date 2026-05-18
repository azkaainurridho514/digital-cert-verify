@extends('layouts.app')
@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

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
    .page-header { margin-bottom: 24px; }
    .page-header h4 {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--c-slate-900);
        margin-bottom: 4px;
    }
    .page-header p { font-size: .85rem; color: var(--c-slate-500); margin: 0; }

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
    .search-wrap { position: relative; display: flex; align-items: center; }
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
        padding: 8px 34px;
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

    /* ── Btn Create ────────────────────────────────────── */
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
    .cert-table { width: 100%; border-collapse: collapse; font-size: 13px; }
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
    .cert-table tbody td { padding: 13px 14px; color: var(--c-slate-700); vertical-align: middle; }
    .cert-table tbody td:first-child { padding-left: 22px; }
    .cert-table tbody td:last-child  { padding-right: 22px; }
    .td-muted { color: var(--c-slate-400); font-size: 12.5px; }

    /* ── User Avatar Chip ──────────────────────────────── */
    .user-chip { display: flex; align-items: center; gap: 9px; }
    .user-avatar {
        width: 30px; height: 30px;
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
    .status-badge.published { background: var(--c-green-soft); color: var(--c-green); }
    .status-badge.process   { background: var(--c-yellow-soft); color: var(--c-yellow); }
    .status-badge.draft     { background: var(--c-slate-100); color: var(--c-slate-500); }
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
    .act-btn-edit         { background: #eff6ff; color: #2563eb; }
    .act-btn-edit:hover   { background: #2563eb; color: #fff; }
    .act-btn-delete       { background: #fef2f2; color: #dc2626; }
    .act-btn-delete:hover { background: #dc2626; color: #fff; }
    .act-btn-view         { background: #f0f9ff; color: #0284c7; }
    .act-btn-view:hover   { background: #0284c7; color: #fff; }
    .act-btn-print        { background: #fffbeb; color: #d97706; }
    .act-btn-print:hover  { background: #d97706; color: #fff; }
    .act-btn-disabled     { background: var(--c-slate-100); color: var(--c-slate-400); cursor: not-allowed; opacity: .6; }

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
    .per-page-wrap { display: flex; align-items: center; gap: 8px; color: var(--c-slate-500); }
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
    .pg-active { background: var(--c-blue) !important; color: #fff !important; border-color: var(--c-blue) !important; }
    .pg-controls { display: flex; align-items: center; gap: 4px; }

    /* ── Empty / Loading State ─────────────────────────── */
    .empty-state { padding: 52px 20px; text-align: center; color: var(--c-slate-400); }
    .empty-state i { font-size: 2.2rem; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 13.5px; margin: 0; }
    .loading-state { padding: 52px 20px; text-align: center; color: var(--c-slate-400); }

    /* ── Number Badge ──────────────────────────────────── */
    .row-number {
        width: 24px; height: 24px;
        border-radius: 6px;
        background: var(--c-slate-100);
        color: var(--c-slate-500);
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
    }

    /* ══════════ MODAL STYLES ══════════════════════════════════════ */
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
    .modal-title-row { display: flex; align-items: center; gap: 10px; margin-bottom: 3px; }
    .modal-main-title {
        font-family: var(--font-display);
        font-size: 15px;
        font-weight: 700;
        color: var(--c-slate-900);
        margin: 0;
    }
    .modal-subtitle { font-size: 12px; color: var(--c-slate-400); margin: 0; }
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
    .badge-add { background: #dbeafe; color: #1d4ed8; }

    /* Form field group */
    .field-group { display: flex; flex-direction: column; gap: 14px; }
    .field-item  { display: flex; flex-direction: column; gap: 5px; }
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
    .field-input:hover { border-color: var(--c-blue-mid); }
    .field-input:focus { border-color: var(--c-blue); box-shadow: 0 0 0 3px rgba(37,99,235,.10); }
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
    .btn-modal-cancel:hover { background: var(--c-slate-50); border-color: var(--c-slate-300); }
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
    .btn-modal-save:hover     { background: #1d4ed8; }
    .btn-modal-save:disabled  { opacity: .6; cursor: not-allowed; }

    /* ══════════ TEMPLATE MODAL STYLES ══════════════════════════════ */
    /* Upload Zone */
    .upload-zone {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 8px; width: 100%; min-height: 150px;
        border: 2px dashed var(--c-slate-200); border-radius: var(--radius-md);
        cursor: pointer; transition: border-color .15s, background .15s;
        padding: 24px; text-align: center; background: var(--c-slate-50);
    }
    .upload-zone:hover, .upload-zone.drag-over { border-color: var(--c-blue); background: var(--c-blue-soft); }
    .upload-zone p     { font-size: 13px; color: var(--c-slate-500); margin: 0; }
    .upload-zone small { font-size: 11.5px; color: var(--c-slate-400); }
    .upload-zone input { display: none; }

    /* Position Finder layout */
    #tplPfContainer { display: none; flex-direction: row; gap: 16px; align-items: flex-start; }
    #tplPfLeft  { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 10px; }
    #tplPfRight { width: 220px; flex-shrink: 0; }

    .tpl-change-img-btn {
        font-size: 12px; color: var(--c-blue); background: none;
        border: none; cursor: pointer; padding: 0; text-decoration: underline; align-self: flex-start;
    }

    #tplPfImgWrap {
        position: relative; display: block; width: 100%;
        border-radius: 6px; overflow: hidden;
        box-shadow: var(--shadow-sm); cursor: crosshair;
    }
    #tplPfImgWrap img { 
        display: block; 
        width: 100%; 
        height: auto; 
        user-select: none; 
        pointer-events: none;
        max-width: 619px; /* tambah ini — atau pakai nilai tetap */
    }

    /* Drag boxes */
    .pf-drag-box {
        position: absolute; border-width: 2px; border-style: solid; border-radius: 3px;
        cursor: move; touch-action: none; user-select: none; z-index: 10;
    }
    .pf-drag-box.active-box { z-index: 20; outline: 2px solid #fbbf24; outline-offset: 2px; }
    .pf-resize-handle {
        position: absolute; right: 0; bottom: 0; width: 14px; height: 14px;
        cursor: se-resize; background: rgba(255,255,255,.8);
        border-top: 2px solid currentColor; border-left: 2px solid currentColor; border-radius: 2px 0 0 0;
    }
    .pf-box-label {
        position: absolute; top: -20px; left: 0; font-size: 10px; font-weight: 600;
        font-family: var(--font-display); white-space: nowrap;
        background: rgba(0,0,0,.55); color: #fff; padding: 1px 5px; border-radius: 3px; pointer-events: none;
    }

    /* Right panel */
    #tplPfBoxPanel {
        background: var(--c-slate-50); border: 1px solid var(--c-slate-200);
        border-radius: var(--radius-md); padding: 12px 14px;
    }
    #tplPfBoxPanel .panel-title {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .8px; color: var(--c-slate-400); margin-bottom: 10px;
    }
    #tplPfBoxList { display: flex; flex-direction: column; gap: 5px; }

    .pf-box-item {
        display: flex; align-items: center; gap: 8px; padding: 7px 9px;
        border-radius: 6px; border: 1px solid var(--c-slate-200);
        background: var(--c-white); font-size: 12px; cursor: pointer; transition: background .12s;
    }
    .pf-box-item:hover    { background: var(--c-slate-100); }
    .pf-box-item.selected { background: var(--c-blue-soft); border-color: var(--c-blue-mid); }
    .pf-box-swatch { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .pf-box-name   { flex: 1; color: var(--c-slate-700); font-family: var(--font-display); font-weight: 600; }
    .pf-box-coords { color: var(--c-slate-400); font-size: 11px; font-family: monospace; }

    .pf-hint       { font-size: 11.5px; color: var(--c-slate-400); text-align: center; margin: 0; }
    .pf-panel-hint { font-size: 11px; color: var(--c-slate-400); margin-top: 10px; line-height: 1.5; }

    @media (max-width: 640px) {
        #tplPfContainer { flex-direction: column; }
        #tplPfRight { width: 100%; }
    }

    /* ── Responsive ────────────────────────────────────── */
    @media (max-width: 640px) {
        .cert-card-header { padding: 14px 16px; gap: 10px; }
        .cert-table thead th,
        .cert-table tbody td { padding: 10px; }
        .cert-table thead th:first-child,
        .cert-table tbody td:first-child { padding-left: 14px; }
        .pagination-wrap { padding: 14px 16px; }
        .search-input { width: 160px; }
        .search-input:focus { width: 180px; }
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <h4>Sertifikat</h4>
    <p>Buat dan kelola sertifikat siswa secara efisien.</p>
</div>

{{-- Main Card --}}
<div class="cert-card">

    {{-- Card Header --}}
    <div class="cert-card-header">
        <div class="header-title">Semua Sertifikat</div>

        <div id="bulkActionBar" style="display:none; padding:8px 22px; border-bottom:1px solid var(--c-slate-100);">
            <span id="bulkCount" style="font-size:13px; color:var(--c-slate-500);"></span>
            <button onclick="bulkUpdateStatus()" class="btn btn-primary btn-sm ms-3">
                <i class="bi bi-arrow-repeat"></i> Update Status
            </button>
            <button onclick="clearSelection()" class="btn btn-secondary btn-sm ms-1">
                <i class="bi bi-x"></i> Batal
            </button>
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
            <button class="cs-btn" type="button" onclick="tog('filterTahun')" style="min-width:175px;">
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

        <button class="btn-create" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Buat Sertifikat
        </button>

        <button class="btn-create" onclick="openTemplateCertificateModal()">
            Template Sertifikat
        </button>
    </div>

    {{-- Table --}}
    <div class="cert-table-wrap">
        <table class="cert-table">
            <thead>
                <tr>
                    <th></th>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>No. Sertifikat</th>
                    <th>Nilai</th>
                    <th>Program</th>
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

{{-- ══ Modal Template Sertifikat ══════════════════════════════════════════ --}}
<div class="modal fade" id="modalTemplateSertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <div style="flex:1;">
                    <h5 class="modal-main-title">Template Sertifikat</h5>
                    <p class="modal-subtitle">Upload gambar lalu drag setiap box ke posisi yang sesuai</p>
                </div>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div class="modal-body">

                {{-- Upload Zone --}}
                <div id="tplUploadStep">
                    <label class="upload-zone" for="tplInputImage" id="tplUploadZoneLabel">
                        <input type="file" id="tplInputImage" accept="image/*">
                        <i class="bi bi-cloud-arrow-up" style="font-size:2rem;color:var(--c-slate-400);"></i>
                        <p>Seret gambar ke sini atau klik untuk memilih</p>
                        <small>PNG · JPG · WebP &nbsp;·&nbsp; Maks 5 MB</small>
                    </label>
                    <div id="tplExistingImgHint" style="display:none;margin-top:8px;font-size:12px;color:var(--c-slate-500);">
                        <i class="bi bi-info-circle"></i> Gambar saat ini sudah dimuat. Upload baru hanya jika ingin menggantinya.
                    </div>
                </div>

                {{-- Position Finder — Fixed 5 boxes --}}
                <div id="tplPfContainer">
                    <div id="tplPfLeft">
                        <button class="tpl-change-img-btn" type="button" onclick="tplResetImage()">
                            <i class="bi bi-arrow-left"></i> Ganti Gambar
                        </button>
                        <div id="tplPfImgWrap">
                            <img id="tplPfImg" src="" alt="template">
                        </div>
                        <p class="pf-hint">Klik field di kanan untuk fokus · Drag box · Tarik sudut untuk resize</p>
                    </div>

                    <div id="tplPfRight">
                        <div id="tplPfBoxPanel">
                            <div class="panel-title">Field Posisi</div>
                            <div id="tplPfBoxList"></div>
                            <p class="pf-panel-hint">
                                <i class="bi bi-info-circle"></i>
                                Klik field untuk aktifkan, lalu drag box ke posisi yang diinginkan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-save" id="tplBtnSave" onclick="tplHandleSubmit()">
                    <i class="bi bi-check-lg"></i>
                    <span id="tplBtnSaveLabel">Simpan Template</span>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal Tambah / Edit Sertifikat --}}
<div class="modal fade" id="modalSertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:540px;">
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

                    <div class="field-item">
                        <label class="field-label">Nomor Sertifikat</label>
                        <div class="field-icon-wrap">
                            <i class="bi bi-upc-scan f-icon"></i>
                            <input type="text" class="field-input" id="inputCertificateNumber" placeholder="Auto / Generate">
                        </div>
                    </div>

                    <div class="field-item">
                        <label class="field-label">Nama Siswa <span class="req">*</span></label>
                        <div class="field-icon-wrap">
                            <i class="bi bi-person f-icon"></i>
                            <input type="text" class="field-input" id="inputStudent" placeholder="Input nama siswa...">
                        </div>
                    </div>

                    <div class="field-item">
                        <label class="field-label">Program <span class="req">*</span></label>
                        <div class="field-icon-wrap">
                            <i class="bi bi-book f-icon"></i>
                            <input type="text" class="field-input" id="inputProgram" placeholder="Input nama program">
                        </div>
                    </div>

                    <div class="field-grid-2">
                        <div class="field-item">
                            <label class="field-label">Nilai</label>
                            <input type="text" class="field-input" id="inputGrade" placeholder="A / B / C">
                        </div>
                        <div class="field-item">
                            <label class="field-label">Level</label>
                            <input type="text" class="field-input" id="inputLevel">
                        </div>
                    </div>

                    <div class="field-item">
                        <label class="field-label">Status</label>
                        <select class="field-input" id="inputStatus">
                            <option value="Draft" selected>Draft</option>
                            <option value="Di Terbitkan">Di Terbitkan</option>
                        </select>
                    </div>

                    <div class="field-item">
                        <label class="field-label">Deskripsi</label>
                        <textarea class="field-input" rows="3" id="inputDescription" placeholder="Deskripsi sertifikat..."></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-save" id="btnSave" onclick="handleSubmit()">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnSaveLabel">Simpan Sertifikat</span>
                </button>
            </div>

        </div>
    </div>
</div>


{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
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
    // ── URL Constants ─────────────────────────────────────────────────────────
    const URL_DATA         = "{{ route('sertifikat.data') }}";
    const URL_STORE        = "{{ route('sertifikat.store') }}";
    const URL_SHOW         = "{{ url('sertifikat') }}";
    const URL_UPDATE       = "{{ url('sertifikat') }}";
    const URL_PRINT        = "{{ url('sertifikat') }}";
    const URL_BULK_UPDATE  = "{{ url('sertifikat/bulk-update') }}";
    const URL_BULK_DESTROY = "{{ url('sertifikat/bulk-destroy') }}";
    const CSRF             = "{{ csrf_token() }}";

    // ── URL Template ───────────────────────────────────────────────────────────────
    const URL_TPL_ACTIVE = "{{ route('template.active') }}";
    const URL_TPL_STORE  = "{{ route('template.store') }}";
    const URL_TPL_UPDATE = "{{ url('template') }}";

    const PREDEFINED_FIELDS = [
        { key: 'name',         label: 'Name',            color: '#3b82f6', widthKey: 'width_position_name',    heightKey: 'height_position_name'    },
        { key: 'cert_number',  label: 'Certificate No.', color: '#ef4444', widthKey: 'width_cert_number',      heightKey: 'height_cert_number'      },
        { key: 'grade',        label: 'Grade',            color: '#22c55e', widthKey: 'width_grade',            heightKey: 'height_grade'            },
        { key: 'program_name', label: 'Program Name',     color: '#f59e0b', widthKey: 'width_program_name',     heightKey: 'height_program_name'     },
        { key: 'publish_date', label: 'Publish Date',     color: '#8b5cf6', widthKey: 'width_publish_date',     heightKey: 'height_publish_date'     },
        { key: 'qr_code',      label: 'QR Code',          color: '#0ea5e9', widthKey: 'width_qr_code',          heightKey: 'height_qr_code'          },
    ];
    // ── Template State ─────────────────────────────────────────────────────────────
    let tplModalBS    = null;
    let tplEditId     = null;   // null = create, string = update
    let tplFile       = null;
    let tplNatW       = 0, tplNatH = 0;
    let tplBoxes      = [];
    let tplActiveKey  = null;

    // ── State ─────────────────────────────────────────────────────────────────
    let currentDetailId = null;
    let currentEditId   = null;
    let activeFilter    = '';
    let activeSearch    = '';
    let searchTimer     = null;
    let currentPage     = 1;
    let totalPages      = 1;
    let perPage         = 10;
    let selectedIds     = new Set();

    // ── Bootstrap Modals ──────────────────────────────────────────────────────
    let modalSertifikat, modalDetail;
    document.addEventListener('DOMContentLoaded', () => {
        modalSertifikat = new bootstrap.Modal(document.getElementById('modalSertifikat'));
        modalDetail     = new bootstrap.Modal(document.getElementById('modalDetail'));
        fetchData();
    });

    // ─────────────────────────────────────────────────────────────────────────
    // TABLE
    // ─────────────────────────────────────────────────────────────────────────

    function renderTable(rows) {
        const tbody = document.getElementById('tableBody');

        if (!rows || rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="11">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>Tidak ada sertifikat ditemukan.</p>
                </div>
            </td></tr>`;
            toggleBulkActionBar();
            return;
        }

        tbody.innerHTML = rows.map(cert => {
            const printable   = cert.file_path && cert.status === 'Di Terbitkan';
            const isPublished = cert.status === 'Di Terbitkan';

            return `
            <tr>
                <td>
                    <input type="checkbox" class="row-check" value="${cert.id}" onchange="handleSelect(this)">
                </td>
                <td><span class="row-number">${cert.no}</span></td>
                <td>
                    <div class="user-chip">
                        <div class="user-avatar">${(cert.username || '-').substring(0, 2).toUpperCase()}</div>
                        <span class="user-name">${cert.username || '-'}</span>
                    </div>
                </td>
                <td><code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;color:#334155;">${cert.certificate_number || '-'}</code></td>
                <td class="td-muted">${cert.grade || '-'}</td>
                <td class="td-muted">${cert.program_name || '-'}</td>
                <td class="td-muted">${cert.publication_date || '-'}</td>
                <td>${renderBadge(cert.status)}</td>
                <td>
                    <div class="action-wrap">
                        ${!isPublished
                            ? `<button class="act-btn act-btn-edit" onclick="openEditModal('${cert.id}')" title="Edit">
                                   <i class="bi bi-pencil-fill"></i>
                               </button>`
                            : ''}
                        <button class="act-btn act-btn-delete" onclick="onDelete('${cert.id}')" title="Hapus">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <button class="act-btn act-btn-view" onclick="onDetail('${cert.id}')" title="Detail">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        ${printable
                            ? `<button class="act-btn act-btn-print" onclick="onPrint('${cert.id}')" title="Cetak">
                                   <i class="bi bi-printer-fill"></i>
                               </button>`
                            : `<button class="act-btn act-btn-disabled" title="File belum tersedia" disabled>
                                   <i class="bi bi-printer-fill"></i>
                               </button>`}
                    </div>
                </td>
            </tr>`;
        }).join('');

        restoreCheckboxState();
    }

    function renderBadge(status) {
        if (status === 'Di Terbitkan') return `<span class="status-badge published"><i class="bi bi-check-circle-fill"></i>${status}</span>`;
        if (status === 'Di Proses')    return `<span class="status-badge process"><i class="bi bi-clock-fill"></i>${status}</span>`;
        return `<span class="status-badge draft"><i class="bi bi-pencil-fill"></i>${status}</span>`;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FETCH & PAGINATION
    // ─────────────────────────────────────────────────────────────────────────

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
            if (activeFilter) {
                const tahun = activeFilter.split('/')[0].trim();
                params.set('tahun', tahun);
            }
            params.set('page',     page);
            params.set('per_page', perPage);

            const res  = await fetch(`${URL_DATA}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            renderTable(json.data);
            renderPagination(json.meta);
        } catch {
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
        const from  = meta.from  ?? 0;
        const to    = meta.to    ?? 0;
        const total = meta.total ?? 0;

        let pages = '';
        const range = 2;
        const start = Math.max(1, currentPage - range);
        const end   = Math.min(totalPages, currentPage + range);

        if (start > 1) {
            pages += pageBtn(1, '1');
            if (start > 2) pages += `<span style="padding:0 4px;color:#94a3b8;font-size:13px;">…</span>`;
        }
        for (let i = start; i <= end; i++) pages += pageBtn(i, i, i === currentPage);
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
            </div>`;
    }

    function pageBtn(page, label, active = false) {
        return `<button onclick="fetchData(${page})" class="pg-btn ${active ? 'pg-active' : ''}">${label}</button>`;
    }

    function changePerPage(val) {
        perPage = parseInt(val);
        fetchData(1);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SEARCH & FILTER
    // ─────────────────────────────────────────────────────────────────────────

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
        const btn    = document.querySelector('#' + id + ' .cs-btn');
        const menu   = document.getElementById(id + '-m');
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

    // ─────────────────────────────────────────────────────────────────────────
    // BULK SELECTION
    // ─────────────────────────────────────────────────────────────────────────

    function handleSelect(cb) {
        if (cb.checked) selectedIds.add(cb.value);
        else selectedIds.delete(cb.value);
        toggleBulkActionBar();
    }

    function toggleBulkActionBar() {
        const bar   = document.getElementById('bulkActionBar');
        const count = document.getElementById('bulkCount');
        if (!bar) return;

        if (selectedIds.size > 0) {
            bar.style.display  = 'block';
            count.textContent  = `${selectedIds.size} item dipilih`;
        } else {
            bar.style.display = 'none';
        }
    }

    function clearSelection() {
        selectedIds.clear();
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        toggleBulkActionBar();
    }

    function restoreCheckboxState() {
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = selectedIds.has(cb.value);
        });
        toggleBulkActionBar();
    }

    function afterBulkSuccess(message) {
        selectedIds.clear();
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        toggleBulkActionBar();
        Swal.fire({
            icon: 'success', title: 'Berhasil!', text: message,
            timer: 1800, showConfirmButton: false,
            customClass: { popup: 'swal-popup-custom' },
        });
        fetchData(currentPage);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BULK ACTIONS
    // ─────────────────────────────────────────────────────────────────────────

    async function bulkUpdateStatus() {
        if (selectedIds.size === 0) return;

        await Swal.fire({
            title: `<span style="font-size:15px;font-weight:700;">${selectedIds.size} Item Dipilih</span>`,
            html: `
                <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                    <button style="padding:12px 16px;border-radius:10px;border:1.5px solid #e2e8f0;
                                   background:#fff;cursor:pointer;font-size:13px;font-weight:600;
                                   color:#1d4ed8;text-align:left;transition:all .2s;"
                            onmouseenter="this.style.borderColor='#3b82f6';this.style.background='#eff6ff'"
                            onmouseleave="this.style.borderColor='#e2e8f0';this.style.background='#fff'"
                            onclick="Swal.close(); setTimeout(() => doBulkUpdateStatus(), 100)">
                        <i class="bi bi-arrow-repeat me-2"></i> Update Status
                    </button>
                    <button style="padding:12px 16px;border-radius:10px;border:1.5px solid #e2e8f0;
                                   background:#fff;cursor:pointer;font-size:13px;font-weight:600;
                                   color:#dc2626;text-align:left;transition:all .2s;"
                            onmouseenter="this.style.borderColor='#ef4444';this.style.background='#fef2f2'"
                            onmouseleave="this.style.borderColor='#e2e8f0';this.style.background='#fff'"
                            onclick="Swal.close(); setTimeout(() => doBulkDestroy(), 100)">
                        <i class="bi bi-trash me-2"></i> Hapus Data
                    </button>
                    <button style="padding:12px 16px;border-radius:10px;border:1.5px solid #e2e8f0;
                                   background:#fff;cursor:pointer;font-size:13px;font-weight:600;
                                   color:#d97706;text-align:left;transition:all .2s;"
                            onmouseenter="this.style.borderColor='#d97706';this.style.background='#fffbeb'"
                            onmouseleave="this.style.borderColor='#e2e8f0';this.style.background='#fff'"
                            onclick="Swal.close(); setTimeout(() => doBulkPrint(), 100)">
                        <i class="bi bi-printer-fill me-2"></i> Print
                    </button>
                </div>`,
            showConfirmButton: false,
            showCancelButton:  true,
            cancelButtonText:  'Batal',
            customClass: { popup: 'swal-popup-custom', cancelButton: 'swal-cancel-custom' },
        });
    }

    async function doBulkUpdateStatus() {
        const statuses     = ['Draft', 'Di Terbitkan'];
        const radioOptions = statuses.map(s => `
            <label style="display:flex;align-items:center;gap:12px;padding:10px 14px;
                   border-radius:10px;cursor:pointer;border:1.5px solid #e2e8f0;
                   background:#fff;margin-bottom:8px;transition:all .2s;"
                   onclick="highlightStatus(this)">
                <input type="radio" name="bulkStatus" value="${s}" style="accent-color:#3b82f6;width:16px;height:16px;">
                <span style="display:flex;flex-direction:column;">
                    <span style="font-weight:600;font-size:13px;color:#111827;">${s}</span>
                    <span style="font-size:11px;color:#6b7280;">${statusDesc(s)}</span>
                </span>
            </label>`).join('');

        Swal.fire({
            title: `<span style="font-size:15px;font-weight:700;">Update Status</span>`,
            html: `
                <p style="font-size:12px;color:#6b7280;margin-bottom:14px;">
                    <b>${selectedIds.size}</b> sertifikat dipilih
                </p>
                <div id="statusOptions" style="text-align:left;">${radioOptions}</div>`,
            showCancelButton:   true,
            confirmButtonText:  '<i class="bi bi-check-lg"></i> Terapkan',
            cancelButtonText:   'Batal',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor:  '#e2e8f0',
            customClass: { cancelButton: 'swal-cancel-custom', popup: 'swal-popup-custom' },
            preConfirm: async () => {
                const selected = document.querySelector('input[name="bulkStatus"]:checked');
                if (!selected) { Swal.showValidationMessage('Pilih salah satu status.'); return false; }

                if (selected.value === 'Di Terbitkan') {
                    const konfirmasi = await Swal.fire({
                        icon: 'warning',
                        title: 'Yakin menerbitkan?',
                        html: 'Status <b>Di Terbitkan</b> bersifat permanen.',
                        showCancelButton:   true,
                        confirmButtonText:  'Ya, Terbitkan!',
                        cancelButtonText:   'Tinjau Ulang',
                        confirmButtonColor: '#22c55e',
                        cancelButtonColor:  '#e2e8f0',
                        customClass: { cancelButton: 'swal-cancel-custom', popup: 'swal-popup-custom' },
                    });
                    if (!konfirmasi.isConfirmed) return false;
                }

                Swal.showLoading();
                try {
                    const res  = await fetch(URL_BULK_UPDATE, {
                        method: 'POST',
                        headers: {
                            'Content-Type':     'application/json',
                            'X-CSRF-TOKEN':     CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ ids: Array.from(selectedIds), status: selected.value }),
                    });
                    const json = await res.json();
                    if (!json.success) { Swal.showValidationMessage(json.message ?? 'Gagal update status.'); return false; }
                    return json;
                } catch {
                    Swal.showValidationMessage('Terjadi kesalahan. Coba lagi.');
                    return false;
                }
            },
        }).then(result => {
            if (result.isConfirmed && result.value) afterBulkSuccess('Status berhasil diperbarui.');
        });
    }

    async function doBulkDestroy() {
        const konfirmasi = await Swal.fire({
            icon: 'warning',
            title: 'Hapus Data?',
            html: `<b>${selectedIds.size}</b> sertifikat akan dihapus permanen.`,
            showCancelButton:   true,
            confirmButtonText:  'Ya, Hapus!',
            cancelButtonText:   'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor:  '#e2e8f0',
            customClass: { cancelButton: 'swal-cancel-custom', popup: 'swal-popup-custom' },
        });
        if (!konfirmasi.isConfirmed) return;

        Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res  = await fetch(URL_BULK_DESTROY, {
                method: 'DELETE',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ ids: Array.from(selectedIds) }),
            });
            const json = await res.json();
            if (!json.success) throw new Error(json.message ?? 'Gagal menghapus.');
            afterBulkSuccess('Data berhasil dihapus.');
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
        }
    }

    async function doBulkPrint() {
        const konfirmasi = await Swal.fire({
            icon: 'info',
            title: 'Print Data?',
            html: `<b>${selectedIds.size}</b> sertifikat yang di pilih akan di print.`,
            showCancelButton:   true,
            confirmButtonText:  'Ya!',
            cancelButtonText:   'Batal',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor:  '#e2e8f0',
            customClass: { cancelButton: 'swal-cancel-custom', popup: 'swal-popup-custom' },
        });
        if (!konfirmasi.isConfirmed) return;

        Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res  = await fetch(URL_BULK_DESTROY, {
                method: 'DELETE',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ ids: Array.from(selectedIds) }),
            });
            const json = await res.json();
            if (!json.success) throw new Error(json.message ?? 'Gagal menghapus.');
            afterBulkSuccess('Data berhasil dihapus.');
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FORM (Create / Edit)
    // ─────────────────────────────────────────────────────────────────────────

    function getFormData() {
        return {
            certificateNumber: document.getElementById('inputCertificateNumber').value.trim(),
            username:          document.getElementById('inputStudent').value.trim(),
            programName:       document.getElementById('inputProgram').value.trim(),
            grade:             document.getElementById('inputGrade').value.trim(),
            level:             document.getElementById('inputLevel').value.trim(),
            status:            document.getElementById('inputStatus').value.trim(),
            desc:              document.getElementById('inputDescription').value.trim(),
        };
    }

    function resetForm() {
        ['inputCertificateNumber', 'inputStudent', 'inputProgram',
         'inputGrade', 'inputLevel', 'inputDescription']
            .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
        document.getElementById('inputStatus').value = 'Draft';
    }

    function openCreateModal() {
        currentEditId = null;
        resetForm();
        modalSertifikat.show();
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

            if (d.status === 'Di Terbitkan') {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Bisa Diedit',
                    html: `Sertifikat <b>${d.certificate_number}</b> sudah diterbitkan.`,
                });
                return;
            }

            document.getElementById('inputCertificateNumber').value = d.certificate_number || '';
            document.getElementById('inputStudent').value           = d.username           || '';
            document.getElementById('inputProgram').value           = d.program_name       || '';
            document.getElementById('inputGrade').value             = d.grade              || '';
            document.getElementById('inputLevel').value             = d.level              || '';
            document.getElementById('inputDescription').value       = d.description        || '';
            document.getElementById('inputStatus').value            = d.status             || 'Draft';

            modalSertifikat.show();
        } catch {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data.' });
        }
    }

    async function handleSubmit() {
        const { certificateNumber, username, programName, grade, level, status, desc } = getFormData();

        if (!username || !programName) {
            await Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Nama siswa dan Program wajib diisi.' });
            return;
        }

        const isEdit = !!currentEditId;
        const result = await Swal.fire({
            title: isEdit ? 'Update data?' : 'Simpan data?',
            icon: 'question',
            showCancelButton:  true,
            confirmButtonText: isEdit ? 'Ya, Update!' : 'Ya, Simpan!',
        });
        if (!result.isConfirmed) return;

        if (status === 'Di Terbitkan') {
            const confirmPublish = await Swal.fire({
                icon: 'warning',
                title: 'Yakin menerbitkan?',
                html: 'Status ini permanen & tidak bisa diedit lagi.',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Terbitkan!',
            });
            if (!confirmPublish.isConfirmed) return;
        }

        await submitData({ certificateNumber, username, programName, grade, level, status, desc });
    }

    async function submitData(data) {
        const btn = document.getElementById('btnSave');
        btn.disabled = true;
        document.getElementById('btnSaveLabel').textContent = 'Menyimpan...';

        const isEdit = !!currentEditId;
        const url    = isEdit ? `${URL_UPDATE}/${currentEditId}` : URL_STORE;
        const method = isEdit ? 'PUT' : 'POST';

        try {
            const res  = await fetch(url, {
                method,
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    certificate_number: data.certificateNumber,
                    username:           data.username,
                    program_name:       data.programName,
                    grade:              data.grade,
                    level:              data.level,
                    status:             data.status,
                    description:        data.desc,
                }),
            });
            const json = await res.json();

            if (json.success) {
                modalSertifikat.hide();
                fetchData();
            } else {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: json.message ?? 'Terjadi kesalahan.' });
            }
        } catch {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan.' });
        } finally {
            btn.disabled = false;
            document.getElementById('btnSaveLabel').textContent = 'Simpan Sertifikat';
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DETAIL
    // ─────────────────────────────────────────────────────────────────────────

    async function onDetail(id) {
        Swal.fire({ title: 'Memuat data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res  = await fetch(`${URL_SHOW}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            if (!json.success) throw new Error();

            const d = json.data;

            Swal.fire({
                title: 'Detail Sertifikat',
                width: 700,
                showCloseButton:   true,
                confirmButtonText: 'Tutup',
                customClass: { popup: 'swal-wide' },
                html: `
                    <div style="text-align:left;font-size:13px;line-height:1.6">
                        <p><b>Username:</b> ${d.username || '-'}</p>
                        <p><b>Certificate Number:</b> ${d.certificate_number || '-'}</p>
                        <p><b>Program Name:</b> ${d.program_name || '-'}</p>
                        <p><b>Grade:</b> ${d.grade || '-'}</p>
                        <p><b>Level:</b> ${d.level || '-'}</p>
                        <p><b>Status:</b> ${renderBadge(d.status)}</p>
                        <hr>
                        <p><b>Publication Date:</b> ${d.publication_date || '-'}</p>
                        <p><b>Created At:</b> ${d.created_at || '-'}</p>
                        <hr>
                        <p><b>Description:</b></p>
                        <div style="background:#f8fafc;padding:10px;border-radius:8px;">${d.description || '-'}</div>
                        <hr>
                        <div class="row g-3 align-items-start">
                            <div class="col-md-4 text-center">
                                <p class="mb-2"><b>QR Code</b></p>
                                ${d.file_path
                                    ? `<img src="${d.file_path}" 
                                            alt="QR Code" 
                                            class="img-thumbnail"
                                            style="width:150px;height:150px;object-fit:contain;" />`
                                    : `<span class="text-muted">-</span>`
                                }
                            </div>
                            <div class="col-md-8">
                                <p class="mb-2"><b>Digital Signature</b></p>

                                <textarea class="form-control" 
                                        readonly 
                                        rows="5"
                                        style="font-size:11px;resize:none;">
                                        ${d.digital_signature || '-'}
                                </textarea>
                            </div>
                        </div>
                    </div>`
            });
        } catch {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat detail data.' });
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────────────────────────────────

    async function onDelete(id) {
        currentDetailId = id;

        const confirm = await Swal.fire({
            title: 'Hapus Data?',
            text: 'Data sertifikat akan dihapus permanen!',
            icon: 'warning',
            showCancelButton:   true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor:  '#e2e8f0',
            confirmButtonText:  'Ya, Hapus',
            cancelButtonText:   'Batal',
            customClass: { popup: 'swal-popup-custom' },
        });
        if (!confirm.isConfirmed) return;

        Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res  = await fetch(`${URL_UPDATE}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            });
            const json = await res.json();
            if (!json.success) throw new Error(json.message || 'Gagal menghapus data');

            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data berhasil dihapus', timer: 1500, showConfirmButton: false });
            fetchData();
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message || 'Terjadi kesalahan saat menghapus data' });
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRINT
    // ─────────────────────────────────────────────────────────────────────────

    function onPrint(id) {
        currentDetailId = id;
        doPrint();
    }

    function doPrint() {
        if (!currentDetailId) return;
        window.open(`${URL_PRINT}/${currentDetailId}/print`, '_blank');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    function statusDesc(s) {
        if (s === 'Draft')        return 'Sertifikat belum diproses';
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

    //  ====================================================================
    //  ====================================================================
    //  ====================================================================
    //  ====================================================================
    // template ============================================================
    //  ====================================================================
    //  ====================================================================
    //  ====================================================================
    //  ====================================================================

    // Init modal Bootstrap — tambahkan ke DOMContentLoaded existing
    document.addEventListener('DOMContentLoaded', () => {
        tplModalBS = new bootstrap.Modal(document.getElementById('modalTemplateSertifikat'));
        tplInitFileInput();

        // Click area gambar → pindah box aktif ke titik klik
        document.getElementById('tplPfImgWrap').addEventListener('click', e => {
            if (e.target.closest('.pf-drag-box')) return;
            const b = tplGetBox(tplActiveKey);
            if (!b) return;
            const rect = document.getElementById('tplPfImgWrap').getBoundingClientRect();
            b.px = (e.clientX - rect.left) - b.bw / 2;
            b.py = (e.clientY - rect.top)  - b.bh / 2;
            tplApplyDOM(b);
            tplRenderList();
        });
    });

    window.addEventListener('resize', () => {
        if (!tplNatW) return;
        const s = tplScale();
        tplBoxes.forEach(b => {
            // Konversi balik ke real → scale ulang dengan s baru
            const realX = tplToReal(b.px); // ini SALAH kalau s sudah berubah
            // Simpan real coords di object b agar bisa rescale
            b.px = (b.realX ?? tplToReal(b.px)) * s;
            b.py = (b.realY ?? tplToReal(b.py)) * s;
            b.bw = (b.realW ?? tplToReal(b.bw)) * s;
            b.bh = (b.realH ?? tplToReal(b.bh)) * s;
            tplApplyDOM(b);
        });
        tplRenderList();
    });

    // ── Entry Point ────────────────────────────────────────────────────────────────
    async function openTemplateCertificateModal() {
        Swal.fire({ title: 'Memuat template...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res  = await fetch(URL_TPL_ACTIVE, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            Swal.close();

            tplResetState();

            if (json.success && json.data) {
                console.log("INI")
                console.log(json.data)
                // Template sudah ada → edit mode
                tplEditId = json.data.id;
                document.getElementById('tplExistingImgHint').style.display = 'block';
                tplShowWithImage(json.data.path, json.data);
            }
            // Template belum ada → create mode, upload zone tampil (sudah default)

            tplModalBS.show();
        } catch {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat template.' });
        }
    }

    // ── Scale & Math ───────────────────────────────────────────────────────────────
    function tplScale()          { return tplNatW ? document.getElementById('tplPfImg').getBoundingClientRect().width / tplNatW : 1; }
    function tplToReal(v)        { return Math.round(v / tplScale()); }
    function tplClamp(v, lo, hi) { return Math.max(lo, Math.min(hi, v)); }
    function tplGetBox(key)      { return tplBoxes.find(b => b.key === key); }

    // ── DOM Apply ──────────────────────────────────────────────────────────────────
    // function tplApplyDOM(b) {
    //     const wrap = document.getElementById('tplPfImgWrap');
    //     b.px = tplClamp(b.px, 0, wrap.clientWidth  - b.bw);
    //     b.py = tplClamp(b.py, 0, wrap.clientHeight - b.bh);
    //     b.el.style.left   = b.px + 'px';
    //     b.el.style.top    = b.py + 'px';
    //     b.el.style.width  = b.bw + 'px';
    //     b.el.style.height = b.bh + 'px';
    // }
    function tplApplyDOM(b) {
    const wrap = document.getElementById('tplPfImgWrap');
    b.px = tplClamp(b.px, 0, wrap.clientWidth  - b.bw);
    b.py = tplClamp(b.py, 0, wrap.clientHeight - b.bh);
    b.el.style.left   = b.px + 'px';
    b.el.style.top    = b.py + 'px';
    b.el.style.width  = b.bw + 'px';
    b.el.style.height = b.bh + 'px';

    // Simpan nilai real setiap update
    b.realX = tplToReal(b.px);
    b.realY = tplToReal(b.py);
    b.realW = tplToReal(b.bw);
    b.realH = tplToReal(b.bh);
}

    function tplApplyColor(el, color) {
        const r = parseInt(color.slice(1,3),16);
        const g = parseInt(color.slice(3,5),16);
        const b = parseInt(color.slice(5,7),16);
        el.style.borderColor = color;
        el.style.background  = `rgba(${r},${g},${b},0.13)`;
    }

    // ── Active box ─────────────────────────────────────────────────────────────────
    function tplSetActive(key) {
        tplActiveKey = key;
        tplBoxes.forEach(b => b.el.classList.toggle('active-box', b.key === key));
        tplRenderList();
    }

    // ── Render panel kanan ─────────────────────────────────────────────────────────
    function tplRenderList() {
        const list = document.getElementById('tplPfBoxList');
        list.innerHTML = '';
        tplBoxes.forEach(b => {
            const item = document.createElement('div');
            item.className = 'pf-box-item' + (b.key === tplActiveKey ? ' selected' : '');

            const sw = document.createElement('div');
            sw.className = 'pf-box-swatch';
            sw.style.background = b.color;

            const name = document.createElement('span');
            name.className   = 'pf-box-name';
            name.textContent = b.label;

            const coords = document.createElement('span');
            coords.className   = 'pf-box-coords';
            coords.textContent = `(${b.realX ?? tplToReal(b.px)}, ${b.realY ?? tplToReal(b.py)})`;

            item.appendChild(sw);
            item.appendChild(name);
            item.appendChild(coords);
            item.addEventListener('click', () => tplSetActive(b.key));
            list.appendChild(item);
        });
    }

    // ── Create box element ─────────────────────────────────────────────────────────
    function tplCreateBox(field, px, py, bw, bh) {
        const el = document.createElement('div');
        el.className = 'pf-drag-box';
        tplApplyColor(el, field.color);

        const lbl = document.createElement('div');
        lbl.className   = 'pf-box-label';
        lbl.textContent = field.label;
        el.appendChild(lbl);

        const handle = document.createElement('div');
        handle.className   = 'pf-resize-handle';
        handle.style.color = field.color;
        el.appendChild(handle);

        document.getElementById('tplPfImgWrap').appendChild(el);

        const b = { key: field.key, label: field.label, color: field.color, field, px, py, bw, bh, el };
        tplBoxes.push(b);
        tplApplyDOM(b);
        tplAttachEvents(b, handle);
        return b;
    }

    // ── Drag & Resize events ───────────────────────────────────────────────────────
    function tplAttachEvents(b, handle) {
        const wrap = document.getElementById('tplPfImgWrap');
        let dragging = false, offX = 0, offY = 0;
        let resizing = false, rsX = 0, rsY = 0, rsW = 0, rsH = 0;

        b.el.addEventListener('pointerdown', e => {
            if (e.target === handle) return;
            e.preventDefault();
            tplSetActive(b.key);
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
            tplApplyDOM(b);
            tplRenderList();
        });
        b.el.addEventListener('pointerup',     () => dragging = false);
        b.el.addEventListener('pointercancel', () => dragging = false);

        handle.addEventListener('pointerdown', e => {
            e.preventDefault(); e.stopPropagation();
            tplSetActive(b.key);
            resizing = true;
            rsX = e.clientX; rsY = e.clientY;
            rsW = b.bw;      rsH = b.bh;
            handle.setPointerCapture(e.pointerId);
        });
        handle.addEventListener('pointermove', e => {
            if (!resizing) return;
            b.bw = Math.max(20, rsW + (e.clientX - rsX));
            b.bh = Math.max(20, rsH + (e.clientY - rsY));
            tplApplyDOM(b);
            tplRenderList();
        });
        handle.addEventListener('pointerup',     () => resizing = false);
        handle.addEventListener('pointercancel', () => resizing = false);
    }

    // ── Show image + spawn boxes ───────────────────────────────────────────────────
    function tplShowWithImage(src, savedData) {
        const img = document.getElementById('tplPfImg');

        img.onload = function () {
            tplNatW = img.naturalWidth;
            tplNatH = img.naturalHeight;
            img.style.maxWidth = tplNatW + 'px';
            document.getElementById('tplUploadStep').style.display  = 'none';
            document.getElementById('tplPfContainer').style.display = 'flex';

            // Bersihkan boxes lama dulu
            tplBoxes.forEach(b => { if (b.el.parentNode) b.el.parentNode.removeChild(b.el); });
            tplBoxes     = [];
            tplActiveKey = null;

            // Tunggu layout selesai & gambar punya clientWidth > 0
            function spawnWhenReady(attempt) {
                const w = img.getBoundingClientRect().width;
                if (w > 0) {
                    spawnBoxes(savedData);
                } else if (attempt < 20) {
                    // Coba lagi di frame berikutnya (maks 20x ~ 333ms)
                    requestAnimationFrame(() => spawnWhenReady(attempt + 1));
                }
            }
            requestAnimationFrame(() => spawnWhenReady(0));
        };

        img.src = src;
    }

    function spawnBoxes(savedData) {
        const s = tplScale();

        PREDEFINED_FIELDS.forEach((field, i) => {
            const px = savedData ? (savedData[`x_position_${field.key}`] ?? 0) * s : 10 * s;
            const py = savedData ? (savedData[`y_position_${field.key}`] ?? 0) * s : (10 + i * 40) * s;
            const bw = savedData && savedData[field.widthKey]  ? savedData[field.widthKey]  * s : 120 * s;
            const bh = savedData && savedData[field.heightKey] ? savedData[field.heightKey] * s : 24  * s;
            tplCreateBox(field, px, py, bw, bh);
        });

        tplSetActive(PREDEFINED_FIELDS[0].key);
    }

    // ── File input ─────────────────────────────────────────────────────────────────
    function tplInitFileInput() {
        const input = document.getElementById('tplInputImage');
        const zone  = document.getElementById('tplUploadZoneLabel');

        input.addEventListener('change', e => {
            if (e.target.files[0]) tplLoadFile(e.target.files[0]);
        });
        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('drag-over');
            if (e.dataTransfer.files[0]) tplLoadFile(e.dataTransfer.files[0]);
        });
    }

    function tplLoadFile(file) {
        if (!file.type.startsWith('image/')) return;
        tplFile = file;
        tplShowWithImage(URL.createObjectURL(file), null);
    }

    // ── Reset ──────────────────────────────────────────────────────────────────────
    function tplResetState() {
        tplFile      = null;
        tplNatW      = tplNatH = 0;
        tplEditId    = null;
        tplBoxes.forEach(b => { if (b.el.parentNode) b.el.parentNode.removeChild(b.el); });
        tplBoxes     = [];
        tplActiveKey = null;

        document.getElementById('tplPfImg').src                      = '';
        document.getElementById('tplInputImage').value               = '';
        document.getElementById('tplUploadStep').style.display       = 'block';
        document.getElementById('tplPfContainer').style.display      = 'none';
        document.getElementById('tplExistingImgHint').style.display  = 'none';
        tplRenderList();
    }

    function tplResetImage() {
        tplFile  = null;
        tplNatW  = tplNatH = 0;
        tplBoxes.forEach(b => { if (b.el.parentNode) b.el.parentNode.removeChild(b.el); });
        tplBoxes     = [];
        tplActiveKey = null;

        document.getElementById('tplPfImg').src             = '';
        document.getElementById('tplInputImage').value      = '';
        document.getElementById('tplUploadStep').style.display    = 'block';
        document.getElementById('tplPfContainer').style.display   = 'none';
        tplRenderList();
    }

    // ── Collect fields ─────────────────────────────────────────────────────────────
    function tplCollectFields() {
        const out = {};
        tplBoxes.forEach(b => {
            out[`x_position_${b.key}`] = b.realX ?? tplToReal(b.px);
            out[`y_position_${b.key}`] = b.realY ?? tplToReal(b.py);
            out[b.field.widthKey]      = b.realW ?? tplToReal(b.bw);
            out[b.field.heightKey]     = b.realH ?? tplToReal(b.bh);
        });
        return out;
    }

    // ── Submit ─────────────────────────────────────────────────────────────────────
    async function tplHandleSubmit() {
        const isEdit = !!tplEditId;

        if (!isEdit && !tplFile) {
            Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Gambar template wajib dipilih.' });
            return;
        }
        if (!tplNatW) {
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

        const btn = document.getElementById('tplBtnSave');
        btn.disabled = true;
        document.getElementById('tplBtnSaveLabel').textContent = 'Menyimpan...';

        try {
            const fields = tplCollectFields();
            const form   = new FormData();

            if (tplFile) form.append('image', tplFile);
            form.append('width_template',  tplNatW);
            form.append('height_template', tplNatH);
            Object.entries(fields).forEach(([k, v]) => form.append(k, v));
            if (isEdit) form.append('_method', 'PUT');

            const url = isEdit ? `${URL_TPL_UPDATE}/${tplEditId}` : URL_TPL_STORE;
            const res = await fetch(url, {
                method:  'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body:    form,
            });
            const json = await res.json();

            if (json.success) {
                tplModalBS.hide();
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: json.message ?? 'Terjadi kesalahan.' });
            }
        } catch {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan template.' });
        } finally {
            btn.disabled = false;
            document.getElementById('tplBtnSaveLabel').textContent = 'Simpan Template';
        }
    }

</script>
@endpush
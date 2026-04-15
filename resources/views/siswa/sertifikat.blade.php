@extends('layouts.app')
@section('title', 'Sertifikat Saya')
@section('page-title', 'Sertifikat Saya')

@section('content')
<div class="page-header">
    <h4>Sertifikat Saya</h4>
    <p>Daftar semua sertifikat yang kamu miliki.</p>
</div>

<div class="card-modern">
    <div class="card-header-modern d-flex align-items-center justify-content-between gap-3">
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold" style="font-size: 15px;">Semua Sertifikat</h6>
        </div>
        <div class="search-wrap">
            <span class="search-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
                </svg>
            </span>
            <input type="text" class="search-input" id="searchSertifikat"
                   placeholder="Cari sertifikat..."
                   onkeyup="handleSearch(this)">
            <button class="clear-btn" id="clearSearch" onclick="clearSearchInput()" style="display:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 13px;">
                <thead>
                    <tr class="table-light">
                        <th class="px-4 py-3 fw-semibold text-secondary">No</th>
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
                    {{-- Diisi via AJAX --}}
                    <tr id="loadingRow">
                        <td colspan="9" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const URL_DATA     = "{{ route('siswa.sertifikat.data') }}";
    const URL_DOWNLOAD = "{{ url('siswa/sertifikat') }}";

    function renderTable(rows) {
        const tbody = document.getElementById('tableBody');

        if (!rows || rows.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox me-2"></i>Tidak ada sertifikat ditemukan.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = rows.map(cert => `
            <tr>
                <td class="px-4 py-3">${cert.no}</td>

                <td class="py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                            ${cert.certificate_number.substring(0, 2).toUpperCase()}
                        </div>
                        ${cert.certificate_number}
                    </div>
                </td>

                <td class="py-3">${cert.grade}</td>
                <td class="py-3 text-muted">${cert.program_name}</td>
                <td class="py-3 text-muted">${cert.program_level}</td>
                <td class="py-3 text-muted">${cert.description}</td>
                <td class="py-3 text-muted">${cert.issued_date}</td>

                <td class="py-3">${renderBadge(cert.status)}</td>

                <td class="py-3">
                    ${cert.has_file && cert.status === 'Di Terbitkan'
                        ? `<span class="badge text-bg-primary" style="cursor:pointer;" onclick="onDownload(${cert.id})" title="Unduh Sertifikat">
                               <i class="bi bi-download"></i>
                           </span>`
                        : `<span class="badge text-bg-secondary" title="Belum tersedia">
                               <i class="bi bi-slash-circle"></i>
                           </span>`
                    }
                </td>
            </tr>
        `).join('');
    }

    function renderBadge(status) {
        if (status === 'Di Terbitkan') {
            return `<span class="badge bg-success-subtle text-success rounded-pill">
                        <i class="bi bi-check-circle-fill me-1"></i>${status}
                    </span>`;
        } else if (status === 'Di Proses') {
            return `<span class="badge bg-warning-subtle text-warning rounded-pill">
                        <i class="bi bi-clock-fill me-1"></i>${status}
                    </span>`;
        }
        return `<span class="badge bg-secondary-subtle text-secondary rounded-pill">
                    <i class="bi bi-pencil-fill me-1"></i>${status}
                </span>`;
    }

    async function fetchData(keyword = '') {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = `
            <tr id="loadingRow">
                <td colspan="9" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Memuat data...
                </td>
            </tr>`;

        try {
            const params = keyword ? `?search=${encodeURIComponent(keyword)}` : '';
            const res    = await fetch(URL_DATA + params, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!res.ok) throw new Error('Gagal mengambil data.');

            const json = await res.json();
            renderTable(json.data);
        } catch (err) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4 text-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>${err.message}
                    </td>
                </tr>`;
        }
    }

    let searchTimer = null;

    function handleSearch(el) {
        document.getElementById('clearSearch').style.display = el.value ? 'flex' : 'none';
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchData(el.value.trim()), 500);
    }

    function clearSearchInput() {
        const input = document.getElementById('searchSertifikat');
        input.value = '';
        document.getElementById('clearSearch').style.display = 'none';
        clearTimeout(searchTimer);
        fetchData('');
    }

    function onDownload(id) {
        window.location.href = `${URL_DOWNLOAD}/${id}/download`;
    }

    document.addEventListener('DOMContentLoaded', () => fetchData());
</script>
@endpush
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
            <h6 class="mb-0 fw-bold" style="font-size: 15px;">Semua Serifikat</h6>
        </div>
        <div class="search-wrap">
            <span class="search-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
                </svg>
            </span>
            <input type="text" class="search-input" id="searchSiswa"
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
        @php
            $students = [
                ['CERT-TOEFL-2025-0001', 'B', 'TOEFL Preparation', 'Intermediate', 'Lulus TOEFL dengan skor baik', '09 Jan 2025', 'Di Terbitkan'],
                ['CERT-IELTS-2025-0002', 'A', 'IELTS Preparation', 'Advanced', 'Lulus IELTS dengan skor tinggi', '12 Jan 2025', 'Di Terbitkan'],
                ['CERT-ENG-2025-0003', 'A', 'Basic English', 'Beginner', 'Menguasai dasar bahasa Inggris', '15 Jan 2025', 'Di Terbitkan'],
                ['-', '-', 'TOEIC Preparation', '-', 'Belum mengikuti ujian', '-', 'Draft'],
                ['CERT-BUS-2025-0005', 'A', 'Business English', 'Advanced', 'Komunikasi bisnis sangat baik', '20 Jan 2025', 'Di Terbitkan'],
                ['CERT-CONV-2025-0006', 'B', 'Conversation Class', 'Intermediate', 'Kemampuan speaking meningkat', '22 Jan 2025', 'Di Proses'],
                ['-', '-', 'TOEFL Preparation', '-', 'Masih dalam proses belajar', '-', 'Draft'],
            ];
        @endphp
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
                <tbody>
                    @foreach($students as $act)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>

                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                        {{ strtoupper(substr($act[0], 0, 2)) }}
                                    </div>
                                    {{ $act[0] }}
                                </div>
                            </td>

                            <td class="py-3">{{ $act[1] }}</td>
                            <td class="py-3 text-muted">{{ $act[2] }}</td>
                            <td class="py-3 text-muted">{{ $act[3] }}</td>
                            <td class="py-3 text-muted">{{ $act[4] }}</td>
                            <td class="py-3 text-muted">{{ $act[5] }}</td>

                            <td class="py-3">
                                @if($act[6] === 'Di Terbitkan')
                                    <span class="badge bg-success-subtle text-success rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i>{{ $act[6] }}
                                    </span>
                                @elseif($act[6] === 'Di Proses')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill">
                                        <i class="bi bi-clock-fill me-1"></i>{{ $act[6] }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                        <i class="bi bi-pencil-fill me-1"></i>{{ $act[6] }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3">
                                <span class="badge text-bg-info"
                                    style="cursor: pointer;"
                                    onclick="onDetail({{ $act->id ?? 'cihuy' }})">
                                    <i class="bi bi-eye"></i>
                                </span>
                                <span class="badge text-bg-primary"
                                    style="cursor: pointer;"
                                    onclick="onDownload({{ $act->id ?? 'cihuy' }})">
                                    <i class="bi bi-download"></i>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah / Edit Siswa --}}
<div class="modal fade" id="modalSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">

            {{-- Header --}}
            <div class="modal-header" style="padding: 18px 22px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="modal-title fw-bold mb-0" id="modalSiswaTitle" style="font-size: 15px;">
                            Tambah Siswa
                        </h5>
                        <span class="badge-mode badge-add" id="modalSiswaBadge">
                            <i class="bi bi-plus-lg"></i> Baru
                        </span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 12px;">Lengkapi data siswa dengan benar</p>
                </div>
                <button type="button" class="close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            {{-- Body --}}
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
                        <input type="file" id="inputFoto" name="photo"
                               accept="image/*" style="display: none;"
                               onchange="previewFotoSiswa(this)">
                    </div>
                </div>

                {{-- NIS & Nama --}}
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">NIS <span class="text-danger">*</span></label>
                        <div class="fi-icon">
                            <span class="icon"><i class="bi bi-card-text"></i></span>
                            <input type="text" class="fi" id="inputNis" name="nis"
                                   placeholder="Contoh: 2024001" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="fi-icon">
                            <span class="icon"><i class="bi bi-person"></i></span>
                            <input type="text" class="fi" id="inputNama" name="nama"
                                   placeholder="Nama lengkap siswa" required>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="form-label-sm">Email <span class="text-danger">*</span></label>
                    <div class="fi-icon">
                        <span class="icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="fi" id="inputEmail" name="email"
                               placeholder="email@siswa.sch.id" required>
                    </div>
                </div>

                {{-- Telepon & Password --}}
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">No. Telepon</label>
                        <div class="fi-icon">
                            <span class="icon"><i class="bi bi-telephone"></i></span>
                            <input type="text" class="fi" id="inputPhone" name="phone"
                                   placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">
                            Password <span class="text-danger" id="pwRequired">*</span>
                        </label>
                        <div class="pw-wrap">
                            <input type="password" class="fi" id="inputPassword" name="password"
                                   placeholder="Min. 8 karakter">
                            <button class="pw-toggle" type="button" onclick="togglePwSiswa()">
                                <i class="bi bi-eye" id="pwEyeIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="form-label-sm">Alamat</label>
                    <div class="fi-icon">
                        <span class="icon" style="top: 10px; transform: none;">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <textarea class="fi" id="inputAddress" name="address"
                                  rows="2" placeholder="Jl. Contoh No. 1, Kota..."
                                  style="padding-left: 36px; resize: none;"></textarea>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="padding: 14px 22px; background: #fafafa; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-save" onclick="submitFormSiswa()">
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
        // pick filter =========================================================================
        function tog(id) {
            const btn  = document.querySelector('#' + id + ' .cs-btn');
            const menu = document.getElementById(id + '-m');
            const isOpen = menu.classList.contains('open');
            closeAllCS();
            if (!isOpen) {
                btn.classList.add('active');
                menu.classList.add('open');
            }
        }
        
        function pick(id, el) {
            document.querySelectorAll('#' + id + '-m .cs-item')
            .forEach(i => i.classList.remove('on'));
            el.classList.add('on');
            const val = el.getAttribute('data-value');
            document.getElementById(id + '-lbl').textContent = val;
            closeAllCS();
            setTimeout(() => {
                alert(val)
            }, 500);
        }
        
        function closeAllCS() {
            document.querySelectorAll('.cs-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.cs-menu').forEach(m => m.classList.remove('open'));
        }
        document.addEventListener('click', e => {
            if (!e.target.closest('.cs')) closeAllCS();
        });
        // END pick filter  =========================================================================

        // search  =========================================================================
        let searchTimer = null;
        function handleSearch(el) {
            document.getElementById('clearSearch').style.display = el.value ? 'flex' : 'none';
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                doSearch(el.value.trim());
            }, 500);
        }
        function doSearch(keyword) {
            alert(keyword)
        }
        function clearSearchInput() {
            const input = document.getElementById('searchSiswa');
            input.value = '';
            document.getElementById('clearSearch').style.display = 'none';
            clearTimeout(searchTimer);
            doSearch('');
        }
        // ENDsearch =========================================================================


        // action =========================================================================
        function onDetail(id) {
        }
        function onDownload(id) {
        }
        // END action =========================================================================
  
        </script>
@endpush
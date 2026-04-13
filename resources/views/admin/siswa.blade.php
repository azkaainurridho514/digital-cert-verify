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
        .pw-wrap        { position: relative; }
        .pw-wrap .fi    { padding-right: 36px; }
        .pw-toggle      { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; }
        .pw-toggle:hover{ color: #6b7280; }
        .btn-cancel { border: 1.5px solid #e2e8f0; background: #fff; border-radius: 9px; padding: 8px 18px; font-size: 13px; color: #374151; cursor: pointer; }
        .btn-save   { border: none; background: #3b82f6; border-radius: 9px; padding: 8px 22px; font-size: 13px; font-weight: 600; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .btn-save:hover { background: #2563eb; }
        .close-btn {width: 32px;height: 32px; border: none;border-radius: 50%;background: #f1f5f9;display: flex;align-items: center;justify-content: center;cursor: pointer;transition: all 0.2s ease;}
        .close-btn i {font-size: 18px;
            color: #64748b; }
        .close-btn:hover { background: #e2e8f0; }
        .close-btn:hover i {color: #0f172a;}
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
            <span class="text-muted" style="font-size: 12px;">
                Dihitung siswa aktif dan siswa yang sudah tidak aktif
            </span>
        </div>
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
        <div class="cs" id="filterTahun">
            <button class="cs-btn" type="button" onclick="tog('filterTahun')" style="min-width: 175px;">
                <svg class="ico" viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="#3b82f6">
                    <rect x="3" y="4" width="18" height="18" rx="3"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="lbl" id="filterTahun-lbl">2023 / 2024</span>
                <svg class="arr" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
            <div class="cs-menu" id="filterTahun-m">

                <div class="cs-item" data-value="Semua Tahun" onclick="pick('filterTahun', this)">
                    <span class="idot"></span>
                    Semua Tahun
                    <svg class="chk" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <div class="cs-sep"></div>

                <div class="cs-item" data-value="2022 / 2023" onclick="pick('filterTahun', this)">
                    <span class="idot"></span>
                    2022 / 2023
                    <svg class="chk" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <div class="cs-item on" data-value="2023 / 2024" onclick="pick('filterTahun', this)">
                    <span class="idot"></span>
                    2023 / 2024
                    <svg class="chk" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <div class="cs-item" data-value="2024 / 2025" onclick="pick('filterTahun', this)">
                    <span class="idot"></span>
                    2024 / 2025
                    <svg class="chk" viewBox="0 0 24 24" stroke-width="2.5" fill="none" stroke="currentColor">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

            </div>
        </div>
        <button class="btn-save" onclick="openModalTambah()">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    </div>
    <div class="p-0">
        @php
            $students = [
                ['Ahmad Fadillah', 'TOEFL Preparation', '09 Jan 2025', '081234567801', 'ahmad@mail.com', 'Kuningan', 'Aktif'],
                ['Siti Rahmawati', 'IELTS Preparation', '12 Jan 2025', '081234567802', 'siti@mail.com', 'Cirebon', 'Aktif'],
                ['Budi Santoso', 'Basic English', '15 Jan 2025', '081234567803', 'budi@mail.com', 'Bandung', 'Aktif'],
                ['Dewi Lestari', 'TOEIC Preparation', '05 Jan 2025', '081234567804', 'dewi@mail.com', 'Jakarta', 'Tidak Aktif'],
                ['Rizky Pratama', 'Business English', '20 Jan 2025', '081234567805', 'rizky@mail.com', 'Bekasi', 'Aktif'],
                ['Nabila Putri', 'Conversation Class', '22 Jan 2025', '081234567806', 'nabila@mail.com', 'Bogor', 'Aktif'],
                ['Andi Saputra', 'TOEFL Preparation', '03 Jan 2025', '081234567807', 'andi@mail.com', 'Depok', 'Tidak Aktif'],
                ['Fitri Handayani', 'IELTS Preparation', '28 Jan 2025', '081234567808', 'fitri@mail.com', 'Tasikmalaya', 'Aktif'],
                ['Rina Marlina', 'Basic English', '30 Jan 2025', '081234567809', 'rina@mail.com', 'Garut', 'Aktif'],
                ['Dedi Kurniawan', 'TOEIC Preparation', '02 Feb 2025', '081234567810', 'dedi@mail.com', 'Subang', 'Aktif'],
                ['Lina Suryani', 'Business English', '07 Jan 2025', '081234567811', 'lina@mail.com', 'Purwakarta', 'Tidak Aktif'],
                ['Fajar Nugroho', 'Conversation Class', '08 Feb 2025', '081234567812', 'fajar@mail.com', 'Majalengka', 'Aktif'],
                ['Maya Sari', 'TOEFL Preparation', '10 Feb 2025', '081234567813', 'maya@mail.com', 'Indramayu', 'Aktif'],
                ['Agus Setiawan', 'IELTS Preparation', '01 Jan 2025', '081234567814', 'agus@mail.com', 'Sumedang', 'Tidak Aktif'],
                ['Nur Aini', 'Basic English', '15 Feb 2025', '081234567815', 'nur@mail.com', 'Ciamis', 'Aktif'],
            ];
        @endphp
        <table class="table table-hover mb-0" style="font-size: 13px;">
            <thead>
                <tr class="table-light">
                    <th class="px-4 py-3 fw-semibold text-secondary">No</th>
                    <th class="py-3 fw-semibold text-secondary">Nama Siswa</th>
                    <th class="py-3 fw-semibold text-secondary">Terakhir Di Ikuti</th>
                    <th class="py-3 fw-semibold text-secondary">Tanggal Bergabung</th>
                    <th class="py-3 fw-semibold text-secondary">Nomor HP</th>
                    <th class="py-3 fw-semibold text-secondary">Email</th>
                    <th class="py-3 fw-semibold text-secondary">Alamat</th>
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
                    <td>
                        @if($act[6] === 'Aktif')
                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                <i class="bi bi-x-circle-fill me-1"></i>Tidak Aktif
                            </span>
                        @endif
                    </td>

                    <td class="py-3 text-muted">
                        <span class="badge text-bg-info">
                            <i class="bi bi-eye"></i>
                        </span>
                        <span class="badge text-bg-warning" style="cursor: pointer;"
                        onclick="openModalEdit('asas')">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
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
        // MODAL BOX EDIT & EDIT
        var modalSiswa = null;

        document.addEventListener('DOMContentLoaded', () => {
            modalSiswa = new bootstrap.Modal(document.getElementById('modalSiswa'));
        });

        function openModalTambah() {
            document.getElementById('modalSiswaTitle').textContent  = 'Tambah Siswa';
            document.getElementById('modalSiswaBadge').innerHTML    = '<i class="bi bi-plus-lg"></i> Baru';
            document.getElementById('modalSiswaBadge').className    = 'badge-mode badge-add';
            document.getElementById('btnSaveLabel').textContent     = 'Simpan Siswa';
            document.getElementById('pwRequired').style.display     = '';
            document.getElementById('inputPassword').placeholder   = 'Min. 8 karakter';

            // Reset semua field
            ['inputNis','inputNama','inputEmail','inputPhone','inputPassword','inputAddress']
                .forEach(id => document.getElementById(id).value = '');
            document.getElementById('photoPreview').innerHTML =
                '<i class="bi bi-person" style="font-size:22px;color:#6366f1;"></i>';

            modalSiswa.show();
        }

        function openModalEdit(id) {
            document.getElementById('modalSiswaTitle').textContent  = 'Edit Siswa';
            document.getElementById('modalSiswaBadge').innerHTML    = '<i class="bi bi-pencil"></i> Edit';
            document.getElementById('modalSiswaBadge').className    = 'badge-mode badge-edit';
            document.getElementById('btnSaveLabel').textContent     = 'Simpan Perubahan';
            document.getElementById('pwRequired').style.display     = 'none';
            document.getElementById('inputPassword').placeholder   = 'Kosongkan jika tidak diubah';

            // Fetch data siswa lalu isi field
            fetch(`/siswa/${id}/edit-data`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('inputNis').value      = data.nis;
                    document.getElementById('inputNama').value     = data.nama;
                    document.getElementById('inputEmail').value    = data.email;
                    document.getElementById('inputPhone').value    = data.phone;
                    document.getElementById('inputAddress').value  = data.address;
                });

            modalSiswa.show();
        }

        function submitFormSiswa() {
            // Kirim via AJAX / Livewire / form submit
            alert('Data berhasil disimpan!');
            modalSiswa.hide();
        }

        function togglePwSiswa() {
            const inp  = document.getElementById('inputPassword');
            const icon = document.getElementById('pwEyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function previewFotoSiswa(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('photoPreview').innerHTML =
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        // END MODAL BOX EDIT & EDIT


        // pick filter
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
        // END pick filter

        // search
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
        // ENDsearch


        // action
        function onDetail(id) {
        }
        function onPrint(id) {
        }
        // END action


        
        </script>
@endpush
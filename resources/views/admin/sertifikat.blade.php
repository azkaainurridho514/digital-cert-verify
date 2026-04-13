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
        .photo-upload-btn { display: inline-block; border: 1.5px solid #e2e8f0; background: #fff; border-radius: 8px; padding: 4px 12px; font-size: 12px; color: #374151; cursor: pointer; }
        .photo-upload-btn:hover { border-color: #93c5fd; }
        .form-label-sm { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; display: block; }
        .fi           { width: 100%; border: 1.5px solid #e2e8f0; border-radius: 9px; padding: 8px 12px; font-size: 13px; color: #374151; background: #fff; outline: none; transition: border-color .18s, box-shadow .18s; }
        .fi:hover     { border-color: #93c5fd; }
        .fi:focus     { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
        .fi-icon      { position: relative; }
        .fi-icon .fi  { padding-left: 36px; }
        .fi-icon .icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 1;
        }
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
        .search-item {
            padding: 10px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-item:hover {
            background: #f8fafc;
        }

        .si-main {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .si-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4338ca;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
        }

        .si-info {
            display: flex;
            flex-direction: column;
        }

        .si-name {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .si-meta {
            font-size: 11px;
            color: #6b7280;
        }

        

        .search-dropdown {
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 10px;
            margin-top: 5px;
            max-height: 180px;
            overflow-y: auto;
            z-index: 9999; /* INI KUNCI */
        }
    </style>
@endpush
@section('content')
<div class="page-header">
    <h4>Manajemen Sertifikat</h4>
    <p>Buat dan kelola sertifikat Sertifikat.</p>
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
                   placeholder="Cari nama sertifikat..."
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
            <i class="bi bi-plus-lg"></i> Buat Sertifikat
        </button>
    </div>
    <div class="p-0">
        @php
            $students = [
                ['Ahmad Fadillah', 'CERT-TOEFL-2025-0001', 'B', 'TOEFL Preparation', 'Intermediate', 'Lulus TOEFL dengan skor baik', '09 Jan 2025', 'Di Terbitkan'],
                ['Siti Rahmawati', 'CERT-IELTS-2025-0002', 'A', 'IELTS Preparation', 'Advanced', 'Lulus IELTS dengan skor tinggi', '12 Jan 2025', 'Di Terbitkan'],
                ['Budi Santoso', 'CERT-ENG-2025-0003', 'A', 'Basic English', 'Beginner', 'Menguasai dasar bahasa Inggris', '15 Jan 2025', 'Di Terbitkan'],
                ['Dewi Lestari', '-', '-', 'TOEIC Preparation', '-', 'Belum mengikuti ujian', '-', 'Draft'],
                ['Rizky Pratama', 'CERT-BUS-2025-0005', 'A', 'Business English', 'Advanced', 'Komunikasi bisnis sangat baik', '20 Jan 2025', 'Di Terbitkan'],
                ['Nabila Putri', 'CERT-CONV-2025-0006', 'B', 'Conversation Class', 'Intermediate', 'Kemampuan speaking meningkat', '22 Jan 2025', 'Di Proses'],
                ['Andi Saputra', '-', '-', 'TOEFL Preparation', '-', 'Masih dalam proses belajar', '-', 'Draft'],
            ];
        @endphp
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 13px;">
                <thead>
                    <tr class="table-light">
                        <th class="px-4 py-3 fw-semibold text-secondary">No</th>
                        <th class="py-3 fw-semibold text-secondary">Nama Sertifikat</th>
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
                            <td class="py-3 text-muted">{{ $act[6] }}</td>

                            <td class="py-3">
                                @if($act[7] === 'Di Terbitkan')
                                    <span class="badge bg-success-subtle text-success rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i>{{ $act[7] }}
                                    </span>
                                @elseif($act[7] === 'Di Proses')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill">
                                        <i class="bi bi-clock-fill me-1"></i>{{ $act[7] }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                        <i class="bi bi-pencil-fill me-1"></i>{{ $act[7] }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3">
                                <span class="badge text-bg-info"
                                    style="cursor: pointer;"
                                    onclick="onDetail({{ $act->id ?? 'cihuy' }})">
                                    <i class="bi bi-eye"></i>
                                </span>
                                <span class="badge text-bg-warning"
                                    style="cursor: pointer;"
                                    onclick="onPrint({{ $act->id ?? 'cihuy' }})">
                                    <i class="bi bi-printer"></i>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">

            {{-- Header --}}
            <div class="modal-header" style="padding: 18px 22px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="modal-title fw-bold mb-0" id="modalSertifikatTitle" style="font-size: 15px;">
                            Tambah Sertifikat
                        </h5>
                        <span class="badge-mode badge-add" id="modalSertifikatBadge">
                            <i class="bi bi-plus-lg"></i> Baru
                        </span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 12px;">Lengkapi data Sertifikat dengan benar</p>
                </div>
                <button type="button" class="close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding: 20px 22px; display: flex; flex-direction: column; gap: 14px;">

                <!-- Nomor Sertifikat -->
                <div>
                    <label class="form-label-sm">Nomor Sertifikat</label>
                    <div class="fi-icon">
                        <span class="icon"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" class="fi" id="inputCertificateNumber"
                            placeholder="Auto / Generate">
                    </div>
                </div>

                <!-- Nama Sertifikat (Searchable) -->
                <div>
                    <label class="form-label-sm">Nama Siswa <span class="text-danger">*</span></label>
                    <div class="fi-icon position-relative">
                        <span class="icon"><i class="bi bi-person"></i></span>
                        <input type="text" class="fi" id="inputStudent"
                            placeholder="Cari nama Siswa..."
                            onkeyup="searchStudent(this.value)">
                        
                        <!-- Dropdown Result -->
                        <div id="studentResult" class="search-dropdown"></div>
                    </div>

                    <!-- Preview Selected -->
                    <div id="studentPreview" class="mt-2 text-muted" style="font-size:12px;"></div>
                </div>

                <!-- Program (Searchable) -->
                <div>
                    <label class="form-label-sm">Program <span class="text-danger">*</span></label>
                    <div class="fi-icon position-relative">
                        <span class="icon"><i class="bi bi-book"></i></span>
                        <input type="text" class="fi" id="inputProgram"
                            placeholder="Cari program..."
                            onkeyup="searchProgram(this.value)">
                        <div id="programResult" class="search-dropdown"></div>
                    </div>
                     <div id="programPreview" class="mt-2 text-muted" style="font-size:12px;"></div>
                </div>

                <!-- Nilai & Level -->
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

                <!-- Deskripsi -->
                <div>
                    <label class="form-label-sm">Deskripsi</label>
                    <textarea class="fi" rows="3" placeholder="Deskripsi sertifikat..." id="inputDescription"></textarea>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="padding: 14px 22px; background: #fafafa; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-save" onclick="submitFormSertifikat()">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnSaveLabel">Simpan Sertifikat</span>
                </button>
            </div>

        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script>
        // MODAL BOX
        var modalSertifikat = null;

        document.addEventListener('DOMContentLoaded', () => {
            modalSertifikat = new bootstrap.Modal(document.getElementById('modalSertifikat'));
        });

        function openModalTambah() {
            [
                'inputCertificateNumber',
                'inputStudent',
                'inputProgram',
                'inputGrade',
                'inputLevel',
                'inputDescription'
            ].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            
            modalSertifikat.show();
            document.getElementById('inputLevel').selectedIndex = 0;
            document.getElementById('programPreview').innerHTML = "";
            document.getElementById('studentPreview').innerHTML = "";
        }

        function submitFormSertifikat() {
            alert('Data berhasil disimpan!');
            modalSertifikat.hide();
        }
        const students = [
            {id:1, name:'Ahmad Fadillah', email:'ahmad@mail.com'},
            {id:2, name:'Siti Rahmawati', email:'siti@mail.com'},
        ];
        const programs = [
            {id:1, name:'TOEFL Preparation', code:'TOEFL'},
            {id:2, name:'IELTS Preparation', code:'IELTS'},
            {id:3, name:'Basic English', code:'ENG'},
        ];
        function searchStudent(keyword) {
            const result = document.getElementById('studentResult');
            result.innerHTML = '';

            if (!keyword) return;

            const filtered = students.filter(s => 
                s.name.toLowerCase().includes(keyword.toLowerCase())
            );

            filtered.forEach(s => {
                result.innerHTML += `
                    <div class="search-item" onclick="selectStudent('${s.name}', '${s.email}')">
                        <div class="si-main">
                            <div class="si-avatar">${s.name.charAt(0)}</div>
                            <div class="si-info">
                                <div class="si-name">${s.name}</div>
                                <div class="si-meta">${s.email}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        function searchProgram(keyword) {
            const result = document.getElementById('programResult');
            result.innerHTML = '';

            if (!keyword) return;

            const filtered = programs.filter(s => 
                s.name.toLowerCase().includes(keyword.toLowerCase())
            );

            filtered.forEach(s => {
                result.innerHTML += `
                    <div class="search-item" onclick="selectProgram('${s.name}', '${s.code}')">
                        <div class="si-main">
                            <div class="si-avatar">${s.name.charAt(0)}</div>
                            <div class="si-info">
                                <div class="si-name">${s.name}</div>
                                <div class="si-meta">${s.code}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        function selectStudent(name, email) {
            document.getElementById('inputStudent').value = name;
            document.getElementById('studentResult').innerHTML = '';

            document.getElementById('studentPreview').innerHTML =
                `Dipilih: <b>${name}</b> (${email})`;
        }
        function selectProgram(name, code) {
            document.getElementById('inputProgram').value = name;
            document.getElementById('programResult').innerHTML = '';

            document.getElementById('programPreview').innerHTML =
                `Dipilih: <b>${name}</b> (${code})`;
        }

        // function generateCertificateNumber() {
        //     const year = new Date().getFullYear();
        //     const random = Math.floor(Math.random() * 9999).toString().padStart(4,'0');
        //     return `CERT-GEN-${year}-${random}`;
        // }

        // document.addEventListener('DOMContentLoaded', () => {
        //     document.getElementById('inputCertificateNumber').value = generateCertificateNumber();
        // });
        // END MODAL BOX

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
            const input = document.getElementById('searchSertifikat');
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
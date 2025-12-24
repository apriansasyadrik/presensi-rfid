<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem Presensi RFID</title>
</head>
<body>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
                <p class="text-gray-600 mt-1">Kelola jadwal pelajaran per kelas dan guru</p>
            </div>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-plus"></i>
                Tambah Jadwal
            </button>
        </div>

        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $this->session->flashdata('success') ?>
        </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kelas</label>
                    <select id="filterKelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Kelas</option>
                        <?php foreach($kelas as $k): ?>
                        <option value="<?= $k->id ?>">Kelas <?= $k->tingkat ?> <?= $k->jurusan ?> - <?= $k->nama_kelas ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Hari</label>
                    <select id="filterHari" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" id="searchInput" placeholder="Cari mata pelajaran atau guru..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="jadwalTableBody" class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; foreach($jadwal as $j): ?>
                    <tr data-kelas="<?= $j->id_kelas ?>" data-hari="<?= $j->hari ?>" data-search="<?= strtolower($j->nama_mapel . ' ' . $j->nama_guru) ?>">
                        <td class="px-6 py-4"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?= $j->hari ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm"><?= substr($j->jam_mulai, 0, 5) ?> - <?= substr($j->jam_selesai, 0, 5) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium"><?= $j->nama_kelas ?></div>
                            <div class="text-xs text-gray-500">Tingkat <?= $j->tingkat ?> <?= $j->jurusan ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium"><?= $j->nama_mapel ?></div>
                            <div class="text-xs text-gray-500"><?= $j->kode_mapel ?></div>
                        </td>
                        <td class="px-6 py-4"><?= $j->nama_guru ?></td>
                        <td class="px-6 py-4">
                            <button onclick="editJadwal(<?= $j->id ?>)" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteJadwal(<?= $j->id ?>)" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="jadwalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Tambah Jadwal Pelajaran</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="jadwalForm">
                <input type="hidden" id="jadwal_id" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas *</label>
                        <select name="id_kelas" id="id_kelas" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kelas</option>
                            <?php foreach($kelas as $k): ?>
                            <option value="<?= $k->id ?>">Kelas <?= $k->tingkat ?> <?= $k->jurusan ?> - <?= $k->nama_kelas ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran *</label>
                        <select name="id_mata_pelajaran" id="id_mata_pelajaran" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Mata Pelajaran</option>
                            <?php foreach($mapel as $m): ?>
                            <option value="<?= $m->id ?>"><?= $m->nama_mapel ?> (<?= $m->kode_mapel ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guru Pengajar *</label>
                    <select name="id_guru" id="id_guru" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Guru</option>
                        <?php foreach($guru as $g): ?>
                        <option value="<?= $g->id ?>"><?= $g->nama ?> - <?= $g->nip ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hari *</label>
                        <select name="hari" id="hari" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai *</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai *</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal() {
            document.getElementById('jadwalModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah Jadwal Pelajaran';
            document.getElementById('jadwalForm').reset();
            document.getElementById('jadwal_id').value = '';
        }

        function closeModal() {
            document.getElementById('jadwalModal').classList.add('hidden');
        }

        // Edit jadwal
        function editJadwal(id) {
            fetch(`<?= base_url('admin/jadwal_pelajaran/get_by_id/') ?>${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const data = result.data;
                        document.getElementById('jadwal_id').value = data.id;
                        document.getElementById('id_kelas').value = data.id_kelas;
                        document.getElementById('id_mata_pelajaran').value = data.id_mata_pelajaran;
                        document.getElementById('id_guru').value = data.id_guru;
                        document.getElementById('hari').value = data.hari;
                        document.getElementById('jam_mulai').value = data.jam_mulai;
                        document.getElementById('jam_selesai').value = data.jam_selesai;
                        document.getElementById('modalTitle').textContent = 'Edit Jadwal Pelajaran';
                        document.getElementById('jadwalModal').classList.remove('hidden');
                    }
                });
        }

        // Delete jadwal
        function deleteJadwal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                fetch(`<?= base_url('admin/jadwal_pelajaran/delete/') ?>${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        location.reload();
                    } else {
                        alert(result.message);
                    }
                });
            }
        }

        // Form submit
        document.getElementById('jadwalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('<?= base_url('admin/jadwal_pelajaran/save') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    location.reload();
                } else {
                    alert(result.message);
                }
            });
        });

        // Filter functions
        document.getElementById('filterKelas').addEventListener('change', filterTable);
        document.getElementById('filterHari').addEventListener('change', filterTable);
        document.getElementById('searchInput').addEventListener('keyup', filterTable);

        function filterTable() {
            const filterKelas = document.getElementById('filterKelas').value;
            const filterHari = document.getElementById('filterHari').value;
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#jadwalTableBody tr');
            
            rows.forEach(row => {
                const kelas = row.getAttribute('data-kelas');
                const hari = row.getAttribute('data-hari');
                const search = row.getAttribute('data-search');
                
                let show = true;
                
                if (filterKelas && kelas !== filterKelas) show = false;
                if (filterHari && hari !== filterHari) show = false;
                if (searchText && !search.includes(searchText)) show = false;
                
                row.style.display = show ? '' : 'none';
            });
        }
    </script>
</body>
</html>

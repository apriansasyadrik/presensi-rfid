<!-- Flash Messages -->
<?php if($this->session->flashdata('success')): ?>
<div id="flashSuccess" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p><?= $this->session->flashdata('success') ?></p>
    </div>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div id="flashError" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p><?= $this->session->flashdata('error') ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Data Siswa -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i>
                    Data Siswa
                </h3>
                <p class="text-blue-100 text-sm mt-1">Kelola data siswa sekolah</p>
            </div>
            <div class="flex space-x-2">
                <button 
                    onclick="alert('Fitur import memerlukan PhpSpreadsheet')" 
                    class="px-4 py-2 bg-white bg-opacity-20 text-white font-semibold rounded-lg hover:bg-opacity-30 transition duration-200"
                >
                    <i class="fas fa-file-import mr-2"></i> Import Excel
                </button>
                <button 
                    onclick="alert('Fitur export memerlukan PhpSpreadsheet')" 
                    class="px-4 py-2 bg-white bg-opacity-20 text-white font-semibold rounded-lg hover:bg-opacity-30 transition duration-200"
                >
                    <i class="fas fa-file-export mr-2"></i> Export Excel
                </button>
                <button 
                    onclick="openAddModal()" 
                    class="px-4 py-2 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition duration-200 transform hover:scale-105"
                >
                    <i class="fas fa-plus mr-2"></i> Tambah Siswa
                </button>
            </div>
        </div>
    </div>

    <div class="p-6">
        <?php if(!empty($siswa)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RFID</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; foreach($siswa as $s): ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $s->nis ?></div>
                            <?php if($s->nisn): ?>
                            <div class="text-xs text-gray-500">NISN: <?= $s->nisn ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= $s->nama_lengkap ?></div>
                            <?php if($s->no_telepon_ortu): ?>
                            <div class="text-xs text-gray-500"><i class="fas fa-phone mr-1"></i><?= $s->no_telepon_ortu ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $s->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                                <?= $s->jenis_kelamin ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $s->nama_kelas ? $s->nama_kelas : '-' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($s->rfid_uid): ?>
                            <span class="px-2 py-1 text-xs font-mono bg-green-100 text-green-800 rounded"><?= substr($s->rfid_uid, 0, 10) ?>...</span>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">Belum terdaftar</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button 
                                onclick="editItem(<?= $s->id ?>)" 
                                class="text-blue-600 hover:text-blue-900 mr-3"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                onclick="deleteItem(<?= $s->id ?>, '<?= addslashes($s->nama_lengkap) ?>')" 
                                class="text-red-600 hover:text-red-900"
                                title="Hapus"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada data siswa</p>
            <button 
                onclick="openAddModal()" 
                class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Siswa
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Add/Edit -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 my-8 transform transition-all">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4 rounded-t-lg">
            <h3 id="modalTitle" class="text-xl font-bold">Tambah Siswa</h3>
        </div>
        <form id="siswaForm" class="p-6">
            <input type="hidden" id="siswa_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIS -->
                <div>
                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                        NIS <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nis" 
                        name="nis" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nomor Induk Siswa"
                    >
                </div>

                <!-- NISN -->
                <div>
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                        NISN
                    </label>
                    <input 
                        type="text" 
                        id="nisn" 
                        name="nisn" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nomor Induk Siswa Nasional"
                    >
                </div>

                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama_lengkap" 
                        name="nama_lengkap" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama lengkap siswa"
                    >
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="jenis_kelamin" 
                        name="jenis_kelamin" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <!-- Kelas -->
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas
                    </label>
                    <select 
                        id="kelas_id" 
                        name="kelas_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Pilih Kelas</option>
                        <?php foreach($kelas as $k): ?>
                        <option value="<?= $k->id ?>"><?= $k->nama_kelas ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Lahir
                    </label>
                    <input 
                        type="text" 
                        id="tempat_lahir" 
                        name="tempat_lahir" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tempat lahir"
                    >
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Lahir
                    </label>
                    <input 
                        type="date" 
                        id="tanggal_lahir" 
                        name="tanggal_lahir" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- RFID UID -->
                <div class="md:col-span-2">
                    <label for="rfid_uid" class="block text-sm font-medium text-gray-700 mb-2">
                        RFID UID
                    </label>
                    <input 
                        type="text" 
                        id="rfid_uid" 
                        name="rfid_uid" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="UID kartu RFID"
                    >
                    <p class="text-xs text-gray-500 mt-1">UID unik dari kartu RFID siswa</p>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Alamat lengkap"
                    ></textarea>
                </div>

                <!-- Nama Orang Tua -->
                <div>
                    <label for="nama_ortu" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Orang Tua/Wali
                    </label>
                    <input 
                        type="text" 
                        id="nama_ortu" 
                        name="nama_ortu" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama orang tua/wali"
                    >
                </div>

                <!-- No Telepon Orang Tua -->
                <div>
                    <label for="no_telepon_ortu" class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon Orang Tua
                    </label>
                    <input 
                        type="text" 
                        id="no_telepon_ortu" 
                        name="no_telepon_ortu" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="08xxxxxxxxxx"
                    >
                    <p class="text-xs text-gray-500 mt-1">Untuk notifikasi WhatsApp</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <button 
                    type="button" 
                    onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200"
                >
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Siswa';
    document.getElementById('siswaForm').reset();
    document.getElementById('siswa_id').value = '';
    document.getElementById('modal').classList.remove('hidden');
}

function editItem(id) {
    fetch('<?= base_url("admin/siswa/get/") ?>' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalTitle').textContent = 'Edit Siswa';
            document.getElementById('siswa_id').value = data.data.id;
            document.getElementById('nis').value = data.data.nis;
            document.getElementById('nisn').value = data.data.nisn || '';
            document.getElementById('nama_lengkap').value = data.data.nama_lengkap;
            document.getElementById('jenis_kelamin').value = data.data.jenis_kelamin;
            document.getElementById('kelas_id').value = data.data.kelas_id || '';
            document.getElementById('tempat_lahir').value = data.data.tempat_lahir || '';
            document.getElementById('tanggal_lahir').value = data.data.tanggal_lahir || '';
            document.getElementById('rfid_uid').value = data.data.rfid_uid || '';
            document.getElementById('alamat').value = data.data.alamat || '';
            document.getElementById('nama_ortu').value = data.data.nama_ortu || '';
            document.getElementById('no_telepon_ortu').value = data.data.no_telepon_ortu || '';
            document.getElementById('modal').classList.remove('hidden');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
    });
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

document.getElementById('siswaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('siswa_id').value;
    const url = id ? '<?= base_url("admin/siswa/edit/") ?>' + id : '<?= base_url("admin/siswa/add") ?>';
    
    const formData = new FormData(this);
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});

function deleteItem(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus siswa ' + nama + '?')) {
        fetch('<?= base_url("admin/siswa/delete/") ?>' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
    }
}

// Close modal when clicking outside
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Auto-hide flash messages
setTimeout(function() {
    const flashSuccess = document.getElementById('flashSuccess');
    const flashError = document.getElementById('flashError');
    if (flashSuccess) {
        flashSuccess.style.transition = 'opacity 0.5s';
        flashSuccess.style.opacity = '0';
        setTimeout(() => flashSuccess.remove(), 500);
    }
    if (flashError) {
        flashError.style.transition = 'opacity 0.5s';
        flashError.style.opacity = '0';
        setTimeout(() => flashError.remove(), 500);
    }
}, 5000);
</script>

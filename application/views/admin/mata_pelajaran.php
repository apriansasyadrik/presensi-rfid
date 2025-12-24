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

<!-- Data Mata Pelajaran -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-book mr-2"></i>
                    Mata Pelajaran
                </h3>
                <p class="text-orange-100 text-sm mt-1">Kelola data mata pelajaran sekolah</p>
            </div>
            <button 
                onclick="openAddModal()" 
                class="px-4 py-2 bg-white text-orange-600 font-semibold rounded-lg hover:bg-orange-50 transition duration-200 transform hover:scale-105"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Mata Pelajaran
            </button>
        </div>
    </div>

    <div class="p-6">
        <?php if(!empty($mata_pelajaran)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru Pengampu</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">KKM</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; foreach($mata_pelajaran as $mp): ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-mono bg-gray-100 text-gray-800 rounded"><?= $mp->kode_mapel ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= $mp->nama_mapel ?></div>
                            <?php if($mp->deskripsi): ?>
                            <div class="text-xs text-gray-500 mt-1"><?= substr($mp->deskripsi, 0, 50) ?><?= strlen($mp->deskripsi) > 50 ? '...' : '' ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $mp->nama_guru ? $mp->nama_guru : '<span class="text-gray-400">Belum ditentukan</span>' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if($mp->kkm): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= $mp->kkm ?>
                            </span>
                            <?php else: ?>
                            <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button 
                                onclick="editItem(<?= $mp->id ?>)" 
                                class="text-blue-600 hover:text-blue-900 mr-3"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                onclick="deleteItem(<?= $mp->id ?>, '<?= addslashes($mp->nama_mapel) ?>')" 
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
            <p class="text-gray-500 text-lg">Belum ada data mata pelajaran</p>
            <button 
                onclick="openAddModal()" 
                class="mt-4 px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Mata Pelajaran
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Add/Edit -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-4 my-8 transform transition-all">
        <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white px-6 py-4 rounded-t-lg">
            <h3 id="modalTitle" class="text-xl font-bold">Tambah Mata Pelajaran</h3>
        </div>
        <form id="mapelForm" class="p-6">
            <input type="hidden" id="mapel_id">
            
            <!-- Kode Mata Pelajaran -->
            <div class="mb-4">
                <label for="kode_mapel" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="kode_mapel" 
                    name="kode_mapel" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Contoh: MTK, IPA, ENG"
                >
                <p class="text-xs text-gray-500 mt-1">Kode unik untuk mata pelajaran</p>
            </div>

            <!-- Nama Mata Pelajaran -->
            <div class="mb-4">
                <label for="nama_mapel" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_mapel" 
                    name="nama_mapel" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Contoh: Matematika"
                >
            </div>

            <!-- Guru Pengampu -->
            <div class="mb-4">
                <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Guru Pengampu
                </label>
                <select 
                    id="guru_id" 
                    name="guru_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                    <option value="">Pilih Guru (Opsional)</option>
                    <?php foreach($guru as $g): ?>
                    <option value="<?= $g->id ?>"><?= $g->nama_lengkap ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Guru yang mengajar mata pelajaran ini</p>
            </div>

            <!-- KKM -->
            <div class="mb-4">
                <label for="kkm" class="block text-sm font-medium text-gray-700 mb-2">
                    KKM (Kriteria Ketuntasan Minimal)
                </label>
                <input 
                    type="number" 
                    id="kkm" 
                    name="kkm" 
                    min="0"
                    max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Contoh: 75"
                >
                <p class="text-xs text-gray-500 mt-1">Nilai minimal kelulusan (0-100)</p>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    placeholder="Deskripsi singkat tentang mata pelajaran (opsional)"
                ></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <button 
                    type="button" 
                    onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200"
                >
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200"
                >
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('mapelForm').reset();
    document.getElementById('mapel_id').value = '';
    document.getElementById('modal').classList.remove('hidden');
}

function editItem(id) {
    fetch('<?= base_url("admin/mata-pelajaran/get/") ?>' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran';
            document.getElementById('mapel_id').value = data.data.id;
            document.getElementById('kode_mapel').value = data.data.kode_mapel;
            document.getElementById('nama_mapel').value = data.data.nama_mapel;
            document.getElementById('guru_id').value = data.data.guru_id || '';
            document.getElementById('kkm').value = data.data.kkm || '';
            document.getElementById('deskripsi').value = data.data.deskripsi || '';
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

document.getElementById('mapelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('mapel_id').value;
    const url = id ? '<?= base_url("admin/mata-pelajaran/edit/") ?>' + id : '<?= base_url("admin/mata-pelajaran/add") ?>';
    
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
    if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ' + nama + '?')) {
        fetch('<?= base_url("admin/mata-pelajaran/delete/") ?>' + id, {
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

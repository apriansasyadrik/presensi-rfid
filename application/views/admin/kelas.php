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

<!-- Data Kelas -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-door-open mr-2"></i>
                    Data Kelas
                </h3>
                <p class="text-purple-100 text-sm mt-1">Kelola data kelas sekolah</p>
            </div>
            <button 
                onclick="openAddModal()" 
                class="px-4 py-2 bg-white text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition duration-200 transform hover:scale-105"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </button>
        </div>
    </div>

    <div class="p-6">
        <?php if(!empty($kelas)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; foreach($kelas as $k): ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $k->nama_kelas ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Kelas <?= $k->tingkat ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $k->jurusan ?: '-' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $k->tahun ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button 
                                onclick="editItem(<?= $k->id ?>)" 
                                class="text-blue-600 hover:text-blue-900 mr-3"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                onclick="deleteItem(<?= $k->id ?>, '<?= $k->nama_kelas ?>')" 
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
            <p class="text-gray-500 text-lg">Belum ada data kelas</p>
            <button 
                onclick="openAddModal()" 
                class="mt-4 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Add/Edit -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md mx-4 transform transition-all">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-4 rounded-t-lg">
            <h3 id="modalTitle" class="text-xl font-bold">Tambah Kelas</h3>
        </div>
        <form id="kelasForm" class="p-6">
            <input type="hidden" id="kelas_id">
            
            <!-- Nama Kelas -->
            <div class="mb-4">
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kelas <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_kelas" 
                    name="nama_kelas" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Contoh: X IPA 1"
                >
            </div>

            <!-- Tingkat -->
            <div class="mb-4">
                <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2">
                    Tingkat <span class="text-red-500">*</span>
                </label>
                <select 
                    id="tingkat" 
                    name="tingkat" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                    <option value="">Pilih Tingkat</option>
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>

            <!-- Jurusan -->
            <div class="mb-4">
                <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">
                    Jurusan
                </label>
                <input 
                    type="text" 
                    id="jurusan" 
                    name="jurusan" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Contoh: IPA, IPS, Bahasa (opsional)"
                >
            </div>

            <!-- Tahun Ajaran -->
            <div class="mb-6">
                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <select 
                    id="tahun_ajaran_id" 
                    name="tahun_ajaran_id" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php foreach($tahun_ajaran as $ta): ?>
                    <option value="<?= $ta->id ?>"><?= $ta->tahun ?></option>
                    <?php endforeach; ?>
                </select>
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
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200"
                >
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kelas';
    document.getElementById('kelasForm').reset();
    document.getElementById('kelas_id').value = '';
    document.getElementById('modal').classList.remove('hidden');
}

function editItem(id) {
    fetch('<?= base_url("admin/kelas/get/") ?>' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalTitle').textContent = 'Edit Kelas';
            document.getElementById('kelas_id').value = data.data.id;
            document.getElementById('nama_kelas').value = data.data.nama_kelas;
            document.getElementById('tingkat').value = data.data.tingkat;
            document.getElementById('jurusan').value = data.data.jurusan || '';
            document.getElementById('tahun_ajaran_id').value = data.data.tahun_ajaran_id;
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

document.getElementById('kelasForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('kelas_id').value;
    const url = id ? '<?= base_url("admin/kelas/edit/") ?>' + id : '<?= base_url("admin/kelas/add") ?>';
    
    const formData = new FormData();
    formData.append('nama_kelas', document.getElementById('nama_kelas').value);
    formData.append('tingkat', document.getElementById('tingkat').value);
    formData.append('jurusan', document.getElementById('jurusan').value);
    formData.append('tahun_ajaran_id', document.getElementById('tahun_ajaran_id').value);
    
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
    if (confirm('Apakah Anda yakin ingin menghapus kelas ' + nama + '?')) {
        fetch('<?= base_url("admin/kelas/delete/") ?>' + id, {
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

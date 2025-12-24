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

<!-- Data Semester -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-calendar-week mr-2"></i>
                    Data Semester
                </h3>
                <p class="text-green-100 text-sm mt-1">Kelola data semester sekolah</p>
            </div>
            <button 
                onclick="openAddModal()" 
                class="px-4 py-2 bg-white text-green-600 font-semibold rounded-lg hover:bg-green-50 transition duration-200 transform hover:scale-105"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Semester
            </button>
        </div>
    </div>

    <div class="p-6">
        <?php if(!empty($semester)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; foreach($semester as $sem): ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $sem->tahun ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $sem->semester == 'ganjil' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                <?= ucfirst($sem->semester) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if($sem->is_active): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button 
                                onclick="editItem(<?= $sem->id ?>)" 
                                class="text-blue-600 hover:text-blue-900 mr-3"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                onclick="deleteItem(<?= $sem->id ?>, '<?= $sem->tahun ?> - <?= ucfirst($sem->semester) ?>')" 
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
            <p class="text-gray-500 text-lg">Belum ada data semester</p>
            <button 
                onclick="openAddModal()" 
                class="mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200"
            >
                <i class="fas fa-plus mr-2"></i> Tambah Semester
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Add/Edit -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md mx-4 transform transition-all">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-lg">
            <h3 id="modalTitle" class="text-xl font-bold">Tambah Semester</h3>
        </div>
        <form id="semesterForm" class="p-6">
            <input type="hidden" id="semester_id">
            
            <!-- Tahun Ajaran -->
            <div class="mb-4">
                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <select 
                    id="tahun_ajaran_id" 
                    name="tahun_ajaran_id" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php foreach($tahun_ajaran as $ta): ?>
                    <option value="<?= $ta->id ?>"><?= $ta->tahun ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Semester -->
            <div class="mb-4">
                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select 
                    id="semester" 
                    name="semester" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                    <option value="">Pilih Semester</option>
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <!-- Status Aktif -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        id="is_active" 
                        name="is_active"
                        class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500"
                    >
                    <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan sebagai semester saat ini</span>
                </label>
                <p class="text-xs text-gray-500 mt-1 ml-6">Hanya satu semester yang bisa aktif</p>
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
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200"
                >
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Semester';
    document.getElementById('semesterForm').reset();
    document.getElementById('semester_id').value = '';
    document.getElementById('modal').classList.remove('hidden');
}

function editItem(id) {
    fetch('<?= base_url("admin/semester/get/") ?>' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalTitle').textContent = 'Edit Semester';
            document.getElementById('semester_id').value = data.data.id;
            document.getElementById('tahun_ajaran_id').value = data.data.tahun_ajaran_id;
            document.getElementById('semester').value = data.data.semester;
            document.getElementById('is_active').checked = data.data.is_active == 1;
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

document.getElementById('semesterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('semester_id').value;
    const url = id ? '<?= base_url("admin/semester/edit/") ?>' + id : '<?= base_url("admin/semester/add") ?>';
    
    const formData = new FormData();
    formData.append('tahun_ajaran_id', document.getElementById('tahun_ajaran_id').value);
    formData.append('semester', document.getElementById('semester').value);
    formData.append('is_active', document.getElementById('is_active').checked ? '1' : '0');
    
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

function deleteItem(id, name) {
    if (confirm('Apakah Anda yakin ingin menghapus semester ' + name + '?')) {
        fetch('<?= base_url("admin/semester/delete/") ?>' + id, {
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

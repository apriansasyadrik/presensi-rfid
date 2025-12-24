<!-- Flash Messages -->
<?php if($this->session->flashdata('success')): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p><?= $this->session->flashdata('success') ?></p>
    </div>
</div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p><?= $this->session->flashdata('error') ?></p>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Jam Kerja -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-clock text-blue-600 mr-2"></i>
            Pengaturan Jam Kerja
        </h3>

        <?= form_open('admin/pengaturan-hari-kerja', ['class' => 'space-y-4']); ?>
            <input type="hidden" name="action" value="update_jam">
            
            <div>
                <label for="jam_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                    Jam Masuk <span class="text-red-500">*</span>
                </label>
                <input 
                    type="time" 
                    id="jam_masuk" 
                    name="jam_masuk" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    value="<?= isset($jam_kerja->jam_masuk) ? $jam_kerja->jam_masuk : '07:00' ?>"
                >
                <?= form_error('jam_masuk', '<p class="text-red-500 text-sm mt-1">', '</p>'); ?>
            </div>

            <div>
                <label for="jam_pulang" class="block text-sm font-medium text-gray-700 mb-2">
                    Jam Pulang <span class="text-red-500">*</span>
                </label>
                <input 
                    type="time" 
                    id="jam_pulang" 
                    name="jam_pulang" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    value="<?= isset($jam_kerja->jam_pulang) ? $jam_kerja->jam_pulang : '15:00' ?>"
                >
                <?= form_error('jam_pulang', '<p class="text-red-500 text-sm mt-1">', '</p>'); ?>
            </div>

            <div>
                <label for="toleransi_terlambat" class="block text-sm font-medium text-gray-700 mb-2">
                    Toleransi Keterlambatan (menit) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="toleransi_terlambat" 
                    name="toleransi_terlambat" 
                    min="0"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Contoh: 15"
                    value="<?= isset($jam_kerja->toleransi_terlambat) ? $jam_kerja->toleransi_terlambat : '15' ?>"
                >
                <?= form_error('toleransi_terlambat', '<p class="text-red-500 text-sm mt-1">', '</p>'); ?>
                <p class="text-xs text-gray-500 mt-1">Siswa/guru dianggap terlambat jika datang lebih dari toleransi ini</p>
            </div>

            <button 
                type="submit" 
                class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200"
            >
                <i class="fas fa-save mr-2"></i> Simpan Jam Kerja
            </button>
        <?= form_close(); ?>
    </div>

    <!-- Hari Kerja -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
            Pengaturan Hari Kerja
        </h3>

        <div class="space-y-3">
            <?php if(!empty($hari_kerja)): ?>
                <?php foreach($hari_kerja as $hk): ?>
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar-day text-gray-500"></i>
                        <span class="font-medium text-gray-800"><?= $hk->hari ?></span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            class="sr-only peer toggle-hari" 
                            data-hari="<?= $hk->hari ?>"
                            <?= $hk->is_aktif ? 'checked' : '' ?>
                        >
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        <span class="ml-3 text-sm font-medium <?= $hk->is_aktif ? 'text-green-600' : 'text-gray-500' ?>">
                            <?= $hk->is_aktif ? 'Aktif' : 'Tidak Aktif' ?>
                        </span>
                    </label>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500 py-4">Data hari kerja tidak tersedia</p>
            <?php endif; ?>
        </div>

        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-1"></i>
                Aktifkan hari-hari kerja sesuai dengan jadwal sekolah Anda
            </p>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Informasi Penting</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Jam masuk dan pulang akan digunakan untuk menentukan keterlambatan</li>
                    <li>Toleransi keterlambatan berlaku untuk siswa dan guru</li>
                    <li>Hari kerja yang tidak diaktifkan tidak akan dicatat dalam absensi</li>
                    <li>Pastikan pengaturan ini sesuai dengan kebijakan sekolah</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Handle toggle hari kerja
document.querySelectorAll('.toggle-hari').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const hari = this.getAttribute('data-hari');
        const isAktif = this.checked ? 1 : 0;

        fetch('<?= base_url("admin/pengaturan-hari-kerja") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=update_hari&hari=' + encodeURIComponent(hari) + '&is_aktif=' + isAktif
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error: ' + data.message);
                this.checked = !this.checked; // Revert toggle
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status hari kerja');
            this.checked = !this.checked; // Revert toggle
        });
    });
});

// Auto-hide flash messages after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
}, 5000);
</script>

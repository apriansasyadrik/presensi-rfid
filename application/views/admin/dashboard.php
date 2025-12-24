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

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Siswa -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Siswa</p>
                <h3 class="text-4xl font-bold mt-2"><?= number_format($total_siswa) ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-graduate text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-blue-100">
            <i class="fas fa-users mr-2"></i>
            <span class="text-sm">Siswa Aktif</span>
        </div>
    </div>

    <!-- Total Guru -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Guru</p>
                <h3 class="text-4xl font-bold mt-2"><?= number_format($total_guru) ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-green-100">
            <i class="fas fa-user-tie mr-2"></i>
            <span class="text-sm">Guru Aktif</span>
        </div>
    </div>

    <!-- Absen Siswa Hari Ini -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Absen Siswa</p>
                <h3 class="text-4xl font-bold mt-2"><?= number_format($absen_siswa_hari_ini) ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-clipboard-check text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-purple-100">
            <i class="fas fa-calendar-day mr-2"></i>
            <span class="text-sm">Hari Ini</span>
        </div>
    </div>

    <!-- Absen Guru Hari Ini -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Absen Guru</p>
                <h3 class="text-4xl font-bold mt-2"><?= number_format($absen_guru_hari_ini) ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-check text-3xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-orange-100">
            <i class="fas fa-calendar-day mr-2"></i>
            <span class="text-sm">Hari Ini</span>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Siswa Attendance -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
            <h3 class="text-lg font-semibold flex items-center">
                <i class="fas fa-clock mr-2"></i>
                Absensi Siswa Terbaru
            </h3>
        </div>
        <div class="p-6">
            <?php if(!empty($recent_siswa)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">NIS</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Nama</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Jam Masuk</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_siswa as $siswa): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm"><?= $siswa->nis ?></td>
                            <td class="py-3 px-4 text-sm font-medium"><?= $siswa->nama_lengkap ?></td>
                            <td class="py-3 px-4 text-sm"><?= $siswa->nama_kelas ?></td>
                            <td class="py-3 px-4 text-sm"><?= date('H:i', strtotime($siswa->jam_masuk)) ?></td>
                            <td class="py-3 px-4 text-sm">
                                <?php if($siswa->status_masuk == 'tepat_waktu'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Tepat Waktu</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Terlambat (<?= $siswa->keterlambatan_menit ?> menit)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>Belum ada data absensi siswa hari ini</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Guru Attendance -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4">
            <h3 class="text-lg font-semibold flex items-center">
                <i class="fas fa-clock mr-2"></i>
                Absensi Guru Terbaru
            </h3>
        </div>
        <div class="p-6">
            <?php if(!empty($recent_guru)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">NIP</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Nama</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Jam Masuk</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_guru as $guru): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm"><?= $guru->nip ?></td>
                            <td class="py-3 px-4 text-sm font-medium"><?= $guru->nama_lengkap ?></td>
                            <td class="py-3 px-4 text-sm"><?= date('H:i', strtotime($guru->jam_masuk)) ?></td>
                            <td class="py-3 px-4 text-sm">
                                <?php if($guru->status_masuk == 'tepat_waktu'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Tepat Waktu</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Terlambat (<?= $guru->keterlambatan_menit ?> menit)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>Belum ada data absensi guru hari ini</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
        Quick Actions
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?= base_url('admin/siswa') ?>" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
            <i class="fas fa-user-graduate text-3xl text-blue-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Kelola Siswa</span>
        </a>
        <a href="<?= base_url('admin/guru') ?>" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
            <i class="fas fa-chalkboard-teacher text-3xl text-green-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Kelola Guru</span>
        </a>
        <a href="<?= base_url('admin/laporan-siswa') ?>" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
            <i class="fas fa-file-alt text-3xl text-purple-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Laporan</span>
        </a>
        <a href="<?= base_url('admin/wa-notifikasi') ?>" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
            <i class="fab fa-whatsapp text-3xl text-orange-600 mb-2"></i>
            <span class="text-sm font-medium text-gray-700">WhatsApp</span>
        </a>
    </div>
</div>

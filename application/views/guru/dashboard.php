<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Guru</h2>
        <p class="text-gray-600">Selamat datang di panel guru</p>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Mengajar</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $stats['total_mengajar'] ?> Kelas</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <i class="fas fa-book text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jurnal Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $stats['jurnal_bulan_ini'] ?> Jurnal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Jadwal Hari Ini</h3>
        </div>
        <div class="p-6">
            <?php if(!empty($jadwal_hari_ini)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($jadwal_hari_ini as $jadwal): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                            <?= $jadwal->jam_mulai ?> - <?= $jadwal->jam_selesai ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $jadwal->nama_kelas ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $jadwal->nama_mapel ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="<?= base_url('guru/jurnal') ?>" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-plus-circle"></i> Isi Jurnal
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Tidak ada jadwal hari ini</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Jurnal Hari Ini -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Jurnal Hari Ini</h3>
        </div>
        <div class="p-6">
            <?php if(!empty($jurnal_hari_ini)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($jurnal_hari_ini as $jurnal): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $jurnal->nama_kelas ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $jurnal->nama_mapel ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= substr($jurnal->materi, 0, 50) ?>...
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('H:i', strtotime($jurnal->created_at)) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada jurnal hari ini</p>
            <?php endif; ?>
        </div>
    </div>
</div>

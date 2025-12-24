<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Monitoring Siswa</h2>
        <p class="text-gray-600">Monitor siswa dengan pelanggaran kehadiran</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="flex items-end space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <input type="month" name="month" value="<?= $month ?>" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Siswa Alpha 3x -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Siswa Alpha 3x atau Lebih</h3>
        </div>
        <div class="p-6">
            <?php if(!empty($siswa_alpha)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($siswa_alpha as $siswa): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $siswa->nis ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $siswa->nama_lengkap ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $siswa->nama_kelas ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">
                                            <?= $siswa->jumlah_alpha ?>x
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="<?= base_url('bk/surat?siswa_id=' . $siswa->id) ?>" class="text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-file-alt"></i> Buat Surat
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Tidak ada data</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Siswa Terlambat 5x -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Siswa Terlambat 5x atau Lebih</h3>
        </div>
        <div class="p-6">
            <?php if(!empty($siswa_terlambat)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($siswa_terlambat as $siswa): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $siswa->nis ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $siswa->nama_lengkap ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $siswa->nama_kelas ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">
                                            <?= $siswa->jumlah_terlambat ?>x
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="<?= base_url('bk/surat?siswa_id=' . $siswa->id) ?>" class="text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-file-alt"></i> Buat Surat
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Tidak ada data</p>
            <?php endif; ?>
        </div>
    </div>
</div>

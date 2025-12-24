<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard BK</h2>
        <p class="text-gray-600">Monitoring dan Bimbingan Konseling</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Siswa Bermasalah (Bulan Ini)</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $stats['total_siswa_bermasalah'] ?> Siswa</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <i class="fas fa-file-alt text-white text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Surat Pemanggilan (Bulan Ini)</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $stats['surat_bulan_ini'] ?> Surat</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Siswa Alpha 3x -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Siswa Alpha 3x (Bulan Ini)</h3>
        </div>
        <div class="p-6">
            <?php if(!empty($siswa_alpha)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($siswa_alpha as $siswa): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $siswa->nis ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $siswa->nama_lengkap ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $siswa->nama_kelas ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">
                                            <?= $siswa->jumlah_alpha ?>x Alpha
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Tidak ada siswa alpha 3x bulan ini</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Siswa Terlambat 5x -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Siswa Terlambat 5x (Bulan Ini)</h3>
        </div>
        <div class="p-6">
            <?php if(!empty($siswa_terlambat)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($siswa_terlambat as $siswa): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $siswa->nis ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $siswa->nama_lengkap ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $siswa->nama_kelas ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">
                                            <?= $siswa->jumlah_terlambat ?>x Terlambat
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Tidak ada siswa terlambat 5x bulan ini</p>
            <?php endif; ?>
        </div>
    </div>
</div>

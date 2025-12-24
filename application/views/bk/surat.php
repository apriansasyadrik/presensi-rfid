<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Cetak Surat Pemanggilan</h2>
        <p class="text-gray-600">Buat surat pemanggilan orang tua/wali siswa</p>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Form Surat -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Buat Surat Baru</h3>
        </div>
        <div class="p-6">
            <form action="<?= base_url('bk/surat/save') ?>" method="POST">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat *</label>
                        <input type="text" name="nomor_surat" value="<?= sprintf('%03d/BK/%s/%s', 1, date('m'), date('Y')) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <p class="text-xs text-gray-500 mt-1">Format: 001/BK/MM/YYYY</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Siswa *</label>
                        <select name="siswa_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach($siswa_list as $siswa): ?>
                                <option value="<?= $siswa->id ?>" <?= (isset($_GET['siswa_id']) && $_GET['siswa_id'] == $siswa->id) ? 'selected' : '' ?>>
                                    <?= $siswa->nis ?> - <?= $siswa->nama_lengkap ?> (<?= $siswa->nama_kelas ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat *</label>
                        <input type="date" name="tanggal_surat" value="<?= date('Y-m-d') ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Panggilan *</label>
                        <input type="datetime-local" name="waktu_panggil" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Perihal *</label>
                    <input type="text" name="perihal" placeholder="Contoh: Pemanggilan Orang Tua/Wali" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Isi Surat (Opsional)</label>
                    <textarea name="isi_surat" rows="5" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Isi surat akan menggunakan template default jika tidak diisi"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded">
                        <i class="fas fa-save"></i> Simpan & Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Surat -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Surat</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perihal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(!empty($surat_list)): ?>
                            <?php foreach($surat_list as $surat): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $surat->nomor_surat ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= date('d/m/Y', strtotime($surat->tanggal_surat)) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $surat->nama_lengkap ?></td>
                                    <td class="px-6 py-4 text-sm"><?= $surat->perihal ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="<?= base_url('bk/surat/preview/' . $surat->id) ?>" target="_blank" class="text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data surat</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

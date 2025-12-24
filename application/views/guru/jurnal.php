<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Jurnal & Absensi Siswa</h2>
        <p class="text-gray-600">Input jurnal dan absensi siswa per mata pelajaran</p>
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

    <!-- Form Isi Jurnal -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Isi Jurnal Baru</h3>
        </div>
        <div class="p-6">
            <form action="<?= base_url('guru/jurnal/save') ?>" method="POST" id="formJurnal">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal Pelajaran *</label>
                        <select name="jadwal_id" id="jadwalSelect" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih Jadwal --</option>
                            <?php foreach($jadwal_list as $jadwal): ?>
                                <option value="<?= $jadwal->id ?>" data-kelas="<?= $jadwal->kelas_id ?>">
                                    <?= $jadwal->nama_kelas ?> - <?= $jadwal->nama_mapel ?> (<?= $jadwal->hari ?>, <?= $jadwal->jam_mulai ?>-<?= $jadwal->jam_selesai ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                        <input type="date" name="tanggal" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Materi Pelajaran *</label>
                    <textarea name="materi" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan materi yang diajarkan" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>

                <!-- Daftar Siswa untuk Absensi -->
                <div id="siswaList" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Absensi Siswa *</label>
                    <div class="border border-gray-300 rounded p-4 max-h-96 overflow-y-auto">
                        <p class="text-gray-500 text-center">Pilih jadwal terlebih dahulu</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                        <i class="fas fa-save"></i> Simpan Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Jurnal -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Jurnal</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(!empty($jurnal_list)): ?>
                            <?php foreach($jurnal_list as $jurnal): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('d/m/Y', strtotime($jurnal->tanggal)) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $jurnal->nama_kelas ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $jurnal->nama_mapel ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= substr($jurnal->materi, 0, 100) ?>...
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data jurnal</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('jadwalSelect').addEventListener('change', function() {
        const kelasId = this.options[this.selectedIndex].dataset.kelas;
        if (!kelasId) {
            document.getElementById('siswaList').querySelector('div').innerHTML = '<p class="text-gray-500 text-center">Pilih jadwal terlebih dahulu</p>';
            return;
        }

        // Load student list via AJAX
        fetch('<?= base_url('guru/jurnal/get_siswa/') ?>' + kelasId)
            .then(response => response.json())
            .then(data => {
                let html = '<table class="min-w-full"><thead><tr><th class="text-left py-2">NIS</th><th class="text-left py-2">Nama</th><th class="text-center py-2">Status Kehadiran</th></tr></thead><tbody>';
                
                data.forEach(siswa => {
                    html += `
                        <tr class="border-t">
                            <td class="py-2">${siswa.nis}</td>
                            <td class="py-2">${siswa.nama_lengkap}</td>
                            <td class="py-2 text-center">
                                <div class="flex justify-center space-x-4">
                                    <label><input type="radio" name="status_${siswa.id}" value="H" required> H</label>
                                    <label><input type="radio" name="status_${siswa.id}" value="S"> S</label>
                                    <label><input type="radio" name="status_${siswa.id}" value="I"> I</label>
                                    <label><input type="radio" name="status_${siswa.id}" value="A"> A</label>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += '</tbody></table>';
                document.getElementById('siswaList').querySelector('div').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('siswaList').querySelector('div').innerHTML = '<p class="text-red-500 text-center">Gagal memuat data siswa</p>';
            });
    });
</script>

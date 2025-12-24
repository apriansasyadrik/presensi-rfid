<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>
<body>
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Naik Kelas Massal</h2>
            
            <!-- Alert -->
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
            
            <!-- Form Naik Kelas -->
            <form id="formNaikKelas" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tahun Ajaran Asal -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Tahun Ajaran Asal</label>
                        <select name="tahun_ajaran_asal" id="tahunAjaranAsal" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Tahun Ajaran</option>
                            <?php foreach($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta->id ?>"><?= $ta->tahun_awal ?>/<?= $ta->tahun_akhir ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Tingkat Asal -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Tingkat Asal</label>
                        <select name="tingkat_asal" id="tingkatAsal" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    
                    <!-- Tahun Ajaran Tujuan -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Tahun Ajaran Tujuan</label>
                        <select name="tahun_ajaran_tujuan" id="tahunAjaranTujuan" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Tahun Ajaran</option>
                            <?php foreach($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta->id ?>"><?= $ta->tahun_awal ?>/<?= $ta->tahun_akhir ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Tingkat Tujuan -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Tingkat Tujuan</label>
                        <select name="tingkat_tujuan" id="tingkatTujuan" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                            <option value="lulus">Lulus</option>
                        </select>
                    </div>
                    
                    <!-- Jurusan Tujuan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Jurusan Tujuan</label>
                        <input type="text" name="jurusan_tujuan" id="jurusanTujuan" class="w-full border rounded px-3 py-2" placeholder="Contoh: IPA, IPS, Bahasa">
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="button" onclick="previewNaikKelas()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                    <button type="button" onclick="prosesNaikKelas()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        <i class="fas fa-check mr-2"></i>Proses Naik Kelas
                    </button>
                </div>
            </form>
            
            <!-- Preview Table -->
            <div id="previewSection" class="mt-6 hidden">
                <h3 class="text-xl font-bold mb-4">Preview Siswa yang Akan Dinaikan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">NIS</th>
                                <th class="px-4 py-2 border">Nama</th>
                                <th class="px-4 py-2 border">Kelas Asal</th>
                            </tr>
                        </thead>
                        <tbody id="previewTableBody">
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Total: <span id="totalSiswa" class="font-bold">0</span> siswa</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function previewNaikKelas() {
        const tahunAjaran = $('#tahunAjaranAsal').val();
        const tingkat = $('#tingkatAsal').val();
        
        if (!tahunAjaran || !tingkat) {
            alert('Pilih tahun ajaran dan tingkat terlebih dahulu');
            return;
        }
        
        $.ajax({
            url: '<?= base_url('admin/naik-kelas/preview') ?>',
            type: 'POST',
            data: {
                tahun_ajaran_id: tahunAjaran,
                tingkat: tingkat,
                <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    let html = '';
                    data.data.forEach((siswa, index) => {
                        html += `<tr>
                            <td class="px-4 py-2 border text-center">${index + 1}</td>
                            <td class="px-4 py-2 border">${siswa.nis}</td>
                            <td class="px-4 py-2 border">${siswa.nama_lengkap}</td>
                            <td class="px-4 py-2 border">${siswa.nama_kelas}</td>
                        </tr>`;
                    });
                    
                    $('#previewTableBody').html(html);
                    $('#totalSiswa').text(data.count);
                    $('#previewSection').removeClass('hidden');
                } else {
                    alert(data.message);
                }
            }
        });
    }
    
    function prosesNaikKelas() {
        if (!confirm('Yakin akan memproses naik kelas? Proses ini tidak dapat dibatalkan!')) {
            return;
        }
        
        const formData = $('#formNaikKelas').serialize();
        
        $.ajax({
            url: '<?= base_url('admin/naik-kelas/process') ?>',
            type: 'POST',
            data: formData + '&<?= $this->security->get_csrf_token_name() ?>=' + '<?= $this->security->get_csrf_hash() ?>',
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            }
        });
    }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem Presensi RFID</title>
</head>
<body>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Kelola konfigurasi dan template notifikasi WhatsApp</p>
        </div>

        <!-- Flash Messages -->
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

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('config')" id="tab-config" class="tab-button border-b-2 border-blue-500 py-4 px-1 text-blue-600 font-medium">
                        <i class="fas fa-cog mr-2"></i>Konfigurasi API
                    </button>
                    <button onclick="showTab('template')" id="tab-template" class="tab-button border-b-2 border-transparent py-4 px-1 text-gray-500 hover:text-gray-700 font-medium">
                        <i class="fas fa-file-alt mr-2"></i>Template Pesan
                    </button>
                    <button onclick="showTab('kelas')" id="tab-kelas" class="tab-button border-b-2 border-transparent py-4 px-1 text-gray-500 hover:text-gray-700 font-medium">
                        <i class="fas fa-school mr-2"></i>Kelas Notifikasi
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab: Konfigurasi API -->
        <div id="content-config" class="tab-content">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4">Konfigurasi API WhatsApp</h2>
                
                <form action="<?= base_url('admin/wa_config/save_config') ?>" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">API URL *</label>
                        <input type="url" name="api_url" value="<?= isset($config->api_url) ? $config->api_url : '' ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                               placeholder="https://api.whatsapp.com/..." required>
                        <p class="text-xs text-gray-500 mt-1">Contoh: https://api.fonnte.com/send</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Key *</label>
                        <input type="text" name="api_key" value="<?= isset($config->api_key) ? $config->api_key : '' ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                               placeholder="Your API Key" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sender (Nomor WA) *</label>
                        <input type="text" name="sender" value="<?= isset($config->sender) ? $config->sender : '' ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                               placeholder="628123456789" required>
                        <p class="text-xs text-gray-500 mt-1">Format: 628xxxxxxxxxx (tanpa +)</p>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" <?= (isset($config->is_active) && $config->is_active) ? 'checked' : '' ?> 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktifkan Notifikasi WhatsApp</span>
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Simpan Konfigurasi
                        </button>
                        <button type="button" onclick="testConnection()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plug mr-2"></i>Test Koneksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab: Template Pesan -->
        <div id="content-template" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Template Masuk -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Template Absen Masuk</h2>
                    <?php $template_masuk = null; foreach($templates as $t) { if($t->template_type == 'masuk') $template_masuk = $t; } ?>
                    
                    <form id="form-masuk">
                        <input type="hidden" name="id" value="<?= $template_masuk ? $template_masuk->id : '' ?>">
                        <input type="hidden" name="template_type" value="masuk">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pesan Template</label>
                            <textarea name="message" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required><?= $template_masuk ? $template_masuk->message : '' ?></textarea>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm font-medium text-blue-800 mb-2">Variabel yang tersedia:</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li><code>{nama}</code> - Nama siswa</li>
                                <li><code>{kelas}</code> - Kelas siswa</li>
                                <li><code>{waktu}</code> - Waktu absen</li>
                                <li><code>{tanggal}</code> - Tanggal absen</li>
                                <li><code>{status}</code> - Status (Tepat Waktu/Terlambat)</li>
                            </ul>
                        </div>

                        <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Simpan Template
                        </button>
                    </form>
                </div>

                <!-- Template Pulang -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Template Absen Pulang</h2>
                    <?php $template_pulang = null; foreach($templates as $t) { if($t->template_type == 'pulang') $template_pulang = $t; } ?>
                    
                    <form id="form-pulang">
                        <input type="hidden" name="id" value="<?= $template_pulang ? $template_pulang->id : '' ?>">
                        <input type="hidden" name="template_type" value="pulang">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pesan Template</label>
                            <textarea name="message" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required><?= $template_pulang ? $template_pulang->message : '' ?></textarea>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm font-medium text-blue-800 mb-2">Variabel yang tersedia:</p>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li><code>{nama}</code> - Nama siswa</li>
                                <li><code>{kelas}</code> - Kelas siswa</li>
                                <li><code>{waktu}</code> - Waktu pulang</li>
                                <li><code>{tanggal}</code> - Tanggal pulang</li>
                            </ul>
                        </div>

                        <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Simpan Template
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab: Kelas Notifikasi -->
        <div id="content-kelas" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Kelas untuk Notifikasi</h2>
                <p class="text-gray-600 mb-6">Pilih kelas yang akan menerima notifikasi WhatsApp saat siswa absen</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php 
                    $active_kelas = [];
                    foreach($notif_kelas as $nk) {
                        $active_kelas[] = $nk->id_kelas;
                    }
                    foreach($kelas as $k): 
                        $is_active = in_array($k->id, $active_kelas);
                    ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-800">Kelas <?= $k->tingkat ?> <?= $k->jurusan ?></h3>
                                <p class="text-sm text-gray-500"><?= $k->nama_kelas ?></p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" 
                                       data-kelas-id="<?= $k->id ?>" 
                                       <?= $is_active ? 'checked' : '' ?>
                                       onchange="toggleKelas(this)">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        function showTab(tab) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('border-blue-500', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected content
            document.getElementById('content-' + tab).classList.remove('hidden');
            // Add active to selected button
            const btn = document.getElementById('tab-' + tab);
            btn.classList.add('border-blue-500', 'text-blue-600');
            btn.classList.remove('border-transparent', 'text-gray-500');
        }

        // Test connection
        function testConnection() {
            fetch('<?= base_url('admin/wa_config/test_connection') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
            });
        }

        // Save template
        document.getElementById('form-masuk').addEventListener('submit', function(e) {
            e.preventDefault();
            saveTemplate(this);
        });

        document.getElementById('form-pulang').addEventListener('submit', function(e) {
            e.preventDefault();
            saveTemplate(this);
        });

        function saveTemplate(form) {
            const formData = new FormData(form);
            
            fetch('<?= base_url('admin/wa_config/save_template') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                if (result.status === 'success') {
                    location.reload();
                }
            });
        }

        // Toggle kelas
        function toggleKelas(checkbox) {
            const kelasId = checkbox.getAttribute('data-kelas-id');
            const isActive = checkbox.checked ? 1 : 0;
            
            const formData = new FormData();
            formData.append('id_kelas', kelasId);
            formData.append('is_active', isActive);
            
            fetch('<?= base_url('admin/wa_config/toggle_kelas') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status !== 'success') {
                    alert('Gagal mengupdate: ' + result.message);
                    checkbox.checked = !checkbox.checked; // Revert
                }
            });
        }
    </script>
</body>
</html>

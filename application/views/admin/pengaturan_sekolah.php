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

<!-- School Settings Form -->
<div class="bg-white rounded-lg shadow-md p-8">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">
            <i class="fas fa-school text-blue-600 mr-2"></i>
            Pengaturan Sekolah
        </h3>
        <p class="text-gray-600">Kelola informasi sekolah dan logo</p>
    </div>

    <?= form_open_multipart('admin/pengaturan-sekolah', ['class' => 'space-y-6']); ?>
        
        <!-- Nama Sekolah -->
        <div>
            <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-building mr-1"></i> Nama Sekolah *
            </label>
            <input 
                type="text" 
                id="nama_sekolah" 
                name="nama_sekolah" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                placeholder="Masukkan nama sekolah"
                value="<?= isset($pengaturan->nama_sekolah) ? $pengaturan->nama_sekolah : '' ?>"
            >
            <?= form_error('nama_sekolah', '<p class="text-red-500 text-sm mt-1">', '</p>'); ?>
        </div>

        <!-- Alamat Sekolah -->
        <div>
            <label for="alamat_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-map-marker-alt mr-1"></i> Alamat Sekolah *
            </label>
            <textarea 
                id="alamat_sekolah" 
                name="alamat_sekolah" 
                rows="3"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                placeholder="Masukkan alamat lengkap sekolah"
            ><?= isset($pengaturan->alamat_sekolah) ? $pengaturan->alamat_sekolah : '' ?></textarea>
            <?= form_error('alamat_sekolah', '<p class="text-red-500 text-sm mt-1">', '</p>'); ?>
        </div>

        <!-- Nama Kepala Sekolah -->
        <div>
            <label for="nama_kepala_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user-tie mr-1"></i> Nama Kepala Sekolah *
            </label>
            <input 
                type="text" 
                id="nama_kepala_sekolah" 
                name="nama_kepala_sekolah" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                placeholder="Masukkan nama kepala sekolah"
                value="<?= isset($pengaturan->nama_kepala_sekolah) ? $pengaturan->nama_kepala_sekolah : '' ?>"
            >
            <?= form_error('nama_kepala_sekolah', '<p class="text-red-500 text-sm mt-1">', '</p>'); ?>
        </div>

        <!-- Logo Sekolah -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-image mr-1"></i> Logo Sekolah
            </label>
            
            <?php if(isset($pengaturan->logo_sekolah) && !empty($pengaturan->logo_sekolah)): ?>
            <div class="mb-4 flex items-center space-x-4">
                <img 
                    src="<?= base_url('uploads/logo/' . $pengaturan->logo_sekolah) ?>" 
                    alt="Logo Sekolah" 
                    class="h-24 w-24 object-contain border border-gray-300 rounded-lg p-2"
                >
                <div class="text-sm text-gray-600">
                    <p class="font-medium">Logo saat ini</p>
                    <p class="text-xs"><?= $pengaturan->logo_sekolah ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="flex items-center space-x-4">
                <label class="flex-1">
                    <input 
                        type="file" 
                        name="logo_sekolah" 
                        accept="image/jpeg,image/jpg,image/png"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    >
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4 pt-4 border-t">
            <button 
                type="submit" 
                class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <i class="fas fa-save mr-2"></i> Simpan Pengaturan
            </button>
        </div>

    <?= form_close(); ?>
</div>

<!-- Info Box -->
<div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informasi Pengaturan</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Nama sekolah akan ditampilkan di header laporan dan surat</li>
                    <li>Alamat sekolah akan ditampilkan di kop laporan</li>
                    <li>Logo sekolah akan ditampilkan di laporan PDF dan halaman login</li>
                    <li>Pastikan logo dalam format JPG, JPEG, atau PNG dengan ukuran maksimal 2MB</li>
                </ul>
            </div>
        </div>
    </div>
</div>

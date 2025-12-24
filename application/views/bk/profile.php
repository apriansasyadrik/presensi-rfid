<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Profile</h2>
        <p class="text-gray-600">Kelola informasi profile Anda</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Profile</h3>
            </div>
            <div class="p-6">
                <form action="<?= base_url('bk/profile/update') ?>" method="POST">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" value="<?= $user->username ?>" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="<?= $user->nama_lengkap ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="<?= $user->email ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Ubah Password</h3>
            </div>
            <div class="p-6">
                <form action="<?= base_url('bk/profile/change_password') ?>" method="POST">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama *</label>
                        <input type="password" name="password_lama" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru *</label>
                        <input type="password" name="password_baru" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password *</label>
                        <input type="password" name="konfirmasi_password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

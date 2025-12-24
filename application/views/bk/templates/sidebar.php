<div class="flex">
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="p-4 border-b">
            <h2 class="text-lg font-bold text-gray-800">Menu BK</h2>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li>
                    <a href="<?= base_url('bk/dashboard') ?>" class="flex items-center space-x-3 p-3 rounded hover:bg-purple-50 <?= $this->uri->segment(2) == 'dashboard' ? 'bg-purple-100 text-purple-600' : 'text-gray-700' ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('bk/monitoring') ?>" class="flex items-center space-x-3 p-3 rounded hover:bg-purple-50 <?= $this->uri->segment(2) == 'monitoring' ? 'bg-purple-100 text-purple-600' : 'text-gray-700' ?>">
                        <i class="fas fa-user-shield"></i>
                        <span>Monitoring Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('bk/surat') ?>" class="flex items-center space-x-3 p-3 rounded hover:bg-purple-50 <?= $this->uri->segment(2) == 'surat' ? 'bg-purple-100 text-purple-600' : 'text-gray-700' ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>Cetak Surat</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('bk/profile') ?>" class="flex items-center space-x-3 p-3 rounded hover:bg-purple-50 <?= $this->uri->segment(2) == 'profile' ? 'bg-purple-100 text-purple-600' : 'text-gray-700' ?>">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
    
    <main class="flex-1 p-6">

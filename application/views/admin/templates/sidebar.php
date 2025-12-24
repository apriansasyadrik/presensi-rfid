        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-gradient-to-b from-blue-800 to-blue-900 text-white w-64 flex-shrink-0 hidden md:block overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-id-card text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">PRESENSI RFID</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <!-- Dashboard -->
                    <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'dashboard' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Pengaturan Sekolah -->
                    <a href="<?= base_url('admin/pengaturan-sekolah') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'pengaturan-sekolah' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-school w-5"></i>
                        <span>Pengaturan Sekolah</span>
                    </a>

                    <!-- Pengaturan Hari Kerja -->
                    <a href="<?= base_url('admin/pengaturan-hari-kerja') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'pengaturan-hari-kerja' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span>Hari Kerja</span>
                    </a>

                    <!-- Data Master -->
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider px-4 mb-2">Data Master</p>
                    </div>

                    <a href="<?= base_url('admin/tahun-ajaran') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'tahun-ajaran' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span>Tahun Ajaran</span>
                    </a>

                    <a href="<?= base_url('admin/semester') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'semester' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-calendar-week w-5"></i>
                        <span>Semester</span>
                    </a>

                    <a href="<?= base_url('admin/kelas') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'kelas' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-door-open w-5"></i>
                        <span>Kelas</span>
                    </a>

                    <a href="<?= base_url('admin/naik-kelas') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'naik-kelas' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-level-up-alt w-5"></i>
                        <span>Naik Kelas</span>
                    </a>

                    <a href="<?= base_url('admin/siswa') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'siswa' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-user-graduate w-5"></i>
                        <span>Data Siswa</span>
                    </a>

                    <a href="<?= base_url('admin/guru') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'guru' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-chalkboard-teacher w-5"></i>
                        <span>Data Guru</span>
                    </a>

                    <!-- Mata Pelajaran & Jadwal -->
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider px-4 mb-2">Akademik</p>
                    </div>

                    <a href="<?= base_url('admin/mata-pelajaran') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'mata-pelajaran' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-book w-5"></i>
                        <span>Mata Pelajaran</span>
                    </a>

                    <a href="<?= base_url('admin/jadwal-pelajaran') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'jadwal-pelajaran' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-clock w-5"></i>
                        <span>Jadwal Pelajaran</span>
                    </a>

                    <!-- Notifikasi WA -->
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider px-4 mb-2">Notifikasi</p>
                    </div>

                    <a href="<?= base_url('admin/wa-notifikasi') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'wa-notifikasi' ? 'bg-blue-700' : '' ?>">
                        <i class="fab fa-whatsapp w-5"></i>
                        <span>WhatsApp Settings</span>
                    </a>

                    <!-- Laporan -->
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider px-4 mb-2">Laporan</p>
                    </div>

                    <a href="<?= base_url('admin/laporan-siswa') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'laporan-siswa' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-file-alt w-5"></i>
                        <span>Laporan Siswa</span>
                    </a>

                    <a href="<?= base_url('admin/laporan-guru') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'laporan-guru' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-file-alt w-5"></i>
                        <span>Laporan Guru</span>
                    </a>

                    <a href="<?= base_url('admin/rekap-siswa') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'rekap-siswa' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Rekap Siswa</span>
                    </a>

                    <a href="<?= base_url('admin/rekap-guru') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?= $this->uri->segment(2) == 'rekap-guru' ? 'bg-blue-700' : '' ?>">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Rekap Guru</span>
                    </a>

                    <!-- Logout -->
                    <div class="pt-4 pb-4">
                        <a href="<?= base_url('auth/logout') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-gray-800"><?= isset($title) ? $title : 'Dashboard' ?></h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-user-circle text-2xl text-blue-600"></i>
                            <div>
                                <p class="font-semibold text-gray-800"><?= $user_name ?></p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">

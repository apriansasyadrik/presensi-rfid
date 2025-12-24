            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <!-- Mobile Sidebar -->
    <aside id="mobileSidebar" class="sidebar sidebar-hidden fixed inset-y-0 left-0 bg-gradient-to-b from-blue-800 to-blue-900 text-white w-64 z-50 md:hidden overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-id-card text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">PRESENSI RFID</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="space-y-1">
                <!-- Same navigation as desktop sidebar -->
                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/pengaturan-sekolah') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-school w-5"></i>
                    <span>Pengaturan Sekolah</span>
                </a>
                <a href="<?= base_url('admin/pengaturan-hari-kerja') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Hari Kerja</span>
                </a>
                <div class="pt-4">
                    <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider px-4 mb-2">Data Master</p>
                </div>
                <a href="<?= base_url('admin/tahun-ajaran') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>Tahun Ajaran</span>
                </a>
                <a href="<?= base_url('admin/semester') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-week w-5"></i>
                    <span>Semester</span>
                </a>
                <a href="<?= base_url('admin/kelas') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-door-open w-5"></i>
                    <span>Kelas</span>
                </a>
                <a href="<?= base_url('admin/naik-kelas') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-level-up-alt w-5"></i>
                    <span>Naik Kelas</span>
                </a>
                <a href="<?= base_url('admin/siswa') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-user-graduate w-5"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="<?= base_url('admin/guru') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-chalkboard-teacher w-5"></i>
                    <span>Data Guru</span>
                </a>
                <div class="pt-4 pb-4">
                    <a href="<?= base_url('auth/logout') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <script>
        function toggleSidebar() {
            const mobileSidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            mobileSidebar.classList.toggle('sidebar-hidden');
            overlay.classList.toggle('hidden');
        }

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>

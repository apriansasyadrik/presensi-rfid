<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Scanner - Sistem Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.7;
            }
            100% {
                transform: scale(0.95);
                opacity: 1;
            }
        }
        
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }
        
        @keyframes slide-up {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .slide-up {
            animation: slide-up 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-2xl mb-6 pulse-ring">
                <i class="fas fa-id-card text-5xl text-blue-600"></i>
            </div>
            <h1 class="text-5xl font-black text-white mb-3 drop-shadow-lg">SISTEM PRESENSI RFID</h1>
            <p class="text-xl text-white/90 font-medium">Tempelkan Kartu RFID Anda</p>
            <div class="mt-4 inline-block bg-white/20 backdrop-blur-sm px-6 py-2 rounded-full">
                <p class="text-white font-semibold" id="currentTime"></p>
            </div>
        </div>

        <!-- Scanner Input (Hidden) -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-2xl">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            id="rfid_input" 
                            class="w-full px-6 py-4 bg-white/90 border-2 border-white rounded-xl text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-white/50 transition duration-200"
                            placeholder="Scan RFID Card..."
                            autofocus
                        >
                    </div>
                    <button 
                        onclick="manualScan()" 
                        class="px-6 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition duration-200 shadow-lg"
                    >
                        <i class="fas fa-qrcode mr-2"></i> Scan
                    </button>
                </div>
            </div>
        </div>

        <!-- Result Display -->
        <div id="resultContainer" class="max-w-4xl mx-auto hidden slide-up">
            <div id="resultCard" class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Result content will be inserted here -->
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="max-w-6xl mx-auto mt-8">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 shadow-2xl">
                <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-clock mr-3"></i>
                    Absensi Terbaru
                </h3>
                <div id="recentAttendance" class="space-y-3">
                    <!-- Recent attendance will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto focus on RFID input
        document.getElementById('rfid_input').focus();

        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('currentTime').textContent = now.toLocaleDateString('id-ID', options);
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Handle RFID input
        document.getElementById('rfid_input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                scanRFID();
            }
        });

        function manualScan() {
            scanRFID();
        }

        function scanRFID() {
            const rfidInput = document.getElementById('rfid_input');
            const rfidValue = rfidInput.value.trim();

            if (!rfidValue) {
                showError('RFID tidak boleh kosong');
                return;
            }

            // Send AJAX request
            fetch('<?= base_url("rfid-scanner/scan") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'rfid_uid=' + encodeURIComponent(rfidValue)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data);
                    loadRecentAttendance();
                } else {
                    showError(data.message);
                }
                rfidInput.value = '';
                rfidInput.focus();
            })
            .catch(error => {
                showError('Terjadi kesalahan sistem');
                rfidInput.value = '';
                rfidInput.focus();
            });
        }

        function showSuccess(data) {
            const container = document.getElementById('resultContainer');
            const card = document.getElementById('resultCard');
            
            let statusBadge = '';
            if (data.status === 'terlambat') {
                statusBadge = `<span class="px-4 py-2 bg-red-100 text-red-800 text-sm font-bold rounded-full">Terlambat ${data.keterlambatan} menit</span>`;
            } else if (data.status === 'tepat_waktu') {
                statusBadge = '<span class="px-4 py-2 bg-green-100 text-green-800 text-sm font-bold rounded-full">Tepat Waktu</span>';
            }

            const userInfo = data.user_type === 'siswa' 
                ? `<p class="text-gray-600 text-lg">NIS: ${data.user_data.nis}</p>
                   <p class="text-gray-600 text-lg">Kelas: ${data.user_data.nama_kelas || '-'}</p>`
                : `<p class="text-gray-600 text-lg">NIP: ${data.user_data.nip || '-'}</p>`;

            card.innerHTML = `
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-8 text-white text-center">
                    <i class="fas fa-check-circle text-6xl mb-4"></i>
                    <h2 class="text-3xl font-bold mb-2">BERHASIL!</h2>
                    <p class="text-xl">${data.message}</p>
                </div>
                <div class="p-8 text-center">
                    <div class="mb-6">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                            <i class="fas ${data.user_type === 'siswa' ? 'fa-user-graduate' : 'fa-chalkboard-teacher'} text-6xl text-white"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">${data.user_data.nama_lengkap}</h3>
                        ${userInfo}
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Tipe</p>
                                <p class="text-xl font-bold text-gray-800">${data.attendance_type === 'masuk' ? 'MASUK' : 'PULANG'}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Waktu</p>
                                <p class="text-xl font-bold text-gray-800">${data.jam}</p>
                            </div>
                        </div>
                        ${statusBadge ? `<div class="mt-4">${statusBadge}</div>` : ''}
                    </div>
                    <p class="text-gray-600"><i class="fas fa-info-circle mr-2"></i>Notifikasi WhatsApp sedang dikirim</p>
                </div>
            `;

            container.classList.remove('hidden');
            setTimeout(() => {
                container.classList.add('hidden');
            }, 5000);
        }

        function showError(message) {
            const container = document.getElementById('resultContainer');
            const card = document.getElementById('resultCard');

            card.innerHTML = `
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-12 text-white text-center">
                    <i class="fas fa-times-circle text-6xl mb-4"></i>
                    <h2 class="text-3xl font-bold mb-2">GAGAL!</h2>
                    <p class="text-xl">${message}</p>
                </div>
            `;

            container.classList.remove('hidden');
            setTimeout(() => {
                container.classList.add('hidden');
            }, 3000);
        }

        function loadRecentAttendance() {
            // This would typically load via AJAX from server
            // For now, just a placeholder
        }

        // Load recent attendance on page load
        loadRecentAttendance();
    </script>
</body>
</html>

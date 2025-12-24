<div class="container-fluid px-6 py-8">
	<div class="flex justify-between items-center mb-6">
		<h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
	</div>

	<!-- Filter Form -->
	<div class="bg-white rounded-lg shadow-md p-6 mb-6">
		<form method="GET" action="<?= site_url('admin/laporan_guru') ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4">
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
				<input type="month" name="bulan" value="<?= $bulan ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-2">Guru (Opsional - Kosongkan untuk semua)</label>
				<select name="guru_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
					<option value="">Semua Guru</option>
					<?php foreach ($guru_list as $guru): ?>
						<option value="<?= $guru->id ?>" <?= $guru_id == $guru->id ? 'selected' : '' ?>>
							<?= $guru->nama_lengkap ?> (<?= $guru->nip ?>)
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="flex items-end">
				<button type="submit" class="w-full bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
					<i class="fas fa-search mr-2"></i> Tampilkan
				</button>
			</div>
		</form>
	</div>

	<?php if (!empty($laporan)): ?>
	<!-- Export Buttons -->
	<div class="flex gap-4 mb-4">
		<a href="<?= site_url('admin/laporan_guru/export_excel?bulan=' . $bulan . ($guru_id ? '&guru_id=' . $guru_id : '')) ?>" 
		   class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
			<i class="fas fa-file-excel mr-2"></i> Export Excel
		</a>
		<a href="<?= site_url('admin/laporan_guru/export_pdf?bulan=' . $bulan . ($guru_id ? '&guru_id=' . $guru_id : '')) ?>" 
		   target="_blank" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
			<i class="fas fa-file-pdf mr-2"></i> Export PDF
		</a>
		<a href="<?= site_url('admin/laporan_guru/rekap?bulan=' . $bulan . ($guru_id ? '&guru_id=' . $guru_id : '')) ?>" 
		   class="bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-600">
			<i class="fas fa-chart-bar mr-2"></i> Lihat Rekap
		</a>
	</div>

	<!-- Data Table -->
	<div class="bg-white rounded-lg shadow-md overflow-hidden">
		<div class="overflow-x-auto">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-50">
					<tr>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					<?php foreach ($laporan as $row): ?>
					<tr>
						<td class="px-6 py-4 whitespace-nowrap text-sm"><?= $row->nip ?></td>
						<td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><?= $row->nama_lengkap ?></td>
						<td class="px-6 py-4 whitespace-nowrap text-sm"><?= date('d/m/Y', strtotime($row->tanggal)) ?></td>
						<td class="px-6 py-4 whitespace-nowrap text-sm"><?= $row->jam_masuk ?: '-' ?></td>
						<td class="px-6 py-4 whitespace-nowrap text-sm"><?= $row->jam_pulang ?: '-' ?></td>
						<td class="px-6 py-4 whitespace-nowrap">
							<span class="px-2 py-1 text-xs font-semibold rounded-full 
								<?= $row->status == 'Hadir' ? 'bg-green-100 text-green-800' : '' ?>
								<?= $row->status == 'Sakit' ? 'bg-yellow-100 text-yellow-800' : '' ?>
								<?= $row->status == 'Izin' ? 'bg-blue-100 text-blue-800' : '' ?>
								<?= $row->status == 'Alpha' ? 'bg-red-100 text-red-800' : '' ?>">
								<?= $row->status ?>
							</span>
						</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm">
							<?= $row->terlambat > 0 ? $row->terlambat . ' menit' : '-' ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php else: ?>
	<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800">
		<i class="fas fa-info-circle mr-2"></i> Pilih bulan untuk menampilkan laporan.
	</div>
	<?php endif; ?>
</div>

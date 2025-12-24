<div class="container-fluid px-6 py-8">
	<div class="flex justify-between items-center mb-6">
		<h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
		<a href="<?= site_url('admin/laporan_guru') ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
			<i class="fas fa-arrow-left mr-2"></i> Kembali
		</a>
	</div>

	<?php if (!empty($rekap)): ?>
	<!-- Data Table -->
	<div class="bg-white rounded-lg shadow-md overflow-hidden">
		<div class="p-6 border-b border-gray-200">
			<h2 class="text-xl font-semibold">Rekap Absensi Guru</h2>
			<p class="text-gray-600">Bulan: <?= date('F Y', strtotime($bulan . '-01')) ?></p>
		</div>
		<div class="overflow-x-auto">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-50">
					<tr>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
						<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
						<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
						<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
						<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
						<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
						<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
						<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Menit</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					<?php if (is_array($rekap)): ?>
						<?php foreach ($rekap as $row): ?>
						<tr>
							<td class="px-6 py-4 whitespace-nowrap text-sm"><?= $row->nip ?></td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><?= $row->nama_lengkap ?></td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full"><?= $row->hadir ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full"><?= $row->sakit ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full"><?= $row->izin ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full"><?= $row->alpha ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center"><?= $row->total_terlambat ?>x</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center"><?= $row->total_menit_terlambat ?> menit</td>
						</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td class="px-6 py-4 whitespace-nowrap text-sm"><?= $rekap->nip ?></td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><?= $rekap->nama_lengkap ?></td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full"><?= $rekap->hadir ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full"><?= $rekap->sakit ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full"><?= $rekap->izin ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center">
								<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full"><?= $rekap->alpha ?></span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center"><?= $rekap->total_terlambat ?>x</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-center"><?= $rekap->total_menit_terlambat ?> menit</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php else: ?>
	<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800">
		<i class="fas fa-info-circle mr-2"></i> Tidak ada data rekap.
	</div>
	<?php endif; ?>
</div>

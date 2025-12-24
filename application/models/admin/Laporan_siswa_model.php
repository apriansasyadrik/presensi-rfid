<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_siswa_model extends CI_Model {

	public function get_laporan_bulanan($kelas_id, $bulan) {
		// $bulan format: YYYY-MM
		$tahun = substr($bulan, 0, 4);
		$bln = substr($bulan, 5, 2);
		
		$this->db->select('
			s.nis,
			s.nama_lengkap,
			ah.tanggal,
			ah.jam_masuk,
			ah.jam_pulang,
			ah.status,
			ah.terlambat
		');
		$this->db->from('absensi_harian ah');
		$this->db->join('siswa s', 's.id = ah.siswa_id');
		$this->db->where('s.kelas_id', $kelas_id);
		$this->db->where('YEAR(ah.tanggal)', $tahun);
		$this->db->where('MONTH(ah.tanggal)', $bln);
		$this->db->order_by('ah.tanggal', 'DESC');
		$this->db->order_by('s.nama_lengkap', 'ASC');
		
		return $this->db->get()->result();
	}

	public function get_rekap_bulanan($kelas_id, $bulan) {
		// Get summary: total hadir, sakit, izin, alpha, terlambat per siswa
		$tahun = substr($bulan, 0, 4);
		$bln = substr($bulan, 5, 2);
		
		$this->db->select('
			s.nis,
			s.nama_lengkap,
			COUNT(ah.id) as total_absensi,
			SUM(CASE WHEN ah.status = "Hadir" THEN 1 ELSE 0 END) as hadir,
			SUM(CASE WHEN ah.status = "Sakit" THEN 1 ELSE 0 END) as sakit,
			SUM(CASE WHEN ah.status = "Izin" THEN 1 ELSE 0 END) as izin,
			SUM(CASE WHEN ah.status = "Alpha" THEN 1 ELSE 0 END) as alpha,
			SUM(CASE WHEN ah.terlambat > 0 THEN 1 ELSE 0 END) as total_terlambat,
			SUM(ah.terlambat) as total_menit_terlambat
		');
		$this->db->from('siswa s');
		$this->db->join('absensi_harian ah', 'ah.siswa_id = s.id AND YEAR(ah.tanggal) = ' . $tahun . ' AND MONTH(ah.tanggal) = ' . $bln, 'left');
		$this->db->where('s.kelas_id', $kelas_id);
		$this->db->where('s.deleted_at IS NULL');
		$this->db->group_by('s.id');
		$this->db->order_by('s.nama_lengkap', 'ASC');
		
		return $this->db->get()->result();
	}

	public function get_laporan_siswa_individu($siswa_id, $bulan_dari, $bulan_sampai) {
		// Get attendance for individual student across date range
		$this->db->select('
			ah.tanggal,
			ah.jam_masuk,
			ah.jam_pulang,
			ah.status,
			ah.terlambat
		');
		$this->db->from('absensi_harian ah');
		$this->db->where('ah.siswa_id', $siswa_id);
		$this->db->where('ah.tanggal >=', $bulan_dari . '-01');
		$this->db->where('ah.tanggal <=', date('Y-m-t', strtotime($bulan_sampai . '-01')));
		$this->db->order_by('ah.tanggal', 'ASC');
		
		return $this->db->get()->result();
	}

	public function get_rekap_semester($kelas_id, $semester_id) {
		// Get semester summary
		$this->db->select('s.*, sem.nama_semester, ta.nama_tahun');
		$this->db->from('semester sem');
		$this->db->join('tahun_ajaran ta', 'ta.id = sem.tahun_ajaran_id');
		$this->db->where('sem.id', $semester_id);
		$semester_info = $this->db->get()->row();
		
		if (!$semester_info) {
			return [];
		}
		
		// Get attendance summary for the semester date range
		$this->db->select('
			s.nis,
			s.nama_lengkap,
			COUNT(ah.id) as total_absensi,
			SUM(CASE WHEN ah.status = "Hadir" THEN 1 ELSE 0 END) as hadir,
			SUM(CASE WHEN ah.status = "Sakit" THEN 1 ELSE 0 END) as sakit,
			SUM(CASE WHEN ah.status = "Izin" THEN 1 ELSE 0 END) as izin,
			SUM(CASE WHEN ah.status = "Alpha" THEN 1 ELSE 0 END) as alpha,
			SUM(CASE WHEN ah.terlambat > 0 THEN 1 ELSE 0 END) as total_terlambat
		');
		$this->db->from('siswa s');
		$this->db->join('absensi_harian ah', 'ah.siswa_id = s.id AND ah.tanggal BETWEEN "' . $semester_info->tanggal_mulai . '" AND "' . $semester_info->tanggal_selesai . '"', 'left');
		$this->db->where('s.kelas_id', $kelas_id);
		$this->db->where('s.deleted_at IS NULL');
		$this->db->group_by('s.id');
		$this->db->order_by('s.nama_lengkap', 'ASC');
		
		return $this->db->get()->result();
	}
}

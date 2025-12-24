<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_guru_model extends CI_Model {

	public function get_laporan_bulanan($guru_id, $bulan) {
		// $bulan format: YYYY-MM
		$tahun = substr($bulan, 0, 4);
		$bln = substr($bulan, 5, 2);
		
		$this->db->select('
			g.nip,
			g.nama_lengkap,
			ah.tanggal,
			ah.jam_masuk,
			ah.jam_pulang,
			ah.status,
			ah.terlambat
		');
		$this->db->from('absensi_harian ah');
		$this->db->join('guru g', 'g.id = ah.guru_id');
		$this->db->where('ah.guru_id', $guru_id);
		$this->db->where('YEAR(ah.tanggal)', $tahun);
		$this->db->where('MONTH(ah.tanggal)', $bln);
		$this->db->order_by('ah.tanggal', 'DESC');
		
		return $this->db->get()->result();
	}

	public function get_laporan_all_guru($bulan) {
		// Get all teachers attendance for the month
		$tahun = substr($bulan, 0, 4);
		$bln = substr($bulan, 5, 2);
		
		$this->db->select('
			g.nip,
			g.nama_lengkap,
			ah.tanggal,
			ah.jam_masuk,
			ah.jam_pulang,
			ah.status,
			ah.terlambat
		');
		$this->db->from('absensi_harian ah');
		$this->db->join('guru g', 'g.id = ah.guru_id');
		$this->db->where('YEAR(ah.tanggal)', $tahun);
		$this->db->where('MONTH(ah.tanggal)', $bln);
		$this->db->order_by('ah.tanggal', 'DESC');
		$this->db->order_by('g.nama_lengkap', 'ASC');
		
		return $this->db->get()->result();
	}

	public function get_rekap_bulanan($guru_id, $bulan) {
		// Get summary for one teacher
		$tahun = substr($bulan, 0, 4);
		$bln = substr($bulan, 5, 2);
		
		$this->db->select('
			g.nip,
			g.nama_lengkap,
			COUNT(ah.id) as total_absensi,
			SUM(CASE WHEN ah.status = "Hadir" THEN 1 ELSE 0 END) as hadir,
			SUM(CASE WHEN ah.status = "Sakit" THEN 1 ELSE 0 END) as sakit,
			SUM(CASE WHEN ah.status = "Izin" THEN 1 ELSE 0 END) as izin,
			SUM(CASE WHEN ah.status = "Alpha" THEN 1 ELSE 0 END) as alpha,
			SUM(CASE WHEN ah.terlambat > 0 THEN 1 ELSE 0 END) as total_terlambat,
			SUM(ah.terlambat) as total_menit_terlambat
		');
		$this->db->from('guru g');
		$this->db->join('absensi_harian ah', 'ah.guru_id = g.id AND YEAR(ah.tanggal) = ' . $tahun . ' AND MONTH(ah.tanggal) = ' . $bln, 'left');
		$this->db->where('g.id', $guru_id);
		$this->db->where('g.deleted_at IS NULL');
		$this->db->group_by('g.id');
		
		return $this->db->get()->row();
	}

	public function get_rekap_all_guru($bulan) {
		// Get summary for all teachers
		$tahun = substr($bulan, 0, 4);
		$bln = substr($bulan, 5, 2);
		
		$this->db->select('
			g.nip,
			g.nama_lengkap,
			COUNT(ah.id) as total_absensi,
			SUM(CASE WHEN ah.status = "Hadir" THEN 1 ELSE 0 END) as hadir,
			SUM(CASE WHEN ah.status = "Sakit" THEN 1 ELSE 0 END) as sakit,
			SUM(CASE WHEN ah.status = "Izin" THEN 1 ELSE 0 END) as izin,
			SUM(CASE WHEN ah.status = "Alpha" THEN 1 ELSE 0 END) as alpha,
			SUM(CASE WHEN ah.terlambat > 0 THEN 1 ELSE 0 END) as total_terlambat,
			SUM(ah.terlambat) as total_menit_terlambat
		');
		$this->db->from('guru g');
		$this->db->join('absensi_harian ah', 'ah.guru_id = g.id AND YEAR(ah.tanggal) = ' . $tahun . ' AND MONTH(ah.tanggal) = ' . $bln, 'left');
		$this->db->where('g.deleted_at IS NULL');
		$this->db->group_by('g.id');
		$this->db->order_by('g.nama_lengkap', 'ASC');
		
		return $this->db->get()->result();
	}

	public function get_rekap_semester($guru_id, $semester_id) {
		// Get semester summary for a teacher
		$this->db->select('s.*, sem.nama_semester, ta.nama_tahun, sem.tanggal_mulai, sem.tanggal_selesai');
		$this->db->from('semester sem');
		$this->db->join('tahun_ajaran ta', 'ta.id = sem.tahun_ajaran_id');
		$this->db->where('sem.id', $semester_id);
		$semester_info = $this->db->get()->row();
		
		if (!$semester_info) {
			return null;
		}
		
		$this->db->select('
			g.nip,
			g.nama_lengkap,
			COUNT(ah.id) as total_absensi,
			SUM(CASE WHEN ah.status = "Hadir" THEN 1 ELSE 0 END) as hadir,
			SUM(CASE WHEN ah.status = "Sakit" THEN 1 ELSE 0 END) as sakit,
			SUM(CASE WHEN ah.status = "Izin" THEN 1 ELSE 0 END) as izin,
			SUM(CASE WHEN ah.status = "Alpha" THEN 1 ELSE 0 END) as alpha,
			SUM(CASE WHEN ah.terlambat > 0 THEN 1 ELSE 0 END) as total_terlambat
		');
		$this->db->from('guru g');
		$this->db->join('absensi_harian ah', 'ah.guru_id = g.id AND ah.tanggal BETWEEN "' . $semester_info->tanggal_mulai . '" AND "' . $semester_info->tanggal_selesai . '"', 'left');
		$this->db->where('g.id', $guru_id);
		$this->db->where('g.deleted_at IS NULL');
		$this->db->group_by('g.id');
		
		return $this->db->get()->row();
	}
}

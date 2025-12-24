<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    
    public function get_statistics() {
        $stats = [];
        
        // Total siswa bermasalah (monitoring)
        $this->db->where('MONTH(tanggal)', date('m'));
        $this->db->where('YEAR(tanggal)', date('Y'));
        $stats['total_siswa_bermasalah'] = $this->db->count_all_results('monitoring_bk');
        
        // Total surat bulan ini
        $this->db->where('MONTH(tanggal_surat)', date('m'));
        $this->db->where('YEAR(tanggal_surat)', date('Y'));
        $stats['surat_bulan_ini'] = $this->db->count_all_results('surat_bk');
        
        return $stats;
    }
    
    public function get_siswa_alpha_3x() {
        $month = date('Y-m');
        
        $this->db->select('siswa.id, siswa.nis, siswa.nama_lengkap, kelas.nama_kelas, COUNT(*) as jumlah_alpha');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->where('absensi_harian.status', 'A');
        $this->db->where('DATE_FORMAT(absensi_harian.tanggal, "%Y-%m")', $month);
        $this->db->where('siswa.deleted_at IS NULL');
        $this->db->group_by('siswa.id');
        $this->db->having('COUNT(*) >= 3');
        $this->db->order_by('jumlah_alpha', 'DESC');
        $this->db->limit(10);
        
        return $this->db->get()->result();
    }
    
    public function get_siswa_terlambat_5x() {
        $month = date('Y-m');
        
        $this->db->select('siswa.id, siswa.nis, siswa.nama_lengkap, kelas.nama_kelas, COUNT(*) as jumlah_terlambat');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->where('absensi_harian.keterangan !=', 'Tepat Waktu');
        $this->db->where('absensi_harian.keterangan !=', '');
        $this->db->where('DATE_FORMAT(absensi_harian.tanggal, "%Y-%m")', $month);
        $this->db->where('siswa.deleted_at IS NULL');
        $this->db->group_by('siswa.id');
        $this->db->having('COUNT(*) >= 5');
        $this->db->order_by('jumlah_terlambat', 'DESC');
        $this->db->limit(10);
        
        return $this->db->get()->result();
    }
}

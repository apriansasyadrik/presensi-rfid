<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_model extends CI_Model {
    
    public function get_siswa_alpha_3x($month) {
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
        
        return $this->db->get()->result();
    }
    
    public function get_siswa_terlambat_5x($month) {
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
        
        return $this->db->get()->result();
    }
    
    public function add_monitoring($data) {
        return $this->db->insert('monitoring_bk', $data);
    }
    
    public function get_all_monitoring() {
        $this->db->select('monitoring_bk.*, siswa.nis, siswa.nama_lengkap, kelas.nama_kelas');
        $this->db->from('monitoring_bk');
        $this->db->join('siswa', 'siswa.id = monitoring_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->order_by('monitoring_bk.tanggal', 'DESC');
        
        return $this->db->get()->result();
    }
}

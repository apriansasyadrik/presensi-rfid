<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function count_total_siswa() {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('siswa');
    }

    public function count_total_guru() {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('guru');
    }

    public function count_absen_siswa_hari_ini() {
        $today = date('Y-m-d');
        $this->db->where('user_type', 'siswa');
        $this->db->where('tanggal', $today);
        $this->db->where('jam_masuk IS NOT NULL');
        return $this->db->count_all_results('absensi_harian');
    }

    public function count_absen_guru_hari_ini() {
        $today = date('Y-m-d');
        $this->db->where('user_type', 'guru');
        $this->db->where('tanggal', $today);
        $this->db->where('jam_masuk IS NOT NULL');
        return $this->db->count_all_results('absensi_harian');
    }

    public function get_recent_siswa_attendance($limit = 10) {
        $today = date('Y-m-d');
        $this->db->select('absensi_harian.*, siswa.nama_lengkap, siswa.nis, kelas.nama_kelas');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', 'siswa');
        $this->db->where('absensi_harian.tanggal', $today);
        $this->db->order_by('absensi_harian.jam_masuk', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_recent_guru_attendance($limit = 10) {
        $today = date('Y-m-d');
        $this->db->select('absensi_harian.*, guru.nama_lengkap, guru.nip');
        $this->db->from('absensi_harian');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id');
        $this->db->where('absensi_harian.user_type', 'guru');
        $this->db->where('absensi_harian.tanggal', $today);
        $this->db->order_by('absensi_harian.jam_masuk', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}

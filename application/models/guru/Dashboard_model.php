<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function get_jadwal_hari_ini($id_guru) {
        $hari = $this->get_indonesian_day();
        
        $this->db->select('jadwal_pelajaran.*, 
                          kelas.nama_kelas, kelas.tingkat, kelas.jurusan,
                          mata_pelajaran.nama_mapel, mata_pelajaran.kode_mapel');
        $this->db->from('jadwal_pelajaran');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->where('jadwal_pelajaran.id_guru', $id_guru);
        $this->db->where('jadwal_pelajaran.hari', $hari);
        $this->db->order_by('jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    public function get_jurnal_hari_ini($id_guru) {
        $today = date('Y-m-d');
        
        $this->db->select('jurnal.*, 
                          jadwal_pelajaran.hari, jadwal_pelajaran.jam_mulai,
                          kelas.nama_kelas,
                          mata_pelajaran.nama_mapel');
        $this->db->from('jurnal');
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.id_jadwal', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->where('jurnal.id_guru', $id_guru);
        $this->db->where('jurnal.tanggal', $today);
        $this->db->order_by('jurnal.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    public function count_mengajar($id_guru) {
        $this->db->where('id_guru', $id_guru);
        return $this->db->count_all_results('jadwal_pelajaran');
    }

    public function count_jurnal_bulan_ini($id_guru) {
        $this->db->where('id_guru', $id_guru);
        $this->db->where('MONTH(tanggal)', date('m'));
        $this->db->where('YEAR(tanggal)', date('Y'));
        return $this->db->count_all_results('jurnal');
    }

    private function get_indonesian_day() {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        $english_day = date('l');
        return $days[$english_day];
    }
}

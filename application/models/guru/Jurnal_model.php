<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model {

    private $table = 'jurnal';

    public function get_all_by_guru($id_guru) {
        $this->db->select('jurnal.*, 
                          jadwal_pelajaran.hari, jadwal_pelajaran.jam_mulai, jadwal_pelajaran.jam_selesai,
                          kelas.nama_kelas, kelas.tingkat, kelas.jurusan,
                          mata_pelajaran.nama_mapel, mata_pelajaran.kode_mapel');
        $this->db->from($this->table);
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.id_jadwal', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->where('jurnal.id_guru', $id_guru);
        $this->db->order_by('jurnal.tanggal', 'DESC');
        $this->db->order_by('jurnal.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('jurnal.*, 
                          jadwal_pelajaran.hari, jadwal_pelajaran.jam_mulai,
                          kelas.nama_kelas,
                          mata_pelajaran.nama_mapel');
        $this->db->from($this->table);
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.id_jadwal', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->where('jurnal.id', $id);
        
        return $this->db->get()->row();
    }

    public function get_absensi($id_jurnal) {
        $this->db->select('absensi_mapel.*, siswa.nama, siswa.nis');
        $this->db->from('absensi_mapel');
        $this->db->join('siswa', 'siswa.id = absensi_mapel.id_siswa', 'left');
        $this->db->where('absensi_mapel.id_jurnal', $id_jurnal);
        
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        // Delete related absensi first
        $this->db->where('id_jurnal', $id);
        $this->db->delete('absensi_mapel');
        
        // Delete jurnal
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function check_exists($id_jadwal, $tanggal) {
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->where('tanggal', $tanggal);
        return $this->db->get($this->table)->row();
    }

    public function get_rekap_by_kelas_mapel($id_kelas, $id_mata_pelajaran, $start_date, $end_date) {
        $this->db->select('jurnal.*, COUNT(absensi_mapel.id) as total_absensi');
        $this->db->from($this->table);
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.id_jadwal', 'left');
        $this->db->join('absensi_mapel', 'absensi_mapel.id_jurnal = jurnal.id', 'left');
        $this->db->where('jadwal_pelajaran.id_kelas', $id_kelas);
        $this->db->where('jadwal_pelajaran.id_mata_pelajaran', $id_mata_pelajaran);
        $this->db->where('jurnal.tanggal >=', $start_date);
        $this->db->where('jurnal.tanggal <=', $end_date);
        $this->db->group_by('jurnal.id');
        
        return $this->db->get()->result();
    }
}

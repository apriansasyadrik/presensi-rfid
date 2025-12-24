<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {

    protected $table = 'siswa';

    public function get_all() {
        $this->db->where('is_active', 1);
        $this->db->order_by('nama_lengkap', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_all_with_kelas() {
        $this->db->select('siswa.*, kelas.nama_kelas, kelas.tingkat');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.is_active', 1);
        $this->db->order_by('siswa.nama_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_nis($nis) {
        $this->db->where('nis', $nis);
        return $this->db->get($this->table)->row();
    }

    public function get_by_rfid($rfid_uid) {
        $this->db->where('rfid_uid', $rfid_uid);
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->row();
    }

    public function get_by_kelas($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        $this->db->order_by('nama_lengkap', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        // Soft delete
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['is_active' => 0]);
    }

    public function count_all() {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results($this->table);
    }

    public function count_by_kelas($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        return $this->db->count_all_results($this->table);
    }
}

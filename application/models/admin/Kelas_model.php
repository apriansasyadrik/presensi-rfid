<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    protected $table = 'kelas';

    public function get_all() {
        $this->db->order_by('tingkat', 'ASC');
        $this->db->order_by('nama_kelas', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_all_with_tahun() {
        $this->db->select('kelas.*, tahun_ajaran.tahun');
        $this->db->from($this->table);
        $this->db->join('tahun_ajaran', 'tahun_ajaran.id = kelas.tahun_ajaran_id');
        $this->db->order_by('kelas.tingkat', 'ASC');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_tahun_ajaran($tahun_ajaran_id) {
        $this->db->where('tahun_ajaran_id', $tahun_ajaran_id);
        $this->db->order_by('tingkat', 'ASC');
        $this->db->order_by('nama_kelas', 'ASC');
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
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function count_siswa($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('siswa');
    }
}

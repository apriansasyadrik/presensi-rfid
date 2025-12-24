<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mata_pelajaran_model extends CI_Model {

    protected $table = 'mata_pelajaran';

    public function get_all() {
        $this->db->order_by('nama_mapel', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_all_with_guru() {
        $this->db->select('mata_pelajaran.*, guru.nama_lengkap as nama_guru');
        $this->db->from($this->table);
        $this->db->join('guru', 'guru.id = mata_pelajaran.guru_id', 'left');
        $this->db->order_by('mata_pelajaran.nama_mapel', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_kode($kode_mapel) {
        $this->db->where('kode_mapel', $kode_mapel);
        return $this->db->get($this->table)->row();
    }

    public function get_by_guru($guru_id) {
        $this->db->where('guru_id', $guru_id);
        $this->db->order_by('nama_mapel', 'ASC');
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

    public function count_all() {
        return $this->db->count_all_results($this->table);
    }
}

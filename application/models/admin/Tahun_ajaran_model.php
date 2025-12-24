<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_ajaran_model extends CI_Model {

    protected $table = 'tahun_ajaran';

    public function get_all() {
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_active() {
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->row();
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

    public function deactivate_all() {
        return $this->db->update($this->table, ['is_active' => 0]);
    }
}

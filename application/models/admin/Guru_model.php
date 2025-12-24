<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru_model extends CI_Model {

    protected $table = 'guru';

    public function get_all() {
        $this->db->where('is_active', 1);
        $this->db->order_by('nama_lengkap', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_all_with_user() {
        $this->db->select('guru.*, users.username, users.role');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = guru.user_id');
        $this->db->where('guru.is_active', 1);
        $this->db->order_by('guru.nama_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id_with_user($id) {
        $this->db->select('guru.*, users.username, users.role');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = guru.user_id');
        $this->db->where('guru.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_nip($nip) {
        $this->db->where('nip', $nip);
        return $this->db->get($this->table)->row();
    }

    public function get_by_rfid($rfid_uid) {
        $this->db->where('rfid_uid', $rfid_uid);
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->row();
    }

    public function get_by_user_id($user_id) {
        $this->db->where('user_id', $user_id);
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
        // Soft delete
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['is_active' => 0]);
    }

    public function count_all() {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results($this->table);
    }
}

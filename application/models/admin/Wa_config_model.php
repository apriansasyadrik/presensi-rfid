<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wa_config_model extends CI_Model {

    public function get_config() {
        return $this->db->get('wa_config')->row();
    }

    public function update_config($data) {
        // Check if config exists
        $existing = $this->db->get('wa_config')->row();
        
        if ($existing) {
            return $this->db->update('wa_config', $data);
        } else {
            return $this->db->insert('wa_config', $data);
        }
    }

    public function get_templates() {
        return $this->db->get('wa_template')->result();
    }

    public function get_template($id) {
        return $this->db->get_where('wa_template', ['id' => $id])->row();
    }

    public function get_template_by_type($type) {
        return $this->db->get_where('wa_template', ['template_type' => $type])->row();
    }

    public function insert_template($data) {
        return $this->db->insert('wa_template', $data);
    }

    public function update_template($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('wa_template', $data);
    }

    public function delete_template($id) {
        $this->db->where('id', $id);
        return $this->db->delete('wa_template');
    }

    public function get_notif_kelas() {
        $this->db->select('wa_notifikasi_kelas.*, kelas.nama_kelas, kelas.tingkat, kelas.jurusan');
        $this->db->from('wa_notifikasi_kelas');
        $this->db->join('kelas', 'kelas.id = wa_notifikasi_kelas.id_kelas', 'left');
        return $this->db->get()->result();
    }

    public function is_kelas_active($id_kelas) {
        $result = $this->db->get_where('wa_notifikasi_kelas', ['id_kelas' => $id_kelas])->row();
        return $result ? true : false;
    }

    public function add_notif_kelas($id_kelas) {
        // Check if already exists
        $existing = $this->db->get_where('wa_notifikasi_kelas', ['id_kelas' => $id_kelas])->row();
        if ($existing) {
            return true;
        }
        
        return $this->db->insert('wa_notifikasi_kelas', [
            'id_kelas' => $id_kelas,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function remove_notif_kelas($id_kelas) {
        $this->db->where('id_kelas', $id_kelas);
        return $this->db->delete('wa_notifikasi_kelas');
    }

    public function get_active_kelas_ids() {
        $this->db->select('id_kelas');
        $result = $this->db->get('wa_notifikasi_kelas')->result();
        return array_column($result, 'id_kelas');
    }
}

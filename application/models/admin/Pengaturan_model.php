<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

    public function get_pengaturan() {
        $query = $this->db->get('pengaturan');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        
        // If no settings exist, create default
        $default = [
            'nama_sekolah' => 'Nama Sekolah',
            'alamat_sekolah' => 'Alamat Sekolah',
            'nama_kepala_sekolah' => 'Nama Kepala Sekolah'
        ];
        $this->db->insert('pengaturan', $default);
        return (object) $default;
    }

    public function update_pengaturan($data) {
        $pengaturan = $this->get_pengaturan();
        
        if ($pengaturan) {
            $this->db->where('id', $pengaturan->id);
            return $this->db->update('pengaturan', $data);
        }
        
        return $this->db->insert('pengaturan', $data);
    }

    public function get_jam_kerja() {
        $query = $this->db->get('jam_kerja');
        return $query->row();
    }

    public function update_jam_kerja($data) {
        $jam_kerja = $this->get_jam_kerja();
        
        if ($jam_kerja) {
            $this->db->where('id', $jam_kerja->id);
            return $this->db->update('jam_kerja', $data);
        }
        
        return $this->db->insert('jam_kerja', $data);
    }

    public function get_all_hari_kerja() {
        return $this->db->get('hari_kerja')->result();
    }

    public function update_hari_kerja($hari, $is_aktif) {
        $this->db->where('hari', $hari);
        return $this->db->update('hari_kerja', ['is_aktif' => $is_aktif]);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_model extends CI_Model {
    
    public function get_all_surat() {
        $this->db->select('surat_bk.*, siswa.nis, siswa.nama_lengkap, kelas.nama_kelas');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->order_by('surat_bk.tanggal_surat', 'DESC');
        
        return $this->db->get()->result();
    }
    
    public function get_surat_by_id($id) {
        $this->db->select('surat_bk.*, siswa.nis, siswa.nama_lengkap, siswa.tempat_lahir, siswa.tanggal_lahir, kelas.nama_kelas, users.nama_lengkap as nama_bk');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->join('users', 'users.id = surat_bk.petugas_bk_id');
        $this->db->where('surat_bk.id', $id);
        
        return $this->db->get()->row();
    }
    
    public function save_surat($data) {
        return $this->db->insert('surat_bk', $data);
    }
    
    public function update_surat($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('surat_bk', $data);
    }
    
    public function delete_surat($id) {
        $this->db->where('id', $id);
        return $this->db->delete('surat_bk');
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_pelajaran_model extends CI_Model {

    private $table = 'jadwal_pelajaran';

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_all_with_details() {
        $this->db->select('jadwal_pelajaran.*, 
                          kelas.nama_kelas, kelas.tingkat, kelas.jurusan,
                          mata_pelajaran.nama_mapel, mata_pelajaran.kode_mapel,
                          guru.nama as nama_guru');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.id_guru', 'left');
        $this->db->order_by('hari', 'ASC');
        $this->db->order_by('jam_mulai', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_kelas($id_kelas) {
        $this->db->select('jadwal_pelajaran.*, 
                          mata_pelajaran.nama_mapel, mata_pelajaran.kode_mapel,
                          guru.nama as nama_guru');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.id_guru', 'left');
        $this->db->where('jadwal_pelajaran.id_kelas', $id_kelas);
        $this->db->order_by('hari', 'ASC');
        $this->db->order_by('jam_mulai', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_guru($id_guru) {
        $this->db->select('jadwal_pelajaran.*, 
                          kelas.nama_kelas, kelas.tingkat, kelas.jurusan,
                          mata_pelajaran.nama_mapel, mata_pelajaran.kode_mapel');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->where('jadwal_pelajaran.id_guru', $id_guru);
        $this->db->order_by('hari', 'ASC');
        $this->db->order_by('jam_mulai', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_hari($hari) {
        $this->db->select('jadwal_pelajaran.*, 
                          kelas.nama_kelas, 
                          mata_pelajaran.nama_mapel,
                          guru.nama as nama_guru');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.id_kelas', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.id_mata_pelajaran', 'left');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.id_guru', 'left');
        $this->db->where('jadwal_pelajaran.hari', $hari);
        $this->db->order_by('jam_mulai', 'ASC');
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
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function check_conflict($id_kelas, $hari, $jam_mulai, $jam_selesai, $exclude_id = null) {
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('hari', $hari);
        $this->db->group_start();
            $this->db->where("(jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai')");
            $this->db->or_where("(jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai')");
            $this->db->or_where("(jam_mulai >= '$jam_mulai' AND jam_selesai <= '$jam_selesai')");
        $this->db->group_end();
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->get($this->table)->num_rows() > 0;
    }
}

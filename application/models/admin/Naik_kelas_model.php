<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naik_kelas_model extends CI_Model {

    /**
     * Get students by tingkat and tahun ajaran
     */
    public function get_students_by_tingkat($tahun_ajaran_id, $tingkat) {
        return $this->db
            ->select('siswa.*, kelas.nama_kelas, kelas.tingkat, kelas.jurusan')
            ->join('kelas', 'kelas.id = siswa.kelas_id')
            ->where('kelas.tahun_ajaran_id', $tahun_ajaran_id)
            ->where('kelas.tingkat', $tingkat)
            ->where('siswa.deleted_at IS NULL')
            ->order_by('siswa.nama_lengkap', 'ASC')
            ->get('siswa')
            ->result();
    }

    /**
     * Get student count by tingkat
     */
    public function count_students_by_tingkat($tahun_ajaran_id, $tingkat) {
        return $this->db
            ->join('kelas', 'kelas.id = siswa.kelas_id')
            ->where('kelas.tahun_ajaran_id', $tahun_ajaran_id)
            ->where('kelas.tingkat', $tingkat)
            ->where('siswa.deleted_at IS NULL')
            ->count_all_results('siswa');
    }

    /**
     * Promote students to new class
     */
    public function promote_students($student_ids, $new_class_id) {
        $this->db->where_in('id', $student_ids);
        return $this->db->update('siswa', ['kelas_id' => $new_class_id]);
    }
}

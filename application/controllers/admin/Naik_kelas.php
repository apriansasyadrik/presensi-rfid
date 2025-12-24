<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naik_kelas extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Naik_kelas_model');
    }

    public function index() {
        $data['title'] = 'Naik Kelas';
        $data['tahun_ajaran'] = $this->db->order_by('tahun_awal', 'DESC')->get('tahun_ajaran')->result();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/naik_kelas', $data);
        $this->load->view('admin/templates/footer');
    }

    /**
     * Preview students who will be promoted
     */
    public function preview() {
        $tahun_ajaran_id = $this->input->post('tahun_ajaran_id');
        $tingkat = $this->input->post('tingkat');
        
        if (!$tahun_ajaran_id || !$tingkat) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }
        
        $students = $this->Naik_kelas_model->get_students_by_tingkat($tahun_ajaran_id, $tingkat);
        
        echo json_encode([
            'success' => true,
            'data' => $students,
            'count' => count($students)
        ]);
    }

    /**
     * Process mass class promotion
     */
    public function process() {
        $tahun_ajaran_asal = $this->input->post('tahun_ajaran_asal');
        $tahun_ajaran_tujuan = $this->input->post('tahun_ajaran_tujuan');
        $tingkat_asal = $this->input->post('tingkat_asal');
        $tingkat_tujuan = $this->input->post('tingkat_tujuan');
        $jurusan_tujuan = $this->input->post('jurusan_tujuan');
        
        // Validation
        if (!$tahun_ajaran_asal || !$tahun_ajaran_tujuan || !$tingkat_asal || !$tingkat_tujuan) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }
        
        // Get target class
        $target_class = $this->db
            ->where('tahun_ajaran_id', $tahun_ajaran_tujuan)
            ->where('tingkat', $tingkat_tujuan)
            ->where('jurusan', $jurusan_tujuan)
            ->get('kelas')
            ->row();
        
        if (!$target_class) {
            echo json_encode(['success' => false, 'message' => 'Kelas tujuan tidak ditemukan']);
            return;
        }
        
        // Get students to promote
        $students = $this->Naik_kelas_model->get_students_by_tingkat($tahun_ajaran_asal, $tingkat_asal);
        
        if (empty($students)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada siswa yang akan dinaikan']);
            return;
        }
        
        // Process promotion
        $this->db->trans_start();
        
        $promoted = 0;
        foreach ($students as $student) {
            $update = $this->db
                ->where('id', $student->id)
                ->update('siswa', ['kelas_id' => $target_class->id]);
            
            if ($update) {
                $promoted++;
            }
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['success' => false, 'message' => 'Gagal melakukan naik kelas']);
        } else {
            echo json_encode([
                'success' => true,
                'message' => "Berhasil menaikan $promoted siswa",
                'promoted' => $promoted
            ]);
        }
    }

    /**
     * Get classes by tingkat and tahun ajaran
     */
    public function get_kelas() {
        $tahun_ajaran_id = $this->input->get('tahun_ajaran_id');
        $tingkat = $this->input->get('tingkat');
        
        $kelas = $this->db
            ->where('tahun_ajaran_id', $tahun_ajaran_id)
            ->where('tingkat', $tingkat)
            ->get('kelas')
            ->result();
        
        echo json_encode($kelas);
    }
}

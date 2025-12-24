<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semester extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Semester_model');
        $this->load->model('admin/Tahun_ajaran_model');
    }

    public function index() {
        $data['title'] = 'Data Semester';
        $data['semester'] = $this->Semester_model->get_all_with_tahun();
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        $this->load_template('admin/semester', $data);
    }

    public function add() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required|trim');
        $this->form_validation->set_rules('semester', 'Semester', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
            'semester' => $this->input->post('semester'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        // If set as active, deactivate others
        if ($data['is_active']) {
            $this->Semester_model->deactivate_all();
        }

        if ($this->Semester_model->insert($data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Semester berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan semester'
            ]);
        }
    }

    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required|trim');
        $this->form_validation->set_rules('semester', 'Semester', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
            'semester' => $this->input->post('semester'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        // If set as active, deactivate others
        if ($data['is_active']) {
            $this->Semester_model->deactivate_all();
        }

        if ($this->Semester_model->update($id, $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Semester berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui semester'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        if ($this->Semester_model->delete($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Semester berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus semester'
            ]);
        }
    }

    public function get($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = $this->Semester_model->get_by_id($id);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}

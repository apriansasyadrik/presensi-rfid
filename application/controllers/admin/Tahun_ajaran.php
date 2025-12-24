<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_ajaran extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Tahun_ajaran_model');
    }

    public function index() {
        $data['title'] = 'Data Tahun Ajaran';
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        $this->load_template('admin/tahun_ajaran', $data);
    }

    public function add() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'tahun' => $this->input->post('tahun'),
            'keterangan' => $this->input->post('keterangan'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        // If set as active, deactivate others
        if ($data['is_active']) {
            $this->Tahun_ajaran_model->deactivate_all();
        }

        if ($this->Tahun_ajaran_model->insert($data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Tahun ajaran berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan tahun ajaran'
            ]);
        }
    }

    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'tahun' => $this->input->post('tahun'),
            'keterangan' => $this->input->post('keterangan'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        // If set as active, deactivate others
        if ($data['is_active']) {
            $this->Tahun_ajaran_model->deactivate_all();
        }

        if ($this->Tahun_ajaran_model->update($id, $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Tahun ajaran berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui tahun ajaran'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        if ($this->Tahun_ajaran_model->delete($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Tahun ajaran berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus tahun ajaran'
            ]);
        }
    }

    public function get($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = $this->Tahun_ajaran_model->get_by_id($id);
        
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

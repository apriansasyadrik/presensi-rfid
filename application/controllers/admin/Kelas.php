<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Kelas_model');
        $this->load->model('admin/Tahun_ajaran_model');
    }

    public function index() {
        $data['title'] = 'Data Kelas';
        $data['kelas'] = $this->Kelas_model->get_all_with_tahun();
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        $this->load_template('admin/kelas', $data);
    }

    public function add() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim');
        $this->form_validation->set_rules('tingkat', 'Tingkat', 'required|numeric');
        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'nama_kelas' => $this->input->post('nama_kelas'),
            'tingkat' => $this->input->post('tingkat'),
            'jurusan' => $this->input->post('jurusan'),
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id')
        ];

        if ($this->Kelas_model->insert($data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan kelas'
            ]);
        }
    }

    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required|trim');
        $this->form_validation->set_rules('tingkat', 'Tingkat', 'required|numeric');
        $this->form_validation->set_rules('tahun_ajaran_id', 'Tahun Ajaran', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'nama_kelas' => $this->input->post('nama_kelas'),
            'tingkat' => $this->input->post('tingkat'),
            'jurusan' => $this->input->post('jurusan'),
            'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id')
        ];

        if ($this->Kelas_model->update($id, $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Kelas berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui kelas'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        if ($this->Kelas_model->delete($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Kelas berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus kelas'
            ]);
        }
    }

    public function get($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = $this->Kelas_model->get_by_id($id);
        
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

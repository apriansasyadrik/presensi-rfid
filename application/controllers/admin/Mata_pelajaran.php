<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mata_pelajaran extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Mata_pelajaran_model');
        $this->load->model('admin/Guru_model');
    }

    public function index() {
        $data['title'] = 'Mata Pelajaran';
        $data['mata_pelajaran'] = $this->Mata_pelajaran_model->get_all_with_guru();
        $data['guru'] = $this->Guru_model->get_all();
        $this->load_template('admin/mata_pelajaran', $data);
    }

    public function add() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|trim');
        $this->form_validation->set_rules('kode_mapel', 'Kode Mata Pelajaran', 'required|trim|is_unique[mata_pelajaran.kode_mapel]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'kode_mapel' => $this->input->post('kode_mapel'),
            'nama_mapel' => $this->input->post('nama_mapel'),
            'guru_id' => $this->input->post('guru_id') ?: NULL,
            'kkm' => $this->input->post('kkm'),
            'deskripsi' => $this->input->post('deskripsi')
        ];

        if ($this->Mata_pelajaran_model->insert($data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Mata pelajaran berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan mata pelajaran'
            ]);
        }
    }

    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required|trim');
        $this->form_validation->set_rules('kode_mapel', 'Kode Mata Pelajaran', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        // Check kode_mapel uniqueness
        $this->db->where('kode_mapel', $this->input->post('kode_mapel'));
        $this->db->where('id !=', $id);
        if ($this->db->count_all_results('mata_pelajaran') > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Kode mata pelajaran sudah digunakan'
            ]);
            return;
        }

        $data = [
            'kode_mapel' => $this->input->post('kode_mapel'),
            'nama_mapel' => $this->input->post('nama_mapel'),
            'guru_id' => $this->input->post('guru_id') ?: NULL,
            'kkm' => $this->input->post('kkm'),
            'deskripsi' => $this->input->post('deskripsi')
        ];

        if ($this->Mata_pelajaran_model->update($id, $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Mata pelajaran berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui mata pelajaran'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        if ($this->Mata_pelajaran_model->delete($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus mata pelajaran'
            ]);
        }
    }

    public function get($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = $this->Mata_pelajaran_model->get_by_id($id);
        
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

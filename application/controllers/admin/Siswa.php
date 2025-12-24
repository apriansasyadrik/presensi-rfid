<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Siswa_model');
        $this->load->model('admin/Kelas_model');
    }

    public function index() {
        $data['title'] = 'Data Siswa';
        $data['siswa'] = $this->Siswa_model->get_all_with_kelas();
        $data['kelas'] = $this->Kelas_model->get_all();
        $this->load_template('admin/siswa', $data);
    }

    public function add() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('nis', 'NIS', 'required|trim|is_unique[siswa.nis]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'nis' => $this->input->post('nis'),
            'nisn' => $this->input->post('nisn'),
            'rfid_uid' => $this->input->post('rfid_uid'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'kelas_id' => $this->input->post('kelas_id') ?: NULL,
            'no_telepon_ortu' => $this->input->post('no_telepon_ortu'),
            'nama_ortu' => $this->input->post('nama_ortu'),
            'is_active' => 1
        ];

        if ($this->Siswa_model->insert($data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Data siswa berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan data siswa'
            ]);
        }
    }

    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $siswa = $this->Siswa_model->get_by_id($id);
        if (!$siswa) {
            echo json_encode([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ]);
            return;
        }

        $this->form_validation->set_rules('nis', 'NIS', 'required|trim');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        // Check NIS uniqueness (except current siswa)
        $this->db->where('nis', $this->input->post('nis'));
        $this->db->where('id !=', $id);
        if ($this->db->count_all_results('siswa') > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'NIS sudah digunakan oleh siswa lain'
            ]);
            return;
        }

        $data = [
            'nis' => $this->input->post('nis'),
            'nisn' => $this->input->post('nisn'),
            'rfid_uid' => $this->input->post('rfid_uid'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'kelas_id' => $this->input->post('kelas_id') ?: NULL,
            'no_telepon_ortu' => $this->input->post('no_telepon_ortu'),
            'nama_ortu' => $this->input->post('nama_ortu')
        ];

        if ($this->Siswa_model->update($id, $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Data siswa berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui data siswa'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        if ($this->Siswa_model->delete($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus data siswa'
            ]);
        }
    }

    public function get($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = $this->Siswa_model->get_by_id($id);
        
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

    public function export() {
        // Export will be implemented when PhpSpreadsheet is installed
        $this->session->set_flashdata('error', 'Fitur export Excel memerlukan library PhpSpreadsheet. Silakan install dengan: composer require phpoffice/phpspreadsheet');
        redirect('admin/siswa');
    }

    public function import() {
        // Import will be implemented when PhpSpreadsheet is installed
        $this->session->set_flashdata('error', 'Fitur import Excel memerlukan library PhpSpreadsheet. Silakan install dengan: composer require phpoffice/phpspreadsheet');
        redirect('admin/siswa');
    }
}

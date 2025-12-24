<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Guru_model');
    }

    public function index() {
        $data['title'] = 'Data Guru';
        $data['guru'] = $this->Guru_model->get_all_with_user();
        $this->load_template('admin/guru', $data);
    }

    public function add() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nip', 'NIP', 'required|trim|is_unique[guru.nip]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $this->db->trans_start();

        // Insert user
        $user_data = [
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'email' => $this->input->post('email'),
            'role' => 'guru',
            'is_active' => 1
        ];
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        // Insert guru
        $guru_data = [
            'user_id' => $user_id,
            'nip' => $this->input->post('nip'),
            'rfid_uid' => $this->input->post('rfid_uid'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'no_telepon' => $this->input->post('no_telepon'),
            'email' => $this->input->post('email'),
            'is_active' => 1
        ];
        $this->db->insert('guru', $guru_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan data guru'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Data guru berhasil ditambahkan'
            ]);
        }
    }

    public function edit($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $guru = $this->Guru_model->get_by_id($id);
        if (!$guru) {
            echo json_encode([
                'success' => false,
                'message' => 'Data guru tidak ditemukan'
            ]);
            return;
        }

        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        // Check NIP uniqueness (except current guru)
        $this->db->where('nip', $this->input->post('nip'));
        $this->db->where('id !=', $id);
        if ($this->db->count_all_results('guru') > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'NIP sudah digunakan oleh guru lain'
            ]);
            return;
        }

        $this->db->trans_start();

        // Update user
        $user_data = [
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'email' => $this->input->post('email')
        ];
        
        // Update password if provided
        if ($this->input->post('password')) {
            $user_data['password'] = md5($this->input->post('password'));
        }
        
        $this->db->where('id', $guru->user_id);
        $this->db->update('users', $user_data);

        // Update guru
        $guru_data = [
            'nip' => $this->input->post('nip'),
            'rfid_uid' => $this->input->post('rfid_uid'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'no_telepon' => $this->input->post('no_telepon'),
            'email' => $this->input->post('email')
        ];
        $this->db->where('id', $id);
        $this->db->update('guru', $guru_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui data guru'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Data guru berhasil diperbarui'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $guru = $this->Guru_model->get_by_id($id);
        if (!$guru) {
            echo json_encode([
                'success' => false,
                'message' => 'Data guru tidak ditemukan'
            ]);
            return;
        }

        $this->db->trans_start();

        // Soft delete guru
        $this->db->where('id', $id);
        $this->db->update('guru', ['is_active' => 0]);

        // Soft delete user
        $this->db->where('id', $guru->user_id);
        $this->db->update('users', ['is_active' => 0]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus data guru'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Data guru berhasil dihapus'
            ]);
        }
    }

    public function get($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $data = $this->Guru_model->get_by_id_with_user($id);
        
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
        $this->session->set_flashdata('error', 'Fitur export Excel memerlukan library PhpSpreadsheet');
        redirect('admin/guru');
    }

    public function import() {
        // Import will be implemented when PhpSpreadsheet is installed
        $this->session->set_flashdata('error', 'Fitur import Excel memerlukan library PhpSpreadsheet');
        redirect('admin/guru');
    }
}

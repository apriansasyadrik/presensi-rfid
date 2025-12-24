<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
        $this->check_role(['Guru', 'Guru Wali Kelas', 'Guru Piket']);
        $this->load->model('guru/Profile_model');
    }
    
    public function index() {
        $data['title'] = 'Profile';
        $data['guru'] = $this->Profile_model->get_guru_profile($this->session->userdata('user_id'));
        
        $this->load->view('guru/templates/header', $data);
        $this->load->view('guru/templates/sidebar', $data);
        $this->load->view('guru/profile', $data);
        $this->load->view('guru/templates/footer');
    }
    
    public function update() {
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/profile');
        }
        
        $guru_id = $this->Profile_model->get_guru_id_by_user($this->session->userdata('user_id'));
        
        $data = [
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_telp' => $this->input->post('no_telp'),
            'alamat' => $this->input->post('alamat')
        ];
        
        if ($this->Profile_model->update_profile($guru_id, $data)) {
            $this->session->set_flashdata('success', 'Profile berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profile');
        }
        
        redirect('guru/profile');
    }
    
    public function change_password() {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/profile');
        }
        
        $user_id = $this->session->userdata('user_id');
        $password_lama = md5($this->input->post('password_lama'));
        
        // Verify old password
        if (!$this->Profile_model->verify_password($user_id, $password_lama)) {
            $this->session->set_flashdata('error', 'Password lama tidak sesuai');
            redirect('guru/profile');
        }
        
        $password_baru = md5($this->input->post('password_baru'));
        
        if ($this->Profile_model->update_password($user_id, $password_baru)) {
            $this->session->set_flashdata('success', 'Password berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password');
        }
        
        redirect('guru/profile');
    }
}

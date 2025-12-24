<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
        $this->check_role(['BK']);
        $this->load->model('bk/Profile_model');
    }
    
    public function index() {
        $data['title'] = 'Profile';
        $data['user'] = $this->Profile_model->get_user_profile($this->session->userdata('user_id'));
        
        $this->load->view('bk/templates/header', $data);
        $this->load->view('bk/templates/sidebar', $data);
        $this->load->view('bk/profile', $data);
        $this->load->view('bk/templates/footer');
    }
    
    public function update() {
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/profile');
        }
        
        $user_id = $this->session->userdata('user_id');
        
        $data = [
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'email' => $this->input->post('email')
        ];
        
        if ($this->Profile_model->update_profile($user_id, $data)) {
            $this->session->set_flashdata('success', 'Profile berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profile');
        }
        
        redirect('bk/profile');
    }
    
    public function change_password() {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/profile');
        }
        
        $user_id = $this->session->userdata('user_id');
        $password_lama = md5($this->input->post('password_lama'));
        
        if (!$this->Profile_model->verify_password($user_id, $password_lama)) {
            $this->session->set_flashdata('error', 'Password lama tidak sesuai');
            redirect('bk/profile');
        }
        
        $password_baru = md5($this->input->post('password_baru'));
        
        if ($this->Profile_model->update_password($user_id, $password_baru)) {
            $this->session->set_flashdata('success', 'Password berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password');
        }
        
        redirect('bk/profile');
    }
}

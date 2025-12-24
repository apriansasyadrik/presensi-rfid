<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function login() {
        // Check if already logged in
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            $this->_redirect_by_role($role);
            return;
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == TRUE) {
                $username = $this->input->post('username');
                $password = $this->input->post('password');

                $user = $this->Auth_model->verify_login($username, $password);

                if ($user) {
                    // Set session data
                    $session_data = array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'nama_lengkap' => $user->nama_lengkap,
                        'email' => $user->email,
                        'role' => $user->role,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);

                    // Redirect based on role
                    $this->_redirect_by_role($user->role);
                } else {
                    $this->session->set_flashdata('error', 'Username atau password salah!');
                    redirect('auth/login');
                }
            }
        }

        $this->load->view('auth/login');
    }

    public function logout() {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('nama_lengkap');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        
        $this->session->set_flashdata('success', 'Anda telah berhasil logout.');
        redirect('auth/login');
    }

    private function _redirect_by_role($role) {
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'guru':
            case 'guru_wali_kelas':
            case 'guru_piket':
                redirect('guru/dashboard');
                break;
            case 'bk':
                redirect('bk/dashboard');
                break;
            default:
                redirect('auth/login');
                break;
        }
    }
}

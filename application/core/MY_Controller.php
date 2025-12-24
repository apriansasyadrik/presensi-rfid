<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $allowed_roles = array();

    public function __construct() {
        parent::__construct();
        $this->_check_auth();
    }

    private function _check_auth() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu!');
            redirect('auth/login');
        }

        // Check role access
        if (!empty($this->allowed_roles)) {
            $user_role = $this->session->userdata('role');
            if (!in_array($user_role, $this->allowed_roles)) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
                redirect('auth/login');
            }
        }
    }

    protected function check_role($required_roles) {
        $user_role = $this->session->userdata('role');
        if (is_array($required_roles)) {
            return in_array($user_role, $required_roles);
        }
        return $user_role == $required_roles;
    }

    protected function load_template($view, $data = array()) {
        $role = $this->session->userdata('role');
        $data['user_role'] = $role;
        $data['user_name'] = $this->session->userdata('nama_lengkap');
        
        // Load different templates based on role
        switch ($role) {
            case 'admin':
                $this->load->view('admin/templates/header', $data);
                $this->load->view('admin/templates/sidebar', $data);
                $this->load->view($view, $data);
                $this->load->view('admin/templates/footer', $data);
                break;
            case 'guru':
            case 'guru_wali_kelas':
            case 'guru_piket':
                $this->load->view('guru/templates/header', $data);
                $this->load->view('guru/templates/sidebar', $data);
                $this->load->view($view, $data);
                $this->load->view('guru/templates/footer', $data);
                break;
            case 'bk':
                $this->load->view('bk/templates/header', $data);
                $this->load->view('bk/templates/sidebar', $data);
                $this->load->view($view, $data);
                $this->load->view('bk/templates/footer', $data);
                break;
            default:
                redirect('auth/login');
                break;
        }
    }
}

/**
 * Admin Controller - for Admin role only
 */
class Admin_Controller extends MY_Controller {
    protected $allowed_roles = array('admin');
}

/**
 * Guru Controller - for Teacher roles
 */
class Guru_Controller extends MY_Controller {
    protected $allowed_roles = array('guru', 'guru_wali_kelas', 'guru_piket');
}

/**
 * BK Controller - for BK (Bimbingan Konseling) role
 */
class BK_Controller extends MY_Controller {
    protected $allowed_roles = array('bk');
}

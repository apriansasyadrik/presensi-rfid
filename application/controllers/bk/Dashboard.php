<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
        $this->check_role(['BK']);
        $this->load->model('bk/Dashboard_model');
    }
    
    public function index() {
        $data['title'] = 'Dashboard BK';
        $data['stats'] = $this->Dashboard_model->get_statistics();
        $data['siswa_alpha'] = $this->Dashboard_model->get_siswa_alpha_3x();
        $data['siswa_terlambat'] = $this->Dashboard_model->get_siswa_terlambat_5x();
        
        $this->load->view('bk/templates/header', $data);
        $this->load->view('bk/templates/sidebar', $data);
        $this->load->view('bk/dashboard', $data);
        $this->load->view('bk/templates/footer');
    }
}

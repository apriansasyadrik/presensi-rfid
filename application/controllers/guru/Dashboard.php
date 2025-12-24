<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Guru', 'Guru Wali Kelas', 'Guru Piket']);
        $this->load->model('guru/Dashboard_model');
    }

    public function index() {
        $id_guru = $this->session->userdata('user_id'); // Assuming guru has user_id
        
        // Get guru data
        $this->load->model('admin/Guru_model');
        $guru = $this->Guru_model->get_by_user_id($id_guru);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('auth/logout');
        }

        $data['title'] = 'Dashboard Guru';
        $data['guru'] = $guru;
        
        // Get today's schedule
        $data['jadwal_hari_ini'] = $this->Dashboard_model->get_jadwal_hari_ini($guru->id);
        
        // Get today's journals
        $data['jurnal_hari_ini'] = $this->Dashboard_model->get_jurnal_hari_ini($guru->id);
        
        // Get statistics
        $data['total_mengajar'] = $this->Dashboard_model->count_mengajar($guru->id);
        $data['jurnal_bulan_ini'] = $this->Dashboard_model->count_jurnal_bulan_ini($guru->id);
        
        $this->load->view('guru/templates/header', $data);
        $this->load->view('guru/templates/sidebar');
        $this->load->view('guru/dashboard', $data);
        $this->load->view('guru/templates/footer');
    }
}

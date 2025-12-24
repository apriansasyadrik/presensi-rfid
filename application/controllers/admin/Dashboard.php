<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Dashboard_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';
        
        // Get statistics
        $data['total_siswa'] = $this->Dashboard_model->count_total_siswa();
        $data['total_guru'] = $this->Dashboard_model->count_total_guru();
        $data['absen_siswa_hari_ini'] = $this->Dashboard_model->count_absen_siswa_hari_ini();
        $data['absen_guru_hari_ini'] = $this->Dashboard_model->count_absen_guru_hari_ini();
        
        // Get recent attendance data
        $data['recent_siswa'] = $this->Dashboard_model->get_recent_siswa_attendance(10);
        $data['recent_guru'] = $this->Dashboard_model->get_recent_guru_attendance(10);

        $this->load_template('admin/dashboard', $data);
    }
}

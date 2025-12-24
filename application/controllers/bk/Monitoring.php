<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
        $this->check_role(['BK']);
        $this->load->model('bk/Monitoring_model');
    }
    
    public function index() {
        $data['title'] = 'Monitoring Siswa';
        
        // Filter by month
        $month = $this->input->get('month') ?: date('Y-m');
        $data['month'] = $month;
        
        $data['siswa_alpha'] = $this->Monitoring_model->get_siswa_alpha_3x($month);
        $data['siswa_terlambat'] = $this->Monitoring_model->get_siswa_terlambat_5x($month);
        
        $this->load->view('bk/templates/header', $data);
        $this->load->view('bk/templates/sidebar', $data);
        $this->load->view('bk/monitoring', $data);
        $this->load->view('bk/templates/footer');
    }
    
    public function add_to_monitoring() {
        $siswa_id = $this->input->post('siswa_id');
        $jenis = $this->input->post('jenis'); // 'alpha' or 'terlambat'
        $keterangan = $this->input->post('keterangan');
        
        $data = [
            'siswa_id' => $siswa_id,
            'jenis' => $jenis,
            'keterangan' => $keterangan,
            'tanggal' => date('Y-m-d'),
            'petugas_bk_id' => $this->session->userdata('user_id')
        ];
        
        if ($this->Monitoring_model->add_monitoring($data)) {
            $this->session->set_flashdata('success', 'Berhasil menambahkan ke monitoring');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan ke monitoring');
        }
        
        redirect('bk/monitoring');
    }
}

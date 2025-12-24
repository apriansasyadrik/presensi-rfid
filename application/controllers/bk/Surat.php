<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
        $this->check_role(['BK']);
        $this->load->model('bk/Surat_model');
        $this->load->model('admin/Siswa_model');
    }
    
    public function index() {
        $data['title'] = 'Cetak Surat Pemanggilan';
        $data['surat_list'] = $this->Surat_model->get_all_surat();
        $data['siswa_list'] = $this->Siswa_model->get_all();
        
        $this->load->view('bk/templates/header', $data);
        $this->load->view('bk/templates/sidebar', $data);
        $this->load->view('bk/surat', $data);
        $this->load->view('bk/templates/footer');
    }
    
    public function save() {
        $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('tanggal_surat', 'Tanggal Surat', 'required');
        $this->form_validation->set_rules('waktu_panggil', 'Waktu Panggilan', 'required');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/surat');
        }
        
        $data = [
            'nomor_surat' => $this->input->post('nomor_surat'),
            'siswa_id' => $this->input->post('siswa_id'),
            'tanggal_surat' => $this->input->post('tanggal_surat'),
            'waktu_panggil' => $this->input->post('waktu_panggil'),
            'perihal' => $this->input->post('perihal'),
            'isi_surat' => $this->input->post('isi_surat'),
            'petugas_bk_id' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->Surat_model->save_surat($data)) {
            $this->session->set_flashdata('success', 'Surat berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan surat');
        }
        
        redirect('bk/surat');
    }
    
    public function preview($id) {
        $data['surat'] = $this->Surat_model->get_surat_by_id($id);
        $this->load->view('bk/surat_preview', $data);
    }
}

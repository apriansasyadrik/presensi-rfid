<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_hari_kerja extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Pengaturan_model');
    }

    public function index() {
        $data['title'] = 'Pengaturan Hari Kerja';
        $data['hari_kerja'] = $this->Pengaturan_model->get_all_hari_kerja();
        $data['jam_kerja'] = $this->Pengaturan_model->get_jam_kerja();

        if ($this->input->method() == 'post') {
            // Update jam kerja
            if ($this->input->post('action') == 'update_jam') {
                $this->form_validation->set_rules('jam_masuk', 'Jam Masuk', 'required');
                $this->form_validation->set_rules('jam_pulang', 'Jam Pulang', 'required');
                $this->form_validation->set_rules('toleransi_terlambat', 'Toleransi Terlambat', 'required|numeric');

                if ($this->form_validation->run() == TRUE) {
                    $data_jam = [
                        'jam_masuk' => $this->input->post('jam_masuk'),
                        'jam_pulang' => $this->input->post('jam_pulang'),
                        'toleransi_terlambat' => $this->input->post('toleransi_terlambat')
                    ];

                    if ($this->Pengaturan_model->update_jam_kerja($data_jam)) {
                        $this->session->set_flashdata('success', 'Jam kerja berhasil diperbarui');
                    } else {
                        $this->session->set_flashdata('error', 'Gagal memperbarui jam kerja');
                    }

                    redirect('admin/pengaturan-hari-kerja');
                }
            }
            
            // Update hari kerja
            if ($this->input->post('action') == 'update_hari') {
                $hari = $this->input->post('hari');
                $is_aktif = $this->input->post('is_aktif') ? 1 : 0;

                if ($this->Pengaturan_model->update_hari_kerja($hari, $is_aktif)) {
                    echo json_encode(['success' => true, 'message' => 'Status hari kerja berhasil diperbarui']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status hari kerja']);
                }
                return;
            }
        }

        $this->load_template('admin/pengaturan_hari_kerja', $data);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_sekolah extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Pengaturan_model');
    }

    public function index() {
        $data['title'] = 'Pengaturan Sekolah';
        $data['pengaturan'] = $this->Pengaturan_model->get_pengaturan();

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('nama_sekolah', 'Nama Sekolah', 'required');
            $this->form_validation->set_rules('alamat_sekolah', 'Alamat Sekolah', 'required');
            $this->form_validation->set_rules('nama_kepala_sekolah', 'Nama Kepala Sekolah', 'required');

            if ($this->form_validation->run() == TRUE) {
                $data_update = [
                    'nama_sekolah' => $this->input->post('nama_sekolah'),
                    'alamat_sekolah' => $this->input->post('alamat_sekolah'),
                    'nama_kepala_sekolah' => $this->input->post('nama_kepala_sekolah')
                ];

                // Handle logo upload
                if (!empty($_FILES['logo_sekolah']['name'])) {
                    $config['upload_path'] = './uploads/logo/';
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['max_size'] = 2048; // 2MB
                    $config['file_name'] = 'logo_' . time();

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('logo_sekolah')) {
                        $upload_data = $this->upload->data();
                        $data_update['logo_sekolah'] = $upload_data['file_name'];

                        // Delete old logo
                        if (!empty($data['pengaturan']->logo_sekolah)) {
                            $old_file = './uploads/logo/' . $data['pengaturan']->logo_sekolah;
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                    }
                }

                if ($this->Pengaturan_model->update_pengaturan($data_update)) {
                    $this->session->set_flashdata('success', 'Pengaturan sekolah berhasil diperbarui');
                } else {
                    $this->session->set_flashdata('error', 'Gagal memperbarui pengaturan sekolah');
                }

                redirect('admin/pengaturan-sekolah');
            }
        }

        $this->load_template('admin/pengaturan_sekolah', $data);
    }
}

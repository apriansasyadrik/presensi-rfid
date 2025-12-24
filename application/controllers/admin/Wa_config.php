<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wa_config extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin']);
        $this->load->model('admin/Wa_config_model');
        $this->load->model('admin/Kelas_model');
    }

    public function index() {
        $data['title'] = 'Pengaturan WhatsApp';
        $data['config'] = $this->Wa_config_model->get_config();
        $data['templates'] = $this->Wa_config_model->get_templates();
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['notif_kelas'] = $this->Wa_config_model->get_notif_kelas();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/wa_config', $data);
        $this->load->view('admin/templates/footer');
    }

    public function save_config() {
        $this->form_validation->set_rules('api_url', 'API URL', 'required|valid_url');
        $this->form_validation->set_rules('api_key', 'API Key', 'required');
        $this->form_validation->set_rules('sender', 'Sender Number', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/wa_config');
            return;
        }

        $data = [
            'api_url' => $this->input->post('api_url'),
            'api_key' => $this->input->post('api_key'),
            'sender' => $this->input->post('sender'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        if ($this->Wa_config_model->update_config($data)) {
            $this->session->set_flashdata('success', 'Konfigurasi WhatsApp berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan konfigurasi');
        }
        redirect('admin/wa_config');
    }

    public function save_template() {
        $this->form_validation->set_rules('template_type', 'Tipe Template', 'required|in_list[masuk,pulang]');
        $this->form_validation->set_rules('message', 'Pesan', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $data = [
            'template_type' => $this->input->post('template_type'),
            'message' => $this->input->post('message')
        ];

        $id = $this->input->post('id');
        if ($id) {
            $result = $this->Wa_config_model->update_template($id, $data);
        } else {
            $result = $this->Wa_config_model->insert_template($data);
        }

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Template berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan template']);
        }
    }

    public function get_template($id) {
        $template = $this->Wa_config_model->get_template($id);
        if ($template) {
            echo json_encode(['status' => 'success', 'data' => $template]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Template tidak ditemukan']);
        }
    }

    public function toggle_kelas() {
        $id_kelas = $this->input->post('id_kelas');
        $is_active = $this->input->post('is_active');

        if ($is_active == 1) {
            // Add kelas to notification
            $result = $this->Wa_config_model->add_notif_kelas($id_kelas);
        } else {
            // Remove kelas from notification
            $result = $this->Wa_config_model->remove_notif_kelas($id_kelas);
        }

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate konfigurasi']);
        }
    }

    public function test_connection() {
        $config = $this->Wa_config_model->get_config();
        
        if (!$config || !$config->is_active) {
            echo json_encode(['status' => 'error', 'message' => 'WhatsApp API tidak aktif']);
            return;
        }

        // Simple test to check if API is reachable
        $ch = curl_init($config->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $config->api_key
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 || $http_code == 401) { // 401 means API is reachable but auth might be wrong
            echo json_encode(['status' => 'success', 'message' => 'Koneksi berhasil! API dapat dijangkau.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal terhubung ke API. HTTP Code: ' . $http_code]);
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfid_scanner extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Rfid_model');
        
        // Exclude CSRF check for AJAX requests
        if ($this->input->is_ajax_request()) {
            $this->config->set_item('csrf_protection', FALSE);
        }
    }

    public function index() {
        // Public page - no authentication required
        $data['title'] = 'RFID Scanner';
        $this->load->view('public/rfid_scanner', $data);
    }

    public function scan() {
        // AJAX endpoint for RFID scanning
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $rfid_uid = $this->input->post('rfid_uid');
        
        if (empty($rfid_uid)) {
            echo json_encode([
                'success' => false,
                'message' => 'RFID UID tidak boleh kosong'
            ]);
            return;
        }

        // Check if RFID belongs to siswa or guru
        $result = $this->Rfid_model->process_attendance($rfid_uid);

        if ($result['success']) {
            // Add to WA queue if configured
            $this->_add_to_wa_queue($result);
        }

        echo json_encode($result);
    }

    private function _add_to_wa_queue($result) {
        // Check if WA notification is active
        $wa_config = $this->Rfid_model->get_wa_config();
        
        if (!$wa_config || !$wa_config->is_active) {
            return;
        }

        // Check if user type is siswa and class is in notification list
        if ($result['user_type'] == 'siswa') {
            $is_notif_enabled = $this->Rfid_model->is_class_notification_enabled($result['user_data']->kelas_id);
            
            if (!$is_notif_enabled) {
                return;
            }
        }

        // Get template
        $template = $this->Rfid_model->get_wa_template($result['attendance_type']);
        
        if (!$template) {
            return;
        }

        // Prepare message
        $message = $template->template;
        $no_telepon = '';

        if ($result['user_type'] == 'siswa') {
            $user = $result['user_data'];
            $message = str_replace('{NAMA_ORTU}', $user->nama_ortu, $message);
            $message = str_replace('{NAMA_SISWA}', $user->nama_lengkap, $message);
            $message = str_replace('{JAM_MASUK}', $result['jam'], $message);
            $message = str_replace('{JAM_PULANG}', $result['jam'], $message);
            $no_telepon = $user->no_telepon_ortu;
        } else {
            $user = $result['user_data'];
            $message = str_replace('{NAMA_GURU}', $user->nama_lengkap, $message);
            $message = str_replace('{JAM_MASUK}', $result['jam'], $message);
            $message = str_replace('{JAM_PULANG}', $result['jam'], $message);
            $no_telepon = $user->no_telepon;
        }

        // Add to queue
        if (!empty($no_telepon)) {
            $this->Rfid_model->add_to_wa_queue($no_telepon, $message);
        }
    }
}

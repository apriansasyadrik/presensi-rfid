<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Guru', 'Guru Wali Kelas', 'Guru Piket']);
        $this->load->model('guru/Jurnal_model');
        $this->load->model('admin/Guru_model');
    }

    public function index() {
        $id_guru = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($id_guru);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('auth/logout');
        }

        $data['title'] = 'Jurnal Mengajar';
        $data['guru'] = $guru;
        $data['jurnal'] = $this->Jurnal_model->get_all_by_guru($guru->id);
        
        $this->load->view('guru/templates/header', $data);
        $this->load->view('guru/templates/sidebar');
        $this->load->view('guru/jurnal', $data);
        $this->load->view('guru/templates/footer');
    }

    public function create($id_jadwal) {
        $id_guru = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($id_guru);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('auth/logout');
        }

        // Get jadwal details
        $this->load->model('admin/Jadwal_pelajaran_model');
        $jadwal = $this->Jadwal_pelajaran_model->get_by_id($id_jadwal);
        
        if (!$jadwal || $jadwal->id_guru != $guru->id) {
            $this->session->set_flashdata('error', 'Jadwal tidak valid');
            redirect('guru/dashboard');
        }

        $data['title'] = 'Isi Jurnal';
        $data['guru'] = $guru;
        $data['jadwal'] = $jadwal;
        
        // Get students from the class
        $this->load->model('admin/Siswa_model');
        $data['siswa'] = $this->Siswa_model->get_by_kelas($jadwal->id_kelas);
        
        $this->load->view('guru/templates/header', $data);
        $this->load->view('guru/templates/sidebar');
        $this->load->view('guru/jurnal_form', $data);
        $this->load->view('guru/templates/footer');
    }

    public function save() {
        $id_guru = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($id_guru);
        
        if (!$guru) {
            echo json_encode(['status' => 'error', 'message' => 'Data guru tidak ditemukan']);
            return;
        }

        $this->form_validation->set_rules('id_jadwal', 'Jadwal', 'required|integer');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('materi', 'Materi', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        // Start transaction
        $this->db->trans_start();

        // Insert jurnal
        $jurnal_data = [
            'id_jadwal' => $this->input->post('id_jadwal'),
            'id_guru' => $guru->id,
            'tanggal' => $this->input->post('tanggal'),
            'materi' => $this->input->post('materi'),
            'keterangan' => $this->input->post('keterangan'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Jurnal_model->insert($jurnal_data);
        $id_jurnal = $this->db->insert_id();

        // Insert absensi per mapel
        $absensi = $this->input->post('absensi'); // array of id_siswa => status
        if ($absensi && is_array($absensi)) {
            foreach ($absensi as $id_siswa => $status) {
                $absensi_data = [
                    'id_jurnal' => $id_jurnal,
                    'id_siswa' => $id_siswa,
                    'status' => $status,
                    'tanggal' => $this->input->post('tanggal')
                ];
                $this->db->insert('absensi_mapel', $absensi_data);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jurnal']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Jurnal berhasil disimpan']);
        }
    }

    public function get_by_id($id) {
        $jurnal = $this->Jurnal_model->get_by_id($id);
        if ($jurnal) {
            // Get absensi data
            $absensi = $this->Jurnal_model->get_absensi($id);
            $jurnal->absensi = $absensi;
            echo json_encode(['status' => 'success', 'data' => $jurnal]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Jurnal tidak ditemukan']);
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_pelajaran extends MY_Controller {

    protected $allowed_roles = array('admin');

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Jadwal_pelajaran_model');
        $this->load->model('admin/Kelas_model');
        $this->load->model('admin/Mata_pelajaran_model');
        $this->load->model('admin/Guru_model');
    }

    public function index() {
        $data['title'] = 'Jadwal Pelajaran';
        $data['jadwal'] = $this->Jadwal_pelajaran_model->get_all_with_details();
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['mapel'] = $this->Mata_pelajaran_model->get_all();
        $data['guru'] = $this->Guru_model->get_all();
        
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar');
        $this->load->view('admin/jadwal_pelajaran', $data);
        $this->load->view('admin/templates/footer');
    }

    public function get_by_id($id) {
        $jadwal = $this->Jadwal_pelajaran_model->get_by_id($id);
        if ($jadwal) {
            echo json_encode(['status' => 'success', 'data' => $jadwal]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Jadwal tidak ditemukan']);
        }
    }

    public function save() {
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required|integer');
        $this->form_validation->set_rules('id_mata_pelajaran', 'Mata Pelajaran', 'required|integer');
        $this->form_validation->set_rules('id_guru', 'Guru', 'required|integer');
        $this->form_validation->set_rules('hari', 'Hari', 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error', 
                'message' => validation_errors()
            ]);
            return;
        }

        $data = [
            'id_kelas' => $this->input->post('id_kelas'),
            'id_mata_pelajaran' => $this->input->post('id_mata_pelajaran'),
            'id_guru' => $this->input->post('id_guru'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai')
        ];

        $id = $this->input->post('id');
        if ($id) {
            // Update
            if ($this->Jadwal_pelajaran_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil diperbarui');
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui jadwal']);
            }
        } else {
            // Insert
            if ($this->Jadwal_pelajaran_model->insert($data)) {
                $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil ditambahkan');
                echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil ditambahkan']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan jadwal']);
            }
        }
    }

    public function delete($id) {
        if ($this->Jadwal_pelajaran_model->delete($id)) {
            $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil dihapus');
            echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus jadwal']);
        }
    }

    public function get_by_kelas($id_kelas) {
        $jadwal = $this->Jadwal_pelajaran_model->get_by_kelas($id_kelas);
        echo json_encode(['status' => 'success', 'data' => $jadwal]);
    }

    public function get_by_guru($id_guru) {
        $jadwal = $this->Jadwal_pelajaran_model->get_by_guru($id_guru);
        echo json_encode(['status' => 'success', 'data' => $jadwal]);
    }
}

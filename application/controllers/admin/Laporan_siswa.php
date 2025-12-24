<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_siswa extends Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Laporan_siswa_model');
		$this->load->model('admin/Kelas_model');
		$this->load->helper('date');
	}

	public function index() {
		$data['title'] = 'Laporan Absensi Siswa';
		$data['kelas_list'] = $this->Kelas_model->get_all();
		
		// Default: current month
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$kelas_id = $this->input->get('kelas_id');
		
		$data['bulan'] = $bulan;
		$data['kelas_id'] = $kelas_id;
		$data['laporan'] = [];
		
		if ($kelas_id) {
			$data['laporan'] = $this->Laporan_siswa_model->get_laporan_bulanan($kelas_id, $bulan);
			$data['kelas_info'] = $this->Kelas_model->get_by_id($kelas_id);
		}
		
		$this->load->view('admin/templates/header', $data);
		$this->load->view('admin/templates/sidebar');
		$this->load->view('admin/laporan_siswa', $data);
		$this->load->view('admin/templates/footer');
	}

	public function rekap() {
		$data['title'] = 'Rekap Absensi Siswa';
		$data['kelas_list'] = $this->Kelas_model->get_all();
		
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$kelas_id = $this->input->get('kelas_id');
		
		$data['bulan'] = $bulan;
		$data['kelas_id'] = $kelas_id;
		$data['rekap'] = [];
		
		if ($kelas_id) {
			$data['rekap'] = $this->Laporan_siswa_model->get_rekap_bulanan($kelas_id, $bulan);
			$data['kelas_info'] = $this->Kelas_model->get_by_id($kelas_id);
		}
		
		$this->load->view('admin/templates/header', $data);
		$this->load->view('admin/templates/sidebar');
		$this->load->view('admin/rekap_siswa', $data);
		$this->load->view('admin/templates/footer');
	}

	public function export_excel() {
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$kelas_id = $this->input->get('kelas_id');
		
		if (!$kelas_id) {
			$this->session->set_flashdata('error', 'Pilih kelas terlebih dahulu');
			redirect('admin/laporan_siswa');
			return;
		}
		
		$data = $this->Laporan_siswa_model->get_laporan_bulanan($kelas_id, $bulan);
		$kelas_info = $this->Kelas_model->get_by_id($kelas_id);
		
		// TODO: Implement Excel export with PhpSpreadsheet
		// For now, return CSV
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="laporan_siswa_' . $bulan . '.csv"');
		
		$output = fopen('php://output', 'w');
		fputcsv($output, ['NIS', 'Nama', 'Kelas', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Terlambat (menit)']);
		
		foreach ($data as $row) {
			fputcsv($output, [
				$row->nis,
				$row->nama_lengkap,
				$kelas_info->nama_kelas,
				$row->tanggal,
				$row->jam_masuk,
				$row->jam_pulang,
				$row->status,
				$row->terlambat
			]);
		}
		
		fclose($output);
	}

	public function export_pdf() {
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$kelas_id = $this->input->get('kelas_id');
		
		if (!$kelas_id) {
			$this->session->set_flashdata('error', 'Pilih kelas terlebih dahulu');
			redirect('admin/laporan_siswa');
			return;
		}
		
		$data['laporan'] = $this->Laporan_siswa_model->get_laporan_bulanan($kelas_id, $bulan);
		$data['kelas_info'] = $this->Kelas_model->get_by_id($kelas_id);
		$data['bulan'] = $bulan;
		
		// Load pengaturan untuk kop surat
		$this->load->model('admin/Pengaturan_model');
		$data['pengaturan'] = $this->Pengaturan_model->get_pengaturan();
		
		// TODO: Implement PDF export with DOMPDF
		// For now, show HTML view that can be printed
		$this->load->view('admin/laporan_siswa_pdf', $data);
	}
}

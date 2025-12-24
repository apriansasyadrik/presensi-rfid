<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_guru extends Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Laporan_guru_model');
		$this->load->model('admin/Guru_model');
		$this->load->helper('date');
	}

	public function index() {
		$data['title'] = 'Laporan Absensi Guru';
		$data['guru_list'] = $this->Guru_model->get_all();
		
		// Default: current month
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$guru_id = $this->input->get('guru_id');
		
		$data['bulan'] = $bulan;
		$data['guru_id'] = $guru_id;
		$data['laporan'] = [];
		
		if ($guru_id) {
			$data['laporan'] = $this->Laporan_guru_model->get_laporan_bulanan($guru_id, $bulan);
			$data['guru_info'] = $this->Guru_model->get_by_id($guru_id);
		} else {
			// Show all teachers if no specific teacher selected
			$data['laporan'] = $this->Laporan_guru_model->get_laporan_all_guru($bulan);
		}
		
		$this->load->view('admin/templates/header', $data);
		$this->load->view('admin/templates/sidebar');
		$this->load->view('admin/laporan_guru', $data);
		$this->load->view('admin/templates/footer');
	}

	public function rekap() {
		$data['title'] = 'Rekap Absensi Guru';
		$data['guru_list'] = $this->Guru_model->get_all();
		
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$guru_id = $this->input->get('guru_id');
		
		$data['bulan'] = $bulan;
		$data['guru_id'] = $guru_id;
		$data['rekap'] = [];
		
		if ($guru_id) {
			$data['rekap'] = $this->Laporan_guru_model->get_rekap_bulanan($guru_id, $bulan);
			$data['guru_info'] = $this->Guru_model->get_by_id($guru_id);
		} else {
			$data['rekap'] = $this->Laporan_guru_model->get_rekap_all_guru($bulan);
		}
		
		$this->load->view('admin/templates/header', $data);
		$this->load->view('admin/templates/sidebar');
		$this->load->view('admin/rekap_guru', $data);
		$this->load->view('admin/templates/footer');
	}

	public function export_excel() {
		$bulan = $this->input->get('bulan') ?: date('Y-m');
		$guru_id = $this->input->get('guru_id');
		
		if ($guru_id) {
			$data = $this->Laporan_guru_model->get_laporan_bulanan($guru_id, $bulan);
			$guru_info = $this->Guru_model->get_by_id($guru_id);
			$filename = 'laporan_guru_' . $guru_info->nip . '_' . $bulan . '.csv';
		} else {
			$data = $this->Laporan_guru_model->get_laporan_all_guru($bulan);
			$filename = 'laporan_all_guru_' . $bulan . '.csv';
		}
		
		// TODO: Implement Excel export with PhpSpreadsheet
		// For now, return CSV
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		
		$output = fopen('php://output', 'w');
		fputcsv($output, ['NIP', 'Nama', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Terlambat (menit)']);
		
		foreach ($data as $row) {
			fputcsv($output, [
				$row->nip,
				$row->nama_lengkap,
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
		$guru_id = $this->input->get('guru_id');
		
		if ($guru_id) {
			$data['laporan'] = $this->Laporan_guru_model->get_laporan_bulanan($guru_id, $bulan);
			$data['guru_info'] = $this->Guru_model->get_by_id($guru_id);
		} else {
			$data['laporan'] = $this->Laporan_guru_model->get_laporan_all_guru($bulan);
			$data['guru_info'] = null;
		}
		
		$data['bulan'] = $bulan;
		
		// Load pengaturan untuk kop surat
		$this->load->model('admin/Pengaturan_model');
		$data['pengaturan'] = $this->Pengaturan_model->get_pengaturan();
		
		// TODO: Implement PDF export with DOMPDF
		// For now, show HTML view that can be printed
		$this->load->view('admin/laporan_guru_pdf', $data);
	}
}

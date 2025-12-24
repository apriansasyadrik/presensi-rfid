<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfid_model extends CI_Model {

    public function process_attendance($rfid_uid) {
        // Check if RFID belongs to siswa
        $siswa = $this->get_siswa_by_rfid($rfid_uid);
        
        if ($siswa) {
            return $this->_process_siswa_attendance($siswa);
        }

        // Check if RFID belongs to guru
        $guru = $this->get_guru_by_rfid($rfid_uid);
        
        if ($guru) {
            return $this->_process_guru_attendance($guru);
        }

        return [
            'success' => false,
            'message' => 'RFID tidak terdaftar'
        ];
    }

    private function _process_siswa_attendance($siswa) {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');

        // Check if already has attendance today
        $existing = $this->get_attendance_today('siswa', $siswa->id);

        if ($existing) {
            // Update pulang
            if (empty($existing->jam_pulang)) {
                $this->db->where('id', $existing->id);
                $this->db->update('absensi_harian', ['jam_pulang' => $current_time]);

                return [
                    'success' => true,
                    'message' => 'Absensi pulang berhasil',
                    'user_type' => 'siswa',
                    'user_data' => $siswa,
                    'attendance_type' => 'pulang',
                    'jam' => $current_time
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Sudah absen masuk dan pulang hari ini',
                    'user_type' => 'siswa',
                    'user_data' => $siswa
                ];
            }
        } else {
            // Insert new attendance (masuk)
            $jam_kerja = $this->get_jam_kerja();
            $status_masuk = 'tepat_waktu';
            $keterlambatan = 0;

            if ($jam_kerja) {
                $jam_masuk_kerja = strtotime($jam_kerja->jam_masuk);
                $jam_masuk_siswa = strtotime($current_time);
                $toleransi = $jam_kerja->toleransi_terlambat * 60; // convert to seconds

                if ($jam_masuk_siswa > ($jam_masuk_kerja + $toleransi)) {
                    $status_masuk = 'terlambat';
                    $keterlambatan = ceil(($jam_masuk_siswa - $jam_masuk_kerja) / 60); // in minutes
                }
            }

            $data = [
                'user_type' => 'siswa',
                'user_id' => $siswa->id,
                'tanggal' => $today,
                'jam_masuk' => $current_time,
                'status_masuk' => $status_masuk,
                'keterlambatan_menit' => $keterlambatan
            ];

            $this->db->insert('absensi_harian', $data);

            return [
                'success' => true,
                'message' => 'Absensi masuk berhasil',
                'user_type' => 'siswa',
                'user_data' => $siswa,
                'attendance_type' => 'masuk',
                'jam' => $current_time,
                'status' => $status_masuk,
                'keterlambatan' => $keterlambatan
            ];
        }
    }

    private function _process_guru_attendance($guru) {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');

        // Check if already has attendance today
        $existing = $this->get_attendance_today('guru', $guru->id);

        if ($existing) {
            // Update pulang
            if (empty($existing->jam_pulang)) {
                $this->db->where('id', $existing->id);
                $this->db->update('absensi_harian', ['jam_pulang' => $current_time]);

                return [
                    'success' => true,
                    'message' => 'Absensi pulang berhasil',
                    'user_type' => 'guru',
                    'user_data' => $guru,
                    'attendance_type' => 'pulang',
                    'jam' => $current_time
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Sudah absen masuk dan pulang hari ini',
                    'user_type' => 'guru',
                    'user_data' => $guru
                ];
            }
        } else {
            // Insert new attendance (masuk)
            $jam_kerja = $this->get_jam_kerja();
            $status_masuk = 'tepat_waktu';
            $keterlambatan = 0;

            if ($jam_kerja) {
                $jam_masuk_kerja = strtotime($jam_kerja->jam_masuk);
                $jam_masuk_guru = strtotime($current_time);
                $toleransi = $jam_kerja->toleransi_terlambat * 60;

                if ($jam_masuk_guru > ($jam_masuk_kerja + $toleransi)) {
                    $status_masuk = 'terlambat';
                    $keterlambatan = ceil(($jam_masuk_guru - $jam_masuk_kerja) / 60);
                }
            }

            $data = [
                'user_type' => 'guru',
                'user_id' => $guru->id,
                'tanggal' => $today,
                'jam_masuk' => $current_time,
                'status_masuk' => $status_masuk,
                'keterlambatan_menit' => $keterlambatan
            ];

            $this->db->insert('absensi_harian', $data);

            return [
                'success' => true,
                'message' => 'Absensi masuk berhasil',
                'user_type' => 'guru',
                'user_data' => $guru,
                'attendance_type' => 'masuk',
                'jam' => $current_time,
                'status' => $status_masuk,
                'keterlambatan' => $keterlambatan
            ];
        }
    }

    public function get_siswa_by_rfid($rfid_uid) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.rfid_uid', $rfid_uid);
        $this->db->where('siswa.is_active', 1);
        return $this->db->get()->row();
    }

    public function get_guru_by_rfid($rfid_uid) {
        $this->db->where('rfid_uid', $rfid_uid);
        $this->db->where('is_active', 1);
        return $this->db->get('guru')->row();
    }

    public function get_attendance_today($user_type, $user_id) {
        $today = date('Y-m-d');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_id', $user_id);
        $this->db->where('tanggal', $today);
        return $this->db->get('absensi_harian')->row();
    }

    public function get_jam_kerja() {
        return $this->db->get('jam_kerja')->row();
    }

    public function get_wa_config() {
        return $this->db->get('wa_config')->row();
    }

    public function get_wa_template($tipe) {
        $this->db->where('tipe', $tipe);
        return $this->db->get('wa_template')->row();
    }

    public function is_class_notification_enabled($kelas_id) {
        if (empty($kelas_id)) {
            return false;
        }
        
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        $result = $this->db->get('wa_notifikasi_kelas')->row();
        return !empty($result);
    }

    public function add_to_wa_queue($no_telepon, $pesan) {
        $data = [
            'no_telepon' => $no_telepon,
            'pesan' => $pesan,
            'status' => 'pending',
            'retry_count' => 0
        ];
        return $this->db->insert('wa_queue', $data);
    }
}

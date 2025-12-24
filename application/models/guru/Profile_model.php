<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {
    
    public function get_guru_profile($user_id) {
        $this->db->select('guru.*, users.username, users.role');
        $this->db->from('guru');
        $this->db->join('users', 'users.id = guru.user_id');
        $this->db->where('guru.user_id', $user_id);
        $this->db->where('guru.deleted_at IS NULL');
        return $this->db->get()->row();
    }
    
    public function get_guru_id_by_user($user_id) {
        $this->db->select('id');
        $this->db->from('guru');
        $this->db->where('user_id', $user_id);
        $this->db->where('deleted_at IS NULL');
        $result = $this->db->get()->row();
        return $result ? $result->id : null;
    }
    
    public function update_profile($guru_id, $data) {
        $this->db->where('id', $guru_id);
        return $this->db->update('guru', $data);
    }
    
    public function verify_password($user_id, $password) {
        $this->db->where('id', $user_id);
        $this->db->where('password', $password);
        $result = $this->db->get('users')->row();
        return $result ? true : false;
    }
    
    public function update_password($user_id, $new_password) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['password' => $new_password]);
    }
}

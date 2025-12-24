<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {
    
    public function get_user_profile($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->get('users')->row();
    }
    
    public function update_profile($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
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

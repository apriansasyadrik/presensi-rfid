<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function verify_login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $this->db->where('is_active', 1);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            return $query->row();
        }
        return FALSE;
    }

    public function get_user_by_id($user_id) {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function update_password($user_id, $new_password) {
        $data = array(
            'password' => md5($new_password)
        );
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }
}

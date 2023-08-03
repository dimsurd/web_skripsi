<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_data()
    {
        return $this->db->get("users")->result();
    }

    public function create_data($data)
    {
        $username = $data['username'];

        // Check duplicate username
        $duplicate_data = $this->db->where('username', $username)->get('users')->row();
        if ($duplicate_data) {
            $result['status'] = false;
            $result['message'] = 'Username already exist';
            $result['code'] = '501';
            return $result;
        }
        // End Check duplicate username


        // hash password
        $hashed_password = md5($data['password']);
        // end hash password

        $data_insert = [
            "name" => $data['name'],
            "username" => $data['username'],
            "password" => $hashed_password,
            "role" => $data['role'],
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['username'],
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        ];

        $create_data = $this->db->insert('users', $data_insert);

        if (!$create_data) {
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '201';
        }
        return $result;
    }

    public function update_data($data)
    {
        $id = $data['id_hidden'];
        $username = $data['username'];

        // Check duplicate username
        $duplicate_data = $this->db->where('username', $username)->where('id <>', $id)->get('users')->row();
        if ($duplicate_data) {
            $result['status'] = false;
            $result['message'] = 'Username already exist';
            $result['code'] = '501';
            return $result;
        }
        // End Check duplicate username


        if (empty($data['password'])) {
            $data_update = array(
                "name" => $data['name'],
                "username" => $data['username'],
                "role" => $data['role'],
                'date_log' => date('Y-m-d H:i:s'),
                'user_log' => $_SESSION['username'],
            );
        } else {
            // hash password
            $hashed_password = md5($data['password']);
            // end hash password
            $data_update = array(
                "name" => $data['name'],
                "username" => $data['username'],
                "password" => $hashed_password,
                "role" => $data['role'],
                'date_log' => date('Y-m-d H:i:s'),
                'user_log' => $_SESSION['username'],
            );
        }


        $this->db->where('id', $id);
        $update = $this->db->update('users', $data_update);
        if (!$update) {
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '201';
        }
        return $result;
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');
        return  $this->db->affected_rows();
    }
}

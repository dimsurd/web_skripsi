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

        $this->db->insert('users', $data_insert);
        return  $this->db->affected_rows();
    }

    public function update_data($data)
    {
        $id = $data['id_hidden'];


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
        $this->db->update('users', $data_update);
        return  $this->db->affected_rows();
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');
        return  $this->db->affected_rows();
    }
}

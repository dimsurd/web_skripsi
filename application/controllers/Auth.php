<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('login/view');
    }

    public function do_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $data_user = $this->db->where('username', $username)->get('users')->row();

        if (!$data_user) {
            $result['status'] = false;
            $result['message'] = 'Invalid Username / Password';
            $result['code'] = '500';
            echo json_encode($result);
            exit;
        }

        $hashedPassword = md5($password);
        $storedHashedPassword = $data_user->password;

        if ($hashedPassword === $storedHashedPassword) {
            $data = array(
                "username" => $username,
                "role" => $data_user->role,
                "is_logged_in" => true
            );

            $this->session->set_userdata($data);

            $result['status'] = true;
            $result['message'] = 'Success login';
            $result['code'] = '200';
        } else {
            $result['status'] = false;
            $result['message'] = 'Invalid Username / Password';
            $result['code'] = '500';
        }
        echo json_encode($result);
    }

    public function log_out()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Users_model");
        if (empty($_SESSION['is_logged_in'])) {
            redirect(base_url("/Auth"));
        }
    }

    public function index()
    {
        $data['data_users'] = $this->Users_model->get_all_data();
        $this->load->view('users/view', $data);
    }

    public function add_page()
    {
        $this->load->view('users/add');
    }


    public function submit_form()
    {
        $post = $this->input->post();
        $create_data = $this->Users_model->create_data($post);

        echo json_encode($create_data);
    }

    public function edit_page($id)
    {
        $data_users = $this->db->where('id', $id)->get('users')->row();

        $data = array(
            'data_users' => $data_users
        );
        $this->load->view('users/edit', $data);
    }

    public function submit_form_edit()
    {
        $post = $this->input->post();

        $update_data = $this->Users_model->update_data($post);

        echo json_encode($update_data);
    }

    public function delete_data()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $update_data = $this->Users_model->delete_data($id);

        if (!$update_data) {
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '201';
        }

        echo json_encode($result);
    }
}

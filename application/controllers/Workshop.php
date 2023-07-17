<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Workshop extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Workshop_model");
        if (empty($_SESSION['is_logged_in'])) {
            redirect(base_url("/Auth"));
        }
    }

    public function index()
    {
        $data['data_workshop'] = $this->Workshop_model->get_all_data();
        $this->load->view('workshop/view', $data);
    }

    public function add_page()
    {
        $this->load->view('workshop/add');
    }


    public function submit_form()
    {
        $post = $this->input->post();

        if (!isset($post['charge_repair_name'])) {
            $result['status'] = false;
            $result['message'] = 'Please add charge repair';
            $result['code'] = '500';
            echo json_encode($result);
            exit;
        }

        $create_data = $this->Workshop_model->create_data($post);


        if (!$create_data['status']) {
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = $create_data['message'];
        } else {
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '201';
        }

        echo json_encode($result);
    }

    public function submit_form_charge()
    {
        $post = $this->input->post();
        $create_data = $this->Workshop_model->create_data_charge($post);

        if (!$create_data['status']) {
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $result['id_workshop_charge'] = $create_data['id_workshop_charge'];
            $result['current_total'] = $create_data['current_total'];
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '201';
        }

        echo json_encode($result);
    }

    public function submit_form_charge_edit()
    {
        $post = $this->input->post();
        $create_data = $this->Workshop_model->update_data_charge($post);

        if (!$create_data['status']) {
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $result['current_total'] = $create_data['current_total'];
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '201';
        }

        echo json_encode($result);
    }

    public function edit_page($id)
    {
        $data_workshop = $this->db->where('id', $id)->get('workshop')->row();
        $data_workshop_charge_repair = $this->db->where('id_workshop', $id)->get('workshop_charge_repair')->result();

        if ($data_workshop->status > 0) {
            redirect(base_url("workshop/detail_page/" . $id));
        }

        $data = array(
            'data_workshop' => $data_workshop,
            'data_workshop_charge_repair' => $data_workshop_charge_repair,
        );
        $this->load->view('workshop/edit', $data);
    }

    public function detail_page($id)
    {
        $data_workshop = $this->db->where('id', $id)->get('workshop')->row();
        $data_workshop_charge_repair = $this->db->where('id_workshop', $id)->get('workshop_charge_repair')->result();

        $data = array(
            'data_workshop' => $data_workshop,
            'data_workshop_charge_repair' => $data_workshop_charge_repair,
        );
        $this->load->view('workshop/detail', $data);
    }

    public function submit_form_edit()
    {
        $post = $this->input->post();

        $update_data = $this->Workshop_model->update_data($post);

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

    public function delete_data()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $update_data = $this->Workshop_model->delete_data($id);

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

    public function delete_charge()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $update_data = $this->Workshop_model->delete_data_charge($id);

        if (!$update_data['status']) {
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

    public function get_data_charge()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $data_charge = $this->db->where('id', $id)->get('workshop_charge_repair')->row();

        if (!$data_charge) {
            $result['status'] = false;
            $result['message'] = 'Failed: Data not found';
            $result['code'] = '5001';
        } else {
            $result['status'] = true;
            $result['message'] = 'Data found';
            $result['code'] = '200';
            $result['data_charge'] = $data_charge;
        }


        echo json_encode($result);
    }

    public function confirm_payment($id)
    {
        $this->Workshop_model->confirm_payment($id);

        redirect(base_url("workshop"));
    }
}

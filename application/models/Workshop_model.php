<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Workshop_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_data()
    {
        return $this->db->get("workshop")->result();
    }

    public function create_data($data)
    {

        $this->db->trans_begin();

        $datainsert = array(
            'nopol' => $data['nopol'],
            'customer_name' => $data['customer_name'],
            'total' => str_replace(',', '', $data['total_charge']),
            'status' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['username'],
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        );
        $res = $this->db->insert('workshop', $datainsert);
        $id_workshop = $this->db->insert_id();


        $charge_repair_name = $data['charge_repair_name'];
        $charge_repair_cost = $data['charge_repair_cost'];

        $scan_seq = 0;


        foreach ($charge_repair_name as $key => $value) {
            $scan_seq++;
            $dataChargeWorkshop = array(
                'id_workshop' => $id_workshop,
                'charge_name' => $charge_repair_name[$key],
                'charge_cost' => str_replace(',', '', $charge_repair_cost[$key]),
                'scan_seq' => $scan_seq,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $_SESSION['username'],
                'date_log' => date('Y-m-d H:i:s'),
                'user_log' => $_SESSION['username'],
            );
            $res = $this->db->insert('workshop_charge_repair', $dataChargeWorkshop);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $this->db->trans_commit();
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '200';
        }


        return  $result;
    }

    public function create_data_charge($data)
    {
        $this->db->trans_begin();


        $workshop_id = $data['workshop_id'];
        $charge_name = $data['charge_name'];
        $charge_cost = str_replace(',', '', $data['charge_cost']);

        // Get latest data 
        $latest_data = $this->db->where('id_workshop', $workshop_id)->order_by('scan_seq', 'desc')->get('workshop_charge_repair')->row();
        $scan_seq = 1;

        if ($latest_data) {
            $current_seq = $latest_data->scan_seq;
            $scan_seq = $current_seq + 1;
        }
        // End Get latest data 


        $datainsert = array(
            'id_workshop' => $workshop_id,
            'charge_name' => $charge_name,
            'charge_cost' => $charge_cost,
            'scan_seq' => $scan_seq,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['username'],
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        );
        $res = $this->db->insert('workshop_charge_repair', $datainsert);
        $id_workshop_charge = $this->db->insert_id();

        // Get data workshop 
        $data_workshop = $this->db->where('id', $workshop_id)->get('workshop')->row();
        // End Get data workshop charge

        // Count current total
        $current_total = floatval($data_workshop->total) + floatval($charge_cost);
        // End Count current total

        $dataupdate = array(
            'total' => $current_total,
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        );
        $this->db->set($dataupdate);
        $this->db->where(array('ID' => $workshop_id));
        $this->db->update('WORKSHOP');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $this->db->trans_commit();
            $result['status'] = true;
            $result['message'] = 'Data has been saved';
            $result['code'] = '200';
            $result['id_workshop_charge'] = $id_workshop_charge;
            $result['current_total'] = $current_total;
        }

        return  $result;
    }

    public function update_data($data)
    {
        $id = $data['idHidden'];

        $data_update = array(
            "nopol" => $data['nopol'],
            "customer_name" => $data['customer_name']
        );

        $this->db->where('id', $id);
        $this->db->update('workshop', $data_update);
        return  $this->db->affected_rows();
    }

    public function update_data_charge($data)
    {
        $id = $data['charge_id'];
        $charge_cost = str_replace(',', '', $data['charge_cost_edit']);

        $this->db->trans_begin();

        // Get data workshop charge
        $data_charge = $this->db->where('id', $id)->get('workshop_charge_repair')->row();
        // End Get data workshop charge

        // Count current total
        $this->db->select('SUM(charge_cost) as total_charge_cost');
        $this->db->from('workshop_charge_repair');
        $this->db->where('id_workshop', $data_charge->id_workshop);
        $this->db->where('id !=', $id);
        $total_charge = $this->db->get()->row();

        $current_total = floatval($total_charge->total_charge_cost) + floatval($charge_cost);
        // End Count current total

        $data_update = array(
            "charge_name" => $data['charge_name_edit'],
            "charge_cost" => $charge_cost
        );

        $this->db->where('id', $id);
        $this->db->update('workshop_charge_repair', $data_update);


        $dataupdate = array(
            'total' => $current_total,
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        );
        $this->db->set($dataupdate);
        $this->db->where(array('id' => $data_charge->id_workshop));
        $this->db->update('workshop');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $this->db->trans_commit();
            $result['status'] = true;
            $result['message'] = 'Data has been updated';
            $result['code'] = '200';
            $result['current_total'] = $current_total;
        }
        return $result;
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('workshop');

        $this->db->where('id_workshop', $id);
        $this->db->delete('workshop_charge_repair');


        return  $this->db->affected_rows();
    }
    public function delete_data_charge($id)
    {
        $this->db->trans_begin();

        // Get data workshop charge
        $data_charge = $this->db->where('id', $id)->get('workshop_charge_repair')->row();
        // End Get data workshop charge

        // Get data workshop 
        $data_workshop = $this->db->where('id', $data_charge->id_workshop)->get("workshop")->row();
        // End Get data workshop 

        // Count current total
        $current_total = floatval($data_workshop->total) - floatval($data_charge->charge_cost);
        // End Count current total

        $this->db->where('id', $id);
        $this->db->delete('workshop_charge_repair');

        $dataupdate = array(
            'total' => $current_total,
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        );
        $this->db->set($dataupdate);
        $this->db->where(array('id' => $data_charge->id_workshop));
        $this->db->update('workshop');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $result['status'] = false;
            $result['message'] = 'Failed: Failed to submit data';
            $result['code'] = '500';
        } else {
            $this->db->trans_commit();
            $result['status'] = true;
            $result['message'] = 'Data has been updated';
            $result['code'] = '200';
            $result['current_total'] = $current_total;
        }
        return $result;
    }

    public function confirm_payment($id)
    {
        $dataupdate = array(
            'status' => 1,
            'date_log' => date('Y-m-d H:i:s'),
            'user_log' => $_SESSION['username'],
        );
        $this->db->set($dataupdate);
        $this->db->where(array('id' => $id));
        $this->db->update('workshop');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */

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
        $data_waiting_for_payment = $this->db->where('status', 0)->get('workshop')->result();
        $data_on_process = $this->db->select("a.*,b.charge_name")->from("workshop a")->join('workshop_charge_repair b', 'b.id_workshop = a.id')->where('b.is_scanned', 0)->where('a.status', 1)->order_by('b.id', 'desc')->group_by('a.id')->get()->result();
        $data_finishing = $this->db->where('status', 2)->get('workshop')->result();
        $data_finished = $this->db->where('status', 3)->get('workshop')->result();

        $data = [
            "data_on_process" => $data_on_process,
            "data_finished" => $data_finished,
            "data_finishing" => $data_finishing,
            "data_waiting_for_payment" => $data_waiting_for_payment,
        ];
        $this->load->view('home', $data);
    }

    public function scan_data()
    {
        $id = $_POST['id'];



        $this->db->trans_begin();

        // Data Validation
        $data_workshop = $this->db->where("ID", $id)->get("workshop")->row();
        if (!$data_workshop) {
            $res['status'] = false;
            $res['msg'] = 'Data Not Found';
            echo json_encode($res);
            exit;
        }

        if ($data_workshop->status == 3) {
            $res['status'] = false;
            $res['msg'] = 'This data already finished';
            echo json_encode($res);
            exit;
        }

        // End Data Validation

        // Check if all data already scanned
        $current_data_seq = $this->db->where('id_workshop', $id)->where('is_scanned <> 1')->order_by('scan_seq', 'ASC')->get('workshop_charge_repair')->row();

        if ($current_data_seq) {
            $id_workshop_charge_repair = $current_data_seq->id;

            $dataupdate = array(
                'IS_SCANNED' => 1,
                'DATE_LOG' => date('Y-m-d H:i:s'),
                'USER_LOG' => $_SESSION['username'],
            );
            $this->db->set($dataupdate);
            $this->db->where(array('id' => $id_workshop_charge_repair));
            $this->db->update('workshop_charge_repair');
        }
        // End Check if all data already scanned

        $last_data_seq = $this->db->where('id_workshop', $id)->where('is_scanned <> 1')->order_by('scan_seq', 'ASC')->get('workshop_charge_repair')->row();

        if (!$last_data_seq) {
            if ($data_workshop->status == 1) {
                $dataupdateWorkshop = array(
                    'status' => 2,
                    'date_log' => date('Y-m-d H:i:s'),
                    'user_log' => $_SESSION['username'],
                );
            } elseif ($data_workshop->status == 2) {
                $dataupdateWorkshop = array(
                    'status' => 3,
                    'date_log' => date('Y-m-d H:i:s'),
                    'user_log' => $_SESSION['username'],
                );
            } elseif ($data_workshop->status == 3) {
                $dataupdateWorkshop = array(
                    'status' => 4,
                    'date_log' => date('Y-m-d H:i:s'),
                    'user_log' => $_SESSION['username'],
                );
            }
            $this->db->set($dataupdateWorkshop);
            $this->db->where(array('ID' => $id));
            $this->db->update('WORKSHOP');
        }


        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $res['status'] = false;
            $res['msg'] = 'Failed Update Data';
        } else {
            $this->db->trans_commit();
            $res['status'] = true;
            $res['msg'] = 'Success Update Data';
        }

        echo json_encode($res);
        exit;
    }
}

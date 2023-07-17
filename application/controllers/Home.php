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
        require 'vendor/autoload.php';

        $this->load->library('Pdf');
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

    public function generate_barcode()
    {

        // This will output the barcode as HTML output to display in the browser
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
    }
    public function generate_barcode_pdf($id)
    {
        $data_workshop = $this->db->where('id', $id)->get('workshop')->row();


        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 027');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Surat Pengerjaan" . '- ' . strtoupper($data_workshop->nopol), "Daihatsu Narogong Bekasi");

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('helvetica', '', 11);

        // add a page
        $pdf->AddPage();

        // print a message
        $txt = "Detail Data:\nNopol:" . strtoupper($data_workshop->nopol) . "\nCustomer Name:" . strtoupper($data_workshop->customer_name) . "\nTotal:" . number_format($data_workshop->total, 2);
        $pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);
        $pdf->SetY(30);

        // -----------------------------------------------------------------------------

        $pdf->SetFont('helvetica', '', 10);

        // define barcode style
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );



        // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
        $pdf->Cell(0, 0, strtoupper($data_workshop->nopol) . " - " . strtoupper($data_workshop->customer_name), 0, 1);
        // $pdf->Cell(0, 0, 'CODE asd - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
        $pdf->write1DBarcode($id, 'C39', '', '', '', 18, 0.4, $style, 'N');

        //Close and output PDF document
        $pdf->Output('example_027.pdf', 'I');
    }
}

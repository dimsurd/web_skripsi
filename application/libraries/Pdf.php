<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
// require_once dirname(__FILE__) . '/tcpdf/tcpdf_barcodes_1d.php';
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}

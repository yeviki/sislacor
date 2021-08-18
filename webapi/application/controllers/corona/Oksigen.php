<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Oksigen extends REST_Controller {

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->model(array('Model_oksigen' => 'mOksigen'));
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    	$this->methods['oksigen_rs_get']['limit'] = 1000;
    	$this->methods['jenis_oksigen_get']['limit']   = 1000;
    }
    
    public function jenis_oksigen_get() {
        $dataOksigen = array();
        $dataShow = $this->mOksigen->get_StokKatOksigen();
		foreach ($dataShow as $key => $rs) {
            $row['id_kat_tabung']        = $rs['id_kat_tabung'];
            $row['nm_tabung']            = $rs['nm_tabung'];
            $row['total_stok_tabung']    = $rs['total_stok_tabung'];
            $row['jml_digunakan']        = $rs['jml_digunakan'];
            $row['sisa_tabung']          = $rs['sisa_tabung'];
            $dataOksigen[] = $row;
        }

        if(count($dataShow) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataOksigen
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function oksigen_rs_get() {
        $dataRS             = array();
        $jenisTabung         = array();
        $dataShow = $this->mOksigen->get_RS();
		foreach ($dataShow as $key => $rs) {
            $jenisTabung         = array();
            $row['id_rs']            = $rs['id_rs'];
            $row['shortname']        = $rs['shortname'];

            $dataKamar = $this->mOksigen->get_StokKatOksigenRS($rs['id_rs']);
            foreach($dataKamar as $show => $key ) {
                $jenisTabung[$show]['id_kat_tabung']         = !empty($key) ? $key['id_kat_tabung'] : '0';
                $jenisTabung[$show]['nm_tabung']             = !empty($key) ? $key['nm_tabung'] : '0';
                $jenisTabung[$show]['total_stok_tabung']     = !empty($key) ? $key['total_stok_tabung'] : '0';
                $jenisTabung[$show]['jml_digunakan']         = !empty($key) ? $key['jml_digunakan'] : '0';
                $jenisTabung[$show]['sisa_tabung']           = !empty($key) ? $key['sisa_tabung'] : '0';
            }
            $row['data_jenis_tabung']            = $jenisTabung;
            unset($jenisTabung);
            $dataRS[]                           = $row;
        }
        
        if(count($dataRS) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataRS
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

}

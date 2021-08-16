<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Kasus extends REST_Controller {

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->model(array('Model_kasus' => 'mKasus'));
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		// $this->methods['list_get']['limit']     = 1000;
    	$this->methods['totkasus_get']['limit'] = 1000;
    	$this->methods['totkasuskabkota_get']['limit'] = 1000;
    	$this->methods['totkasusharian_get']['limit']   = 500;
    	$this->methods['totkasuskabkotanow_get']['limit']   = 500;
	}

    public function totkasus_get() {
        $dataTotalKasus = array();
        $dataGet = $this->mKasus->get_TotalKasus();
		foreach ($dataGet as $key => $r) {
            $row['total_positif']       = format_ribuan($r['total_p']);
            $row['total_sembuh']        = format_ribuan($r['total_s']);
            $row['total_meninggal']     = format_ribuan($r['total_m']);
            $dataTotalKasus[]      = $row;
        }

        if(count($dataGet) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataTotalKasus
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function totkasusharian_get() {
        $dataTotalKasus = array();
        $dataGet = $this->mKasus->get_PenambahanKasusHarian();
		foreach ($dataGet as $key => $r) {
            $row['total_positif_now']       = format_ribuan($r['total_p_now']);
            $row['total_sembuh_now']        = format_ribuan($r['total_s_now']);
            $row['total_meninggal_now']     = format_ribuan($r['total_m_now']);
            $dataTotalKasus[]      = $row;
        }

        if(count($dataGet) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataTotalKasus
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function totkasuskabkota_get()
	{ 
        //get data kabupaten/kota
		$dataKasusPerKabKota = array();
        $dataKabKota = $this->mKasus->get_KasusKabKota();
        foreach ($dataKabKota as $key => $r) {
            $row['kabkota']             = $r['name'];
            $row['total_positif']       = format_ribuan($r['total_p']);
            $row['total_sembuh']        = format_ribuan($r['total_s']);
            $row['total_meninggal']     = format_ribuan($r['total_m']);
            $dataKasusPerKabKota[]      = $row;
        }
        if(count($dataKabKota) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataKasusPerKabKota
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
	}

    public function totkasuskabkotanow_get()
	{ 
        //get data kabupaten/kota
		$dataKasusPerKabKotaNew = array();
        $dataKabKota = $this->mKasus->get_KasusKabKotaHarian();
        foreach ($dataKabKota as $key => $r) {
            $row['kabkota']                 = $r['name'];
            $row['total_positif_now']       = format_ribuan($r['total_p_now']);
            $row['total_sembuh_now']        = format_ribuan($r['total_s_now']);
            $row['total_meninggal_now']     = format_ribuan($r['total_m_now']);
            $dataKasusPerKabKotaNew[]      = $row;
        }
        if(count($dataKabKota) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataKasusPerKabKotaNew
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
	}

}

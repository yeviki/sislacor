<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Vaksin extends REST_Controller {

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->model(array('Model_vaksin' => 'mVaksin'));
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		// $this->methods['list_get']['limit']     = 1000;
    	$this->methods['totvaksin_get']['limit'] = 1000;
    	$this->methods['pelatihanDetail_get']['limit']   = 500;
	}

    public function totvaksin_get() {
        $dataTotalVaksin = array();
        $dataAll = $this->mVaksin->get_TotalVaksin();
		foreach ($dataAll as $key => $r) {
            $row['total_vaksin_masuk']      = format_ribuan($r['total_vaksin_masuk']);
            $dataTotalVaksin[]              = $row;
        }

        if(count($dataAll) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataTotalVaksin
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function totjenisvaksin_get() {
        $dataTotalJenisVaksin = array();
        $dataAll = $this->mVaksin->get_TotalJenisVaksin();
		foreach ($dataAll as $key => $r) {
            $row['jenis_vaksin']         = $r['id_jenis_vaksin'];
            $row['total_jenis_vaksin']   = format_ribuan($r['total_vaksin_per_jenis']);
            $dataTotalJenisVaksin[]      = $row;
        }

        if(count($dataAll) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataTotalJenisVaksin
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function totsuplaivaksinkabkota_get()
	{ 
        //get data kabupaten/kota
		$dataVaksinPerKabKota = array();
        $dataShow = $this->mVaksin->get_SuplaiVaksinKabKota();
        foreach ($dataShow as $key => $r) {
            $row['kabkota']                 = $r['name'];
            $row['total_suplai_vaksin']     = format_ribuan($r['total_suplai_vaksin']);
            $dataVaksinPerKabKota[]         = $row;
        }
        if(count($dataShow) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataVaksinPerKabKota
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
	}

    public function totcapaianvaksinkabkota_get()
	{ 
        //get data kabupaten/kota
		$dataCapaianVaksinPerKabKota = array();
        $dataShow = $this->mVaksin->get_CapaianVaksinKabKota();
        foreach ($dataShow as $key => $r) {
            $row['kabkota']                  = $r['name'];
            $row['total_capaian_vaksin']     = format_ribuan($r['total_capaian_vaksin']);
            $row['total_suplai_vaksin']      = format_ribuan($r['total_suplai_vaksin']);
            $row['total_stok']               = format_ribuan($r['total_stok']);
            $dataCapaianVaksinPerKabKota[]   = $row;
        }
        if(count($dataShow) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataCapaianVaksinPerKabKota
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
	}

}

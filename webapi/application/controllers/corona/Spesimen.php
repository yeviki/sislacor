<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Spesimen extends REST_Controller {

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->model(array('Model_spesimen' => 'mSpesimen'));
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		// $this->methods['list_get']['limit']     = 1000;
    	$this->methods['totspesimen_get']['limit'] = 1000;
    	$this->methods['totkasuskabkota_get']['limit'] = 1000;
	}

    public function totspesimen_get() {
        $dataTotalSpesimen = array();
        $dataTotalNow = array();
        $dataGet = $this->mSpesimen->get_TotalSpesimen();
		foreach ($dataGet as $key => $r) {
            $row['total_spesimen']       = format_ribuan($r['total_sp']);
            $row['total_pemeriksaan']    = format_ribuan($r['total_pem']);
            
            $dataNow = $this->mSpesimen->get_PenambahanSpesimenHarian();
            foreach ($dataNow as $key => $rnow) {
                $rows['total_spesimen_now']       = !empty($rnow) ? format_ribuan($rnow['total_now_spesimen']) : '0';
                $rows['total_pemeriksaan_now']    = !empty($rnow) ? format_ribuan($rnow['total_now_pemeriksaan']) : '0';
                $dataTotalNow[]                   = $rows;
            }
            $row['data_now']        = $dataTotalNow;
            $dataTotalSpesimen[]    = $row;
        }

        if(count($dataGet) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataTotalSpesimen
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function totspesimenkabkota_get()
	{ 
        //get data kabupaten/kota
        $dataKabKota = array();
        $seluruh = array();
        $harian = array();
        $dataShow = $this->mSpesimen->get_KabKota();
        foreach ($dataShow as $key => $r) {
            $row['id_regency']              = $r['id'];
            $row['kabkota']                 = $r['name'];

            $dataAll = $this->mSpesimen->get_SpesimenKabKota($r['id']);
            $seluruh['total_spesimen']              = !empty($dataAll) ? format_ribuan($dataAll['total_sp']) : '0';
            $seluruh['total_pemeriksaan']           = !empty($dataAll) ? format_ribuan($dataAll['total_pem']) : '0';
            
            $dataHarian = $this->mSpesimen->get_SpesimenKabKotaHarian($r['id']);
            $harian['total_spesimen_now']           = !empty($dataHarian) ? format_ribuan($dataHarian['total_now_spesimen']) : '0';
            $harian['total_pemeriksaan_now']        = !empty($dataHarian) ? format_ribuan($dataHarian['total_now_pemeriksaan']) : '0';
            
            $dataMingguan = $this->mSpesimen->get_SpesimenKabKotaMingguan($r['id']);
            $ming['total_spesimen_week']            = !empty($dataMingguan) ? format_ribuan($dataMingguan['total_spesimen_week']) : '0';
            $ming['total_pemeriksaan_week']         = !empty($dataMingguan) ? format_ribuan($dataMingguan['total_pemeriksaan_week']) : '0';

            $row['data_spesimen_all']             = $seluruh;
            $row['data_spesimen_mingguan']        = $ming;
            $row['data_spesimen_harian']          = $harian;
            $dataKabKota[]                        = $row;
        }
        if(count($dataShow) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataKabKota
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
	}

}


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
	}

    public function totkasus_get() {
        $dataTotalKasus = array();
        $dataTotalNow = array();
        $dataGet = $this->mKasus->get_TotalKasus();
		foreach ($dataGet as $key => $r) {
            $row['total_positif']       = format_ribuan($r['total_p']);
            $row['total_sembuh']        = format_ribuan($r['total_s']);
            $row['total_meninggal']     = format_ribuan($r['total_m']);
            
            $dataNow = $this->mKasus->get_PenambahanKasusHarian();
            foreach ($dataNow as $key => $rnow) {
                $rows['total_positif_now']       = !empty($rnow) ? format_ribuan($rnow['total_p_now']) : '0';
                $rows['total_sembuh_now']        = !empty($rnow) ? format_ribuan($rnow['total_s_now']) : '0';
                $rows['total_meninggal_now']     = !empty($rnow) ? format_ribuan($rnow['total_m_now']) : '0';
                $dataTotalNow[]                  = $rows;
            }
            $row['data_now']        = $dataTotalNow;
            $dataTotalKasus[]       = $row;
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
        $dataKabKota = array();
        $seluruh = array();
        $harian = array();
        $dataShow = $this->mKasus->get_KabKota();
        foreach ($dataShow as $key => $r) {
            $row['id_regency']              = $r['id'];
            $row['kabkota']                 = $r['name'];

            $dataAll = $this->mKasus->get_KasusKabKota($r['id']);
            $seluruh['total_positif']       = !empty($dataAll) ? format_ribuan($dataAll['total_p']) : '0';
            $seluruh['total_sembuh']        = !empty($dataAll) ? format_ribuan($dataAll['total_s']) : '0';
            $seluruh['total_meninggal']     = !empty($dataAll) ? format_ribuan($dataAll['total_m']) : '0';  
            
            $dataHarian = $this->mKasus->get_KasusKabKotaHarian($r['id']);
            $harian['total_positif_now']       = !empty($dataHarian) ? format_ribuan($dataHarian['total_p_now']) : '0';
            $harian['total_sembuh_now']        = !empty($dataHarian) ? format_ribuan($dataHarian['total_s_now']) : '0';
            $harian['total_meninggal_now']     = !empty($dataHarian) ? format_ribuan($dataHarian['total_m_now']) : '0'; 
            
            $dataMingguan = $this->mKasus->get_KasusKabKotaMingguan($r['id']);
            $ming['total_positif_week']       = !empty($dataMingguan) ? format_ribuan($dataMingguan['total_p_week']) : '0';
            $ming['total_sembuh_week']        = !empty($dataMingguan) ? format_ribuan($dataMingguan['total_s_week']) : '0';
            $ming['total_meninggal_week']     = !empty($dataMingguan) ? format_ribuan($dataMingguan['total_m_week']) : '0'; 

            $row['data_kasus_all']             = $seluruh;
            $row['data_kasus_mingguan']        = $ming;
            $row['data_kasus_harian']          = $harian;
            $dataKabKota[]                     = $row;
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
    
    public function grafikkasus_post() {

        $tahun  = $this->post('tahun', TRUE);
        $bulan  = $this->post('bulan', TRUE);

        $dataGrafikKasus = array();
        $dataGrafik = $this->mKasus->post_GrafikTotalKasus($tahun, $bulan);
		foreach ($dataGrafik as $key => $r) {
            $row['regency_id']                 = $r['regency_id'];
            $row['name']                       = $r['name'];
            $row['tanggal']                    = $r['tanggal_kasus'];
            $row['grafik_total_positif']       = format_ribuan($r['total_positif']);
            $row['grafik_total_sembuh']        = format_ribuan($r['total_sembuh']);
            $row['grafik_total_meninggal']     = format_ribuan($r['total_meninggal']);
            $dataGrafikKasus[]       = $row;
        }

        if(count($dataGrafik) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataGrafikKasus
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function grafikKasusBulan_post() {

        $tahun  = $this->post('tahun', TRUE);
        $bulan  = $this->post('bulan', TRUE);

        $dataGrafikKasus = array();
        $dataGrafik = $this->mKasus->post_GrafikTotalKasusBulan($tahun, $bulan);
		foreach ($dataGrafik as $key => $r) {
            $row['tanggal']                    = tgl_indo($r['tanggal_kasus']);
            $row['grafik_total_positif']       = format_ribuan($r['total_positif']);
            $row['grafik_total_sembuh']        = format_ribuan($r['total_sembuh']);
            $row['grafik_total_meninggal']     = format_ribuan($r['total_meninggal']);
            $dataGrafikKasus[]       = $row;
        }

        if(count($dataGrafik) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataGrafikKasus
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

}

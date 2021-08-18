<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Kamar extends REST_Controller {

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->model(array('Model_kamar' => 'mKamar'));
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    	$this->methods['rs_get']['limit'] = 1000;
    	$this->methods['jenis_kamar_get']['limit']   = 1000;
    }
    
    public function jenis_kamar_get() {
        $dataKamar             = array();
        $dataShow = $this->mKamar->get_StokKatKamar();
		foreach ($dataShow as $key => $rs) {
            $row['id_kat_kamar']        = $rs['id_kat_kamar'];
            $row['nm_kamar']            = $rs['nm_kamar'];
            $row['total_kamar']         = $rs['total_kamar'];
            $row['jml_digunakan']       = $rs['jml_digunakan'];
            $row['sisa_kamar']          = $rs['sisa_kamar'];
            $dataKamar[]    = $row;
        }
        

        if(count($dataShow) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $dataKamar
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function rs_get() {
        $dataRS             = array();
        $jenisKamar         = array();
        $dataShow = $this->mKamar->get_RS();
		foreach ($dataShow as $key => $rs) {
            $jenisKamar         = array();
            $row['id_rs']            = $rs['id_rs'];
            $row['shortname']        = $rs['shortname'];

            $dataKamar = $this->mKamar->get_StokKatKamarRS($rs['id_rs']);
            foreach($dataKamar as $show => $key ) {
                $jenisKamar[$show]['id_kat_kamar']      = !empty($key) ? $key['id_kat_kamar'] : '0';
                $jenisKamar[$show]['nm_kamar']          = !empty($key) ? $key['nm_kamar'] : '0';
                $jenisKamar[$show]['total_kamar']       = !empty($key) ? $key['total_kamar'] : '0' ;
                $jenisKamar[$show]['jml_digunakan']     = !empty($key) ? $key['jml_digunakan'] : '0';
                $jenisKamar[$show]['sisa_kamar']        = !empty($key) ? $key['sisa_kamar'] : '0';
                
                
            }
            $row['data_jenis_kamar']            = $jenisKamar;
            unset($jenisKamar);
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

    public function rsshow_get() {
        $dataRS             = array();
        $jenisKamar         = array();
        $dataShow = $this->mKamar->get_RS();
        $data_jenis_kamar = $this->mKamar->get_KatKamar();
        $data_total_kamar = $this->mKamar->get_total_kamar();

        
		// foreach ($dataShow as $key => $rs) {
        //     $jenisKamar         = array();
        //     $row['id_rs']            = $rs['id_rs'];
        //     $row['shortname']        = $rs['shortname'];

        //     $dataKamar = $this->mKamar->get_StokKatKamarKabKota($rs['id_rs']);
        //     foreach($dataKamar as $show => $key ) {
        //         $jenisKamar[$show]['id_kat_kamar']      = !empty($key) ? $key['id_kat_kamar'] : '0';
        //         $jenisKamar[$show]['nm_kamar']          = !empty($key) ? $key['nm_kamar'] : '0';
        //         $jenisKamar[$show]['total_kamar']       = !empty($key) ? $key['total_kamar'] : '0' ;
        //         $jenisKamar[$show]['jml_digunakan']     = !empty($key) ? $key['jml_digunakan'] : '0';
        //         $jenisKamar[$show]['sisa_kamar']        = !empty($key) ? $key['sisa_kamar'] : '0';
                
                
        //     }
        //     $row['data_jenis_kamar']            = $jenisKamar;
        //     unset($jenisKamar);
        //     $dataRS[]                           = $row;
        // }
        $total_kamar = array();
        $arr_total_kamar = array();
        foreach ($data_total_kamar as $rdtk)
        {
            // $arr_total_kamar[] = array(
            //     'id_rs' => $rdtk['id_rs'],
            //     'id_kat_kamar' => $rdtk['id_kat_kamar'],
            //     'total_kamar' => $rdtk['total_kamar'],
            //     'tt' => $rdtk['id_rs']
                
            // );

            $total_kamar[$rdtk['id_rs']][$rdtk['id_kat_kamar']]= isset($rdtk['total_kamar']) ? $rdtk['total_kamar'] : '0';
        }

        $arr_jk = array();
        foreach ($data_jenis_kamar as $rdjk)
        {
            $arr_jk[] = array(
                'id_kat_kamar' => $rdjk['id_kat_kamar'],
                'nm_kamar' => $rdjk['nm_kamar'],
                'total_kamar' => isset($total_kamar['1'][$rdjk['id_kat_kamar']]) ? $total_kamar['1'][$rdjk['id_kat_kamar']] : '0',
                'jml_digunakan' => 0,
                'sisa_kamar' => 0,
            );
        }
        

        $arr_rs = array();
        foreach ($dataShow as $rds)
        {
            $arr_rs[] = array(
                            'id_rs' => $rds['id_rs'],
                            'shortname' => $rds['shortname'],
                            'data_jenis_kamar' => $arr_jk
            );
        }
        
        if(count($dataShow) > 0) {
            $this->response([
            'response' => 'RC200',
            'result' => $arr_rs//$total_kamar
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
            'response' => 'RC404',
            'result' => 'No data were found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

}

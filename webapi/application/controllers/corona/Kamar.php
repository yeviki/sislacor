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
    	$this->methods['totjeniskamar_get']['limit']   = 1000;
	}

    public function rs_get() {
        $dataRS             = array();
        // $jenisKamar         = array();
        $dataShow = $this->mKamar->get_RS();

        $data_kamar = $this->mKamar->get_KatKamar();

		// foreach ($dataShow as $key => $rs) {
        //     // $jenisKamar         = array();
        //     $row['id_rs']            = $rs['id_rs'];
        //     $row['shortname']        = $rs['shortname'];

        //     // $dataKamar = $this->mKamar->get_StokKatKamar($rs['id_rs']);
        //     // foreach($dataKamar as $show => $key ) {
        //     //     $jenisKamar[$show]['id_kat_kamar']      = !empty($key) ? $key['id_kat_kamar'] : '';
        //     //     $jenisKamar[$show]['nm_kamar']          = !empty($key) ? $key['nm_kamar'] : '';
        //     //     $jenisKamar[$show]['total_kamar']       = !empty($key) ? $key['total_kamar'] : '';
                
        //     // // }
        //     // $row['data_jenis_kamar']        = $jenisKamar;
        //     // unset($jenisKamar);
        //     $dataRS[]                       = $row;
        // }
        $tampilkan_rumah_sakit = array(
            
        );


        $dataRS = array(
            'rumah_sakit' => $tampilkan_rumah_sakit,
        );

        

        if(count($dataShow) > 0) {
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

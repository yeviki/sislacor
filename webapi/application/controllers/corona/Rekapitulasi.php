<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Rekapitulasi extends REST_Controller {
  protected $_key = "";
  function __construct()
  {
    // Construct the parent class
    parent::__construct();
    $this->load->model(array('model_rekap' => 'mrekap'));
    $this->_key = 'XBnKaywRCrj05m-XXX-v6DXuZ3FFkUgiw45';
    // Configure limits on our controller methods
    // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    $this->methods['publish_get']['limit'] = 1000;
    $this->methods['fasyankes_get']['limit'] = 1000;
  }

  public function publish_get()
  {
    $key = $this->get('token');
    if(!isset($key) OR $key != $this->_key) {
      $this->response([
        'response' => 'RC404',
        'result' => 'No data were found'
      ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
    } else {
      //get data rekapitulasi
      $data = array();
      $dataRekap = $this->mrekap->getDataListForPublish();
      $data['otg_last']   = !empty($dataRekap) ? $dataRekap['otg_last'] : 0;
      $data['otg_baru']   = !empty($dataRekap) ? $dataRekap['otg_baru'] : 0;
      $data['otg_bs']     = !empty($dataRekap) ? $dataRekap['otg_bs'] : 0;
      $data['otg_sembuh'] = !empty($dataRekap) ? $dataRekap['otg_sembuh'] : 0;
      $data['odp_last']   = !empty($dataRekap) ? $dataRekap['odp_last'] : 0;
      $data['odp_baru']   = !empty($dataRekap) ? $dataRekap['odp_baru'] : 0;
      $data['odp_bs']     = !empty($dataRekap) ? $dataRekap['odp_bs'] : 0;
      $data['odp_sembuh'] = !empty($dataRekap) ? $dataRekap['odp_sembuh'] : 0;
      $data['pdp_last']   = !empty($dataRekap) ? $dataRekap['pdp_last'] : 0;
      $data['pdp_baru']   = !empty($dataRekap) ? $dataRekap['pdp_baru'] : 0;
      $data['pdp_bs']     = !empty($dataRekap) ? $dataRekap['pdp_bs'] : 0;
      $data['pdp_sembuh'] = !empty($dataRekap) ? $dataRekap['pdp_sembuh'] : 0;
      $data['positif_last']       = !empty($dataRekap) ? $dataRekap['positif_last'] : 0;
      $data['positif_baru']       = !empty($dataRekap) ? $dataRekap['positif_baru'] : 0;
      $data['positif_meninggal']  = !empty($dataRekap) ? $dataRekap['positif_meninggal'] : 0;
      $data['positif_sembuh']     = !empty($dataRekap) ? $dataRekap['positif_sembuh'] : 0;
      if(count($dataRekap) > 0) {
        $this->response([
          'response' => 'RC200',
          'result' => $data
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
      } else {
        $this->response([
          'response' => 'RC404',
          'result' => 'No data were found'
        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
      }
    }
  }

  public function fasyankes_get()
  {
    $key = $this->get('token');
    if(!isset($key) OR $key != $this->_key) {
      $this->response([
        'response' => 'RC404',
        'result' => 'No data were found'
      ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
    } else {
      //get data rekapitulasi
      $data = array();
      $dataRs = $this->mrekap->getDataListFasyankes();
      foreach ($dataRs as $key => $r) {
        $row['name']    = $r['fullname'];
        $row['address'] = (($r['address'] != '') ? $r['address'].' ': '').$r['village'].', '.$r['district'].', '.$r['regency'].', '.$r['province'];
        $row['phone']   = $r['phone'];
        $data[] = $row;
      }
      if(count($dataRs) > 0) {
        $this->response([
          'response' => 'RC200',
          'result' => $data
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
      } else {
        $this->response([
          'response' => 'RC404',
          'result' => 'No data were found'
        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
      }
    }
  }
}

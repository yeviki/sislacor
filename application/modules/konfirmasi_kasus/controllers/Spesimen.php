<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of PDP class
 *
 * @author Yogi "solop" Kaputra
 */

class Spesimen extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_swab' => 'mswab', 'master/model_master' => 'mmas'));
		$this->load->library( array('cipusher'));
  }

	public function index()
	{
    $this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Konfirmasi Kasus', '#');
		$this->breadcrumb->add('Pengiriman Spesimen', '#');

		$this->session_info['page_name'] 			= "Pengiriman Spesimen";
		$this->session_info['data_province']  = $this->mmas->getDataProvince();
		$this->session_info['data_hospital']  = ($this->app_loader->is_admin()) ? $this->mmas->getDataMasterHospital() : $this->mmas->getDataMasterHospitalById($this->app_loader->current_hospital());
		$this->session_info['data_labor'] 		= $this->mmas->getDataMasterLaboratorium();
		$this->session_info['data_spesimen'] 	= $this->mmas->getDataMasterSpesimen();
		$this->session_info['kode_rs']				= ($this->app_loader->is_admin()) ? '' : $this->app_loader->current_hospital();
		$this->template->build('form_swab/vlist', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
				$param = $this->input->post('param',TRUE);
		    $dataList = $this->mswab->get_datatables($param);
				$no  = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
	        $row = array();
	        $row[] = $no;
					$row[] = $dl['kode_swab'];
					$row[] = date('d/m/Y', strtotime($dl['tgl_kirim']));
					$row[] = 'Hari ke-'.$dl['hari_ke'];
					$row[] = spesimen($dl['spesimen']);
					$row[] = strtoupper($dl['namalkp']).' ('.$dl['umur'].' th)';
					$row[] = ($dl['status']==1) ? '<span class="label label-warning">DIKIRIM</span>' : '<span class="label label-primary">DITERIMA</span>';
					$row[] = ($dl['hasil']=='') ? '<span class="label label-info">Menunggu</span>' : (($dl['hasil']=='I') ? '<span class="label label-warning">INCONCLUSIVE</span>' : (($dl['hasil']=='N') ? '<span class="label label-success">NEGATIF</span>' : '<span class="label label-danger">POSITIF</span>'));
				 	$row[] = '<button type="button" class="btn btn-sm btn-orange btnView" data-id="'.$this->encryption->encrypt($dl['id_swab']).'" data-token="'.$dl['token'].'" data-flag="'.(($dl['status']==1 AND $dl['hasil']=='') ? 1 : 0).'"><i class="fa fa-pencil"></i> </button>';
	        $data[] = $row;
				}

				$output = array(
	        "draw" => $this->input->post('draw'),
	        "recordsTotal" => $this->mswab->count_all(),
	        "recordsFiltered" => $this->mswab->count_filtered($param),
	        "data" => $data,
	      );
			}
			//output to json format
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
	}

	public function create()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			if(!empty($session)) {
				if($this->mswab->validasiDataValue(1) == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mswab->insertDataSpesimen();
					if($data['message'] == 'ERROR')
						$result = array('status' => 0, 'message' => array('isi' => 'Data pasien pemilik spesimen tidak ditemukan, harap periksa kembali...'), 'csrfHash' => $csrfHash);
					else if($data['message'] == 'HAVECODE')
						$result = array('status' => 0, 'message' => array('isi' => 'Kode spesimen <b>'.$data['nama'].'</b> yang anda masukan sudah ada dan sudah dikirim ke laboratorium...'), 'csrfHash' => $csrfHash);
					else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data spesimen pasien <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> yang akan diperiksa telah berhasil dikirimkan ke laboratorium...', 'csrfHash' => $csrfHash);
						$this->cipusher->send(array('message'=>'swabsuccess'));
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses kirim spesimen baru gagal...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function details()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$id_swab  = escape($this->input->post('swab', TRUE));
			$token    = escape($this->input->post('token', TRUE));
			if(!empty($id_swab) AND !empty($token) AND !empty($session)) {
				$row = array();
				$data = $this->mswab->getDataDetailSpesimen($this->encryption->decrypt($id_swab), $token);
				$row['hospital'] 	= !empty($data) ? $data['id_hospital'] : '';
				$row['labor']			= !empty($data) ? $data['id_labor'] : '';
				$row['pasien']		= !empty($data) ? $data['namalkp'].' ['.$data['nik'].']' : '';
				$row['hari']			= !empty($data) ? $data['hari_ke'] : '';
				$row['kode']			= !empty($data) ? $data['kode_swab'] : '';
				$row['spesimen']	= !empty($data) ? $data['spesimen'] : '';
				$row['ambil']			= !empty($data) ? date('d/m/Y', strtotime($data['tgl_ambil'])) : '';
				$row['kirim']			= !empty($data) ? date('d/m/Y', strtotime($data['tgl_kirim'])) : '';
				$row['status']    = !empty($data) ? $data['status'] : '';
				$row['hasil']    	= !empty($data) ? $data['hasil'] : '';
				$row['keluar']    = !empty($data) ? (($data['tgl_keluar'] != '0000-00-00') ? date('d/m/Y', strtotime($data['tgl_keluar'])) : '') : '';
				$row['ket']    		= !empty($data) ? $data['keterangan'] : '';
				$result = array('status' => 1, 'message' => $row, 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => array(), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function update()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$id_swab 	= escape($this->input->post('swabId', TRUE));
			if(!empty($session) AND !empty($id_swab)) {
				if($this->mswab->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mswab->updateDataSpesimen();
					if($data['message'] == 'ERROR')
						$result = array('status' => 0, 'message' => array('isi' => 'Data pasien pemilik spesimen tidak ditemukan, harap periksa kembali...'), 'csrfHash' => $csrfHash);
					else if($data['message'] == 'HAVECODE')
						$result = array('status' => 0, 'message' => array('isi' => 'Kode spesimen <b>'.$data['nama'].'</b> yang anda masukan sudah ada dan sudah dikirim ke laboratorium...'), 'csrfHash' => $csrfHash);
					else if($data['message'] == 'FAILED')
						$result = array('status' => 0, 'message' => array('isi' => 'Data spesimen pasien <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> sudah tidak bisa diperbaharui karena proses pemeriksaan sudah selesai dan hasil lab sudah keluar...'), 'csrfHash' => $csrfHash);
					else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data spesimen pasien <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> yang dikirim ke laboratorium berhasil diperbaharui...', 'csrfHash' => $csrfHash);
						$this->cipusher->send(array('message'=>'swabsuccess'));
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses memperharui data spesimen gagal...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	//get akumulasi kasus
	public function akumulasi()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			if(!empty($session)) {
				$arrData = array();
				$dataAll = $this->mswab->getDataAkumulasiSpesimen();
				foreach ($dataAll as $key => $d) {
					$arrData[$d['key_column']] = (int) $d['total'];
				}
				$arrNew = array();
				$dataNew = $this->mswab->getDataAkumulasiSpesimen(TRUE);
				foreach ($dataNew as $key => $n) {
					$arrNew[$n['key_column']] = (int) $n['total'];
				}
				$result = array('total' => $arrData, 'today'=>$arrNew);
			} else {
				$result = array('total' => array(), 'today'=>array());
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function searching()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
				$row = array();
				$keyword  = escape($this->input->get('q', TRUE));
				$hospital = !empty($this->input->get('rs', TRUE)) ? escape($this->input->get('rs', TRUE)) : $this->app_loader->current_hospital();
				$dataPasien = $this->mswab->getDataListPasien($keyword, $hospital);
				if(count($dataPasien) > 0) {
					foreach ($dataPasien as $key => $p) {
		        $item['token']  = $p['token'];
		        $item['nama']   = strtoupper($p['namalkp']);
						$item['nik'] 	  = $p['nik'];
						$item['umur']  	= $p['umur'];
		        $item['gender'] = strtoupper(jenis_kelamin($p['gender']));
		        $row[] = $item;
					}
				}
				$data['items'] 				= $row;
				$data['total_count']	= count($dataPasien);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}

	//get data kab/kota
  public function regency()
  {
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$province = $this->input->get('province', TRUE);
			if(!empty($province)AND !empty($session)) {
				$data = $this->mmas->getDataRegencyByProvince($province);
				if(count($data) > 0) {
					$row = array();
					foreach ($data as $key => $val) {
						$row['id'] 		= $val['id'];
						$row['text']	= ($val['status'] == 1) ? "KAB ".$val['name'] : $val['name'];
						$hasil[] = $row;
					}
					$result = array('status' => 1, 'message' => $hasil, 'csrfHash' => $csrfHash);
				} else
					$result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
  }

  //get data kecamatan
  public function district()
  {
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$regency = $this->input->get('regency', TRUE);
			if(!empty($regency)AND !empty($session)) {
				$data = $this->mmas->getDataDistrictByRegency($regency);
				if(count($data) > 0) {
					$row = array();
					foreach ($data as $key => $val) {
						$row['id'] 		= $val['id'];
						$row['text']	= $val['name'];
						$hasil[] = $row;
					}
					$result = array('status' => 1, 'message' => $hasil, 'csrfHash' => $csrfHash);
				} else
					$result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
  }

  //get data Kelurahan/desa/nagari
  public function village()
  {
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$district = $this->input->get('district', TRUE);
			if(!empty($district)AND !empty($session)) {
				$data = $this->mmas->getDataVillageByDistrict($district);
				if(count($data) > 0) {
					$row = array();
					foreach ($data as $key => $val) {
						$row['id'] 		= $val['id'];
						$row['text']	= $val['name'];
						$hasil[] = $row;
					}
					$result = array('status' => 1, 'message' => $hasil, 'csrfHash' => $csrfHash);
				} else
					$result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => '', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
  }
}

// This is the end of home clas

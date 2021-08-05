<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of OTG class
 *
 * @author Yogi "solop" Kaputra
 */

class Hospital extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_hospital' => 'mrs', 'model_master' => 'mmas'));
  }

	public function index()
	{
    $this->breadcrumb->add('Dashboard', site_url('home'));
    $this->breadcrumb->add('Master', '#');
		$this->breadcrumb->add('Fasilitas Layanan Kesehatan', '#');

		$this->session_info['page_name'] = "Data Fasyankes";
    $this->session_info['province']  = $this->mmas->getDataProvince();
    $this->template->build('form_hospital/vlist', $this->session_info);
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
		    $dataList = $this->mrs->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
	        $row = array();
	        $row[] = $no;
					$row[] = $dl['kode_fasyankes'];
					$row[] = $dl['fullname'];
					$row[] = provinsi($dl['id_province']);
					$row[] = regency($dl['id_regency']);
					$row[] = ($dl['flag'] == 1) ? 'RS Rujukan Pemerintah' : 'RS Daerah';
					$row[] = convert_status($dl['status']);
	        $row[] = '<button type="button" class="btn btn-sm btn-orange btnEdit" data-id="'.$this->encryption->encrypt($dl['id_rs']).'"><i class="fa fa-pencil"></i> </button>';
	        $data[] = $row;
				}

				$output = array(
	        "draw" => $this->input->post('draw'),
	        "recordsTotal" => $this->mrs->count_all(),
	        "recordsFiltered" => $this->mrs->count_filtered($param),
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
				if($this->mrs->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mrs->insertDataHospital();
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses input data <b>'.$data['nama'].'</b> dengan kode <b>'.$data['kode'].'</b> gagal, karena sudah ada fasilitas layanan kesehatan yang terdaftar dengan kode tersebut...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data fasilitas layanan kesehatan <b>'.$data['nama'].'</b> dengan kode <b>'.$data['kode'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses input data fasilitas layanan kesehatan gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
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
			$id_rs  	= $this->input->post('token', TRUE);
			if(!empty($id_rs) AND !empty($session)) {
				$data = $this->mrs->getDataDetailHospital($this->encryption->decrypt($id_rs));
				$row = array();
				$row['kode'] 			= !empty($data) ? $data['kode_fasyankes'] : '';
				$row['fullname']	= !empty($data) ? $data['fullname'] : '';
				$row['shortname']	= !empty($data) ? $data['shortname'] : '';
				$row['province']	= !empty($data) ? $data['id_province'] : '';
				$row['regency']		= !empty($data) ? $data['id_regency'] : '';
				$row['district']	= !empty($data) ? $data['id_district'] : '';
				$row['village']		= !empty($data) ? $data['id_village'] : '';
				$row['address']		= !empty($data) ? $data['address'] : '';
				$row['phone']			= !empty($data) ? $data['phone'] : '';
				$row['tipe'] 			= !empty($data) ? $data['tipe_fasyankes'] : '';
				$row['flag']			= !empty($data) ? $data['flag'] : '';
				$row['status']		= !empty($data) ? $data['status'] : 1;
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
			$id_rs 		= escape($this->input->post('tokenId', TRUE));
			if(!empty($session) AND !empty($id_rs)) {
				if($this->mrs->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mrs->updateDataHospital();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data layanan fasilitas kesehatan gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					}	else if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data <b>'.$data['nama'].'</b> dengan kode <b>'.$data['kode'].'</b> gagal, karena sudah ada fasilitas layanan kesehatan yang terdaftar dengan kode tersebut...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data fasilitas layanan kesehatan <b>'.$data['nama'].'</b> dengan kode <b>'.$data['kode'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses update data fasilitas layanan kesehatan gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
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

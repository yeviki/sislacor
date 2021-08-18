<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Pemakaian_tabung extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'oksigen/pemakaian-tabung';
		$this->load->model(array('Model_pemakaian_tabung' => 'mPemakaianTabung', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Oksigen', '#');
		$this->breadcrumb->add('Pemakaian Tabung', '#');
		$this->session_info['list_tabung']   			= $this->mPemakaianTabung->getDataKatTabung();
		$this->session_info['list_id_rs']   			= $this->mmas->getDataMasterHospital();
		$this->session_info['page_name'] = "Pemakaian Oksigen";

    	$this->template->build('pemakaian_tabung/vlist_new', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data    = array();
			$rsud 	 = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
				$param = $this->input->post('param',TRUE);
				$dataList  	= $this->mPemakaianTabung->get_datatables($param);
				$dataTabung = $this->mPemakaianTabung->getDataKatTabung();
				$no = $this->input->post('start');
				foreach ($dataList as $key => $k) {
					$no++;
					$row = array();
					$row[] = $no;
					$row[] = tgl_indo($k['tanggal_pemakaian']);
					$row[] = $k['shortname'];
					foreach ($dataTabung as $room => $dk) {
						$total = $this->mPemakaianTabung->getTotalTerpakai($k['tanggal_pemakaian'], $k['id_rs'], $dk['id_kat_tabung']);
						$row[] = !empty($total['total_terpakai']) ? $total['total_terpakai'] : 0;
					}
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($k['id_rs']).'" data-date="'.$k['tanggal_pemakaian'].'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($k['id_rs']).'" data-date="'.$k['tanggal_pemakaian'].'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mPemakaianTabung->count_all(),
					"recordsFiltered" => $this->mPemakaianTabung->count_filtered($param),
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
				if($this->mPemakaianTabung->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mPemakaianTabung->insertData();
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses simpan data gagal, data pada tanggal tersebut sudah ada...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data pemakaian tabung tanggal <b>'.$data['tanggal_pemakaian'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses input data TPA gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function review()
	{
		// print_r($_POST);
		// exit; 
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  		= $this->app_loader->current_account();
			$csrfHash 		= $this->security->get_csrf_hash();
			$id_rs  		= $this->input->post('rsId', TRUE);
			if(!empty($id_rs) AND !empty($session)) {
				$data = $this->mPemakaianTabung->getDataStokTabung($id_rs);
				// print_r($data);
				// die; 	
				$row = array(); $no=1;
				foreach ($data as $key => $val) {
					$row['no'] 				= $no;
					$row['nm_tabung']		= $val['nm_tabung'];
					$row['sisa_tabung']		= $val['sisa_tabung'];
					$hasil[] = $row;
					$no++;
				}
				// print_r($hasil); die;
				$result = array('status' => 1, 'message' => $hasil, 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => array(), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function details()
	{
		// print_r($_POST);
		// exit; 
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		 } else {
			 $session  		= $this->app_loader->current_account();
			 $csrfHash 		= $this->security->get_csrf_hash();
			 $id_rs  		= $this->input->post('rsId', TRUE);
			 $tanggal 		= $this->input->post('publishDate', TRUE);
			 if(!empty($id_rs) AND !empty($session)) {
				 $data = $this->mPemakaianTabung->getDataDetail($this->encryption->decrypt($id_rs), $tanggal);
				//  print_r($data);
				//  die; 	
				 $data_show = array('id_rs' => $this->encryption->decrypt($id_rs), 'tanggal_pemakaian' => date('d/m/Y', strtotime($tanggal)));
				 $row = array(); $no=1;
				 foreach ($data as $key => $val) {
					 $row['no'] 				= $no;
					 $row['nm_tabung']			= $val['nm_tabung'];
					 $row['total_terpakai']		= $val['total_terpakai'];
					 $hasil[] = $row;
					 $no++;
				 }
				 // print_r($hasil); die;
				 $result = array('status' => 1, 'message' => $hasil, 'detail' => $data_show, 'csrfHash' => $csrfHash);
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
			$param    = $this->input->post('param', TRUE);
			$rs_id    = escape($this->input->post('rsId', TRUE));
			if(!empty($session) AND !empty($rs_id) AND !empty($param)) {
				if($this->mPemakaianTabung->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mPemakaianTabung->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data berhasil diperbaharui...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function delete()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  		= $this->app_loader->current_account();
			$csrfHash 		= $this->security->get_csrf_hash();
			$id_pemakaian_tabung 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_pemakaian_tabung)) {
				$data = $this->mPemakaianTabung->deleteData();
				if($data['message'] == 'ERROR') {
					$result = array('status' => 0, 'message' => 'Proses delete data gagal dikarenakan data tidak ditemukan...', 'csrfHash' => $csrfHash);
				}	else if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => 'Data telah didelete...', 'csrfHash' => $csrfHash);
				}
			} else {
				$result = array('status' => 0, 'message' => 'Proses delete data gagal...', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}
}

// This is the end of home class

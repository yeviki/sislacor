<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Rs_kamar extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'kamar/rs_kamar';
		$this->load->model(array('Model_rs_kamar' => 'mRsKamar', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Kamar', '#');
		$this->breadcrumb->add('Ketersedaan Kamar', '#');
		// $this->session_info['list_id_kat_kamar']   	= $this->mmas->getDataKamar();
		$this->session_info['list_kamar']   	= $this->mRsKamar->getDataKategoriKamar();
		$this->session_info['list_id_rs']   		= $this->mmas->getDataMasterHospital();
		$this->session_info['page_name'] = "Ketersedaan Kamar";

    	$this->template->build('rs_kamar/vlist_new', $this->session_info);
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
		    	$dataList = $this->mRsKamar->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['tanggal'];
							$row[] = $dl['shortname'];
							$row[] = $dl['rekap'];
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_rs']).'" data-date="'.$dl['tanggal'].'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_rs']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mRsKamar->count_all(),
					"recordsFiltered" => $this->mRsKamar->count_filtered($param),
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
				if($this->mRsKamar->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mRsKamar->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data capaian vaksin pada tanggal <b>'.$data['tanggal'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses input data TPA gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
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
			$id_rs  		= $this->input->post('regencyId', TRUE);
			$tanggal 		= $this->input->post('publishDate', TRUE);
			if(!empty($id_rs) AND !empty($session)) {
				$data = $this->mRsKamar->getDataDetail($this->encryption->decrypt($id_rs), $tanggal);
				// print_r($data);
				// die; 	
				$test = array('id_rs' => $this->encryption->decrypt($id_rs), 'tanggal' => $tanggal);
				$row = array(); $no=1;
				foreach ($data as $key => $val) {
					$row['no'] 				= $no;
					$row['nm_kamar']		= $val['nm_kamar'];
					$row['total_kamar']		= $val['total_kamar'];
					$hasil[] = $row;
					$no++;
				}

				// print_r($hasil); die;
				$result = array('status' => 1, 'message' => $hasil, 'detail' => $test, 'csrfHash' => $csrfHash);
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
			$id_rs_kamar  = $this->input->post('vaksinId', TRUE);
			if(!empty($session) AND !empty($id_rs_kamar)) {
				if($this->mRsKamar->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mRsKamar->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan <b>'.$data['tanggal'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
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
			$id_rs_kamar 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_rs_kamar)) {
				$data = $this->mRsKamar->deleteData();
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

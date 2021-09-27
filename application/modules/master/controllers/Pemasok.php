<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Pemasok extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'master/pemasok';
		$this->load->model(array('Model_pemasok' => 'mPemasok', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Master', '#');
		$this->breadcrumb->add('Pemasok', '#');
		$this->session_info['page_name'] = "Pemasok";
    	$this->template->build('from_pemasok/vlist', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
		    	$dataList = $this->mPemasok->get_datatables();
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['nm_penyalur'];
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_penyalur']).'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_penyalur']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mPemasok->count_all(),
					"recordsFiltered" => $this->mPemasok->count_filtered(),
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
				if($this->mPemasok->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mPemasok->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data berhasil ditambahkan...', 'csrfHash' => $csrfHash);
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
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  		= $this->app_loader->current_account();
			$csrfHash 		= $this->security->get_csrf_hash();
			$id_penyalur  = $this->input->post('pemasokId', TRUE);
			if(!empty($id_penyalur) AND !empty($session)) {
				$data = $this->mPemasok->getDataDetail($this->encryption->decrypt($id_penyalur));
				$row = array();
				$row['id_penyalur']		=	!empty($data) ? $data['id_penyalur'] : '';
				$row['nm_penyalur']				=	!empty($data) ? $data['nm_penyalur'] : '';

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
			$id_penyalur  = $this->input->post('pemasokId', TRUE);
			if(!empty($session) AND !empty($id_penyalur)) {
				if($this->mPemasok->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mPemasok->updateData();
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
			$id_penyalur 	= escape($this->input->post('pemasokId', TRUE));
			if(!empty($session) AND !empty($id_penyalur)) {
				$data = $this->mPemasok->deleteData();
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

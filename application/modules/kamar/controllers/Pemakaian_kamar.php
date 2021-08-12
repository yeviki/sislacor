<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Pemakaian_kamar extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'kamar/pemakaian-kamar';
		$this->load->model(array('Model_pemakaian_kamar' => 'mPemakaianKamar', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Kamar', '#');
		$this->breadcrumb->add('Pemakaian Kamar', '#');
		$this->session_info['list_pemakaian_kamar']   	= $this->mmas->getDataStokKamar();
        $this->session_info['list_id_kat_kamar']   	    = $this->mmas->getDataKamar();
		$this->session_info['list_id_rs']   			= $this->mmas->getDataMasterHospital();
		$this->session_info['page_name'] = "Pemakaian Kamar";

    	$this->template->build('pemakaian_kamar/vlist', $this->session_info);
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
		    	$dataList = $this->mPemakaianKamar->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['tanggal_pemakaian'];
							$row[] = rujukan($dl['id_rs']);
							$row[] = kamar($dl['id_kat_kamar']);
							$row[] = format_ribuan($dl['total_terpakai']);
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_pemakaian_kamar']).'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_pemakaian_kamar']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mPemakaianKamar->count_all(),
					"recordsFiltered" => $this->mPemakaianKamar->count_filtered($param),
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
				if($this->mPemakaianKamar->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mPemakaianKamar->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data capaian vaksin pada tanggal pemakaian <b>'.$data['tanggal_pemakaian'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
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
			$id_pemakaian_kamar  = $this->input->post('vaksinId', TRUE);
			if(!empty($id_pemakaian_kamar) AND !empty($session)) {
				$data = $this->mPemakaianKamar->getDataDetail($this->encryption->decrypt($id_pemakaian_kamar));
				$row = array();
				$row['id_pemakaian_kamar']			=	!empty($data) ? $data['id_pemakaian_kamar'] : '';
				$row['total_terpakai']			    =	!empty($data) ? $data['total_terpakai'] : '';
				$row['id_rs_kamar']				    =	!empty($data) ? $data['id_rs_kamar'] : '';
				$row['tanggal_pemakaian']			= 	!empty($data) ? date('d/m/Y', strtotime($data['tanggal_pemakaian'])) : '';

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
			$id_pemakaian_kamar  = $this->input->post('vaksinId', TRUE);
			if(!empty($session) AND !empty($id_pemakaian_kamar)) {
				if($this->mPemakaianKamar->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mPemakaianKamar->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan tanggal <b>'.$data['tanggal_pemakaian'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
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
			$id_pemakaian_kamar 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_pemakaian_kamar)) {
				$data = $this->mPemakaianKamar->deleteData();
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

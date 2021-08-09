<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of Vaksin Masuk class
 *
 * @author Yogi "solop" Kaputra
 */

class Suplai_vaksin extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'vaksinasi/suplai-vaksin';
		$this->load->model(array('Model_suplai_vaksin' => 'mSuplaiVaksin', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Vaksinasi', '#');
		$this->breadcrumb->add('Vaksin Keluar', '#');

		$this->session_info['page_name'] 			= "Vaksin Keluar";
		$this->session_info['list_penyalur']    	= $this->mmas->getDataPenyalur();
		$this->session_info['list_jenis_vaksin']    = $this->mmas->getDataJenisVaksin();
		$this->session_info['list_kabkota']         = $this->mmas->getDataRegency();

    	$this->template->build('vaksin_keluar/vlist', $this->session_info);
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
		    	$dataList = $this->mSuplaiVaksin->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['tanggal_suplai'];
							$row[] = format_ribuan($dl['total_suplai']);
							$row[] = $dl['nm_vaksin'];
							$row[] = $dl['nm_penyalur'];
							$row[] = regency($dl['regency_id']);
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_suplai_vaksin']).'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_suplai_vaksin']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mSuplaiVaksin->count_all(),
					"recordsFiltered" => $this->mSuplaiVaksin->count_filtered($param),
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
				if($this->mSuplaiVaksin->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mSuplaiVaksin->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data vaksin keluar pada tanggal suplai <b>'.$data['tanggal_suplai'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
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
			$id_suplai_vaksin  = $this->input->post('vaksinId', TRUE);
			if(!empty($id_suplai_vaksin) AND !empty($session)) {
				$data = $this->mSuplaiVaksin->getDataDetail($this->encryption->decrypt($id_suplai_vaksin));
				$row = array();
				$row['total_suplai']		=	!empty($data) ? $data['total_suplai'] : '';
				$row['id_jenis_vaksin']		=	!empty($data) ? $data['id_jenis_vaksin'] : '';
				$row['id_penyalur']			=	!empty($data) ? $data['id_penyalur'] : '';
				$row['regency_id']			=	!empty($data) ? $data['regency_id'] : '';
				$row['tanggal_suplai']		= 	!empty($data) ? date('d/m/Y', strtotime($data['tanggal_suplai'])) : '';

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
			$id_suplai_vaksin  = $this->input->post('vaksinId', TRUE);
			if(!empty($session) AND !empty($id_suplai_vaksin)) {
				if($this->mSuplaiVaksin->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mSuplaiVaksin->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan <b>'.$data['tanggal_suplai'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
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
			$id_suplai_vaksin 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_suplai_vaksin)) {
				$data = $this->mSuplaiVaksin->deleteData();
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

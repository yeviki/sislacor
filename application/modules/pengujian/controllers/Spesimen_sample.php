<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Spesimen_sample extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'pengujian/spesimen-sample';
		$this->load->model(array('Model_sample' => 'mSpesimen', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Pengujian', '#');
		$this->breadcrumb->add('Spesimen Sample', '#');
		$this->session_info['page_name'] = "Spesimen Sample";
		$this->session_info['list_regency_id']  = $this->mmas->getDataRegency();
    	$this->template->build('vpengujian/vlist', $this->session_info);
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
		    	$dataList = $this->mSpesimen->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['tanggal_spesimen'];
							$row[] = regency($dl['regency_id']);
							$row[] = $dl['total_spesimen'];
							$row[] = $dl['total_pemeriksaan'];
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_spesimen_sample']).'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_spesimen_sample']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mSpesimen->count_all(),
					"recordsFiltered" => $this->mSpesimen->count_filtered($param),
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
				if($this->mSpesimen->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mSpesimen->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data kasus pada tanggal <b>'.$data['tanggal_spesimen'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
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
			$id_spesimen_sample  = $this->input->post('vaksinId', TRUE);
			if(!empty($id_spesimen_sample) AND !empty($session)) {
				$data = $this->mSpesimen->getDataDetail($this->encryption->decrypt($id_spesimen_sample));
				$row = array();
				$row['id_spesimen_sample']		=	!empty($data) ? $data['id_spesimen_sample'] : '';
				$row['regency_id']				=	!empty($data) ? $data['regency_id'] : '';
				$row['total_pemeriksaan']		=	!empty($data) ? $data['total_pemeriksaan'] : '';
				$row['total_spesimen']			=	!empty($data) ? $data['total_spesimen'] : '';
				$row['tanggal_spesimen']		= 	!empty($data) ? date('d/m/Y', strtotime($data['tanggal_spesimen'])) : '';

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
			$id_spesimen_sample  = $this->input->post('vaksinId', TRUE);
			if(!empty($session) AND !empty($id_spesimen_sample)) {
				if($this->mSpesimen->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mSpesimen->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan tanggal kasus <b>'.$data['tanggal_spesimen'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
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
			$id_spesimen_sample 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_spesimen_sample)) {
				$data = $this->mSpesimen->deleteData();
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

	public function upload()
    {
        include APPPATH . 'third_party/PHPExcel.php';
        $create_by   	= $this->app_loader->current_account();
        $create_date 	= gmdate('Y-m-d H:i:s', time() + 60 * 60 * 7);
        $create_ip   	= $this->input->ip_address();
        $csrfHash 		= $this->security->get_csrf_hash();
        $namafile  		= $_FILES['file']['name'];
        $lokasi    		= $_FILES['file']['tmp_name'];
        move_uploaded_file($lokasi, './repository/temporary/' . $namafile);
        $excelreader     	= new PHPExcel_Reader_Excel2007();
        $spreadsheet 		= $excelreader->load('repository/temporary/' . $namafile);
        $sheetdata 			= $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // echo "<pre>";
        // print_r($sheetdata);
        // echo "</pre>";
		// exit;
		
        $dataexcel = array();
        $numrow = 1;
        foreach ($sheetdata as $row) {
			if ($numrow > 2) { // kalau row ke satu di excel adalah nama th table
                array_push($dataexcel, array(
                    'tanggal_spesimen'  => $row['A'] ? $row['A'] : '',
                    'regency_id'        => explode(' - ', $row['B'])[0],
                    'total_spesimen'    => $row['C'] ? $row['C'] : '0',
                    'total_pemeriksaan' => $row['D'] ? $row['D'] : '0',
                    'create_by'         => $create_by,
                    'create_date'       => $create_date,
                    'create_ip'         => $create_ip,
                    'mod_by'            => $create_by,
                    'mod_date'          => $create_date,
                    'mod_ip'            => $create_ip,
                ));
            }
            $numrow++;
        }

        $response =  $this->db->insert_batch('ta_spesimen_sample', $dataexcel);
        if ($response == true) {
            unlink(realpath('repository/temporary/' . $namafile));
            $json_data = array(
                'csrfnew' => $csrfHash,
                'message' => 'Berhasil Di Simpan',
                'response' => true
            );
            return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($json_data));
        } else {
            $json_data = array(
                'csrfnew' => $csrfHash,
                'message' => 'Gagal Disimpan',
                'response' => false
            );
            return $this->output->set_status_header(422)->set_content_type('application/json')->set_output(json_encode($json_data));
        }
    }
}

// This is the end of home class

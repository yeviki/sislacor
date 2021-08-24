<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of Suplai Capaian Vaksin
 *
 * @author Yogi "solop" Kaputra
 */

class Capaian_vaksin extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'vaksinasi/capaian-vaksin';
		$this->load->model(array('Model_capaian_vaksin' => 'mCapaianVaksin', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Vaksinasi', '#');
		$this->breadcrumb->add('Capaian Vaksin', '#');

		$this->session_info['page_name'] 	        = "Capaian Vaksin";
		$this->session_info['list_suplai_vaksin']   = $this->mmas->getDataSuplai();
        $this->session_info['list_penyalur']    	= $this->mmas->getDataPenyalur();
		$this->session_info['list_jenis_vaksin']    = $this->mmas->getDataJenisVaksin();

    	$this->template->build('capaian_vaksin/vlist', $this->session_info);
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
		    	$dataList = $this->mCapaianVaksin->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['tanggal_capaian'];
							$row[] = regency($dl['regency_id']);
							$row[] = $dl['nm_penyalur'];
							$row[] = $dl['nm_vaksin'];
							$row[] = format_ribuan($dl['total_vaksinasi']);
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_capaian_vaksin']).'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_capaian_vaksin']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mCapaianVaksin->count_all(),
					"recordsFiltered" => $this->mCapaianVaksin->count_filtered($param),
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
				if($this->mCapaianVaksin->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mCapaianVaksin->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data capaian vaksin pada tanggal <b>'.$data['tanggal_capaian'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
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
			$id_capaian_vaksin  = $this->input->post('vaksinId', TRUE);
			if(!empty($id_capaian_vaksin) AND !empty($session)) {
				$data = $this->mCapaianVaksin->getDataDetail($this->encryption->decrypt($id_capaian_vaksin));
				$row = array();
				$row['id_suplai_vaksin']	=	!empty($data) ? $data['id_suplai_vaksin'] : '';
				$row['total_vaksinasi']		=	!empty($data) ? $data['total_vaksinasi'] : '';
				$row['tanggal_capaian']		= 	!empty($data) ? date('d/m/Y', strtotime($data['tanggal_capaian'])) : '';

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
			$id_capaian_vaksin  = $this->input->post('vaksinId', TRUE);
			if(!empty($session) AND !empty($id_capaian_vaksin)) {
				if($this->mCapaianVaksin->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mCapaianVaksin->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan <b>'.$data['tanggal_capaian'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
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
			$id_capaian_vaksin 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_capaian_vaksin)) {
				$data = $this->mCapaianVaksin->deleteData();
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
                    'tanggal_capaian'     => $row['A'] ? $row['A'] : '',
                    'regency_id'        => explode(' - ', $row['B'])[0],
                    'id_penyalur'       => explode(' - ', $row['C'])[0],
                    'id_jenis_vaksin'   => explode(' - ', $row['D'])[0],
                    'total_vaksinasi'  	=> $row['E'] ? $row['E'] : '0',
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

        $response =  $this->db->insert_batch('ta_suplai_vaksin', $dataexcel);
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

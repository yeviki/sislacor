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
		$this->breadcrumb->add('Ketersediaan Kamar', '#');
		// $this->session_info['list_id_kat_kamar']   	= $this->mmas->getDataKamar();
		$this->session_info['list_kamar']   	= $this->mRsKamar->getDataKategoriKamar();
		$this->session_info['list_id_rs']   	= $this->mmas->getDataMasterHospital();
		$this->session_info['page_name'] = "Ketersedaan Kamar";

    	$this->template->build('rs_kamar/vlist', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data    = array();
			$rsud 	 = array();
			$kamar   = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
				$param = $this->input->post('param',TRUE);
				$dataList  = $this->mRsKamar->get_datatables($param);
				$dataKamar = $this->mRsKamar->getDataKategoriKamar();
				$no = $this->input->post('start');
				foreach ($dataList as $key => $k) {
					$no++;
					$row = array();
					$row[] = $no;
					$row[] = tgl_indo($k['tanggal']);
					$row[] = $k['shortname'];
					foreach ($dataKamar as $room => $dk) {
						$total = $this->mRsKamar->getTotalKamar($k['tanggal'], $k['id_rs'], $dk['id_kat_kamar']);
						$row[] = !empty($total['total_kamar']) ? $total['total_kamar'] : 0;
					}
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($k['id_rs']).'" data-date="'.$k['tanggal'].'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$k['id_rs'].'" data-date="'.$k['tanggal'].'" title="Delete"><i class="fa fa-times"></i> </button>';
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
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses simpan data gagal, data pada tanggal tersebut sudah ada...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data stok kamar pada tanggal <b>'.$data['tanggal'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses input data gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
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
				$data = $this->mRsKamar->getDataDetail($this->encryption->decrypt($id_rs), $tanggal);
				// print_r($data);
				// die; 	
				$data_show = array('id_rs' => $this->encryption->decrypt($id_rs), 'tanggal' => date('d/m/Y', strtotime($tanggal)));
				$row = array(); $no=1;
				foreach ($data as $key => $val) {
					$row['no'] 				= $no;
					$row['nm_kamar']		= $val['nm_kamar'];
					$row['total_kamar']		= $val['total_kamar'];
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
				if($this->mRsKamar->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mRsKamar->updateData();
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan berhasil diperbaharui...', 'csrfHash' => $csrfHash);
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
			$rs_id 			= $this->input->post('rsId', TRUE);
			if(!empty($session) AND !empty($rs_id)) {
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
		// die;
		
		$dataexcel = array();
		$kategori = $this->mRsKamar->getDataKategoriKamar();
		if(count($sheetdata) > 0) {
			for ($i=4; $i <= count($sheetdata) ; $i++) { 
				$j = 'C';
				foreach ($kategori as $key => $k) {
					$dataexcel[] = array(
						'tanggal'  			=> $sheetdata[$i]['A'] ? $sheetdata[$i]['A'] : '',
						'id_rs'        		=> explode(' - ', $sheetdata[$i]['B'])[0],
						'id_kat_kamar'    	=> $k['id_kat_kamar'],
						'total_kamar' 		=> $sheetdata[$i][$j] ? $sheetdata[$i][$j] : '0',
						'create_by'         => $create_by,
						'create_date'       => $create_date,
						'create_ip'         => $create_ip,
						'mod_by'            => $create_by,
						'mod_date'          => $create_date,
						'mod_ip'            => $create_ip,
					);
					$j++;
				}
			}
		}
        $response =  $this->db->insert_batch('ta_rs_kamar', $dataexcel);
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

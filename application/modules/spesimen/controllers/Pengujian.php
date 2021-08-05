<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of PDP class
 *
 * @author Yogi "solop" Kaputra
 */

class Pengujian extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_sample' => 'msam', 'master/model_master' => 'mmas'));
		$this->load->library( array('cipusher'));
  }

	public function index()
	{
    $this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Daftar Spesimen', '#');

		$this->session_info['page_name'] 			= "Daftar Spesimen";
		$this->session_info['data_province']  = $this->mmas->getDataProvince();
		$this->session_info['data_hospital']  = $this->mmas->getDataMasterHospital();
		$this->session_info['data_labor'] 		= $this->mmas->getDataMasterLaboratorium();
		$this->session_info['data_spesimen'] 	= $this->mmas->getDataMasterSpesimen();
		if($this->app_loader->is_admin() OR $this->app_loader->is_lab())
			$this->template->build('vlist', $this->session_info);
		else
			$this->template->build('vlist_opr', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
				$flag  = escape($this->input->post('flag',TRUE));
				$param = $this->input->post('param',TRUE);
				$data_swab = $this->msam->get_datatables($flag, $param);
				$no = $this->input->post('start');
				foreach ($data_swab as $ds) {
					$no++;
					$row = array();
					$row[] = $no;
					$row[] = rujukan($ds['id_hospital']);
					$row[] = date('d/m/Y', strtotime($ds['tgl_kirim']));
					$row[] = 'Hari ke-'.$ds['hari_ke'];
					$row[] = $ds['kode_swab'];
					$row[] = ($flag == 1) ? spesimen($ds['spesimen']) : $ds['namalkp'].' ('.$ds['umur'].' th)';
					$row[] = ($flag == 1) ? date('d/m/Y', strtotime($ds['tgl_ambil'])) : date('d/m/Y', strtotime($ds['tgl_keluar']));
					$row[] = ($flag == 1) ? $ds['namalkp'].' ('.$ds['umur'].' th)' : (($ds['hasil']=='I') ? '<span class="label label-warning">INCONCLUSIVE</span>' : (($ds['hasil']=='N') ? '<span class="label label-success">NEGATIF</span>' : '<span class="label label-danger">POSITIF</span>'));
					$row[] = '<button class="btn btn-sm btn-orange btnAdd" data-id="'.$this->encryption->encrypt($ds['id_swab']).'" data-token="'.$ds['token'].'" data-flag="'.$flag.'" title="Laporan hasil pemeriksaan"><i class="fa fa-check"></i></button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->msam->count_all($flag),
					"recordsFiltered" => $this->msam->count_filtered($flag, $param),
					"data" => $data,
				);
			}
			//output to json format
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
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
				$data = $this->msam->getDataDetailSpesimen($this->encryption->decrypt($id_swab), $token);
				$row['swabId']			= $id_swab;
				$row['tokenId']			= $token;
				$row['kode_swab']		= !empty($data) ? $data['kode_swab'] : '';
				$row['spesimen']		= !empty($data) ? spesimen($data['spesimen']) : '';
				$row['tgl_ambil'] 	= !empty($data) ? date('d/m/Y', strtotime($data['tgl_ambil'])) : '';
				$row['hari_ke']			= !empty($data) ? 'Hari ke '.$data['hari_ke'].' ('.terbilang($data['hari_ke']).')' : '';
				$row['tgl_kirim']		= !empty($data) ? date('d/m/Y', strtotime($data['tgl_kirim'])) : '';
				$row['hasil_lab'] 	= !empty($data) ? $data['hasil'] : '';
				$row['tgl_keluar'] 	= !empty($data) ? (($data['tgl_keluar'] != '0000-00-00') ? date('d/m/Y', strtotime($data['tgl_keluar'])) : '') : '';
				$row['keterangan'] 	= !empty($data) ? $data['keterangan'] : '';
				$row['status']    	= !empty($data) ? $data['status'] : '';
				$row['namalkp']			= !empty($data) ? $data['namalkp'] : '';
				$row['nik']					= !empty($data) ? $data['nik'] : '';
				$row['umur']				= !empty($data) ? $data['umur'].' (th)' : '';
				$row['gender']			= !empty($data) ? jenis_kelamin($data['gender']) : '';
				$row['province']		= !empty($data) ? provinsi($data['province_id']) : '';
				$row['regency']			= !empty($data) ? regency($data['regency_id']) : '';
				$row['district']		= !empty($data) ? district($data['district_id']) : '';
				$row['village']			= !empty($data) ? village($data['village_id']) : '';
				$row['hospital'] 		= !empty($data) ? hospital($data['id_hospital'], 1) : '';
				$row['labor']				= !empty($data) ? laboratorium($data['id_labor'], 1) : '';
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
			$swabid   = $this->input->post('swabId', TRUE);
			$token    = $this->input->post('tokenId', TRUE);
			if(!empty($session) AND !empty($swabid) AND !empty($token)) {
				if($this->msam->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->msam->updateDataSpesimen();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Hasil lab spesimen pasien <b>'.$data['nama'].'</b> dengan kode spesimen <b>'.$data['kode'].'</b> telah berhasil diupdate', 'csrfHash' => $csrfHash);
						$this->cipusher->send(array('message'=>'swabresult'));
					} else {
						$result = array('status' => 0, 'message' => array('isi'=>'Proses update hasil lab spesimen pasien <b>'.$data['nama'].'</b> dengan kode spesimen <b>'.$data['kode'].'</b> gagal, karena hasil lab sudah diupdate sebelumnya dan hasil lab tidak bisa diupdate dua kali jika sudah dinyatakan Negatif/Positif...'), 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi'=>'Mohon periksa kembali data yang diinputkan...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	//get akumulasi swab
	public function akumulasi()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			if(!empty($session)) {
				$arrData = array();
				$dataAll = $this->msam->getDataAkumulasiSpesimen();
				foreach ($dataAll as $key => $d) {
					$arrData[$d['key_column']] = (int) $d['total'];
				}
				$arrNew = array();
				$dataNew = $this->msam->getDataAkumulasiSpesimen(TRUE);
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

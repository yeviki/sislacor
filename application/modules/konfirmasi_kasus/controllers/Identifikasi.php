<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of PDP class
 *
 * @author Yogi "solop" Kaputra
 */

class Identifikasi extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_pasien' => 'mpas', 'master/model_master' => 'mmas'));
		$this->load->library( array('cipusher'));
  }

	public function index()
	{
    $this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Konfirmasi Kasus', '#');
		$this->breadcrumb->add('Identifikasi', '#');

		$this->session_info['page_name'] 			= "Identifikasi";
		$this->session_info['data_province']  = $this->mmas->getDataProvince();
		$this->session_info['data_study']     = $this->mmas->getDataStudy();
		$this->session_info['data_rsrujukan'] = $this->mmas->getDataMasterRsRujukan();
		$this->session_info['data_fasilitas'] = $this->mmas->getDataMasterFasilitasPemda();
		$this->template->build('form_kasus/vlist', $this->session_info);
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
		    $dataList = $this->mpas->get_datatables($param);
				$no  = $this->input->post('start');
				$ket = '';
				foreach ($dataList as $key => $dl) {
					$no++;
	        $row = array();
					if($dl['tindaklanjut'] == 1)
            $ket = 'Pasien mejalani perawatan di rumah sakit '.$dl['namarsrawat'];
          else if($dl['tindaklanjut'] == 2)
            $ket = ($dl['rsrujukan'] == $this->app_loader->current_hospital()) ? 'Pasien dirujuk dari '.$dl['rsrawatsebelumnya'] : 'Pasien dirujuk ke '.$dl['namarsrawat'];
          else if($dl['tindaklanjut'] == 3)
            $ket = 'Pasien menjalani isolasi diri di rumah';
          else if($dl['tindaklanjut'] == 4)
            $ket = 'Pasien menjalani isolasi diri di '.fasilitas($dl['id_fasilitas']);
	        $row[] = $no;
					$row[] = $dl['norm'];
					$row[] = strtoupper($dl['namalkp']);
					$row[] = $dl['umur'];
					$row[] = jenis_kelamin($dl['gender'], 1);
					$row[] = (($dl['address'] != '') ? strtoupper($dl['address']).' ' : '').village($dl['village_id']).', '.district($dl['district_id']).', '.regency($dl['regency_id']).', '.provinsi($dl['province_id']);
					$row[] = ($dl['pasienstatus']==3) ? '<span class="label label-danger">'.pasien_status($dl['pasienstatus']).'</span>' : '<span class="label label-primary">'.pasien_status($dl['pasienstatus']).'</span>';
					$row[] = $ket;
				 	$row[] = '<a type="button" class="btn btn-sm btn-orange" href="'.site_url('konfirmasi-kasus/identifikasi/review/'.$dl['token']).'" title="Review Data Pasien"><i class="fa fa-pencil"></i> </a>';
	        $data[] = $row;
				}

				$output = array(
	        "draw" => $this->input->post('draw'),
	        "recordsTotal" => $this->mpas->count_all(),
	        "recordsFiltered" => $this->mpas->count_filtered($param),
	        "data" => $data,
	      );
			}
			//output to json format
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
	}

  public function create()
	{
		if($this->input->post('tokenId', TRUE))
			$this->createData();
		else
			$this->createForm();
	}

	private function createForm()
	{
		$this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Konfirmasi Kasus', '#');
    $this->breadcrumb->add('Identifikasi', site_url('konfirmasi-kasus/identifikasi'));
		$this->breadcrumb->add('Baru', '#');

		$this->session_info['page_name']      = "Entri Kasus Baru";
    $this->session_info['data_study']     = $this->mmas->getDataStudy();
    $this->session_info['data_province']  = $this->mmas->getDataProvince();
    $this->session_info['data_gaji']      = array(1=>'TIDAK BERPENGHASILAN', 2=>'<1 Juta', 3=>'1-3 Juta', 4=>'3-5 Juta', 5=>'5-10 Juta', 6=>'>10 Juta');
    $this->session_info['data_fisik']     = array(1=>'Sedenter', 2=>'Latihan fisik < 150 menit/minggu (3 x 50 menit/minggu atau 5 x 30 menit/minggu)', 3=>'Latihan fisik ≥ 150 menit/minggu (3 x 50 menit/minggu atau 5 x 30 menit/minggu)');
    $this->session_info['data_gejala']    = $this->mmas->getDataMasterGejala();
    $this->session_info['data_penyerta']  = $this->mmas->getDataMasterKomorbiditas();
    $this->session_info['data_diagnosis'] = $this->mmas->getDataMasterDiagnosis();
    $this->session_info['data_negara']    = $this->mmas->getDataNegara();
    $this->session_info['data_medis']     = $this->mmas->getDataTenagaMedis();
    $this->session_info['data_rsrujukan'] = $this->mmas->getDataMasterRsRujukan($this->app_loader->current_hospital());
    $this->session_info['data_hospital']  = $this->mmas->getDataMasterHospital();
		$this->session_info['data_fasilitas'] = $this->mmas->getDataMasterFasilitasPemda();
		$this->template->build('form_kasus/vadd', $this->session_info);
	}

	private function createData()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			if(!empty($session)) {
				if($this->mpas->validasiDataValue(1) == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mpas->insertDataPasien();
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Data pasien dengan nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> sudah terdaftar sebelumnya, silahkan periksa kembali. Jika tidak ditemukan silahkan hubungi Dinas Kesehatan Provinsi...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						error_message('success', 'Sukses!', 'Data pasien baru atas nama <b>'.$data['nama'].'</b> /<b>'.$data['nik'].'</b> telah berhasil ditambahkan...');
						$result = array('status' => 1, 'message' => 'Data pasien baru atas nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> telah berhasil ditambahkan...', 'csrfHash' => $csrfHash);
						$this->cipusher->send(array('message'=>'casesuccess'));
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses entri data baru gagal...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function review($id)
	{
		if(!isset($id))
      redirect('konfirmasi-kasus/identifikasi');

		$dataKasus = $this->mpas->getDataDetailPasien($id);
		if(count($dataKasus) <= 0)
			redirect('konfirmasi-kasus/identifikasi');

		$this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Konfirmasi Kasus', '#');
		$this->breadcrumb->add('Identifikasi', site_url('konfirmasi-kasus/identifikasi'));
		$this->breadcrumb->add('Review', '#');

		$this->session_info['page_name'] 			= "Review Data Kasus";
		$this->session_info['data_kasus']     = $dataKasus;
		$this->session_info['data_study']     = $this->mmas->getDataStudy();
    $this->session_info['data_province']  = $this->mmas->getDataProvince();
    $this->session_info['data_gaji']      = array(1=>'TIDAK BERPENGHASILAN', 2=>'<1 Juta', 3=>'1-3 Juta', 4=>'3-5 Juta', 5=>'5-10 Juta', 6=>'>10 Juta');
    $this->session_info['data_fisik']     = array(1=>'Sedenter', 2=>'Latihan fisik < 150 menit/minggu (3 x 50 menit/minggu atau 5 x 30 menit/minggu)', 3=>'Latihan fisik ≥ 150 menit/minggu (3 x 50 menit/minggu atau 5 x 30 menit/minggu)');
    $this->session_info['data_gejala']    = $this->mmas->getDataMasterGejala();
    $this->session_info['data_penyerta']  = $this->mmas->getDataMasterKomorbiditas();
    $this->session_info['data_diagnosis'] = $this->mmas->getDataMasterDiagnosis();
    $this->session_info['data_negara']    = $this->mmas->getDataNegara();
    $this->session_info['data_medis']     = $this->mmas->getDataTenagaMedis();
    $this->session_info['data_rsrujukan'] = $this->mmas->getDataMasterRsRujukan($this->app_loader->current_hospital());
    $this->session_info['data_hospital']  = $this->mmas->getDataMasterHospital();
		$this->session_info['data_fasilitas'] = $this->mmas->getDataMasterFasilitasPemda();
		$this->session_info['data_spesimen']  = $this->mpas->getDataSpesimenPasien($dataKasus['id_pasien']);
		$this->session_info['tindak_lanjut'] 	= ($dataKasus['rsrujukan'] != 0 AND $dataKasus['rsrujukan'] == $this->app_loader->current_hospital()) ? 1 : $dataKasus['tindaklanjut'];
		$row = array();
		$dataGejala = $this->mpas->getDataGejalaPasien($dataKasus['id_pasien']);
		foreach ($dataGejala as $key => $dg) {
			$row[$dg['nama_gejala']] = $dg['value_gejala'];
		}
		$dataPenyerta = $this->mpas->getDataKomorbidPasien($dataKasus['id_pasien']);
		foreach ($dataPenyerta as $key => $dp) {
			$row[$dp['nama_komorbid']] = $dp['value_komorbid'];
		}
		$dataDiagnosis = $this->mpas->getDataDiagnosisPasien($dataKasus['id_pasien']);
		foreach ($dataDiagnosis as $key => $dd) {
			$row[$dd['nama_diagnosis']] = $dd['value_diagnosis'];
		}
		$this->session_info['klinis'] = $row;
		$this->template->build(($dataKasus['rsrujukan'] != 0 AND $dataKasus['rsrujukan'] != $this->app_loader->current_hospital()) ? 'form_kasus/vcheck' : 'form_kasus/vedit', $this->session_info);
	}

	public function update()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$token 		= escape($this->input->post('tokenId'));
			if(!empty($session) AND !empty($token)) {
				if($this->mpas->validasiDataValue(2) == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mpas->updateDataPasien();
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Data pasien dengan nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> sudah terdaftar sebelumnya, silahkan periksa kembali. Jika tidak ditemukan silahkan hubungi Dinas Kesehatan Provinsi...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						error_message('success', 'Sukses!', 'Data pasien atas nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> telah berhasil diperbaharui...');
						$result = array('status' => 1, 'message' => 'Data pasien atas nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> telah berhasil diperbaharui...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function update_status_pasien()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$token 		= escape($this->input->post('tokenpasien'));
			if(!empty($session) AND !empty($token)) {
				if($this->mpas->validasiDataValue(3) == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mpas->updateDataStatusPasien();
					if($data['message'] == 'ERROR') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update status pasien gagal, silahkan ulangi kembali...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						error_message('success', 'Sukses!', 'Status pasien atas nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> telah berhasil diperbaharui...');
						$result = array('status' => 1, 'message' => 'Status pasien atas nama <b>'.$data['nama'].'</b> / <b>'.$data['nik'].'</b> telah berhasil diperbaharui...', 'csrfHash' => $csrfHash);
						$this->cipusher->send(array('message'=>'casesuccess'));
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses update data status pasien gagal...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	//get akumulasi kasus
	public function akumulasi()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			if(!empty($session)) {
				$arrData = array();
				$data = $this->mpas->getDataAkumulasiKasus();
				foreach ($data as $key => $d) {
					$arrData[$d['key_column']] = $d['total'];
				}
				$rwt = !empty($arrData[1]) ? $arrData[1] : 0;
				$rjk = !empty($arrData[2]) ? $arrData[2] : 0;
				$rmh = !empty($arrData[3]) ? $arrData[3] : 0;
				$flt = !empty($arrData[4]) ? $arrData[4] : 0;
				$result = array('message' => array('satu'=>($rwt+$rjk+$rmh+$flt),'dua'=>$rwt,'tiga'=>$rjk,'empat'=>($rmh+$flt)));
			} else {
				$result = array('message' => array('satu'=>0,'dua'=>0,'tiga'=>0,'empat'=>0));
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

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of PDP class
 *
 * @author Yogi "solop" Kaputra
 */

class Kasus extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_kasus' => 'mkas', 'master/model_master' => 'mmas'));
		$this->load->library( array('cipusher'));
  }

	public function index()
	{
    $this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Varifikasi Kasus', '#');
		$this->breadcrumb->add('Data Kasus', '#');

		$this->session_info['page_name'] 	 		= "Data Kasus";
		$this->session_info['data_gejala'] 		= $this->mmas->getDataMasterGejala();
		$this->session_info['data_penyerta']  = $this->mmas->getDataMasterKomorbiditas();
		$this->session_info['data_province']  = $this->mmas->getDataProvince();
		$this->session_info['data_hospital'] 	= $this->mmas->getDataMasterHospital();
		$this->template->build('form_case/vlist', $this->session_info);
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
				$data_case = $this->mkas->get_datatables($flag, $param);
				$no = $this->input->post('start');
				foreach ($data_case as $dc) {
					$no++;
					$row = array();
					$row[] = $no;
					$row[] = regency($dc['regency']);
					$row[] = ($dc['pelapor'] == 0) ? $dc['nama_pewawancara'] : rujukan($dc['pelapor']);
					$row[] = $dc['namalkp'];
					$row[] = $dc['umur'];
					$row[] = jenis_kelamin($dc['gender'], TRUE);
					$row[] = ($dc['jenis_kasus'] == 1) ? '<span class="label label-warning">'.pasien_status($dc['pasienstatus']).' Baru</span>' : (($dc['jenis_kasus'] == 3) ? '<span class="label label-danger">'.pasien_status($dc['pasienstatus']).' Meninggal</span>' : '<span class="label label-success">'.pasien_status($dc['pasienstatus']).' '.(($dc['pasienstatus'] == 3) ? "Sembuh" : "Negatif Covid-19").'</span>');
					if($flag == 1)
						$row[] = date('d/m/Y H:i:s', strtotime($dc['create_date']));
					else
						$row[] = date('d/m/Y H:i:s', strtotime($dc['publish_date'])).(($dc['status'] == 'A') ? '<br/><span class="label label-info">Menunggu Publish</span>' : '');
					$row[] = '<button class="btn btn-sm btn-orange btnView" data-id="'.$this->encryption->encrypt($dc['id_kasus']).'" data-token="'.$dc['token'].'" data-flag="'.$flag.'" title="Review Data Kasus"><i class="fa fa-pencil"></i></button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mkas->count_all($flag),
					"recordsFiltered" => $this->mkas->count_filtered($flag, $param),
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
			$id_case  = escape($this->input->post('case', TRUE));
			$token    = escape($this->input->post('token', TRUE));
			if(!empty($id_case) AND !empty($token) AND !empty($session)) {
				$row = array();
				$data = $this->mkas->getDataDetailPasien($this->encryption->decrypt($id_case), $token);
				if($data['tindaklanjut'] == 1)
					$perawatan = 'Pasien mejalani perawatan di rumah sakit';
				else if($data['tindaklanjut'] == 2)
					$perawatan = 'Pasien dirujuk ke '.rujukan($data['rsrujukan']);
				else if($data['tindaklanjut'] == 3)
					$perawatan = 'Pasien mejalani isolasi diri di rumah';
				else if($data['tindaklanjut'] == 4)
					$perawatan = 'Pasien mejalani isolasi diri di '.fasilitas($data['nm_fasilitas']);
				$row['fasyankes'] = !empty($data) ? ($data['pelapor']==0) ? $data['nama_fasyankes'].' ['.$data['kode_fasyankes'].']' : hospital($data['pelapor'], 1) : '';
				$row['tglinput']	= !empty($data) ? ($data['pelapor']==0) ? date('d/m/Y', strtotime($data['create_date'])) : $data['tgl_wawancara'] : '';
				$row['status']		= !empty($data) ? ($data['jenis_kasus']==1) ? pasien_status($data['pasienstatus'], TRUE).' (Kasus Baru)' : (($data['jenis_kasus']==2) ? (($data['pasienstatus']==3) ? pasien_status($data['pasienstatus'], TRUE).' (Sudah Sembuh)' : pasien_status($data['pasienstatus'], TRUE).' (Negatif Covid-19)') : pasien_status($data['pasienstatus'], TRUE).' (Meninggal Dunia)') : '';
				$row['jnskasus']	= !empty($data) ? $data['jenis_kasus'] : '';
				$row['kondisi'] 	= !empty($data) ? ($data['pasienlastkondisi']==1) ? 'Masih Sakit/Menjalani Perawatan' : (($data['pasienlastkondisi']==2) ? (($data['pasienstatus']==3) ? 'Sudah Sembuh' : 'Negatif Covid-19') : 'Meninggal Dunia') : '';
				$row['rawat']			= !empty($data) ? $perawatan : '';
				$row['norm']			= !empty($data) ? $data['norm'] : '';
				$row['tindakan']	= !empty($data) ? $data['tindaklanjut'] : '';
				$row['tglrawat']	= !empty($data) ? $data['tanggalmasukrawat'] : '';
				$row['rsrawat']		= !empty($data) ? $data['namarsrawat'] : '';
				$row['rsdulu']		= !empty($data) ? $data['rsrawatsebelumnya'] : '';
				$row['namalkp'] 	= !empty($data) ? $data['namalkp'] : '';
				$row['nik']				= !empty($data) ? $data['nik'] : '';
				$row['nokk']			= !empty($data) ? $data['nokk'] : '';
				$row['nohp']			= !empty($data) ? $data['nohp'] : '';
				$row['tmptlhr']		= !empty($data) ? $data['tmptlhr'] : '';
				$row['tgllhr']		= !empty($data) ? $data['tgllhr'] : '';
				$row['umur']			= !empty($data) ? $data['umur'] : '';
				$row['gender']    = !empty($data) ? jenis_kelamin($data['gender']) : '';
				$row['province']  = !empty($data) ? provinsi($data['province']) : '';
				$row['regency']  	= !empty($data) ? regency($data['regency']) : '';
				$row['district']  = !empty($data) ? district($data['district']) : '';
				$row['village']  	= !empty($data) ? village($data['village']) : '';
				$row['address']		= !empty($data) ? $data['address'] : '';
				$row['study']			= !empty($data) ? pendidikan($data['study']) : '';
				$row['job']				= !empty($data) ? $data['job'] : '';
				$row['riwayatperjalanan']		= !empty($data) ? strtoupper(convert_info($data['riwayatperjalanan'])) : '';
				$row['kontaksuspekcovid19']	= !empty($data) ? strtoupper(convert_info($data['kontaksuspekcovid19'])) : '';
				$row['kontakcovid19']				= !empty($data) ? strtoupper(convert_info($data['kontakcovid19'])) : '';
				$row['perjalanan']					= !empty($data) ? (($data['negara'] != '') ? $data['kota'].' ('.$data['negara'].')'.'<br/>'.(($data['traveldate'] != '' AND $data['arrivaldate'] != '') ? $data['traveldate'].' - '.$data['arrivaldate'] : $data['traveldate'].$data['arrivaldate']) : '') : '';
				$row['tgl_pulang']					= !empty($data) ? $data['tgl_pulang'] : '';
				$row['alasan_pulang']				= !empty($data) ? $data['alasan_pulang'] : '';
				$row['tgl_meninggal']				= !empty($data) ? $data['tgl_meninggal'] : '';
				$row['waktu_meninggal']			= !empty($data) ? $data['waktu_meninggal'] : '';
				$klinis = array();
				$dataGejala = $this->mkas->getDataGejalaPasien($data['id_pasien']);
				foreach ($dataGejala as $key => $dg) {
					$klinis[$dg['nama_gejala']] = $dg['value_gejala'];
				}
				$dataPenyerta = $this->mkas->getDataKomorbidPasien($data['id_pasien']);
				foreach ($dataPenyerta as $key => $dp) {
					$klinis[$dp['nama_komorbid']] = $dp['value_komorbid'];
				}
				$spesimen =  array();
				$dataSwab = $this->mkas->getDataSpesimenPasien($data['id_pasien']);
				foreach ($dataSwab as $key => $ds) {
					$spesimen[] = array(
						'kode_swab' 	=> $ds['kode_swab'],
						'tgl_kirim' 	=> date('d/m/Y', strtotime($ds['tgl_kirim'])),
						'hari_ke'			=> 'Hari ke-'.$ds['hari_ke'],
						'spesimen'		=> spesimen($ds['spesimen']),
						'tgl_ambil'		=> date('d/m/Y', strtotime($ds['tgl_ambil'])),
						'status'			=> ($ds['status'] == 1) ? '<span class="label label-warning">DIKIRIM</span>' : '<span class="label label-primary">DITERIMA</span>',
						'hasil'				=> ($ds['hasil']=='') ? '<span class="label label-info">Menunggu</span>' : (($ds['hasil']=='I') ? '<span class="label label-warning">INCONCLUSIVE</span>' : (($ds['hasil']=='N') ? '<span class="label label-success">NEGATIF</span>' : '<span class="label label-danger">POSITIF</span>')),
						'tgl_keluar' 	=> ($ds['tgl_keluar'] != '0000-00-00') ? date('d/m/Y', strtotime($ds['tgl_keluar'])) : '-'
					);
				}
				$result = array('status' => 1, 'message' => array('identitas'=>$row, 'klinis'=>$klinis, 'swab'=>$spesimen), 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => array(), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function approve()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$caseid   = escape($this->input->post('caseId', TRUE));
			$token    = escape($this->input->post('tokenId', TRUE));
			if(!empty($session) AND !empty($caseid) AND !empty($token)) {
				$data = $this->mkas->approveDataKasus();
				if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => 'Proses verifikasi data pasien <b>'.$data['nama'].'</b> berhasil...', 'csrfHash' => $csrfHash);
					$this->cipusher->send(array('message'=>'caseverified'));
				} else {
					$result = array('status' => 0, 'message' => 'Proses verifikasi data pasien <b>'.$data['nama'].'</b> gagal, karena data sudah diverifikasi sebelumnya...', 'csrfHash' => $csrfHash);
				}
			} else {
				$result = array('status' => 0, 'message' => 'Mohon periksa kembali data yang akan diverifikasi...', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function akumulasi()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			if(!empty($session)) {
				$arrNew = array();
				$dataNew = $this->mkas->getDataAkumulasiKasus(TRUE);
				foreach ($dataNew as $key => $dn) {
					$arrNew[$dn['pasienstatus']][$dn['jenis_kasus']] = $dn['jumlah'];
				}
				$pdp = !empty($arrNew[2][1]) ? $arrNew[2][1] : 0;
				$pos = !empty($arrNew[3][1]) ? $arrNew[3][1] : 0;
				$arrLast = array();
				$dataLast = $this->mkas->getDataAkumulasiKasus();
				foreach ($dataLast as $key => $dl) {
					$arrLast[$dl['pasienstatus']][$dl['jenis_kasus']] = $dl['jumlah'];
				}
				$pdp_a = !empty($arrLast[2][1]) ? $arrLast[2][1] : 0;
				$pdp_b = !empty($arrLast[2][2]) ? $arrLast[2][2] : 0;
				$pdp_c = !empty($arrLast[2][3]) ? $arrLast[2][3] : 0;
				$pos_a = !empty($arrLast[3][1]) ? $arrLast[3][1] : 0;
				$pos_b = !empty($arrLast[3][2]) ? $arrLast[3][2] : 0;
				$pos_c = !empty($arrLast[3][3]) ? $arrLast[3][3] : 0;
				$result = array('pdp_a'=>$pdp_a, 'pdp_b'=>($pdp_b+$pdp_c+$pos_a), 'pos_a'=>$pos_a, 'pos_b'=>($pos_b+$pos_c), 'new'=>array('pdp'=>$pdp, 'positif'=>$pos));
			} else {
				$result = array('pdp_a'=>0, 'pdp_b'=>0, 'pos_a'=>0, 'pos_b'=>0, 'new'=>array());
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

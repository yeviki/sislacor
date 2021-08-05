<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ODP class
 *
 * @author Yogi "solop" Kaputra
 */

class Odp extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_odp' => 'modp', 'master/model_master' => 'mmas'));
		$this->load->library( array('cipusher'));
  }

	public function index()
	{
		if(date('H:i:s') > waktu_publish()) {
			$jadwal = tgl_indo(date('Y-m-d')).' '.date('H:i:s', strtotime('+1 minutes', strtotime(waktu_publish()))).' WIB'.' s/d '.tgl_indo(date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))))).' '.waktu_publish().' WIB';
			$waktu  = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))).' '.waktu_input();
		} else {
			$jadwal = tgl_indo(date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))).' '.date('H:i:s', strtotime('+1 minutes', strtotime(waktu_publish()))).' WIB'.' s/d '.tgl_indo(date('Y-m-d')).' '.waktu_publish().' WIB';
			$waktu  = date('Y-m-d').' '.waktu_input();
		}

    $this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Rekap ODP', '#');

		$this->session_info['page_name'] = "Rekap ODP";
		$this->session_info['jadwal'] 	 = $jadwal;
		$this->session_info['deadline']  = $waktu;
		$this->session_info['data_odp']  = $this->modp->cekDataOdpPublish();
		$this->session_info['regency']   = $this->mmas->getDataRegency();
		$this->session_info['kategori']  = $this->modp->getDataKategoriOdp();
		if($this->app_loader->is_kesreg())
    	$this->template->build('vlist_opr', $this->session_info);
		else {
			$this->session_info['tot_odp'] = $this->modp->cekDataOdpRegencyPerDay();
			$this->template->build('vlist', $this->session_info);
		}
	}

	public function create()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$param    = $this->input->post('param', TRUE);
			if(!empty($session) AND !empty($param)) {
				$data = $this->modp->insertDataOdp();
				if($data['message'] == 'ERROR') {
					$result = array('status' => 0, 'message' => array('reg' => '<b>'.$data['note'].'</b>', 'isi' => ' gagal karena data sudah diinputkan, data baru bisa diinputkan lagi jika data yang sebelumnya sudah dipublish...'), 'csrfHash' => $csrfHash);
				} else if($data['message'] == 'NOTIME') {
					$result = array('status' => 0, 'message' => array('reg' => '<b>'.$data['note'].'</b>', 'isi' => ' gagal. Karena batas waktu anda untuk menginputkan data sudah habis, silahkan laporkan ke dinas kesehatan provinsi...'), 'csrfHash' => $csrfHash);
				} else if($data['message'] == 'SUCCESS') {
					$tot_odp = $this->modp->cekDataOdpRegencyPerDay();
					$result = array('status' => 1, 'message' => array('reg' => '<b>'.$data['note'].'</b>', 'isi' => ' berhasil disimpan...'), 'csrfHash' => $csrfHash);
					$this->cipusher->send(array('message'=>'odpsuccess', 'status'=>'new', 'tot_odp'=>$tot_odp));
				}
			} else {
				$result = array('status' => 0, 'message' => array('reg' => '', 'isi' => ' gagal. Mohon periksa kembali data yang diinputkan...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			if(!$this->app_loader->is_kesreg())
			 	$this->listViewAll();
			else {
				$flag = escape($this->input->post('flag', TRUE));
				if($flag == 2)
					$this->listViewAll();
				else {
					$data = $this->modp->getDataDetailOdpNew();
					if(count($data) > 0) {
						$row = array(); $no=1;
						foreach ($data as $key => $val) {
							$jadwal = tgl_indo(date('Y-m-d', strtotime('-1 days', strtotime($val['publish_date'])))).' '.date('H:i:s', strtotime('+1 minutes', strtotime(waktu_publish()))).' WIB'.' s/d '.tgl_indo(date('Y-m-d', strtotime($val['publish_date']))).' '.waktu_publish().' WIB';
							$row['no'] 				= $no;
							$row['kategori']	= $val['desc'].(($val['id_kategori'] > 4) ? ' sampai pada saat ini' : ' per tanggal '.$jadwal);
							$row['tanggal']		= date('d/m/Y H:i:s', strtotime($val['create_date']));
							$row['jumlah']		= $val['jumlah'];
							$row['status']		= ($val['status'] == 'S') ? '<span class="label label-warning">Menunggu Verifikasi</span>' : (($val['status'] == 'A') ? '<span class="label label-primary">Menunggu Publish</span>' : '<span class="label label-success">Data Publish</span>');
							$row['action']    = ($val['status'] == 'S') ? '<button class="btn btn-xs btn-orange btnUpdate" data-id="'.$this->encryption->encrypt($val['id']).'" title="Edit Data ODP"><i class="fa fa-pencil"></i></button>' : '<button class="btn btn-xs btn-orange disabled" title="Edit Data ODP"><i class="fa fa-pencil"></i></button>';
							$hasil[] = $row;
							$no++;
						}
						$result = array('status' => 1, 'message' => $hasil);
					} else
						$result = array('status' => 0, 'message' => '');
					$this->output->set_content_type('application/json')->set_output(json_encode($result));
				}
			}
		}
	}

	private function listViewAll()
	{
		$data = array();
		$session = $this->app_loader->current_account();
		if(isset($session)){
			$flag  = escape($this->input->post('flag',TRUE));
			$data_odp = $this->modp->get_datatables($flag);
			$no = $this->input->post('start');
			foreach ($data_odp as $do) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $do['name'];
				$row[] = $do['rekap'].' '.(($flag == 1 AND $this->app_loader->is_admin()) ? '<button class="btn btn-link btn-xs btnUpdate" data-id="'.$this->encryption->encrypt($do['id_regency']).'" data-date="'.$do['publish_date'].'" data-reg="'.$do['id_regency'].'"><i class="fa fa-pencil"></i></button>' : '');
				$row[] = date('d/m/Y H:i:s', strtotime($do['create_date']));
				if($flag == 1)
					$row[] = ($do['status'] == 'S') ? '<span class="label label-warning">Menunggu Verifikasi</span>' : '<span class="label label-primary">Menunggu Publish</span>';
				else
					$row[] = date('d/m/Y H:i:s', strtotime($do['publish_date']));
				$row[] = '<button class="btn btn-sm btn-green btnDetail" data-id="'.$this->encryption->encrypt($do['id_regency']).'" data-date="'.$do['publish_date'].'" data-flag="'.$flag.'" data-reg="'.$do['name'].'" title="Rincian Rekapitulasi Data ODP"><i class="fa fa-eye"></i></button>';
				$data[] = $row;
			}

			$output = array(
				"draw" => $this->input->post('draw'),
				"recordsTotal" => $this->modp->count_all($flag),
				"recordsFiltered" => $this->modp->count_filtered($flag),
				"data" => $data,
			);
		}
		//output to json format
		echo json_encode($output);
	}

	public function details()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$id_regency   = $this->input->post('regencyId', TRUE);
			$publish_date = $this->input->post('publishDate', TRUE);
			$flag = $this->input->post('flag', TRUE);
			if(!empty($id_regency) AND !empty($publish_date) AND !empty($session)) {
				$jadwal = tgl_indo(date('Y-m-d', strtotime('-1 days', strtotime($publish_date)))).' '.date('H:i:s', strtotime('+1 minutes', strtotime(waktu_publish()))).' WIB'.' s/d '.tgl_indo(date('Y-m-d', strtotime($publish_date))).' '.waktu_publish().' WIB';
				$data = $this->modp->getDataDetailOdp($this->encryption->decrypt($id_regency), $publish_date, $flag);
				if(count($data) > 0) {
					$row = array(); $no=1;
					foreach ($data as $key => $val) {
						$row['no'] 				= $no;
						$row['kategori']	= $val['desc'].(($val['id_kategori'] > 4) ? ' sampai pada saat ini' : ' per tanggal '.$jadwal);
						$row['jumlah']		= $val['jumlah'];
						$row['status']		= ($val['status'] == 'S') ? '<span class="label label-warning">Menunggu Verifikasi</span>' : (($val['status'] == 'A') ? '<span class="label label-primary">Menunggu Publish</span>' : '<span class="label label-success">Data Publish</span>');
						$hasil[] = $row;
						$no++;
					}
					$result = array('status' => 1, 'message' => $hasil, 'jadwal' => $jadwal, 'csrfHash' => $csrfHash);
				} else
					$result = array('status' => 0, 'message' => '', 'jadwal' => '', 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => '', 'jadwal' => '', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function update()
	{
		if($this->app_loader->is_kesreg()) {
			$this->updateKesreg();
		} else {
			$this->updateAdmin();
		}
	}

	private function updateKesreg()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$odpId    = escape($this->input->post('odpId', TRUE));
			if(!empty($odpId) AND !empty($session)) {
				$data = $this->modp->updateDataOdp();
				if($data['message'] == 'ERROR') {
					$result = array('status' => 0, 'message' => ' tidak bisa diperbaharui lagi karena sudah diverifikasi oleh Dinkes Provinsi...', 'csrfHash' => $csrfHash);
				} else if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => ' telah berhasil diperbaharui...', 'csrfHash' => $csrfHash);
					$this->cipusher->send(array('message'=>'odpsuccess', 'status'=>'update'));
				}
			} else {
				$result = array('status' => 0, 'message' => ' gagal. Mohon periksa kembali data yang diinputkan...', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	private function updateAdmin()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$param    = $this->input->post('param', TRUE);
			$regency  = escape($this->input->post('regencyId', TRUE));
			if(!empty($session) AND !empty($regency) AND !empty($param)) {
				$data = $this->modp->updateDataOdpAdmin();
				if($data['message'] == 'ERROR') {
					$result = array('status' => 0, 'message' => array('reg' => '<b>'.$data['note'].'</b>', 'isi' => ' gagal karena data sudah dipublish...'), 'csrfHash' => $csrfHash);
				} else if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => array('reg' => '<b>'.$data['note'].'</b>', 'isi' => ' berhasil diperbaharui...'), 'csrfHash' => $csrfHash);
					$this->cipusher->send(array('message'=>'odpsuccess', 'status'=>'update'));
				}
			} else {
				$result = array('status' => 0, 'message' => array('reg' => '', 'isi' => ' gagal. Mohon periksa kembali data yang diinputkan...'), 'csrfHash' => $csrfHash);
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
				$dataNew = $this->modp->getDataAkumulasiOdp(TRUE);
				foreach ($dataNew as $key => $dn) {
					$arrNew[$dn['key_column']] = $dn['jumlah'];
				}
				$new = !empty($arrNew[1]) ? $arrNew[1] : 0;
				$end = !empty($arrNew[2]) ? $arrNew[2] : 0;
				$sts = (!empty($arrNew[3]) ? $arrNew[3] : 0) + (!empty($arrNew[4]) ? $arrNew[4] : 0);
				$arrLast = array();
				$dataLast = $this->modp->getDataAkumulasiOdp();
				foreach ($dataLast as $key => $dl) {
					$arrLast[$dl['key_column']] = $dl['jumlah'];
				}
				$totNew = !empty($arrLast[1]) ? $arrLast[1] : 0;
				$totEnd = !empty($arrLast[2]) ? $arrLast[2] : 0;
				$totSts = (!empty($arrLast[3]) ? $arrLast[3] : 0) + (!empty($arrLast[4]) ? $arrLast[4] : 0);
				$result = array('message' => array('satu'=>($totNew-$totEnd-$totSts),'dua'=>$new,'tiga'=>$sts,'empat'=>$end));
			} else {
				$result = array('message' => array('satu'=>0,'dua'=>0,'tiga'=>0,'empat'=>0));
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}
}

// This is the end of home clas

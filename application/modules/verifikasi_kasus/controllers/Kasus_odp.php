<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ODP class
 *
 * @author Yogi "solop" Kaputra
 */

class Kasus_odp extends SLP_Controller {

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
		} else {
			$jadwal = tgl_indo(date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))).' '.date('H:i:s', strtotime('+1 minutes', strtotime(waktu_publish()))).' WIB'.' s/d '.tgl_indo(date('Y-m-d')).' '.waktu_publish().' WIB';
		}

    $this->breadcrumb->add('Dashboard', site_url('home'));
		$this->breadcrumb->add('Varifikasi Kasus', '#');
		$this->breadcrumb->add('Kasus ODP', '#');

		$this->session_info['page_name'] = "Kasus ODP";
		$this->session_info['jadwal'] 	 = $jadwal;
		$this->session_info['regency']   = $this->mmas->getDataRegency();
		$this->session_info['tot_odp'] 	 = $this->modp->cekDataOdpRegencyPerDay();
		$this->template->build('form_odp/vlist', $this->session_info);
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
				$data_odp = $this->modp->get_datatables($flag);
				$no = $this->input->post('start');
				foreach ($data_odp as $do) {
					$no++;
					$row = array();
					$row[] = $no;
					$row[] = regency($do['id_regency']);
					$row[] = $do['rekap'];
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
	}

	public function details()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$id_regency   = escape($this->input->post('regencyId', TRUE));
			$publish_date = escape($this->input->post('publishDate', TRUE));
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
					$result = array('status' => 1, 'message' => $hasil, 'jadwal' => $jadwal, 'validasi' => $data[0]['status'], 'csrfHash' => $csrfHash);
				} else
					$result = array('status' => 0, 'message' => '', 'jadwal' => '', 'validasi' => '', 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => '', 'jadwal' => '', 'validasi' => '', 'csrfHash' => $csrfHash);
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
			$regency  = escape($this->input->post('regencyId', TRUE));
			if(!empty($session) AND !empty($regency)) {
				$data = $this->modp->approveDataOdp();
				if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => 'Proses verifikasi data rekapitulasi ODP <b>'.$data['note'].'</b> berhasil...', 'csrfHash' => $csrfHash);
					$this->cipusher->send(array('message'=>'odpverified'));
				} else {
					$result = array('status' => 0, 'message' => 'Proses verifikasi data rekapitulasi ODP <b>'.regency($this->encryption->decrypt($regency)).'</b> gagal, karena data sudah diverifikasi sebelumnya...', 'csrfHash' => $csrfHash);
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

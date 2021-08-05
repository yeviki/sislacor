<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of PDP class
 *
 * @author Yogi "solop" Kaputra
 */

class Paparan_kontak extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_kontak' => 'mkon'));
  }

  public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
      $data = array();
  		$session = $this->app_loader->current_account();
  		if(isset($session)){
				$token = escape($this->input->post('tokenId', TRUE));
				$flag  = escape($this->input->post('flag', TRUE));
  			$dataKontak = $this->mkon->get_datatables($token);
  			$no = $this->input->post('start');
  			foreach ($dataKontak as $dk) {
  				$no++;
  				$row = array();
  				$row[] = $no;
          $row[] = $dk['namalkp'].'<br/>'.$dk['nik'];
					$row[] = $dk['umur'];
  				$row[] = jenis_kelamin($dk['gender'], 1);
					$row[] = $dk['address'];
					$row[] = $dk['nohp'];
					$row[] = $dk['hubdgnkasus'];
					$row[] = $dk['aktivitaskontak'];
					if($flag==1) {
						$row[] = '<button type="button" class="btn btn-sm btn-danger btnDelete disabled" data-id="'.$this->encryption->encrypt($dk['id_kontak']).'" title="Hapus Data Kontak Erat Kasus"><i class="fa fa-times"></i></button>';
					}
  				$data[] = $row;
  			}

  			$output = array(
  				"draw" => $this->input->post('draw'),
  				"recordsTotal" => $this->mkon->count_all($token),
  				"recordsFiltered" => $this->mkon->count_filtered($token),
  				"data" => $data,
  			);
  		}
  		//output to json format
  		$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
	}

  public function delete()
  {
    if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
      $session   = $this->app_loader->current_account();
			$csrfHash  = $this->security->get_csrf_hash();
      $token     = escape($this->input->post('tokenId', TRUE));
      $kontakId  = escape($this->input->post('kontakId', TRUE));
      if(!empty($session) AND !empty($token) AND !empty($kontakId)) {
        $data = $this->mkon->deleteDataPaparanKontak($token, $this->encryption->decrypt($kontakId));
        if($data['message'] == 'SUCCESS') {
          $result = array('status' => 1, 'message' => 'Data kontak erat kasus pasien <b>'.$data['nama'].'</b> berhasil dihapus...', 'csrfHash' => $csrfHash);
				} else {
          $result = array('status' => 0, 'message' => 'Proses hapus data kontak erat kasus pasien gagal, harap periksa kembali data yang akan dihapus...', 'csrfHash' => $csrfHash);
        }
      } else {
        $result = array('status' => 0, 'message' => 'Proses hapus data gagal...', 'csrfHash' => $csrfHash);
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
  }

}

// This is the end of home clas

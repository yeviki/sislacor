<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_swab extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

  public function validasiDataValue($flag=0)
	{
		if($flag==1){
			$this->form_validation->set_rules('hospital', 'Rumah Sakit', 'required|trim');
			$this->form_validation->set_rules('pasien', 'Nama Pasien', 'required|trim');
		}
		$this->form_validation->set_rules('laboratorium', 'Laboratorium ', 'required|trim');
		$this->form_validation->set_rules('hari_ke', 'Hari Ke', 'required|trim');
		$this->form_validation->set_rules('kode_swab', 'Kode Spesimen', 'required|trim');
		$this->form_validation->set_rules('spesimen', 'Jenis Spesimen', 'required|trim');
		$this->form_validation->set_rules('tgl_ambil', 'Tanggal Pengambilan ', 'required|trim');
		$this->form_validation->set_rules('tgl_kirim', 'Tanggal Pengiriman ', 'required|trim');
  	validation_message_setting();
    if ($this->form_validation->run() == FALSE)
      return false;
    else
      return true;
	}

  private function getDataPasienByToken($token)
	{
		$this->db->where('token', $token);
		$query = $this->db->get('ta_pasien');
		return $query->row_array();
	}

	public function getDataListPasien($keyword, $hospital)
	{
		$this->db->select('a.id_pasien,
											 a.token,
											 a.namalkp,
											 a.nik,
											 a.umur,
											 a.gender');
		$this->db->from('ta_pasien a');
		$this->db->join('ta_pasien_tindakan b', 'a.id_pasien = b.id_pasien', 'inner');
		$this->db->where('a.pasienlastkondisi', 1);
		$this->db->where('CASE
		 										WHEN b.rsrujukan = 0 THEN b.id_hospital = "'.$hospital.'"
												ELSE b.rsrujukan = "'.$hospital.'"
											END', NULL, FALSE);
		$this->db->group_start();
			$this->db->like('a.namalkp', escape($keyword), 'after');
			$this->db->or_like('a.nik', escape($keyword), 'after');
		$this->db->group_end();
		$this->db->order_by('a.id_pasien ASC');
		$query = $this->db->get();
    return $query->result_array();
	}

	var $search = array('a.kode_swab', 'b.namalkp');
	public function get_datatables($param)
  {
    $this->_get_datatables_query($param);
    if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

	public function count_filtered($param)
  {
    $this->_get_datatables_query($param);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
		if (!$this->app_loader->is_admin()) {
			$this->db->where('id_hospital', $this->app_loader->current_hospital());
		}
    return $this->db->count_all_results('ta_pasien_swab');
  }

  private function _get_datatables_query($param)
  {
		$post = array();
		if (is_array($param)) {
      foreach ($param as $v) {
        $post[$v['name']] = $v['value'];
      }
    }
		$this->db->select('a.id_swab,
											 a.id_pasien,
											 a.kode_swab,
											 a.id_hospital,
											 a.id_labor,
											 a.spesimen,
											 a.hari_ke,
											 a.tgl_ambil,
											 a.tgl_kirim,
											 a.hasil,
											 a.tgl_keluar,
											 a.keterangan,
											 a.status,
											 b.token,
											 b.namalkp,
											 b.nik,
											 b.umur');
		$this->db->from('ta_pasien_swab a');
		$this->db->join('ta_pasien b', 'a.id_pasien = b.id_pasien', 'left');
		if ($this->app_loader->is_hospital()) {
			$this->db->where('a.id_hospital', $this->app_loader->current_hospital());
		}
		//hospital
		if(isset($post['hospital']) AND $post['hospital'] != '')
			$this->db->where('a.id_hospital', $post['hospital']);
		//laboratorium
		if(isset($post['laboratorium']) AND $post['laboratorium'] != '')
			$this->db->where('a.id_labor', $post['laboratorium']);
		//hari ke
		if(isset($post['hari_ke']) AND $post['hari_ke'] != '')
			$this->db->where('a.hari_ke', $post['hari_ke']);
		//kode spesimen
		if(isset($post['kode_swab']) AND $post['kode_swab'] != '')
			$this->db->where('a.kode_swab', $post['kode_swab']);
		//spesimen
		if(isset($post['spesimen']) AND $post['spesimen'] != '')
			$this->db->where('a.spesimen', $post['spesimen']);
		//tgl ambil
		if(isset($post['tgl_ambil']) AND $post['tgl_ambil'] != '')
			$this->db->where('a.tgl_ambil', date_convert($post['tgl_ambil']));
		//tgl kirim
		if(isset($post['tgl_kirim']) AND $post['tgl_kirim'] != '')
			$this->db->where('a.tgl_kirim', date_convert($post['tgl_kirim']));
		//status
		if(isset($post['status']) AND $post['status'] != '')
			$this->db->where('a.status', $post['status']);
		//hasil lab
		if(isset($post['hasil_lab']) AND $post['hasil_lab'] != '')
			$this->db->where('a.hasil', $post['hasil_lab']);
		//tgl Keluar
		if(isset($post['tgl_keluar']) AND $post['tgl_keluar'] != '')
			$this->db->where('a.tgl_keluar', date_convert($post['tgl_keluar']));
		//provinsi
		if(isset($post['province']) AND $post['province'] != '')
			$this->db->where('b.province_id', $post['province']);
		//regency
		if(isset($post['regency']) AND $post['regency'] != '')
			$this->db->where('b.regency_id', $post['regency']);
		//district
		if(isset($post['district']) AND $post['district'] != '')
			$this->db->where('b.district_id', $post['district']);
		//village
		if(isset($post['village']) AND $post['village'] != '')
			$this->db->where('b.village_id', $post['village']);
		//umur
		if(isset($post['umur']) AND $post['umur'] != '')
			$this->db->where('b.umur', $post['umur']);
		//jenis kelamin
		if(isset($post['gender']) AND $post['gender'] != '')
			$this->db->where('b.gender', $post['gender']);
		//nama lengkap
		if(isset($post['namalkp']) AND $post['namalkp'] != '')
			$this->db->like('b.namalkp', $post['namalkp'], 'after');
		//nik
		if(isset($post['nik']) AND $post['nik'] != '')
			$this->db->like('b.nik', $post['nik'], 'after');
    $i = 0;
    foreach ($this->search as $item) { // loop column
      if($_POST['search']['value']) { // if datatable send POST for search
        if($i===0) { // first loop
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if(count($this->search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
		$this->db->order_by('a.id_swab DESC');
  }

	public function getDataDetailSpesimen($id_swab, $token)
	{
		$this->db->select('a.id_swab,
											 a.id_pasien,
											 a.kode_swab,
											 a.id_hospital,
											 a.id_labor,
											 a.spesimen,
											 a.hari_ke,
											 a.tgl_ambil,
											 a.tgl_kirim,
											 a.hasil,
											 a.tgl_keluar,
											 a.keterangan,
											 a.status,
											 b.token,
											 b.namalkp,
											 b.nik');
		$this->db->from('ta_pasien_swab a');
		$this->db->join('ta_pasien b', 'a.id_pasien = b.id_pasien', 'inner');
		$this->db->where('a.id_swab', $id_swab);
		$this->db->where('b.token', $token);
		if ($this->app_loader->is_hospital()) {
			$this->db->where('a.id_hospital', $this->app_loader->current_hospital());
		}
		$this->db->order_by('a.id_swab ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row_array();
	}

  public function insertDataSpesimen()
  {
    $create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$token        = escape($this->input->post('pasien', TRUE));
		$kode_swab    = escape($this->input->post('kode_swab', TRUE));
		//load class notification
		$notif = new notification_manager();
    //get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
    $id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		$namalkp    = !empty($dataPasien) ? $dataPasien['namalkp'] : '';
		$nik    		= !empty($dataPasien) ? $dataPasien['nik'] : '';
    if(count($dataPasien) <= 0) {
      return array('message'=>'ERROR', 'nama'=>'');
    } else {
			//cek data kode spesimen
			$this->db->where('kode_swab', $kode_swab);
			$qTot = $this->db->count_all_results('ta_pasien_swab');
			if($qTot > 0)
				return array('message'=>'HAVECODE', 'nama'=>$kode_swab);
			else {
				$data = array(
	        'id_pasien'  	=> $id_pasien,
	        'kode_swab'   => $kode_swab,
	        'id_hospital' => escape($this->input->post('hospital', TRUE)),
	        'id_labor'  	=> escape($this->input->post('laboratorium', TRUE)),
					'spesimen' 		=> escape($this->input->post('spesimen', TRUE)),
					'hari_ke' 		=> escape($this->input->post('hari_ke', TRUE)),
					'tgl_ambil' 	=> date_convert(escape($this->input->post('tgl_ambil', TRUE))),
					'tgl_kirim'	 	=> date_convert(escape($this->input->post('tgl_kirim', TRUE))),
					'hasil' 			=> '',
					'tgl_keluar' 	=> '0000-00-00',
					'keterangan' 	=> '',
	        'status' 			=> 1,
	        'create_by'		=> $create_by,
	        'create_date'	=> $create_date,
	        'create_ip'		=> $create_ip,
	        'mod_by'			=> $create_by,
	        'mod_date'		=> $create_date,
	        'mod_ip'			=> $create_ip
	      );
	      $this->db->insert('ta_pasien_swab', $data);
				//kirim notifikasi
				$params['sender_id'] 		= $create_by;
				$params['labor']				= escape($this->input->post('laboratorium', TRUE));
				$params['type']					= 'swab.new';
				$params['parameters']		= $kode_swab;
				$params['reference']		=	'Mengirim spesimen '.spesimen(escape($this->input->post('spesimen', TRUE))).' dengan kode '.$kode_swab.' atas nama '.$namalkp.' yang diambil pada hari '.(($this->input->post('hari_ke', TRUE) == 1) ? 'pertama' : 'ke '.strtolower(terbilang($this->input->post('hari_ke', TRUE)))).' tanggal '.tgl_indo(date_convert(escape($this->input->post('tgl_ambil', TRUE))));
				$params['create_by']		= $create_by;
				$params['create_date']	= $create_date;
				$params['create_ip']		= $create_ip;
				$notif->add($params);
	      return array('message'=>'SUCCESS', 'nama'=>$namalkp, 'nik'=>$nik);
			}
    }
  }

	public function updateDataSpesimen()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$id_swab			= $this->encryption->decrypt(escape($this->input->post('swabId', TRUE)));
		$token        = escape($this->input->post('pasien', TRUE));
		$kode_swab    = escape($this->input->post('kode_swab', TRUE));
    //get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
    $id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		$namalkp    = !empty($dataPasien) ? $dataPasien['namalkp'] : '';
		$nik    		= !empty($dataPasien) ? $dataPasien['nik'] : '';
		//get data spesimen
		$dataSwab = $this->getDataDetailSpesimen($id_swab, $token);
		$status = !empty($dataSwab) ? $dataSwab['status'] : 0;
    if(count($dataPasien) <= 0) {
      return array('message'=>'ERROR', 'nama'=>'');
    } else {
			//cek data kode spesimen
			$this->db->where('id_swab !=', $id_swab);
			$this->db->where('kode_swab', $kode_swab);
			$qTot = $this->db->count_all_results('ta_pasien_swab');
			if($qTot > 0)
				return array('message'=>'HAVECODE', 'nama'=>$kode_swab);
			else {
				//cek status apakah masih dalam proses atau tidak
				if($status != 1)
					return array('message'=>'FAILED', 'nama'=>$namalkp, 'nik'=>$nik);
				else {
					$data = array(
		        'kode_swab'   => $kode_swab,
		        'id_labor'  	=> escape($this->input->post('laboratorium', TRUE)),
						'spesimen' 		=> escape($this->input->post('spesimen', TRUE)),
						'hari_ke' 		=> escape($this->input->post('hari_ke', TRUE)),
						'tgl_ambil' 	=> date_convert(escape($this->input->post('tgl_ambil', TRUE))),
						'tgl_kirim'	 	=> date_convert(escape($this->input->post('tgl_kirim', TRUE))),
		        'mod_by'			=> $create_by,
		        'mod_date'		=> $create_date,
		        'mod_ip'			=> $create_ip
		      );
					$this->db->where('id_swab', $id_swab);
					$this->db->where('id_pasien', $id_pasien);
					$this->db->where('status', 1);
		      $this->db->update('ta_pasien_swab', $data);
		      return array('message'=>'SUCCESS', 'nama'=>$namalkp, 'nik'=>$nik);
				}
			}
    }
	}

	public function getDataAkumulasiSpesimen($today=FALSE)
	{
		$this->db->select('(CASE
		 										WHEN hasil = "" THEN "M"
												ELSE hasil
											 END) AS key_column,
											 COUNT(id_swab) AS total');
		$this->db->from('ta_pasien_swab');
		if ($this->app_loader->is_hospital()) {
			$this->db->where('id_hospital', $this->app_loader->current_hospital());
		}
		if($today==TRUE) {
			$this->db->where('tgl_kirim', date('Y-m-d'));
		}
		$this->db->group_by('key_column');
		$query = $this->db->get();
		return $query->result_array();
	}

}

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_sample extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
	  $this->form_validation->set_rules('hasil_lab', 'Hasil Lab', 'required|trim');
    $this->form_validation->set_rules('tgl_keluar', 'Tgl. Spesimen Keluar', 'required|trim');
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

	//set column search data target
	var $search = array('b.namalkp', 'a.kode_swab');
	public function get_datatables($flag, $param)
  {
    $this->_get_datatables_query($flag, $param);
    if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered($flag, $param)
  {
    $this->_get_datatables_query($flag, $param);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($flag)
  {
		$this->db->where('status', $flag);
		return $this->db->count_all_results('ta_pasien_swab');
  }

  private function _get_datatables_query($flag, $param)
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
											 b.umur');
		$this->db->from('ta_pasien_swab a');
		$this->db->join('ta_pasien b', 'a.id_pasien = b.id_pasien', 'left');
		$this->db->where('a.status', $flag);
		$this->db->where('a.tgl_kirim <=', date('Y-m-d'));
		if($this->app_loader->is_lab()) {
			$this->db->where('a.id_labor', $this->app_loader->current_labor());
		}
		//hospital
		if(isset($post['nm_hospital']) AND $post['nm_hospital'] != '')
			$this->db->where('a.id_hospital', $post['nm_hospital']);
		//laboratorium
		if(isset($post['laboratorium']) AND $post['laboratorium'] != '')
			$this->db->where('a.id_labor', $post['laboratorium']);
		//tgl kirim awal
		if(isset($post['tgl_kirim_awal']) AND $post['tgl_kirim_awal'] != '')
			$this->db->where('a.tgl_kirim >=', date_convert($post['tgl_kirim_awal']));
		//tgl kirim akhir
		if(isset($post['tgl_kirim_akhir']) AND $post['tgl_kirim_akhir'] != '')
			$this->db->where('a.tgl_kirim <=', date_convert($post['tgl_kirim_akhir']));
		//hasil lab
		if(isset($post['hasillab']) AND $post['hasillab'] != '')
			$this->db->where('a.hasil', $post['hasillab']);
		//tgl keluar awal
		if(isset($post['tgl_keluar_awal']) AND $post['tgl_keluar_awal'] != '')
			$this->db->where('a.tgl_keluar >=', date_convert($post['tgl_keluar_awal']));
		//tgl keluar akhir
		if(isset($post['tgl_keluar_akhir']) AND $post['tgl_keluar_akhir'] != '')
			$this->db->where('a.tgl_keluar <=', date_convert($post['tgl_keluar_akhir']));
		//hari ke
		if(isset($post['harike']) AND $post['harike'] != '')
			$this->db->where('a.harike', $post['harike']);
		//kode spesimen
		if(isset($post['kodeswab']) AND $post['kodeswab'] != '')
			$this->db->where('a.kode_swab', $post['kodeswab']);
		//spesimen
		if(isset($post['nm_spesimen']) AND $post['nm_spesimen'] != '')
			$this->db->where('a.spesimen', $post['nm_spesimen']);
		//tgl ambil
		if(isset($post['tglambil']) AND $post['tglambil'] != '')
			$this->db->where('a.tgl_ambil', date_convert($post['tglambil']));
		//umur
		if(isset($post['umur_pasien']) AND $post['umur_pasien'] != '')
			$this->db->where('b.umur', $post['umur_pasien']);
		//jenis kelamin
		if(isset($post['gender']) AND $post['gender'] != '')
			$this->db->where('b.gender', $post['gender']);
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
		//nama lengkap
		if(isset($post['nm_pasien']) AND $post['nm_pasien'] != '')
			$this->db->like('b.namalkp', $post['nm_pasien'], 'after');
		//nik
		if(isset($post['nik_pasien']) AND $post['nik_pasien'] != '')
			$this->db->like('b.nik', $post['nik_pasien'], 'after');
		$i = 0;
    foreach ($this->search as $se) { // loop column
      if($_POST['search']['value']) { // if datatable send POST for search
        if($i===0) { // first loop
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($se, $_POST['search']['value']);
        } else {
          $this->db->or_like($se, $_POST['search']['value']);
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
											 a.create_by,
											 a.create_date,
											 b.token,
											 b.namalkp,
											 b.nik,
											 b.umur,
											 b.gender,
											 b.province_id,
											 b.regency_id,
											 b.district_id,
											 b.village_id');
		$this->db->from('ta_pasien_swab a');
		$this->db->join('ta_pasien b', 'a.id_pasien = b.id_pasien', 'inner');
		$this->db->where('a.id_swab', $id_swab);
		$this->db->where('b.token', $token);
		if($this->app_loader->is_lab()) {
			$this->db->where('a.id_labor', $this->app_loader->current_labor());
		}
		$this->db->order_by('a.id_swab ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function updateDataSpesimen()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$id_swab      = $this->encryption->decrypt(escape($this->input->post('swabId', TRUE)));
		$token				= escape($this->input->post('tokenId', TRUE));
		$hasil				= escape($this->input->post('hasil_lab', TRUE));
		//load class notification
		$notif = new notification_manager();
		//get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
		$id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		$namalkp  	= !empty($dataPasien) ? $dataPasien['namalkp'] : '';
		$nik  			= !empty($dataPasien) ? $dataPasien['nik'] : '';
		//get data spesimen
		$dataSwab = $this->getDataDetailSpesimen($id_swab, $token);
		$kodeswab = !empty($dataSwab) ? $dataSwab['kode_swab'] : '';
		$pengirim = !empty($dataSwab) ? $dataSwab['create_by'] : '';
		$spesimen = !empty($dataSwab) ? $dataSwab['spesimen'] : '';
		$hari_ke  = !empty($dataSwab) ? $dataSwab['hari_ke'] : '';
		//cek data specimen
		$this->db->where('id_swab', $id_swab);
		$this->db->where('id_pasien', $id_pasien);
		$this->db->where('status', 2);
		$qSwab = $this->db->count_all_results('ta_pasien_swab');
		if($qSwab > 0) {
			return array('message'=>'ERROR', 'nama'=>$namalkp.' ['.$nik.']', 'kode'=>$kodeswab);
		} else {
			//update data swab
			$this->db->set('hasil', $hasil);
			$this->db->set('tgl_keluar', date_convert(escape($this->input->post('tgl_keluar', TRUE))));
			$this->db->set('keterangan', escape($this->input->post('keterangan', TRUE)));
			$this->db->set('status', 2);
			$this->db->set('mod_by', $create_by);
			$this->db->set('mod_date', $create_date);
			$this->db->set('mod_ip', $create_ip);
			$this->db->where('id_swab', $id_swab);
			$this->db->where('id_pasien', $id_pasien);
			$this->db->where('status', 1);
			$this->db->update('ta_pasien_swab');
			//kirim notifikasi
			$params['sender_id'] 		= $create_by;
			$params['recipient_id'] = $pengirim;
			$params['type']					= 'swab.result';
			$params['parameters']		= $kodeswab;
			$params['reference']		=	'Hasil pemeriksaan laboratorium spesimen '.spesimen($spesimen).' yang diambil dihari '.(($hari_ke == 1) ? 'pertama' : 'ke '.strtolower(terbilang($hari_ke))).' dari pasien '.$namalkp.' dengan kode spesimen '.$kodeswab.' telah keluar, dan hasil pemeriksaanya dinyatakan '.(($hasil == 'I') ? 'INCONCLUSIVE' : (($hasil == 'N') ? 'NEGATIF' : 'POSITIF'));
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			return array('message'=>'SUCCESS', 'nama'=>$namalkp.' ['.$nik.']', 'kode'=>$kodeswab);
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
		if($today==TRUE) {
			$this->db->where('tgl_kirim', date('Y-m-d'));
		} else {
			$this->db->where('tgl_kirim <=', date('Y-m-d'));
		}
		$this->db->group_by('key_column');
		$query = $this->db->get();
		return $query->result_array();
	}
}

// This is the end of auth signin model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_kasus extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('master/model_master' => 'mmas'));
		$datenow = (date('H:i:s') > waktu_publish()) ? date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))) : date('Y-m-d');
		$this->_publishDate = date('Y-m-d H:i:s', strtotime($datenow.' '.waktu_publish()));
	}

	private function getDataPasienByToken($token)
	{
		$this->db->where('token', $token);
		$query = $this->db->get('ta_pasien');
		return $query->row_array();
	}

	//set column search data target
	var $search = array('b.namalkp', 'c.nama_pewawancara');
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
		$status = ($flag == 2) ? array('P', 'A') : array('S');
		$this->db->where_in('status', $status);
		return $this->db->count_all_results('ta_pasien_status');
  }

  private function _get_datatables_query($flag, $param)
  {
		$post = array();
		if (is_array($param)) {
      foreach ($param as $v) {
        $post[$v['name']] = $v['value'];
      }
    }
		$status = ($flag == 2) ? array('P', 'A') : array('S');
		$this->db->select('a.id_kasus,
											 a.id_pasien,
											 a.pasienstatus,
											 a.jenis_kasus,
											 a.status,
											 a.publish_date,
											 a.create_date,
											 b.token,
											 b.namalkp,
											 b.nik,
											 b.umur,
											 b.gender,
											 b.regency_id AS regency,
											 c.nama_pewawancara,
											 (SELECT id_rs FROM xi_sa_users WHERE username = a.create_by) AS pelapor');
		$this->db->from('ta_pasien_status a');
		$this->db->join('ta_pasien b', 'a.id_pasien = b.id_pasien', 'inner');
		$this->db->join('ta_pewawancara c', 'a.id_pasien = c.id_pasien', 'inner');
		$this->db->where_in('a.status', $status);
		//hospital
		if(isset($post['hospital']) AND $post['hospital'] != '') {
			$this->db->group_start();
				$this->db->where('c.kode_fasyankes = (SELECT kode_fasyankes FROM ms_rs_rujukan WHERE id_rs = '.$post['hospital'].')', NULL, FALSE);
				$this->db->or_where('(SELECT id_rs FROM xi_sa_users WHERE username = a.create_by) = '.$post['hospital'], NULL, FALSE);
			$this->db->group_end();
		}
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
		//asalpasien
		if(isset($post['asalpasien']) AND $post['asalpasien'] != '')
			$this->db->where('b.asalpasien', $post['asalpasien']);
		//pasienstatus
		if(isset($post['pasienstatus']) AND $post['pasienstatus'] != '')
			$this->db->where('a.pasienstatus', $post['pasienstatus']);
		//pasienlastkondisi
		if(isset($post['pasienlastkondisi']) AND $post['pasienlastkondisi'] != '')
			$this->db->where('b.pasienlastkondisi', $post['pasienlastkondisi']);
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
		$this->db->order_by('a.id_kasus DESC');
  }

	public function getDataDetailPasien($id_case, $token)
	{
		$this->db->select('a.id_pasien,
											 a.token,
											 a.pasienlastkondisi,
											 a.nik,
											 a.nokk,
											 a.namalkp,
											 a.tmptlhr,
											 DATE_FORMAT(a.tgllhr, "%d/%m/%Y") AS tgllhr,
											 a.umur,
											 a.gender,
											 a.study,
											 a.job,
											 a.province_id AS province,
											 a.regency_id AS regency,
											 a.district_id AS district,
											 a.village_id AS village,
											 a.address AS address,
											 a.nohp AS nohp,
											 b.norm,
											 b.kontaksuspekcovid19,
											 b.kontakcovid19,
											 b.riwayatperjalanan,
											 c.tindaklanjut,
											 c.id_hospital AS hospital,
											 c.rsrujukan,
											 c.namarsrawat,
											 c.id_fasilitas as nm_fasilitas,
											 DATE_FORMAT(c.tanggalmasukrawat, "%d/%m/%Y") AS tanggalmasukrawat,
											 c.rsrawatsebelumnya,
											 d.negara,
											 d.kota,
											 (CASE
											  	WHEN d.traveldate = "0000-00-00" THEN ""
													ELSE DATE_FORMAT(d.traveldate, "%d/%m/%Y")
											 END) AS traveldate,
											 (CASE
											  	WHEN d.arrivaldate = "0000-00-00" THEN ""
													ELSE DATE_FORMAT(d.arrivaldate, "%d/%m/%Y")
											 END) AS arrivaldate,
											 e.kode_fasyankes,
											 e.nama_fasyankes,
											 e.nama_pewawancara,
											 DATE_FORMAT(e.tgl_wawancara, "%d/%m/%Y") AS tgl_wawancara,
											 f.id_kasus,
											 f.pasienstatus,
											 f.jenis_kasus,
											 DATE_FORMAT(f.tgl_pulang, "%d/%m/%Y") AS tgl_pulang,
											 f.alasan_pulang,
											 DATE_FORMAT(f.tgl_meninggal, "%d/%m/%Y") AS tgl_meninggal,
											 (CASE f.waktu_meninggal
										 			WHEN "A" THEN "SETELAH TERKONFIRMASI COVID-19"
													ELSE "SEBELUM TERKONFIRMASI COVID-19"
										 	  END) AS waktu_meninggal,
											 f.publish_date,
											 f.create_date,
											 (SELECT id_rs FROM xi_sa_users WHERE username = f.create_by) AS pelapor');
		$this->db->from('ta_pasien a');
		$this->db->join('ta_pasien_medis b', 'a.id_pasien = b.id_pasien', 'left');
		$this->db->join('ta_pasien_tindakan c', 'a.id_pasien = c.id_pasien', 'left');
		$this->db->join('ta_paparan_area d', 'a.id_pasien = d.id_pasien', 'left');
		$this->db->join('ta_pewawancara e', 'a.id_pasien = e.id_pasien', 'left');
		$this->db->join('ta_pasien_status f', 'a.id_pasien = f.id_pasien', 'left');
		$this->db->where('a.token', $token);
		$this->db->where('f.id_kasus', $id_case);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getDataGejalaPasien($id_pasien)
	{
		$this->db->where('id_pasien', $id_pasien);
		$query = $this->db->get('ta_pasien_gejala');
		return $query->result_array();
	}

	public function getDataKomorbidPasien($id_pasien)
	{
		$this->db->where('id_pasien', $id_pasien);
		$query = $this->db->get('ta_pasien_komorbiditas');
		return $query->result_array();
	}

	public function getDataSpesimenPasien($id_pasien)
	{
		$this->db->where('id_pasien', $id_pasien);
		$this->db->order_by('id_swab DESC');
		$query = $this->db->get('ta_pasien_swab');
		return $query->result_array();
	}

	public function approveDataKasus()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$id_kasus     = $this->encryption->decrypt(escape($this->input->post('caseId', TRUE)));
		$token				= escape($this->input->post('tokenId', TRUE));
		//load class notification
		$notif = new notification_manager();
		//get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
		$id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		$namalkp  	= !empty($dataPasien) ? $dataPasien['namalkp'] : '';
		$nik	 			= !empty($dataPasien) ? $dataPasien['nik'] : '';
		$umur	 			= !empty($dataPasien) ? $dataPasien['umur'] : '';
		$gender	 		= !empty($dataPasien) ? $dataPasien['gender'] : '';
		$regency	 	= !empty($dataPasien) ? $dataPasien['regency_id'] : '';
		//get data tindak lanjut pasien
		$this->db->where('id_pasien', $id_pasien);
		$dataTL = $this->db->get('ta_pasien_tindakan')->row_array();
		$tindak = !empty($dataTL) ? $dataTL['tindaklanjut'] : 0;
		$id_rs  = ($tindak == 2) ? $dataTL['rsrujukan'] : $dataTL['id_hospital'];
		//get data kasus
		$this->db->where('id_kasus', $id_kasus);
		$this->db->where('id_pasien', $id_pasien);
		$this->db->where('status', 'S');
		$dataCase = $this->db->get('ta_pasien_status')->row_array();
		$pasienstatus = !empty($dataCase) ? $dataCase['pasienstatus'] : '';
		$jenis_kasus  = !empty($dataCase) ? $dataCase['jenis_kasus'] : '';
		if(count($dataCase) <= 0) {
			return array('message'=>'ERROR', 'nama'=>$namalkp.' ['.$nik.']');
		} else {
			//update data kasus
			$this->db->set('status', 'A');
			$this->db->set('publish_date', $this->_publishDate);
			$this->db->set('mod_by', $create_by);
			$this->db->set('mod_date', $create_date);
			$this->db->set('mod_ip', $create_ip);
			$this->db->where('id_kasus', $id_kasus);
			$this->db->where('id_pasien', $id_pasien);
			$this->db->where('status', 'S');
			$this->db->update('ta_pasien_status');
			//kirim notifikasi
			$note = ($jenis_kasus == 2) ? (($pasienstatus==3) ? 'Sembuh' : 'Negatif Covid-19') : (($jenis_kasus == 3) ? 'Meninggal Dunia' : 'Baru');
			$params['sender_id'] 		= $create_by;
			$params['recipient_id']	= $dataCase['create_by'];
			$params['type']					= 'case.verified';
			$params['parameters']		= $token;
			$params['reference']		=	'Data pasien bernama '.$namalkp.' ('.$umur.' th) dengan jenis kelamin '.jenis_kelamin($gender).' dari '.regency($regency).', yang dilaporkan sebagai kasus '.pasien_status($pasienstatus).' '.$note.' telah diverifikasi';
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			//kirim ke gugus tugas
			$paramNew['sender_id'] 		= $create_by;
			$paramNew['level_akses']	= array(6);
			$paramNew['type']					= 'data.new';
			$paramNew['parameters']		= $token;
			$paramNew['reference']		=	rujukan($id_rs).' telah melaporkan kasus '.pasien_status($pasienstatus).' '.$note.' per tanggal '.tgl_indo($this->_publishDate);
			$paramNew['create_by']		= $create_by;
			$paramNew['create_date']	= $create_date;
			$paramNew['create_ip']		= $create_ip;
			$notif->add($paramNew);
			return array('message'=>'SUCCESS', 'nama'=>$namalkp.' ['.$nik.']');
		}
	}

	public function getDataAkumulasiKasus($today=FALSE)
	{
		$this->db->select('pasienstatus,
											 jenis_kasus,
											 COUNT(id_kasus) as jumlah');
		$this->db->where('status !=', 'S');
		if($today==TRUE) {
			$this->db->where('publish_date', $this->_publishDate);
		} else {
			$this->db->where('publish_date <=', $this->_publishDate);
		}
		$this->db->group_by(array('pasienstatus', 'jenis_kasus'));
		$this->db->order_by('pasienstatus ASC');
		$this->db->order_by('jenis_kasus ASC');
		$query = $this->db->get('ta_pasien_status');
		return $query->result_array();
	}
}

// This is the end of auth signin model

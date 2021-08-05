<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_pasien extends CI_Model
{
	protected $_publishDate = "";
	protected $_gejala 			= array();
	protected $_penyerta 		= array();
	protected $_diagnosis		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('master/model_master' => 'mmas'));
		$datenow = (date('H:i:s') > waktu_publish()) ? date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))) : date('Y-m-d');
		$this->_publishDate = date('Y-m-d H:i:s', strtotime($datenow.' '.waktu_publish()));
		$this->_gejala      = $this->mmas->getDataMasterGejala();
		$this->_penyerta    = $this->mmas->getDataMasterKomorbiditas();
		$this->_diagnosis   = $this->mmas->getDataMasterDiagnosis();
	}

	public function validasiDataValue($flag)
	{
		if($flag == 1) {
			$tl = escape($this->input->post('tindaklanjut', TRUE));
			$this->form_validation->set_rules('nik', 'NIK', 'required|numeric|min_length[16]|max_length[16]|trim');
			$this->form_validation->set_rules('namalkp', 'Nama Pasien', 'required|trim');
			$this->form_validation->set_rules('nohp', 'No HP', 'required|trim');
			$this->form_validation->set_rules('tmptlhr', 'Tempat Lahir', 'required|trim');
			$this->form_validation->set_rules('tgllhr', 'Tempat Lahir', 'required|trim');
			$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required|trim');
			$this->form_validation->set_rules('province', 'Provinsi', 'required|trim');
			$this->form_validation->set_rules('regency', 'Kab/Kota', 'required|trim');
			$this->form_validation->set_rules('district', 'Kecamatan', 'required|trim');
			$this->form_validation->set_rules('village', 'Kelurahan/Desa/Nagari', 'required|trim');
			$this->form_validation->set_rules('norm', 'Nomor Rekap Medis', 'required|trim');
			if($this->input->post('riwayatperjalanan', TRUE) == 'Y') {
				$this->form_validation->set_rules('negara', 'Negara', 'required|trim');
				$this->form_validation->set_rules('kota', 'Kota', 'required|trim');
			}
			if($this->app_loader->is_admin()){
				$this->form_validation->set_rules('hospital', 'Nama Rumah Sakit', 'required|trim');
			}
			if($tl == 2) {
				$this->form_validation->set_rules('rsrujukan', 'Rumah Sakit Rujukan', 'required|trim');
			}
			if($tl == 4) {
				$this->form_validation->set_rules('nm_fasilitas', 'Lokasi Isolasi/Karantina', 'required|trim');
			}
			$this->form_validation->set_rules('tanggalmasukrawat', 'Tanggal Masuk', 'required|trim');
			//setting gejala
			foreach ($this->_gejala as $key => $g) {
				if($g['mandatory'] == 'Y')
					$this->form_validation->set_rules($g['nm_field'], $g['title'], 'required|trim');
			}
			//setting penyerta
			foreach ($this->_penyerta as $key => $p) {
				if($p['mandatory'] == 'Y')
					$this->form_validation->set_rules($p['nm_field'], $p['title'], 'required|trim');
			}
			//setting diagnosis
			foreach ($this->_diagnosis as $key => $d) {
				if($d['mandatory'] == 'Y')
					$this->form_validation->set_rules($d['nm_field'], $d['title'], 'required|trim');
			}
		} elseif($flag == 2) {
			$this->form_validation->set_rules('nik', 'NIK', 'required|numeric|min_length[16]|max_length[16]|trim');
			$this->form_validation->set_rules('namalkp', 'Nama Pasien', 'required|trim');
			$this->form_validation->set_rules('nohp', 'No HP', 'required|trim');
			$this->form_validation->set_rules('tmptlhr', 'Tempat Lahir', 'required|trim');
			$this->form_validation->set_rules('tgllhr', 'Tempat Lahir', 'required|trim');
			$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required|trim');
			$this->form_validation->set_rules('province', 'Provinsi', 'required|trim');
			$this->form_validation->set_rules('regency', 'Kab/Kota', 'required|trim');
			$this->form_validation->set_rules('district', 'Kecamatan', 'required|trim');
			$this->form_validation->set_rules('village', 'Kelurahan/Desa/Nagari', 'required|trim');
			$this->form_validation->set_rules('norm', 'Nomor Rekap Medis', 'required|trim');
			if($this->input->post('riwayatperjalanan', TRUE) == 'Y') {
				$this->form_validation->set_rules('negara', 'Negara', 'required|trim');
				$this->form_validation->set_rules('kota', 'Kota', 'required|trim');
			}
			//setting gejala
			foreach ($this->_gejala as $key => $g) {
				if($g['mandatory'] == 'Y')
					$this->form_validation->set_rules($g['nm_field'], $g['title'], 'required|trim');
			}
			//setting penyerta
			foreach ($this->_penyerta as $key => $p) {
				if($p['mandatory'] == 'Y')
					$this->form_validation->set_rules($p['nm_field'], $p['title'], 'required|trim');
			}
			//setting diagnosis
			foreach ($this->_diagnosis as $key => $d) {
				if($d['mandatory'] == 'Y')
					$this->form_validation->set_rules($d['nm_field'], $d['title'], 'required|trim');
			}
		} elseif($flag == 3) {
			$this->form_validation->set_rules('pasienstatus', 'Status Terakhir Pasien', 'required|trim');
			$this->form_validation->set_rules('pasienlastkondisi', 'Kondisi Pasien Saat Ini', 'required|trim');
			if($this->input->post('pasienlastkondisi', TRUE) == 1) {
				$this->form_validation->set_rules('tindaklanjut', 'Tindakan terhadap pasien', 'required|trim');
			}
			if($this->input->post('tindaklanjut', TRUE) == 2){
				$this->form_validation->set_rules('rsrujukan', 'Rumah Sakit Rujukan', 'required|trim');
			}
			if($this->input->post('tindaklanjut', TRUE) == 4) {
				$this->form_validation->set_rules('nm_fasilitas', 'Lokasi Isolasi/Karantina', 'required|trim');
			}
		}
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

	var $search = array('a.namalkp', 'a.nik', 'b.norm', 'c.namarsrawat', 'c.rsrawatsebelumnya');
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
		$this->db->select('a.id_pasien');
		$this->db->from('ta_pasien a');
		$this->db->join('ta_pasien_medis b', 'a.id_pasien = b.id_pasien', 'left');
		$this->db->join('ta_pasien_tindakan c', 'a.id_pasien = c.id_pasien', 'left');
		$this->db->where('a.pasienlastkondisi', 1);
		if($this->app_loader->is_hospital()) {
			$this->db->group_start();
				$this->db->where('a.create_by', $this->app_loader->current_account());
				$this->db->or_where('c.id_hospital', $this->app_loader->current_hospital());
				$this->db->or_where('c.rsrujukan', $this->app_loader->current_hospital());
			$this->db->group_end();
		}
    return $this->db->count_all_results();
  }

  private function _get_datatables_query($param)
  {
		$post = array();
		if (is_array($param)) {
      foreach ($param as $v) {
        $post[$v['name']] = $v['value'];
      }
    }
		$this->db->select('a.id_pasien,
											 a.token,
											 a.pasienstatus,
											 a.namalkp,
											 a.umur,
											 a.gender,
											 a.province_id,
											 a.regency_id,
											 a.district_id,
											 a.village_id,
											 a.address,
											 b.norm,
											 c.tindaklanjut,
											 c.id_hospital,
											 c.rsrujukan,
											 c.namarsrawat,
											 c.id_fasilitas,
											 c.rsrawatsebelumnya');
		$this->db->from('ta_pasien a');
		$this->db->join('ta_pasien_medis b', 'a.id_pasien = b.id_pasien', 'left');
		$this->db->join('ta_pasien_tindakan c', 'a.id_pasien = c.id_pasien', 'left');
		$this->db->where('a.pasienlastkondisi', 1);
		if($this->app_loader->is_hospital()) {
			$this->db->group_start();
				$this->db->where('a.create_by', $this->app_loader->current_account());
				$this->db->or_where('c.id_hospital', $this->app_loader->current_hospital());
				$this->db->or_where('c.rsrujukan', $this->app_loader->current_hospital());
			$this->db->group_end();
		}
		//provinsi
		if(isset($post['province']) AND $post['province'] != '')
			$this->db->where('a.province_id', $post['province']);
		//regency
		if(isset($post['regency']) AND $post['regency'] != '')
			$this->db->where('a.regency_id', $post['regency']);
		//district
		if(isset($post['district']) AND $post['district'] != '')
			$this->db->where('a.district_id', $post['district']);
		//village
		if(isset($post['village']) AND $post['village'] != '')
			$this->db->where('a.village_id', $post['village']);
		//tanggal lahir
		if(isset($post['tgllhr']) AND $post['tgllhr'] != '')
			$this->db->where('a.tgllhr', date_convert($post['tgllhr']));
		//umur
		if(isset($post['umur']) AND $post['umur'] != '')
			$this->db->where('a.umur', $post['umur']);
		//jenis kelamin
		if(isset($post['gender']) AND $post['gender'] != '')
			$this->db->where('a.gender', $post['gender']);
		//asalpasien
		if(isset($post['asalpasien']) AND $post['asalpasien'] != '')
			$this->db->where('a.asalpasien', $post['asalpasien']);
		//pendidikan
		if(isset($post['study']) AND $post['study'] != '')
			$this->db->where('a.study', $post['study']);
		//pasienstatus
		if(isset($post['pasienstatus']) AND $post['pasienstatus'] != '')
			$this->db->where('a.pasienstatus', $post['pasienstatus']);
		//tindaklanjut
		if(isset($post['tindaklanjut']) AND $post['tindaklanjut'] != '')
			$this->db->where('c.tindaklanjut', $post['tindaklanjut']);
		//rs rujukan
		if(isset($post['rsrujukan']) AND $post['rsrujukan'] != '')
			$this->db->where('c.rsrujukan', $post['rsrujukan']);
		//fasilitas pemerintah
		if(isset($post['nm_fasilitas']) AND $post['nm_fasilitas'] != '')
			$this->db->where('c.id_fasilitas', $post['nm_fasilitas']);
		//nama lengkap
		if(isset($post['namalkp']) AND $post['namalkp'] != '')
			$this->db->like('a.namalkp', $post['namalkp'], 'after');
		//nik
		if(isset($post['nik']) AND $post['nik'] != '')
			$this->db->like('a.nik', $post['nik'], 'after');
		//nokk
		if(isset($post['nokk']) AND $post['nokk'] != '')
			$this->db->like('a.nokk', $post['nokk'], 'after');
		//nohp
		if(isset($post['nohp']) AND $post['nohp'] != '')
			$this->db->like('a.nohp', $post['nohp'], 'after');
		//tempat lahir
		if(isset($post['tmptlhr']) AND $post['tmptlhr'] != '')
			$this->db->like('a.tmptlhr', $post['tmptlhr'], 'after');
		//address
		if(isset($post['address']) AND $post['address'] != '')
			$this->db->like('a.address', $post['address']);
		//pekerjaan
		if(isset($post['job']) AND $post['job'] != '')
			$this->db->like('a.job', $post['job']);
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
		$this->db->order_by('a.id_pasien ASC');
  }

	public function getDataDetailPasien($token)
	{
		$this->db->select('a.id_pasien,
											 a.token,
											 a.pasienstatus,
											 a.pasienlastkondisi,
											 a.nik,
											 a.nokk,
											 a.namalkp,
											 a.tmptlhr,
											 DATE_FORMAT(a.tgllhr, "%d/%m/%Y") AS tgllhr,
											 a.umur,
											 a.gender,
											 (CASE a.study
										 		WHEN 0 THEN ""
											  ELSE a.study
											 END) AS study,
											 a.job,
											 a.province_id AS province,
											 a.regency_id AS regency,
											 a.district_id AS district,
											 a.village_id AS village,
											 a.address AS address,
											 a.nohp AS nohp,
											 b.norm,
											 b.gajiperbln AS gajiperbln,
											 b.anggotabpjs AS anggotabpjs,
											 b.nobpjs AS nobpjs,
											 b.aktivitasfisik,
											 b.merokok,
											 b.konsumsialkohol,
											 b.suhu_tubuh,
											 b.tekanandarah,
											 b.nadi,
											 b.pernapasan,
											 b.tinggibadan,
											 b.beratbadan,
											 b.triage,
											 (CASE
											  	WHEN b.tanggalkeluhan = "0000-00-00" THEN ""
													ELSE DATE_FORMAT(b.tanggalkeluhan, "%d/%m/%Y")
											 END) AS tanggalkeluhan,
											 b.kontaksuspekcovid19,
											 b.kontakcovid19,
											 b.riwayatperjalanan,
											 b.pasienispa,
											 b.paparanlainnya,
											 c.tindaklanjut,
											 c.id_hospital AS hospital,
											 (CASE c.rsrujukan
											 	WHEN 0 THEN ""
												ELSE c.rsrujukan
											 END) AS rsrujukan,
											 c.namarsrawat,
											 c.id_fasilitas as nm_fasilitas,
											 DATE_FORMAT(c.tanggalmasukrawat, "%d/%m/%Y") AS tanggalmasukrawat,
											 c.dirawaticu,
											 c.intubasi,
											 c.penggunaanemco,
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
											 e.petugaskesehatan,
											 e.profesimedis,
											 e.gown,
											 e.maskermedis,
											 e.sarungtangan,
											 e.maskern95standardffp2,
											 e.ffp3,
											 e.kacamatapelindung,
											 e.tidakmemakaiapd,
											 f.kode_fasyankes,
											 f.nama_fasyankes,
											 f.nama_pewawancara,
											 DATE_FORMAT(f.tgl_wawancara, "%d/%m/%Y") AS tgl_wawancara');
		$this->db->from('ta_pasien a');
		$this->db->join('ta_pasien_medis b', 'a.id_pasien = b.id_pasien', 'left');
		$this->db->join('ta_pasien_tindakan c', 'a.id_pasien = c.id_pasien', 'left');
		$this->db->join('ta_paparan_area d', 'a.id_pasien = d.id_pasien', 'left');
		$this->db->join('ta_petugas_medis e', 'a.id_pasien = e.id_pasien', 'left');
		$this->db->join('ta_pewawancara f', 'a.id_pasien = f.id_pasien', 'left');
		$this->db->where('a.token', $token);
		$this->db->where('a.pasienlastkondisi', 1);
		if($this->app_loader->is_hospital()) {
			$this->db->group_start();
				$this->db->where('a.create_by', $this->app_loader->current_account());
				$this->db->or_where('c.id_hospital', $this->app_loader->current_hospital());
				$this->db->or_where('c.rsrujukan', $this->app_loader->current_hospital());
			$this->db->group_end();
		}
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

	public function getDataDiagnosisPasien($id_pasien)
	{
		$this->db->where('id_pasien', $id_pasien);
		$query = $this->db->get('ta_pasien_diagnosis');
		return $query->result_array();
	}

	public function getDataPaparanKontak($id_pasien)
	{
		$this->db->where('id_pasien', $id_pasien);
		$query = $this->db->get('ta_paparan_kontak');
		return $query->result_array();
	}

  public function getDataSpesimenPasien($id_pasien)
	{
		$this->db->where('id_pasien', $id_pasien);
		$this->db->order_by('id_swab DESC');
		$query = $this->db->get('ta_pasien_swab');
		return $query->result_array();
	}

	public function insertDataPasien()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$nik					= escape($this->input->post('nik', TRUE));
		$namalkp		  = escape($this->input->post('namalkp', TRUE));
		$token        = generateToken($nik, $namalkp);
		$province     = escape($this->input->post('province', TRUE));
		$regency			= escape($this->input->post('regency', TRUE));
		$gender				= escape($this->input->post('gender', TRUE));
		$umur					= escape($this->input->post('umur', TRUE));
		$riwayatjln		= escape($this->input->post('riwayatperjalanan', TRUE));
		$tindaklanjut	= escape($this->input->post('tindaklanjut', TRUE));
		$id_hospital  = ($this->app_loader->is_admin()) ?  escape($this->input->post('hospital', TRUE)) : $this->app_loader->current_hospital();
		$id_rujukan   = ($tindaklanjut == 2) ? escape($this->input->post('rsrujukan')) : 0;
		$namarsrawat  = ($tindaklanjut == 2) ? hospital($id_rujukan) : hospital($id_hospital);
		$dirawatrs    = ($tindaklanjut > 2 ) ? 'N' : 'Y';
		$kodefaskes   = explode('[', hospital($id_hospital, 1));
		$kontak				= escape($this->input->post('kontak', TRUE));
		//load class notification
		$notif = new notification_manager();
		//cek nik pasien yang diinputkan
		$this->db->where('nik', $nik);
		$qTot = $this->db->count_all_results('ta_pasien');
		if($qTot > 0)
			return array('message'=>'ERROR', 'nik'=>$nik, 'nama'=>$namalkp);
		else {
			//insert data pasien
			$pasien = array(
				'token' 						=> $token,
				'pasienstatus'			=> 2,
				'pasienlastkondisi'	=> 1,
				'nik'								=> $nik,
				'nokk'							=> escape($this->input->post('nokk', TRUE)),
				'namalkp'						=> strtoupper($namalkp),
				'tmptlhr'						=> strtoupper(escape($this->input->post('tmptlhr', TRUE))),
				'tgllhr'						=> date_convert(escape($this->input->post('tgllhr', TRUE))),
				'umur'							=> $umur,
				'gender'						=> $gender,
				'study'							=> escape($this->input->post('study', TRUE)),
				'job'								=> strtoupper(escape($this->input->post('job', TRUE))),
				'namaortu'					=> '',
				'asalpasien'				=> ($province != 13) ? 'Y' : 'N',
				'province_id'				=> $province,
				'regency_id'				=> $regency,
				'district_id'				=> escape($this->input->post('district', TRUE)),
				'village_id'				=> escape($this->input->post('village', TRUE)),
				'address'						=> strtoupper(escape($this->input->post('address', TRUE))),
				'nohp'							=> escape($this->input->post('nohp', TRUE)),
				'create_by'					=> $create_by,
				'create_date'				=> $create_date,
				'create_ip'					=> $create_ip,
				'mod_by'						=> $create_by,
				'mod_date'					=> $create_date,
				'mod_ip'						=> $create_ip
			);
			$this->db->insert('ta_pasien', $pasien);
			$id_pasien = $this->db->insert_id();
			//insert data medis pasien
			$medis = array(
				'id_pasien'							=> $id_pasien,
				'norm' 									=> escape($this->input->post('norm', TRUE)),
				'gajiperbln'						=> escape($this->input->post('gajiperbln', TRUE)),
				'anggotabpjs'						=> escape($this->input->post('anggotabpjs', TRUE)),
				'nobpjs'								=> escape($this->input->post('nobpjs', TRUE)),
				'aktivitasfisik' 				=> escape($this->input->post('aktivitasfisik', TRUE)),
				'merokok' 							=> escape($this->input->post('merokok', TRUE)),
				'konsumsialkohol' 			=> escape($this->input->post('konsumsialkohol', TRUE)),
				'suhu_tubuh' 						=> escape($this->input->post('suhu_tubuh', TRUE)),
				'tekanandarah' 					=> escape($this->input->post('tekanandarah', TRUE)),
				'nadi'	 								=> escape($this->input->post('nadi', TRUE)),
				'pernapasan' 						=> escape($this->input->post('pernapasan', TRUE)),
				'tinggibadan' 					=> escape($this->input->post('tinggibadan', TRUE)),
				'beratbadan' 						=> escape($this->input->post('beratbadan', TRUE)),
				'triage' 								=> escape($this->input->post('triage', TRUE)),
				'tanggalkeluhan' 				=> ($this->input->post('tanggalkeluhan', TRUE) != '') ? date_convert(escape($this->input->post('tanggalkeluhan', TRUE))) : '0000-00-00',
				'kontaksuspekcovid19' 	=> escape($this->input->post('kontaksuspekcovid19', TRUE)),
				'kontakcovid19' 				=> escape($this->input->post('kontakcovid19', TRUE)),
				'riwayatperjalanan' 		=> $riwayatjln,
				'pasienispa' 						=> escape($this->input->post('pasienispa', TRUE)),
				'paparanlainnya' 				=> escape($this->input->post('paparanlainnya', TRUE)),
				'create_by'							=> $create_by,
				'create_date'						=> $create_date,
				'create_ip'							=> $create_ip,
				'mod_by'								=> $create_by,
				'mod_date'							=> $create_date,
				'mod_ip'								=> $create_ip
			);
			$this->db->insert('ta_pasien_medis', $medis);
			//insert data tindak lanjut pasien
			$tindakan = array(
				'id_pasien'					=> $id_pasien,
				'tindaklanjut'			=> $tindaklanjut,
				'pasiendirawatrs' 	=> $dirawatrs,
				'id_hospital'				=> $id_hospital,
				'rsrujukan'					=> $id_rujukan,
				'namarsrawat' 			=> $namarsrawat,
				'id_fasilitas'			=> ($tindaklanjut == 4) ? escape($this->input->post('nm_fasilitas', TRUE)) : 0,
				'tanggalmasukrawat' => date_convert(escape($this->input->post('tanggalmasukrawat', TRUE))),
				'ruangrawat'				=> '',
				'dirawaticu'				=> ($tindaklanjut > 2 ) ? 'N' : escape($this->input->post('dirawaticu', TRUE)),
				'intubasi'					=> ($tindaklanjut > 2 ) ? 'N' : escape($this->input->post('intubasi', TRUE)),
				'penggunaanemco'		=> ($tindaklanjut > 2 ) ? 'N' : escape($this->input->post('penggunaanemco', TRUE)),
				'rsrawatsebelumnya'	=> ($tindaklanjut == 2) ? hospital($id_hospital) : '',
				'create_by'					=> $create_by,
				'create_date'				=> $create_date,
				'create_ip'					=> $create_ip,
				'mod_by'						=> $create_by,
				'mod_date'					=> $create_date,
				'mod_ip'						=> $create_ip
			);
			$this->db->insert('ta_pasien_tindakan', $tindakan);
			//insert data gejala
			$arrGejala = array();
			foreach ($this->_gejala as $key => $g) {
				$arrGejala[] = array(
					'id_pasien'			=> $id_pasien,
					'nama_gejala'		=> $g['nm_field'],
					'value_gejala'	=> escape($this->input->post($g['nm_field'], TRUE)),
					'create_by'			=> $create_by,
					'create_date'		=> $create_date,
					'create_ip'			=> $create_ip,
					'mod_by'				=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'				=> $create_ip
				);
			}
			$this->db->insert_batch('ta_pasien_gejala', $arrGejala);
			//insert data Komorbiditas
			$arrKomorbid = array();
			foreach ($this->_penyerta as $key => $p) {
				$value =  !empty($this->input->post($p['nm_field'], TRUE)) ? escape($this->input->post($p['nm_field'], TRUE)) : (($p['tp_field'] == 'checkbox') ? 'N' : '');
				$arrKomorbid[] = array(
					'id_pasien'				=> $id_pasien,
					'nama_komorbid'		=> $p['nm_field'],
					'value_komorbid'	=> $value,
					'create_by'				=> $create_by,
					'create_date'			=> $create_date,
					'create_ip'				=> $create_ip,
					'mod_by'					=> $create_by,
					'mod_date'				=> $create_date,
					'mod_ip'					=> $create_ip
				);
			}
			$this->db->insert_batch('ta_pasien_komorbiditas', $arrKomorbid);
			//insert data diagnosis
			$arrDiagnosis = array();
			foreach ($this->_diagnosis as $key => $d) {
				$arrDiagnosis[] = array(
					'id_pasien'				=> $id_pasien,
					'nama_diagnosis'	=> $d['nm_field'],
					'value_diagnosis'	=> escape($this->input->post($d['nm_field'], TRUE)),
					'create_by'				=> $create_by,
					'create_date'			=> $create_date,
					'create_ip'				=> $create_ip,
					'mod_by'					=> $create_by,
					'mod_date'				=> $create_date,
					'mod_ip'					=> $create_ip
				);
			}
			$this->db->insert_batch('ta_pasien_diagnosis', $arrDiagnosis);
			//insert pewawacan
			$wawancara = array(
				'id_pasien'					=> $id_pasien,
				'kode_fasyankes'		=> rtrim($kodefaskes[1], ']'),
				'nama_fasyankes'		=> hospital($id_hospital),
				'nama_pewawancara'	=> rujukan($id_hospital),
				'tgl_wawancara'			=> date('Y-m-d', strtotime($create_date)),
				'hp_wawancara'			=> '',
				'create_by'					=> $create_by,
				'create_date'				=> $create_date,
				'create_ip'					=> $create_ip,
				'mod_by'						=> $create_by,
				'mod_date'					=> $create_date,
				'mod_ip'						=> $create_ip
			);
			$this->db->insert('ta_pewawancara', $wawancara);
			//insert petugas medis
			$petugas = array(
				'id_pasien'							=> $id_pasien,
				'petugaskesehatan'			=> escape($this->input->post('petugaskesehatan', TRUE)),
				'profesimedis'					=> escape($this->input->post('profesimedis', TRUE)),
				'gown'									=> !empty($this->input->post('gown', TRUE)) ? escape($this->input->post('gown', TRUE)) : 'N',
				'maskermedis'						=> !empty($this->input->post('maskermedis', TRUE)) ? escape($this->input->post('maskermedis', TRUE)) : 'N',
				'sarungtangan'					=> !empty($this->input->post('sarungtangan', TRUE)) ? escape($this->input->post('sarungtangan', TRUE)) : 'N',
				'maskern95standardffp2'	=> !empty($this->input->post('maskern95standardffp2', TRUE)) ? escape($this->input->post('maskern95standardffp2', TRUE)) : 'N',
				'ffp3'									=> !empty($this->input->post('ffp3', TRUE)) ? escape($this->input->post('ffp3', TRUE)) : 'N',
				'kacamatapelindung'			=> !empty($this->input->post('kacamatapelindung', TRUE)) ? escape($this->input->post('kacamatapelindung', TRUE)) : 'N',
				'tidakmemakaiapd'				=> !empty($this->input->post('tidakmemakaiapd', TRUE)) ? escape($this->input->post('tidakmemakaiapd', TRUE)) : 'N',
				'proseduraerosol'				=> 'N',
				'create_by'							=> $create_by,
				'create_date'						=> $create_date,
				'create_ip'							=> $create_ip,
				'mod_by'								=> $create_by,
				'mod_date'							=> $create_date,
				'mod_ip'								=> $create_ip
			);
			$this->db->insert('ta_petugas_medis', $petugas);
			//insert riwayat perjalanan
			if($riwayatjln == 'Y') {
				$area = array(
					'id_pasien'		=> $id_pasien,
					'negara'			=> escape($this->input->post('negara', TRUE)),
					'kota'				=> strtoupper(escape($this->input->post('kota', TRUE))),
					'traveldate' 	=> ($this->input->post('traveldate', TRUE) != '') ? date_convert(escape($this->input->post('traveldate', TRUE))) : '0000-00-00',
					'arrivaldate' => ($this->input->post('arrivaldate', TRUE) != '') ? date_convert(escape($this->input->post('arrivaldate', TRUE))) : '0000-00-00',
					'create_by'		=> $create_by,
					'create_date'	=> $create_date,
					'create_ip'		=> $create_ip,
					'mod_by'			=> $create_by,
					'mod_date'		=> $create_date,
					'mod_ip'			=> $create_ip
				);
				$this->db->insert('ta_paparan_area', $area);
			}
			//insert paparan kontak
			$arrKontak = array();
			if(count($kontak) > 0) {
				for ($i=0; $i < count($kontak); $i++) {
					$arrKontak[] = array(
						'id_pasien'				=> $id_pasien,
						'nik'							=> escape($_POST['kontak'][$i]['nik']),
						'namalkp'					=> strtoupper(escape($_POST['kontak'][$i]['namalkp'])),
						'tmptlhr'					=> '',
						'tgllhr'					=> '0000-00-00',
						'umur'						=> escape($_POST['kontak'][$i]['umur']),
						'gender'					=> escape($_POST['kontak'][$i]['gender']),
						'hubdgnkasus'			=> strtoupper(escape($_POST['kontak'][$i]['hubdgnkasus'])),
						'address'					=> strtoupper(escape($_POST['kontak'][$i]['address'])),
						'nohp'						=> escape($_POST['kontak'][$i]['nohp']),
						'aktivitaskontak'	=> strtoupper(escape($_POST['kontak'][$i]['aktivitas'])),
						'rmhsama'					=> !empty($_POST['kontak'][$i]['rmhsama']) ? escape($_POST['kontak'][$i]['rmhsama']) : 0,
						'create_by'				=> $create_by,
						'create_date'			=> $create_date,
						'create_ip'				=> $create_ip,
						'mod_by'					=> $create_by,
						'mod_date'				=> $create_date,
						'mod_ip'					=> $create_ip
					);
				}
				if($arrKontak[0]['namalkp'] != '') {
					$this->db->insert_batch('ta_paparan_kontak', $arrKontak);
				}
			}
			//insert ke table pasien status
			$kasus = array(
				'id_pasien'					=> $id_pasien,
				'pasienstatus'			=> 2,
				'jenis_kasus'				=> 1,
				'tgl_pulang'				=> '0000-00-00',
				'alasan_pulang'			=> '',
				'tgl_meninggal'			=> '0000-00-00',
				'waktu_meninggal'		=> '',
				'status'						=> 'S',
				'publish_date'			=> $this->_publishDate,
				'create_by'					=> $create_by,
				'create_date'				=> $create_date,
				'create_ip'					=> $create_ip,
				'mod_by'						=> $create_by,
				'mod_date'					=> $create_date,
				'mod_ip'						=> $create_ip
			);
			$this->db->insert('ta_pasien_status', $kasus);
			//kirim notifikasi
			$ket = ($tindaklanjut == 4) ? 'sekarang menjalani isolasi diri di '.fasilitas(escape($this->input->post('nm_fasilitas', TRUE))).' dari tanggal' : (($tindaklanjut == 3) ? 'sekarang menjalani isolasi diri di rumah dari tanggal' : (($tindaklanjut==2) ? 'telah dirujuk ke rumah sakit '.$namarsrawat.' pada tanggal' : 'sekarang dirawat di rumah sakit dari tanggal'));
			$params['sender_id'] 		= ($this->app_loader->is_admin()) ? $notif->getUserByHospital($id_hospital) : $create_by;
			$params['level_akses']	= array(2);
			$params['type']					= 'case.new';
			$params['parameters']		= $token;
			$params['reference']		=	'Penambahan pasien '.pasien_status(2).' baru dengan jenis kelamin '.jenis_kelamin($gender).' bernama '.$namalkp.' ('.$umur.' th) dari '.regency($regency).' '.$ket.' '.tgl_indo(date_convert(escape($this->input->post('tanggalmasukrawat', TRUE))));
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			if($tindaklanjut == 2) {
				$paramNew['sender_id'] 		= ($this->app_loader->is_admin()) ? $notif->getUserByHospital($id_hospital) : $create_by;
				$paramNew['recipient_id']	= $notif->getUserByHospital($id_rujukan);
				$paramNew['type']					= 'case.ref';
				$paramNew['parameters']		= $token;
				$paramNew['reference']		=	'Pasien '.pasien_status(2).' atas nama '.$namalkp.' ('.$umur.' th) dengan jenis kelamin '.jenis_kelamin($gender).' dari '.regency($regency).', pada tanggal '.tgl_indo(date_convert(escape($this->input->post('tanggalmasukrawat', TRUE)))).' kami rujuk ke rumah sakit anda';
				$paramNew['create_by']		= $create_by;
				$paramNew['create_date']	= $create_date;
				$paramNew['create_ip']		= $create_ip;
				$notif->add($paramNew);
			}
			return array('message'=>'SUCCESS', 'nik'=>$nik, 'nama'=>$namalkp);
		}
	}

	public function updateDataPasien()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$nik					= escape($this->input->post('nik', TRUE));
		$namalkp		  = escape($this->input->post('namalkp', TRUE));
		$token        = escape($this->input->post('tokenId', TRUE));
		$province     = escape($this->input->post('province', TRUE));
		$riwayatjln		= escape($this->input->post('riwayatperjalanan', TRUE));
		$kontak				= escape($this->input->post('kontak', TRUE));
		//get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
		$id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		//cek nik pasien yang diinputkan
		$this->db->where('nik', $nik);
		$this->db->where('token !=', $token);
		$qTot = $this->db->count_all_results('ta_pasien');
		if($qTot > 0)
			return array('message'=>'ERROR', 'nik'=>$nik, 'nama'=>$namalkp);
		else {
			//update data pasien
			$pasien = array(
				'nik'						=> $nik,
				'nokk'					=> escape($this->input->post('nokk', TRUE)),
				'namalkp'				=> strtoupper($namalkp),
				'tmptlhr'				=> strtoupper(escape($this->input->post('tmptlhr', TRUE))),
				'tgllhr'				=> date_convert(escape($this->input->post('tgllhr', TRUE))),
				'umur'					=> escape($this->input->post('umur', TRUE)),
				'gender'				=> escape($this->input->post('gender', TRUE)),
				'study'					=> escape($this->input->post('study', TRUE)),
				'job'						=> strtoupper(escape($this->input->post('job', TRUE))),
				'namaortu'			=> '',
				'asalpasien'		=> ($province != 13) ? 'Y' : 'N',
				'province_id'		=> $province,
				'regency_id'		=> escape($this->input->post('regency', TRUE)),
				'district_id'		=> escape($this->input->post('district', TRUE)),
				'village_id'		=> escape($this->input->post('village', TRUE)),
				'address'				=> strtoupper(escape($this->input->post('address', TRUE))),
				'nohp'					=> escape($this->input->post('nohp', TRUE)),
				'mod_by'				=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'				=> $create_ip
			);
			$this->db->where('token', $token);
			$this->db->update('ta_pasien', $pasien);
			//insert data medis pasien
			$medis = array(
				'norm' 									=> escape($this->input->post('norm', TRUE)),
				'gajiperbln'						=> escape($this->input->post('gajiperbln', TRUE)),
				'anggotabpjs'						=> escape($this->input->post('anggotabpjs', TRUE)),
				'nobpjs'								=> escape($this->input->post('nobpjs', TRUE)),
				'aktivitasfisik' 				=> escape($this->input->post('aktivitasfisik', TRUE)),
				'merokok' 							=> escape($this->input->post('merokok', TRUE)),
				'konsumsialkohol' 			=> escape($this->input->post('konsumsialkohol', TRUE)),
				'suhu_tubuh' 						=> escape($this->input->post('suhu_tubuh', TRUE)),
				'tekanandarah' 					=> escape($this->input->post('tekanandarah', TRUE)),
				'nadi'	 								=> escape($this->input->post('nadi', TRUE)),
				'pernapasan' 						=> escape($this->input->post('pernapasan', TRUE)),
				'tinggibadan' 					=> escape($this->input->post('tinggibadan', TRUE)),
				'beratbadan' 						=> escape($this->input->post('beratbadan', TRUE)),
				'triage' 								=> escape($this->input->post('triage', TRUE)),
				'tanggalkeluhan' 				=> ($this->input->post('tanggalkeluhan', TRUE) != '') ? date_convert(escape($this->input->post('tanggalkeluhan', TRUE))) : '0000-00-00',
				'kontaksuspekcovid19' 	=> escape($this->input->post('kontaksuspekcovid19', TRUE)),
				'kontakcovid19' 				=> escape($this->input->post('kontakcovid19', TRUE)),
				'riwayatperjalanan' 		=> $riwayatjln,
				'pasienispa' 						=> escape($this->input->post('pasienispa', TRUE)),
				'paparanlainnya' 				=> escape($this->input->post('paparanlainnya', TRUE)),
				'mod_by'								=> $create_by,
				'mod_date'							=> $create_date,
				'mod_ip'								=> $create_ip
			);
			$this->db->where('id_pasien', $id_pasien);
			$this->db->update('ta_pasien_medis', $medis);
			//update data gejala
			foreach ($this->_gejala as $key => $g) {
				$this->db->set('value_gejala', escape($this->input->post($g['nm_field'], TRUE)));
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('id_pasien', $id_pasien);
				$this->db->where('nama_gejala', $g['nm_field']);
				$this->db->update('ta_pasien_gejala');
			}
			//update data Komorbiditas
			foreach ($this->_penyerta as $key => $p) {
				$value =  !empty($this->input->post($p['nm_field'], TRUE)) ? escape($this->input->post($p['nm_field'], TRUE)) : (($p['tp_field'] == 'checkbox') ? 'N' : '');
				$this->db->set('value_komorbid', $value);
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('id_pasien', $id_pasien);
				$this->db->where('nama_komorbid', $p['nm_field']);
				$this->db->update('ta_pasien_komorbiditas');
			}
			//update data diagnosis
			foreach ($this->_diagnosis as $key => $d) {
				$this->db->set('value_diagnosis', escape($this->input->post($d['nm_field'], TRUE)));
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('id_pasien', $id_pasien);
				$this->db->where('nama_diagnosis', $d['nm_field']);
				$this->db->update('ta_pasien_diagnosis');
			}
			//update petugas medis
			$petugas = array(
				'petugaskesehatan'			=> escape($this->input->post('petugaskesehatan', TRUE)),
				'profesimedis'					=> escape($this->input->post('profesimedis', TRUE)),
				'gown'									=> !empty($this->input->post('gown', TRUE)) ? escape($this->input->post('gown', TRUE)) : 'N',
				'maskermedis'						=> !empty($this->input->post('maskermedis', TRUE)) ? escape($this->input->post('maskermedis', TRUE)) : 'N',
				'sarungtangan'					=> !empty($this->input->post('sarungtangan', TRUE)) ? escape($this->input->post('sarungtangan', TRUE)) : 'N',
				'maskern95standardffp2'	=> !empty($this->input->post('maskern95standardffp2', TRUE)) ? escape($this->input->post('maskern95standardffp2', TRUE)) : 'N',
				'ffp3'									=> !empty($this->input->post('ffp3', TRUE)) ? escape($this->input->post('ffp3', TRUE)) : 'N',
				'kacamatapelindung'			=> !empty($this->input->post('kacamatapelindung', TRUE)) ? escape($this->input->post('kacamatapelindung', TRUE)) : 'N',
				'tidakmemakaiapd'				=> !empty($this->input->post('tidakmemakaiapd', TRUE)) ? escape($this->input->post('tidakmemakaiapd', TRUE)) : 'N',
				'proseduraerosol'				=> 'N',
				'mod_by'								=> $create_by,
				'mod_date'							=> $create_date,
				'mod_ip'								=> $create_ip
			);
			$this->db->where('id_pasien', $id_pasien);
			$this->db->update('ta_petugas_medis', $petugas);
			//update riwayat perjalanan
			if($riwayatjln == 'Y') {
				//cek data sebelumnya udah ada atau belum
				$this->db->where('id_pasien', $id_pasien);
				$qArea = $this->db->count_all_results('ta_paparan_area');
				if($qArea <= 0) {
					$area = array(
						'id_pasien'		=> $id_pasien,
						'negara'			=> escape($this->input->post('negara', TRUE)),
						'kota'				=> strtoupper(escape($this->input->post('kota', TRUE))),
						'traveldate' 	=> ($this->input->post('traveldate', TRUE) != '') ? date_convert(escape($this->input->post('traveldate', TRUE))) : '0000-00-00',
						'arrivaldate' => ($this->input->post('arrivaldate', TRUE) != '') ? date_convert(escape($this->input->post('arrivaldate', TRUE))) : '0000-00-00',
						'create_by'		=> $create_by,
						'create_date'	=> $create_date,
						'create_ip'		=> $create_ip,
						'mod_by'			=> $create_by,
						'mod_date'		=> $create_date,
						'mod_ip'			=> $create_ip
					);
					$this->db->insert('ta_paparan_area', $area);
				} else {
					$area = array(
						'negara'			=> escape($this->input->post('negara', TRUE)),
						'kota'				=> strtoupper(escape($this->input->post('kota', TRUE))),
						'traveldate' 	=> ($this->input->post('traveldate', TRUE) != '') ? date_convert(escape($this->input->post('traveldate', TRUE))) : '0000-00-00',
						'arrivaldate' => ($this->input->post('arrivaldate', TRUE) != '') ? date_convert(escape($this->input->post('arrivaldate', TRUE))) : '0000-00-00',
						'mod_by'			=> $create_by,
						'mod_date'		=> $create_date,
						'mod_ip'			=> $create_ip
					);
					$this->db->where('id_pasien', $id_pasien);
					$this->db->update('ta_paparan_area', $area);
				}
			} else {
				$this->db->where('id_pasien', $id_pasien);
				$this->db->delete('ta_paparan_area');
			}
			//insert paparan kontak
			$arrKontak = array();
			if(count($kontak) > 0) {
				for ($i=0; $i < count($kontak); $i++) {
					$arrKontak[] = array(
						'id_pasien'				=> $id_pasien,
						'nik'							=> escape($_POST['kontak'][$i]['nik']),
						'namalkp'					=> strtoupper(escape($_POST['kontak'][$i]['namalkp'])),
						'tmptlhr'					=> '',
						'tgllhr'					=> '0000-00-00',
						'umur'						=> escape($_POST['kontak'][$i]['umur']),
						'gender'					=> escape($_POST['kontak'][$i]['gender']),
						'hubdgnkasus'			=> strtoupper(escape($_POST['kontak'][$i]['hubdgnkasus'])),
						'address'					=> strtoupper(escape($_POST['kontak'][$i]['address'])),
						'nohp'						=> escape($_POST['kontak'][$i]['nohp']),
						'aktivitaskontak'	=> strtoupper(escape($_POST['kontak'][$i]['aktivitas'])),
						'rmhsama'					=> !empty($_POST['kontak'][$i]['rmhsama']) ? escape($_POST['kontak'][$i]['rmhsama']) : 0,
						'create_by'				=> $create_by,
						'create_date'			=> $create_date,
						'create_ip'				=> $create_ip,
						'mod_by'					=> $create_by,
						'mod_date'				=> $create_date,
						'mod_ip'					=> $create_ip
					);
				}
				if($arrKontak[0]['namalkp'] != '') {
					$this->db->insert_batch('ta_paparan_kontak', $arrKontak);
				}
			}
			return array('message'=>'SUCCESS', 'nik'=>$nik, 'nama'=>$namalkp);
		}
	}

	public function updateDataStatusPasien()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$token        = escape($this->input->post('tokenpasien', TRUE));
		$statusnew		= escape($this->input->post('pasienstatus', TRUE));
		$kondisinew		= escape($this->input->post('pasienlastkondisi', TRUE));
		$tindaklanjut = escape($this->input->post('tindaklanjut', TRUE));
		//load class notification
		$notif = new notification_manager();
		//get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
		$id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		$nik  		  = !empty($dataPasien) ? $dataPasien['nik'] : 0;
		$namalkp  	= !empty($dataPasien) ? $dataPasien['namalkp'] : 0;
		$gender  		= !empty($dataPasien) ? $dataPasien['gender'] : 0;
		$umur  			= !empty($dataPasien) ? $dataPasien['umur'] : 0;
		$regency  	= !empty($dataPasien) ? $dataPasien['regency_id'] : 0;
		$statusold  = !empty($dataPasien) ? $dataPasien['pasienstatus'] : '';
		if(count($dataPasien) <= 0){
			return array('message'=>'ERROR', 'nik'=>'', 'nama'=>'');
		} else {
			//get data tindakan sebelumnya
			$this->db->where('id_pasien', $id_pasien);
			$dataTL = $this->db->get('ta_pasien_tindakan')->row_array();
			$tlold  = !empty($dataTL) ? $dataTL['tindaklanjut'] : '';
			$id_rs  = !empty($dataTL) ? $dataTL['id_hospital'] : '';
			$rsrjkn = !empty($dataTL) ? $dataTL['rsrujukan'] : '';
			//insert jika terjadi perubahan status pasien
			if($statusold != $statusnew) {
				$kasus = array(
					'id_pasien'				=> $id_pasien,
					'pasienstatus'		=> $statusnew,
					'jenis_kasus'			=> 1,
					'tgl_pulang'			=> '0000-00-00',
					'alasan_pulang'		=> '',
					'tgl_meninggal'		=> '0000-00-00',
					'waktu_meninggal'	=> '',
					'status'					=> 'S',
					'publish_date'		=> $this->_publishDate,
					'create_by'				=> $create_by,
					'create_date'			=> $create_date,
					'create_ip'				=> $create_ip,
					'mod_by'					=> $create_by,
					'mod_date'				=> $create_date,
					'mod_ip'					=> $create_ip
				);
				$this->db->insert('ta_pasien_status', $kasus);
			}
			//update kondisi terakhir pasien
			if($kondisinew == 1) {
				$this->db->set('tindaklanjut', $tindaklanjut);
				$this->db->set('pasiendirawatrs', ($tindaklanjut > 2) ? 'N' : 'Y');
				if($tindaklanjut == 2) {
					$this->db->set('id_hospital', ($tlold == 2) ? $rsrjkn : $id_rs);
					$this->db->set('rsrujukan', escape($this->input->post('rsrujukan', TRUE)));
					$this->db->set('namarsrawat', hospital(escape($this->input->post('rsrujukan', TRUE))));
					$this->db->set('rsrawatsebelumnya',  ($tlold == 2) ? hospital($rsrjkn) : hospital($id_rs));
				}
				$this->db->set('id_fasilitas', ($tindaklanjut == 4) ? escape($this->input->post('nm_fasilitas', TRUE)) : 0);
				$this->db->set('dirawaticu', ($tindaklanjut > 2) ? 'N' : escape($this->input->post('dirawaticu', TRUE)));
				$this->db->set('intubasi', ($tindaklanjut > 2) ? 'N' : escape($this->input->post('intubasi', TRUE)));
				$this->db->set('penggunaanemco', ($tindaklanjut > 2) ? 'N' : escape($this->input->post('penggunaanemco', TRUE)));
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('id_pasien', $id_pasien);
				$this->db->update('ta_pasien_tindakan');
			} else {
				//insert ke ta pasien status
				$kasus = array(
					'id_pasien'				=> $id_pasien,
					'pasienstatus'		=> $statusnew,
					'jenis_kasus'			=> $kondisinew,
					'tgl_pulang'			=> ($kondisinew == '2') ? date_convert(escape($this->input->post('tgl_pulang', TRUE))) : '0000-00-00',
					'alasan_pulang'		=> ($kondisinew == '2') ? escape($this->input->post('alasan_pulang', TRUE)) : '',
					'tgl_meninggal'		=> ($kondisinew == '3') ? date_convert(escape($this->input->post('tgl_meninggal', TRUE))) : '0000-00-00',
					'waktu_meninggal'	=> ($kondisinew == '3') ? (($statusold == 2) ? 'B' : 'A') : '',
					'status'					=> 'S',
					'publish_date'		=> $this->_publishDate,
					'create_by'				=> $create_by,
					'create_date'			=> $create_date,
					'create_ip'				=> $create_ip,
					'mod_by'					=> $create_by,
					'mod_date'				=> $create_date,
					'mod_ip'					=> $create_ip
				);
				$this->db->insert('ta_pasien_status', $kasus);
			}
			//update data pasien
			$this->db->set('pasienstatus', $statusnew);
			$this->db->set('pasienlastkondisi', $kondisinew);
			$this->db->set('mod_by', $create_by);
			$this->db->set('mod_date', $create_date);
			$this->db->set('mod_ip', $create_ip);
			$this->db->where('id_pasien', $id_pasien);
			$this->db->update('ta_pasien');
			//kirim notifikasi
			if($kondisinew == 2)
				$note = (($statusnew == 3) ? ' telah dinyatakan sembuh dari covid-19' : ' berdasarkan hasil swab dinyatakan negatif covid-19').(($this->input->post('tgl_pulang', TRUE) != '') ? ' pada tanggal '.tgl_indo(date_convert(escape($this->input->post('tgl_pulang', TRUE)))) : '');
			else if($kondisinew == 3)
				$note = ' telah meninggal dunia'.(($this->input->post('tgl_meninggal', TRUE) != '') ? ' pada tanggal '.tgl_indo(date_convert(escape($this->input->post('tgl_meninggal', TRUE)))) : '');
			else
				$note = ($tindaklanjut == 4) ? 'sekarang menjalani isolasi diri di '.fasilitas(escape($this->input->post('nm_fasilitas', TRUE))) : (($tindaklanjut == 3) ? 'sekarang menjalani isolasi diri di rumah' : (($tindaklanjut==2) ? 'telah dirujuk ke rumah sakit '.hospital(escape($this->input->post('rsrujukan', TRUE))).' pada tanggal '.date_convert(date('Y-m-d')) : 'sekarang dirawat di rumah sakit'));

			if($statusnew != $statusold)
				$ket = 'Penambahan pasien '.pasien_status($statusnew).' baru dengan jenis kelamin '.jenis_kelamin($gender).' bernama '.$namalkp.' ('.$umur.' th) dari '.regency($regency).' '.$note;
			else
				$ket = 'Pasien '.pasien_status($statusnew).' atas nama '.$namalkp.' ('.$umur.' th) dengan jenis kelamin '.jenis_kelamin($gender).' dari '.regency($regency).' '.$note;
			$params['sender_id'] 		= ($this->app_loader->is_admin()) ? $notif->getUserByHospital(($tlold == 2) ? $rsrjkn : $id_rs) : $create_by;
			$params['level_akses']	= array(2);
			$params['type']					= 'case.new';
			$params['parameters']		= $token;
			$params['reference']		=	$ket;
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			if($tindaklanjut == 2) {
				$paramNew['sender_id'] 		= ($this->app_loader->is_admin()) ? $notif->getUserByHospital(($tlold == 2) ? $rsrjkn : $id_rs) : $create_by;
				$paramNew['recipient_id']	= $notif->getUserByHospital(escape($this->input->post('rsrujukan', TRUE)));
				$paramNew['type']					= 'case.ref';
				$paramNew['parameters']		= $token;
				$paramNew['reference']		=	'Pasien '.pasien_status($statusnew).' atas nama '.$namalkp.' ('.$umur.' th) dengan jenis kelamin '.jenis_kelamin($gender).' dari '.regency($regency).', pada tanggal '.tgl_indo(date('Y-m-d')).' kami rujuk ke rumah sakit anda';
				$paramNew['create_by']		= $create_by;
				$paramNew['create_date']	= $create_date;
				$paramNew['create_ip']		= $create_ip;
				$notif->add($paramNew);
			}
			return array('message'=>'SUCCESS', 'nik'=>$nik, 'nama'=>$namalkp);
		}
	}

	public function getDataAkumulasiKasus()
	{
		$this->db->select('c.tindaklanjut AS key_column,
											 COUNT(a.id_pasien) AS total');
		$this->db->from('ta_pasien a');
		$this->db->join('ta_pasien_tindakan c', 'a.id_pasien = c.id_pasien', 'left');
		$this->db->where('a.pasienlastkondisi', 1);
		if($this->app_loader->is_hospital()) {
			$this->db->group_start();
				$this->db->where('a.create_by', $this->app_loader->current_account());
				$this->db->or_where('c.id_hospital', $this->app_loader->current_hospital());
				$this->db->or_where('c.rsrujukan', $this->app_loader->current_hospital());
			$this->db->group_end();
		}
		$this->db->group_by('c.tindaklanjut');
		$query = $this->db->get();
		return $query->result_array();
	}

}

// This is the end of auth signin model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_lap_oksigen extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function getKategori()
	{
		$this->db->select('a.id_kat_tabung,
							a.nm_tabung,
							');
		$this->db->from('ref_kat_tabung a');
    	$query = $this->db->get();
    	return $query->result_array();
	}

	public function getDataPemakaian()
	{
		// $arrDate = explode(' - ', $tanggal);
		$this->db->select('a.tanggal_pemakaian,
							b.fullname as shortname,
							a.id_kat_tabung,
							a.id_rs
							');
		$this->db->from('ta_pemakaian_tabung a');
		$this->db->join('ms_rs_rujukan b ','a.id_rs=b.id_rs','left');
		$this->db->ORDER_BY('b.fullname ASC');
		$this->db->group_by(array('a.id_rs', 'a.tanggal_pemakaian'));
		// if($tanggal != '')
		// 	$this->db->where('DATE_FORMAT(a.tanggal_pemakaian, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"');
    	$query = $this->db->get();
		return $query->result_array();
	}

	public function get_transaksi_tabung()
	{
		$this->db->select('a.tanggal_pemakaian,
							b.id_rs,
							a.id_kat_tabung,
							a.total_terpakai
							');
		$this->db->from('ta_pemakaian_tabung a');
		$this->db->join('ms_rs_rujukan b ','a.id_rs=b.id_rs','left');
		$this->db->group_by(array('a.tanggal_pemakaian', 'a.id_kat_tabung','a.id_rs'));
		$this->db->ORDER_BY('b.fullname ASC');
		// $this->db->group_by(array('a.id_rs', 'a.tanggal_pemakaian'));
		// if($tanggal != '')
		// 	$this->db->where('DATE_FORMAT(a.tanggal_pemakaian, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"');
    	$query = $this->db->get();
		return $query->result_array();
	}

	// public function getTotalTerpakai($date, $rs, $tabung){
	// 	$this->db->select('a.id_rs,
	// 					   a.tanggal_pemakaian,
	// 					   a.id_kat_tabung,
	// 					   a.total_terpakai');
	// 	$this->db->from('ta_pemakaian_tabung a');
	// 	$this->db->where('a.tanggal_pemakaian', $date);
	// 	$this->db->where('a.id_rs', $rs);
	// 	$this->db->where('a.id_kat_tabung', $tabung);
	// 	$query = $this->db->get();
	// 	return $query->row_array();
	// }

	public function ReportKasus($regency_id, $start_date, $end_date)
	{
		// $arrDate = explode(' - ', $tanggal);
        $this->db->select('a.id_spesimen_sample,
								a.total_spesimen,
								a.total_pemeriksaan,
								a.tanggal_spesimen,
								a.regency_id
								');
		$this->db->from('ta_spesimen_sample a');
		if($regency_id != '')
			$this->db->where('a.regency_id', $regency_id);
		if($start_date != '' || $end_date != '')
			// $this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"');
			$this->db->where('DATE_FORMAT(a.tanggal_spesimen, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
		$this->db->order_by('a.id_spesimen_sample ASC');

		$query = $this->db->get();
    	return $query->result_array();
	}
}

// This is the end of model

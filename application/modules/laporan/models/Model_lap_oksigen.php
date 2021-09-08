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

	public function getDataPemakaian($id_rs, $start_date, $end_date)
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
		if($id_rs != '')
			$this->db->where('b.id_rs', $id_rs);
		if($start_date != '' || $end_date != '')
			$this->db->where('DATE_FORMAT(a.tanggal_pemakaian, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
    	$query = $this->db->get();
		return $query->result_array();
	}

	public function get_transaksi_tabung($id_rs, $start_date, $end_date)
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
		if($id_rs != '')
			$this->db->where('b.id_rs', $id_rs);
		if($start_date != '' || $end_date != '')
		$this->db->where('DATE_FORMAT(a.tanggal_pemakaian, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
    	$query = $this->db->get();
		return $query->result_array();
	}

	public function get_stok_tabung($id_rs)
	{
		$this->db->select('a.id_kat_tabung, 
						a.id_rs, 
						SUM(a.total_stok_tabung) AS total_stok_tabung,
						a.id_kat_tabung,
						c.shortname, 
						e.nm_tabung
					');
		$this->db->join('ms_rs_rujukan c', 'a.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_tabung e', 'e.id_kat_tabung = a.id_kat_tabung', 'inner');
		if($id_rs != '')
			$this->db->where('a.id_rs', $id_rs);
		$this->db->group_by('a.id_rs');
		$this->db->group_by('a.id_kat_tabung');
		$query = $this->db->get('ta_stok_tabung a');
		return $query->result_array();
	}
}

// This is the end of model

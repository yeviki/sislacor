<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_lap_vaksin extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	// public function ReportKasus($regency_id, $start_date, $end_date)
	// {
	// 	// $arrDate = explode(' - ', $tanggal);
    //     $this->db->select('a.id_capaian_vaksin,
	// 							a.total_positif,
	// 							a.total_sembuh,
	// 							a.total_meninggal,
	// 							a.tanggal_kasus,
	// 							a.regency_id
	// 							');
	// 	$this->db->from('ta_kasus a');
	// 	if($regency_id != '')
	// 		$this->db->where('a.regency_id', $regency_id);
	// 	if($start_date != '' || $end_date != '')
	// 		// $this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"');
	// 		$this->db->where('DATE_FORMAT(a.tanggal_kasus, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
	// 	$this->db->order_by('a.id_capaian_vaksin ASC');

	// 	$query = $this->db->get();
    // 	return $query->result_array();
	// }

	public function getKategori()
	{
		$this->db->select('a.id_jenis_vaksin,
							a.nm_vaksin,
							');
		$this->db->from('ref_jenis_vaksin a');
    	$query = $this->db->get();
    	return $query->result_array();
	}

	public function getDataPemakaian($regency_id, $start_date, $end_date)
	{
		// $arrDate = explode(' - ', $tanggal);
		$this->db->select('a.tanggal_capaian,
							a.id_suplai_vaksin,
							a.total_vaksinasi,
							b.id_jenis_vaksin,
							b.regency_id,
							b.id_penyalur,
							c.name
							');
		$this->db->from('ta_capaian_vaksin a');
		$this->db->join('ta_suplai_vaksin b ','a.id_suplai_vaksin = b.id_suplai_vaksin','inner');
		$this->db->join('wa_regency c ','b.regency_id = c.id','inner');
		$this->db->ORDER_BY('a.id_suplai_vaksin ASC');
		$this->db->group_by(array('b.regency_id'));
		if($regency_id != '')
			$this->db->where('b.regency_id', $regency_id);
		if($start_date != '' || $end_date != '')
			$this->db->where('DATE_FORMAT(a.tanggal_capaian, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
    	$query = $this->db->get();
		return $query->result_array();
	}

	public function get_transaksi_vaksinasi($regency_id, $start_date, $end_date)
	{
		$this->db->select('a.tanggal_capaian,
							a.id_suplai_vaksin,
							a.total_vaksinasi,
							b.id_jenis_vaksin,
							b.regency_id,
							b.id_penyalur,
							c.name
							');
		$this->db->from('ta_capaian_vaksin a');
		$this->db->join('ta_suplai_vaksin b ','a.id_suplai_vaksin = b.id_suplai_vaksin','inner');
		$this->db->join('wa_regency c ','b.regency_id = c.id','inner');
		$this->db->ORDER_BY('a.id_suplai_vaksin ASC');
		$this->db->group_by(array('b.regency_id'));
		if($regency_id != '')
			$this->db->where('b.regency_id', $regency_id);
		if($start_date != '' || $end_date != '')
			$this->db->where('DATE_FORMAT(a.tanggal_capaian, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
    	$query = $this->db->get();
		return $query->result_array();
	}

	public function get_stok_vaksinasi($regency_id)
	{
		$this->db->select('a.id_jenis_vaksin, 
						a.regency_id, 
						SUM(a.total_suplai) AS total_suplai,
						a.id_jenis_vaksin,
						c.name, 
						e.nm_vaksin
					');
		$this->db->join('wa_regency c', 'a.regency_id = c.id', 'inner');
		$this->db->join('ref_jenis_vaksin e', 'e.id_jenis_vaksin = a.id_jenis_vaksin', 'inner');
		if($regency_id != '')
			$this->db->where('a.regency_id', $regency_id);
		$this->db->group_by('a.regency_id');
		$this->db->group_by('a.id_jenis_vaksin');
		$query = $this->db->get('ta_suplai_vaksin a');
		return $query->result_array();
	}
}

// This is the end of model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_lap_pengujian extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

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

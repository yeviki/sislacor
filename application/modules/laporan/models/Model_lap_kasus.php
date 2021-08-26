<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_lap_kasus extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function ReportKasus($regency_id, $start_date, $end_date)
	{
		// $arrDate = explode(' - ', $tanggal);
        $this->db->select('a.id_kasus,
								a.total_positif,
								a.total_sembuh,
								a.total_meninggal,
								a.tanggal_kasus,
								a.regency_id
								');
		$this->db->from('ta_kasus a');
		if($regency_id != '')
			$this->db->where('a.regency_id', $regency_id);
		if($start_date != '' || $end_date != '')
			// $this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"');
			$this->db->where('DATE_FORMAT(a.tanggal_kasus, "%m/%d/%Y") BETWEEN "'.$start_date.'"  AND "'.$end_date.'"');
		$this->db->order_by('a.id_kasus ASC');

		$query = $this->db->get();
    	return $query->result_array();
	}
}

// This is the end of model

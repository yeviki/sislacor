<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of program model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_kasus extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
    }

    public function get_TotalKasus()
	{
		$this->db->select('a.id_kasus,
                            a.tanggal_kasus,
							SUM(a.total_positif) AS total_p,
							SUM(a.total_sembuh) AS total_s,
							SUM(a.total_meninggal) AS total_m
                            ');
		$this->db->from('ta_kasus a');
		$query = $this->db->get();
		return $query->result_array();
	}

    public function get_PenambahanKasusHarian()
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_kasus,
                            a.tanggal_kasus,
							SUM(a.total_positif) AS total_p_now,
							SUM(a.total_sembuh) AS total_s_now,
							SUM(a.total_meninggal) AS total_m_now
                            ');
		$this->db->from('ta_kasus a');
        $this->db->where('a.tanggal_kasus', $date);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_KabKota()
	{
		$this->db->select('a.id,
                            a.province_id,
                            a.name
                            ');
		$this->db->from('wa_regency a');
        $this->db->where('province_id', '13');
		$query = $this->db->get();
		return $query->result_array();
	}

    public function get_KasusKabKota($id)
	{
		$this->db->select('a.id_kasus,
                            a.regency_id,
                            a.total_positif AS positif,
                            a.total_sembuh AS sembuh,
                            a.total_meninggal AS meninggal,
                            a.tanggal_kasus,
							SUM(a.total_positif) AS total_p,
							SUM(a.total_sembuh) AS total_s,
							SUM(a.total_meninggal) AS total_m
                            ');
		$this->db->from('ta_kasus a');
        $this->db->where('a.regency_id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_KasusKabKotaHarian($id)
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_kasus,
							a.tanggal_kasus,
							a.regency_id,
							SUM(a.total_positif) AS total_p_now,
							SUM(a.total_sembuh) AS total_s_now,
							SUM(a.total_meninggal) AS total_m_now,
							b.name
                            ');
		$this->db->from('ta_kasus a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->where('a.tanggal_kasus', $date);
        $this->db->where('a.regency_id', $id);
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_KasusKabKotaMingguan($id)
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_kasus,
							a.tanggal_kasus,
							a.regency_id,
							SUM(a.total_positif) AS total_p_week,
							SUM(a.total_sembuh) AS total_s_week,
							SUM(a.total_meninggal) AS total_m_week,
							b.name
                            ');
		$this->db->from('ta_kasus a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->where("WEEK(a.tanggal_kasus) = WEEK('".$date."')");
        $this->db->where('a.regency_id', $id);
		$this->db->group_by('a.regency_id');
		$query = $this->db->get();
		// echo $this->db->last_query();die;
		return $query->row_array();
	}

}

// This is the end of auth signin model

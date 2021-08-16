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

    public function get_KasusKabKota()
	{
		$this->db->select('a.id_kasus,
                            a.regency_id,
                            a.total_positif AS positif,
                            a.total_sembuh AS sembuh,
                            a.total_meninggal AS meninggal,
                            a.tanggal_kasus,
							SUM(a.total_positif) AS total_p,
							SUM(a.total_sembuh) AS total_s,
							SUM(a.total_meninggal) AS total_m,
                            b.name
                            ');
		$this->db->from('ta_kasus a');
        $this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_KasusKabKotaHarian()
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_kasus,
                            a.tanggal_kasus,
							SUM(a.total_positif) AS total_p_now,
							SUM(a.total_sembuh) AS total_s_now,
							SUM(a.total_meninggal) AS total_m_now,
							b.name
                            ');
		$this->db->from('ta_kasus a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->where('a.tanggal_kasus', $date);
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->result_array();
	}

}

// This is the end of auth signin model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of program model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_spesimen extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
    }

    public function get_TotalSpesimen()
	{
		$this->db->select('a.id_spesimen_sample,
                            a.tanggal_spesimen,
							SUM(a.total_spesimen) AS total_sp,
							SUM(a.total_pemeriksaan) AS total_pem
                            ');
		$this->db->from('ta_spesimen_sample a');
		$query = $this->db->get();
		return $query->result_array();
	}

    public function get_PenambahanSpesimenHarian()
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_spesimen_sample,
                            a.tanggal_spesimen,
							SUM(a.total_spesimen) AS total_now_spesimen,
							SUM(a.total_pemeriksaan) AS total_now_pemeriksaan
                            ');
		$this->db->from('ta_spesimen_sample a');
        $this->db->where('a.tanggal_spesimen', $date);
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

    public function get_SpesimenKabKota($id)
	{
		$this->db->select('a.id_spesimen_sample,
                            a.regency_id,
                            a.total_spesimen AS spesimen,
                            a.total_pemeriksaan AS pemeriksaan,
                            a.tanggal_spesimen,
							SUM(a.total_spesimen) AS total_sp,
							SUM(a.total_pemeriksaan) AS total_pem,
                            ');
		$this->db->from('ta_spesimen_sample a');
        $this->db->where('a.regency_id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_SpesimenKabKotaHarian($id)
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_spesimen_sample,
							a.tanggal_spesimen,
							a.regency_id,
							SUM(a.total_spesimen) AS total_now_spesimen,
							SUM(a.total_pemeriksaan) AS total_now_pemeriksaan,
							b.name
                            ');
		$this->db->from('ta_spesimen_sample a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->where('a.tanggal_spesimen', $date);
        $this->db->where('a.regency_id', $id);
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_SpesimenKabKotaMingguan($id)
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_spesimen_sample,
							a.tanggal_spesimen,
							a.regency_id,
							SUM(a.total_spesimen) AS total_spesimen_week,
							SUM(a.total_pemeriksaan) AS total_pemeriksaan_week,
                            b.name
                            ');
		$this->db->from('ta_spesimen_sample a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->where("WEEK(a.tanggal_spesimen) = WEEK('".$date."')");
        $this->db->where('a.regency_id', $id);
		$this->db->group_by('a.regency_id');
		$query = $this->db->get();
		// echo $this->db->last_query();die;
		return $query->row_array();
	}

}

// This is the end of auth signin model

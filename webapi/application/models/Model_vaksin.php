<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of program model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_vaksin extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
    }

    public function get_TotalVaksin()
	{
		$this->db->select('a.id_stok_masuk,
                            a.total_stok,
                            a.tanggal_masuk,
                            a.id_jenis_vaksin,
							SUM(a.total_stok) AS total_vaksin_masuk
                            ');
		$this->db->from('ta_vaksin_masuk a');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_TotalJenisVaksin()
	{
		$this->db->select('a.id_stok_masuk,
                            a.total_stok,
                            a.tanggal_masuk,
                            a.id_jenis_vaksin,
							SUM(a.total_stok) AS total_vaksin_per_jenis
                            ');
		$this->db->from('ta_vaksin_masuk a');
		$this->db->join('ref_jenis_vaksin b', 'a.id_jenis_vaksin = b.id_jenis_vaksin', 'inner');
        $this->db->group_by('a.id_jenis_vaksin');
		$query = $this->db->get();
		return $query->result_array();
	}

    public function get_SuplaiVaksinKabKota()
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_suplai_vaksin,
                            a.id_jenis_vaksin,
                            a.id_penyalur,
                            a.regency_id,
                            a.total_suplai,
                            a.tanggal_suplai,
							SUM(a.total_suplai) AS total_suplai_vaksin,
							b.name
                            ');
		$this->db->from('ta_suplai_vaksin a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_CapaianVaksinKabKota()
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_capaian_vaksin,
							a.id_suplai_vaksin,
							a.total_vaksinasi,
							a.tanggal_capaian,
                            b.id_jenis_vaksin,
                            b.id_penyalur,
                            b.regency_id,
                            b.total_suplai,
                            b.tanggal_suplai,
							SUM(a.total_vaksinasi) AS total_capaian_vaksin,
							SUM(b.total_suplai) AS total_suplai_vaksin,
							SUM(b.total_suplai - a.total_vaksinasi) AS total_stok,
							c.name
                            ');
		$this->db->from('ta_capaian_vaksin a');
		$this->db->join('ta_suplai_vaksin b', 'a.id_suplai_vaksin = b.id_suplai_vaksin', 'inner');
		$this->db->join('wa_regency c', 'b.regency_id = c.id', 'inner');
        $this->db->group_by('b.regency_id');
		$query = $this->db->get();
		return $query->result_array();
	}

}

// This is the end of auth signin model

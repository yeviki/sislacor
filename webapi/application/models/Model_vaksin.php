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
							SUM(a.total_stok) AS total_vaksin_per_jenis,
                            b.nm_vaksin
                            ');
		$this->db->from('ta_vaksin_masuk a');
		$this->db->join('ref_jenis_vaksin b', 'a.id_jenis_vaksin = b.id_jenis_vaksin', 'inner');
        $this->db->group_by('a.id_jenis_vaksin');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_TotalSuplaiVaksin($id_jenis_vaksin)
	{
		$this->db->select('a.id_suplai_vaksin,
                            a.total_suplai,
                            a.tanggal_suplai,
                            a.id_jenis_vaksin,
							SUM(a.total_suplai) AS total_distribusi,
                            b.nm_vaksin
                            ');
		$this->db->from('ta_suplai_vaksin a');
		$this->db->join('ref_jenis_vaksin b', 'a.id_jenis_vaksin = b.id_jenis_vaksin', 'inner');
        $this->db->where('a.id_jenis_vaksin', $id_jenis_vaksin);
        $this->db->group_by('a.id_jenis_vaksin');
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

	public function get_SuplaiVaksinKabKota($id)
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
		$this->db->where('a.regency_id', $id);
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_CapaianVaksinKabKota($id)
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
							(SELECT SUM(total_suplai) FROM ta_suplai_vaksin WHERE regency_id = '.$id.') - (SELECT SUM(total_vaksinasi) FROM ta_capaian_vaksin, ta_suplai_vaksin WHERE ta_capaian_vaksin.id_suplai_vaksin = ta_suplai_vaksin.id_suplai_vaksin AND ta_suplai_vaksin.regency_id = '.$id.') AS total_stok,
							c.name
                            ');
		$this->db->from('ta_capaian_vaksin a');
		$this->db->join('ta_suplai_vaksin b', 'a.id_suplai_vaksin = b.id_suplai_vaksin', 'inner');
		$this->db->join('wa_regency c', 'b.regency_id = c.id', 'inner');
		$this->db->where('b.regency_id', $id);
        $this->db->group_by('b.regency_id');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_StokJenisVaksin($id)
	{
		$this->db->select('DISTINCT(a.id_jenis_vaksin), a.regency_id,
                      ((SELECT SUM(b.total_suplai) FROM ta_suplai_vaksin b WHERE b.regency_id = a.regency_id AND b.id_jenis_vaksin = a.id_jenis_vaksin) -
                      IFNULL ((SELECT SUM(c.total_vaksinasi) FROM ta_capaian_vaksin c, ta_suplai_vaksin d WHERE c.id_suplai_vaksin = d.id_suplai_vaksin AND d.regency_id = a.regency_id AND d.id_jenis_vaksin = a.id_jenis_vaksin),0)) AS total_suplai,
                      a.id_suplai_vaksin,
                      a.id_jenis_vaksin,
                      a.id_penyalur,
                      a.tanggal_suplai,
                      a.regency_id,
                      e.nm_vaksin,
                      f.nm_penyalur
								');
		$this->db->from('ta_suplai_vaksin a');
		$this->db->join('ref_jenis_vaksin e', 'e.id_jenis_vaksin = a.id_jenis_vaksin', 'inner');
		$this->db->join('ref_penyalur f', 'f.id_penyalur = a.id_penyalur', 'inner');
		$this->db->where('a.regency_id', $id);
		$this->db->group_by('a.regency_id');
		$this->db->group_by('a.id_jenis_vaksin');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_JenisVaksin()
	{
		$this->db->select('a.id_jenis_vaksin,
                            a.nm_vaksin
                            ');
		$this->db->from('ref_jenis_vaksin a');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_VaksinasiKabKota($id)
	{
        $date  = gmdate('Y-m-d');
		$this->db->select('a.id_capaian_vaksinasi,
                            a.id_kat_dosis,
                            a.total_vaksinasi,
                            a.regency_id,
                            a.tanggal_vaksinasi,
							SUM(a.total_vaksinasi) AS total_vaksinasi_kab,
							b.name,
							c.nm_dosis
                            ');
		$this->db->from('ta_capaian_vaksinasi a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
		$this->db->join('ref_kat_dosis c', 'a.id_kat_dosis = c.id_kat_dosis', 'inner');
		$this->db->where('a.regency_id', $id);
        $this->db->group_by('a.regency_id');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_totVaksinasiPerDosis($id)
	{
		$query = $this->db->query('select b.id, ifnull((select x.total_vaksinasi from ta_capaian_vaksinasi x where x.id_kat_dosis=c.id_kat_dosis and x.regency_id=b.id),0) as total_dosis, c.nm_dosis from ref_kat_dosis c, wa_regency b, ta_capaian_vaksinasi a where b.province_id=13 and b.id="'.$id.'"
		GROUP by b.id, c.id_kat_dosis');
		return $query->result_array();
	}

}

// This is the end of auth signin model

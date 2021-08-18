<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of program model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_oksigen extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
    }

	public function get_RS()
	{
  $this->db->select('a.id_rs,
                          a.shortname
                          ');
  $this->db->from('ms_rs_rujukan a');
  $query = $this->db->get();
  return $query->result_array();
  }
    

  public function get_KatOksigen() {
      $this->db->select('id_kat_tabung,
                          nm_tabung
                          ');
  $this->db->from('ref_kat_tabung');
  $query = $this->db->get();
  return $query->result_array();
  }

  public function get_StokKatOksigen()
	{
		$this->db->select('DISTINCT(a.id_kat_tabung), 
                      SUM(a.total_stok_tabung) AS total_stok_tabung,
                      (SELECT sum(x.total_terpakai) FROM  ta_pemakaian_tabung x WHERE x.id_kat_tabung=a.id_kat_tabung) AS jml_digunakan,
                      IFNULL (((SUM(a.total_stok_tabung))-(SELECT sum(x.total_terpakai) FROM  ta_pemakaian_tabung x WHERE x.id_kat_tabung=a.id_kat_tabung)),SUM(a.total_stok_tabung)) AS sisa_tabung,
                      a.id_kat_tabung,
                      a.tanggal,
                      e.nm_tabung
					');
		$this->db->join('ref_kat_tabung e', 'e.id_kat_tabung = a.id_kat_tabung', 'inner');
		$this->db->group_by('a.id_kat_tabung');
		$query = $this->db->get('ta_stok_tabung a');
		return $query->result_array();
	}

  public function get_StokKatOksigenRS($id_rs)
	{
		$this->db->select('DISTINCT(a.id_kat_tabung), a.id_rs, 
						SUM(a.total_stok_tabung) AS total_stok_tabung,
						(SELECT sum(x.total_terpakai) FROM  ta_pemakaian_tabung x WHERE x.id_rs=a.id_rs AND x.id_kat_tabung=a.id_kat_tabung) AS jml_digunakan,
						IFNULL (((SUM(a.total_stok_tabung))-(SELECT sum(x.total_terpakai) FROM  ta_pemakaian_tabung x WHERE x.id_rs=a.id_rs AND x.id_kat_tabung=a.id_kat_tabung)),SUM(a.total_stok_tabung)) AS sisa_tabung,
						a.id_kat_tabung,
						a.tanggal,
						c.shortname, 
						e.nm_tabung
					');
		$this->db->join('ms_rs_rujukan c', 'a.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_tabung e', 'e.id_kat_tabung = a.id_kat_tabung', 'inner');
		$this->db->where('a.id_rs', $id_rs);
		$this->db->group_by('a.id_kat_tabung');
		$query = $this->db->get('ta_stok_tabung a');
		return $query->result_array();
  }
  
  public function get_total_kamar()
  {
    $this->db->select('
                      id_rs,
                      id_kat_kamar,
                      sum(total_kamar) as total_kamar
          ');
    $this->db->from('ta_rs_kamar');
		$this->db->group_by('id_rs');
		$this->db->group_by('id_kat_kamar');
		$query = $this->db->get();
		return $query->result_array();
  }
}

// This is the end of auth signin model

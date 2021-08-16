<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of program model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_kamar extends CI_Model
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
    

    public function get_KatKamar() {
        $this->db->select('id_kat_kamar,
                            nm_kamar
                            ');
		$this->db->from('ref_kat_kamar');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function get_StokKatKamar($id)
	{
		$this->db->select('DISTINCT(a.id_kat_kamar), a.id_rs,
                      ((SELECT SUM(b.total_kamar) FROM ta_rs_kamar b WHERE b.id_rs = a.id_rs AND b.id_kat_kamar = a.id_kat_kamar) - 
                      IFNULL ((SELECT SUM(c.total_terpakai) 
                      FROM ta_pemakaian_kamar c, ta_rs_kamar d 
                      WHERE c.id_rs_kamar=d.id_rs_kamar AND d.id_rs=a.id_rs ), 0)) AS total_kamar,
                      a.id_rs_kamar,
                      a.id_rs,
                      a.id_kat_kamar,
                      a.tanggal,
                      c.fullname,
                      c.shortname,
                      d.nm_kamar
								');
		$this->db->from('ta_rs_kamar a');
		$this->db->join('ms_rs_rujukan c', 'a.id_rs = c.id_rs', 'inner');
        $this->db->join('ref_kat_kamar d', 'd.id_kat_kamar = a.id_kat_kamar', 'inner');
        $this->db->where('a.id_rs', $id);
        $this->db->group_by('a.id_rs');
        $this->db->group_by('a.id_kat_kamar');
		$query = $this->db->get();
		return $query->result_array();
	}
}

// This is the end of auth signin model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of program model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_rekap extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getDataListForPublish()
  {
    $this->db->select('(CASE
											 	WHEN SUM(b.tot_otg) IS NULL THEN 0
												ELSE SUM(b.tot_otg)
											 END) AS otg_last,
											 (CASE
 											 	WHEN SUM(a.otg_baru) IS NULL THEN 0
 												ELSE SUM(a.otg_baru)
 											 END) AS otg_baru,
											 (CASE
 											 	WHEN (SUM(a.otg_to_odp) + SUM(a.otg_to_pdp) + SUM(a.otg_meninggal)) IS NULL THEN 0
 												ELSE (SUM(a.otg_to_odp) + SUM(a.otg_to_pdp) + SUM(a.otg_meninggal))
 											 END) AS otg_bs,
											 (CASE
 											 	WHEN SUM(a.otg_negatif) IS NULL THEN 0
 												ELSE SUM(a.otg_negatif)
 											 END) AS otg_sembuh,
											 (CASE
 											 	WHEN SUM(b.tot_odp) IS NULL THEN 0
 												ELSE SUM(b.tot_odp)
 											 END) AS odp_last,
											 (CASE
 											 	WHEN SUM(a.odp_baru) IS NULL THEN 0
 												ELSE SUM(a.odp_baru)
 											 END) AS odp_baru,
											 (CASE
 											 	WHEN (SUM(a.odp_to_pdp) + SUM(a.odp_meninggal)) IS NULL THEN 0
 												ELSE (SUM(a.odp_to_pdp) + SUM(a.odp_meninggal))
 											 END) AS odp_bs,
											 (CASE
 											 	WHEN SUM(a.odp_sembuh) IS NULL THEN 0
 												ELSE SUM(a.odp_sembuh)
 											 END) AS odp_sembuh,
											 (CASE
											 	WHEN SUM(b.tot_pdp) IS NULL THEN 0
												ELSE SUM(b.tot_pdp)
											 END) AS pdp_last,
											 (CASE
											 	WHEN SUM(a.pdp_baru) IS NULL THEN 0
												ELSE SUM(a.pdp_baru)
											 END) AS pdp_baru,
											 (CASE
											 	WHEN (SUM(a.pdp_meninggal) + SUM(a.pdp_to_positif)) IS NULL THEN 0
												ELSE (SUM(a.pdp_meninggal) + SUM(a.pdp_to_positif))
											 END) AS pdp_bs,
											 (CASE
											 	WHEN (SUM(a.pdp_sembuh) + SUM(a.pdp_negatif)) IS NULL THEN 0
												ELSE (SUM(a.pdp_sembuh) + SUM(a.pdp_negatif))
											 END) AS pdp_sembuh,
											 (CASE
											 	WHEN SUM(b.tot_positif) IS NULL THEN 0
												ELSE SUM(b.tot_positif)
											 END) AS positif_last,
											 (CASE
											 	WHEN SUM(a.positif_baru) IS NULL THEN 0
												ELSE SUM(a.positif_baru)
											 END) AS positif_baru,
											 (CASE
											 	WHEN SUM(a.positif_meninggal) IS NULL THEN 0
												ELSE SUM(a.positif_meninggal)
											 END) AS positif_meninggal,
											 (CASE
											 	WHEN SUM(a.positif_sembuh) IS NULL THEN 0
												ELSE SUM(a.positif_sembuh)
											 END) AS positif_sembuh');
    $this->db->from('ta_rekap_kasus a');
		$this->db->join('(SELECT
											  c.id_regency,
												(SUM(c.otg_baru) - (SUM(c.otg_negatif) + SUM(c.otg_to_odp) + SUM(c.otg_to_pdp) + SUM(c.otg_meninggal))) AS tot_otg,
												(SUM(c.odp_baru) - (SUM(c.odp_sembuh) + SUM(c.odp_to_pdp) + SUM(c.odp_meninggal))) AS tot_odp,
												(SUM(c.pdp_baru) - (SUM(c.pdp_sembuh) + SUM(c.pdp_meninggal) + SUM(c.pdp_negatif) + SUM(c.pdp_to_positif))) AS tot_pdp,
												(SUM(c.positif_baru) - (SUM(c.positif_sembuh) + SUM(c.positif_meninggal))) AS tot_positif
											FROM ta_rekap_kasus c
											WHERE c.publish_date < (SELECT MAX(publish_date) FROM ta_rekap_kasus)
											GROUP BY c.id_regency) b', 'a.id_regency = b.id_regency', 'left');
		$this->db->where('a.publish_date = (SELECT MAX(publish_date) FROM ta_rekap_kasus)', NULL, FALSE);
		$this->db->order_by('a.publish_date DESC');
		$this->db->limit(1);
    $query = $this->db->get();
    return $query->row_array();
  }

	public function getDataListFasyankes()
	{
		$this->db->select('a.kode_fasyankes,
											 a.fullname,
											 a.address,
											 a.phone,
											 b.name AS province,
											 c.name AS regency,
											 d.name AS district,
											 e.name AS village');
		$this->db->from('ms_rs_rujukan a');
		$this->db->join('wa_province b', 'b.id = a.id_province', 'inner');
		$this->db->join('wa_regency c', 'c.id = a.id_regency', 'inner');
		$this->db->join('wa_district d', 'd.id = a.id_district', 'inner');
		$this->db->join('wa_village e', 'e.id = a.id_village', 'inner');
		$this->db->where('a.flag', 1);
		$this->db->where('a.status', 1);
		$this->db->order_by('a.id_rs');
		$query = $this->db->get();
		return $query->result_array();
	}
}

// This is the end of auth signin model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_publish extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
		$datenow = (date('H:i:s') < waktu_input()) ? date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d')))) : date('Y-m-d');
		$this->_publishDate = date('Y-m-d H:i:s', strtotime($datenow.' '.waktu_publish()));
	}

  public function getDataListForPublish()
  {
    $this->db->select('a.id,
                       b.name_regency AS name,
											 (CASE
											 	WHEN f.tot_otg IS NULL THEN 0
												ELSE f.tot_otg
											 END) AS otg_last,
                       b.otg_baru,
                       (b.otg_to_odp + b.otg_to_pdp + b.otg_meninggal) AS otg_bs,
                       b.otg_negatif AS otg_sembuh,
											 (CASE
											 	WHEN f.tot_odp IS NULL THEN 0
												ELSE f.tot_odp
											 END) AS odp_last,
                       c.odp_baru,
                       (c.odp_to_pdp + c.odp_meninggal) AS odp_bs,
                       c.odp_sembuh,
											 (CASE
											 	WHEN f.tot_pdp IS NULL THEN 0
												ELSE f.tot_pdp
											 END) AS pdp_last,
                       d.pdp_baru,
                       (d.pdp_meninggal + d.pdp_to_positif) AS pdp_bs,
                       d.pdp_sembuh AS pdp_sembuh,
											 (CASE
											 	WHEN f.tot_positif IS NULL THEN 0
												ELSE f.tot_positif
											 END) AS positif_last,
                       e.positif_baru,
                       e.positif_meninggal,
                       e.positif_sembuh');
    $this->db->from('wa_regency a');
    $this->db->join('view_otg_publish_now b', 'a.id = b.id', 'inner');
    $this->db->join('view_odp_publish_now c', 'a.id = c.id', 'inner');
    $this->db->join('view_pdp_publish_now d', 'a.id = d.id', 'inner');
    $this->db->join('view_positif_publish_now e', 'a.id = e.id', 'inner');
		$this->db->join('(SELECT
											  id_regency,
												(SUM(otg_baru) - (SUM(otg_negatif) + SUM(otg_to_odp) + SUM(otg_to_pdp) + SUM(otg_meninggal))) AS tot_otg,
												(SUM(odp_baru) - (SUM(odp_sembuh) + SUM(odp_to_pdp) + SUM(odp_meninggal))) AS tot_odp,
												(SUM(pdp_baru) - (SUM(pdp_sembuh) + SUM(pdp_meninggal) + SUM(pdp_to_positif))) AS tot_pdp,
												(SUM(positif_baru) - (SUM(positif_sembuh) + SUM(positif_meninggal))) AS tot_positif
											FROM ta_rekap_kasus
											WHERE publish_date < "'.$this->_publishDate.'"
											GROUP BY id_regency) f', 'a.id = f.id_regency', 'left');
		$this->db->order_by('e.positif_baru DESC');
    $this->db->order_by('b.name_regency ASC');
    $query = $this->db->get();
    return $query->result_array();
  }

	public function getDataKasusAkumulasi()
	{
		$this->db->select('(CASE
											 	WHEN SUM(e.tot_otg) IS NULL THEN 0
												ELSE SUM(e.tot_otg)
											 END) AS otg_last,
                       SUM(a.otg_baru) AS otg_baru,
                       (SUM(a.otg_to_odp) + SUM(a.otg_to_pdp) + SUM(a.otg_meninggal) + SUM(a.otg_negatif)) AS otg_bs,
											 (CASE
											 	WHEN SUM(e.tot_odp) IS NULL THEN 0
												ELSE SUM(e.tot_odp)
											 END) AS odp_last,
                       SUM(b.odp_baru) AS odp_baru,
                       (SUM(b.odp_to_pdp) + SUM(b.odp_meninggal) + SUM(b.odp_sembuh)) AS odp_bs,
											 (CASE
											 	WHEN SUM(e.tot_pdp) IS NULL THEN 0
												ELSE SUM(e.tot_pdp)
											 END) AS pdp_last,
                       SUM(c.pdp_baru) AS pdp_baru,
                       (SUM(c.pdp_meninggal) + SUM(c.pdp_to_positif) + SUM(c.pdp_sembuh)) AS pdp_bs,
											 (CASE
											 	WHEN SUM(e.tot_positif) IS NULL THEN 0
												ELSE SUM(e.tot_positif)
											 END) AS positif_last,
                       SUM(d.positif_baru) AS positif_baru,
                       (SUM(d.positif_meninggal) + SUM(d.positif_sembuh)) AS positif_bs');
    $this->db->from('view_otg_publish_now a');
    $this->db->join('view_odp_publish_now b', 'a.id = b.id', 'inner');
    $this->db->join('view_pdp_publish_now c', 'a.id = c.id', 'inner');
    $this->db->join('view_positif_publish_now d', 'a.id = d.id', 'inner');
		$this->db->join('(SELECT
											  id_regency,
												(SUM(otg_baru) - (SUM(otg_negatif) + SUM(otg_to_odp) + SUM(otg_to_pdp) + SUM(otg_meninggal))) AS tot_otg,
												(SUM(odp_baru) - (SUM(odp_sembuh) + SUM(odp_to_pdp) + SUM(odp_meninggal))) AS tot_odp,
												(SUM(pdp_baru) - (SUM(pdp_sembuh) + SUM(pdp_meninggal) + SUM(pdp_to_positif))) AS tot_pdp,
												(SUM(positif_baru) - (SUM(positif_sembuh) + SUM(positif_meninggal))) AS tot_positif
											FROM ta_rekap_kasus
											WHERE publish_date < "'.$this->_publishDate.'"
											GROUP BY id_regency) e', 'a.id = e.id_regency', 'left');
    $query = $this->db->get();
    return $query->row_array();
	}

	private function getDataListKasus()
	{
		$this->db->select('a.id,
                       b.otg_baru,
											 b.otg_negatif,
                       b.otg_to_odp,
											 b.otg_to_pdp,
											 b.otg_meninggal,
                       c.odp_baru,
											 c.odp_sembuh,
                       c.odp_to_pdp,
											 c.odp_meninggal,
											 c.isolasi_dirumah,
											 c.isolasi_difasilitas,
                       d.pdp_baru,
											 d.pdp_sembuh,
                       d.pdp_meninggal,
											 d.pdp_to_positif,
                       e.positif_baru,
                       e.positif_meninggal,
                       e.positif_sembuh');
    $this->db->from('wa_regency a');
    $this->db->join('view_otg_publish_now b', 'a.id = b.id', 'inner');
    $this->db->join('view_odp_publish_now c', 'a.id = c.id', 'inner');
    $this->db->join('view_pdp_publish_now d', 'a.id = d.id', 'inner');
    $this->db->join('view_positif_publish_now e', 'a.id = e.id', 'inner');
		$this->db->order_by('a.id ASC');
    $query = $this->db->get();
    return $query->result_array();
	}

	public function approveDataKasus()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$jadwal       = 'pukul '.date('H:i', strtotime('-30 minutes', strtotime(waktu_publish()))).' s/d pukul '.date('H:i', strtotime(waktu_publish()));
		//load class notification
		$notif = new notification_manager();
		//cek jam publish
		if(date('H:i') >= date('H:i', strtotime('-30 minutes', strtotime(waktu_publish()))) AND date('H:i') <= date('H:i', strtotime(waktu_publish()))) {
			//cek data sudah dipublish atau belum
			$this->db->where('publish_date', $this->_publishDate);
			$qTot = $this->db->count_all_results('ta_rekap_kasus');
			if($qTot > 0)
				return array('message'=>'HAVEDATA', 'note'=>$this->_publishDate);
			else {
				//update data otg sesuai tgl publish
				$this->db->set('status', 'P');
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('status', 'A');
				$this->db->where('publish_date', $this->_publishDate);
				$this->db->update('ta_otg');
				//update data odp sesuai tgl publish
				$this->db->set('status', 'P');
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('status', 'A');
				$this->db->where('publish_date', $this->_publishDate);
				$this->db->update('ta_odp');
				//update data kasus covid
				$this->db->set('status', 'P');
				$this->db->set('mod_by', $create_by);
				$this->db->set('mod_date', $create_date);
				$this->db->set('mod_ip', $create_ip);
				$this->db->where('status', 'A');
				$this->db->where('publish_date', $this->_publishDate);
				$this->db->update('ta_pasien_status');
				//insert ke table rekap
				$dataKasus = $this->getDataListKasus();
				$arrKasus = array();
				foreach ($dataKasus as $key => $dk) {
					$arrKasus[] = array(
						'id_regency'					=> $dk['id'],
						'otg_baru'						=> $dk['otg_baru'],
						'otg_negatif'					=> $dk['otg_negatif'],
						'otg_to_odp'					=> $dk['otg_to_odp'],
						'otg_to_pdp'					=> $dk['otg_to_pdp'],
						'otg_meninggal'				=> $dk['otg_meninggal'],
						'odp_baru'						=> $dk['odp_baru'],
						'odp_sembuh'					=> $dk['odp_sembuh'],
						'odp_to_pdp'					=> $dk['odp_to_pdp'],
						'odp_meninggal'				=> $dk['odp_meninggal'],
						'isolasi_dirumah'			=> $dk['isolasi_dirumah'],
						'isolasi_difasilitas'	=> $dk['isolasi_difasilitas'],
						'pdp_baru'						=> $dk['pdp_baru'],
						'pdp_sembuh'					=> $dk['pdp_sembuh'],
						'pdp_meninggal'				=> $dk['pdp_meninggal'],
						'pdp_to_positif'			=> $dk['pdp_to_positif'],
						'positif_baru'				=> $dk['positif_baru'],
						'positif_sembuh'			=> $dk['positif_sembuh'],
						'positif_meninggal'		=> $dk['positif_meninggal'],
						'publish_date'				=> $this->_publishDate
					);
				}
				$this->db->insert_batch('ta_rekap_kasus', $arrKasus);
				//kirim notifikasi
				$params['sender_id'] 		= $create_by;
				$params['level_akses']	= array(2, 3);
				$params['type']					= 'data.new';
				$params['parameters']		= '';
				$params['reference']		=	'Rekap data per tanggal '.tgl_indo_time($this->_publishDate).' WIB telah dipublish';
				$params['create_by']		= $create_by;
				$params['create_date']	= $create_date;
				$params['create_ip']		= $create_ip;
				$notif->add($params);
				return array('message'=>'SUCCESS', 'note'=>$this->_publishDate);
			}
		} else {
			return array('message'=>'NOTIME', 'note'=>$jadwal);
		}
	}

}

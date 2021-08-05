<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model otg
 *
 * @author Yogi "solop" Kaputra
 */

class Model_otg extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
		$datenow = (date('H:i:s') > waktu_publish()) ? date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))) : date('Y-m-d');
		$this->_publishDate = date('Y-m-d H:i:s', strtotime($datenow.' '.waktu_publish()));
	}

	public function getDataKategoriOtg()
	{
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get('ref_kategori_otg');
		return $query->result_array();
	}

	public function cekDataOtgRegencyPerDay()
	{
		$this->db->where('publish_date', $this->_publishDate);
		$this->db->group_by(array('publish_date', 'id_regency'));
		$query = $this->db->count_all_results('ta_otg');
		return $query;
	}

  public function cekDataOtgPublish()
	{
		//set publish_date
		$this->db->where('publish_date', $this->_publishDate);
		if($this->app_loader->is_kesreg()) {
			$this->db->where('id_regency', $this->app_loader->current_regency());
		}
		$this->db->group_by(array('id_regency', 'publish_date'));
		$query = $this->db->count_all_results('ta_otg');
		return $query;
	}

	//set column search data target
	var $search = array('b.name');
	public function get_datatables($flag)
  {
    $this->_get_datatables_query($flag);
    if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered($flag)
  {
    $this->_get_datatables_query($flag);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($flag)
  {
		$status = ($flag == 2) ? array('P') : array('S', 'A');
		if($flag == 1)
			$this->db->where('publish_date', $this->_publishDate);
		else
			$this->db->where('publish_date <=', $this->_publishDate);
		$this->db->where_in('status', $status);
		if($this->app_loader->is_kesreg()) {
			$this->db->where('id_regency', $this->app_loader->current_regency());
		}
		$this->db->group_by(array('id_regency', 'publish_date'));
		return $this->db->count_all_results('ta_otg');
  }

  private function _get_datatables_query($flag)
  {
		$status = ($flag == 2) ? array('P') : array('S', 'A');
		$this->db->select('a.id_regency,
											 GROUP_CONCAT(CONCAT(c.title,": ",a.jumlah) ORDER BY a.id_kategori ASC SEPARATOR ", ") AS rekap,
											 a.status,
											 a.publish_date,
											 a.create_date,
											 IF(b.status = 1, CONCAT("KAB ", b.name), b.name) AS name');
		$this->db->from('ta_otg a');
		$this->db->join('wa_regency b', 'a.id_regency = b.id', 'inner');
		$this->db->join('ref_kategori_otg c', 'a.id_kategori = c.id', 'inner');
		if($flag == 1)
			$this->db->where('a.publish_date', $this->_publishDate);
		else
			$this->db->where('a.publish_date <=', $this->_publishDate);
		$this->db->where_in('a.status', $status);
		if($this->app_loader->is_kesreg()) {
			$this->db->where('a.id_regency', $this->app_loader->current_regency());
		}
		$i = 0;
    foreach ($this->search as $se) { // loop column
      if($_POST['search']['value']) { // if datatable send POST for search
        if($i===0) { // first loop
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($se, $_POST['search']['value']);
        } else {
          $this->db->or_like($se, $_POST['search']['value']);
        }
        if(count($this->search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
		$this->db->group_by(array('a.id_regency', 'a.publish_date'));
		$this->db->order_by('b.name ASC');
		$this->db->order_by('a.publish_date ASC');
  }

	public function getDataDetailOtg($id_regency, $publish_date, $flag)
	{
		$status = ($flag == 2) ? array('P') : array('S', 'A');
		$this->db->select('a.id,
											 a.id_regency,
											 a.id_kategori,
											 b.desc,
											 a.jumlah,
											 a.status,
											 a.create_date,
											 a.publish_date');
		$this->db->from('ta_otg a');
		$this->db->join('ref_kategori_otg b', 'a.id_kategori = b.id', 'inner');
		$this->db->where('a.id_regency', $id_regency);
		$this->db->where('a.publish_date', $publish_date);
		$this->db->where_in('a.status', $status);
		$this->db->order_by('a.id_kategori ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getDataDetailOtgNew()
	{
		$this->db->select('a.id,
											 a.id_regency,
											 a.id_kategori,
											 b.desc,
											 a.jumlah,
											 a.status,
											 a.create_date,
											 a.publish_date');
		$this->db->from('ta_otg a');
		$this->db->join('ref_kategori_otg b', 'a.id_kategori = b.id', 'inner');
		$this->db->where('a.id_regency', $this->app_loader->current_regency());
		$this->db->where('a.publish_date', $this->_publishDate);
		$this->db->where('a.status !=', 'P');
		$this->db->order_by('a.id_kategori ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertDataOtg()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$params       = escape($this->input->post('param', TRUE));
		$id_daerah    = ($this->app_loader->is_admin()) ? escape($this->input->post('regency', TRUE)) : $this->app_loader->current_regency();
		$publishDate  = $this->_publishDate;
		//load class notification
		$notif = new notification_manager();
		//cek data otg berdasarkan tgl publish
		$this->db->where('publish_date', $publishDate);
		$this->db->where('id_regency', $id_daerah);
		$qTot = $this->db->count_all_results('ta_otg');
		if($qTot > 0) {
			return array('message'=>'ERROR', 'note'=>regency($id_daerah));
		} else if($qTot <= 0 AND (date('Y-m-d', strtotime($publishDate)).' '.waktu_input() < date('Y-m-d H:i:s')) AND $this->app_loader->is_kesreg()) {
			return array('message'=>'NOTIME', 'note'=>regency($id_daerah));
		} else {
			$arrOtg = array();
			foreach ($params as $key => $v) {
				$arrOtg[] = array(
					'id_regency' 		=> $id_daerah,
					'id_kategori'	 	=> $key,
					'jumlah'		 		=> $v,
					'status'		 		=> 'S',
					'publish_date'	=> $publishDate,
					'create_by'			=> $create_by,
					'create_date'		=> $create_date,
					'create_ip'			=> $create_ip,
					'mod_by'				=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'				=> $create_ip
				);
			}
			$this->db->insert_batch('ta_otg', $arrOtg);
			//kirim notifikasi
			$params['sender_id'] 		= ($this->app_loader->is_admin()) ? $notif->getUserByRegency($id_daerah) : $create_by;
			$params['level_akses']	= array(2);
			$params['type']					= 'otg.new';
			$params['parameters']		= $id_daerah;
			$params['reference']		=	'Mengirim rekap data OTG per tanggal '.tgl_indo(date('Y-m-d', strtotime($publishDate)));
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			return array('message'=>'SUCCESS', 'note'=>regency($id_daerah));
		}
	}

	public function updateDataOtg()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$id_daerah    = $this->app_loader->current_regency();
		$id_otg				= $this->encryption->decrypt(escape($this->input->post('otgId', TRUE)));
		$publishDate  = $this->_publishDate;
		//load class notification
		$notif = new notification_manager();
		//cek data otg berdasarkan tgl publish
		$this->db->where('status !=', 'S');
		$this->db->where('publish_date', $publishDate);
		$this->db->where('id_regency', $id_daerah);
		$this->db->where('id', $id_otg);
		$qTot = $this->db->count_all_results('ta_otg');
		if($qTot > 0) {
			return array('message'=>'ERROR');
		} else {
			$data = array(
				'jumlah'	 => escape($this->input->post('total', TRUE)),
				'mod_by'	 => $create_by,
				'mod_date' => $create_date,
				'mod_ip'	 => $create_ip
			);
			$this->db->where('status', 'S');
			$this->db->where('publish_date', $publishDate);
			$this->db->where('id_regency', $id_daerah);
			$this->db->where('id', $id_otg);
			$this->db->update('ta_otg', $data);
			//kirim notifikasi
			$params['sender_id'] 		= $create_by;
			$params['level_akses']	= array(2);
			$params['type']					= 'otg.new';
			$params['parameters']		= $id_daerah;
			$params['reference']		=	'Memperbaharui rekap data OTG per tanggal '.tgl_indo(date('Y-m-d', strtotime($publishDate)));
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			return array('message'=>'SUCCESS');
		}
	}

	public function updateDataOtgAdmin()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$params       = escape($this->input->post('param', TRUE));
		$id_daerah		= $this->encryption->decrypt(escape($this->input->post('regencyId', TRUE)));
		$publishDate  = escape($this->input->post('publishDate', TRUE));
		//cek data otg berdasarkan tgl publish
		$this->db->where('status', 'P');
		$this->db->where('publish_date', $publishDate);
		$this->db->where('id_regency', $id_daerah);
		$qTot = $this->db->count_all_results('ta_otg');
		if($qTot > 0) {
			return array('message'=>'ERROR', 'note'=>regency($id_daerah));
		} else {
			foreach ($params as $key => $v) {
				$data = array(
					'jumlah'	 => $v,
					'mod_by'	 => $create_by,
					'mod_date' => $create_date,
					'mod_ip'	 => $create_ip
				);
				$this->db->where('status !=', 'P');
				$this->db->where('id_regency', $id_daerah);
				$this->db->where('id_kategori', $key);
				$this->db->where('publish_date', $publishDate);
				$this->db->update('ta_otg', $data);
			}
			return array('message'=>'SUCCESS', 'note'=>regency($id_daerah));
		}
	}

	public function getDataAkumulasiOtg($today=FALSE)
	{
		$this->db->select('id_kategori as key_column,
											 SUM(jumlah) as jumlah');
		$this->db->where('status !=', 'S');
		if($this->app_loader->is_kesreg()) {
			$this->db->where('id_regency', $this->app_loader->current_regency());
		}
		if($today == TRUE) {
			$this->db->where('publish_date', $this->_publishDate);
		} else {
			$this->db->where('publish_date <=', $this->_publishDate);
		}
		$this->db->group_by(array('id_kategori'));
		$this->db->order_by('id_kategori ASC');
		$query = $this->db->get('ta_otg');
		return $query->result_array();
	}
}

// This is the end of auth signin model

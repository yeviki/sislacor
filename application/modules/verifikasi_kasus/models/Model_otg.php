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

	public function cekDataOtgRegencyPerDay()
	{
		$this->db->where('publish_date', $this->_publishDate);
		$this->db->group_by(array('publish_date', 'id_regency'));
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
											 b.name');
		$this->db->from('ta_otg a');
		$this->db->join('wa_regency b', 'a.id_regency = b.id', 'inner');
		$this->db->join('ref_kategori_otg c', 'a.id_kategori = c.id', 'inner');
		if($flag == 1)
			$this->db->where('a.publish_date', $this->_publishDate);
		else
			$this->db->where('a.publish_date <=', $this->_publishDate);
		$this->db->where_in('a.status', $status);
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
		$this->db->order_by('b.status ASC');
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

	public function approveDataOtg()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		$id_daerah    = $this->encryption->decrypt(escape($this->input->post('regencyId', TRUE)));
		$publishDate  = escape($this->input->post('publishDate', TRUE));
		//load class notification
		$notif = new notification_manager();
		//cek data otg
		$this->db->where('id_regency', $id_daerah);
		$this->db->where('publish_date', $publishDate);
		$this->db->where('status', 'S');
		$dataOtg = $this->db->get('ta_otg')->result_array();
		if(count($dataOtg) <= 0)
			return array('message'=>'ERROR', 'note'=>regency($id_daerah));
		else {
			$this->db->set('status', 'A');
			$this->db->set('mod_by', $create_by);
			$this->db->set('mod_date', $create_date);
			$this->db->set('mod_ip', $create_ip);
			$this->db->where('id_regency', $id_daerah);
			$this->db->where('publish_date', $publishDate);
			$this->db->where('status', 'S');
			$this->db->update('ta_otg');
			//kirim notifikasi
			$params['sender_id'] 		= $create_by;
			$params['recipient_id']	= !empty($dataOtg) ? $dataOtg[0]['create_by'] : $notif->getUserByRegency($id_daerah);
			$params['type']					= 'otg.verified';
			$params['parameters']		= $id_daerah;
			$params['reference']		=	'Rekap data OTG per tanggal '.tgl_indo(date('Y-m-d', strtotime($publishDate))).' telah kami verifikasi';
			$params['create_by']		= $create_by;
			$params['create_date']	= $create_date;
			$params['create_ip']		= $create_ip;
			$notif->add($params);
			//kirim ke gugus tugas
			$paramNew['sender_id'] 		= $create_by;
			$paramNew['level_akses']	= array(6);
			$paramNew['type']					= 'data.new';
			$paramNew['parameters']		= $id_daerah;
			$paramNew['reference']		=	regency($id_daerah).' telah melaporkan data terbaru rekap OTG per tanggal '.tgl_indo(date('Y-m-d', strtotime($publishDate)));
			$paramNew['create_by']		= $create_by;
			$paramNew['create_date']	= $create_date;
			$paramNew['create_ip']		= $create_ip;
			$notif->add($paramNew);
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

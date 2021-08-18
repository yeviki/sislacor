<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_rs_kamar extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function getDataKategoriKamar()
	{
		$this->db->order_by('id_kat_kamar', 'ASC');
		$query = $this->db->get('ref_kat_kamar');
		return $query->result_array();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal', 'id_rs');
	public function get_datatables($param)
	{
		$this->_get_datatables_query($param);
		if($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

		public function count_filtered($param)
	{
		$this->_get_datatables_query($param);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->group_by(array('a.id_rs', 'a.tanggal'));
		return $this->db->count_all_results('ta_rs_kamar a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_rs,
						   a.tanggal,
						   b.shortname');
		$this->db->from('ta_rs_kamar a');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
	
		// id_rs
		if(isset($post['id_rs']) AND $post['id_rs'] != '')
			$this->db->where('a.id_rs', $post['id_rs']);
		// tanggal Masuk
        if(isset($post['tanggal_kamar']) AND $post['tanggal_kamar'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y")', $post['tanggal_kamar']);
		}
		
		$i = 0;
		foreach ($this->search as $item) { // loop column
			if($_POST['search']['value']) { // if datatable send POST for search
				if($i===0) { // first loop
				$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
				$this->db->like($item, $_POST['search']['value']);
				} else {
				$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->search) - 1 == $i) //last loop
				$this->db->group_end(); //close bracket
			}
		$i++;
		}
		$this->db->group_by(array('a.id_rs', 'a.tanggal'));
		$this->db->order_by('a.tanggal DESC');
		$this->db->order_by('a.id_rs ASC');
  	}

	public function getDataDetail($id_rs, $tanggal)
	{
		$this->db->select('a.id_rs_kamar,
							a.total_kamar,
							a.id_rs,
							a.id_kat_kamar,
							a.tanggal,
							b.shortname,
							c.nm_kamar
								');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		$this->db->join('ref_kat_kamar c', 'a.id_kat_kamar = c.id_kat_kamar', 'inner');
		$this->db->where('a.id_rs', $id_rs);
		$this->db->where('a.tanggal', $tanggal);

		$query = $this->db->get('ta_rs_kamar a');
		// echo $this->db->last_query(); die;
		return $query->result_array();
	}

	public function getTotalKamar($date, $rs, $kamar){
		$this->db->select('a.id_rs,
						   a.tanggal,
						   a.id_kat_kamar,
						   a.total_kamar');
		$this->db->from('ta_rs_kamar a');
		$this->db->where('a.tanggal', $date);
		$this->db->where('a.id_rs', $rs);
		$this->db->where('a.id_kat_kamar', $kamar);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$params      	 	= escape($this->input->post('param', TRUE));
		$id_rs       		= escape($this->input->post('id_rs', TRUE));
		$tanggal       		= date_convert(escape($this->input->post('tanggal', TRUE)));

		$this->db->where('id_rs', $id_rs);
		$this->db->where('tanggal', $tanggal);
		$qTot = $this->db->count_all_results('ta_rs_kamar');
		if($qTot > 0)
			return array('message'=>'ERROR');
		else {
			$arrKamar = array();
			foreach ($params as $key => $v) {
				$arrKamar[] = array(
					'id_rs' 			=> $id_rs,
					'tanggal' 			=> $tanggal,
					'id_kat_kamar'	 	=> $key,
					'total_kamar'		=> $v,
					'create_by'	 		=> $create_by,
					'create_date' 		=> $create_date,
					'create_ip'	 		=> $create_ip,
					'mod_by'	 		=> $create_by,
					'mod_date' 			=> $create_date,
					'mod_ip'	 		=> $create_ip
				);
			}
			$this->db->insert_batch('ta_rs_kamar', $arrKamar);
			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
		}
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$params       		= escape($this->input->post('param', TRUE));
		$id_rs				= $this->encryption->decrypt(escape($this->input->post('rsId', TRUE)));
		$publishDate  		= escape($this->input->post('publishDate', TRUE));
		//cek data
		$dataKamar 	    	= $this->getDataDetail($id_rs, $publishDate);
		if(count($dataKamar) <= 0)
			return array('message'=>'ERROR');
		else {
			foreach ($params as $key => $v) {
				$data = array(
					'total_kamar'	 	=> $v,
					'tanggal'	 		=> date_convert(escape($this->input->post('tanggal', TRUE))),
					'mod_by'	 		=> $create_by,
					'mod_date' 	 		=> $create_date,
					'mod_ip'	 		=> $create_ip
				);
				$this->db->where('id_rs', $id_rs);
				$this->db->where('id_kat_kamar', $key);
				$this->db->where('tanggal', $publishDate);
				$this->db->update('ta_rs_kamar', $data);
			}
			return array('message'=>'SUCCESS');
		}
	}

	public function deleteData()
	{
		$id_rs_kamar	= $this->encryption->decrypt(escape($this->input->post('rsId', TRUE)));
		$tanggal		= $this->input->post('publishDate', TRUE);
		foreach ($id_rs_kamar as $id) {
			$this->db->where('id_rs', $id);
			$this->db->where('tanggal', $tanggal);
			$this->db->delete('ta_rs_kamar');
		}
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

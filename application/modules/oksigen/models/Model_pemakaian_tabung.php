<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_pemakaian_tabung extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_terpakai', 'Total Terpakai', 'required|trim');
		$this->form_validation->set_rules('id_stok_tabung', 'Kategori Tabung', 'required|trim');
		$this->form_validation->set_rules('tanggal_pemakaian', 'Tanggal pemakaian', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal_pemakaian', 'id_stok_tabung');
	public function get_datatables($param)
	{
		$this->_get_datatables_query($param);
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
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
		$this->db->select('a.id_pemakaian_tabung,
								a.total_terpakai,
								a.id_stok_tabung,
								a.tanggal_pemakaian,
								b.id_rs,
								b.id_kat_tabung,
								b.total_stok_tabung,
								c.fullname,
								d.nm_tabung
								');
		$this->db->join('ta_stok_tabung b', 'a.id_stok_tabung = b.id_stok_tabung', 'inner');
		$this->db->join('ms_rs_rujukan c', 'b.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_tabung d', 'b.id_kat_tabung = d.id_kat_tabung', 'inner');
		return $this->db->count_all_results('ta_pemakaian_tabung a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_pemakaian_tabung,
								a.total_terpakai,
								a.id_stok_tabung,
								a.tanggal_pemakaian,
								b.id_rs,
								b.id_kat_tabung,
								b.total_stok_tabung,
								c.fullname,
								d.nm_tabung
								');
        $this->db->from('ta_pemakaian_tabung a');
		$this->db->join('ta_stok_tabung b', 'a.id_stok_tabung = b.id_stok_tabung', 'inner');
		$this->db->join('ms_rs_rujukan c', 'b.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_tabung d', 'b.id_kat_tabung = d.id_kat_tabung', 'inner');
		
		// RS
		if(isset($post['id_rs']) AND $post['id_rs'] != '')
			$this->db->where('b.id_rs', $post['id_rs']);
        // Kamar
		if(isset($post['id_tabung']) AND $post['id_tabung'] != '')
        $this->db->where('b.id_kat_tabung', $post['id_tabung']);
		// Tanggal Pemakaian
        if(isset($post['pemakaian']) AND $post['pemakaian'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal_pemakaian, "%m/%d/%Y")', $post['pemakaian']);
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
		$this->db->order_by('a.tanggal_pemakaian DESC');
		$this->db->order_by('a.id_pemakaian_tabung DESC');
  	}

	public function getDataDetail($id_pemakaian_tabung)
	{
		$this->db->where('id_pemakaian_tabung', $id_pemakaian_tabung);
		$query = $this->db->get('ta_pemakaian_tabung');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal_pemakaian	= date_convert(escape($this->input->post('tanggal_pemakaian', TRUE)));

			$data = array(
				'tanggal_pemakaian'			=> $tanggal_pemakaian,
				'total_terpakai'		    => escape($this->input->post('total_terpakai', TRUE)),
				'id_stok_tabung'		    => escape($this->input->post('id_stok_tabung', TRUE))
			);
			$this->db->insert('ta_pemakaian_tabung', $data);
			return array('message'=>'SUCCESS', 'tanggal_pemakaian'=>$tanggal_pemakaian);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_pemakaian_tabung	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_pemakaian_tabung);
		$tanggal_pemakaian  = !empty($dataVaksin) ? $dataVaksin['tanggal_pemakaian'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal_pemakaian'=>$tanggal_pemakaian);
		else {
				$data = array(
					'tanggal_pemakaian'		=> date_convert(escape($this->input->post('tanggal_pemakaian', TRUE))),
					'total_terpakai'		=> escape($this->input->post('total_terpakai', TRUE)),
					'id_stok_tabung'		=> escape($this->input->post('id_stok_tabung', TRUE))
				);
			$this->db->where('id_pemakaian_tabung', $id_pemakaian_tabung);
			$this->db->update('ta_pemakaian_tabung', $data);
			return array('message'=>'SUCCESS', 'tanggal_pemakaian'=>$tanggal_pemakaian);
		}
	}

	public function deleteData()
	{
		$id_pemakaian_tabung	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_pemakaian_tabung', $id_pemakaian_tabung);
		$this->db->delete('ta_pemakaian_tabung');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

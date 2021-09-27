<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model sample
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_pemasok extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('nm_penyalur', 'Total Spesimen', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('nm_penyalur');
	public function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->select('a.id_penyalur,
								a.nm_penyalur,
								');
		return $this->db->count_all_results('ref_penyalur a');
	}

	private function _get_datatables_query()
	{
		$this->db->select('a.id_penyalur,
								a.nm_penyalur
								');
		$this->db->from('ref_penyalur a');
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
		$this->db->order_by('a.id_penyalur DESC');
  	}

	public function getDataDetail($id_penyalur)
	{
		$this->db->where('id_penyalur', $id_penyalur);
		$query = $this->db->get('ref_penyalur');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

			$data = array(
				'nm_penyalur'		    => escape($this->input->post('nm_penyalur', TRUE))
			);
			$this->db->insert('ref_penyalur', $data);
			return array('message'=>'SUCCESS',);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_penyalur		= $this->encryption->decrypt(escape($this->input->post('pemasokId', TRUE)));
		//cek data
		$dataD 	    = $this->getDataDetail($id_penyalur);
		if(count($dataD) <= 0)
			return array('message'=>'ERROR');
		else {
				$data = array(
					'nm_penyalur'		=> escape($this->input->post('nm_penyalur', TRUE))
				);
			$this->db->where('id_penyalur', $id_penyalur);
			$this->db->update('ref_penyalur', $data);
			return array('message'=>'SUCCESS');
		}
	}

	public function deleteData()
	{
		$id_penyalur	= $this->encryption->decrypt(escape($this->input->post('pemasokId', TRUE)));
		$this->db->where('id_penyalur', $id_penyalur);
		$this->db->delete('ref_penyalur');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

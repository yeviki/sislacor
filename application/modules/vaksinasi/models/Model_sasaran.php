<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model suplai capaian vaksin
 *
 * @author Yogi "solop" Kaputra
 */

class Model_sasaran extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_sasaran', 'Total Sasaran', 'required|trim');
		$this->form_validation->set_rules('kabkota', 'Kab/Kota', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('kabkota');
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
		$this->db->select('a.id_sasaran,
								a.total_sasaran,
								a.regency_id,
                                b.name
								');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
		return $this->db->count_all_results('ta_sasaran a');
	}

	private function _get_datatables_query()
	{
		$this->db->select('a.id_sasaran,
							a.total_sasaran,
							a.regency_id,
							b.name
								');
		$this->db->from('ta_sasaran a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
		
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
		$this->db->order_by('a.id_sasaran DESC');
  	}

	public function getDataDetail($id_sasaran)
	{
		$this->db->select('a.id_sasaran,
							a.total_sasaran,
							a.regency_id,
							b.name
						');
		$this->db->from('ta_sasaran a');
		$this->db->join('wa_regency b', 'a.regency_id = b.id', 'inner');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$tahun 				= gmdate('Y');
		$create_ip    		= $this->input->ip_address();

			$data = array(
				'total_sasaran'		=> escape($this->input->post('total_sasaran', TRUE)),
				'regency_id'		=> escape($this->input->post('kabkota', TRUE)),
				'tahun'				=> $tahun,
				'create_by'			=> $create_by,
				'create_date'		=> $create_date,
				'create_ip'			=> $create_ip,
				'mod_by'			=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'			=> $create_ip
			);
			$this->db->insert('ta_sasaran', $data);
			return array('message'=>'SUCCESS');
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_sasaran			= $this->encryption->decrypt(escape($this->input->post('sasaranId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_sasaran);
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR');
		else {
				$data = array(
					'total_sasaran'		=> escape($this->input->post('total_sasaran', TRUE)),
					'regency_id'		=> escape($this->input->post('kabkota', TRUE)),
					'mod_by'			=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'			=> $create_ip
				);
			$this->db->where('id_sasaran', $id_sasaran);
			$this->db->update('ta_sasaran', $data);
			return array('message'=>'SUCCESS');
		}
	}

	public function deleteData()
	{
		$id_sasaran	= $this->encryption->decrypt(escape($this->input->post('sasaranId', TRUE)));
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->delete('ta_sasaran');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

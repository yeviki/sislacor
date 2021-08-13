<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model sample
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_sample extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_spesimen', 'Total Spesimen', 'required|trim');
		$this->form_validation->set_rules('total_pemeriksaan', 'Total Pemeriksaan', 'required|trim');
		$this->form_validation->set_rules('regency_id', 'Kab/Kota', 'required|trim');
		$this->form_validation->set_rules('tanggal_spesimen', 'Tanggal Spesimen', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal', 'regency_id');
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
		$this->db->select('a.id_spesimen_sample,
								a.total_spesimen,
								a.total_pemeriksaan,
								a.regency_id,
								a.tanggal_spesimen,
								a.regency_id
								');
		return $this->db->count_all_results('ta_spesimen_sample a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_spesimen_sample,
								a.total_spesimen,
								a.total_pemeriksaan,
								a.regency_id,
								a.tanggal_spesimen,
								a.regency_id
								');
		$this->db->from('ta_spesimen_sample a');
		
		// Kab/Kota
		if(isset($post['regency']) AND $post['regency'] != '')
			$this->db->where('a.regency_id', $post['regency']);
		// Tanggal
        if(isset($post['tanggal']) AND $post['tanggal'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal_spesimen, "%m/%d/%Y")', $post['tanggal']);
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
		$this->db->order_by('a.tanggal_spesimen DESC');
		$this->db->order_by('a.id_spesimen_sample DESC');
  	}

	public function getDataDetail($id_spesimen_sample)
	{
		$this->db->where('id_spesimen_sample', $id_spesimen_sample);
		$query = $this->db->get('ta_spesimen_sample');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal_spesimen	= date_convert(escape($this->input->post('tanggal_spesimen', TRUE)));

			$data = array(
				'tanggal_spesimen'		=> $tanggal_spesimen,
				'total_spesimen'		=> escape($this->input->post('total_spesimen', TRUE)),
				'total_pemeriksaan'		=> escape($this->input->post('total_pemeriksaan', TRUE)),
				'regency_id'			=> escape($this->input->post('regency_id', TRUE)),
				'regency_id'			=> escape($this->input->post('regency_id', TRUE))
			);
			$this->db->insert('ta_spesimen_sample', $data);
			return array('message'=>'SUCCESS', 'tanggal_spesimen'=>$tanggal_spesimen);
	}

	public function updateData()
	{
		$create_by    			= $this->app_loader->current_account();
		$create_date 			= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    			= $this->input->ip_address();
		$id_spesimen_sample		= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataD 	    = $this->getDataDetail($id_spesimen_sample);
		$tanggal_spesimen  = !empty($dataD) ? $dataD['tanggal_spesimen'] : '';
		if(count($dataD) <= 0)
			return array('message'=>'ERROR', 'tanggal_spesimen'=>$tanggal_spesimen);
		else {
				$data = array(
					'tanggal_spesimen'		=> date_convert(escape($this->input->post('tanggal_spesimen', TRUE))),
					'total_spesimen'		=> escape($this->input->post('total_spesimen', TRUE)),
					'total_pemeriksaan'		=> escape($this->input->post('total_pemeriksaan', TRUE)),
					'regency_id'			=> escape($this->input->post('regency_id', TRUE))
				);
			$this->db->where('id_spesimen_sample', $id_spesimen_sample);
			$this->db->update('ta_spesimen_sample', $data);
			return array('message'=>'SUCCESS', 'tanggal_spesimen'=>$tanggal_spesimen);
		}
	}

	public function deleteData()
	{
		$id_spesimen_sample	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_spesimen_sample', $id_spesimen_sample);
		$this->db->delete('ta_spesimen_sample');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

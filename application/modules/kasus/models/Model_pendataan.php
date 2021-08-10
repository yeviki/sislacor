<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model pendataan
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_pendataan extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_positif', 'Total Positif', 'required|trim');
		$this->form_validation->set_rules('total_sembuh', 'Total Sembuh', 'required|trim');
		$this->form_validation->set_rules('total_meninggal', 'Total Meninggal', 'required|trim');
		$this->form_validation->set_rules('tanggal_kasus', 'Tanggal Kasus', 'required|trim');
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
		$this->db->select('a.id_kasus,
								a.total_positif,
								a.total_sembuh,
								a.total_meninggal,
								a.tanggal_kasus,
								a.regency_id
								');
		return $this->db->count_all_results('ta_kasus a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_kasus,
								a.total_positif,
								a.total_sembuh,
								a.total_meninggal,
								a.tanggal_kasus,
								a.regency_id
								');
		$this->db->from('ta_kasus a');
		
		// Kab/Kota
		if(isset($post['regency_id']) AND $post['regency_id'] != '')
			$this->db->where('a.regency_id', $post['regency_id']);
		// Tanggal kasus Masuk
        if(isset($post['tanggal']) AND $post['tanggal'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal_kasus, "%m/%d/%Y")', $post['tanggal']);
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
		$this->db->order_by('a.tanggal_kasus DESC');
		$this->db->order_by('a.id_kasus DESC');
  	}

	public function getDataDetail($id_kasus)
	{
		$this->db->where('id_kasus', $id_kasus);
		$query = $this->db->get('ta_kasus');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal_kasus	= date_convert(escape($this->input->post('tanggal_kasus', TRUE)));

			$data = array(
				'tanggal_kasus'			=> $tanggal_kasus,
				'total_positif'			=> escape($this->input->post('total_positif', TRUE)),
				'total_sembuh'			=> escape($this->input->post('total_sembuh', TRUE)),
				'total_meninggal'		=> escape($this->input->post('total_meninggal', TRUE)),
				'regency_id'			=> escape($this->input->post('regency_id', TRUE))
			);
			$this->db->insert('ta_kasus', $data);
			return array('message'=>'SUCCESS', 'tanggal_kasus'=>$tanggal_kasus);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_kasus		= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataD 	    = $this->getDataDetail($id_kasus);
		$tanggal_kasus  = !empty($dataD) ? $dataD['tanggal_kasus'] : '';
		if(count($dataD) <= 0)
			return array('message'=>'ERROR', 'tanggal_kasus'=>$tanggal_kasus);
		else {
				$data = array(
					'tanggal_kasus'			=> date_convert(escape($this->input->post('tanggal_kasus', TRUE))),
					'total_positif'			=> escape($this->input->post('total_positif', TRUE)),
					'total_sembuh'			=> escape($this->input->post('total_sembuh', TRUE)),
					'total_meninggal'		=> escape($this->input->post('total_meninggal', TRUE)),
					'regency_id'			=> escape($this->input->post('regency_id', TRUE))
				);
			$this->db->where('id_kasus', $id_kasus);
			$this->db->update('ta_kasus', $data);
			return array('message'=>'SUCCESS', 'tanggal_kasus'=>$tanggal_kasus);
		}
	}

	public function deleteData()
	{
		$id_kasus	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_kasus', $id_kasus);
		$this->db->delete('ta_kasus');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

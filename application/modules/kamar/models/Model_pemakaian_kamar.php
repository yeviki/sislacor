<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_pemakaian_kamar extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_terpakai', 'Total Terpakai', 'required|trim');
		$this->form_validation->set_rules('id_rs_kamar', 'Kategori Kamar', 'required|trim');
		$this->form_validation->set_rules('tanggal_pemakaian', 'Tanggal_pemakaian', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal_pemakaian', 'id_rs_kamar');
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
		$this->db->select('a.id_pemakaian_kamar,
								a.total_terpakai,
								a.id_rs_kamar,
								a.tanggal_pemakaian,
								b.id_rs,
								b.id_kat_kamar,
								b.total_kamar,
								c.fullname,
								d.nm_kamar
								');
		$this->db->join('ta_rs_kamar b', 'a.id_rs_kamar = b.id_rs_kamar', 'inner');
		$this->db->join('ms_rs_rujukan c', 'b.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_kamar d', 'b.id_kat_kamar = d.id_kat_kamar', 'inner');
		return $this->db->count_all_results('ta_pemakaian_kamar a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_pemakaian_kamar,
								a.total_terpakai,
								a.id_rs_kamar,
								a.tanggal_pemakaian,
								b.id_rs,
								b.id_kat_kamar,
								b.total_kamar,
								c.fullname,
								d.nm_kamar
								');
        $this->db->from('ta_pemakaian_kamar a');
		$this->db->join('ta_rs_kamar b', 'a.id_rs_kamar = b.id_rs_kamar', 'inner');
		$this->db->join('ms_rs_rujukan c', 'b.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_kamar d', 'b.id_kat_kamar = d.id_kat_kamar', 'inner');
		
		// RS
		if(isset($post['id_rs']) AND $post['id_rs'] != '')
			$this->db->where('b.id_rs', $post['id_rs']);
        // Kamar
		if(isset($post['id_kamar']) AND $post['id_kamar'] != '')
        $this->db->where('b.id_kat_kamar', $post['id_kamar']);
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
		$this->db->order_by('a.id_pemakaian_kamar DESC');
  	}

	public function getDataDetail($id_pemakaian_kamar)
	{
		$this->db->where('id_pemakaian_kamar', $id_pemakaian_kamar);
		$query = $this->db->get('ta_pemakaian_kamar');
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
				'id_rs_kamar'		        => escape($this->input->post('id_rs_kamar', TRUE))
			);
			$this->db->insert('ta_pemakaian_kamar', $data);
			return array('message'=>'SUCCESS', 'tanggal_pemakaian'=>$tanggal_pemakaian);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_pemakaian_kamar	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_pemakaian_kamar);
		$tanggal_pemakaian  			= !empty($dataVaksin) ? $dataVaksin['tanggal_pemakaian'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal_pemakaian'=>$tanggal_pemakaian);
		else {
				$data = array(
					'tanggal_pemakaian'		=> date_convert(escape($this->input->post('tanggal_pemakaian', TRUE))),
					'total_terpakai'		=> escape($this->input->post('total_terpakai', TRUE)),
					'id_rs_kamar'		    => escape($this->input->post('id_rs_kamar', TRUE))
				);
			$this->db->where('id_pemakaian_kamar', $id_pemakaian_kamar);
			$this->db->update('ta_pemakaian_kamar', $data);
			return array('message'=>'SUCCESS', 'tanggal_pemakaian'=>$tanggal_pemakaian);
		}
	}

	public function deleteData()
	{
		$id_pemakaian_kamar	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_pemakaian_kamar', $id_pemakaian_kamar);
		$this->db->delete('ta_pemakaian_kamar');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

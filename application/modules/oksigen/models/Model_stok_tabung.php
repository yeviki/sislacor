<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_stok_tabung extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_stok_tabung', 'Total Stok', 'required|trim');
		$this->form_validation->set_rules('id_kat_tabung', 'Kategori Kamar', 'required|trim');
		$this->form_validation->set_rules('id_rs', 'Rumah Sakit', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal', 'id_kamar', 'id_rs');
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
		$this->db->select('a.id_stok_tabung,
								a.total_stok_tabung,
								a.id_rs,
								a.id_kat_tabung,
								a.tanggal,
								b.fullname,
								c.nm_tabung
								');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		$this->db->join('ref_kat_tabung c', 'c.id_kat_tabung = a.id_kat_tabung', 'inner');
		return $this->db->count_all_results('ta_stok_tabung a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_stok_tabung,
								a.total_stok_tabung,
								a.id_rs,
								a.id_kat_tabung,
								a.tanggal,
								b.fullname,
								c.nm_tabung
								');
		$this->db->from('ta_stok_tabung a');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		$this->db->join('ref_kat_tabung c', 'a.id_kat_tabung = c.id_kat_tabung', 'inner');
		
		// id_rs Vaksin
		if(isset($post['id_rs']) AND $post['id_rs'] != '')
			$this->db->where('a.id_rs', $post['id_rs']);
		// Jenis Vaksin
		if(isset($post['id_tabung']) AND $post['id_tabung'] != '')
			$this->db->where('a.id_kat_tabung', $post['id_tabung']);
		// tanggal Masuk
        if(isset($post['tanggal_tabung']) AND $post['tanggal_tabung'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y")', $post['tanggal_tabung']);
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
		$this->db->order_by('a.tanggal DESC');
		$this->db->order_by('a.id_stok_tabung DESC');
  	}

	public function getDataDetail($id_stok_tabung)
	{
		$this->db->where('id_stok_tabung', $id_stok_tabung);
		$query = $this->db->get('ta_stok_tabung');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal	= date_convert(escape($this->input->post('tanggal', TRUE)));

			$data = array(
				'tanggal'				=> $tanggal,
				'total_stok_tabung'		=> escape($this->input->post('total_stok_tabung', TRUE)),
				'id_rs'					=> escape($this->input->post('id_rs', TRUE)),
				'id_kat_tabung'			=> escape($this->input->post('id_kat_tabung', TRUE))
			);
			$this->db->insert('ta_stok_tabung', $data);
			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_stok_tabung		= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_stok_tabung);
		$tanggal  			= !empty($dataVaksin) ? $dataVaksin['tanggal'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal'=>$tanggal);
		else {
				$data = array(
					'tanggal'				=> date_convert(escape($this->input->post('tanggal', TRUE))),
					'total_stok_tabung'		=> escape($this->input->post('total_stok_tabung', TRUE)),
					'id_rs'					=> escape($this->input->post('id_rs', TRUE)),
					'id_kat_tabung'			=> escape($this->input->post('id_kat_tabung', TRUE))
				);
			$this->db->where('id_stok_tabung', $id_stok_tabung);
			$this->db->update('ta_stok_tabung', $data);
			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
		}
	}

	public function deleteData()
	{
		$id_stok_tabung	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_stok_tabung', $id_stok_tabung);
		$this->db->delete('ta_stok_tabung');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

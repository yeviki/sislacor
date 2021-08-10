<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model vaksin masuk
 *
 * @author Yogi "solop" Kaputra
 */

class Model_vaksin_masuk extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_stok', 'Total Stok', 'required|trim');
		$this->form_validation->set_rules('jenis_vaksin', 'Jenis Vaksin', 'required|trim');
		$this->form_validation->set_rules('penyalur', 'Penyalur', 'required|trim');
		$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('id_penyalur', 'id_jenis_vaksin', 'tanggal');
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
		$this->db->select('a.id_stok_masuk,
								a.total_stok,
								a.id_jenis_vaksin,
								a.id_penyalur,
								a.tanggal,
								a.create_by,
								a.create_date,
								a.create_ip,
								a.mod_by,
								a.mod_date,
								a.mod_ip,
								b.nm_vaksin,
								c.nm_penyalur
								');
		$this->db->join('ref_jenis_vaksin b', 'b.id_jenis_vaksin = a.id_jenis_vaksin', 'inner');
		$this->db->join('ref_penyalur c', 'c.id_penyalur = a.id_penyalur', 'inner');
		return $this->db->count_all_results('ta_vaksin_masuk a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_stok_masuk,
							a.total_stok,
							a.id_jenis_vaksin,
							a.id_penyalur,
							a.tanggal,
							b.nm_vaksin,
							c.nm_penyalur
							');
		$this->db->from('ta_vaksin_masuk a');
		$this->db->join('ref_jenis_vaksin b', 'b.id_jenis_vaksin = a.id_jenis_vaksin', 'inner');
		$this->db->join('ref_penyalur c', 'c.id_penyalur = a.id_penyalur', 'inner');
		
		// Penyalur Vaksin
		if(isset($post['penyalur']) AND $post['penyalur'] != '')
			$this->db->where('a.id_penyalur', $post['penyalur']);
		// Jenis Vaksin
		if(isset($post['jenis_vaksin']) AND $post['jenis_vaksin'] != '')
			$this->db->where('a.id_jenis_vaksin', $post['jenis_vaksin']);
		// Tanggal Masuk
        if(isset($post['tanggal']) AND $post['tanggal'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y")', $post['tanggal']);
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
		$this->db->order_by('a.id_stok_masuk DESC');
  	}

	public function getDataDetail($id_stok_masuk)
	{
		$this->db->where('id_stok_masuk', $id_stok_masuk);
		$query = $this->db->get('ta_vaksin_masuk');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal			= date_convert(escape($this->input->post('tanggal_masuk', TRUE)));

			$data = array(
				'tanggal'			=> $tanggal,
				'total_stok'		=> escape($this->input->post('total_stok', TRUE)),
				'id_jenis_vaksin'	=> escape($this->input->post('jenis_vaksin', TRUE)),
				'id_penyalur'		=> escape($this->input->post('penyalur', TRUE)),
				'create_by'			=> $create_by,
				'create_date'		=> $create_date,
				'create_ip'			=> $create_ip,
				'mod_by'			=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'			=> $create_ip
			);
			$this->db->insert('ta_vaksin_masuk', $data);
			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_stok_masuk		= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));

		//cek data
		$dataVaksin 	= $this->getDataDetail($id_stok_masuk);
		$tanggal  	= !empty($dataVaksin) ? $dataVaksin['tanggal'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal'=>$tanggal);
		else {
				$data = array(
					'tanggal'			=> date_convert(escape($this->input->post('tanggal', TRUE))),
					'total_stok'		=> escape($this->input->post('total_stok', TRUE)),
					'id_jenis_vaksin'	=> escape($this->input->post('jenis_vaksin', TRUE)),
					'id_penyalur'		=> escape($this->input->post('penyalur', TRUE)),
					'mod_by'			=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'			=> $create_ip
				);
			$this->db->where('id_stok_masuk', $id_stok_masuk);
			$this->db->update('ta_vaksin_masuk', $data);
			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
		}
	}

	public function deleteData()
	{
		$id_stok_masuk	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_stok_masuk', $id_stok_masuk);
		$this->db->delete('ta_vaksin_masuk');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model vaksin masuk
 *
 * @author Yogi "solop" Kaputra
 */

class Model_suplai_vaksin extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_suplai', 'Total Stok', 'required|trim');
		$this->form_validation->set_rules('jenis_vaksin', 'Jenis Vaksin', 'required|trim');
		$this->form_validation->set_rules('penyalur', 'Penyalur', 'required|trim');
		$this->form_validation->set_rules('tanggal_suplai', 'tanggal_suplai Masuk', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('id_penyalur', 'id_jenis_vaksin', 'tanggal_suplai');
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
		$this->db->select('a.id_suplai_vaksin,
								a.total_suplai,
								a.id_jenis_vaksin,
								a.id_penyalur,
								a.tanggal_suplai,
								a.regency_id,
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
		return $this->db->count_all_results('ta_suplai_vaksin a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_suplai_vaksin,
							a.total_suplai,
							a.id_jenis_vaksin,
							a.id_penyalur,
							a.tanggal_suplai,
							a.regency_id,
							b.nm_vaksin,
							c.nm_penyalur
							');
		$this->db->from('ta_suplai_vaksin a');
		$this->db->join('ref_jenis_vaksin b', 'b.id_jenis_vaksin = a.id_jenis_vaksin', 'inner');
		$this->db->join('ref_penyalur c', 'c.id_penyalur = a.id_penyalur', 'inner');
		
		// Penyalur Vaksin
		if(isset($post['penyalur']) AND $post['penyalur'] != '')
			$this->db->where('a.id_penyalur', $post['penyalur']);
		// Jenis Vaksin
		if(isset($post['jenis_vaksin']) AND $post['jenis_vaksin'] != '')
			$this->db->where('a.id_jenis_vaksin', $post['jenis_vaksin']);
		// tanggal_suplai Masuk
        if(isset($post['tanggal_suplai']) AND $post['tanggal_suplai'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal_suplai, "%m/%d/%Y")', $post['tanggal_suplai']);
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
		$this->db->order_by('a.tanggal_suplai DESC');
		$this->db->order_by('a.id_suplai_vaksin DESC');
  	}

	public function getDataDetail($id_suplai_vaksin)
	{
		$this->db->where('id_suplai_vaksin', $id_suplai_vaksin);
		$query = $this->db->get('ta_suplai_vaksin');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal_suplai			= date_convert(escape($this->input->post('tanggal_suplai', TRUE)));

			$data = array(
				'tanggal_suplai'	=> $tanggal_suplai,
				'total_suplai'		=> escape($this->input->post('total_suplai', TRUE)),
				'id_jenis_vaksin'	=> escape($this->input->post('jenis_vaksin', TRUE)),
				'id_penyalur'		=> escape($this->input->post('penyalur', TRUE)),
				'regency_id'		=> escape($this->input->post('kabkota', TRUE)),
				'create_by'			=> $create_by,
				'create_date'		=> $create_date,
				'create_ip'			=> $create_ip,
				'mod_by'			=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'			=> $create_ip
			);
			$this->db->insert('ta_suplai_vaksin', $data);
			return array('message'=>'SUCCESS', 'tanggal_suplai'=>$tanggal_suplai);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_suplai_vaksin		= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));

		//cek data
		$dataVaksin 	= $this->getDataDetail($id_suplai_vaksin);
		$tanggal_suplai  	= !empty($dataVaksin) ? $dataVaksin['tanggal_suplai'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal_suplai'=>$tanggal_suplai);
		else {
				$data = array(
					'tanggal_suplai'	=> $tanggal_suplai,
					'total_suplai'		=> escape($this->input->post('total_suplai', TRUE)),
					'id_jenis_vaksin'	=> escape($this->input->post('jenis_vaksin', TRUE)),
					'id_penyalur'		=> escape($this->input->post('penyalur', TRUE)),
                    'regency_id'		=> escape($this->input->post('kabkota', TRUE)),
					'mod_by'			=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'			=> $create_ip
				);
			$this->db->where('id_suplai_vaksin', $id_suplai_vaksin);
			$this->db->update('ta_suplai_vaksin', $data);
			return array('message'=>'SUCCESS', 'tanggal_suplai'=>$tanggal_suplai);
		}
	}

	public function deleteData()
	{
		$id_suplai_vaksin	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_suplai_vaksin', $id_suplai_vaksin);
		$this->db->delete('ta_suplai_vaksin');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

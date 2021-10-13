<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model suplai capaian vaksin
 *
 * @author Yogi "solop" Kaputra
 */

class Model_capaian_vaksinasi extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue()
	{
		$this->form_validation->set_rules('total_vaksinasi', 'Total Stok', 'required|trim');
		$this->form_validation->set_rules('tanggal_capaian', 'Tanggal Capaian Vaksin', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal_capaian');
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
		$this->db->select('a.id_capaian_vaksin,
								a.total_vaksinasi,
								a.id_suplai_vaksin,
								a.tanggal_capaian,
								a.create_by,
								a.create_date,
								a.create_ip,
								a.mod_by,
								a.mod_date,
								a.mod_ip,
								b.total_suplai,
								b.id_jenis_vaksin,
								b.id_penyalur,
								b.regency_id,
								c.nm_vaksin,
								d.nm_penyalur,
                                e.regency_id
								');
		$this->db->join('ta_suplai_vaksin b', 'a.id_suplai_vaksin = b.id_suplai_vaksin', 'inner');
		$this->db->join('ref_jenis_vaksin c', 'b.id_jenis_vaksin = c.id_jenis_vaksin', 'inner');
		$this->db->join('ref_penyalur d', 'b.id_penyalur = d.id_penyalur', 'inner');
		return $this->db->count_all_results('ta_capaian_vaksin a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_capaian_vaksin,
								a.total_vaksinasi,
								a.id_suplai_vaksin,
								a.tanggal_capaian,
								a.create_by,
								a.create_date,
								a.create_ip,
								a.mod_by,
								a.mod_date,
								a.mod_ip,
								b.total_suplai,
								b.id_jenis_vaksin,
								b.id_penyalur,
								b.regency_id,
								c.nm_vaksin,
								d.nm_penyalur
								');
		$this->db->from('ta_capaian_vaksin a');
		$this->db->join('ta_suplai_vaksin b', 'a.id_suplai_vaksin = b.id_suplai_vaksin', 'inner');
		$this->db->join('ref_jenis_vaksin c', 'b.id_jenis_vaksin = c.id_jenis_vaksin', 'inner');
		$this->db->join('ref_penyalur d', 'b.id_penyalur = d.id_penyalur', 'inner');
		
		// Penyalur Vaksin
		if(isset($post['penyalur']) AND $post['penyalur'] != '')
			$this->db->where('b.id_penyalur', $post['penyalur']);
		// Jenis Vaksin
		if(isset($post['jenis_vaksin']) AND $post['jenis_vaksin'] != '')
			$this->db->where('b.id_suplai_vaksin', $post['jenis_vaksin']);
		// tanggal_capaian Masuk
        if(isset($post['tanggal_capaian']) AND $post['tanggal_capaian'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal_capaian, "%m/%d/%Y")', $post['tanggal_capaian']);
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
		$this->db->order_by('a.tanggal_capaian DESC');
		$this->db->order_by('a.id_capaian_vaksin DESC');
  	}

	public function getDataDetail($id_capaian_vaksin)
	{
		$this->db->where('id_capaian_vaksin', $id_capaian_vaksin);
		$query = $this->db->get('ta_capaian_vaksin');
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal_capaian	= date_convert(escape($this->input->post('tanggal_capaian', TRUE)));

			$data = array(
				'tanggal_capaian'	=> $tanggal_capaian,
				'total_vaksinasi'	=> escape($this->input->post('total_vaksinasi', TRUE)),
				'id_suplai_vaksin'	=> escape($this->input->post('suplai_vaksin', TRUE)),
				'create_by'			=> $create_by,
				'create_date'		=> $create_date,
				'create_ip'			=> $create_ip,
				'mod_by'			=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'			=> $create_ip
			);
			$this->db->insert('ta_capaian_vaksin', $data);
			return array('message'=>'SUCCESS', 'tanggal_capaian'=>$tanggal_capaian);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_capaian_vaksin	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_capaian_vaksin);
		$tanggal_capaian  	= !empty($dataVaksin) ? $dataVaksin['tanggal_capaian'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal_capaian'=>$tanggal_capaian);
		else {
				$data = array(
					'tanggal_capaian'	=> date_convert(escape($this->input->post('tanggal_capaian', TRUE))),
					'total_vaksinasi'	=> escape($this->input->post('total_vaksinasi', TRUE)),
					'id_suplai_vaksin'	=> escape($this->input->post('suplai_vaksin', TRUE)),
					'mod_by'			=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'			=> $create_ip
				);
			$this->db->where('id_capaian_vaksin', $id_capaian_vaksin);
			$this->db->update('ta_capaian_vaksin', $data);
			return array('message'=>'SUCCESS', 'tanggal_capaian'=>$tanggal_capaian);
		}
	}

	public function deleteData()
	{
		$id_capaian_vaksin	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_capaian_vaksin', $id_capaian_vaksin);
		$this->db->delete('ta_capaian_vaksin');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

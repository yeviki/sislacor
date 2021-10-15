<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model suplai capaian vaksinasi
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
		$this->form_validation->set_rules('tanggal_vaksinasi', 'Tanggal Capaian Vaksin', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal_vaksinasi', 'id_kat_dosis', 'id_regency');
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
		$this->db->select('a.id_capaian_vaksinasi,
								a.total_vaksinasi,
								a.id_kat_dosis,
								a.regency_id,
								a.tanggal_vaksinasi,
								a.create_by,
								a.create_date,
								a.create_ip,
								a.mod_by,
								a.mod_date,
								a.mod_ip,
                                b.nm_dosis,
                                c.name
								');
		$this->db->join('ref_kat_dosis b', 'a.id_kat_dosis = b.id_kat_dosis', 'inner');
		$this->db->join('wa_regency c', 'c.id = a.regency_id', 'inner');
		return $this->db->count_all_results('ta_capaian_vaksinasi a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('a.id_capaian_vaksinasi,
								a.total_vaksinasi,
								a.id_kat_dosis,
								a.regency_id,
								a.tanggal_vaksinasi,
								a.create_by,
								a.create_date,
								a.create_ip,
								a.mod_by,
								a.mod_date,
								a.mod_ip,
                                b.nm_dosis,
                                c.name
								');
		$this->db->from('ta_capaian_vaksinasi a');
		$this->db->join('ref_kat_dosis b', 'a.id_kat_dosis = b.id_kat_dosis', 'inner');
		$this->db->join('wa_regency c', 'c.id = a.regency_id', 'inner');
		
		// Regency
		if(isset($post['id_regency']) AND $post['id_regency'] != '')
			$this->db->where('a.regency_id', $post['id_regency']);
		// Jenis Dosis
		if(isset($post['id_kat_dosis']) AND $post['id_kat_dosis'] != '')
			$this->db->where('a.id_kat_dosis', $post['id_kat_dosis']);
		// Tanggal vaksinasi
        if(isset($post['tanggal']) AND $post['tanggal'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal_vaksinasi, "%m/%d/%Y")', $post['tanggal']);
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
		$this->db->order_by('a.tanggal_vaksinasi DESC');
		$this->db->order_by('a.id_capaian_vaksinasi DESC');
  	}

	public function getDataDetail($id_capaian_vaksinasi)
	{
		$this->db->select('a.id_capaian_vaksinasi,
								a.total_vaksinasi,
								a.id_kat_dosis,
								a.regency_id,
								a.tanggal_vaksinasi,
								a.create_by,
								a.create_date,
								a.create_ip,
								a.mod_by,
								a.mod_date,
								a.mod_ip,
                                b.nm_dosis,
                                c.name
								');
		$this->db->from('ta_capaian_vaksinasi a');
		$this->db->join('ref_kat_dosis b', 'a.id_kat_dosis = b.id_kat_dosis', 'inner');
		$this->db->join('wa_regency c', 'c.id = a.regency_id', 'inner');
		$this->db->where('a.id_capaian_vaksinasi', $id_capaian_vaksinasi);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$tanggal_vaksinasi	= date_convert(escape($this->input->post('tanggal_vaksinasi', TRUE)));

			$data = array(
				'tanggal_vaksinasi'	=> $tanggal_vaksinasi,
				'total_vaksinasi'	=> escape($this->input->post('total_vaksinasi', TRUE)),
				'regency_id'		=> escape($this->input->post('kabkota', TRUE)),
				'id_kat_dosis'		=> escape($this->input->post('dosis', TRUE)),
				'create_by'			=> $create_by,
				'create_date'		=> $create_date,
				'create_ip'			=> $create_ip,
				'mod_by'			=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'			=> $create_ip
			);
			$this->db->insert('ta_capaian_vaksinasi', $data);
			return array('message'=>'SUCCESS', 'tanggal_vaksinasi'=>$tanggal_vaksinasi);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_capaian_vaksinasi	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_capaian_vaksinasi);
		$tanggal_vaksinasi  	= !empty($dataVaksin) ? $dataVaksin['tanggal_vaksinasi'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal_vaksinasi'=>$tanggal_vaksinasi);
		else {
				$data = array(
					'tanggal_vaksinasi'	=> date_convert(escape($this->input->post('tanggal_vaksinasi', TRUE))),
					'total_vaksinasi'	=> escape($this->input->post('total_vaksinasi', TRUE)),
					'regency_id'		=> escape($this->input->post('kabkota', TRUE)),
					'id_kat_dosis'		=> escape($this->input->post('dosis', TRUE)),
					'mod_by'			=> $create_by,
					'mod_date'			=> $create_date,
					'mod_ip'			=> $create_ip
				);
			$this->db->where('id_capaian_vaksinasi', $id_capaian_vaksinasi);
			$this->db->update('ta_capaian_vaksinasi', $data);
			return array('message'=>'SUCCESS', 'tanggal_vaksinasi'=>$tanggal_vaksinasi);
		}
	}

	public function deleteData()
	{
		$id_capaian_vaksinasi	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_capaian_vaksinasi', $id_capaian_vaksinasi);
		$this->db->delete('ta_capaian_vaksinasi');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

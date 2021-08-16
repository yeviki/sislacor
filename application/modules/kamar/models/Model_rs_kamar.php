<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model
 * 
 * @author Yogi "solop" Kaputra
 */

class Model_rs_kamar extends CI_Model
{
	protected $_publishDate = "";
	public function __construct()
	{
		parent::__construct();
	}

	public function getDataKategoriKamar()
	{
		$this->db->order_by('id_kat_kamar', 'ASC');
		$query = $this->db->get('ref_kat_kamar');
		return $query->result_array();
	}

	public function validasiDataValue()
	{
		// $this->form_validation->set_rules('total_kamar', 'Total Stok', 'required|trim');
		// $this->form_validation->set_rules('id_kat_kamar', 'Kategori Kamar', 'required|trim');
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
		$this->db->group_by(array('a.id_rs', 'a.tanggal'));
		return $this->db->count_all_results('ta_rs_kamar a');
	}

	private function _get_datatables_query($param)
	{
		$post = array();
		if (is_array($param)) {
			foreach ($param as $v) {
				$post[$v['name']] = $v['value'];
			}
		}
		$this->db->select('GROUP_CONCAT(CONCAT(c.nm_kamar,": ",a.total_kamar) ORDER BY a.id_kat_kamar ASC SEPARATOR ", ") AS rekap,
								a.id_rs_kamar,
								a.total_kamar,
								a.id_rs,
								a.id_kat_kamar,
								a.tanggal,
								CONCAT("RS ", b.shortname), b.shortname AS name,
								b.shortname,
								c.nm_kamar
								');
		$this->db->from('ta_rs_kamar a');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		$this->db->join('ref_kat_kamar c', 'a.id_kat_kamar = c.id_kat_kamar', 'inner');
		
		// id_rs Vaksin
		if(isset($post['id_rs']) AND $post['id_rs'] != '')
			$this->db->where('a.id_rs', $post['id_rs']);
		// // Jenis Vaksin
		// if(isset($post['id_kamar']) AND $post['id_kamar'] != '')
		// 	$this->db->where('a.id_kat_kamar', $post['id_kamar']);
		// tanggal Masuk
        if(isset($post['tanggal_kamar']) AND $post['tanggal_kamar'] != ''){
			$this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y")', $post['tanggal_kamar']);
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
		$this->db->group_by(array('a.id_rs', 'a.tanggal'));
		$this->db->order_by('a.tanggal DESC');
		$this->db->order_by('a.id_rs_kamar DESC');
  	}

	public function getDataDetail($id_rs, $tanggal)
	{
		$this->db->select('a.id_rs_kamar,
							a.total_kamar,
							a.id_rs,
							a.id_kat_kamar,
							a.tanggal,
							b.shortname,
							c.nm_kamar
								');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		$this->db->join('ref_kat_kamar c', 'a.id_kat_kamar = c.id_kat_kamar', 'inner');
		$this->db->where('a.id_rs', $id_rs);
		$this->db->where('a.tanggal', $tanggal);

		$query = $this->db->get('ta_rs_kamar a');
		// echo $this->db->last_query(); die;
		return $query->result_array();
	}

	public function insertData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$params      	 	= escape($this->input->post('param', TRUE));
		$id_rs       		= escape($this->input->post('id_rs', TRUE));
		$tanggal       		= date_convert(escape($this->input->post('tanggal', TRUE)));

		// $tanggal	= date_convert(escape($this->input->post('tanggal', TRUE)));

		// 	$data = array(
		// 		'tanggal'			=> $tanggal,
		// 		'total_kamar'		=> escape($this->input->post('total_kamar', TRUE)),
		// 		'id_rs'				=> escape($this->input->post('id_rs', TRUE)),
		// 		'id_kat_kamar'		=> escape($this->input->post('id_kat_kamar', TRUE))
		// 	);
		// 	$this->db->insert('ta_rs_kamar', $data);

			$arrKamar = array();
			foreach ($params as $key => $v) {
				$arrKamar[] = array(
					'id_rs' 			=> $id_rs,
					'tanggal' 			=> $tanggal,
					'id_kat_kamar'	 	=> $key,
					'total_kamar'		=> $v,
				);
			}
			$this->db->insert_batch('ta_rs_kamar', $arrKamar);

			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_rs_kamar	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		//cek data
		$dataVaksin 	    = $this->getDataDetail($id_rs_kamar);
		$tanggal  			= !empty($dataVaksin) ? $dataVaksin['tanggal'] : '';
		if(count($dataVaksin) <= 0)
			return array('message'=>'ERROR', 'tanggal'=>$tanggal);
		else {
				$data = array(
					'tanggal'			=> date_convert(escape($this->input->post('tanggal', TRUE))),
					'total_kamar'		=> escape($this->input->post('total_kamar', TRUE)),
					'id_rs'				=> escape($this->input->post('id_rs', TRUE)),
					'id_kat_kamar'		=> escape($this->input->post('id_kat_kamar', TRUE))
				);
			$this->db->where('id_rs_kamar', $id_rs_kamar);
			$this->db->update('ta_rs_kamar', $data);
			return array('message'=>'SUCCESS', 'tanggal'=>$tanggal);
		}
	}

	public function deleteData()
	{
		$id_rs_kamar	= $this->encryption->decrypt(escape($this->input->post('vaksinId', TRUE)));
		$this->db->where('id_rs_kamar', $id_rs_kamar);
		$this->db->delete('ta_rs_kamar');
		return array('message'=>'SUCCESS');
		
	}
}

// This is the end of model

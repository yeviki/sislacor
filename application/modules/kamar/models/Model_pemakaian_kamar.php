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

	public function getDataKategoriKamar()
	{
		$this->db->order_by('id_kat_kamar', 'ASC');
		$query = $this->db->get('ref_kat_kamar');
		return $query->result_array();
	}
	
	public function validasiDataValue()
	{
		$this->form_validation->set_rules('tanggal_pemakaian', 'Tanggal_pemakaian', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('tanggal_pemakaian', 'id_rs');
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
		$this->db->group_by(array('a.id_rs', 'a.tanggal_pemakaian'));
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
		$this->db->select('a.id_rs,
							a.tanggal_pemakaian,
							b.shortname');
		$this->db->from('ta_pemakaian_kamar a');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		
		// RS
		if(isset($post['id_rs']) AND $post['id_rs'] != '')
			$this->db->where('a.id_rs', $post['id_rs']);
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
		$this->db->group_by(array('a.id_rs', 'a.tanggal_pemakaian'));
		$this->db->order_by('a.tanggal_pemakaian DESC');
		$this->db->order_by('a.id_rs ASC');
	}
	  
	  public function getTotalPemakaian($date, $rs, $kamar){
		$this->db->select('a.id_rs,
						   a.tanggal_pemakaian,
						   a.id_kat_kamar,
						   a.total_terpakai');
		$this->db->from('ta_pemakaian_kamar a');
		$this->db->where('a.tanggal_pemakaian', $date);
		$this->db->where('a.id_rs', $rs);
		$this->db->where('a.id_kat_kamar', $kamar);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getDataDetail($id_rs, $tanggal)
	{
		$this->db->select('a.id_pemakaian_kamar,
							a.total_terpakai,
							a.id_rs,
							a.id_kat_kamar,
							a.tanggal_pemakaian,
							b.shortname,
							c.nm_kamar
								');
		$this->db->join('ms_rs_rujukan b', 'a.id_rs = b.id_rs', 'inner');
		$this->db->join('ref_kat_kamar c', 'a.id_kat_kamar = c.id_kat_kamar', 'inner');
		$this->db->where('a.id_rs', $id_rs);
		$this->db->where('a.tanggal_pemakaian', $tanggal);

		$query = $this->db->get('ta_pemakaian_kamar a');
		// echo $this->db->last_query(); die;
		return $query->result_array();
	}

	public function getDataStokKamar($id_rs)
	{
		$this->db->select('DISTINCT(a.id_kat_kamar), a.id_rs, 
						SUM(a.total_kamar) AS total_kamar,
						(SELECT sum(x.total_terpakai) FROM  ta_pemakaian_kamar x WHERE x.id_rs=a.id_rs AND x.id_kat_kamar=a.id_kat_kamar) AS jml_digunakan,
						IFNULL (((SUM(a.total_kamar))-(SELECT sum(x.total_terpakai) FROM  ta_pemakaian_kamar x WHERE x.id_rs=a.id_rs AND x.id_kat_kamar=a.id_kat_kamar)),SUM(a.total_kamar)) AS sisa_kamar,
						a.id_kat_kamar,
						a.tanggal,
						c.shortname, 
						e.nm_kamar
					');
		$this->db->join('ms_rs_rujukan c', 'a.id_rs = c.id_rs', 'inner');
		$this->db->join('ref_kat_kamar e', 'e.id_kat_kamar = a.id_kat_kamar', 'inner');
		$this->db->where('a.id_rs', $id_rs);
		$this->db->group_by('a.id_rs');
		$this->db->group_by('a.id_kat_kamar');
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
		$tanggal_pemakaian	= date_convert(escape($this->input->post('tanggal_pemakaian', TRUE)));
		$this->db->where('id_rs', $id_rs);
		$this->db->where('tanggal_pemakaian', $tanggal_pemakaian);
		$qTot = $this->db->count_all_results('ta_pemakaian_kamar');
		if($qTot > 0)
			return array('message'=>'ERROR');
		else {
			$arrKamar = array();
			foreach ($params as $key => $v) {
				$arrKamar[] = array(
					'id_rs' 			=> $id_rs,
					'tanggal_pemakaian' => $tanggal_pemakaian,
					'id_kat_kamar'	 	=> $key,
					'total_terpakai'	=> $v,
					'create_by'	 		=> $create_by,
					'create_date' 		=> $create_date,
					'create_ip'	 		=> $create_ip,
					'mod_by'	 		=> $create_by,
					'mod_date' 			=> $create_date,
					'mod_ip'	 		=> $create_ip
				);
			}
			$this->db->insert_batch('ta_pemakaian_kamar', $arrKamar);
			return array('message'=>'SUCCESS', 'tanggal_pemakaian'=>$tanggal_pemakaian);
		}
	}

	public function updateData()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$params       		= escape($this->input->post('param', TRUE));
		$id_rs				= $this->encryption->decrypt(escape($this->input->post('rsId', TRUE)));
		$publishDate  		= escape($this->input->post('publishDate', TRUE));

		//cek data
		$dataKamar 	    	= $this->getDataDetail($id_rs, $publishDate);
		if(count($dataKamar) <= 0)
			return array('message'=>'ERROR');
		else {
			foreach ($params as $key => $v) {
				$data = array(
					'total_terpakai'	 	=> $v,
					'tanggal_pemakaian'	 	=> date_convert(escape($this->input->post('tanggal_pemakaian', TRUE))),
					'mod_by'	 			=> $create_by,
					'mod_date' 	 			=> $create_date,
					'mod_ip'	 			=> $create_ip
				);
				$this->db->where('id_rs', $id_rs);
				$this->db->where('id_kat_kamar', $key);
				$this->db->where('tanggal_pemakaian', $publishDate);
				$this->db->update('ta_pemakaian_kamar', $data);
				return array('message'=>'SUCCESS');
			}

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

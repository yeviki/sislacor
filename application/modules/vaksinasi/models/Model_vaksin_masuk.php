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
		$this->form_validation->set_rules('id_penyalur', 'Penyalur', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal Masuk', 'required|trim');
  		validation_message_setting();
		if ($this->form_validation->run() == FALSE)
			return false;
		else
			return true;
	}

	var $search = array('total_stok', 'nm_kondisi', 'tanggal');
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
							a.id_penyalur,
							a.tanggal,
							b.nm_penyalur
							');
	$this->db->join('ref_penyalur b', 'b.id_penyalur = a.id_penyalur', 'inner');
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
							a.id_penyalur,
							a.tanggal,
							b.nm_penyalur
							');
		$this->db->from('ta_vaksin_masuk a');
		$this->db->join('ref_penyalur b', 'b.id_penyalur = a.id_penyalur', 'inner');
		
		// Penyalur Vaksin
		if(isset($post['penyalur']) AND $post['penyalur'] != '')
			$this->db->where('a.id_penyalur', $post['penyalur']);
		// Tanggal Masuk
        if(isset($post['start_date']) AND $post['start_date'] != ''){
            // $arrDate = explode(' - ', $post['tgl_range']);
			// $this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"', NULL, FALSE);
			$this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y"), $post['end_date'].'"', NULL, FALSE);
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
		$this->db->order_by('a.id_timbangan DESC');
  }

	public function getDataListTimbanganReport($lokasi, $suplier, $tanggal)
	{
		$arrDate = explode(' - ', $tanggal);
        $this->db->select('a.id_timbangan,
							a.token,
							a.id_master_truck,
							a.id_pemasok,
							a.id_master_tpa,
							a.total_stok,
							a.no_tiket,
							a.jam_masuk,
							a.jam_keluar,
							a.berat_masuk,
							a.berat_keluar,
							a.berat_bersih,
							a.id_penyalur,
							a.tanggal,
							a.biaya,
							a.flag,
							b.nm_kondisi,
							c.plat_no,
							d.lokasi_tpa,
							d.nama_tpa,
							e.id_regency,
							f.name
							');
        $this->db->from('ms_timbangan a');
        $this->db->join('wa_kondisi b', 'a.id_penyalur = b.id_penyalur', 'inner');
        $this->db->join('master_truck c', 'a.id_master_truck = c.id_master_truck', 'inner');
        $this->db->join('master_tpa d', 'c.id_master_tpa = d.id_master_tpa', 'inner');
        $this->db->join('tb_pemasok e', 'e.id_pemasok = a.id_pemasok', 'inner');
        $this->db->join('wa_regency f', 'f.id = e.id_regency', 'inner');
		$this->db->where('a.flag', 2);
		if($this->app_loader->is_tpa() OR $this->app_loader->is_petugas()) {
			$this->db->where('a.id_master_tpa', $this->app_loader->current_tpa());
		}
		if($lokasi != '')
			$this->db->where('a.id_master_tpa', $lokasi);
		if($suplier != '')
			$this->db->where('a.id_pemasok', $suplier);
		if($tanggal != '')
			$this->db->where('DATE_FORMAT(a.tanggal, "%m/%d/%Y") BETWEEN "'.$arrDate[0].'"  AND "'.$arrDate[1].'"');
		$this->db->order_by('a.id_timbangan ASC');

		$query = $this->db->get();
    	return $query->result_array();
	}

	public function getDataDetailTMB($id_timbangan)
	{
		$this->db->where('id_timbangan', $id_timbangan);
		$query = $this->db->get('ms_timbangan');
		return $query->row_array();
	}

	private function getToken($param1, $param2) {

		$token = generateToken($param1, $param2);

		//Cek Token yang sudah ada atau belum
		$this->db->where('token', $token);
		$count = $this->db->count_all_results('ms_timbangan');

		if($count > 0)
			$this->getToken($param1, $param2);
		else
			return $token;
	}

	//Menampilkan kode report pemeriksaan
	public function getTiket()
	{	
		$year = date('Y');
		$sql2 = "SELECT count(id_timbangan) AS tiket FROM ms_timbangan WHERE tahun = '$year'";
		$no_tiket = $this->db->query($sql2, array());
		return $no_tiket->row_array();
	}

	public function insertDataTMB()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();

		$month  		= date('m');
		$year  			= date('Y');
		$data_tiket 	= $this->getTiket();

		$noTiket 		= ($data_tiket['tiket']+1).'/'.bulan_romawi($month).'/'.$year;
		$nmSupir 		= escape($this->input->post('total_stok', TRUE));

		$token			= $this->getToken($nmSupir, $noTiket);

		//cek data
		$this->db->where('no_tiket', $noTiket);
		$qTot = $this->db->count_all_results('ms_timbangan');
		if($qTot > 0)
			return array('message'=>'ERROR', 'kode'=>$noTiket);
		else {
			$data = array(
				'token'				=> $token,
				'no_tiket'			=> $noTiket,
				'total_stok'			=> escape($this->input->post('total_stok', TRUE)),
				'jam_masuk'			=> $create_time_now,
				'jam_keluar'		=> '',
				'berat_masuk'		=> escape($this->input->post('berat_masuk', TRUE)),
				'berat_keluar'		=> escape($this->input->post('berat_keluar', TRUE)),
				'berat_bersih'		=> escape($this->input->post('berat_bersih', TRUE)),
				'biaya'				=> escape($this->input->post('biaya', TRUE)),
				'id_penyalur'		=> escape($this->input->post('id_penyalur', TRUE)),
				'id_master_tpa'		=> escape($this->input->post('tpa', TRUE)),
				'id_pemasok'		=> escape($this->input->post('pemasok', TRUE)),
				'id_master_truck'	=> escape($this->input->post('truck', TRUE)),
				'tanggal'			=> date_convert(escape($this->input->post('tanggal', TRUE))),
				'tahun'				=> $year,
				'flag'				=> '1',
				'create_by'			=> $create_by,
				'create_date'		=> $create_date,
				'create_ip'			=> $create_ip,
				'mod_by'			=> $create_by,
				'mod_date'			=> $create_date,
				'mod_ip'			=> $create_ip
			);
			$this->db->insert('ms_timbangan', $data);
			return array('message'=>'SUCCESS', 'kode'=>$noTiket);
		}
	}

	public function updateDataTMB()
	{
		$create_by    		= $this->app_loader->current_account();
		$create_date 		= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_time_now 	= gmdate('H:i:s', time()+60*60*7);
		$create_ip    		= $this->input->ip_address();
		$id_timbangan		= $this->encryption->decrypt(escape($this->input->post('timbanganId', TRUE)));
		//cek data rs by id
		$dataTPA 	= $this->getDataDetailTMB($id_timbangan);
		$tiket  	= !empty($dataTPA) ? $dataTPA['no_tiket'] : '';
		$flag  		= !empty($dataTPA) ? $dataTPA['flag'] : '';
		if(count($dataTPA) <= 0)
			return array('message'=>'ERROR', 'kode'=>$tiket);
		else {
				if ($flag == 1) {
					$data = array(
						'total_stok'			=> escape($this->input->post('total_stok', TRUE)),
						'jam_keluar'		=> $create_time_now,
						'berat_masuk'		=> escape($this->input->post('berat_masuk', TRUE)),
						'berat_keluar'		=> escape($this->input->post('berat_keluar', TRUE)),
						'berat_bersih'		=> escape($this->input->post('berat_bersih', TRUE)),
						'biaya'				=> escape($this->input->post('biaya', TRUE)),
						'id_penyalur'		=> escape($this->input->post('id_penyalur', TRUE)),
						'id_master_tpa'		=> escape($this->input->post('tpa', TRUE)),
						'id_pemasok'		=> escape($this->input->post('pemasok', TRUE)),
						'id_master_truck'	=> escape($this->input->post('truck', TRUE)),
						'tanggal'			=> date_convert(escape($this->input->post('tanggal', TRUE))),
						'flag'				=> '2',
						'mod_by'			=> $create_by,
						'mod_date'			=> $create_date,
						'mod_ip'			=> $create_ip
					);
				} else {
					$data = array(
						'total_stok'			=> escape($this->input->post('total_stok', TRUE)),
						'berat_masuk'		=> escape($this->input->post('berat_masuk', TRUE)),
						'berat_keluar'		=> escape($this->input->post('berat_keluar', TRUE)),
						'berat_bersih'		=> escape($this->input->post('berat_bersih', TRUE)),
						'biaya'				=> escape($this->input->post('biaya', TRUE)),
						'id_penyalur'		=> escape($this->input->post('id_penyalur', TRUE)),
						'id_master_tpa'		=> escape($this->input->post('tpa', TRUE)),
						'id_pemasok'		=> escape($this->input->post('pemasok', TRUE)),
						'id_master_truck'	=> escape($this->input->post('truck', TRUE)),
						'tanggal'			=> date_convert(escape($this->input->post('tanggal', TRUE))),
						'mod_by'			=> $create_by,
						'mod_date'			=> $create_date,
						'mod_ip'			=> $create_ip
					);
				}
				$this->db->where('id_timbangan', $id_timbangan);
				$this->db->update('ms_timbangan', $data);
				return array('message'=>'SUCCESS', 'kode'=>$tiket);
		}
	}

	public function deleteDataTMB()
	{
		$id_timbangan	= $this->encryption->decrypt(escape($this->input->post('timbanganId', TRUE)));

			$this->db->where('id_timbangan', $id_timbangan);
			$this->db->delete('ms_timbangan');
			return array('message'=>'SUCCESS');
		
	}

	public function getDataTiket($token)
	{
        $this->db->select('a.id_timbangan,
							a.token,
							a.id_master_truck,
							a.no_tiket,
							a.id_pemasok,
							a.id_master_tpa,
							a.total_stok,
							a.jam_masuk,
							a.jam_keluar,
							a.berat_masuk,
							a.berat_keluar,
							a.berat_bersih,
							a.id_penyalur,
							a.tanggal,
							a.biaya,
							a.flag,
							b.nm_kondisi,
							c.plat_no,
							d.lokasi_tpa,
							d.nama_tpa,
							e.id_regency,
							f.name
							');
        $this->db->from('ms_timbangan a');
        $this->db->join('wa_kondisi b', 'a.id_penyalur = b.id_penyalur', 'inner');
        $this->db->join('master_truck c', 'a.id_master_truck = c.id_master_truck', 'left');
        $this->db->join('master_tpa d', 'c.id_master_tpa = d.id_master_tpa', 'left');
        $this->db->join('tb_pemasok e', 'e.id_pemasok = a.id_pemasok', 'left');
        $this->db->join('wa_regency f', 'f.id = e.id_regency', 'left');
		$this->db->where('a.token', $token);
		$query = $this->db->get();
		return $query->row_array();
	}
}

// This is the end of model

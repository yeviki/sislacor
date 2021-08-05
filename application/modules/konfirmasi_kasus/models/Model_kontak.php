<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_kontak extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

  private function getDataPasienByToken($token)
	{
		$this->db->where('token', $token);
		$query = $this->db->get('ta_pasien');
		return $query->row_array();
	}

  //set column search data target
	var $search = array('a.namalkp', 'a.nik', 'a.umur', 'a.kontakaddress', 'a.nohp', 'a.hubdgnkasus', 'a.aktivitaskontak');
	public function get_datatables($token)
  {
    $this->_get_datatables_query($token);
    if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered($token)
  {
    $this->_get_datatables_query($token);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($token)
  {
    $this->db->where('id_pasien IN(SELECT id_pasien FROM ta_pasien WHERE token = "'.$token.'")', NULL, FALSE);
		return $this->db->count_all_results('ta_paparan_kontak');
  }

  private function _get_datatables_query($token)
  {
		$this->db->select('a.id_kontak,
                       a.id_pasien,
                       a.nik,
                       a.namalkp,
											 a.tmptlhr,
											 a.tgllhr,
											 a.umur,
											 a.gender,
											 a.address,
											 a.nohp,
											 a.hubdgnkasus,
											 a.aktivitaskontak,
											 a.rmhsama');
		$this->db->from('ta_paparan_kontak a');
		$this->db->join('ta_pasien b', 'a.id_pasien = b.id_pasien', 'inner');
		$this->db->where('b.token', $token);
		$i = 0;
    foreach ($this->search as $se) { // loop column
      if($_POST['search']['value']) { // if datatable send POST for search
        if($i===0) { // first loop
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($se, $_POST['search']['value']);
        } else {
          $this->db->or_like($se, $_POST['search']['value']);
        }
        if(count($this->search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
		$this->db->order_by('a.id_kontak ASC');
  }

  public function deleteDataPaparanKontak($token, $id_kontak)
  {
    //get id pasien
		$dataPasien = $this->getDataPasienByToken($token);
    $id_pasien  = !empty($dataPasien) ? $dataPasien['id_pasien'] : 0;
		$namalkp    = !empty($dataPasien) ? $dataPasien['namalkp'] : '';
    if(count($dataPasien) <= 0) {
      return array('message'=>'ERROR', 'nama'=>'');
    } else {
      $this->db->where('id_pasien', $id_pasien);
      $this->db->where('id_kontak', $id_kontak);
      $this->db->delete('ta_paparan_kontak');
      return array('message'=>'SUCCESS', 'nama'=>$namalkp);
    }
  }

}

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of users model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_users extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function validasiDataValue($role)
	{
		if($role == "add") {
			$rules = 'required|trim|min_length[8]|alpha_dash|is_unique[xi_sa_users.username]';
			$valid = 'required|';
		} else {
			$rules = 'required|trim|min_length[8]|alpha_dash';
			if($this->input->post('password') != "")
				$valid = 'required|';
			else
				$valid = '';
		}
  	$this->form_validation->set_rules('username', 'Username', $rules);
  	$this->form_validation->set_rules('nama_user', 'Nama Lengkap', 'required|trim');
  	$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', $valid.'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/]');
  	$this->form_validation->set_rules('conf_password', 'Ulangi Password', $valid.'matches[password]');
		$this->form_validation->set_rules('nama_group[]', 'Nama Group', 'required|trim');
		if(in_array(3, $this->input->post('nama_group', TRUE)))
			$this->form_validation->set_rules('regency', 'Nama Daerah', 'required|trim');
		if(in_array(4, $this->input->post('nama_group', TRUE)))
			$this->form_validation->set_rules('rsud', 'Rumah Sakit', 'required|trim');
		if(in_array(5, $this->input->post('nama_group', TRUE)))
			$this->form_validation->set_rules('labor', 'Nama Laboratorium', 'required|trim');
  	validation_message_setting();
    if ($this->form_validation->run() == FALSE)
      return false;
    else
      return true;
	}

	public function getDataGroup()
	{
		$this->db->where('id_status', 1);
		if(!$this->app_loader->is_admin()) {
			$this->db->where_not_in('id_level_akses', array(1));
		}
		$query = $this->db->get('xi_sa_group');
		return $query->result_array();
	}

	public function getDataLevelAkses()
  {
		if(!$this->app_loader->is_admin()) {
			$this->db->where('id_level_akses !=', 1);
		}
		$this->db->where('id_status', 1);
		$query = $this->db->get('xi_sa_level_akses');
    $dd_level[''] = 'Pilih Data';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_level[$row['id_level_akses']] = $row['level_akses'];
      }
    }
    return $dd_level;
  }

	public function getDataRegency()
  {
		$this->db->where('province_id', '13');
		$this->db->order_by('status ASC');
		$this->db->order_by('name ASC');
		$query = $this->db->get('wa_regency');
    $dd_reg[''] = 'Pilih Data';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_reg[$row['id']] = ($row['status'] == 1) ? 'KAB '.$row['name'] : $row['name'];
      }
    }
    return $dd_reg;
  }

	public function getDataHospital()
  {
		$this->db->where('status', '1');
		$query = $this->db->get('ms_rs_rujukan');
    $dd_rs[''] = 'Pilih Data';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_rs[$row['id_rs']] = $row['fullname'].' ['.$row['kode_fasyankes'].']';
      }
    }
    return $dd_rs;
  }

	public function getDataLabor()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ms_laboratorium');
    $dd_lab[''] = 'Pilih Data';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_lab[$row['id']] = $row['name'].' ['.$row['kode'].']';
      }
    }
    return $dd_lab;
  }

	var $search = array('a.username', 'a.nama_user', 'c.nama_group', 'e.fullname', 'e.shortname', 'f.name');
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
		$this->db->select('a.id_users');
		$this->db->from('xi_sa_users a');
		$this->db->join('xi_sa_users_privileges b', 'a.id_users = b.id_users', 'left');
		$this->db->join('xi_sa_group c', 'b.id_group = c.id_group', 'left');
		if(!$this->app_loader->is_admin()) {
			$this->db->where('c.id_level_akses !=', 1);
		}
    return $this->db->count_all_results();
  }

  private function _get_datatables_query($param)
  {
		$post = array();
		if (is_array($param)) {
      foreach ($param as $v) {
        $post[$v['name']] = $v['value'];
      }
    }
		$this->db->select('a.id_users,
											 a.token,
											 a.username,
											 a.email,
											 a.nama_user,
											 a.id_rs,
											 a.id_regency,
											 a.blokir,
											 a.id_status,
											 (CASE
											   WHEN d.pass_plain IS NULL THEN "-"
											   ELSE d.pass_plain
											 END) AS pass_plain,
											 (CASE
											   WHEN a.id_rs = 0 THEN "-"
											   ELSE e.fullname
											 END) AS rs_rujukan,
											 (CASE
											   WHEN a.id_regency = 0 THEN "-"
											   ELSE IF(f.status = 1, CONCAT("KAB ", f.name), f.name)
											 END) AS regency,
											 GROUP_CONCAT(c.nama_group ORDER BY c.id_level_akses ASC SEPARATOR ",") AS group_user');
    $this->db->from('xi_sa_users a');
		$this->db->join('xi_sa_users_privileges b', 'a.id_users = b.id_users', 'left');
		$this->db->join('xi_sa_group c', 'b.id_group = c.id_group', 'left');
		$this->db->join('xi_sa_users_default_pass d', 'a.id_users = d.id_users', 'left');
		$this->db->join('ms_rs_rujukan e', 'a.id_rs = e.id_rs', 'left');
		$this->db->join('wa_regency f', 'a.id_regency = f.id', 'left');
		$this->db->where('b.id_status', 1);
		if(!$this->app_loader->is_admin()) {
			$this->db->where('c.id_level_akses !=', 1);
		}
		//level
		if(isset($post['level']) AND $post['level'] != '')
			$this->db->where('c.id_level_akses', $post['level']);
		//regency
		if(isset($post['regency']) AND $post['regency'] != '')
			$this->db->where('a.id_regency', $post['regency']);
		//rsud
		if(isset($post['rsud']) AND $post['rsud'] != '')
			$this->db->where('a.id_rs', $post['rsud']);
		//blokir
		if(isset($post['blokir']) AND $post['blokir'] != '')
			$this->db->where('a.blokir', $post['blokir']);
		//status
		if(isset($post['status']) AND $post['status'] != '')
			$this->db->where('a.id_status', $post['status']);

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

		$this->db->group_by('a.id_users');
		$this->db->order_by('a.id_users ASC');
  }

	public function getDataUserPassword($id_users)
	{
		$this->db->select('pass_plain');
		$this->db->where('id_users', $id_users);
		$query = $this->db->get('xi_sa_users_default_pass');
		return $query->row_array();
	}

	public function getDataUserGroup($id_users)
	{
		$this->db->select('p.id_group,
										   g.nama_group');
		$this->db->from('xi_sa_users_privileges p');
		$this->db->join('xi_sa_group g', 'g.id_group = p.id_group', 'inner');
		$this->db->where('p.id_users', $id_users);
		$this->db->where('p.id_status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	/*Fungsi get data edit by id dan url*/
	public function editDataUsers($id)
	{
		$this->db->select('a.*,
											 GROUP_CONCAT(b.id_group ORDER BY b.id_group ASC SEPARATOR ",") AS group_user');
		$this->db->from('xi_sa_users a');
		$this->db->join('xi_sa_users_privileges b', 'a.id_users = b.id_users', 'left');
		$this->db->where('a.token', $id);
		$this->db->where('b.id_status', 1);
		$this->db->group_by('a.id_users');
		$this->db->order_by('a.id_users ASC');
		$this->db->limit(1);
    $query = $this->db->get();
    return $query->row_array();
	}

	public function getDataUserGroupPrivileges($id_users)
	{
		$this->db->where('id_users', abs($id_users));
		$this->db->where('id_status', 1);
    $query = $this->db->get('xi_sa_users_privileges');
    return $query->result_array();
	}

	/* Fungsi untuk insert data */
	public function insertDataUsers()
	{
		//get data
		$create_by   = $this->app_loader->current_account();
		$create_date = date('Y-m-d H:i:s');
		$create_ip   = $this->input->ip_address();
		$username		 = escape($this->input->post('username', TRUE));
		$password 	 = escape($this->input->post('password', TRUE));
		$group			 = escape($this->input->post('nama_group', TRUE));
		$fullname    = escape($this->input->post('nama_user', TRUE));
		$id_rs       = escape($this->input->post('rsud', TRUE));
		$token			 = generateToken($username, $fullname);
		//get data rsud
		$this->db->where('id_rs', $id_rs);
		$dataRs = $this->db->get('ms_rs_rujukan')->row_array();
		$regency = !empty($dataRs) ? $dataRs['id_regency'] : escape($this->input->post('regency', TRUE));
		$data = array(
			'token'				  => $token,
			'username' 			=> $username,
			'password' 			=> $this->bcrypt->hash_password($password),
			'email' 				=> escape($this->input->post('email', TRUE)),
			'nama_user' 		=> $fullname,
			'foto_profile' 	=> 'default.png',
			'id_rs' 				=> $id_rs,
			'id_regency' 		=> $regency,
			'id_labor' 			=> escape($this->input->post('labor', TRUE)),
			'create_by' 		=> $create_by,
			'create_date' 	=> $create_date,
			'create_ip' 		=> $create_ip,
			'mod_by' 				=> $create_by,
			'mod_date' 			=> $create_date,
			'mod_ip' 				=> $create_ip,
			'blokir' 				=> escape($this->input->post('blokir', TRUE)),
			'id_status' 		=> escape($this->input->post('status', TRUE))
		);
		/*cek username yang diinputkan*/
		$this->db->where('username', $username);
		$totUser = $this->db->count_all_results('xi_sa_users');
		if($totUser > 0)
			return array('message'=>'ERROR', 'note'=>$username);
		else {
			/*query insert*/
			$this->db->insert('xi_sa_users', $data);
			$id_users = $this->db->insert_id();
			/*query insert user password*/
			$this->db->insert('xi_sa_users_default_pass', array('id_users' => $id_users, 'pass_plain' => $password, 'updated' => 'N'));
			/*query insert user group privileges*/
			foreach ($group as $id) {
				$this->db->insert('xi_sa_users_privileges', array('id_users' => $id_users, 'id_group' => $id, 'id_status' => 1));
			}
			return array('message'=>'SUCCESS', 'note'=>'');
		}
	}

	/* Fungsi untuk update data */
  public function updateDataUsers()
  {
    //get data
    $create_by   	= $this->app_loader->current_account();
    $create_date 	= date('Y-m-d H:i:s');
    $create_ip  	= $this->input->ip_address();
		$id_users			= escape($this->encryption->decrypt($this->input->post('users', TRUE)));
		$token   			= escape($this->input->post('token', TRUE));
		$username   	= escape($this->input->post('username', TRUE));
		$password 		= escape($this->input->post('conf_password', TRUE));
		$group				= escape($this->input->post('nama_group', TRUE));
		$id_rs       	= escape($this->input->post('rsud', TRUE));
		//get data rsud
		$this->db->where('id_rs', $id_rs);
		$dataRs = $this->db->get('ms_rs_rujukan')->row_array();
		$regency = !empty($dataRs) ? $dataRs['id_regency'] : escape($this->input->post('regency', TRUE));
		$data = array(
			'username'  	=> $username,
			'email' 	  	=> escape($this->input->post('email', TRUE)),
			'nama_user' 	=> escape($this->input->post('nama_user', TRUE)),
			'id_rs' 			=> $id_rs,
			'id_regency' 	=> $regency,
			'id_labor' 		=> escape($this->input->post('labor', TRUE)),
			'mod_by' 	  	=> $create_by,
			'mod_date'  	=> $create_date,
			'mod_ip'    	=> $create_ip,
			'blokir'    	=> escape($this->input->post('blokir', TRUE)),
			'id_status' 	=> escape($this->input->post('status', TRUE)),
		);
		if($password != "")
			$data = array_merge($data, array('password' => $this->bcrypt->hash_password($password)));
		//cek nama username biar tidak terjadi duplikat data
		$this->db->where('username', $username);
		$this->db->where('id_users !=', abs($id_users));
		$qTot = $this->db->count_all_results('xi_sa_users');
		if($qTot > 0)
			return array('message'=>'ERROR', 'note'=>$username);
		else {
			/*query update*/
			$this->db->where('id_users', abs($id_users));
			$this->db->where('token', $token);
			$this->db->update('xi_sa_users', $data);
			/*query update user password*/
			if($password != ""){
				$this->db->set('pass_plain', $password);
				$this->db->where('id_users', abs($id_users));
				$this->db->update('xi_sa_users_default_pass');
			}
			/*query update user group privileges*/
			$this->db->set('id_status', 0);
			$this->db->where('id_users', abs($id_users));
			$this->db->update('xi_sa_users_privileges');

			foreach ($group as $id) {
				$this->db->where('id_users', abs($id_users));
				$this->db->where('id_group', abs($id));
				$totGroup = $this->db->count_all_results('xi_sa_users_privileges');
				if($totGroup > 0){
					//update status group privileges jadi 1
					$this->db->set('id_status', 1);
					$this->db->where('id_users', abs($id_users));
					$this->db->where('id_group', abs($id));
					$this->db->update('xi_sa_users_privileges');
				} else
					$this->db->insert('xi_sa_users_privileges', array('id_users' => $id_users, 'id_group' => $id, 'id_status' => 1));
			}
			return array('message'=>'SUCCESS', 'note'=>'');
		}
  }

	public function deleteDataUsers()
	{
		$users = $this->input->post('checkid', TRUE);
		//jika ingin menghapus data lakukan looping
		foreach ($users as $id) {
			/*query delete*/
			$this->db->delete('xi_sa_users', array('id_users' => abs($id)));
			$this->db->delete('xi_sa_users_privileges', array('id_users' => abs($id)));
			$this->db->delete('xi_sa_users_default_pass', array('id_users' => abs($id)));
		}
		return TRUE;
	}
}

// This is the end of auth signin model

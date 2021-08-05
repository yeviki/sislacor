<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of users class
 *
 * @author Yogi "solop" Kaputra
 */

class Users extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_users' => 'muser'));
  }

	public function index()
	{
    $this->breadcrumb->add('Dashboard', site_url('home'));
    $this->breadcrumb->add('Manajemen', '#');
    $this->breadcrumb->add('Users', site_url('manajemen/users'));

		$this->session_info['page_name']    = "Users";
		$this->session_info['data_level']   = $this->muser->getDataLevelAkses();
		$this->session_info['data_regency'] = $this->muser->getDataRegency();
		$this->session_info['data_rsud'] 		= $this->muser->getDataHospital();
    $this->template->build('form_admin/list', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data = array();
			$session = $this->app_loader->current_account();
			if(isset($session)){
				$param = $this->input->post('param',TRUE);
		    $dataUser = $this->muser->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataUser as $key => $u) {
					$no++;
	        $row = array();
					$arrGroup = explode(',', $u['group_user']);
					$nm_group = '<ul style="margin-left:-30px;">';
					foreach ($arrGroup as $g) {
						$nm_group .= '<li>'.$g.'</li>';
					}
					$nm_group .= '</ul>';
					$password = ($this->app_loader->is_admin()) ? '<li><strong>Password :</strong> '.$u['pass_plain'].'</li>' : '';
					$row[] = '<input type="checkbox" name="checkid[]" value="'.$u['id_users'].'">';
	        $row[] = $no;
					$row[] = '<ul class="list-unstyled" style="margin-bottom:0px;">'.
		                  '<li><strong>Username :</strong> '.$u['username'].'</li>'.
		                   $password.
											'<li><strong>Nama :</strong> '.$u['nama_user'].'</li>'.
		                '</ul>';
	        $row[] = $nm_group;
					$row[] = $u['rs_rujukan'];
					$row[] = $u['regency'];
					$row[] = convert_blokir($u['blokir']);
					$row[] = convert_status($u['id_status']);
	        $row[] = '<a href="'.site_url('manajemen/users/update/'.$u['token']).'" type="button" class="btn btn-xs btn-orange confirm-edit"><i class="fa fa-pencil"></i> </a>';
	        $data[] = $row;
				}

				$output = array(
	        "draw" => $this->input->post('draw'),
	        "recordsTotal" => $this->muser->count_all(),
	        "recordsFiltered" => $this->muser->count_filtered($param),
	        "data" => $data,
	      );
			}
			//output to json format
	    echo json_encode($output);
		}
	}

	public function create()
  {
		if($this->input->post('save', TRUE))
      $this->createData();
    else
      $this->createForm();
  }

	private function createForm()
	{
		$this->breadcrumb->add('Dashboard', site_url('home'));
    $this->breadcrumb->add('Manajemen', '#');
    $this->breadcrumb->add('Users', site_url('manajemen/users'));
    $this->breadcrumb->add('Entri Baru', '#');

		$this->session_info['page_name'] 		= "Users - Entri Baru";
		$this->session_info['group_list']		= $this->muser->getDataGroup();
		$this->session_info['data_regency'] = $this->muser->getDataRegency();
		$this->session_info['data_rsud'] 		= $this->muser->getDataHospital();
		$this->session_info['data_labor'] 	= $this->muser->getDataLabor();
    $this->template->build('form_admin/add', $this->session_info);
	}

	private function createData()
	{
		if($this->muser->validasiDataValue("add") == FALSE) {
			error_message('danger', 'Peringatan!', 'Tolong dilengkapi form inputan dibawah...');
      $this->createForm();
    } else {
			$data = $this->muser->insertDataUsers();
			if($data['message'] == 'SUCCESS') {
				error_message('success', 'Sukses!', 'Data berhasil disimpan...');
				redirect('manajemen/users');
			} else {
				error_message('danger', 'Peringatan!', 'Username <b>"'.$data['note'].'"</b> yang anda inputkan sudah ada yang menggunakan, harap inputkan username yang lain...');
				$this->createForm();
			}
		}
	}

	public function update($id)
	{
		if(!isset($id))
      redirect('manajemen/users');

		if($this->input->post('save', TRUE))
      $this->updateData($id);
    else
      $this->updateForm($id);
	}

	private function updateForm($id)
	{
    $dataEdit = $this->muser->editDataUsers($id);
    if(count($dataEdit) == 0)
      redirect('manajemen/users/create');

		$this->breadcrumb->add('Dashboard', site_url('home'));
    $this->breadcrumb->add('Manajemen', '#');
    $this->breadcrumb->add('Users', site_url('manajemen/users'));
    $this->breadcrumb->add('Edit Data', '#');

		$this->session_info['page_name'] 	  = "Users - Edit Data";
		$this->session_info['data_users'] 	= $dataEdit;
		$this->session_info['group_list']		= $this->muser->getDataGroup();
		$this->session_info['data_regency'] = $this->muser->getDataRegency();
		$this->session_info['data_rsud'] 		= $this->muser->getDataHospital();
		$this->session_info['data_labor'] 	= $this->muser->getDataLabor();
		$this->session_info['urlform']		  = "manajemen/users/update/".$id;
    $this->template->build('form_admin/edit', $this->session_info);
	}

	private function updateData($id)
	{
		if($this->muser->validasiDataValue("edit") == FALSE) {
			error_message('danger', 'Peringatan!', 'Tolong dilengkapi form inputan dibawah...');
      $this->updateForm($id);
    } else {
			$data = $this->muser->updateDataUsers();
			if($data['message'] == 'SUCCESS') {
				error_message('success', 'Sukses!', 'Data berhasil disimpan...');
				redirect('manajemen/users');
			} else {
				error_message('danger', 'Peringatan!', 'Username <b>"'.$data['note'].'"</b> yang anda inputkan sudah ada yang menggunakan, harap inputkan username yang lain...');
				$this->updateForm($id);
			}
		}
	}

	public function delete()
	{
		$users = $this->input->post('checkid', TRUE);
		if(empty($users)) {
			error_message('danger', 'Peringatan!', 'Data harus dipilih terlebih dahulu jika ingin dihapus..');
			redirect('manajemen/users');
		} else
      $this->deleteData();
	}

	private function deleteData()
	{
		$data = $this->model_users->deleteDataUsers();
		error_message('success', 'Sukses!', 'Data berhasil dihapus.');
		redirect('manajemen/users');
	}

}

// This is the end of users class

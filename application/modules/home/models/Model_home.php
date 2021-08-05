<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of home model
 *
 * @author Yogi "solop" Kaputra
 */

class Model_home extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getDataListNotification($limit, $offset)
	{
		$this->db->select('a.token,
											 a.sender_id,
											 a.recipient_id,
											 a.type,
											 a.parameters,
											 a.reference,
											 a.unread,
											 DATE_FORMAT(a.create_date, "%d/%m/%Y %h:%i %p") AS create_date,
											 b.nama_user,
											 (CASE a.type
											 	WHEN "otg.new" THEN "verifikasi-kasus/kasus-otg"
												WHEN "odp.new" THEN "verifikasi-kasus/kasus-odp"
												WHEN "otg.verified" THEN "otg"
												WHEN "odp.verified" THEN "odp"
												WHEN "case.new" THEN "verifikasi-kasus/kasus"
												WHEN "case.ref" THEN CONCAT("konfirmasi-kasus/identifikasi/review/", a.parameters)
												WHEN "swab.new" THEN "spesimen/pengujian"
												WHEN "swab.result" THEN "konfirmasi-kasus/spesimen"
												WHEN "case.verified" THEN CONCAT("konfirmasi-kasus/identifikasi/review/", a.parameters)
												WHEN "data.new" THEN "rekapitulasi/daily"
												ELSE "home"
											 END) AS url');
		$this->db->from('ta_notification a');
		$this->db->join('xi_sa_users b', 'a.sender_id = b.username', 'inner');
		if(!$this->app_loader->is_admin()) {
			$this->db->where('a.recipient_id', $this->app_loader->current_account());
		}
		$this->db->order_by('a.create_date DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getCountAllNotification()
	{
		if(!$this->app_loader->is_admin()) {
			$this->db->where('recipient_id', $this->app_loader->current_account());
		}
		return $this->db->count_all_results('ta_notification');
	}

	public function updateDataNotificationAll()
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		//update notifikasi
		$this->db->set('unread', 2);
		$this->db->set('mod_by', $create_by);
		$this->db->set('mod_date', $create_date);
		$this->db->set('mod_ip', $create_ip);
		$this->db->where('unread', 1);
		if(!$this->app_loader->is_admin()) {
			$this->db->where('recipient_id', $this->app_loader->current_account());
		}
		$this->db->update('ta_notification');
		return TRUE;
	}

	public function updateDataNotificationByToken($token)
	{
		$create_by    = $this->app_loader->current_account();
		$create_date 	= gmdate('Y-m-d H:i:s', time()+60*60*7);
		$create_ip    = $this->input->ip_address();
		//update notifikasi
		$this->db->set('unread', 2);
		$this->db->set('mod_by', $create_by);
		$this->db->set('mod_date', $create_date);
		$this->db->set('mod_ip', $create_ip);
		$this->db->where('token', $token);
		$this->db->where('unread', 1);
		if(!$this->app_loader->is_admin()) {
			$this->db->where('recipient_id', $this->app_loader->current_account());
		}
		$this->db->update('ta_notification');
		return TRUE;
	}

	public function deleteDataNotification()
	{
		$token = $this->input->post('checkid');
		foreach ($token as $key => $t) {
			$this->db->where('token', $t);
			$this->db->delete('ta_notification');
		}
		return TRUE;
	}
}

// This is the end of auth signin model

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of model odp
 *
 * @author Yogi "solop" Kaputra
 */

class Model_master extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getDataStudy()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ref_pendidikan');
    $dd_study[''] = 'Pilih Pendidikan';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_study[$row['id']] = $row['study'];
      }
    }
    return $dd_study;
  }

	public function getDataTenagaMedis()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ref_tenaga_medis');
    $dd_medis[''] = 'Pilih Tenaga Kesehatan';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_medis[$row['id']] = $row['name'];
      }
    }
    return $dd_medis;
  }

	public function getDataNegara()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('wa_negara');
    $dd_negara[''] = 'Pilih Negara';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_negara[$row['name']] = $row['name'];
      }
    }
    return $dd_negara;
  }

	public function getDataProvince()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('wa_province');
    $dd_prov[''] = 'Pilih Provinsi';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_prov[$row['id']] = $row['name'];
      }
    }
    return $dd_prov;
  }

	public function getDataRegency()
  {
		$this->db->where('province_id', '13');
		$this->db->order_by('status ASC');
		$this->db->order_by('name ASC');
		$query = $this->db->get('wa_regency');
    $dd_reg[''] = 'Pilih Kab/Kota';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_reg[$row['id']] = ($row['status'] == 1) ? "KAB ".$row['name'] : $row['name'];
      }
    }
    return $dd_reg;
  }

  public function getDataRegencyByProvince($id)
  {
		$this->db->where('province_id', $id);
		$this->db->order_by('name ASC');
		$this->db->order_by('status ASC');
		$query = $this->db->get('wa_regency');
    return $query->result_array();
  }

  public function getDataDistrictByRegency($id)
  {
		$this->db->where('regency_id', $id);
		$this->db->order_by('id ASC');
		$query = $this->db->get('wa_district');
    return $query->result_array();
  }

  public function getDataVillageByDistrict($id)
  {
		$this->db->where('district_id', $id);
		$this->db->order_by('id ASC');
		$query = $this->db->get('wa_village');
    return $query->result_array();
  }

  public function getDataMasterGejala()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ms_gejala');
    return $query->result_array();
  }

  public function getDataMasterKomorbiditas()
  {
		$this->db->order_by('field(tp_field, "checlbox", "select", "text", "number") ASC');
		$this->db->order_by('title ASC');
		$query = $this->db->get('ms_komorbiditas');
    return $query->result_array();
  }

	public function getDataMasterDiagnosis()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ms_diagnosis');
    return $query->result_array();
  }

	public function getDataMasterRsRujukan($id=0)
  {
		$this->db->where('status', '1');
		$this->db->where('flag', '1');
		if($id!=0)
			$this->db->where('id_rs !=', $id);
		$this->db->order_by('id_rs ASC');
		$query = $this->db->get('ms_rs_rujukan');
    $dd_rs[''] = 'Pilih Rumah Sakit';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_rs[$row['id_rs']] = $row['shortname'].' ['.$row['kode_fasyankes'].']';
      }
    }
    return $dd_rs;
  }

	public function getDataMasterHospital()
  {
		$this->db->where('status', '1');
		$this->db->order_by('id_rs ASC');
		$query = $this->db->get('ms_rs_rujukan');
    $dd_rs[''] = 'Pilih Rumah Sakit';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_rs[$row['id_rs']] = $row['shortname'].' ['.$row['kode_fasyankes'].']';
      }
    }
    return $dd_rs;
  }

	public function getDataMasterHospitalById($id)
  {
		$this->db->where('id_rs', $id);
		$this->db->where('status', '1');
		$this->db->order_by('id_rs ASC');
		$query = $this->db->get('ms_rs_rujukan');
    $dd_rs[''] = 'Pilih Rumah Sakit';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_rs[$row['id_rs']] = $row['shortname'].' ['.$row['kode_fasyankes'].']';
      }
    }
    return $dd_rs;
  }

	public function getDataMasterFasilitasPemda()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ref_fasiltas_pemda');
    $dd_fp[''] = 'Pilih Lokasi';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_fp[$row['id']] = $row['name'];
      }
    }
    return $dd_fp;
  }

	public function getDataMasterLaboratorium()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ms_laboratorium');
    $dd_lab[''] = 'Pilih Laboratorium';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_lab[$row['id']] = $row['name'].' ['.$row['kode'].']';
      }
    }
    return $dd_lab;
  }

	public function getDataMasterSpesimen()
  {
		$this->db->order_by('id ASC');
		$query = $this->db->get('ref_spesimen');
    $dd_sp[''] = 'Pilih Spesimen';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_sp[$row['id']] = $row['name'];
      }
    }
    return $dd_sp;
  }

  public function getDataPenyalur()
  {
		$this->db->order_by('id_penyalur ASC');
		$query = $this->db->get('ref_penyalur');
    $dd_penyalur[''] = 'Pilih Penyalur';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_penyalur[$row['id_penyalur']] = $row['nm_penyalur'];
      }
    }
    return $dd_penyalur;
  }

  public function getDataJenisVaksin()
  {
		$this->db->order_by('id_jenis_vaksin ASC');
		$query = $this->db->get('ref_jenis_vaksin');
    $dd_vaksin[''] = 'Pilih Jenis Vaksin';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_vaksin[$row['id_jenis_vaksin']] = $row['nm_vaksin'];
      }
    }
    return $dd_vaksin;
  }

  public function getDataSuplai()
  {
		$this->db->select('DISTINCT(a.id_jenis_vaksin), a.regency_id,
                      ((SELECT SUM(b.total_suplai) FROM ta_suplai_vaksin b WHERE b.regency_id = a.regency_id AND b.id_jenis_vaksin = a.id_jenis_vaksin) -
                      IFNULL ((SELECT SUM(c.total_vaksinasi) FROM ta_capaian_vaksin c, ta_suplai_vaksin d WHERE c.id_suplai_vaksin = d.id_suplai_vaksin AND d.regency_id = a.regency_id AND d.id_jenis_vaksin = a.id_jenis_vaksin),0)) AS total_suplai,
                      a.id_suplai_vaksin,
                      a.id_jenis_vaksin,
                      a.id_penyalur,
                      a.tanggal_suplai,
                      a.regency_id,
                      e.nm_vaksin,
                      f.nm_penyalur
								');
		$this->db->join('ref_jenis_vaksin e', 'e.id_jenis_vaksin = a.id_jenis_vaksin', 'inner');
    $this->db->join('ref_penyalur f', 'f.id_penyalur = a.id_penyalur', 'inner');
    $this->db->group_by('a.regency_id');
    $this->db->group_by('a.id_jenis_vaksin');
    $query = $this->db->get('ta_suplai_vaksin a');
    // echo $this->db->last_query();die;
    $dd_suplai_vaksin[''] = 'Pilih Suplai';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_suplai_vaksin[$row['id_suplai_vaksin']] = regency($row['regency_id']) .' - '. $row['nm_vaksin'] .' - '. format_ribuan($row['total_suplai']);
      }
    }
    return $dd_suplai_vaksin;
  }

  public function getDataKamar()
  {
		$this->db->order_by('id_kat_kamar ASC');
		$query = $this->db->get('ref_kat_kamar');
    $dd_kamar[''] = 'Pilih Kategori Kamar';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_kamar[$row['id_kat_kamar']] = $row['nm_kamar'];
      }
    }
    return $dd_kamar;
  }

  public function getDataStokKamar()
  {
    $this->db->select('DISTINCT(a.id_kat_kamar), a.id_rs,
                      ((SELECT SUM(b.total_kamar) FROM ta_rs_kamar b WHERE b.id_rs = a.id_rs AND b.id_kat_kamar = a.id_kat_kamar) - 
                      IFNULL ((SELECT SUM(c.total_terpakai) 
                      FROM ta_pemakaian_kamar c, ta_rs_kamar d 
                      WHERE c.id_rs_kamar=d.id_rs_kamar AND d.id_rs=a.id_rs ), 0)) AS total_kamar,
                      a.id_rs_kamar,
                      a.id_rs,
                      a.id_kat_kamar,
                      a.tanggal,
                      c.fullname,
                      d.nm_kamar
								');
		$this->db->join('ms_rs_rujukan c', 'a.id_rs = c.id_rs', 'inner');
    $this->db->join('ref_kat_kamar d', 'd.id_kat_kamar = a.id_kat_kamar', 'inner');
    $this->db->group_by('a.id_rs');
    $this->db->group_by('a.id_kat_kamar');
    $query = $this->db->get('ta_rs_kamar a');
    // echo $this->db->last_query();die;
    $dd_kamar[''] = 'Pilih RS Kamar';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_kamar[$row['id_rs_kamar']] = $row['fullname'] .' - '. $row['nm_kamar'] .' - '. format_ribuan($row['total_kamar']);
      }
    }
    return $dd_kamar;
  }


  public function getDataKatTabung()
  {
		$this->db->order_by('id_kat_tabung ASC');
		$query = $this->db->get('ref_kat_tabung');
    $dd_tabung[''] = 'Pilih Kategori Tabung';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_tabung[$row['id_kat_tabung']] = $row['nm_tabung'];
      }
    }
    return $dd_tabung;
  }

  public function getDataStokTabung()
  {
    $this->db->select('DISTINCT(a.id_kat_tabung), a.id_rs,
                      ((SELECT SUM(b.total_stok_tabung) FROM ta_stok_tabung b WHERE b.id_rs = a.id_rs AND b.id_kat_tabung = a.id_kat_tabung) - 
                      IFNULL ((SELECT SUM(c.total_terpakai) 
                      FROM ta_pemakaian_tabung c, ta_stok_tabung d 
                      WHERE c.id_stok_tabung=d.id_stok_tabung AND d.id_rs=a.id_rs ), 0)) AS total_stok_tabung,
                      a.id_stok_tabung,
                      a.id_rs,
                      a.id_kat_tabung,
                      a.tanggal,
                      e.fullname,
                      f.nm_tabung
								');
		$this->db->join('ms_rs_rujukan e', 'a.id_rs = e.id_rs', 'inner');
    $this->db->join('ref_kat_tabung f', 'f.id_kat_tabung = a.id_kat_tabung', 'inner');
    $this->db->group_by('a.id_rs');
    $this->db->group_by('a.id_kat_tabung');
    $query = $this->db->get('ta_stok_tabung a');
    // echo $this->db->last_query();die;
    $dd_kamar[''] = 'Pilih Tabung';
    if ($query->num_rows() > 0) {
      foreach ($query->result_array() as $row) {
        $dd_kamar[$row['id_stok_tabung']] = format_ribuan($row['total_stok_tabung']) .' - '. $row['nm_tabung'] .' - '. $row['fullname'];
      }
    }
    return $dd_kamar;
  }

}

// This is the end of auth signin model

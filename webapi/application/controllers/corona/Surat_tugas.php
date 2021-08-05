<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Surat_tugas extends REST_Controller {

  function __construct()
  {
    // Construct the parent class
    parent::__construct();
    $this->load->model(array('model_spt' => 'mspt'));
    $this->methods['spt_get']['limit'] = 500;
  }

  public function list_get()
  {
    $pegawai = $this->get('id');
    $status  = $this->get('status');
    // Validate the id.
    if (empty($pegawai)) {
      $this->response([
          'response' => 0,
          'result' => 'No data were found'
      ], REST_Controller::HTTP_OK);
    } else {
      //get data pegawai
      $dataAsn = $this->mspt->getDataPegawai($pegawai);
      $sub_opd = !empty($dataAsn) ? $dataAsn['id_sub_opd'] : 0;
      //get data kegiatan
      $dataSpt = $this->mspt->getDataListSptSign($pegawai, $sub_opd, ($status === NULL) ? '' : $status);
      $row  = array();
      $data = array();
      foreach ($dataSpt as $key => $ds) {
        $tujuan = ($ds['kategori'] == 'DL') ? nama_tujuan($ds['id_provinsi'], $ds['id_daerah'], $ds['tujuan'], $ds['provinsi_dua'], $ds['daerah_dua'], $ds['tujuan_dua'], $ds['kategori']) : $ds['tujuan'].' '.regency_lokal(explode(',', $ds['region']));
        $row['sptid']       = $ds['token'];
        $row['nmopd']       = $ds['nm_opd'];
        $row['tahun']       = $ds['tahun'];
        $row['bulan']       = $ds['bulan'];
        $row['tglspt']      = tgl_indo($ds['tgl_terbit']);
        $row['nospt']       = ($ds['no_spt'] == '') ? 'Belum Ada Nomor' : $ds['no_front'].'/'.$ds['no_urut'].'/'.$ds['no_back'];
        $row['kategori']    = $ds['kategori'];
        $row['nmkategori']  = ($ds['kategori'] == 'DL') ? 'Luar Daerah' : (($ds['kategori'] == 'DD') ? 'Dalam Daerah' : 'Dalam Kota');
        $row['maksud']      = $ds['maksud_spt'].' '.$tujuan;
        $row['pergi']       = $ds['tgl_pergi'];
        $row['pulang']      = $ds['tgl_pulang'];
        $row['status']      = $ds['status'];
        $row['nmstatus']    = ($ds['status'] == 'ND') ? 'Surat Tugas Belum Diperiksa' : (($ds['status'] == 'AP') ? 'Surat Tugas Disetujui' : 'Surat Tugas Ditolak');
        $data[] = $row;
      }
      // Check if the users data store contains users (in case the database result returns NULL)
      // if(count($data) > 0) {
        // Set the response and exit
        $this->response([
          'response' => 1,
          'result' => $data
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
      // } else {
      //   // Set the response and exit
      //   $this->response([
      //     'response' => 0,
      //     'result' => 'No data were found'
      //   ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
      // }
    }
  }

  public function detail_get()
  {
    $pegawai = $this->get('id');
    $tahun   = $this->get('tahun');
    $type    = $this->get('tipe');
    $token   = $this->get('token');
    if (empty($pegawai) OR $tahun == '' OR $type == '' OR empty($token)) {
      $this->response([
        'response' => 0,
        'result' => 'No data were found',
        'pegawai' => '',
        'dasar' => '',
        'riwayat' => ''
      ], REST_Controller::HTTP_OK);
    } else {
      //get data pegawai
      $dataAsn = $this->mspt->getDataPegawai($pegawai);
      $sub_opd = !empty($dataAsn) ? $dataAsn['id_sub_opd'] : 0;
      $id_asn  = !empty($dataAsn) ? $dataAsn['id_pegawai'] : 0;
      $dataSpt = $this->mspt->getDataDetailSptSign($pegawai, $sub_opd, $tahun, $token, $type);
      $id_spt  = !empty($dataSpt) ? $dataSpt['id_spt'] : 0;
      $nm_opd  = !empty($dataSpt) ? strtoupper($dataSpt['nm_opd']) : '';
      $thn_spt = !empty($dataSpt) ? $dataSpt['tahun'] : '';
      $signer  = !empty($dataSpt) ? $dataSpt['signer'] : '';
      $dataSign = $this->mspt->getDataSignitureAsn($id_asn, ($signer == 1) ? $sub_opd : '');
      $tujuan = ($type == 'DL') ? nama_tujuan($dataSpt['id_provinsi'], $dataSpt['id_daerah'], $dataSpt['tujuan'], $dataSpt['provinsi_dua'], $dataSpt['daerah_dua'], $dataSpt['tujuan_dua'], $type) : $dataSpt['tujuan'].' '.regency_lokal(explode(',', $dataSpt['region']));
      $data = array(
        'idspt'        => !empty($dataSpt) ? $dataSpt['token'] : '',
        'kategori'     => $type,
        'opd'          => !empty($dataSpt) ? $dataSpt['id_opd'] : '',
        'sub_opd'      => !empty($dataSpt) ? $dataSpt['id_sub_opd'] : '',
        'nm_opd'       => $nm_opd,
        'tgl_spt'      => !empty($dataSpt) ? tgl_indo($dataSpt['tgl_terbit']) : '',
        'bulan'        => !empty($dataSpt) ? $dataSpt['bulan'] : '',
        'nmbulan'      => !empty($dataSpt) ? bulan($dataSpt['bulan']) : '',
        'tahun'        => !empty($dataSpt) ? $dataSpt['tahun'] : '',
        'no_spt'       => ($dataSpt['no_spt'] == '') ? 'Belum Ada Nomor' : $dataSpt['no_front'].'/'.$dataSpt['no_urut'].'/'.$dataSpt['no_back'],
        'nm_kategori'  => ($dataSpt['kategori'] == 'DL') ? 'Luar Daerah' : (($dataSpt['kategori'] == 'DD') ? 'Dalam Daerah' : 'Dalam Kota'),
        'maksud_spt'   => !empty($dataSpt) ? $dataSpt['maksud_spt'].' '.$tujuan : '',
        'tgl_dinas'    => !empty($dataSpt) ? tgl_dinas($dataSpt['tgl_pergi'], $dataSpt['tgl_pulang']) : '',
        'kendaraan'    => !empty($dataSpt) ? kendaraan($dataSpt['kendaraan']).' '.(($dataSpt['kendaraan'] == 'KD') ? str_replace('.',' ',$dataSpt['plat_no']) : '') : '',
        'pembiayaan'   => !empty($dataSpt) ? $dataSpt['pembiayaan'].' / '.$dataSpt['nm_kegiatan'] : '',
        'pemeriksa'    => !empty($dataSpt) ? $dataSpt['pemeriksa'] : '',
        'status'       => !empty($dataSpt) ? $dataSpt['status_sign'] : '',
        'nmstatus'     => !empty($dataSpt) ? (($dataSpt['status_sign'] == 'ND') ? 'Surat Tugas Belum Diperiksa' : (($dataSpt['status_sign'] == 'AP') ? 'Surat Tugas Disetujui' : 'Surat Tugas Ditolak')) : '',
        'have_tte'     => (count($dataSign) > 0) ? 'TRUE' : 'FALSE'
      );
      $dataPegawai = $this->mspt->getDataListSppdPegawai($tahun, $token, $type);
      $arr_sppd = array();
      $item = array();
      foreach ($dataPegawai as $rows => $dp) {
        $item['id_asn']   = $dp['token_ref'];
			  $item['nama_asn'] = nama_pns($dp['gelar_d'], $dp['nama_lengkap'], $dp['gelar_b']);
  			$item['nip_asn']	= ($dp['nip'] != '') ? 'NIP. '.nip($dp['nip']) : '';
  			$item['pangkat']	= ($dp['id_pangkat'] != 0) ? pangkat_golongan($dp['id_pangkat']) : '';
  			$item['eselon']		= ($dp['id_eselon'] != 99 AND $dp['id_eselon'] != 0) ? 'Eselon '.eselon($dp['id_eselon']) : '';
  			$item['instansi'] = strtoupper($dp['nm_opd']);
  			$item['jabatan']	= strtoupper($dp['jabatan']);
  			//$item['tothari']  = $this->msurat->getTotalHariSppd($id_spt, $ds['id_pegawai'], $bulan);
  			$arr_sppd[] = $item;
      }
      /*Dasar SPT*/
      $dataDasar = $this->mspt->getDataDasarSpt($id_spt, $thn_spt);
      $dasar = array();
      if(count($dataDasar) > 0) {
        foreach ($dataDasar as $key => $dd) {
          array_push($dasar, $dd['dasar_spt']);
        }
        array_push($dasar, 'DPA-OPD '.ucwords_title($nm_opd).' Provinsi Sumatera Barat Tahun Anggaran '.$thn_spt);
      } else {
        $dasar = array('DPA-OPD '.ucwords_title($nm_opd).' Provinsi Sumatera Barat Tahun Anggaran '.$thn_spt);
      }
      /*Riwayat SPT*/
      $dataRiwayat = $this->mspt->getDataNoteSpt($id_spt, $thn_spt);
      $note = array(); $row = array(); $i=0; $ket = '';
      foreach ($dataRiwayat as $key => $dr) {
        if($dr['flag'] == 0) {
          $ket = !empty($dataRiwayat[$i-1]['jabatan']) ? $dataRiwayat[$i-1]['jabatan'] : '';
          $row['teruskan'] = ($dr['status'] != 'ND') ? (($dr['status'] == 'AP') ? 'Surat tugas diteruskan ke' : 'Surat tugas dikembalikan ke').' '.ucwords_title(strtolower($ket)) : '';
        } else {
          $row['teruskan'] = ($dr['status'] == 'AP') ? 'Surat tugas telah disetujui. Pemeriksaan surat tugas perjalan dinas telah selesai.' : '';
        }
        $row['jabatan'] = $dr['jabatan'];
        $row['tanggal'] = tgl_login($dr['create_date']);
        $row['status']  = ($dr['status'] == 'ND') ? (($dr['is_maker'] == 1) ? 'Surat tugas belum diajukan' : 'Surat tugas belum diperiksa') : (($dr['is_maker'] == 1) ? 'Diteruskan' : 'Diperiksa').' : '.tgl_login($dr['mod_date']);
        $row['tindakan'] = ($dr['status'] == 'AP') ? 'Disetujui' : (($dr['status'] == 'RJ') ? 'Tidak Disetujui' : '');
        $row['catatan']  = ($dr['catatan'] != '') ? $dr['catatan'] : 'Belum ada catatan pemeriksaan';
        $note[] = $row;
        $i++;
      }

      if(count($dataSpt) > 0) {
        // Set the response and exit
        $this->response([
          'response' => 1,
          'result' => $data,
          'pegawai' => $arr_sppd,
          'dasar' => $dasar,
          'riwayat' => $note
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
      } else {
        // Set the response and exit
        $this->response([
          'response' => 0,
          'result' => 'No data were found',
          'pegawai' => '',
          'dasar' => '',
          'riwayat' => ''
        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
      }
    }
  }

  public function approve_post()
  {
    //get data post
    $pegawai  = $this->post('id', TRUE);
    $username = $this->post('user', TRUE);
    if(empty($pegawai) OR empty($username)) {
      $this->response([
        'response' => 0,
        'result' => 'Proses setujui surat tugas gagal...'
      ], REST_Controller::HTTP_OK);
    } else {
  		$token    = $this->post('token', TRUE);
  		$type     = $this->post('tipe', TRUE);
  		$tahun    = $this->post('tahun', TRUE);
  		$catatan  = $this->post('catatan', TRUE);
      $passkey  = $this->post('passkey', TRUE);
      $device   = !empty($this->post('deviceImei', TRUE)) ? $this->post('deviceImei', TRUE) : '';
      $data = $this->mspt->approveDataSuratTugas($pegawai, $username, $tahun, $token, $type, $catatan, $passkey, $device);
      if($data == 'NOSPT') {
        $this->response([
          'response' => 0,
          'result' => 'Surat tugas yang akan disetujui tidak ditemukan...',
        ], REST_Controller::HTTP_OK);
      } else if($data == 'NOPASS') {
        $this->response([
          'response' => 0,
          'result' => 'Passkey harus diisi...',
        ], REST_Controller::HTTP_OK);
      } else if($data == 'ERRP12') {
        $this->response([
          'response' => 0,
          'result' => 'Sertifikat tanda tangan digital anda tidak ditemukan...',
        ], REST_Controller::HTTP_OK);
      } else if($data == 'ERRPASSKEY') {
        $this->response([
          'response' => 0,
          'result' => 'Password tanda tangan digital yang anda masukan salah...',
        ], REST_Controller::HTTP_OK);
      } else if($data == 'SUCCESS') {
        $this->response([
          'response' => 1,
          'result' => 'Surat tugas berhasil disetujui...',
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'response' => 0,
          'result' => 'Proses persetujuan surat tugas gagal....',
        ], REST_Controller::HTTP_OK);
      }
    }
  }

  public function reject_post()
  {
    //get data post
    $pegawai  = $this->post('id', TRUE);
    $username = $this->post('user', TRUE);
    if(empty($pegawai) OR empty($username)) {
      $this->response([
        'response' => 0,
        'result' => 'Proses tolak nota dinas gagal...'
      ], REST_Controller::HTTP_OK);
    } else {
  		$token    = $this->post('token', TRUE);
  		$type     = $this->post('tipe', TRUE);
  		$tahun    = $this->post('tahun', TRUE);
      $catatan  = $this->post('catatan', TRUE);
      $device   = !empty($this->post('deviceImei', TRUE)) ? $this->post('deviceImei', TRUE) : '';
      $data = $this->mspt->rejectDataSuratTugas($pegawai, $username, $tahun, $token, $type, $catatan, $device);
      if($data) {
        $this->response([
          'response' => 1,
          'result' => 'Nota dinas berhasil ditolak...'
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'response' => 0,
          'result' => 'Proses tolak nota dinas gagal...'
        ], REST_Controller::HTTP_OK);
      }
    }
  }

}

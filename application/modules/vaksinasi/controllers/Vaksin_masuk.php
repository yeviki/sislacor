<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of Vaksin Masuk class
 *
 * @author Yogi "solop" Kaputra
 */

class Vaksin_masuk extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'vaksinasi/vaksin-masuk';
		$this->load->model(array('model_vaksin_masuk' => 'mVaksinMasuk', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Vaksinasi', '#');
		$this->breadcrumb->add('Vaksin Masuk', '#');

		$this->session_info['page_name'] 			= "Vaksin Masuk";
		$this->session_info['list_penyalur']    	= $this->mmas->getDataPenyalur();
		$this->session_info['list_jenis_vaksin']    = $this->mmas->getDataJenisVaksin();

    	$this->template->build('vaksin_masuk/vlist', $this->session_info);
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
		    	$dataList = $this->mVaksinMasuk->get_datatables($param);
				$no = $this->input->post('start');
				foreach ($dataList as $key => $dl) {
					$no++;
					$row = array();
					$row[] = $no;
							$row[] = $dl['tanggal'];
							$row[] = format_ribuan($dl['total_stok']);
							$row[] = $dl['nm_vaksin'];
							$row[] = $dl['nm_penyalur'];
					$row[] = '<button type="button" class="btn btn-xs btnEdit" data-id="'.$this->encryption->encrypt($dl['id_stok_masuk']).'" title="Edit"><i class="fa fa-pencil"></i> </button>
					<button type="button" class="btn btn-xs btn-danger btnDelete" data-id="'.$this->encryption->encrypt($dl['id_stok_masuk']).'" title="Delete"><i class="fa fa-times"></i> </button>';
					$data[] = $row;
				}

				$output = array(
					"draw" => $this->input->post('draw'),
					"recordsTotal" => $this->mVaksinMasuk->count_all(),
					"recordsFiltered" => $this->mVaksinMasuk->count_filtered($param),
					"data" => $data,
				);
			}
			//output to json format
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
	}

	public function create()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			if(!empty($session)) {
				if($this->mVaksinMasuk->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mVaksinMasuk->insertData();
					if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data vaksin masuk pada tanggal <b>'.$data['tanggal'].'</b> berhasil ditambahkan...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses input data TPA gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function details()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  		= $this->app_loader->current_account();
			$csrfHash 		= $this->security->get_csrf_hash();
			$id_stok_masuk  = $this->input->post('vaksinId', TRUE);
			if(!empty($id_stok_masuk) AND !empty($session)) {
				$data = $this->mVaksinMasuk->getDataDetail($this->encryption->decrypt($id_stok_masuk));
				$row = array();
				$row['total_stok']			=	!empty($data) ? $data['total_stok'] : '';
				$row['id_jenis_vaksin']		=	!empty($data) ? $data['id_jenis_vaksin'] : '';
				$row['id_penyalur']			=	!empty($data) ? $data['id_penyalur'] : '';
				$row['tanggal']				= 	!empty($data) ? date('d/m/Y', strtotime($data['tanggal'])) : '';

				$result = array('status' => 1, 'message' => $row, 'csrfHash' => $csrfHash);
			} else {
				$result = array('status' => 0, 'message' => array(), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function update()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			$id_stok_masuk  = $this->input->post('vaksinId', TRUE);
			if(!empty($session) AND !empty($id_stok_masuk)) {
				if($this->mVaksinMasuk->validasiDataValue() == FALSE) {
					$result = array('status' => 0, 'message' => $this->form_validation->error_array(), 'csrfHash' => $csrfHash);
				} else {
					$data = $this->mVaksinMasuk->updateData();
					if($data['message'] == 'NODATA') {
						$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, data yang akan diupdate tidak ditemukan. Mohon diperiksa kembali data yang akan diupdate...'), 'csrfHash' => $csrfHash);
					} else if($data['message'] == 'SUCCESS') {
						$result = array('status' => 1, 'message' => 'Data dengan <b>'.$data['tanggal'].'</b> berhasil diperbaharui...', 'csrfHash' => $csrfHash);
					}
				}
			} else {
				$result = array('status' => 0, 'message' => array('isi' => 'Proses update data gagal, mohon periksa data kembali...'), 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function delete()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  		= $this->app_loader->current_account();
			$csrfHash 		= $this->security->get_csrf_hash();
			$id_stok_masuk 	= escape($this->input->post('vaksinId', TRUE));
			if(!empty($session) AND !empty($id_stok_masuk)) {
				$data = $this->mVaksinMasuk->deleteData();
				if($data['message'] == 'ERROR') {
					$result = array('status' => 0, 'message' => 'Proses delete data gagal dikarenakan data tidak ditemukan...', 'csrfHash' => $csrfHash);
				}	else if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => 'Data telah didelete...', 'csrfHash' => $csrfHash);
				}
			} else {
				$result = array('status' => 0, 'message' => 'Proses delete data gagal...', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function tiket($token)
	{
		$create_by    	= $this->app_loader->current_account();
		// $dataGet 		= $this->mInve->getUserlogin($create_by);
		// $nama_user 		= !empty($dataGet) ? $dataGet['nama_user'] : '';
		require APPPATH . 'third_party/php_word/vendor/autoload.php';

		$template = 'repository/template/Report_Tiket.docx';

		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template);
		//ambil data
		$dataView		= $this->mVaksinMasuk->getDataTiket($token);

		// Data Peserta
		$templateProcessor->setValue('token', $dataView['token']);
		$templateProcessor->setValue('no_tiket', $dataView['no_tiket']);
		$templateProcessor->setValue('plat_no', $dataView['plat_no']);
		$templateProcessor->setValue('nm_supir', $dataView['nm_supir']);
		$templateProcessor->setValue('jam_msk', $dataView['jam_masuk']);
		$templateProcessor->setValue('jam_klr', $dataView['jam_keluar']);
		$templateProcessor->setValue('berat_msk', $dataView['berat_masuk']);
		$templateProcessor->setValue('berat_klr', $dataView['berat_keluar']);
		$templateProcessor->setValue('berat_brs', $dataView['berat_bersih']);
		$templateProcessor->setValue('nm_kondisi', $dataView['nm_kondisi']);
		$templateProcessor->setValue('biaya', $dataView['biaya']);
		$templateProcessor->setValue('petugas', $create_by);
		$templateProcessor->setValue('name_regency', regency($dataView['id_regency']));
		$templateProcessor->setValue('tanggal', date('d/m/Y', strtotime($dataView['tanggal'])));

		// $filename   = "tiket_tgl_".date("d")."_bln_".date("m")."_thn_".date("Y");
		$file = 'Tiket.docx';
		$templateProcessor->saveAs($file);

		if(!$file)
		die('File not found');
		else
		{
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$file");
		header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
		header("Content-Transfer-Encoding: binary");

		readfile($file);
		}
		unlink($file);

		//============================================================+
		// END OF FILE
		//============================================================+
	}

	public function export_to_excel()
	{
		require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$template  = 'repository/template/timbangan_report.xls';
		$objPHPExcel = $objReader->load($template);
		//get data
		$lokasi			= escape($this->input->get('lokasi', TRUE));
		$suplier		= escape($this->input->get('suplier', TRUE));
		$tgl_range		= escape($this->input->get('tgl_range', TRUE));
		$dataTimbangan 	= $this->mVaksinMasuk->getDataListTimbanganReport($lokasi, $suplier, $tgl_range);

		//set title
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:K2');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'DATA TIMBANGAN');
		//set data TPA
		$noRow = 0;
		$baseRow = 6;
		$total_biaya = array();
		$total_masuk = array();
		$total_keluar = array();
		$total_bersih = array();
		if(count($dataTimbangan) > 0) {
			foreach ($dataTimbangan as $key => $dh) {
				$total_biaya[] = $dh['biaya'];
				$total_masuk[] = $dh['berat_masuk'];
				$total_keluar[] = $dh['berat_keluar'];
				$total_bersih[] = $dh['berat_bersih'];

				$noRow++;
				$row = $baseRow + $noRow;
				$objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row,1);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $noRow);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, $dh['no_tiket']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, $dh['tanggal']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $dh['nm_supir']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $dh['name']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, $dh['jam_masuk']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $dh['jam_keluar']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row, $dh['berat_masuk']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row, $dh['berat_keluar']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row, $dh['berat_bersih']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, $dh['biaya']);
			}
		} else {
			$row = $baseRow + 1;
			$objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row,1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, '');
		}
		$objPHPExcel->setActiveSheetIndex(0)->removeRow($baseRow,1);
        $tot_masuk = array_sum($total_masuk);
        $tot_keluar = array_sum($total_keluar);
        $tot_bersih = array_sum($total_bersih);
        $tot_biaya = array_sum($total_biaya);
//		$average = array_sum($total_biaya)/count($total_biaya);
//		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $tot_masuk);
//		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row, $tot_keluar);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, floor($tot_bersih/1000)*1000);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.($row+1), $tot_biaya);
//		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($row+1), $average);
		$file	= 'data_timbangan_report.xlsx';
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=$file");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

}

// This is the end of home class

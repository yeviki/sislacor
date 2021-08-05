<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of OTG class
 *
 * @author Yogi "solop" Kaputra
 */

class Daily extends SLP_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model(array('model_publish' => 'mpub'));
  }

	public function index()
	{
		if(date('H:i:s') > waktu_input()) {
			$jadwal = tgl_indo(date('Y-m-d')).' '.waktu_publish().' WIB';
		} else {
			$jadwal = tgl_indo(date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))).' '.waktu_publish().' WIB';
		}

    $this->breadcrumb->add('Dashboard', site_url('home'));
    $this->breadcrumb->add('Rekapitulasi', '#');
		$this->breadcrumb->add('Harian', '#');

		$this->session_info['page_name'] = "Rekap Harian";
		$this->session_info['jadwal'] 	 = $jadwal;
		if($this->app_loader->is_admin() OR $this->app_loader->is_gugus())
			$this->template->build('vlist', $this->session_info);
		else
			$this->template->build('vlist_opr', $this->session_info);
	}

	public function listview()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
      $data = array();
			$csrfHash = $this->security->get_csrf_hash();
      $session = $this->app_loader->current_account();
      if(isset($session)){
				$row = array();
        $dataPublish = $this->mpub->getDataListForPublish();
				if(count($dataPublish) > 0) {
					foreach ($dataPublish as $key => $dp) {
						$row['name_regency'] 	= $dp['name'];
						$row['otg_last'] 			= $dp['otg_last'];
						$row['otg_baru'] 			= $dp['otg_baru'];
						$row['otg_bs'] 				= $dp['otg_bs'];
						$row['otg_sembuh'] 		= $dp['otg_sembuh'];
						$row['total_otg']			= ($dp['otg_last'] + $dp['otg_baru']) - ($dp['otg_bs'] + $dp['otg_sembuh']);
						$row['odp_last'] 			= $dp['odp_last'];
						$row['odp_baru'] 			= $dp['odp_baru'];
						$row['odp_bs'] 				= $dp['odp_bs'];
						$row['odp_sembuh'] 		= $dp['odp_sembuh'];
						$row['total_odp']			= ($dp['odp_last'] + $dp['odp_baru']) - ($dp['odp_bs'] + $dp['odp_sembuh']);
						$row['pdp_last'] 			= $dp['pdp_last'];
						$row['pdp_baru'] 			= $dp['pdp_baru'];
						$row['pdp_bs'] 				= $dp['pdp_bs'];
						$row['pdp_sembuh'] 		= $dp['pdp_sembuh'];
						$row['total_pdp']			= ($dp['pdp_last'] + $dp['pdp_baru']) - ($dp['pdp_bs'] + $dp['pdp_sembuh']);
						$row['pos_last'] 			= $dp['positif_last'];
						$row['pos_baru'] 			= $dp['positif_baru'];
						$row['pos_meninggal'] = $dp['positif_meninggal'];
						$row['pos_sembuh'] 		= $dp['positif_sembuh'];
						$row['total_pos']			= ($dp['positif_last'] + $dp['positif_baru']) - ($dp['positif_meninggal'] + $dp['positif_sembuh']);
						$data[] = $row;
					}
					$result = array('status'=>1, 'message'=>$data, 'csrfHash' => $csrfHash);
				} else
					$result = array('status'=>0, 'message'=>'', 'csrfHash' => $csrfHash);
      } else
				$result = array('status'=>1, 'message'=>'', 'csrfHash' => $csrfHash);
      //output to json format
      $this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function approve()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$session  = $this->app_loader->current_account();
			$csrfHash = $this->security->get_csrf_hash();
			if(!empty($session)) {
				$data = $this->mpub->approveDataKasus();
				if($data['message'] == 'SUCCESS') {
					$result = array('status' => 1, 'message' => 'Data rekap kasus covid-19 di Sumatera Barat per tanggal <b>'.tgl_indo_time($data['note']).' WIB </b> telah berhasil dipublish...', 'csrfHash' => $csrfHash);
					$this->cipusher->send(array('message'=>'publishsuccess'));
				} else if($data['message'] == 'HAVEDATA') {
					$result = array('status' => 0, 'message' => 'Proses publish data rekap kasus covid-19 gagal, karena data per tanggal <b>'.tgl_indo_time($data['note']).' WIB </b> telah dipublish...', 'csrfHash' => $csrfHash);
				} else {
					$result = array('status' => 0, 'message' => 'Mohon maaf untuk saat ini anda tidak dapat melakukan pembaharuan data. Untuk memperbaharui data rekap kasus covid-19 terbaru hanya dapat dilakukan pada <b>'.$data['note'].' WIB </b>', 'csrfHash' => $csrfHash);
				}
			} else {
				$result = array('status' => 0, 'message' => 'Proses publish data gagal, harap periksa kembali data yang akan dipublish...', 'csrfHash' => $csrfHash);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function akumulasi()
	{
		if (!$this->input->is_ajax_request()) {
   		exit('No direct script access allowed');
		} else {
			$data = array();
			$session  = $this->app_loader->current_account();
			if(!empty($session)) {
				$dataNew = $this->mpub->getDataKasusAkumulasi();
				$data['total_otg'] = !empty($dataNew) ? ($dataNew['otg_last'] + $dataNew['otg_baru']) - $dataNew['otg_bs'] : 0;
				$data['new_otg']   = !empty($dataNew) ? '+'.$dataNew['otg_baru'].' KASUS' : 0;
				$data['total_odp'] = !empty($dataNew) ? ($dataNew['odp_last'] + $dataNew['odp_baru']) - $dataNew['odp_bs'] : 0;
				$data['new_odp']   = !empty($dataNew) ? '+'.$dataNew['odp_baru'].' KASUS' : 0;
				$data['total_pdp'] = !empty($dataNew) ? ($dataNew['pdp_last'] + $dataNew['pdp_baru']) - $dataNew['pdp_bs'] : 0;
				$data['new_pdp']   = !empty($dataNew) ? '+'.$dataNew['pdp_baru'].' KASUS' : 0;
				$data['total_positif'] = !empty($dataNew) ? ($dataNew['positif_last'] + $dataNew['positif_baru']) - $dataNew['positif_bs'] : 0;
				$data['new_positif']   = !empty($dataNew) ? '+'.$dataNew['positif_baru'].' KASUS' : 0;
				$result = array('message' => $data);
			} else {
				$result = array('message' => $data);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	public function export_to_excel()
	{
		require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$template  = 'repository/template/daily_report.xls';
		$objPHPExcel = $objReader->load($template);

		$datenow = (date('H:i:s') < waktu_input()) ? date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d')))) : date('Y-m-d');
		$publishDate = date('Y-m-d H:i:s', strtotime($datenow.' '.waktu_publish()));

		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:V3');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'PER TANGGAL '.strtotime(tgl_indo_time($publishDate)).' WIB');

		//get data kasus
		$noRow = 0;
		$baseRow = 8;
		$otg_a = 0; $otg_b = 0; $otg_c = 0; $otg_d = 0;
		$odp_a = 0; $odp_b = 0; $odp_c = 0; $odp_d = 0;
		$pdp_a = 0; $pdp_b = 0; $pdp_c = 0; $pdp_d = 0;
		$pos_a = 0; $pos_b = 0; $pos_c = 0; $pos_d = 0;
		$dataPublish = $this->mpub->getDataListForPublish();
		if(count($dataPublish) > 0) {
			foreach ($dataPublish as $key => $dp) {
				$noRow++;
				$row = $baseRow + $noRow;
				$otg_a += (int) $dp['otg_last']; $otg_b += (int) $dp['otg_baru']; $otg_c += (int) $dp['otg_bs']; $otg_d += (int) $dp['otg_sembuh'];
				$total_otg = ($dp['otg_last'] + $dp['otg_baru']) - ($dp['otg_bs'] + $dp['otg_sembuh']);
				$odp_a += (int) $dp['odp_last']; $odp_b += (int) $dp['odp_baru']; $odp_c += (int) $dp['odp_bs']; $odp_d += (int) $dp['odp_sembuh'];
				$total_odp = ($dp['odp_last'] + $dp['odp_baru']) - ($dp['odp_bs'] + $dp['odp_sembuh']);
				$pdp_a += (int) $dp['pdp_last']; $pdp_b += (int) $dp['pdp_baru']; $pdp_c += (int) $dp['pdp_bs']; $pdp_d += (int) $dp['pdp_sembuh'];
				$total_pdp = ($dp['pdp_last'] + $dp['pdp_baru']) - ($dp['pdp_bs'] + $dp['pdp_sembuh']);
				$pos_a += (int) $dp['positif_last']; $pos_b += (int) $dp['positif_baru']; $pos_c += (int) $dp['positif_meninggal']; $pos_d += (int) $dp['positif_sembuh'];
				$total_pos = ($dp['positif_last'] + $dp['positif_baru']) - ($dp['positif_meninggal'] + $dp['positif_sembuh']);
				$objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row,1);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $noRow);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, $dp['name']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, $dp['otg_last']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $dp['otg_baru']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $dp['otg_bs']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, $dp['otg_sembuh']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $total_otg);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row, $dp['odp_last']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row, $dp['odp_baru']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row, $dp['odp_bs']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, $dp['odp_sembuh']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$row, $total_odp);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$row, $dp['pdp_last']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$row, $dp['pdp_baru']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$row, $dp['pdp_bs']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$row, $dp['pdp_sembuh']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$row, $total_pdp);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$row, $dp['positif_last']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$row, $dp['positif_baru']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$row, $dp['positif_meninggal']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$row, $dp['positif_sembuh']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$row, $total_pos);
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
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$row, '');
		}
		$objPHPExcel->setActiveSheetIndex(0)->removeRow($baseRow,1);
		$row = $row;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, $otg_a);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $otg_b);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $otg_c);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, $otg_d);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, (($otg_a + $otg_b) - ($otg_c + $otg_d)));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row, $odp_a);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row, $odp_b);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row, $odp_c);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, $odp_d);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$row, (($odp_a + $odp_b) - ($odp_c + $odp_d)));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$row, $pdp_a);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$row, $pdp_b);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$row, $pdp_c);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$row, $pdp_d);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$row, (($pdp_a + $pdp_b) - ($pdp_c + $pdp_d)));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$row, $pos_a);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$row, $pos_b);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$row, $pos_c);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$row, $pos_d);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$row, (($pos_a + $pos_b) - ($pos_c + $pos_d)));
		$file	= 'rekap_data_covid19_per_tgl_'.date('Ymd', strtotime($publishDate)).'.xlsx';
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

// This is the end of home clas

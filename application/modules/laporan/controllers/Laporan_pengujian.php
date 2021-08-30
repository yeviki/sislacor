<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Laporan_pengujian extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'laporan/laporan-kasus';
		$this->load->model(array('Model_lap_pengujian' => 'mLapPengujian', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Laporan', '#');
		$this->breadcrumb->add('Pengujian', '#');
		$this->session_info['list_regency_id']   = $this->mmas->getDataRegency();
		$this->session_info['page_name'] = "Laporan Pengujian";

    	$this->template->build('vlap_pengujian/vlist', $this->session_info);
	}

	public function export_to_excel()
    {
		// die($pemasok);
        require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
		$objReader = PHPExcel_IOFactory::createReader('Excel5');

		$regency_id		= escape($this->input->get('regency_id', TRUE));
		$start_date		= escape($this->input->get('start_date', TRUE));
		$end_date		= escape($this->input->get('end_date', TRUE));

		$dataKasus 	= $this->mLapPengujian->ReportKasus($regency_id, $start_date, $end_date);
			
		if($start_date != NULL) {
			$setdate = $start_date.' s/d '.$end_date;
		} else {
			$setdate = 'Keseluruhan';
		}

		$template  = 'repository/template/pengujian_report.xls';
		$objPHPExcel = $objReader->load($template);
		//set title
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:E2');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'DATA PEMERIKSAAN SPESIMEN COVID-19');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:E3');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'Tanggal : '.$setdate);
		//set data TPA
		$noRow = 0;
		$baseRow = 6;
		$total_spesimen = array();
		$total_pemeriksaan = array();
		if(count($dataKasus) > 0) {
			foreach ($dataKasus as $key => $dh) {
				$total_spesimen[] = $dh['total_spesimen'];
				$total_pemeriksaan[] = $dh['total_pemeriksaan'];

				$noRow++;
				$row = $baseRow + $noRow;
				$objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row,1);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $noRow);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, $dh['tanggal_spesimen']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, regency($dh['regency_id']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $dh['total_spesimen']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $dh['total_pemeriksaan']);
			}
		} else {
			$row = $baseRow + 1;
			$objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore($row,1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, '');
		}
		$objPHPExcel->setActiveSheetIndex(0)->removeRow($baseRow,1);
		$tot_spesimen = array_sum($total_spesimen);
		$tot_pemeriksaan = array_sum($total_pemeriksaan);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $tot_spesimen);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $tot_pemeriksaan);
		
        
		$file	= 'data_pengujian_report.xlsx';
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

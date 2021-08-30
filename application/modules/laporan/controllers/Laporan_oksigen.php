<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Laporan_oksigen extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'laporan/laporan-oksigen';
		$this->load->model(array('Model_lap_oksigen' => 'mLapOksigen', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Laporan', '#');
		$this->breadcrumb->add('Pengujian', '#');
		$this->session_info['list_regency_id']   = $this->mmas->getDataRegency();
		$this->session_info['page_name'] = "Laporan Pengujian";

    	$this->template->build('vlap_oksigen/vlist', $this->session_info);
	}

	public function export_to_excel()
	{
		// $regency_id		= escape($this->input->get('regency_id', TRUE));
		// $start_date		= escape($this->input->get('start_date', TRUE));
		// $end_date		= escape($this->input->get('end_date', TRUE));
		// die($typeReport);
		// if($typeReport == '1') {
		// 	$this->laporan_biasa($lokasi, $pemasok, $start_date, $end_date);
		// } else if ($typeReport == '2') {
		// 	$this->invoice($lokasi, $pemasok, $start_date, $end_date);
		// } else if ($typeReport == '3') {
			$this->rekap_persediaan();
		// }
	}
	
	private function rekap_persediaan() 
	{
		// $regency_id		= escape($this->input->get('regency_id', TRUE));
		// $start_date		= escape($this->input->get('start_date', TRUE));
		// $end_date		= escape($this->input->get('end_date', TRUE));
		
		$colomn_kategori = $this->mLapOksigen->getKategori();
		$kategori = array(); $kategori_oksigen = array();
		foreach ($colomn_kategori as $key => $ca) {
			// echo '<pre>'; print_r($ca); echo '</pre>';
			$kategori[$ca['id_kat_tabung']][] = $ca;
			$kategori_oksigen[$ca['id_kat_tabung']] = $ca['nm_tabung'];
		}

		$dataOksigen = $this->mLapOksigen->getDataPemakaian();
		$cell = array(); $hasil = array();
		foreach ($dataOksigen as $mt) {
			$cell[] = $mt;
		}

		$datatransaksi = $this->mLapOksigen->get_transaksi_tabung();
		$log_transaksi = array();
		foreach ($datatransaksi as $dlt) {
			$log_transaksi[$dlt['tanggal_pemakaian']][$dlt['id_rs']][$dlt['id_kat_tabung']] = $dlt['total_terpakai'];
		}
		
		// Load plugin PHPExcel nya
		require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
		// Panggil class PHPExcel nya
		$excel = new PHPExcel();
		// Settingan awal fil excel
		$excel->getProperties()->setCreator('Dinas Kominfo Sumbar')
								->setLastModifiedBy(ucwords(strtolower('Test')))
								->setTitle("UPTD ".ucwords(strtolower('Testing')))
								->setSubject("UPTD")
								->setDescription("Laporan UPTD ".ucwords(strtolower('Testing')))
								->setKeywords("Rekap UPTD");
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = array(
		 'font' => array('bold' => true), // Set font nya jadi bold
		 'alignment' => array(
			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
			 'wrap' => TRUE // Set wrap text
		 ),
		 'fill' => array(
			 'type' => PHPExcel_Style_Fill::FILL_SOLID,
			 'color' => array('rgb' => "99ccff")
		 ),
		 'borders' => array(
				'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000000')
				)
			  )
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari judul
		$style_header = array(
			'font' => array(
				'bold' => true,
				'size' => '16'
			), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi	 ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
				'wrap' => TRUE // Set wrap text
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID
			)
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = array(
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		$style_content = array(
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
				'wrap' => TRUE // Set wrap text
			)
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_keterangan = array(
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			),
			'font' => array('bold' => true), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
				'wrap' => TRUE // Set wrap text
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => "a3c2c2")
			)
		);

		// Buat header tabel nya pada baris ke 3
		$excel->getActiveSheet()->mergeCells('A4:A6');
		$excel->setActiveSheetIndex(0)->setCellValue('A4', "NO");
		$excel->getActiveSheet()->mergeCells('B4:B6');
		$excel->setActiveSheetIndex(0)->setCellValue('B4', "TANGGAL");
		$excel->getActiveSheet()->mergeCells('C4:C6');
		$excel->setActiveSheetIndex(0)->setCellValue('C4', "RUMAH SAKIT");
		$col = 3; $i = 2; $cols = 2; $merge=0;
		$array_colom_persedian = array("Persedian","Pemakaian");

		foreach ($kategori as $row => $val) {
			$tot = (count($val))*2;
			$jml_kolom = count($array_colom_persedian);
			$excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 5, $col+$tot-1, 5);
			$excel->getActiveSheet()->setCellValueByColumnAndRow($col, 5, $kategori_oksigen[$row])->getColumnDimensionByColumn($col)->setWidth('6');
			$excel->getActiveSheet()->getStyleByColumnAndRow($col, 5, $col+$tot-1, 5)->applyFromArray($style_col);
			
			foreach ($array_colom_persedian as $rowskolom => $datakolom)
			{
				// $excel->getActiveSheet()->mergeCellsByColumnAndRow($cols+1, 6, $cols+1, 6);
				$excel->getActiveSheet()->setCellValueByColumnAndRow($cols+1, 6, $datakolom)->getColumnDimensionByColumn($cols+1)->setWidth('6');
				$excel->getActiveSheet()->getStyleByColumnAndRow($cols, 6, $cols+1, 6)->applyFromArray($style_col);
				$cols += 1;
			}
			$col += $tot;
			$merge = $merge + $tot;
			//$i++;
		}

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $merge+2, 1);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'LAPORAN DATA PERSEDIAAN OKSIGEN');
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, 1, $merge+2, 1)->applyFromArray($style_header);

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 2, $merge+2, 2);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'TANGGAL');
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, 2, $merge+2, 2)->applyFromArray($style_header);

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(3, 4, $merge+2, 4);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'KATEGORI TABUNG');
		$excel->getActiveSheet()->getStyleByColumnAndRow(3, 4, $merge+2, 4)->applyFromArray($style_keterangan);

		/*Bagian isi keterangan*/
		$no=0; 
		$line=6; 
		$i= 2;
		$array_colom_persedian = array("Persedian","Pemakaian");
		foreach($cell as $key => $c) {

			// die(print_r($c['tanggal_pemakaian']));
			$no++;
			//set nomor
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, $line+$no, 0, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(0, $line+$no, $no);
			$excel->getActiveSheet()->getStyleByColumnAndRow(0, $line+$no, 0, $line+$no)->applyFromArray($style_content);
			//set tanggal
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(1, $line+$no, 1, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(1, $line+$no, $c['tanggal_pemakaian']);
			$excel->getActiveSheet()->getStyleByColumnAndRow(1, $line+$no, 1, $line+$no)->applyFromArray($style_row);
			// nama rs
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(2, $line+$no, 2, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(2, $line+$no, $c['shortname'])->getColumnDimensionByColumn(2, $line+$no, 2, $line+$no)->setWidth('35');
			$excel->getActiveSheet()->getStyleByColumnAndRow(2, $line+$no, 2, $line+$no)->applyFromArray($style_row);
			//set hasil 
			$cols = 3;
			$tot_samping =0;
			$tot = (count($val))*2;
			$jml_kolom = count($array_colom_persedian);
			// foreach ($kategori as $row => $val) {
			// 	foreach ($val as $rows => $a) {
			// 		// die(print_r($a['id_kat_tabung']));
			// 		// $skor = isset($log_transaksi[]);
			// 		// die(print_r($c['tanggal_pemakaian']));
			// 		$skor =isset($log_transaksi[$c['tanggal_pemakaian']][$c['id_rs']][$a['id_kat_tabung']]) ? $log_transaksi[$c['tanggal_pemakaian']][$c['id_rs']][$a['id_kat_tabung']] : '0';
			// 		// $skor = isset($hasil[$key]['kategori'][$row]) ? $hasil[$key]['kategori'][$row] : 0;
			// 		$excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+$no, $cols, $line+$no);
			// 		$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+$no, $skor)->getColumnDimensionByColumn($cols)->setWidth('28');
			// 		$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+$no, $cols, $line+$no)->applyFromArray($style_content);
			// 		$cols += 1;
			// 	}
			// }
		}

		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A4:A6')->applyFromArray($style_keterangan);
		$excel->getActiveSheet()->getStyle('B4:B6')->applyFromArray($style_keterangan);
		$excel->getActiveSheet()->getStyle('C4:C6')->applyFromArray($style_keterangan);

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle('Laporan Persediaan Oksigen');
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="rekap_persediaan.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}
	

	private function history_pemakaian_harian() 
	{
		// $regency_id		= escape($this->input->get('regency_id', TRUE));
		// $start_date		= escape($this->input->get('start_date', TRUE));
		// $end_date		= escape($this->input->get('end_date', TRUE));
		
		$colomn_kategori = $this->mLapOksigen->getKategori();
		$kategori = array(); $kategori_oksigen = array();
		foreach ($colomn_kategori as $key => $ca) {
			// echo '<pre>'; print_r($ca); echo '</pre>';
			$kategori[$ca['id_kat_tabung']][] = $ca;
			$kategori_oksigen[$ca['id_kat_tabung']] = $ca['nm_tabung'];
		}

		$dataOksigen = $this->mLapOksigen->getDataPemakaian();
		$cell = array(); $hasil = array();
		foreach ($dataOksigen as $mt) {
			$cell[] = $mt;
		}

		$datatransaksi = $this->mLapOksigen->get_transaksi_tabung();
		$log_transaksi = array();
		foreach ($datatransaksi as $dlt) {
			$log_transaksi[$dlt['tanggal_pemakaian']][$dlt['id_rs']][$dlt['id_kat_tabung']] = $dlt['total_terpakai'];
		}
		
		// Load plugin PHPExcel nya
		require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
		// Panggil class PHPExcel nya
		$excel = new PHPExcel();
		// Settingan awal fil excel
		$excel->getProperties()->setCreator('Dinas Kominfo Sumbar')
								->setLastModifiedBy(ucwords(strtolower('Test')))
								->setTitle("UPTD ".ucwords(strtolower('Testing')))
								->setSubject("UPTD")
								->setDescription("Laporan UPTD ".ucwords(strtolower('Testing')))
								->setKeywords("Rekap UPTD");
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = array(
		 'font' => array('bold' => true), // Set font nya jadi bold
		 'alignment' => array(
			 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
			 'wrap' => TRUE // Set wrap text
		 ),
		 'fill' => array(
			 'type' => PHPExcel_Style_Fill::FILL_SOLID,
			 'color' => array('rgb' => "99ccff")
		 ),
		 'borders' => array(
				'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000000')
				)
			  )
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari judul
		$style_header = array(
			'font' => array(
				'bold' => true,
				'size' => '16'
			), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi	 ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
				'wrap' => TRUE // Set wrap text
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID
			)
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = array(
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		$style_content = array(
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
				'wrap' => TRUE // Set wrap text
			)
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_keterangan = array(
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			),
			'font' => array('bold' => true), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
				'wrap' => TRUE // Set wrap text
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => "a3c2c2")
			)
		);

		// Buat header tabel nya pada baris ke 3
		$excel->getActiveSheet()->mergeCells('A4:A5');
		$excel->setActiveSheetIndex(0)->setCellValue('A4', "NO");
		$excel->getActiveSheet()->mergeCells('B4:B5');
		$excel->setActiveSheetIndex(0)->setCellValue('B4', "TANGGAL");
		$excel->getActiveSheet()->mergeCells('C4:C5');
		$excel->setActiveSheetIndex(0)->setCellValue('C4', "RUMAH SAKIT");
		$col = 3; $i = 2; $cols = 2; $merge=0;
		foreach ($kategori as $row => $val) {
			$tot = count($val);
			$excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 5, $col+$tot-1, 5);
			$excel->getActiveSheet()->setCellValueByColumnAndRow($col, 5, $kategori_oksigen[$row]);
			$excel->getActiveSheet()->getStyleByColumnAndRow($col, 5, $col+$tot-1, 5)->applyFromArray($style_col);
			$col += $tot;
			$merge = $merge + $tot;
			//$i++;
		}

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $merge+2, 1);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'LAPORAN DATA PERSEDIAAN OKSIGEN');
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, 1, $merge+2, 1)->applyFromArray($style_header);

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 2, $merge+2, 2);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'TANGGAL');
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, 2, $merge+2, 2)->applyFromArray($style_header);

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(3, 4, $merge+2, 4);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'KATEGORI TABUNG');
		$excel->getActiveSheet()->getStyleByColumnAndRow(3, 4, $merge+2, 4)->applyFromArray($style_keterangan);

		/*Bagian isi keterangan*/
		$no=0; 
		$line=5; 
		$i= 2;
		foreach($cell as $key => $c) {

			// die(print_r($c['tanggal_pemakaian']));
			$no++;
			//set nomor
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, $line+$no, 0, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(0, $line+$no, $no);
			$excel->getActiveSheet()->getStyleByColumnAndRow(0, $line+$no, 0, $line+$no)->applyFromArray($style_content);
			//set tanggal
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(1, $line+$no, 1, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(1, $line+$no, $c['tanggal_pemakaian']);
			$excel->getActiveSheet()->getStyleByColumnAndRow(1, $line+$no, 1, $line+$no)->applyFromArray($style_row);
			// nama rs
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(2, $line+$no, 2, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(2, $line+$no, $c['shortname'])->getColumnDimensionByColumn(2, $line+$no, 2, $line+$no)->setWidth('35');;
			$excel->getActiveSheet()->getStyleByColumnAndRow(2, $line+$no, 2, $line+$no)->applyFromArray($style_row);
			//set hasil 
			$cols = 3;
			$tot_samping =0;
			foreach ($kategori as $row => $val) {
				foreach ($val as $rows => $a) {
					// die(print_r($a['id_kat_tabung']));
					// $skor = isset($log_transaksi[]);
					// die(print_r($c['tanggal_pemakaian']));
					$skor =isset($log_transaksi[$c['tanggal_pemakaian']][$c['id_rs']][$a['id_kat_tabung']]) ? $log_transaksi[$c['tanggal_pemakaian']][$c['id_rs']][$a['id_kat_tabung']] : '0';
					// $skor = isset($hasil[$key]['kategori'][$row]) ? $hasil[$key]['kategori'][$row] : 0;
					$excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+$no, $cols, $line+$no);
					$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+$no, $skor)->getColumnDimensionByColumn($cols)->setWidth('28');
					$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+$no, $cols, $line+$no)->applyFromArray($style_content);
					$cols += 1;
				}
			}
		}

		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A4:A5')->applyFromArray($style_keterangan);
		$excel->getActiveSheet()->getStyle('B4:B5')->applyFromArray($style_keterangan);
		$excel->getActiveSheet()->getStyle('C4:C5')->applyFromArray($style_keterangan);

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle('Laporan Persediaan Oksigen');
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="rekap_harian.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}
}

// This is the end of home class
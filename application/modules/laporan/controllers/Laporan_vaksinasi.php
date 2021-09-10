<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of 
 *
 * @author Yogi "solop" Kaputra
 */

class Laporan_vaksinasi extends SLP_Controller {
	private $_url  = '';

	public function __construct()
	{
		parent::__construct();
		$this->_url  = 'laporan/laporan-vaksinasi';
		$this->load->model(array('Model_lap_vaksin' => 'mLapVaksin', 'master/model_master' => 'mmas'));
	}

	public function index()
	{
    	$this->breadcrumb->add('Dashboard', site_url('home'));
    	$this->breadcrumb->add('Laporan', '#');
		$this->breadcrumb->add('Vaksin', '#');
		$this->session_info['list_regency_id']   = $this->mmas->getDataRegency();
		$this->session_info['page_name'] = "Laporan Vaksin";

    	$this->template->build('vlap_vaksin/vlist', $this->session_info);
	}

	public function export_to_excel()
	{
		$regency_id			= escape($this->input->get('regency_id', TRUE));
		$start_date		= escape($this->input->get('start_date', TRUE));
		$end_date		= escape($this->input->get('end_date', TRUE));
		// die($typeReport);
		//  if ($typeReport == '1') {
			$this->rekap_data($regency_id, $start_date, $end_date);
		// } else if ($typeReport == '2') {
		// 	$this->rekap_persediaan($regency_id, $start_date, $end_date);
		// }
	}

	private function rekap_data($regency_id, $start_date, $end_date) 
	{
		if($start_date != NULL) {
            $setdate = $start_date.' s/d '.$end_date;
        } else {
            $setdate = 'KESELURUHAN';
		}
		
		$colomn_kategori = $this->mLapVaksin->getKategori();
		$kategori = array(); $kategori_vaksin = array();
		foreach ($colomn_kategori as $key => $ca) {
			$kategori[$ca['id_jenis_vaksin']][] = $ca;
			$kategori_vaksin[$ca['id_jenis_vaksin']] = $ca['nm_vaksin'];
		}

		$dataVaksinasi = $this->mLapVaksin->getDataPemakaian($regency_id, $start_date, $end_date);
		$cell = array(); $hasil = array();
		foreach ($dataVaksinasi as $mt) {
			$cell[] = $mt;
		}

		$datatransaksi = $this->mLapVaksin->get_transaksi_vaksinasi($regency_id, $start_date, $end_date);
		$log_transaksi = array();
		foreach ($datatransaksi as $dlt) {
			$log_transaksi[$dlt['tanggal_capaian']][$dlt['regency_id']][$dlt['id_jenis_vaksin']] = $dlt['total_vaksinasi'];
		}

		$dataStok = $this->mLapVaksin->get_stok_vaksinasi($regency_id);
		$log_tersedia = array();
		foreach ($dataStok as $dltk) {
			$log_tersedia[$dltk['regency_id']][$dltk['id_jenis_vaksin']] = $dltk['total_suplai'];
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
		foreach ($kategori as $row => $val) {
			$tot = count($val);
			$excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 5, $col+($tot*2)-1, 5);
			$excel->getActiveSheet()->setCellValueByColumnAndRow($col, 5, $kategori_vaksin[$row]);
			$excel->getActiveSheet()->getStyleByColumnAndRow($col, 5, $col+($tot*2)-1, 5)->applyFromArray($style_keterangan);

			$excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 6, $col+$tot-1, 6);
			$excel->getActiveSheet()->setCellValueByColumnAndRow($col, 6, 'TERPAKAI');
			$excel->getActiveSheet()->getStyleByColumnAndRow($col, 6, $col+$tot-1, 6)->applyFromArray($style_keterangan);

			$excel->getActiveSheet()->mergeCellsByColumnAndRow($col+1, 6, $col+$tot, 6);
			$excel->getActiveSheet()->setCellValueByColumnAndRow($col+1, 6, 'TERSEDIA');
			$excel->getActiveSheet()->getStyleByColumnAndRow($col+1, 6, $col+$tot, 6)->applyFromArray($style_keterangan);

			$col += $tot*2;
			$merge = $merge + ($tot*2);
			//$i++;
		}

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $merge+2, 1);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'LAPORAN DATA CAPAIAN VAKSINASI KABUPATEN/KOTA');
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, 1, $merge+2, 1)->applyFromArray($style_header);

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, 2, $merge+2, 2);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'TANGGAL ' .$setdate);
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, 2, $merge+2, 2)->applyFromArray($style_header);

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(3, 4, $merge+2, 4);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'KATEGORI VAKSIN');
		$excel->getActiveSheet()->getStyleByColumnAndRow(3, 4, $merge+2, 4)->applyFromArray($style_keterangan);

		/*Bagian isi keterangan*/
		$no=0; 
		$line=6; 
		$i= 2;
		foreach($cell as $key => $c) {
			$no++;
			//set nomor
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, $line+$no, 0, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(0, $line+$no, $no);
			$excel->getActiveSheet()->getStyleByColumnAndRow(0, $line+$no, 0, $line+$no)->applyFromArray($style_content);
			//set tanggal
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(1, $line+$no, 1, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(1, $line+$no, $c['tanggal_capaian']);
			$excel->getActiveSheet()->getStyleByColumnAndRow(1, $line+$no, 1, $line+$no)->applyFromArray($style_row);
			// nama rs
			$excel->getActiveSheet()->mergeCellsByColumnAndRow(2, $line+$no, 2, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow(2, $line+$no, $c['name'])->getColumnDimensionByColumn(2, $line+$no, 2, $line+$no)->setWidth('35');;
			$excel->getActiveSheet()->getStyleByColumnAndRow(2, $line+$no, 2, $line+$no)->applyFromArray($style_row);
			//set hasil 
			$cols = 3;
			$tot_samping =0;
			foreach ($kategori as $row => $val) {
				foreach ($val as $rows => $a) {
					$tersedia =isset($log_tersedia[$c['regency_id']][$a['id_jenis_vaksin']]) ? $log_tersedia[$c['regency_id']][$a['id_jenis_vaksin']] : '0';
					$skor =isset($log_transaksi[$c['tanggal_capaian']][$c['regency_id']][$a['id_jenis_vaksin']]) ? $log_transaksi[$c['tanggal_capaian']][$c['regency_id']][$a['id_jenis_vaksin']] : '0';
					$excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+$no, $cols, $line+$no);
					$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+$no, $skor)->getColumnDimensionByColumn($cols)->setWidth('28');
					$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+$no, $cols, $line+$no)->applyFromArray($style_content);

					$excel->getActiveSheet()->mergeCellsByColumnAndRow($cols+1, $line+$no, $cols+1, $line+$no);
					$excel->getActiveSheet()->setCellValueByColumnAndRow($cols+1, $line+$no, ($tersedia-$skor))->getColumnDimensionByColumn($cols)->setWidth('28');
					$excel->getActiveSheet()->getStyleByColumnAndRow($cols+1, $line+$no, $cols+1, $line+$no)->applyFromArray($style_content);
					$cols += 2;
				}
			}
		}

		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A4:A6')->applyFromArray($style_keterangan);
		$excel->getActiveSheet()->getStyle('B4:B6')->applyFromArray($style_keterangan);
		$excel->getActiveSheet()->getStyle('C4:C6')->applyFromArray($style_keterangan);

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
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

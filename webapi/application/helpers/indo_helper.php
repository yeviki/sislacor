<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//untuk mengetahui bulan bulan
if ( ! function_exists('bulan'))
{
  function bulan($bln){
    switch ($bln){
			case '01':
				return "Januari";
				break;
			case '02':
				return "Februari";
				break;
			case '03':
				return "Maret";
				break;
			case '04':
				return "April";
				break;
			case '05':
				return "Mei";
				break;
			case '06':
				return "Juni";
				break;
			case '07':
				return "Juli";
				break;
			case '08':
				return "Agustus";
				break;
			case '09':
				return "September";
				break;
			case '10':
				return "Oktober";
				break;
			case '11':
				return "November";
				break;
			case '12':
				return "Desember";
				break;
		}
  }
}

//untuk mengetahui bulan bulan
if ( ! function_exists('nama_bulan'))
{
  function nama_bulan($bln){
    switch ($bln){
			case 'januari':
				return 1;
				break;
			case 'februari':
				return 2;
				break;
			case 'maret':
				return 3;
				break;
			case 'april':
				return 4;
				break;
			case 'mei':
				return 5;
				break;
			case 'juni':
				return 6;
				break;
			case 'juli':
				return 7;
				break;
			case 'agustus':
				return 8;
				break;
			case 'september':
				return 9;
				break;
			case '10':
				return "oktober";
				break;
			case 'november':
				return 11;
				break;
			case 'desember':
				return 12;
				break;
      default:
        return 0;
        break;
		}
  }
}

//untuk mengetahui bulan bulan
if ( ! function_exists('bulan_romawi'))
{
  function bulan_romawi($bln){
    switch ($bln){
			case '01':
				return "I";
				break;
			case '02':
				return "II";
				break;
			case '03':
				return "III";
				break;
			case '04':
				return "IV";
				break;
			case '05':
				return "V";
				break;
			case '06':
				return "VI";
				break;
			case '07':
				return "VII";
				break;
			case '08':
				return "VIII";
				break;
			case '09':
				return "IX";
				break;
			case '10':
				return "X";
				break;
			case '11':
				return "XI";
				break;
			case '12':
				return "XII";
				break;
		}
  }
}

//untuk mengetahui hari
if ( ! function_exists('hari'))
{
  function hari($tanggal){
		$hari = date('D', strtotime($tanggal));
		switch($hari){
			case 'Sun':
				return "Minggu";
				break;
			case 'Mon':
				return "Senin";
				break;
			case 'Tue':
				return "Selasa";
				break;
			case 'Wed':
				return "Rabu";
				break;
			case 'Thu':
				return "Kamis";
				break;
			case 'Fri':
				return "Jumat";
				break;
			case 'Sat':
				return "Sabtu";
				break;
			case 'Sunday':
				return "Minggu";
				break;
			case 'Monday':
				return "Senin";
				break;
			case 'Tuesday':
				return "Selasa";
				break;
			case 'Wednesday':
				return "Rabu";
				break;
			case 'Thursday':
				return "Kamis";
				break;
			case 'Friday':
				return "Jumat";
				break;
			case 'Saturday':
				return "Sabtu";
				break;
    }
	}
}

//format tanggal yyyy-mm-dd
if ( ! function_exists('tgl_indo'))
{
  function tgl_indo($tgl){
    $ubah = gmdate($tgl, time()+60*60*8);
    $pecah = explode("-",$ubah);  //memecah variabel berdasarkan -
    $tanggal = $pecah[2];
    $bulan = bulan($pecah[1]);
    $tahun = $pecah[0];
    return $tanggal.' '.$bulan.' '.$tahun; //hasil akhir
  }
}

//format tanggal timestamp
if( ! function_exists('tgl_indo_timestamp'))
{
	function tgl_indo_timestamp($tgl){
    $inttime	= date('Y-m-d H:i:s',$tgl); //mengubah format menjadi tanggal biasa
    $tglBaru 	= explode(" ",$inttime); //memecah berdasarkan spaasi

    $tglBaru1 	= $tglBaru[0]; //mendapatkan variabel format yyyy-mm-dd
    $tglBaru2	= $tglBaru[1]; //mendapatkan fotmat hh:ii:ss
    $tglBarua	= explode("-",$tglBaru1); //lalu memecah variabel berdasarkan -

    $tgl = $tglBarua[2];
    $bln = $tglBarua[1];
    $thn = $tglBarua[0];

    $bln = bulan($bln); //mengganti bulan angka menjadi text dari fungsi bulan
    $ubahTanggal = "$tgl $bln $thn | $tglBaru2 "; //hasil akhir tanggal

    return $ubahTanggal;
	}
}

//format tanggal timestamp
if( ! function_exists('tgl_login'))
{
	function tgl_login($tgl){
		$tgltime 	= explode(' ', $tgl);
		$tglBaru 	= explode('-', $tgltime[0]);

		$hari 	= hari($tgltime[0]);
		$tgl 	= $tglBaru[2];
		$bln 	= bulan($tglBaru[1]);
		$thn 	= $tglBaru[0];

	  $ubahTanggal = $hari.', '.$tgl.' '.$bln.' '.$thn.' '.$tgltime[1]; //hasil akhir tanggal
	  return $ubahTanggal;
	}
}

//format tanggal tanpa jam
if( ! function_exists('tgl_surat'))
{
	function tgl_surat($tgl){
		$tgltime 	= explode(' ', $tgl);
		$tglBaru 	= explode('-', $tgltime[0]);

		$hari = hari($tgltime[0]);
		$tgl 	= $tglBaru[2];
		$bln 	= bulan($tglBaru[1]);
		$thn 	= $tglBaru[0];
	  $ubahTanggal = $hari.', '.$tgl.' '.$bln.' '.$thn; //hasil akhir tanggal

	  return $ubahTanggal;
	}
}

//format tanggal tanpa jam
if( ! function_exists('urutkan_array'))
{
	function urutkan_array($bilangan){
		$arr 	= array();
		$di 	= explode(',', $bilangan);
    $ni 	= count($di);

    for ($i=0; $i < $ni; $i++) {
      if($di[$i] != "" && $di[$i] != 0)
        array_push($arr, $di[$i]);
    }

    $clear = array_unique($arr);
    sort($clear);

    return $clear;
	}
}

//format tanggal tanpa jam
if( ! function_exists('implode_array'))
{
  function implode_array($glue, $arr){
    for ($i=0; $i<count($arr); $i++) {
        if (@is_array($arr[$i]))
            $arr[$i] = implode_array ($glue, $arr[$i]);
    }
    return implode($glue, $arr);
  }
}

//conert tgl dari d/m/Y menjadi Y-m-d
if (!function_exists('date_convert')) {
  function date_convert($date) {
    $newdate = str_replace('/','-', $date);
    $newdate = date('Y-m-d', strtotime($newdate));
    return $newdate;
  }
}

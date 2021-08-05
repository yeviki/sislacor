<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="btn-toolbar" style="margin-bottom: 15px">
        <a type="button" href="<?php echo site_url('konfirmasi-kasus/identifikasi'); ?>" class="btn btn-primary-alt" style="padding:11px 16px;"><b><i class="fa fa-table"></i> Data Kasus</b></a>
      </div>
    </div>
    <?php echo form_open(site_url('#'), array('id'=>'formPasien')); ?>
    <div class="col-xs-12 col-sm-12" style="margin-bottom:10px;">
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errDaftar"></div>
      <div class="row">
        <div class="col-xs-12 col-sm-7" style="margin-bottom:-15px;">
          <h3><b><?php echo ($this->app_loader->is_admin()) ? 'DINAS KESEHATAN PROVINSI' : hospital($this->app_loader->current_hospital(), 1); ?></b></h3>
        </div>
        <div class="col-xs-12 col-sm-5" style="margin-bottom:-15px;">
          <h4 style="text-align:right;"><b>STATUS PASIEN : <?php echo strtoupper(pasien_status($data_kasus['pasienstatus'], TRUE)); ?></b></h4>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Informasi</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <h4 style="margin-top:-10px;"><b>Petugas medis/Paramedis/Relawan kesehatan</b></h4>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                <label for="kode_fasyankes" class="control-label"><b>Kode Fasyankes</b></label>
                <input type="text" class="form-control" value="<?php echo $data_kasus['kode_fasyankes']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group">
                <label for="nama_fasyankes" class="control-label"><b>Nama Fasilitas Layanan Kesehatan</b></label>
                <input type="text" class="form-control toUpperCase" value="<?php echo $data_kasus['nama_fasyankes']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="nama_pewawancara" class="control-label"><b>Petugas Entri Data</b></label>
                <input type="text" class="form-control toUpperCase" value="<?php echo $data_kasus['nama_pewawancara']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tgl_wawancara" class="control-label"><b>Tanggal Entri Data <font color="red">*</font></b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask" value="<?php echo $data_kasus['tgl_wawancara']; ?>" disabled>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <hr/>
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="norm" class="control-label" style="font-size:16px;"><b>No Rekap Medis</b></label>
                <input type="text" class="form-control data-edit" placeholder="Nomor Rekap Medis" name="norm" id="norm" value="<?php echo $data_kasus['norm']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="pasienlastkondisi" class="control-label" style="font-size:16px;"><b>Kondisi Pasien Saat Ini</b></label>
                <input type="text" class="form-control" value="<?php echo ($data_kasus['pasienlastkondisi'] == 1) ? 'Masih Sakit/Menjalani Perawatan' : (($data_kasus['pasienlastkondisi'] == 2) ? (($data_kasus['pasienstatus']==3) ? 'Sudah Sembuh' : 'Negatif Covid-19') : 'Meninggal Dunia'); ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="tindaklanjut" class="control-label" style="font-size:16px;"><b>Riwayat Perawatan</b></label>
                <?php
                  if($data_kasus['tindaklanjut'] == 1)
                    $perawatan = 'Pasien mejalani perawatan di rumah sakit';
                  else if($data_kasus['tindaklanjut'] == 2)
                    $perawatan = ($data_kasus['rsrujukan'] == $this->app_loader->current_hospital()) ? 'Pasien dirujuk dari '.$data_kasus['rsrawatsebelumnya'] : 'Pasien dirujuk ke '.rujukan($data_kasus['rsrujukan']);
                  else if($data_kasus['tindaklanjut'] == 3)
                    $perawatan = 'Pasien mejalani isolasi diri di rumah';
                  else if($data_kasus['tindaklanjut'] == 4)
                    $perawatan = 'Pasien mejalani isolasi diri di '.fasilitas($data_kasus['nm_fasilitas']);
                ?>
                <input type="text" class="form-control" value="<?php echo $perawatan; ?>" disabled>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tanggalmasukrawat" class="control-label"><b>'Tanggal Mulai Dirawat</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask" name="tanggalmasukrawat" id="tanggalmasukrawat" placeholder="dd/mm/yyyy" value="<?php echo $data_kasus['tanggalmasukrawat']; ?>" disabled>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="rsrawatsebelumnya" class="control-label"><b>Rumah Sakit Rawat Sebelumnya</b></label>
                <input type="text" class="form-control" name="rsrawatsebelumnya" id="rsrawatsebelumnya" value="<?php echo $data_kasus['rsrawatsebelumnya']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-5">
              <div class="form-group required">
                <label for="namarsrawat" class="control-label"><b><?php echo ($data_kasus['tindaklanjut'] > 2) ? 'Rumah Sakit Rujukan Swab' : 'Rumah Sakit Rawat Saat Ini'; ?></b></label>
                <input type="text" class="form-control" name="namarsrawat" id="namarsrawat" value="<?php echo $data_kasus['namarsrawat']; ?>" disabled>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="dirawaticu" class="control-label"><b>Dirawat di Icu</b></label>
                <?php echo form_dropdown('dirawaticu', info(1), $data_kasus['dirawaticu'], 'class="select-all" id="dirawaticu" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="intubasi" class="control-label"><b>Intubasi</b></label>
                <?php echo form_dropdown('intubasi', info(1), $data_kasus['intubasi'], 'class="select-all" id="intubasi" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="penggunaanemco" class="control-label"><b>Penggunaan oksigenasi membrane ekstraskorporeal ?</b></label>
                <?php echo form_dropdown('penggunaanemco', info(1), $data_kasus['penggunaanemco'], 'class="select-all" id="penggunaanemco" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Identitas Pasien</p>
        </div>
        <div class="panel-body">
          <?php echo form_hidden('tokenId', $data_kasus['token']); ?>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="namalkp" class="control-label"><b>Nama Pasien <font color="red">*</font></b></label>
                <input type="text" class="form-control toUpperCase data-edit" name="namalkp" id="namalkp" placeholder="Nama Pasien" value="<?php echo $data_kasus['namalkp']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="nik" class="control-label"><b>NIK <font color="red">*</font></b></label>
                <input type="number" class="form-control nominal data-edit" name="nik" id="nik" placeholder="NIK" value="<?php echo $data_kasus['nik']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="nokk"><b>NO KK</b></label>
                <input type="number" class="form-control nominal data-edit" name="nokk" id="nokk" placeholder="NO KK" value="<?php echo $data_kasus['nokk']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="nohp" class="control-label"><b>No HP <font color="red">*</font></b></label>
                <input type="number" class="form-control nominal data-edit" name="nohp" id="nohp" placeholder="No HP" value="<?php echo $data_kasus['nohp']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tmptlhr" class="control-label"><b>Tempat Lahir <font color="red">*</font></b></label>
                <input type="text" class="form-control toUpperCase data-edit" name="tmptlhr" id="tmptlhr" placeholder="Tempat Lahir" value="<?php echo $data_kasus['tmptlhr']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tgllhr" class="control-label"><b>Tanggal Lahir <font color="red">*</font></b></label>
                <div class="input-group date birthdate">
                  <input type="text" class="form-control mask data-edit" name="tgllhr" id="tgllhr" placeholder="dd/mm/yyyy" value="<?php echo $data_kasus['tgllhr']; ?>" disabled>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="umur"><b>Umur (Th)</b></label>
                <input type="text" class="form-control" name="umur" id="umur" placeholder="Umur (th)" value="<?php echo $data_kasus['umur']; ?>" readonly>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="gender" class="control-label"><b>Jenis Kelamin <font color="red">*</font></b></label>
                <?php echo form_dropdown('gender', array(1=>'Laki-laki', 2=>'Perempuan'), $data_kasus['gender'], 'class="select-all data-edit" id="gender" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="province" class="control-label"><b>Provinsi <font color="red">*</font></b></label>
                <?php echo form_dropdown('province', $data_province, $data_kasus['province'], 'class="select-all data-edit" id="province" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="regency" class="control-label"><b>Kab/Kota <font color="red">*</font></b></label>
                <?php echo form_dropdown('regency', array(''=>'Pilih Kab/Kota'), $data_kasus['regency'], 'class="select-all data-edit" id="regency" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="district" class="control-label"><b>Kecamatan <font color="red">*</font></b></label>
                <?php echo form_dropdown('district', array(''=>'Pilih Kecamatan'), $data_kasus['district'], 'class="select-all data-edit" id="district" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="village" class="control-label"><b>Kelurahan <font color="red">*</font></b></label>
                <?php echo form_dropdown('village', array(''=>'Pilih Kelurahan'), $data_kasus['village'], 'class="select-all data-edit" id="village" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label for="address"><b>Alamat Domisili</b></label>
                <input type="text" class="form-control toUpperCase data-edit" name="address" id="address" placeholder="Alamat Domisili" value="<?php echo $data_kasus['address']; ?>" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="study"><b>Pendidikan</b></label>
                <?php echo form_dropdown('study', $data_study, $data_kasus['study'], 'class="select-all data-edit" id="study" disabled'); ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="job"><b>Pekerjaan</b></label>
                <input type="text" class="form-control toUpperCase data-edit" name="job" id="job" placeholder="Pekerjaan" value="<?php echo $data_kasus['job']; ?>" disabled>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Hasil Pemeriksaan Spesimen</p>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered basic-datatables" width="100%">
              <thead>
                <tr>
                  <th width="3%">No.</th>
                  <th width="15%">Kode Spesimen</th>
                  <th width="10%">Tgl Kirim</th>
                  <th width="9%">Data</th>
                  <th width="20%">Spesimen</th>
                  <th width="10%">Tgl Ambil</th>
                  <th width="10%">Status</th>
                  <th width="10%">Hasil Lab</th>
                  <th width="10%">Tgl Keluar</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach ($data_spesimen as $key => $ds): ?>
                  <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $ds['kode_swab']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($ds['tgl_kirim'])); ?></td>
                    <td><?php echo 'Hari ke-'.$ds['hari_ke']; ?></td>
                    <td><?php echo spesimen($ds['spesimen']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($ds['tgl_ambil'])); ?></td>
                    <td><?php echo ($ds['status'] == 1) ? '<span class="label label-warning">DIKIRIM</span>' : '<span class="label label-primary">DITERIMA</span>'; ?></td>
                    <td><?php echo ($ds['hasil']=='') ? '<span class="label label-info">Menunggu</span>' : (($ds['hasil']=='I') ? '<span class="label label-warning">INCONCLUSIVE</span>' : (($ds['hasil']=='N') ? '<span class="label label-success">NEGATIF</span>' : '<span class="label label-danger">POSITIF</span>')); ?></td>
                    <td><?php echo ($ds['tgl_keluar'] != '0000-00-00') ? date('d/m/Y', strtotime($ds['tgl_keluar'])) : '-'; ?></td>
                  </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Riwayat Sosial – Ekonomi</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-7">
              <div class="form-group required">
                <label for="aktivitasfisik" class="control-label" style="margin-bottom:0px;"><b>Aktivitas Fisik :</b></label>
                <?php foreach ($data_fisik as $key => $df): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="aktivitasfisik" id="<?php echo 'aktivitasfisik_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('aktivitasfisik', $key, ($key==$data_kasus['aktivitasfisik']) ? TRUE : ''); ?> disabled> <b><?php echo $df; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label for="merokok" class="control-label" style="margin-bottom:0px;"><b>Merokok :</b></label>
                <?php foreach (info(2) as $key => $m): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="merokok" id="<?php echo 'merokok_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('merokok', $key, ($key==$data_kasus['merokok']) ? TRUE : ''); ?> disabled> <b><?php echo $m; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="konsumsialkohol" class="control-label" style="margin-bottom:0px;"><b>Konsumsi Minuman Beralkohol :</b></label>
                <?php foreach (info(2) as $key => $ka): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="konsumsialkohol" id="<?php echo 'konsumsialkohol_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('konsumsialkohol', $key, ($key==$data_kasus['konsumsialkohol']) ? TRUE : ''); ?> disabled> <b><?php echo $ka; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="gajiperbln" class="control-label" style="margin-bottom:0px;"><b>Penghasilan Perbulan :</b></label>
                <?php foreach ($data_gaji as $key => $dg): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="gajiperbln" id="<?php echo 'gajiperbln_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('gajiperbln', $key, ($key==$data_kasus['gajiperbln']) ? TRUE : ''); ?> disabled> <b><?php echo $dg; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label for="anggotabpjs" class="control-label" style="margin-bottom:0px;"><b>Keanggotaan BPJS :</b></label>
                <?php foreach (info(2) as $key => $b): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="anggotabpjs" id="<?php echo 'anggotabpjs_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('anggotabpjs', $key, ($key==$data_kasus['anggotabpjs']) ? TRUE : ''); ?> disabled> <b><?php echo $b; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6" style="display:none;"  id="field_anggotabpjs">
              <div class="form-group required">
                <label for="nobpjs" class="control-label"><b>Nomor BPJS :</b></label>
                <input type="number" class="form-control nominal data-edit" name="nobpjs" id="nobpjs" placeholder="Nomor BPJS" value="<?php echo $data_kasus['nobpjs']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Pemeriksaan Fisik</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="suhu_tubuh" class="control-label"><b>Suhu Tubuh</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="suhu_tubuh" id="suhu_tubuh" placeholder="Suhu Tubuh" value="<?php echo $data_kasus['suhu_tubuh']; ?>" disabled>
                  <span class="input-group-addon">C</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tekanandarah" class="control-label"><b>Tekanan Darah</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="tekanandarah" id="tekanandarah" placeholder="Tekanan Darah" value="<?php echo $data_kasus['tekanandarah']; ?>"  disabled>
                  <span class="input-group-addon">mmHg</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="nadi" class="control-label"><b>Denyut Nadi</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="nadi" id="nadi" placeholder="Denyut Nadi" value="<?php echo $data_kasus['nadi']; ?>" disabled>
                  <span class="input-group-addon">x/menit</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="pernapasan" class="control-label"><b>Pernapasan</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="pernapasan" id="pernapasan" placeholder="Pernapasan" value="<?php echo $data_kasus['pernapasan']; ?>" disabled>
                  <span class="input-group-addon">x/menit</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="tinggibadan" class="control-label"><b>Tinggi Badan</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="tinggibadan" id="tinggibadan" placeholder="Tinggi Badan" value="<?php echo $data_kasus['tinggibadan']; ?>" disabled>
                  <span class="input-group-addon">Cm</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="beratbadan" class="control-label"><b>Berat Badan</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="beratbadan" id="beratbadan" placeholder="Berat Badan" value="<?php echo $data_kasus['beratbadan']; ?>" disabled>
                  <span class="input-group-addon">Kg</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="triage" class="control-label"><b>Hasil Triage (kalau ke UGD)</b></label>
                <input type="text" class="form-control data-edit" name="triage" id="triage" placeholder="Hasil Triage (kalau ke UGD)" value="<?php echo $data_kasus['triage']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Informasi Klinis</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="tanggalkeluhan" class="control-label"><b>Tanggal pertama muncul keluhan</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask data-edit" name="tanggalkeluhan" id="tanggalkeluhan" placeholder="dd/mm/yyyy" value="<?php echo $data_kasus['tanggalkeluhan']; ?>" disabled>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <?php foreach ($data_gejala as $key => $ge){
              if($ge['tp_field'] == 'text') {
                echo '<div class="col-xs-12 col-sm-6">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$ge['nm_field'].'" class="control-label"><b>'.$ge['title'].' '.(($ge['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<input type="text" class="form-control data-edit" name="'.$ge['nm_field'].'" id="'.$ge['nm_field'].'" placeholder="'.$ge['title'].'" value="'.$klinis[$ge['nm_field']].'" disabled>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              } else {
                echo '<div class="col-xs-12 col-sm-3" style="margin-bottom:20px;padding-left:0px;">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$ge['nm_field'].'" class="col-xs-12 control-label" style="margin-bottom:5px;"><b>'.$ge['title'].' '.(($ge['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<div class="col-xs-12">';
                    foreach (info($ge['val_field']) as $row => $r) {
                      echo '<label class="radio-inline">';
                        echo '<input type="radio" name="'.$ge['nm_field'].'" id="'.$ge['nm_field'].'_'.$row.'" value="'.$row.'" '.(set_radio($ge['nm_field'], $row, ($row==$klinis[$ge['nm_field']]) ? TRUE : '')).' disabled> <b>'.$r.'</b>';
                      echo '</label>';
                    }
                    echo '</div>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              }
            } ?>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Riwayat Penyakit Dahulu (Komorbiditas)</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <?php foreach ($data_penyerta as $key => $dp) {
              if($dp['tp_field'] == 'text') {
                echo '<div class="col-xs-12 col-sm-6">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$dp['nm_field'].'" class="control-label"><b>'.$dp['title'].' '.(($dp['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<input type="text" class="form-control data-edit" name="'.$dp['nm_field'].'" id="'.$dp['nm_field'].'" placeholder="'.$dp['title'].'" value="'.$klinis[$dp['nm_field']].'" disabled>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              } else {
                echo '<div class="col-xs-12 col-sm-3">';
                  echo '<div class="form-group required">';
                    echo '<label class="checkbox-inline">';
                      echo '<input type="checkbox" name="'.$dp['nm_field'].'" id="'.$dp['nm_field'].'" value="Y" '.(($klinis[$dp['nm_field']]=='Y') ? "checked" : '').' disabled><b>'.$dp['title'].'</b>';
                    echo '</label>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              }
            } ?>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Diagnosis</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <?php foreach ($data_diagnosis as $key => $dd) {
              if($dd['tp_field'] == 'text') {
                echo '<div class="col-xs-12 col-sm-6">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$dd['nm_field'].'" class="control-label"><b>'.$dd['title'].' '.(($dd['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<input type="text" class="form-control data-edit" name="'.$dd['nm_field'].'" id="'.$dd['nm_field'].'" placeholder="'.$dd['title'].'" value="'.$klinis[$dd['nm_field']].'" disabled>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              } else {
                echo '<div class="col-xs-12 col-sm-3">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$dd['nm_field'].'" class="control-label" style="margin-bottom:0px;"><b>'.$dd['title'].' '.(($dd['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    foreach (info($dd['val_field']) as $row => $rs) {
                      echo '<div class="radio">';
                        echo '<label>';
                          echo '<input type="radio" name="'.$dd['nm_field'].'" id="'.$dd['nm_field'].'_'.$row.'" value="'.$row.'" '.(set_radio($dd['nm_field'], $row, ($row==$klinis[$dd['nm_field']]) ? TRUE : '')).' disabled> <b>'.$rs.'</b>';
                        echo '</label>';
                      echo '</div>';
                    }
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              }
            } ?>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Faktor Kontak/Paparan</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="riwayatperjalanan" class="control-label" style="margin-bottom:0px;"><b>Dalam 14 hari sebelum sakit, apakah memiliki riwayat perjalanan ?</b></label>
                <?php foreach (info() as $key => $rp): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="riwayatperjalanan" id="<?php echo 'riwayatperjalanan_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('riwayatperjalanan', $key, ($key==$data_kasus['riwayatperjalanan']) ? TRUE : ''); ?> disabled> <b><?php echo $rp; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="kontaksuspekcovid19" class="control-label" style="margin-bottom:0px;"><b>Dalam 14 hari sebelum sakit, apakah memiliki kontak erat dengan kasus suspek COVID-19 ?</b></label>
                <?php foreach (info(2) as $key => $sp): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="kontaksuspekcovid19" id="<?php echo 'kontaksuspekcovid19_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('kontaksuspekcovid19', $key, ($key==$data_kasus['kontaksuspekcovid19']) ? TRUE : ''); ?> disabled> <b><?php echo $sp; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="kontakcovid19" class="control-label" style="margin-bottom:0px;"><b>Dalam 14 hari sebelum sakit, apakah memiliki kontak erat kasus konfirmasi COVID-19 ?</b></label>
                <?php foreach (info(2) as $key => $co): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="kontakcovid19" id="<?php echo 'kontakcovid19_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('kontakcovid19', $key, ($key==$data_kasus['kontakcovid19']) ? TRUE : ''); ?> disabled> <b><?php echo $co; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="pasienispa" class="control-label" style="margin-bottom:0px;"><b>Apakah pasien termasuk kelompok ISPA berat (pneumonia hingga membutuhkan perawatan di RS) yang tidak diketahui penyebabnya</b></label>
                <?php foreach (info(2) as $key => $pi): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="pasienispa" id="<?php echo 'pasienispa_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('pasienispa', $key, ($key==$data_kasus['pasienispa']) ? TRUE : ''); ?> disabled> <b><?php echo $pi; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row" id="field_riwayatperjalanan" style="display:none;">
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="negara" class="control-label"><b>Negara <font color="red">*</font></b></label>
                <?php echo form_dropdown('negara', $data_negara, $data_kasus['negara'], 'class="select-all data-edit" id="negara" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="kota" class="control-label"><b>Kota <font color="red">*</font></b></label>
                <input type="text" class="form-control toUpperCase data-edit" name="kota" id="kota" placeholder="Kota" value="<?php echo $data_kasus['kota']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="traveldate" class="control-label"><b>Tanggal Perjalanan</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask data-edit" name="traveldate" id="traveldate" placeholder="dd/mm/yyyy" value="<?php echo $data_kasus['traveldate']; ?>" disabled>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="arrivaldate" class="control-label"><b>Tanggal Kepulangan</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask data-edit" name="arrivaldate" id="arrivaldate" placeholder="dd/mm/yyyy" value="<?php echo $data_kasus['arrivaldate']; ?>" disabled>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="petugaskesehatan" class="control-label" style="margin-bottom:0px;"><b>Merupakan Petugas Kesehatan</b></label>
                <?php foreach (info(2) as $key => $pk): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="petugaskesehatan" id="<?php echo 'petugaskesehatan_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('petugaskesehatan', $key, ($key==$data_kasus['petugaskesehatan']) ? TRUE : ''); ?> disabled> <b><?php echo $pk; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-9" style="display:none;" id="field_petugaskesehatan">
              <div class="form-group required">
                <label for="profesimedis" class="control-label"><b>Petugas Kesehatan</b></label>
                <?php echo form_dropdown('profesimedis', $data_medis, $data_kasus['profesimedis'], 'class="select-all data-edit" id="profesimedis" disabled'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <label for="alatpelindungdiri" style="margin-bottom:5px;"><b>Alat perlindungan diri yang digunakan ?</b></label>
            </div>
            <div class="col-xs-12 col-sm-1">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="gown" id="gown" value="<?php echo $data_kasus['gown'] ?>" <?php echo ($data_kasus['gown']=='Y') ? 'checked' : ''; ?> disabled><b>Gown</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="maskermedis" id="maskermedis" value="Y" <?php echo ($data_kasus['maskermedis']=='Y') ? 'checked' : ''; ?> disabled><b>Masker Bedah</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="sarungtangan" id="sarungtangan" value="Y" <?php echo ($data_kasus['sarungtangan']=='Y') ? 'checked' : ''; ?> disabled><b>Sarung Tangan</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="maskern95standardffp2" id="maskern95standardffp2" value="Y" <?php echo ($data_kasus['maskern95standardffp2']=='Y') ? 'checked' : ''; ?> disabled><b>Masker N95 standard FFP2</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-1">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="ffp3" id="ffp3" value="Y" <?php echo ($data_kasus['ffp3']=='Y') ? 'checked' : ''; ?> disabled><b>FFP3</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="kacamatapelindung" id="kacamatapelindung" value="Y" <?php echo ($data_kasus['kacamatapelindung']=='Y') ? 'checked' : ''; ?> disabled><b>Kacamata Pelindung Goggle</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="tidakmemakaiapd" id="tidakmemakaiapd" value="Y" <?php echo ($data_kasus['tidakmemakaiapd']=='Y') ? 'checked' : ''; ?> disabled><b>Tidak Sama Sekali</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="paparanlainnya" class="control-label"><b>Paparan Lainnya Sebutkan</b></label>
                <input type="text" class="form-control data-edit" name="paparanlainnya" id="paparanlainnya" placeholder="Paparan Lainnya" value="<?php echo $data_kasus['paparanlainnya']; ?>" disabled>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Daftar Kontak Pasien (dengan orang-orang dekat)</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="table-responsive">
                <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%" id="tblKontak">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th width="18%">Nama Lengkap</th>
                      <th width="5%">Umur</th>
                      <th width="3%">JK</th>
                      <th width="29%">Alamat</th>
                      <th width="10%">No HP</th>
                      <th width="15%">Hubungan</th>
                      <th width="15%">Aktivitas</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var strDate  = new Date('<?php echo date('Y-m-d', strtotime('1920-01-01')); ?>');
  var lastDate = new Date('<?php echo date('Y-m-d', strtotime('2020-01-01')); ?>');
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';
  var provID = '<?php echo $data_kasus['province']; ?>',
      regeID = '<?php echo $data_kasus['regency']; ?>',
      distID = '<?php echo $data_kasus['district']; ?>',
      villID = '<?php echo $data_kasus['village']; ?>';
  $(document).ready(function() {
    run_waitMe_body();
    $('.mask').inputmask();
    $(".birthdate").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
      startDate: strDate
    });
    $(".datemonth").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
      startDate: lastDate
    });
    $('#province').select2('val', provID).trigger('change');
    getDataListPaparanKontak();
  });

  function run_waitMe(el) {
    el.waitMe({
			effect: 'facebook',
			text: 'Please wait...',
			bg: 'rgba(255,255,255,0.7)',
			color: '#000',
			maxSize: 100,
			waitTime: -1,
      textPos: 'vertical',
			source: '',
			fontSize: '',
			onClose: function(el) {}
		});
  }

  function run_waitMe_body() {
    run_waitMe($('#formParent'));
		setTimeout(function(){
			$('#formParent').waitMe('hide');
			setTimeout(function(){
				$('#formParent').waitMe('hide');
			},100);
		},1000);
	}

  $(document).on('change', 'input[type="radio"]', function(e) {
    let id   = $(this).val();
    let name = $(this).attr('name');
    if(id != 'Y') {
      $('#field_'+name).hide();
    } else {
      $('#field_'+name).show();
    }
  });
  $('input[type="radio"]:checked').trigger('change');

  $(document).on('change', '#province', function(e) {
    let id = $(this).val();
    getRegency(id);
  });

  $(document).on('change', '#regency', function(e) {
    let id = $(this).val();
    getDistrict(id);
  });

  $(document).on('change', '#district', function(e) {
    let id = $(this).val();
    getVillage(id);
  });

  //mengambil data kab/kota
  function getRegency(provinceId) {
    regeID = (regeID != '') ? regeID : '<?php echo $this->input->post('regency', TRUE); ?>';
    let lblReg = '';
    $.ajax({
      type: 'GET',
      url: site + 'konfirmasi-kasus/identifikasi/regency',
      data: {'province' : provinceId},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        $('#regency').html('').select2('data', null);
        if(data.status == 1) {
          lblReg = '<option value="">Pilih Kab/Kota</option>';
          $.each(data.message,function(key,value){
            lblReg += '<option value="'+value['id']+'">'+value['text']+'</option>';
          });
        } else
          lblReg = '<option value="">Pilih Kab/Kota</option>';
        $('#regency').html(lblReg);
        $('#regency').select2('val', regeID).trigger('change');
      }
    });
  }

  //mengambil data kecamatan
  function getDistrict(regencyId) {
    let lblDis = '';
    distID = (distID != '') ? distID : '<?php echo $this->input->post('district', TRUE); ?>';
    $.ajax({
      type: 'GET',
      url: site + 'konfirmasi-kasus/identifikasi/district',
      data: {'regency' : regencyId},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        $('#district').html('').select2('data', null);
        if(data.status == 1) {
          lblDis = '<option value="">Pilih Kecamatan</option>';
          $.each(data.message,function(key,value){
            lblDis += '<option value="'+value['id']+'">'+value['text']+'</option>';
          });
        } else
          lblDis = '<option value="">Pilih Kecamatan</option>';
        $('#district').html(lblDis);
        $('#district').select2('val', distID).trigger('change');
      }
    });
  }

  //mengambil data kelurahan/desa/nagari
  function getVillage(districtId) {
    let lblVil = '';
    villID = (villID != '') ? villID : '<?php echo $this->input->post('village', TRUE); ?>';
    $.ajax({
      type: 'GET',
      url: site + 'konfirmasi-kasus/identifikasi/village',
      data: {'district' : districtId},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        $('#village').html('').select2('data', null);
        if(data.status == 1) {
          lblVil = '<option value="">Pilih Kelurahan</option>';
          $.each(data.message,function(key,value){
            lblVil += '<option value="'+value['id']+'">'+value['text']+'</option>';
          });
        } else
          lblVil = '<option value="">Pilih Kelurahan</option>';
        $('#village').html(lblVil);
        $('#village').select2('val', villID).trigger('change');
      }
    });
  }

  //get daftar kontak erat kasus
  function getDataListPaparanKontak() {
    var tblName = '#tblKontak', token = $('input[name="tokenId"]').val();
    $(tblName).dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "konfirmasi-kasus/paparan-kontak/listview",
        "type": "POST",
        "data": {
          "tokenId" : token,
          "flag"    : 2,
          "<?php echo $this->security->get_csrf_token_name(); ?>" : $('input[name="'+csrfName+'"]').val()
        },
      },
      "columnDefs": [
        {
          "targets": [ 0 ], //first column
          "orderable": false, //set not orderable
        },
        {
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
      ],
    });
    $(tblName+'_filter input').addClass('form-control').attr('placeholder','Search Data');
    $(tblName+'_length select').addClass('form-control');
  }
</script>

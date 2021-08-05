<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-orange" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH PDP</div>
              <div class="pull-right" id="new_pdp">+0</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-user-md"></i></div>
              <div class="pull-right" id="total_satu">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-green" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">PDP BERUBAH STATUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-random"></i></div>
              <div class="pull-right" id="total_dua">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-danger" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH TERKONFIRMASI</div>
              <div class="pull-right" id="new_positif">+0</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-user-plus"></i></div>
              <div class="pull-right" id="total_tiga">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-primary" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">TERKONFIRMASI BERUBAH STATUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-external-link-square"></i></div>
              <div class="pull-right" id="total_empat">0</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errSuccess"></div>
    </div>
    <div class="col-xs-12 col-sm-12" style="margin:-5px 0px 0px 0px;">
      <div class="row">
        <div class="col-xs-12 col-sm-3">
          <h3 style="font-weight:bold;text-align:left;">
            <a href="javascript:void(0);" class="btnFilter" style="text-decoration:none;color:#000000;">
              <i class="fa fa-sliders"></i> Filter Data
            </a>
          </h3>
        </div>
        <div class="col-xs-12 col-sm-12">
          <?php echo form_open(site_url('#'), array('id'=>'formFilter', 'style'=>'display:none;margin-bottom:20px;')); ?>
            <div style="display:block;background:#FFF;padding:20px;border:1px solid #CCC;box-shadow:0px 0px 10px #CCC;">
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="namalkp" class="control-label"><b>Nama Pasien</b></label>
                    <input type="text" class="form-control toUpperCase param" name="nm_pasien" id="nm_pasien" placeholder="Nama Pasien" value="<?php echo $this->input->post('nm_pasien', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="nik" class="control-label"><b>NIK</b></label>
                    <input type="number" class="form-control nominal param" name="nik_pasien" id="nik_pasien" placeholder="NIK" value="<?php echo $this->input->post('nik_pasien', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="umur"><b>Umur</b></label>
                    <input type="number" class="form-control nominal" name="umur_pasien" id="umur_pasien" placeholder="Umur" value="<?php echo $this->input->post('umur_pasien', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="gender" class="control-label"><b>Jenis Kelamin</b></label>
                    <?php echo form_dropdown('gender', array(''=>'Pilih Jenis Kelamin', 1=>'Laki-laki', 2=>'Perempuan'), $this->input->post('gender', TRUE), 'class="select-all param" id="gender"'); ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="province" class="control-label"><b>Provinsi</b></label>
                    <?php echo form_dropdown('province', $data_province, $this->input->post('province', TRUE), 'class="select-all param" id="province"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="regency" class="control-label"><b>Kab/Kota</b></label>
                    <?php echo form_dropdown('regency', array(''=>'Pilih Kab/Kota'), $this->input->post('regency', TRUE), 'class="select-all param" id="regency"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="district" class="control-label"><b>Kecamatan</b></label>
                    <?php echo form_dropdown('district', array(''=>'Pilih Kecamatan'), $this->input->post('district', TRUE), 'class="select-all param" id="district"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="village" class="control-label"><b>Kelurahan</b></label>
                    <?php echo form_dropdown('village', array(''=>'Pilih Kelurahan'), $this->input->post('village', TRUE), 'class="select-all param" id="village"'); ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                    <label for="asalpasien" class="control-label"><b>Asal Pasien</b></label>
                    <?php echo form_dropdown('asalpasien', array(''=>'Pilih Asal Pasien', 'N'=>'Dari Dalam Provinsi', 'Y'=>'Dari Luar Provinsi'), $this->input->post('asalpasien', TRUE), 'class="select-all param" id="asalpasien"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="pasienstatus" class="control-label"><b>Status Pasien</b></label>
                    <?php echo form_dropdown('pasienstatus', array(''=>'Pilih Status', 2=>'Pasien Dalam Pengawasan', 3=>'Pasien Terkonfirmasi Covid-19'), $this->input->post('pasienstatus', TRUE), 'class="select-all param" id="pasienstatus"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="pasienlastkondisi" class="control-label"><b>Kondisi Pasien</b></label>
                    <?php echo form_dropdown('pasienlastkondisi', array(''=>'Pilih Kondisi Pasien', 1=>'Masih Sakit/Dalam Perawatan', 2=>'Sembuh/Negatif Covid-19', 3=>'Meninggal Dunia'), $this->input->post('pasienlastkondisi', TRUE), 'class="select-all param" id="pasienlastkondisi"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="hospital" class="control-label"><b>Nama Rumah Sakit</b></label>
                    <?php echo form_dropdown('hospital', $data_hospital, $this->input->post('hospital', TRUE), 'class="select-all param" id="hospital"'); ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="pull-left">
                    <div class="btn-toolbar">
                      <button type="submit" class="btn btn-primary" name="filter" id="filter"><i class="fa fa-filter"></i> LAKUKAN PENCARIAN</button>
                      <button type="button" class="btn btn-danger" name="cancel" id="cancel"><i class="fa fa-refresh"></i> BATALKAN PENCARIAN</button>
                    </div>
                  </div>
                  <div class="pull-right">
                    <div class="btn-toolbar">
                      <button type="button" class="btn btn-default btnFilter" name="button"><i class="fa fa-times"></i> CLOSE</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h4><b>REKAP DATA PER KASUS</b></h4>
          <div class="options">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#home1" data-toggle="tab" id="page1" class="btnTab"><i class="fa fa-clock-o"></i> Baru</a>
              </li>
              <li>
                <a href="#home2" data-toggle="tab" id="page2" class="btnTab"><i class="fa fa-check"></i> Publish</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="panel-body collapse in">
          <div class="tab-content">
            <div class="tab-pane active" id="home1">
              <div class="row">
                <div class="col-xs-12 col-sm-12 clearfix">
                  <div class="pull-left">
                    <h4 style="margin:0 0 10px"><b><?php echo 'Daftar kasus yang baru masuk dan belum diverifikasi'; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div id="errWaiting"></div>
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblKasus1" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="20%">Nama Daerah</th>
                          <th width="20%">Pelapor</th>
                          <th width="20%">Nama Pasien</th>
                          <th width="5%">Umur</th>
                          <th width="3%">JK</th>
                          <th width="11%">Status</th>
                          <th width="15%">Tgl. Input</th>
                          <th width="3%">View</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="home2">
              <div class="row">
                <div class="col-xs-12 col-sm-12 clearfix">
                  <div class="pull-left">
                    <h4 style="margin:0 0 10px"><b><?php echo 'Daftar kasus yang telah diverifikasi dan akan dipublish'; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblKasus2" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="20%">Nama Daerah</th>
                          <th width="15%">Pelapor</th>
                          <th width="20%">Nama Pasien</th>
                          <th width="5%">Umur</th>
                          <th width="3%">JK</th>
                          <th width="15%">Status</th>
                          <th width="15%">Tgl. Publish</th>
                          <th width="4%">View</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-lg in" id="modalCaseForm" tabindex="-1" role="dialog" aria-labelledby="modalCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmCase">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 13px 10px 13px;">
        <button type="button" class="close btnClose" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>FORM RINCIAN KASUS</b</h4>
      </div>
      <?php echo form_open(site_url('#'), array('id' => 'formCase')); ?>
      <div class="modal-body" style="padding:0px;">
        <div id="errCase"></div>
        <div class="panel panel-green">
          <div class="panel-heading">
            <p style="font-size:16px;padding-left:3px;">Informasi</p>
          </div>
          <div class="panel-body" style="padding:13px;">
            <?php
              echo form_hidden('caseId', '');
              echo form_hidden('tokenId', '');
            ?>
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <h4 style="margin-top:0px;"><b>Petugas medis/Paramedis/Relawan kesehatan</b></h4>
              </div>
              <div class="col-xs-12 col-sm-7">
                <div class="form-group">
                  <label for="fasyankes" class="control-label"><b>Nama Fasilitas Layanan Kesehatan</b></label>
                  <input type="text" class="form-control toUpperCase" name="fasyankes" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                  <label for="tglinput" class="control-label"><b>Tanggal Entri Data</b></label>
                  <div class="input-group date datemonth">
                    <input type="text" class="form-control mask" name="tglinput" value="" disabled>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12" style="margin-top:-10px;margin-bottom:-20px;">
                <h4><b>STATUS : <span id="lblStatus"></span></b></h4>
              </div>
            </div>
            <hr/>
            <div class="row">
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label class="control-label"><b>No Rekap Medis</b></label>
                  <input type="text" class="form-control" placeholder="No Rekap Medis" name="norm" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <label class="control-label"><b>Kondisi Pasien Saat Ini</b></label>
                  <input type="text" class="form-control" name="kondisi" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5 fieldRawat">
                <div class="form-group">
                  <label class="control-label"><b>Riwayat Perawatan</b></label>
                  <input type="text" class="form-control" name="rawat" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5 fieldMeninggal">
                <div class="form-group">
                  <label for="tgl_meninggal" class="control-label"><b>Tanggal Meninggal</b></label>
                  <div class="input-group date datemonth">
                    <input type="text" class="form-control mask" name="tgl_meninggal" placeholder="dd/mm/yyyy" value="" disabled>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5 fieldSembuh">
                <div class="form-group">
                  <label for="tgl_pulang" class="control-label"><b>Tanggal Sembuh</b></label>
                  <div class="input-group date datemonth">
                    <input type="text" class="form-control mask" name="tgl_pulang" placeholder="dd/mm/yyyy" value="" disabled>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-3 fieldRawat">
                <div class="form-group">
                  <label for="tglrawat" class="control-label"><b>Tanggal Mulai Dirawat</b></label>
                  <div class="input-group date datemonth">
                    <input type="text" class="form-control mask" name="tglrawat" placeholder="dd/mm/yyyy" value="" disabled>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 fieldRawat">
                <div class="form-group">
                  <label for="rsdulu" class="control-label"><b>Rumah Sakit Rawat Sebelumnya</b></label>
                  <input type="text" class="form-control" name="rsdulu" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5 fieldRawat">
                <div class="form-group">
                  <label for="rsrawat" class="control-label"><b></b></label>
                  <input type="text" class="form-control" name="rsrawat" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 fieldMeninggal">
                <div class="form-group">
                  <label for="waktu_meninggal" class="control-label"><b>Waktu Meninggal Dunia</b></label>
                  <input type="text" class="form-control" name="waktu_meninggal" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 fieldSembuh">
                <div class="form-group">
                  <label for="alasan_pulang" class="control-label"><b>Keterangan</b></label>
                  <input type="text" class="form-control" name="alasan_pulang" value="" disabled>
                </div>
              </div>
            </div>
          </div>
          <div class="panel-heading">
            <p style="font-size:16px;padding-left:3px;">Identitas Pasien</p>
          </div>
          <div class="panel-body" style="padding:13px;">
            <div class="row">
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="namalkp" class="control-label"><b>Nama Pasien</b></label>
                  <input type="text" class="form-control toUpperCase" name="namalkp" placeholder="Nama Pasien" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="nik" class="control-label"><b>NIK</b></label>
                  <input type="number" class="form-control nominal" name="nik"placeholder="NIK" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="nokk"><b>NO KK</b></label>
                  <input type="number" class="form-control nominal" name="nokk" placeholder="NO KK" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="nohp" class="control-label"><b>No HP</b></label>
                  <input type="number" class="form-control nominal" name="nohp" id="nohp" placeholder="No HP" value="" disabled>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="tmptlhr" class="control-label"><b>Tempat Lahir</b></label>
                  <input type="text" class="form-control toUpperCase" name="tmptlhr" placeholder="Tempat Lahir" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="tgllhr" class="control-label"><b>Tanggal Lahir</b></label>
                  <div class="input-group date birthdate">
                    <input type="text" class="form-control mask" name="tgllhr" placeholder="dd/mm/yyyy" value="" disabled>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="umur"><b>Umur (Th)</b></label>
                  <input type="text" class="form-control" name="umur" placeholder="Umur (th)" value="" readonly>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="gender" class="control-label"><b>Jenis Kelamin</b></label>
                  <input type="text" class="form-control toUpperCase" name="gender" placeholder="Jenis Kelamin" value="" disabled>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="province" class="control-label"><b>Provinsi</b></label>
                  <input type="text" class="form-control toUpperCase" name="province" placeholder="Provinsi" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="regency" class="control-label"><b>Kab/Kota</b></label>
                  <input type="text" class="form-control toUpperCase" name="regency" placeholder="Kab/Kota" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="district" class="control-label"><b>Kecamatan</b></label>
                  <input type="text" class="form-control toUpperCase" name="district" placeholder="Kecamatan" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="village" class="control-label"><b>Kelurahan</b></label>
                  <input type="text" class="form-control toUpperCase" name="village" placeholder="Kelurahan" value="" disabled>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label for="address"><b>Alamat Domisili</b></label>
                  <input type="text" class="form-control toUpperCase" name="address" placeholder="Alamat Domisili" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="study"><b>Pendidikan</b></label>
                  <input type="text" class="form-control toUpperCase" name="study" placeholder="Pendidikan" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label for="job"><b>Pekerjaan</b></label>
                  <input type="text" class="form-control toUpperCase" name="job" placeholder="Pekerjaan" value="" disabled>
                </div>
              </div>
            </div>
          </div>
          <div class="panel-heading">
            <p style="font-size:16px;padding-left:3px;">Hasil Pemeriksaan Spesimen</p>
          </div>
          <div class="panel-body" style="padding:13px;">
            <div class="table-responsive">
              <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblSwab" width="100%">
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
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="panel-heading">
            <p style="font-size:16px;padding-left:3px;">Riwayat Lainnya</p>
          </div>
          <div class="panel-body" style="padding:13px;">
            <div class="row">
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <label for="gejala"><b>Gejala yang dialami :</b></label>
                  <div style="margin-top:-15px;">
                    <?php
                      foreach ($data_gejala as $key => $dg) {
                        if($dg['tp_field']=='radio') {
                          echo '<div class="checkbox">';
                            echo '<input type="checkbox" name="'.$dg['nm_field'].'" value="" disabled>'.$dg['title'];
                          echo '</div>';
                        } else {
                          echo '<label><b>'.$dg['title'].'</b></label>';
                          echo '<input type="text" class="form-control" name="'.$dg['nm_field'].'" disabled>';
                        }
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <label for="penyerta"><b>Penyakit Penyerta (Komorbiditas) :</b></label>
                  <div style="margin-top:-15px;">
                    <?php
                      foreach ($data_penyerta as $key => $dp) {
                        if($dp['tp_field']=='checkbox') {
                          echo '<div class="checkbox">';
                            echo '<input type="checkbox" name="'.$dp['nm_field'].'" value="" disabled>'.$dp['title'];
                          echo '</div>';
                        } else {
                          echo '<label><b>'.$dp['title'].'</b></label>';
                          echo '<input type="text" class="form-control" name="'.$dp['nm_field'].'" disabled>';
                        }
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <label for="riwayatperjalanan"><b>Memiliki riwayat perjalanan dalam 14 hari sebelum sakit :</b></label>
                  <input type="text" class="form-control" name="riwayatperjalanan" value="" disabled>
                  <div class="fieldJalan" style="margin-top:5px;margin-bottom:-15px;">
                    <label><b>Jakarta (Indonesia) <br/>13/04/2020 - 20/04/2020</b></label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="kontaksuspekcovid19"><b>Memiliki kontak erat dengan kasus suspek COVID-19 dalam 14 hari sebelum sakit :</b></label>
                  <input type="text" class="form-control" name="kontaksuspekcovid19" value="" disabled>
                </div>
                <div class="form-group">
                  <label for="kontakcovid19"><b>Memiliki kontak erat dengan kasus konfirmasi COVID-19 dalam 14 hari sebelum sakit :</b></label>
                  <input type="text" class="form-control" name="kontakcovid19" value="" disabled>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="alert alert-warning" style="margin-bottom:0px;font-size:15px;">
                  <p><strong>Informasi!</strong> untuk melakukan proses verifikasi silakan diklik tombol <b>VERIFIKASI DATA</b> dibawah...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="margin-top:-20px;padding:10px 10px 15px 15px;border-top:0px;">
        <div class="btn-toolbar">
          <div class="pull-left"></div>
          <div class="pull-right"></div>
        </div>
      </div>
      <?php echo form_close(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';
  var flag     = 1;
  $(document).ready(function(e){
    $('#tblSwab').dataTable({
      "ordering" : false
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search Data');
    $('.dataTables_length select').addClass('form-control');
    getDataListKasus(flag);
    getDataAkumulasi();
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('a6be18f8aa19ab9f3828', {
      cluster: 'ap1',
      forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      if(data.message == 'casesuccess') {
        getDataListKasus(flag);
        getDataAkumulasi();
      }
    });
  });

  function run_waitMe(el) {
    el.waitMe({
      effect: 'bounce',
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

  $(document).on('click', '.btnTab', function(e) {
    var valId = $(this).attr('id');
    var arrId = valId.split('page');
    flag = arrId[1];
    getDataListKasus(flag);
  });

  $(document).on('click', '.btnFilter', function(e){
    $('#formFilter').slideToggle('slow');
  });

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

  $(document).on('change', '#asalpasien', function(e) {
    let id = $(this).val();
    if(id == 'Y')
      $('#province').select2('val', '').trigger('change');
    else
      $('#province').select2('val', 13).trigger('change');
  });
  $('#asalpasien').trigger('change');
  
  $('#formFilter').submit(function(e){
    e.preventDefault();
    getDataListKasus(flag);
  });

  $(document).on('click', '#cancel', function(e){
    e.preventDefault();
    $('form#formFilter').trigger('reset');
    $('.select-all').select2('val', '');
    getDataListKasus(flag);
  });

  //get data list
  function getDataListKasus(flag) {
    var tblName = '#tblKasus'+flag;
    $(tblName).dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "verifikasi-kasus/kasus/listview",
        "type": "POST",
        "data": {
          "flag"  : flag,
          "param" : $('#formFilter').serializeArray(),
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

  $(document).on('click', '.btnView', function(e){
    var id    = $(this).data('id');
    var token = $(this).data('token');
    var flag  = $(this).data('flag');
    $("#modalCaseForm").modal({
      backdrop: 'static'
    });
    if(flag==1) {
      $('.btn-toolbar').find('.pull-left').html('<button type="button" class="btn btn-default btnClose" style="padding:12px 16px;"><i class="fa fa-times"></i> CLOSE</button>');
      $('.btn-toolbar').find('.pull-right').html('<button type="submit" class="btn btn-danger" name="save" id="save" style="padding:12px 16px;"><i class="fa fa-check"></i> VERIFIKASI DATA</button>');
      $('#formCase').find('.alert-warning').show();
    } else {
      $('.btn-toolbar').find('.pull-left').html('');
      $('.btn-toolbar').find('.pull-right').html('<button type="button" class="btn btn-default btnClose" style="padding:12px 16px;"><i class="fa fa-times"></i> CLOSE</button>');
      $('#formCase').find('.alert-warning').hide();
    }
    getDataDetailPasien(id, token);
  });

  //close form entri
  $(document).on('click', '.btnClose', function(e) {
    $("#modalCaseForm").modal('toggle');
  });

  //get data pasien
  function getDataDetailPasien(caseId, tokenId) {
    run_waitMe($('#frmCase'));
    $.ajax({
      type: 'POST',
      url: site + 'verifikasi-kasus/kasus/details',
      data: {'case' : caseId, 'token' : tokenId, '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()},
      dataType: 'json',
      success: function(data) {
        var html = '';
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status == 1) {
          $('input[name="caseId"]').val(caseId);
          $('input[name="tokenId"]').val(tokenId);
          $.each(data.message.identitas, function(key, p){
            $('input[name="'+key+'"]').val(p);
            if(key == 'status') {
              $('#lblStatus').text(p.toUpperCase());
            }
            if(key == 'tindakan') {
              let = (p > 2) ? 'Rumah Sakit Rujukan Swab' : 'Rumah Sakit Rawat Saat Ini';
              $('label[for="rsrawat"]').html('<b>'+let+'</b>');
            }
            if(key == 'jnskasus') {
              hideShow(p);
            }
            if(key== 'perjalanan'){
              (p == '') ? $('.fieldJalan').removeAttr('style').html('') : $('.fieldJalan').attr('style', 'margin-top:5px;').html('<div class="alert alert-info" style="padding:5px;"><b>'+p+'</b></label>');
            }
          });
          $.each(data.message.klinis, function(key, k){
            if(key=='gejalalainnya' || key=='kondisipenyertalainnya') {
              $('input[name="'+key+'"]').val(k);
            } else {
              $('input[name="'+key+'"]').prop('checked', (k=='Y') ? true : false);
            }
          });
          var i = 1;
          $.each(data.message.swab, function(key, s){
            html += '<tr>';
              html += '<td width="3%">'+i+'</td>';
              html += '<td width="15%">'+s.kode_swab+'</td>';
              html += '<td width="10%">'+s.tgl_kirim+'</td>';
              html += '<td width="9%">'+s.hari_ke+'</td>';
              html += '<td width="20%">'+s.spesimen+'</td>';
              html += '<td width="10%">'+s.tgl_ambil+'</td>';
              html += '<td width="10%">'+s.status+'</td>';
              html += '<td width="10%">'+s.hasil+'</td>';
              html += '<td width="10%">'+s.tgl_keluar+'</td>';
            html += '</tr>';
            i++;
          });
          $('#tblSwab > tbody').html(html);
        } else {
          $('.fieldMeninggal').hide();
          $('.fieldSembuh').hide();
        }
        $('#frmCase').waitMe('hide');
      }
    });
  }

  function hideShow(id) {
    if(id == 2) {
      $('.fieldRawat').hide();
      $('.fieldMeninggal').hide();
      $('.fieldSembuh').show();
    } else if(id == 3) {
      $('.fieldRawat').hide();
      $('.fieldMeninggal').show();
      $('.fieldSembuh').hide();
    } else {
      $('.fieldRawat').show();
      $('.fieldMeninggal').hide();
      $('.fieldSembuh').hide();
    }
  }
  //call form verifikasi
  $('#formCase').submit(function(e) {
    e.preventDefault();
    var postData = $(this).serialize();
    var formActionURL = site + 'verifikasi-kasus/kasus/approve';
    $('#save').html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $('#save').addClass('disabled');
    run_waitMe($('#frmCase'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda akan memverifikasi data ini ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
              $('#save').html('<i class="fa fa-check"></i> VERIFIKASI DATA');
              $('#save').removeClass('disabled');
              $('#frmCase').waitMe('hide');
            }
          }
        },
        "main" : {
          "label" : "<i class='fa fa-check'></i> Ya, Lanjutkan",
          "className" : "btn-primary",
          callback:function(response){
            if (response) {
              $.ajax({
                url: formActionURL,
                type: "POST",
                data: postData,
                dataType: "json",
              }).done(function(data) {
                $('input[name="'+csrfName+'"]').val(data.csrfHash);
                if(data.status == 0) {
                  $('#errCase').html('<div class="alert alert-danger"> '+
                                      '<strong>Peringatan!</strong> '+ data.message +
                                     '</div>');
                } else {
                  $('#errSuccess').html('<div class="alert alert-dismissable alert-success">'+
                                          '<strong>Sukses!</strong> '+ data.message +
                                          '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                        '</div>');
                  $("#modalCaseForm").modal('toggle');
                  getDataListKasus(flag);
                  getDataAkumulasi();
                }
                $('#frmCase').waitMe('hide');
              }).fail(function() {
                $('#errCase').html('<div class="alert alert-danger">'+
                                      '<strong>Peringatan!</strong> Harap periksa kembali data yang akan diproses...'+
                                    '</div>');
              }).always(function() {
                $('#save').html('<i class="fa fa-check"></i> VERIFIKASI DATA');
                $('#save').removeClass('disabled');
              });
            }
          }
        }
      }
    });
  });

  function getDataAkumulasi() {
    $.ajax({
      type: 'GET',
      url: site + 'verifikasi-kasus/kasus/akumulasi',
      dataType: 'json',
      success: function(data) {
        let pdp = data.pdp_a - data.pdp_b;
        let pos = data.pos_a - data.pos_b;
        $('#total_satu').text(pdp);
        $('#total_dua').text(data.pdp_b);
        $('#total_tiga').text(pos);
        $('#total_empat').text(data.pos_b);
        $.each(data.new, function(key, n){
          $('#new_'+key).text('+'+n);
        });
      }
    });
  }

  //mengambil data kab/kota
  function getRegency(provinceId) {
    let regeID = '<?php echo $this->input->post('regency', TRUE); ?>';
    let lblReg = '';
    $.ajax({
      type: 'GET',
      url: site + 'verifikasi-kasus/kasus/regency',
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
    let distID = '<?php echo $this->input->post('district', TRUE); ?>';
    let lblDis = '';
    $.ajax({
      type: 'GET',
      url: site + 'verifikasi-kasus/kasus/district',
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
    let villID = '<?php echo $this->input->post('village', TRUE); ?>';
    let lblVil = '';
    $.ajax({
      type: 'GET',
      url: site + 'verifikasi-kasus/kasus/village',
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

</script>

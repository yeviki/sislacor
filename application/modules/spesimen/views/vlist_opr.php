<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-primary" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">TOTAL SPESIMEN</div>
              <div class="pull-right" id="new_all">+0</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-line-chart"></i></div>
              <div class="pull-right" id="tot_all">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-orange" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">INCONCLUSIVE</div>
              <div class="pull-right" id="new_I">+0</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-refresh"></i></div>
              <div class="pull-right" id="tot_I">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-green" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">NEGATIF</div>
              <div class="pull-right" id="new_N">+0</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-minus-square"></i></div>
              <div class="pull-right" id="tot_N">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-danger" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">POSITIF</div>
              <div class="pull-right" id="new_P">+0</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-plus-square"></i></div>
              <div class="pull-right" id="tot_P">0</div>
            </div>
          </a>
        </div>
      </div>
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
                <div class="col-xs-12 col-sm-5">
                  <div class="form-group">
                    <label for="nm_hospital" class="control-label"><b>Rumah Sakit</b></label>
                    <?php echo form_dropdown('nm_hospital', $data_hospital, $this->input->post('nm_hospital', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-7">
                  <div class="form-group">
                    <label for="laboratorium" class="control-label"><b>Laboratorium</b></label>
                    <?php echo form_dropdown('laboratorium', $data_labor, $this->input->post('laboratorium', TRUE), 'class="select-all param"');?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="tgl_kirim" class="control-label"><b>Tanggal Spesimen Masuk</b></label>
                    <div class="input-daterange input-group datemonth" style="width:100%;">
                      <input type="text" class="form-control mask param" name="tgl_kirim_awal" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_kirim_awal', TRUE); ?>">
                      <span class="input-group-addon">to</span>
                      <input type="text" class="form-control mask param" name="tgl_kirim_akhir" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_kirim_akhir', TRUE); ?>">
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="hasillab" class="control-label"><b>Hasil Lab</b></label>
                    <?php echo form_dropdown('hasillab', array(''=>'Hasil Lab', 'I'=>'INCONCLUSIVE', 'N'=>'NEGATIF', 'P'=>'POSITIF'), $this->input->post('hasillab', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="tglkeluar" class="control-label"><b>Tanggal Hasil Keluar</b></label>
                    <div class="input-daterange input-group datemonth" style="width:100%;">
                      <input type="text" class="form-control mask param" name="tgl_keluar_awal" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_keluar_awal', TRUE); ?>">
                      <span class="input-group-addon">to</span>
                      <input type="text" class="form-control mask param" name="tgl_keluar_akhir" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_keluar_akhir', TRUE); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                    <label for="harike" class="control-label" style="font-size:15px;"><b>Data Hari Ke</b></label>
                    <input type="number" class="form-control nominal" name="harike" placeholder="Hari ke" min="1" max="31" value="<?php echo $this->input->post('harike', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="kodeswab" class="control-label"><b>Kode Spesimen</b></label>
                    <input type="text" class="form-control param" name="kodeswab" placeholder="Kode Spesimen" value="<?php echo $this->input->post('kode_swab', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="nm_spesimen" class="control-label"><b>Spesimen</b></label>
                    <?php echo form_dropdown('nm_spesimen', $data_spesimen, $this->input->post('nm_spesimen', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tglambil" class="control-label"><b>Tanggal Pengambilan</b></label>
                    <div class="input-group date datemonth">
                      <input type="text" class="form-control mask param" name="tglambil" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tglambil', TRUE); ?>">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="nm_pasien" class="control-label"><b>Nama Pasien</b></label>
                    <input type="text" class="form-control toUpperCase param" name="nm_pasien" placeholder="Nama Pasien" value="<?php echo $this->input->post('nm_pasien', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="nik_pasien" class="control-label"><b>NIK</b></label>
                    <input type="number" class="form-control nominal param" name="nik_pasien" placeholder="NIK" value="<?php echo $this->input->post('nik_pasien', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="umur_pasien"><b>Umur</b></label>
                    <input type="number" class="form-control nominal" name="umur_pasien" placeholder="Umur" value="<?php echo $this->input->post('umur_pasien', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="gender" class="control-label"><b>Jenis Kelamin</b></label>
                    <?php echo form_dropdown('gender', array(''=>'Pilih Jenis Kelamin', 1=>'Laki-laki', 2=>'Perempuan'), $this->input->post('gender', TRUE), 'class="select-all param"'); ?>
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
          <h4><b>REKAP DATA SPESIMEN</b></h4>
          <div class="options">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#home1" data-toggle="tab" id="page1" class="btnTab"><i class="fa fa-clock-o"></i> Proses</a>
              </li>
              <li>
                <a href="#home2" data-toggle="tab" id="page2" class="btnTab"><i class="fa check"></i> Selesai</a>
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Daftar spesimen baru yang dikirim dari Rumah Sakit'; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblSwab1" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="18%">Rumah Sakit</th>
                          <th width="8%">Tgl. Kirim</th>
                          <th width="9%">Data</th>
                          <th width="12%">Kode Spesimen</th>
                          <th width="21%">Spesimen</th>
                          <th width="8%">Tgl. Ambil</th>
                          <th width="18%">Identitas</th>
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Daftar spesimen yang telah selesai menjalani proses pemeriksaan'; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblSwab2" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="21%">Rumah Sakit</th>
                          <th width="10%">Tgl. Kirim</th>
                          <th width="9%">Data</th>
                          <th width="13%">Kode Spesimen</th>
                          <th width="21%">Identitas</th>
                          <th width="10%">Tgl. Keluar</th>
                          <th width="10%">Hasil Lab</th>
                          <th width="3%">View</th>
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

<div class="modal fade bs-example-modal-lg in" id="modalSpecimenForm" tabindex="-1" role="dialog" aria-labelledby="modalSpecimenLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmSpecimen">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 13px 10px 13px;">
        <button type="button" class="close btnClose" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Form Hasil Pemeriksaan Spesimen</h4>
      </div>
      <?php echo form_open(site_url('#'), array('id' => 'formSpecimen')); ?>
      <div class="modal-body" style="padding:0px;">
        <table cellspacing="0" cellpadding="0" class="table" width="100%">
          <tbody>
            <tr style="background-color:#F5F5F5;">
              <td>
                <div class="row" style="padding-left:3px;padding-right:3px;">
                  <div class="col-xs-12">
                    <h4 style="margin-top:0px;"><b>Data spesimen yang diperiksa</b></h4>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group required">
                      <label class="control-label" for="kode_swab"><b>Kode Spesimen</b></label>
                      <input type="text" class="form-control" name="kode_swab" id="kode_swab" placeholder="Kode Spesimen" value="<?php echo $this->input->post('kode_swab', TRUE); ?>" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group required">
                      <label class="control-label" for="spesimen"><b>Nama Spesimen</b></label>
                      <input type="text" class="form-control toUpperCase" name="spesimen" id="spesimen" placeholder="Spesimen" value="<?php echo $this->input->post('spesimen', TRUE); ?>" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
                <div class="row" style="padding-left:3px;padding-right:3px;">
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group required">
                      <label for="tgl_ambil" class="control-label"><b>Tgl. Pengambilan Spesimen</b></label>
                      <div class="input-group date datemonth">
                        <input type="text" class="form-control mask" name="tgl_ambil" id="tgl_ambil" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_ambil', TRUE); ?>" disabled>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label for="hari_ke" class="control-label"><b>Pengambilan spesimen hari ke</b></label>
                      <input type="text" class="form-control" name="hari_ke" id="hari_ke" placeholder="Hari ke" value="<?php echo $this->input->post('hari_ke', TRUE); ?>" disabled>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr style="background-color:#D1FFE3">
              <td>
                <div class="row" style="padding-left:3px;padding-right:3px;">
                  <div class="col-xs-12 col-sm-2">
                    <div class="form-group required">
                      <label for="hasil_lab" class="control-label" style="margin-bottom:0px;"><b>Hasil Lab</b></label>
                      <?php foreach (array('P'=>'POSITIF', 'N'=>'NEGATIF', 'I'=>'INCONCLUSIVE') as $key => $hl): ?>
                        <div class="radio">
                          <label>
                            <input type="radio" name="hasil_lab" id="<?php echo 'hasil_lab_'.$key ?>" value="<?php echo $key; ?>" disabled><b><?php echo $hl; ?></b>
                          </label>
                        </div>
                      <?php endforeach; ?>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label for="keterangan" class="control-label"><b>Keterangan Lain</b></label>
                      <textarea class="form-control" placeholder="Keterangan" name="keterangan" id="keterangan" rows="5" disabled></textarea>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <div class="form-group required">
                      <label for="tgl_kirim" class="control-label"><b>Tgl. Spesimen Masuk</b></label>
                      <div class="input-group date datemonth">
                        <input type="text" class="form-control mask" name="tgl_kirim" id="tgl_kirim" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="" disabled>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                      <div class="help-block"></div>
                    </div>
                    <div class="form-group required" style="margin-top:30px;">
                      <label for="tgl_keluar" class="control-label"><b>Tgl. Spesimen Keluar</b></label>
                      <div class="input-group date datemonth">
                        <input type="text" class="form-control mask" name="tgl_keluar" id="tgl_keluar" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="" disabled>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr style="background-color:#F5F5F5;">
              <td>
                <div class="row" style="padding-left:3px;padding-right:3px;">
                  <div class="col-xs-12">
                    <h4 style="margin-top:0px;"><b>Identitas pemilik spesimen</b></h4>
                  </div>
                  <div class="col-xs-12 col-xs-4">
                    <div class="form-group required">
                      <label class="control-label" for="namalkp"><b>Nama Pasien</b></label>
                      <input type="text" class="form-control toUpperCase" name="namalkp" id="namalkp" placeholder="Nama Pasien" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xs-3">
                    <div class="form-group required">
                      <label class="control-label" for="nik"><b>NIK</b></label>
                      <input type="text" class="form-control" name="nik" id="nik" placeholder="NIK" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xs-2">
                    <div class="form-group required">
                      <label class="control-label" for="umur"><b>Umur</b></label>
                      <input type="text" class="form-control" name="umur" id="umur" placeholder="Umur (th)" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xs-3">
                    <div class="form-group required">
                      <label class="control-label" for="gender"><b>Jenis Kelamin</b></label>
                      <input type="text" class="form-control toUpperCase" name="gender" id="gender" placeholder="Jenis Kelamin" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
                <div class="row" style="padding-left:3px;padding-right:3px;">
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group required">
                      <label for="province" class="control-label"><b>Provinsi</b></label>
                      <input type="text" class="form-control toUpperCase" name="province" placeholder="Provinsi" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group required">
                      <label for="regency" class="control-label"><b>Kab/Kota</b></label>
                      <input type="text" class="form-control toUpperCase" name="regency" placeholder="Kab/Kota" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group required">
                      <label for="district" class="control-label"><b>Kecamatan</b></label>
                      <input type="text" class="form-control toUpperCase" name="district" placeholder="Kecamatan" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <div class="form-group required">
                      <label for="village" class="control-label"><b>Kelurahan</b></label>
                      <input type="text" class="form-control toUpperCase" name="village" placeholder="Kelurahan" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
                <div class="row" style="padding-left:3px;padding-right:3px;">
                  <div class="col-xs-12 col-sm-5">
                    <div class="form-group required">
                      <label for="hospital" class="control-label"><b>Rumah Sakit</b></label>
                      <input type="text" class="form-control toUpperCase" name="hospital" id="hospital" placeholder="Rumah Sakit" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-7">
                    <div class="form-group required">
                      <label for="labor" class="control-label"><b>Laboratorium</b></label>
                      <input type="text" class="form-control toUpperCase" name="labor" id="labor" placeholder="Laboratorium" value="" disabled>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer" style="margin-top:-20px;padding:10px 10px 15px 15px;">
        <button type="button" class="btn btn-default btnClose" style="padding:12px 16px;"><i class="fa fa-times"></i> CLOSE</button>
      </div>
      <?php echo form_close(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var strDate  = new Date('<?php echo date('Y-m-d', strtotime('2020-01-01')); ?>');
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';
  var flag     = 1;
  $(document).ready(function() {
    $('.mask').inputmask();
    $(".datemonth").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
      startDate: strDate
    });
    getDataListSpesimen(flag);
    getDataAkumulasi();
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('a6be18f8aa19ab9f3828', {
      cluster: 'ap1',
      forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      if(data.message == 'swabsuccess') {
        getDataListSpesimen(flag);
        getDataAkumulasi();
      }
    });
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

  $(document).on('click', '.btnTab', function(e) {
    var valId = $(this).attr('id');
    var arrId = valId.split('page');
    flag = arrId[1];
    getDataListSpesimen(flag);
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

  $('#formFilter').submit(function(e){
    e.preventDefault();
    getDataListSpesimen(flag);
  });

  $(document).on('click', '#cancel', function(e){
    e.preventDefault();
    $('form#formFilter').trigger('reset');
    $('.select-all').select2('val', '');
    getDataListSpesimen(flag);
  });

  //get data list
  function getDataListSpesimen(flag) {
    var tblName = '#tblSwab'+flag;
    $(tblName).dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "spesimen/pengujian/listview",
        "type": "POST",
        "data": {
          "flag" : flag,
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

  $(document).on('click', '.btnAdd', function(e) {
    formReset();
    var swabId  = $(this).data('id');
    var tokenId = $(this).data('token');
    var flag    = $(this).data('flag');
    $("#modalSpecimenForm").modal({
      backdrop: 'static'
    });
    getDataSpesimen(swabId, tokenId);
  });

  //close form entri
  $(document).on('click', '.btnClose', function(e) {
    formReset();
    $("#modalSpecimenForm").modal('toggle');
  });

  function formReset() {
    $('form#formSpecimen').trigger('reset');
  }

  function getDataSpesimen(swab, token) {
    run_waitMe($('#frmSpecimen'));
    $.ajax({
      type: 'POST',
      url: site + 'spesimen/pengujian/details',
      data: {'swab' : swab, 'token' : token, '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status == 1) {
          $.each(data.message, function(key, s){
            if(key == 'hasil_lab') {
              $('input[name="'+key+'"][value="'+s+'"]').prop('checked', (data.message.status == 1) ? false : true);
            } else {
              $('input[name="'+key+'"]').val(s);
              if(key=='keterangan')
                $('textarea[name="'+key+'"]').val(s);
            }
          });
        }
        $('#frmSpecimen').waitMe('hide');
      }
    });
  }

  function getDataAkumulasi() {
    $.ajax({
      type: 'GET',
      url: site + 'spesimen/pengujian/akumulasi',
      dataType: 'json',
      success: function(data) {
        let total = 0, ntot = 0;
        $.each(data.total, function(key, t){
          total =  total + t;
          $('#tot_'+key).text(t);
        });
        $('#tot_all').text(total);
        $.each(data.today, function(key, d){
          ntot = ntot + d;
          $('#new_'+key).text('+'+d);
        });
        $('#new_all').text('+'+ntot);
      }
    });
  }

  //mengambil data kab/kota
  function getRegency(provinceId) {
    let regeID = '<?php echo $this->input->post('regency', TRUE); ?>';
    let lblReg = '';
    $.ajax({
      type: 'GET',
      url: site + 'spesimen/pengujian/regency',
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
      url: site + 'spesimen/pengujian/district',
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
      url: site + 'spesimen/pengujian/village',
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

  $(document).on('keypress keyup', '.nominal',function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });
</script>

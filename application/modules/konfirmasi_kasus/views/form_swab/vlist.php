<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="pull-left">
        <div class="btn-toolbar" style="margin-bottom: 15px">
          <a type="button" href="<?php echo site_url('konfirmasi-kasus/spesimen'); ?>" class="btn btn-inverse" name="button" style="padding:12px 16px;"><b><i class="fa fa-table"></i> Data Kasus</b></a>
          <button type="button" class="btn btn-primary-alt" id="btnAdd" style="padding:11px 16px;"><b><i class="fa fa-plus"></i> Tambah Baru</b></button>
        </div>
      </div>
      <div class="pull-right">
        <h3 style="margin:10px 0px 0px 0px;"><b><?php echo ($this->app_loader->is_admin()) ? 'DINAS KESEHATAN PROVINSI SUMATERA BARAT' : hospital($this->app_loader->current_hospital(), 1); ?></b></h3>
      </div>
    </div>
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
    <div class="col-xs-12 col-sm-12">
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errSukses"></div>
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
                <div class="col-xs-12 col-sm-6">
                  <div class="form-group">
                    <label for="hospital" class="control-label"><b>Rumah Sakit</b></label>
                    <?php echo form_dropdown('hospital', $data_hospital, $this->input->post('hospital', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                  <div class="form-group">
                    <label for="laboratorium" class="control-label"><b>Laboratorium</b></label>
                    <?php echo form_dropdown('laboratorium', $data_labor, $this->input->post('laboratorium', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                    <label for="hari_ke" class="control-label" style="font-size:15px;"><b>Pengambilan Hari Ke</b></label>
                    <input type="number" class="form-control nominal" name="hari_ke" placeholder="Hari ke" min="1" max="31" value="<?php echo $this->input->post('kode_swab', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="kode_swab" class="control-label"><b>Kode Spesimen</b></label>
                    <input type="text" class="form-control param" name="kode_swab" placeholder="Kode Spesimen" value="<?php echo $this->input->post('kode_swab', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="spesimen" class="control-label"><b>Spesimen</b></label>
                    <?php echo form_dropdown('spesimen', $data_spesimen, $this->input->post('spesimen', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tgl_ambil" class="control-label"><b>Tanggal Pengambilan</b></label>
                    <div class="input-group date datemonth">
                      <input type="text" class="form-control mask param" name="tgl_ambil" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_ambil', TRUE); ?>">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tgl_kirim" class="control-label"><b>Tanggal Pengiriman</b></label>
                    <div class="input-group date datemonth">
                      <input type="text" class="form-control mask param" name="tgl_kirim" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_kirim', TRUE); ?>">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="status" class="control-label"><b>Status</b></label>
                    <?php echo form_dropdown('status', array(''=>'Status', 1=>'DIKIRIM', 2=>'DITERIMA'), $this->input->post('spesimen', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="hasil_lab" class="control-label"><b>Hasil Lab</b></label>
                    <?php echo form_dropdown('hasil_lab', array(''=>'Hasil Lab', 'I'=>'INCONCLUSIVE', 'N'=>'NEGATIF', 'P'=>'POSITIF'), $this->input->post('spesimen', TRUE), 'class="select-all param"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tgl_keluar" class="control-label"><b>Tanggal Keluar</b></label>
                    <div class="input-group date datemonth">
                      <input type="text" class="form-control mask param" name="tgl_keluar" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgl_keluar', TRUE); ?>">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="namalkp" class="control-label"><b>Nama Pasien</b></label>
                    <input type="text" class="form-control toUpperCase param" name="namalkp" id="namalkp" placeholder="Nama Pasien" value="<?php echo $this->input->post('namalkp', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="nik" class="control-label"><b>NIK</b></label>
                    <input type="number" class="form-control nominal param" name="nik" id="nik" placeholder="NIK" value="<?php echo $this->input->post('nik', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="umur"><b>Umur</b></label>
                    <input type="number" class="form-control nominal" name="umur" id="umur" placeholder="Umur" value="<?php echo $this->input->post('umur', TRUE); ?>">
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
          <h4><b>DAFTAR PENGIRIMAN SPESIMEN</b></h4>
        </div>
        <div class="panel-body collapse in">
          <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblSpesimen" width="100%">
              <thead>
                <tr>
                  <th width="3%">No.</th>
                  <th width="15%">Kode Spesimen</th>
                  <th width="10%">Tgl Kirim</th>
                  <th width="9%">Data</th>
                  <th width="20%">Spesimen</th>
                  <th width="22%">Identitas</th>
                  <th width="8%">Status</th>
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

<div class="modal fade bs-example-modal-lg in" id="modalSpesimenForm" tabindex="-1" role="dialog" aria-labelledby="modalSpesimenLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmSpesimen">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 15px 10px 15px;">
        <button type="button" class="close btnClose" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>FORMULIR PENGIRIMAN SPESIMEN</b</h4>
      </div>
      <?php echo form_open(site_url('konfirmasi-kasus/spesimen/create'), array('id' => 'formSpesimen')); ?>
      <div class="modal-body" style="padding:15px 15px 5px 15px;">
        <?php echo form_hidden('swabId', ''); ?>
        <div id="errSpesimen"></div>
        <div class="row">
          <div class="col-xs-12 col-sm-5">
            <div class="form-group required">
              <label for="hospital" class="control-label" style="font-size:15px;"><b>Rumah Sakit <font color="red">*</font></b></label>
              <?php echo form_dropdown('hospital', $data_hospital, $this->input->post('hospital', TRUE), 'class="select-all" id="hospital"'); ?>
              <div class="help-block"></div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-7">
            <div class="form-group required">
              <label for="laboratorium" class="control-label" style="font-size:15px;"><b>Laboratorium <font color="red">*</font></b></label>
              <?php echo form_dropdown('laboratorium', $data_labor, $this->input->post('laboratorium', TRUE), 'class="select-all" id="laboratorium"'); ?>
              <div class="help-block"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-5">
            <div class="form-group required">
              <label for="nm_pasien" class="control-label" style="font-size:15px;"><b>Nama Pasien <font color="red">*</font></b></label>
              <input type="text" class="searchdata" name="nm_pasien" id="nm_pasien" value="<?php echo $this->input->post('nm_pasien', TRUE); ?>">
              <?php echo form_hidden('pasien', ''); ?>
              <div class="help-block"></div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <div class="form-group required">
              <label for="hari_ke" class="control-label" style="font-size:15px;"><b>Data Hari Ke <font color="red">*</font></b></label>
              <input type="number" class="form-control nominal" name="hari_ke" id="hari_ke" placeholder="Hari ke" min="1" max="31" value="<?php echo $this->input->post('kode_swab', TRUE); ?>">
              <div class="help-block"></div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-5">
            <div class="form-group required">
              <label for="spesimen" class="control-label" style="font-size:15px;"><b>Jenis Spesimen <font color="red">*</font></b></label>
              <?php echo form_dropdown('spesimen', $data_spesimen, $this->input->post('spesimen', TRUE), 'class="select-all" id="spesimen"'); ?>
              <div class="help-block"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-4">
            <div class="form-group required">
              <label for="kode_swab" class="control-label" style="font-size:15px;"><b>Kode Spesimen <font color="red">*</font></b></label>
              <input type="text" class="form-control" name="kode_swab" id="kode_swab" placeholder="Kode Spesimen" value="<?php echo $this->input->post('kode_swab', TRUE); ?>">
              <div class="help-block"></div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <div class="form-group required">
              <label for="tgl_ambil" class="control-label" style="font-size:15px;"><b>Tanggal Pengambilan <font color="red">*</font></b></label>
              <div class="input-group date datemonth">
                <input type="text" class="form-control mask" name="tgl_ambil" id="tgl_ambil" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo !empty($this->input->post('tgl_ambil', TRUE)) ? $this->input->post('tgl_kirim', TRUE) : date('d/m/Y', strtotime(date('Y-m-d'))); ?>">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
              <div class="help-block"></div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <div class="form-group required">
              <label for="tgl_kirim" class="control-label" style="font-size:15px;"><b>Tanggal Pengiriman <font color="red">*</font></b></label>
              <div class="input-group date datemonth">
                <input type="text" class="form-control mask" name="tgl_kirim" id="tgl_kirim" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo !empty($this->input->post('tgl_kirim', TRUE)) ? $this->input->post('tgl_kirim', TRUE) : date('d/m/Y', strtotime(date('Y-m-d'))); ?>">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
              <div class="help-block"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="margin-top:0px;padding:10px 15px 15px 0px;">
        <button type="button" class="btn btn-default btnClose" style="padding:12px 16px;"><i class="fa fa-times"></i> CANCEL</button>
        <button type="submit" class="btn btn-primary" name="save" id="save" style="padding:12px 16px;"><i class="fa fa-check"></i> SUBMIT</button>
      </div>
      <?php echo form_close(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade bs-example-modal-lg in" id="modalSwabForm" tabindex="-1" role="dialog" aria-labelledby="modalSwabLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmSwab">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 15px 10px 15px;">
        <button type="button" class="close btnCloseSwab" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>RINCIAN DATA SPESIMEN</b</h4>
      </div>
      <?php echo form_open(site_url('#'), array('id' => 'formSwab')); ?>
      <div class="modal-body" style="padding:15px 15px 5px 15px;">
        <div class="row">
          <div class="col-xs-12 col-sm-5">
            <div class="form-group">
              <label for="hospital" class="control-label" style="font-size:15px;"><b>Rumah Sakit</b></label>
              <?php echo form_dropdown('rsud', $data_hospital, $this->input->post('rsud', TRUE), 'class="select-all" disabled=""'); ?>
            </div>
          </div>
          <div class="col-xs-12 col-sm-7">
            <div class="form-group">
              <label for="laboratorium" class="control-label" style="font-size:15px;"><b>Laboratorium</b></label>
              <?php echo form_dropdown('labor', $data_labor, $this->input->post('labor', TRUE), 'class="select-all" disabled=""'); ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-5">
            <div class="form-group">
              <label for="nmpasien" class="control-label" style="font-size:15px;"><b>Nama Pasien</b></label>
              <input type="text" class="form-control" name="nmpasien" placeholder="Nama Pasien" disabled="">
            </div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <div class="form-group">
              <label for="harike" class="control-label" style="font-size:15px;"><b>Data Hari Ke</b></label>
              <input type="number" class="form-control nominal" name="harike" placeholder="Hari ke" min="1" max="31" disabled="">
            </div>
          </div>
          <div class="col-xs-12 col-sm-5">
            <div class="form-group">
              <label for="jnsswab" class="control-label" style="font-size:15px;"><b>Jenis Spesimen</b></label>
              <?php echo form_dropdown('jnsswab', $data_spesimen, $this->input->post('jnsswab', TRUE), 'class="select-all" disabled=""'); ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-3">
            <div class="form-group">
              <label for="kodeswab" class="control-label" style="font-size:15px;"><b>Kode Spesimen</b></label>
              <input type="text" class="form-control" name="kodeswab" placeholder="Kode Spesimen" disabled="">
            </div>
          </div>
          <div class="col-xs-12 col-sm-3">
            <div class="form-group">
              <label for="tglambil" class="control-label" style="font-size:15px;"><b>Tgl Pengambilan</b></label>
              <div class="input-group date datemonth">
                <input type="text" class="form-control mask" name="tglambil" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" disabled="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-3">
            <div class="form-group">
              <label for="tglkirim" class="control-label" style="font-size:15px;"><b>Tgl Pengiriman</b></label>
              <div class="input-group date datemonth">
                <input type="text" class="form-control mask" name="tglkirim" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" disabled="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-3">
            <div class="form-group">
              <label for="tglkeluar" class="control-label" style="font-size:15px;"><b>Tgl Hasil Keluar</b></label>
              <div class="input-group date datemonth">
                <input type="text" class="form-control mask" name="tglkeluar" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" disabled="">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-4">
            <div class="form-group">
              <label for="statusswab" class="control-label" style="font-size:15px;"><b>Status</b></label>
              <?php echo form_dropdown('statusswab', array(1=>'DIKIRIM', 2=>'DITERIMA'), $this->input->post('statusswab', TRUE), 'class="select-all" disabled=""'); ?>
            </div>
            <div class="form-group" style="margin-top:-7px;">
              <label for="hasillab" class="control-label" style="font-size:15px;"><b>Hasil Lab</b></label>
              <?php echo form_dropdown('hasillab', array('I'=>'INCONCLUSIVE', 'N'=>'NEGATIF', 'P'=>'POSITIF'), $this->input->post('hasillab', TRUE), 'class="select-all" disabled=""'); ?>
            </div>
          </div>
          <div class="col-xs-12 col-sm-8">
            <div class="form-group">
              <label for="keterangan" class="control-label" style="font-size:15px;"><b>Keterangan Lainnya</b></label>
              <textarea name="keterangan" class="form-control" rows="5" disabled=""></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="margin-top:0px;padding:10px 15px 15px 0px;">
        <button type="button" class="btn btn-default btnCloseSwab" style="padding:12px 16px;"><i class="fa fa-times"></i> CLOSE</button>
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

  $(document).ready(function() {
    $('.mask').inputmask();
    $('.datemonth').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      startView: 'month',
      startDate: strDate
    });
    $('#province').select2('val', 13).trigger('change');
    $('select[name="hospital"]').select2('val', '<?php echo $kode_rs; ?>').trigger('change');
    getDataListSpesimen();
    getDataAkumulasi();
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('a6be18f8aa19ab9f3828', {
      cluster: 'ap1',
      forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      if(data.message == 'swabresult') {
        getDataListSpesimen();
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

  $(document).on('click', '#btnAdd', function(e){
    formReset();
    $("#modalSpesimenForm").modal({
      backdrop: 'static'
    });
  });

  $(document).on('click', '.btnView', function(e){
    formReset();
    var id = $(this).data('id');
    var token = $(this).data('token');
    if($(this).data('flag') == 1) {
      $('#formSpesimen').attr('action', site + 'konfirmasi-kasus/spesimen/update');
      $("#modalSpesimenForm").modal({
        backdrop: 'static'
      });
      getDataSpesimen(id, token);
    } else {
      $("#modalSwabForm").modal({
        backdrop: 'static'
      });
      getDataSpesimenSwab(id, token);
    }
  });

  //close form entri
  $(document).on('click', '.btnClose', function(e) {
    formReset();
    $("#modalSpesimenForm").modal('toggle');
  });

  //close form entri
  $(document).on('click', '.btnCloseSwab', function(e) {
    $("#modalSwabForm").modal('toggle');
  });

  function formReset() {
    $('#errSpesimen').html('');
    $('#hospital').select2('val', '');
    $('#laboratorium').select2('val', '');
    $('#nm_pasien').select2('val', '');
    $('#pasien').val('');
    $('#hari_ke').val('');
    $('#kode_swab').val('');
    $('#spesimen').select2('val', '');
    $('.help-block').text('');
    $('.required').removeClass('has-error');
    $('#hospital').removeAttr('disabled');
    $('#nm_pasien').removeAttr('disabled');
  }

  $('.searchdata').select2({
    dropdownAutoWidth: false,
    width: 'element',
    placeholder: 'Cari data pasien menggunakan nama atau nik',
    minimumInputLength: 0,
    allowClear: true,
    width: '100%',
    dropdownCssClass: "bigdrop",
    ajax: {
      url: site + 'konfirmasi-kasus/spesimen/searching',
      dataType: "json",
      quietMillis: 250,
      data: function (term, page) {
        return {
          q: term,
          rs: $('select[name="hospital"]#hospital option:selected').val(),
          page: page
        };
      },
      results: function (data, page) {
        page = page || 1;
        var results = [];
        $.each(data.items, function(key, val){
          results.push({
            id: val.nama+' ['+val.nik+']',
            text: val.nama+' ['+val.nik+']',
            pasien: val.token,
            nama: val.nama,
            nik: val.nik,
            umur: val.umur,
            gender: val.gender
          });
        });
        return {
          results: results,
          pagination: {
		  			more: (page * 30) < data.total_count
		  		}
        };
      },
      cache: true
    },
    initSelection : function (element, callback) {
      var data = {id: element.val(), text: element.val()};
      callback(data);
    },
    formatNoMatches: function(term){
      return 'Data pasien "' + term + '" tidak ditemukan';
    },
    formatResult: repoFormatResult,
    formatSelection: repoFormatSelection,
    escapeMarkup: function (m) { return m; }
  }).on("select2-selecting", function(e) {
    $('input[name="pasien"]').val(e.object.pasien);
  }).on("select2-removed", function(e) {
    $('input[name="pasien"]').val('');
  });

  function repoFormatResult(repo) {
    var markup = '<div class="row">' +
                   '<div class="col-xs-12 col-sm-12">' +
                      '<div><strong>' + repo.nama + ' ('+repo.umur+' th)' + '</strong></div>' +
                      '<div>' + repo.nik + '</div>' +
                      '<div>' + repo.gender + '</div>' +
                    '</div>'+
                  '</div>';
    return markup;
  }

  function repoFormatSelection(repo) {
    return repo.text;
  }

  function getDataSpesimen(swab, token) {
    run_waitMe($('#frmSpesimen'));
    $.ajax({
      type: 'POST',
      url: site + 'konfirmasi-kasus/spesimen/details',
      data: {'swab' : swab, 'token' : token, '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status == 1) {
          $('input[name="swabId"]').val(swab);
          $('input[name="pasien"]').val(token);
          $('#hospital').select2('val', data.message.hospital).trigger('change');
          $('#laboratorium').select2('val', data.message.labor).trigger('change');
          $('#nm_pasien').select2('val', data.message.pasien).trigger('change');
          $('#hari_ke').val(data.message.hari);
          $('#kode_swab').val(data.message.kode);
          $('#spesimen').select2('val', data.message.spesimen).trigger('change');
          $('#tgl_ambil').val(data.message.ambil);
          $('#tgl_kirim').val(data.message.kirim);
          $('#hospital').attr('disabled', true);
          $('#nm_pasien').attr('disabled', true);
        }
        $('#frmSpesimen').waitMe('hide');
      }
    });
  }

  function getDataSpesimenSwab(swab, token) {
    run_waitMe($('#frmSwab'));
    $.ajax({
      type: 'POST',
      url: site + 'konfirmasi-kasus/spesimen/details',
      data: {'swab' : swab, 'token' : token, '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status == 1) {
          $('select[name="rsud"]').select2('val', data.message.hospital).trigger('change');
          $('select[name="labor"]').select2('val', data.message.labor).trigger('change');
          $('input[name="nmpasien"]').val(data.message.pasien);
          $('input[name="harike"]').val(data.message.hari);
          $('input[name="kodeswab"]').val(data.message.kode);
          $('select[name="jnsswab"]').select2('val', data.message.spesimen).trigger('change');
          $('input[name="tglambil"]').val(data.message.ambil);
          $('input[name="tglkirim"]').val(data.message.kirim);
          $('input[name="tglkeluar"]').val(data.message.keluar);
          $('select[name="statusswab"]').select2('val', data.message.status).trigger('change');
          $('select[name="hasillab"]').select2('val', data.message.hasil).trigger('change');
          $('textarea[name="keterangan"]').val(data.message.ket);
        }
        $('#frmSwab').waitMe('hide');
      }
    });
  }

  $('#formSpesimen').submit(function(e){
    e.preventDefault();
    var postData = $(this).serialize();
    var formActionURL = $(this).attr('action');
    $("#save").html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $("#save").addClass('disabled');
    run_waitMe($('#frmSpesimen'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda akan mengirimkan spesimen dari pasien <b>"+$('input[name="nm_pasien"]').val()+"</b> ke laboratorium untuk diperiksa ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
              $('#frmSpesimen').waitMe('hide');
              $('#save').html('Selesai <i class="fa fa-check"></i> SUBMIT');
              $('#save').removeClass('disabled');
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
                $('.help-block').text('');
                $('.required').removeClass('has-error');
                if(data.status == 0) {
                  $('#errSpesimen').html('<div class="alert alert-danger" id="pesanErr"><strong>Peringatan!</strong> Tolong dilengkapi form inputan dibawah...</div>');
                  $.each(data.message, function(key,value){
                    if(key != 'isi') {
                      $('input[name="'+key+'"], select[name="'+key+'"]').closest('div.required').addClass('has-error').find('div.help-block').text(value);
                    } else {
                      $('#pesanErr').html('<strong>Peringatan!</strong> ' +value);
                    }
                  });
                  $('#modalSpesimenForm').animate({
                    scrollTop: (data.message.isi) ? 0 : ($('.has-error').find('input, select').first().focus().offset().top-300)
                  }, 'slow');
                } else {
                  formReset();
                  $('#errSukses').html('<div class="alert alert-dismissable alert-success">'+
                                        '<strong>Sukses!</strong> '+ data.message +
                                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                       '</div>');
                  $("#modalSpesimenForm").modal('toggle');
                  getDataListSpesimen();
                  getDataAkumulasi();
                }
                $('#frmSpesimen').waitMe('hide');
              }).fail(function() {
                $('#errSpesimen').html('<div class="alert alert-danger">'+
                                          '<strong>Peringatan!</strong> Harap periksa kembali data yang diinputkan...'+
                                        '</div>');
                $('#frmSpesimen').waitMe('hide');
              }).always(function() {
                $("#save").html('<i class="fa fa-check"></i> SUBMIT');
                $("#save").removeClass('disabled');
              });
            }
          }
        }
      }
    });
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
    getDataListSpesimen();
  });

  $(document).on('click', '#cancel', function(e){
    e.preventDefault();
    $('form#formFilter').trigger('reset');
    $('.select-all').select2('val', '');
    $('#province').select2('val', 13).trigger('change');
    $('select[name="hospital"]').select2('val', '<?php echo $kode_rs; ?>').trigger('change');
    getDataListSpesimen();
  });

  //mengambil data spesimen
  function getDataListSpesimen() {
    $('#tblSpesimen').dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "loadingRecords": "&nbsp;",
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Mecari Data...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "konfirmasi-kasus/spesimen/listview",
        "type": "POST",
        "data": {
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
    $('#tblSpesimen_filter input').addClass('form-control').attr('placeholder','Search Data');
    $('#tblSpesimen_length select').addClass('form-control');
  }

  //mengambil data akumulasi kasus
  function getDataAkumulasi() {
    $.ajax({
      type: 'GET',
      url: site + 'konfirmasi-kasus/spesimen/akumulasi',
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
      url: site + 'konfirmasi-kasus/spesimen/regency',
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
      url: site + 'konfirmasi-kasus/spesimen/district',
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
      url: site + 'konfirmasi-kasus/spesimen/village',
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

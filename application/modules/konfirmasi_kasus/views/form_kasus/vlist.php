<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="pull-left">
        <div class="btn-toolbar" style="margin-bottom: 15px">
          <a type="button" href="<?php echo site_url('konfirmasi-kasus/identifikasi'); ?>" class="btn btn-inverse" name="button" style="padding:12px 16px;"><b><i class="fa fa-table"></i> Data Kasus</b></a>
          <a type="button" href="<?php echo site_url('konfirmasi-kasus/identifikasi/create') ?>" class="btn btn-primary-alt" name="button" style="padding:11px 16px;"><b><i class="fa fa-plus"></i> Tambah Baru</b></a>
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
              <div class="pull-left">JUMLAH KASUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-line-chart"></i></div>
              <div class="pull-right" id="total_satu">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-orange" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">DIRAWAT</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-bed"></i></div>
              <div class="pull-right" id="total_dua">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-purple" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">DIRUJUK</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-ambulance"></i></div>
              <div class="pull-right" id="total_tiga">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-indigo" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">ISOLASI/KARANTINA</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-user-times"></i></div>
              <div class="pull-right" id="total_empat">0</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <?php echo $this->session->flashdata('message'); ?>
    </div>
    <div class="col-xs-12 col-sm-12" style="margin:-5px 0px 0px 0px;">
      <div class="row">
        <div class="col-xs-12 col-sm-3">
          <h3 style="font-weight:bold;text-align:left;;">
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
                    <label for="nokk"><b>NO KK</b></label>
                    <input type="number" class="form-control nominal param" name="nokk" id="nokk" placeholder="NO KK" value="<?php echo $this->input->post('nokk', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="nohp" class="control-label"><b>No HP</b></label>
                    <input type="number" class="form-control nominal param" name="nohp" id="nohp" placeholder="No HP" value="<?php echo $this->input->post('nohp', TRUE); ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tmptlhr" class="control-label"><b>Tempat Lahir</b></label>
                    <input type="text" class="form-control toUpperCase param" name="tmptlhr" id="tmptlhr" placeholder="Tempat Lahir" value="<?php echo $this->input->post('tmptlhr', TRUE); ?>">
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tgllhr" class="control-label"><b>Tanggal Lahir</b></label>
                    <div class="input-group date birthdate">
                      <input type="text" class="form-control mask param" name="tgllhr" id="tgllhr" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgllhr', TRUE); ?>">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
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
                <div class="col-xs-12 col-sm-5">
                  <div class="form-group">
                    <label for="address"><b>Alamat Domisili</b></label>
                    <input type="text" class="form-control toUpperCase param" name="address" id="address" placeholder="Alamat Domisili" value="<?php echo $this->input->post('address', TRUE); ?>" >
                  </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                    <label for="asalpasien" class="control-label"><b>Asal Pasien</b></label>
                    <?php echo form_dropdown('asalpasien', array(''=>'Pilih Asal Pasien', 'N'=>'Dari Dalam Provinsi', 'Y'=>'Dari Luar Provinsi'), $this->input->post('asalpasien', TRUE), 'class="select-all param" id="asalpasien"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                    <label for="study"><b>Pendidikan</b></label>
                    <?php echo form_dropdown('study', $data_study, $this->input->post('study', TRUE), 'class="select-all param" id="study"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="job"><b>Pekerjaan</b></label>
                    <input type="text" class="form-control toUpperCase param" name="job" id="job" placeholder="Pekerjaan" value="<?php echo $this->input->post('job', TRUE); ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="pasienstatus" class="control-label"><b>Status Pasien</b></label>
                    <?php echo form_dropdown('pasienstatus', array(''=>'Pilih Status', 2=>'Pasien Dalam Pengawasan', 3=>'Pasien Terkonfirmasi Covid-19'), $this->input->post('pasienstatus', TRUE), 'class="select-all param" id="pasienstatus"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="tindaklanjut" class="control-label"><b>Jenis Perawatan Pasien</b></label>
                    <?php echo form_dropdown('tindaklanjut', array(''=>'Pilih Perawatan', 1=>'Dirawat di Rumah Sakit', 2=>'Dirujuk ke RS Rujukan Pemerintah', 3=>'Isolasi diri di Rumah', 4=>'Isolasi di Fasilitas Pemerintah'), $this->input->post('tindaklanjut', TRUE), 'class="select-all param" id="tindaklanjut"'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                    <label for="rsrujukan" class="control-label"><b>RS Rujukan Pemerintah</b></label>
                    <?php echo form_dropdown('rsrujukan', $data_rsrujukan, $this->input->post('rsrujukan', TRUE), 'class="select-all param" id="rsrujukan" disabled=""'); ?>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                    <label for="nm_fasilitas" class="control-label"><b>Fasilitas Pemerintah</b></label>
                    <?php echo form_dropdown('nm_fasilitas', $data_fasilitas, $this->input->post('nm_fasilitas', TRUE), 'class="select-all param" id="nm_fasilitas" disabled=""'); ?>
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
          <h4><b>DAFTAR KASUS</b></h4>
        </div>
        <div class="panel-body collapse in">
          <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblKasus" width="100%">
              <thead>
                <tr>
                  <th width="3%">No.</th>
                  <th width="10%">Nomor RM</th>
                  <th width="18%">Nama Pasien</th>
                  <th width="5%">Umur</th>
                  <th width="3%">JK</th>
                  <th width="30%">Alamat</th>
                  <th width="10%">Status</th>
                  <th width="17%">Keterangan</th>
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
<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var strDate  = new Date('<?php echo date('Y-m-d', strtotime('1920-01-01')); ?>');
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';

  $(document).ready(function() {
    $('.mask').inputmask();
    $(".birthdate").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
      startDate: strDate
    });
    $('#province').select2('val', 13).trigger('change');
    getDataListCase();
    getDataAkumulasi();
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

  $(document).on('change', '#tindaklanjut', function(e){
    e.preventDefault();
    let id = $(this).val();
    if(id == 2) {
      $('#rsrujukan').removeAttr('disabled');
      $('#nm_fasilitas').attr('disabled', true);
    } else if(id == 4) {
      $('#nm_fasilitas').removeAttr('disabled');
      $('#rsrujukan').attr('disabled', true);
    } else {
      $('#rsrujukan').attr('disabled', true);
      $('#nm_fasilitas').attr('disabled', true);
    }
  });
  $('#tindaklanjut').trigger('change');

  $('#formFilter').submit(function(e){
    e.preventDefault();
    getDataListCase();
  });

  $(document).on('click', '#cancel', function(e){
    e.preventDefault();
    $('form#formFilter').trigger('reset');
    $('.select-all').select2('val', '');
    $('#province').select2('val', 13).trigger('change');
    getDataListCase();
  });

  //mengambil data pasien
  function getDataListCase() {
    $('#tblKasus').dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "loadingRecords": "&nbsp;",
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Mecari Data...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "konfirmasi-kasus/identifikasi/listview",
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
    $('#tblKasus_filter input').addClass('form-control').attr('placeholder','Search Data');
    $('#tblKasus_length select').addClass('form-control');
  }

  //mengambil data akumulasi kasus
  function getDataAkumulasi() {
    $.ajax({
      type: 'GET',
      url: site + 'konfirmasi-kasus/identifikasi/akumulasi',
      dataType: 'json',
      success: function(data) {
        $.each(data.message, function(key,value){
          $('#total_'+key).html(value);
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
    let distID = '<?php echo $this->input->post('district', TRUE); ?>';
    let lblDis = '';
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
    let villID = '<?php echo $this->input->post('village', TRUE); ?>';
    let lblVil = '';
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

  $(document).on('keypress keyup', '.nominal',function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });
</script>

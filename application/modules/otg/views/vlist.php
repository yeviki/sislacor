<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="btn-toolbar" style="margin-bottom: 15px">
        <a type="button" href="<?php echo site_url('otg'); ?>" class="btn btn-inverse" name="button" style="padding:12px 16px;"><b><i class="fa fa-table"></i> Data OTG</b></a>
        <?php if ($this->app_loader->is_admin()): ?>
        <button type="button" name="button" class="btn btn-primary-alt" id="btnAdd" style="padding:11px 16px;"><i class="fa fa-plus"></i> Entri Data OTG Baru</button>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-primary" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH OTG</div>
              <div class="pull-right" id="new_otg">+0</div>
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
              <div class="pull-left">OTG BARU</div>
              <div class="pull-right">per hari ini</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-plus-circle"></i></div>
              <div class="pull-right" id="total_dua">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-danger" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">BERUBAH STATUS</div>
              <div class="pull-right">per hari ini</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-external-link-square"></i></div>
              <div class="pull-right" id="total_tiga">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-green" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">OTG SELESAI</div>
              <div class="pull-right">per hari ini</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-check-circle"></i></div>
              <div class="pull-right" id="total_empat">0</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <?php $jam = floor((strtotime($deadline) - strtotime(date('Y-m-d H:i:s')))/(60*60)); ?>
      <div class="alert alert-warning">
        <p><font size="3em"><b>Informasi!</b></font></p>
        <p id="lblWarning" style="margin-top:0px;">
          <?php
            $totReg = count($regency) - 1;
            if($tot_otg == 0)
              echo 'Belum ada Kabupaten/Kota yang menginputkan data OTG per tanggal <b>'.$jadwal.'</b>';
            else if($tot_otg > 0 AND $tot_otg < $totReg)
              echo (($totReg-$tot_otg > (ceil($totReg/2)+1)) ? 'Baru' : 'Sudah').' <b>'.$tot_otg.'</b> Kabupaten/Kota yang menginputkan data OTG per tanggal <b>'.$jadwal.'</b>, masih ada <b>'.($totReg - $tot_otg).'</b> Kabupaten/Kota yang belum menginputkan data.';
            else
              echo 'Semua Kabupaten/Kota telah menginputkan data OTG per tanggal <b>'.$jadwal.'</b>';
          ?>
        </p>
      </div>
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errOtg"></div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h4><b>REKAP DATA OTG KAB/KOTA</b></h4>
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Rekap data OTG Kabupaten/Kota per tanggal'.' '.$jadwal; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblOtg1" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="20%">Nama Daerah</th>
                          <th width="47%">Rekap Data</th>
                          <th width="15%">Tgl. Input</th>
                          <th width="12%">Status</th>
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Rekap data OTG Kabupaten/Kota yang telah dipublish'; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblOtg2" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="20%">Nama Dearah</th>
                          <th width="44%">Rekap Data</th>
                          <th width="15%">Tgl. Input</th>
                          <th width="15%">Tgl. Publish</th>
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

<div class="modal fade bs-example-modal-lg in" id="modalRekapForm" tabindex="-1" role="dialog" aria-labelledby="modalRekapLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmRekap">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 20px 10px 20px;">
        <button type="button" class="close btnClose" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>FORM ENTRI REKAP DATA OTG</b></h4>
      </div>
      <?php echo form_open(site_url('otg/create'), array('id' => 'formRekap')); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-sm-12">
            <?php
              echo form_input(array('type'=>'hidden', 'name'=>'regencyId', 'id'=>'idRegency'));
              echo form_input(array('type'=>'hidden', 'name'=>'publishDate', 'id'=>'datePublish'));
            ?>
            <div class="alert alert-warning">
              <p>
                <font size="3em">
                  Silahkan diinputkan rekap data OTG per tanggal <strong><?php echo $jadwal; ?></strong>.
                  Batas waktu penginputan sampai <strong><?php echo tgl_login($deadline).' WIB.'; ?></strong>
                  <?php echo ($jam < 4 AND $data_otg <= 0) ? ' Waktu anda '.(($jam > 1) ? 'kurang dari '.$jam.' jam lagi.' : 'tidak sampai 1 jam lagi') : ''; ?>
                </font>
              </p>
            </div>
            <div id="errRekap"></div>
          </div>
          <?php if ($this->app_loader->is_admin()): ?>
            <div class="col-xs-12 col-sm-12">
              <div class="form-group">
                <label for="regency" class="control-label"><b>Nama Daerah</b> <span style="color:#f00;">*</span></label>
                <?php echo form_dropdown('regency', $regency, $this->input->post('regency'), 'class="select-all" id="regency" required="required"');?>
                <div id="regency_error"></div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="table-responsive">
          <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%" id="tblOtgInput">
            <thead>
              <tr>
                <th width="3%">No.</th>
                <th width="77%">Jenis Rekapitulasi</th>
                <th width="20%">Jumlah</th>
              </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach ($kategori as $key => $k): ?>
              <tr>
                <td><?php echo $i; ?>.</td>
                <td id="<?php echo 'otg_desc_'.$i; ?>"><?php echo $k['desc'].' per tanggal '.$jadwal; ?></td>
                <td>
                  <input type="text" class="form-control nominal" name="param[<?php echo $k['id']; ?>]" id="<?php echo 'otg_jum_'.$i; ?>" placeholder="Jumlah Data" value="<?php echo set_value('param['.$k['id'].']', ''); ?>" required="" oninvalid="this.setCustomValidity('Inputan wajib diisi')" oninput="setCustomValidity('')">
                </td>
              </tr>
            <?php $i++; endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer" style="margin-top:0px;padding:10px 20px 15px 0px;">
        <button type="button" class="btn btn-default btnClose" style="padding:12px 16px;"><i class="fa fa-times"></i> CANCEL</button>
        <button type="submit" class="btn btn-primary" name="save" id="save" style="padding:12px 16px;"><i class="fa fa-check"></i> SUBMIT</button>
      </div>
      <?php echo form_close(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade bs-example-modal-lg in" id="modalDetailForm" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmDetail">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 20px 10px 20px;">
        <button type="button" class="close btnHide" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>REKAP DATA OTG <span id="lblRegency"></span></b></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-xs-12" id="lblValidasi"></div>
        </div>
        <div id="errValid"></div>
        <div class="table-responsive">
          <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%" id="tblOtgDetail">
            <thead>
              <tr>
                <th width="3%">No.</th>
                <th width="75%">Jenis Rekapitulasi</th>
                <th width="10%">Jumlah</th>
                <th width="12%">Status</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer" style="margin-top:0px;padding:10px 20px 15px 0px;">
        <button type="button" class="btn btn-default btnHide" style="padding:12px 16px;"><i class="fa fa-times"></i> CLOSE</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';
  var totReg   = '<?php echo count($regency) - 1; ?>';
  var flag     = 1;
  $(document).ready(function(e){
    getDataListOtg(flag);
    getDataAkumulasi();
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('a6be18f8aa19ab9f3828', {
      cluster: 'ap1',
      forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      if(data.message == 'otgverified') {
        getDataListOtg(flag);
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
    getDataListOtg(flag);
  });

  function getDataListOtg(flag) {
    var tblName = '#tblOtg'+flag;
    $(tblName).dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "otg/listview",
        "type": "POST",
        "data": {
          "flag" : flag,
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
  //panggil form Entri
  $(document).on('click', '#btnAdd', function(e){
    formReset();
    $('#modalRekapForm').modal({
      backdrop: 'static'
    });
  });

  //close form entri
  $(document).on('click', '.btnClose', function(e) {
    formReset();
    $('#modalRekapForm').modal('toggle');
  });

  function formReset() {
    $('#formRekap').attr('action', site + 'otg/create');
    $('#errRekap').html('');
    $('.nominal').val('');
    $('#regency').select2('val', '');
    $('#idRegency').val('');
    $('#datePublish').val('');
    $('#regency').removeAttr('disabled');
  }

  $('#formRekap').submit(function(e) {
    e.preventDefault();
    var postData = $(this).serialize();
    // get form action url
    var formActionURL = $(this).attr("action");
    $("#save").html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $("#save").addClass('disabled');
    run_waitMe($('#frmRekap'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Sebelum anda melanjutkan proses untuk menyimpan data, pastikan data OTG yang anda inputkan sudah benar."+
                " Jika anda yakin datanya sudah benar silahkan melanjutkan proses ini, namun jika belum silahkan cek kembali data anda.",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if(response) {
              $("#save").html('<i class="fa fa-check"></i> SUBMIT');
              $("#save").removeClass('disabled');
              $('#frmRekap').waitMe('hide');
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
                  $('#errRekap').html('<div class="alert alert-danger"> '+
                                        '<strong>Peringatan!</strong> Proses input rekap data OTG '+ data.message.reg +' per tanggal '+ '<?php echo $jadwal ?>' + data.message.isi +
                                      '</div>');
                } else {
                  $('#errOtg').html('<div class="alert alert-dismissable alert-success">'+
                                      '<strong>Sukses!</strong> Rekap data OTG '+ data.message.reg +' per tanggal '+ '<?php echo $jadwal ?>' + data.message.isi +
                                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                     '</div>');
                  $('#modalRekapForm').modal('toggle');
                  getDataListOtg(flag);
                  getDataAkumulasi();
                }
                $('#frmRekap').waitMe('hide');
              }).fail(function() {
                $('#errRekap').html('<div class="alert alert-danger">'+
                                     '<strong>Peringatan!</strong> Harap periksa kembali data yang diinputkan...'+
                                    '</div>');
                $('#frmRekap').waitMe('hide');
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

  function getDataDetailOtg(reg, date, flag) {
    $('#lblValidasi').html('');
    var html = "";
    var postData = {
      'regencyId'   : reg,
      'publishDate' : date,
      'flag'        : flag,
      '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()
    };
    run_waitMe($('#frmDetail'));
    $.ajax({
      type: 'POST',
      url: site + 'otg/details',
      data: postData,
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status != 0) {
          $('#lblValidasi').html('<div class="alert alert-info">'+
                                    '<p><font size="3em">Daftar rincian rekap data OTG yang masuk per tanggal <b>'+data.jadwal+'</b></font></p>'+
                                  '</div>');
          $.each(data.message, function(key,value){
            html += '<tr>'+
                      '<td>'+value['no']+'.</td>'+
                      '<td>'+value['kategori']+'</td>'+
                      '<td align="right">'+value['jumlah']+'</td>'+
                      '<td>'+value['status']+'</td>'+
                    '</tr>';
          });
        } else {
          html = '<tr><td colspan="4">Tidak ada rekap data OTG yang diinputkan</td></tr>';
        }
        $('#tblOtgDetail > tbody').html(html);
        $('#frmDetail').waitMe('hide');
      }
    });
  }

  //panggil form detail
  $(document).on('click', '.btnDetail', function(e){
    var regId   = $(this).data('id');
    var publish = $(this).data('date');
    var flagId  = $(this).data('flag');
    var lblRegency = $(this).data('reg');
    $('#modalDetailForm').modal({
      backdrop: 'static'
    });
    $('#lblRegency').text(lblRegency);
    $('#regencyId').val(regId);
    $('#publishDate').val(publish);
    getDataDetailOtg(regId, publish, flagId);
  });

  //close modal detail
  $('.btnHide').click(function(e) {
    $('#errValid').html();
    $('#modalDetailForm').modal('toggle');
  });

  //set button edit
  $(document).on('click', '.btnUpdate', function(e){
    formReset();
    $('#formRekap').attr('action', site + 'otg/update');
    var regId   = $(this).data('id');
    var publish = $(this).data('date');
    let regency = $(this).data('reg');
    $('#modalRekapForm').modal({
      backdrop: 'static'
    });
    $('#regency').select2('val', regency).trigger('change');
    $('#regency').attr('disabled', true);
    $('#idRegency').val(regId);
    $('#datePublish').val(publish);
    var postData = {
      'regencyId'   : regId,
      'publishDate' : publish,
      'flag'        : 1,
      '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()
    };
    run_waitMe($('#frmRekap'));
    $.ajax({
      type: 'POST',
      url: site + 'otg/details',
      data: postData,
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status != 0) {
          let no = 1;
          $.each(data.message, function(key,value){
            $('#tblOtgInput > tbody > tr').find('td#otg_desc_'+no).text(value['kategori']);
            $('#tblOtgInput > tbody > tr').find('td > #otg_jum_'+no).val(value['jumlah']);
            no++;
          });
        }
        $('#frmRekap').waitMe('hide');
      }
    });
  });

  function getDataAkumulasi() {
    $.ajax({
      type: 'GET',
      url: site + 'otg/akumulasi',
      dataType: 'json',
      success: function(data) {
        $.each(data.message, function(key,value){
          $('#total_'+key).html(value);
        });
        $('#new_otg').html('+'+data.message.dua+' data baru');
      }
    });
  }

  $(document).on('keypress keyup', '.nominal',function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });
</script>

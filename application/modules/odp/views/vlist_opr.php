<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="pull-left">
        <div class="btn-toolbar" style="margin-bottom: 15px">
          <a type="button" href="<?php echo site_url('odp'); ?>" class="btn btn-inverse" name="button" style="padding:12px 16px;"><b><i class="fa fa-table"></i> Data ODP</b></a>
          <button type="button" name="button" class="btn btn-primary-alt" id="btnAdd" style="padding:11px 16px;"><i class="fa fa-plus"></i> Entri Data ODP Baru</button>
        </div>
      </div>
      <div class="pull-right">
        <h3 style="margin:10px 0px 0px 0px;"><b><?php echo 'DINAS KESEHATAN '.regency($this->app_loader->current_regency()); ?></b></h3>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-primary" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH ODP</div>
              <div class="pull-right" id="new_odp">+0</div>
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
              <div class="pull-left">ODP BARU</div>
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
              <div class="pull-left">ODP SELESAI</div>
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
      <div id="lblWarning">
        <?php
          $jam = floor((strtotime($deadline) - strtotime(date('Y-m-d H:i:s')))/(60*60));
          if($jam < 4 AND $data_odp <= 0) {
            echo '<div class="alert alert-warning">';
              echo '<strong>Peringatan! </strong>';
              echo 'Segara inputkan data ODP per tanggal <b>'.$jadwal.'</b> sebelum jam '.waktu_input().' WIB. Waktu anda '.(($jam > 1) ? 'kurang dari '.$jam.' jam lagi' : 'tidak sampai 1 jam lagi').'.';
            echo '</div>';
          } else if($jam >= 4 AND $data_odp <= 0) {
            echo '<div class="alert alert-info">';
              echo '<strong>Informasi! </strong> ';
              echo 'Jangan lupa menginputkan rekap data ODP per tanggal <b>'.$jadwal.'</b>. Batas waktu penginputan sampai <b>'.tgl_login($deadline).' WIB';
            echo '</div>';
          }
        ?>
      </div>
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errOdp"></div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h4><b>REKAP DATA ODP</b></h4>
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Rincian rekap data ODP per tanggal'.' '.$jadwal; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblOdp1" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="55%">Jenis Rekapitulasi</th>
                          <th width="17%">Tgl. Input</th>
                          <th width="10%">Jumlah</th>
                          <th width="12%">Status</th>
                          <th width="3%" style="text-align:center;">Edit</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="home2">
              <div class="row">
                <div class="col-xs-12 col-sm-12 clearfix">
                  <div class="pull-left">
                    <h4 style="margin:0 0 10px"><b><?php echo 'Daftar rekap data ODP yang telah dipublish'; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblOdp2" width="100%">
                      <thead>
                        <tr>
                          <th width="3%">No.</th>
                          <th width="20%">Nama Daerah</th>
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
        <h4 class="modal-title"><b>FORM ENTRI REKAP DATA ODP</b></h4>
      </div>
      <?php echo form_open(site_url('odp/create'), array('id' => 'formRekap')); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-sm-12">
            <div class="alert alert-warning">
              <p>
                <font size="3em">
                  Silahkan diinputkan rekap data ODP per tanggal <strong><?php echo $jadwal; ?></strong>.
                  Batas waktu penginputan sampai <strong><?php echo tgl_login($deadline).' WIB.'; ?></strong>
                  <?php echo ($jam < 4 AND $data_odp <= 0) ? ' Waktu anda '.(($jam > 1) ? 'kurang dari '.$jam.' jam lagi.' : 'tidak sampai 1 jam lagi') : ''; ?>
                </font>
              </p>
            </div>
            <div id="errRekap"></div>
          </div>
        </div>
        <div class="table-responsive">
          <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%">
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
                <td><?php echo $k['desc'].(($k['id'] > 4) ? ' sampai pada saat ini' : ' per tanggal '.$jadwal); ?></td>
                <td>
                  <input type="text" class="form-control nominal" name="param[<?php echo $k['id']; ?>]" id="odp_baru" placeholder="Jumlah Data" value="<?php echo set_value('param['.$k['id'].']', ''); ?>" required="" oninvalid="this.setCustomValidity('Inputan wajib diisi')" oninput="setCustomValidity('')">
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
      <div class="modal-header">
        <button type="button" class="close btnHide" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>REKAP DATA ODP <span id="lblRegency"></span></b></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12 col-xs-12" id="lblValidasi"></div>
        </div>
        <div class="table-responsive">
          <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%" id="tblOdpDetail">
            <thead>
              <tr>
                <th width="3%">No.</th>
                <th width="75%">Jenis Rekapitulasi</th>
                <th width="10%" style="text-align:right;">Jumlah</th>
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
  var flag     = 1;
  $(document).ready(function(e){
    getDataListOdp(flag);
    getDataAkumulasi();
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('a6be18f8aa19ab9f3828', {
      cluster: 'ap1',
      forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      if(data.message == 'odpverified') {
        getDataListOdp(flag);
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
    getDataListOdp(flag);
  });

  function getDataListOdp(flag) {
    if(flag == 2)
      getDataListOdpPublish(2);
    else
      getDataListOdpNew(1);
  }

  function getDataListOdpNew(flag) {
    var html = "";
    var tblName = '#tblOdp'+flag;
    var postData = {
      'flag' : flag,
      '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()
    };
    run_waitMe($(tblName));
    $.ajax({
      type: 'POST',
      url: site + 'odp/listview',
      data: postData,
      dataType: 'json',
      success: function(data) {
        if(data.status != 0) {
          $.each(data.message, function(key,value){
            html += '<tr>'+
                      '<td>'+value['no']+'.</td>'+
                      '<td>'+value['kategori']+'</td>'+
                      '<td>'+value['tanggal']+'</td>'+
                      '<td align="right" class="total">'+value['jumlah']+'</td>'+
                      '<td>'+value['status']+'</td>'+
                      '<td style="text-align:center;" class="action">'+value['action']+'</td>'+
                    '</tr>';
          });
        } else {
          html = '<tr><td colspan="6">Tidak ada rekap data ODP yang diinputkan</td></tr>';
        }
        $(tblName +' > tbody').html(html);
        $(tblName).waitMe('hide');
      }
    });
  }

  function getDataListOdpPublish(flag) {
    var tblName = '#tblOdp'+flag;
    $(tblName).dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "odp/listview",
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
    $('#errRekap').html('');
    $('.nominal').val('');
    $('#regency').select2('val', '');
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
      message: "Sebelum anda melanjutkan proses untuk menyimpan data, pastikan data ODP yang anda inputkan sudah benar."+
                " Jika anda yakin datanya sudah benar silahkan melanjutkan proses ini, namun jika belum silahkan cek kembali data anda.",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
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
                                        '<strong>Peringatan!</strong> Proses input rekap data ODP '+ data.message.reg +' per tanggal '+ '<?php echo $jadwal ?>' + data.message.isi +
                                      '</div>');
                } else {
                  $('#errOdp').html('<div class="alert alert-dismissable alert-success">'+
                                      '<strong>Sukses!</strong> Rekap data ODP '+ data.message.reg +' per tanggal '+ '<?php echo $jadwal ?>' + data.message.isi +
                                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                     '</div>');
                  $('#modalRekapForm').modal('toggle');
                  $('#lblWarning').html('');
                  getDataListOdp(flag);
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

  function getDataDetailOdp(reg, date, flag) {
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
      url: site + 'odp/details',
      data: postData,
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status != 0) {
          $('#lblValidasi').html('<div class="alert alert-info">'+
                                    '<p><font size="3em">Rincian rekap data ODP yang masuk per tanggal '+data.jadwal+'.</font></p>'+
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
          html = '<tr><td colspan="4">Tidak ada rekap data ODP yang diinputkan</td></tr>';
        }
        $('#tblOdpDetail > tbody').html(html);
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
    $("#modalDetailForm").modal({
      backdrop: 'static'
    });
    $('#lblRegency').text(lblRegency);
    getDataDetailOdp(regId, publish, flagId);
  });

  //close modal detail
  $('.btnHide').click(function(e) {
    $("#modalDetailForm").modal('toggle');
  });

  //set button edit
  $(document).on('click', '.btnUpdate', function(e) {
    var id     = $(this).data('id');
    var tot    = $(this).closest('tr').find('.total').text();
    var input  = '<div class="form-group"><input type="text" class="form-control numberonly" name="jml_odp" id="jml_odp" value="'+tot+'" required="required"><span class="help-block"></span></div>';
    var button = '<button class="btn btn-xs btn-danger btnCancel" data-id="'+id+'" data-total="'+tot+'"><i class="fa fa-times"></i></button>'+' '+
                 '<button class="btn btn-xs btn-primary btnSave" data-id="'+id+'" title="Simpan Perubahan Data ODP"><i class="fa fa-save"></i></button>';
    $(this).closest('tr').find('.total').html(input);
    $(this).closest('tr').find('.action').html(button);
  });

  //set button cancel
  $(document).on('click', '.btnCancel', function(e) {
    var id     = $(this).data('id');
    var tot    = $(this).data('total');
    var button = '<button class="btn btn-xs btn-orange btnUpdate" data-id="'+id+'" title="Edit Data ODP"><i class="fa fa-pencil"></i></button>';
    $(this).closest('tr').find('.total').html(tot);
    $(this).closest('tr').find('.action').html(button);
  });

  //set button Simpan
  $(document).on('click', '.btnSave', function(e) {
    var id     = $(this).data('id');
    var regId  = $(this).data('reg');
    var total  = $(this).closest('tr').find('.total > .form-group > #jml_odp').val();
    var postData = {
      'odpId' : id,
      'total' : total,
      '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()
    };
    $(this).closest('tr').find('.total > .form-group > #jml_odp').attr('readonly', 'readonly');
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda yakin ingin merubah data ini ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if(response) {
              $('.btnSave').closest('tr').find('.total > .form-group > #jml_odp').removeAttr('readonly');
            }
          }
        },
        "main" : {
          "label" : "<i class='fa fa-check'></i> Ya, Lanjutkan",
          "className" : "btn-primary",
          callback:function(response){
            if (response) {
              run_waitMe($('#tblOdp1'));
              $.ajax({
                url: site + 'odp/update',
                type: "POST",
                data: postData,
                dataType: "json",
              }).done(function(data) {
                $('input[name="'+csrfName+'"]').val(data.csrfHash);
                if(data.status == 0) {
                  $('#errOdp').html('<div class="alert alert-danger"> '+
                                     '<strong>Peringatan!</strong> Rekap data ODP per tanggal '+ '<?php echo $jadwal ?>' + data.message +
                                    '</div>');
                } else {
                  $('#errOdp').html('<div class="alert alert-success">'+
                                      '<strong>Sukses!</strong> Rekap data ODP per tanggal '+ '<?php echo $jadwal ?>' + data.message +
                                     '</div>');
                  getDataListOdp(1);
                }
                $('#tblOdp1').waitMe('hide');
              }).fail(function() {
                $('#errOdp').html('<div class="alert alert-danger">'+
                                   '<strong>Peringatan!</strong> Harap periksa kembali data yang diinputkan...'+
                                  '</div>');
                $('#tblOdp1').waitMe('hide');
              }).always(function() {
                $('.btnSave').closest('tr').find('.total > .form-group > #jml_odp').removeAttr('readonly');
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
      url: site + 'odp/akumulasi',
      dataType: 'json',
      success: function(data) {
        $.each(data.message, function(key,value){
          $('#total_'+key).html(value);
        });
        $('#new_odp').html('+'+data.message.dua+' data baru');
      }
    });
  }

  $(document).on('keypress keyup', '.numberonly',function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
    if($(this).val() == '') {
      $(this).closest('tr').find('.total > .form-group').addClass('has-error');
      $(this).closest('tr').find('.total > .form-group').removeClass('has-success');
      $(this).closest('tr').find('.total > .form-group > .help-block').text('Wajib Isi!');
      $(this).closest('tr').find('.action > .btnSave').addClass('disabled');
    } else {
      $(this).closest('tr').find('.total > .form-group').removeClass('has-error');
      $(this).closest('tr').find('.total > .form-group').addClass('has-success');
      $(this).closest('tr').find('.total > .form-group > .help-block').text('');
      $(this).closest('tr').find('.action > .btnSave').removeClass('disabled');
    }
  });

  $(document).on('keypress keyup', '.nominal',function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });
</script>

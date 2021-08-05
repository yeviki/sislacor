<div class="container">
  <div class="row" id="formParent">
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
      <div class="alert alert-warning">
        <p><font size="3em"><b>Informasi!</b></font></p>
        <p id="lblWarning" style="margin-top:0px;">
          <?php
            $totReg = count($regency) - 1;
            if($tot_odp == 0)
              echo 'Belum ada Kabupaten/Kota yang menginputkan data ODP per tanggal <b>'.$jadwal.'</b>';
            else if($tot_odp > 0 AND $tot_odp < $totReg)
              echo (($totReg-$tot_odp > (ceil($totReg/2)+1)) ? 'Baru' : 'Sudah').' <b>'.$tot_odp.'</b> Kabupaten/Kota yang menginputkan data ODP per tanggal <b>'.$jadwal.'</b>, masih ada <b>'.($totReg - $tot_odp).'</b> Kabupaten/Kota yang belum menginputkan data.';
            else
              echo 'Semua Kabupaten/Kota telah menginputkan data ODP per tanggal <b>'.$jadwal.'</b>';
          ?>
        </p>
      </div>
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errOdp"></div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h4><b>REKAP DATA ODP KAB/KOTA</b></h4>
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Rekap data ODP Kabupaten/Kota per tanggal'.' '.$jadwal; ?></b></h4>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 clearfix" style="margin-top:10px;">
                  <div class="table-responsive">
                    <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" id="tblOdp1" width="100%">
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
                    <h4 style="margin:0 0 10px"><b><?php echo 'Rekap data ODP Kabupaten/Kota yang telah dipublish'; ?></b></h4>
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

<div class="modal fade bs-example-modal-lg in" id="modalDetailForm" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmDetail">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close btnClose" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>REKAP DATA ODP <span id="lblRegency"></span></b></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <?php
            echo form_open(site_url('#'), array('id' => 'formVerifikasi'));
            echo form_input(array('type'=>'hidden', 'name'=>'regencyId', 'id'=>'regencyId'));
            echo form_input(array('type'=>'hidden', 'name'=>'publishDate', 'id'=>'publishDate'));
          ?>
          <div class="col-sm-12 col-xs-12" id="lblValidasi"></div>
          <?php echo form_close(); ?>
        </div>
        <div id="errValid"></div>
        <div class="table-responsive">
          <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%" id="tblOdpDetail">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-default btnClose" style="padding:12px 16px;"><i class="fa fa-times"></i> CLOSE</button>
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
      if(data.message == 'odpsuccess') {
        getDataListOdp(flag);
        let noteOdp = '';
        if(data.status == 'new') {
          if(data.tot_odp == 0)
            noteOdp = 'Belum ada Kabupaten/Kota yang menginputkan data ODP per tanggal <b>'+'<?php echo $jadwal; ?>'+'</b>';
          else if(data.tot_odp > 0 && data.tot_odp < totReg)
            noteOdp = ((totReg - data.tot_odp > (Math.ceil(totReg/2)+1)) ? 'Baru' : 'Sudah')+' <b>'+data.tot_odp+'</b> Kabupaten/Kota yang menginputkan data ODP per tanggal <b>'+'<?php echo $jadwal; ?>'+'</b>, masih ada <b>'+(totReg-data.tot_odp)+'</b> Kabupaten/Kota lagi yang belum menginputkan data.';
          else
            noteOdp = 'Semua Kabupaten/Kota telah menginputkan data OTG per tanggal <b>'+'<?php echo $jadwal ?>'+'</b>';
          $('#lblWarning').html(noteOdp);
        }
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

  //get data list
  function getDataListOdp(flag) {
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
        "url": site + "verifikasi-kasus/kasus-odp/listview",
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

  //get data detail
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
      url: site + 'verifikasi-kasus/kasus-odp/details',
      data: postData,
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status != 0) {
          if(data.validasi == 'S') {
            $('#lblValidasi').html('<div class="alert alert-info">'+
                                      '<p><font size="3em">Rincian rekap data ODP yang masuk per tanggal '+data.jadwal+'. Untuk melakukan verifikasi terhadap data yang dilaporkan silahkan klik tombol Verifikasi Data dibawah.</font></p>'+
                                      '<p style="margin-top:10px;"><button type="submit" class="btn btn-danger" name="save" id="save" style="padding:12px 16px;"><i class="fa fa-check"></i> VERIFIKASI DATA</button></p>'+
                                    '</div>');
          } else {
            $('#lblValidasi').html('<div class="alert alert-info">'+
                                      '<p><font size="3em">Rincian rekap data ODP yang masuk pada tanggal '+data.jadwal+'.</font></p>'+
                                    '</div>');
          }
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
    $('#modalDetailForm').modal({
      backdrop: 'static'
    });
    $('#lblRegency').text(lblRegency);
    $('#regencyId').val(regId);
    $('#publishDate').val(publish);
    getDataDetailOdp(regId, publish, flagId);
  });

  //close modal detail
  $('.btnClose').click(function(e) {
    $('#errValid').html();
    $('#modalDetailForm').modal('toggle');
  });

  //verifikasi data
  $('#formVerifikasi').submit(function(e) {
    e.preventDefault();
    var postData = $(this).serialize();
    // get form action url
    var formActionURL = site + 'verifikasi-kasus/kasus-odp/approve';
    $("#save").html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $("#save").addClass('disabled');
    run_waitMe($('#frmDetail'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda akan memverifikasi data ODP ini ?"+
                " Pastikan data yang akan anda verifikasi sudah benar.",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
              $('#frmDetail').waitMe('hide');
              $("#save").html('<i class="fa fa-check"></i> VERIFIKASI DATA');
              $("#save").removeClass('disabled');
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
                  $('#errValid').html('<div class="alert alert-danger"> '+
                                        '<strong>Peringatan!</strong> '+ data.message +
                                      '</div>');
                } else {
                  $('#errOdp').html('<div class="alert alert-dismissable alert-success">'+
                                      '<strong>Sukses!</strong> '+ data.message +
                                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                     '</div>');
                  $("#modalDetailForm").modal('toggle');
                  getDataListOdp(flag);
                  getDataAkumulasi();
                }
                $('#frmDetail').waitMe('hide');
              }).fail(function() {
                $('#errValid').html('<div class="alert alert-danger">'+
                                     '<strong>Peringatan!</strong> Harap periksa kembali data yang akan diproses...'+
                                    '</div>');
                $('#frmDetail').waitMe('hide');
              }).always(function() {
                $("#save").html('<i class="fa fa-check"></i> VERIFIKASI DATA');
                $("#save").removeClass('disabled');
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
      url: site + 'verifikasi-kasus/kasus-odp/akumulasi',
      dataType: 'json',
      success: function(data) {
        $.each(data.message, function(key,value){
          $('#total_'+key).html(value);
        });
        $('#new_odp').html('+'+data.message.dua+' data baru');
      }
    });
  }
</script>

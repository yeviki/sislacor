<div class="container">
  <?php echo $this->session->flashdata('message'); ?>
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-green" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH OTG</div>
              <div class="pull-right" id="new_otg">+0 KASUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-child"></i></div>
              <div class="pull-right" id="total_otg">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-primary" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH ODP</div>
              <div class="pull-right" id="new_odp">+0 KASUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-user-times"></i></div>
              <div class="pull-right" id="total_odp">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-orange" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH PDP</div>
              <div class="pull-right" id="new_pdp">+0 KASUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-user-md"></i></div>
              <div class="pull-right" id="total_pdp">0</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-xs-12 col-sm-6">
          <a class="info-tiles tiles-danger" href="javascript:;">
            <div class="tiles-heading">
              <div class="pull-left">JUMLAH KONFIRMASI</div>
              <div class="pull-right" id="new_positif">+0 KASUS</div>
            </div>
            <div class="tiles-body">
              <div class="pull-left"><i class="fa fa-user-plus"></i></div>
              <div class="pull-right" id="total_positif">0</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div id="errPublish"></div>
      <div class="pull-left">
        <h4><b><?php echo ((date('H:i:s') > waktu_input() AND date('H:i:s') < waktu_publish()) ? 'REKAP DATA YANG MASUK PER TANGGAL ' : 'REKAP DATA YANG DIPUBLISH PER TANGGAL '). strtoupper($jadwal); ?></b></h4>
      </div>
      <div class="pull-right" style="padding-bottom:5px;">
        <?php echo form_open(site_url('#')); ?>
        <button type="button" class="btn btn-primary-alt" id="btnPublish" style="padding:11px 16px;"><i class="fa fa-plane"></i> PUBLISH DATA</button>
        <a type="button" class="btn btn-success" href="<?php echo site_url('rekapitulasi/daily/export-to-excel'); ?>" style="padding:12px 16px;"><i class="fa fa-file-excel-o"></i> EXPORT KE EXCEL</a>
        <?php echo form_close(); ?>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel">
        <div class="panel-body" style="padding:0px;">
          <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-bordered table-hover" id="tblPublish" style="font-size:16px;margin-bottom:0px;">
              <thead style="background-color:#ADD8E6;">
                <tr>
                  <th rowspan="2" style="vertical-align:middle;width:3px;">No.</th>
                  <th rowspan="2" align="center" style="vertical-align:middle;width:20%;">KAB/KOTA</th>
                  <th colspan="5" style="text-align:center;width:20%;">OTG</th>
                  <th colspan="5" style="text-align:center;width:20%;">ODP</th>
                  <th colspan="5" style="text-align:center;width:20%;">PDP</th>
                  <th colspan="5" style="text-align:center;width:20%;">TERKONFIRMASI</th>
                </tr>
                <tr>
                  <th style="vertical-align:middle;">Jml N-1</th>
                  <th style="vertical-align:middle;">Baru</th>
                  <th style="vertical-align:middle;">Berubah Status</th>
                  <th style="vertical-align:middle;">Selesai</th>
                  <th style="vertical-align:middle;">Total</th>
                  <th style="vertical-align:middle;">Jml N-1</th>
                  <th style="vertical-align:middle;">Baru</th>
                  <th style="vertical-align:middle;">Berubah Status</th>
                  <th style="vertical-align:middle;">Selesai</th>
                  <th style="vertical-align:middle;">Total</th>
                  <th style="vertical-align:middle;">Jml N-1</th>
                  <th style="vertical-align:middle;">Baru</th>
                  <th style="vertical-align:middle;">Berubah Status</th>
                  <th style="vertical-align:middle;">Negatif</th>
                  <th style="vertical-align:middle;">Total</th>
                  <th style="vertical-align:middle;">Jml N-1</th>
                  <th style="vertical-align:middle;">Baru</th>
                  <th style="vertical-align:middle;">Meninggal</th>
                  <th style="vertical-align:middle;">Sembuh</th>
                  <th style="vertical-align:middle;">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr style="background-color:#FFFACD;">
                  <td colspan="22">Tidak ada yang akan dipublish</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';
  $(document).ready(function(e){
    getDataListPublish();
    getDataAkumulasi();
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('a6be18f8aa19ab9f3828', {
      cluster: 'ap1',
      forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      if(data.message == 'otgverified' || data.message == 'odpverified' || data.message == 'caseverified') {
        getDataListPublish();
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

  function getDataListPublish() {
    var html = "";
    run_waitMe($('#tblPublish'));
    $.ajax({
      type: 'GET',
      url: site + 'rekapitulasi/daily/listview',
      data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()},
      dataType: 'json',
      success: function(data) {
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status != 0) {
          var no=1;
          var otg_last=0, otg_baru=0, otg_bs=0, otg_sembuh=0;
          var odp_last=0, odp_baru=0, odp_bs=0, odp_sembuh=0;
          var pdp_last=0, pdp_baru=0, pdp_bs=0, pdp_sembuh=0;
          var pos_last=0, pos_baru=0, pos_dead=0, pos_sembuh=0;
          $.each(data.message, function(key,value){
            otg_last  += parseInt(value['otg_last']); otg_baru    += parseInt(value['otg_baru']);
            otg_bs    += parseInt(value['otg_bs']);   otg_sembuh  += parseInt(value['otg_sembuh']);
            odp_last  += parseInt(value['odp_last']); odp_baru    += parseInt(value['odp_baru']);
            odp_bs    += parseInt(value['odp_bs']);   odp_sembuh  += parseInt(value['odp_sembuh']);
            pdp_last  += parseInt(value['pdp_last']); pdp_baru    += parseInt(value['pdp_baru']);
            pdp_bs    += parseInt(value['pdp_bs']);   pdp_sembuh  += parseInt(value['pdp_sembuh']);
            pos_last  += parseInt(value['pos_last']); pos_dead    += parseInt(value['pos_meninggal']);
            pos_baru  += parseInt(value['pos_baru']); pos_sembuh  += parseInt(value['pos_sembuh']);
            html += '<tr style="background-color:#FFFACD;">'+
                      '<td style="width:3px;">'+no+'.</td>'+
                      '<td style="width:25px;">'+value['name_regency']+'</td>'+
                      //otg
                      '<td style="width:10px;">'+value['otg_last']+'</td>'+
                      '<td style="width:10px;">'+value['otg_baru']+'</td>'+
                      '<td style="width:10px;">'+value['otg_bs']+'</td>'+
                      '<td style="width:10px;">'+value['otg_sembuh']+'</td>'+
                      '<td style="width:10px;"><strong>'+value['total_otg']+'</strong></td>'+
                      //odp
                      '<td style="width:10px;">'+value['odp_last']+'</td>'+
                      '<td style="width:10px;">'+value['odp_baru']+'</td>'+
                      '<td style="width:10px;">'+value['odp_bs']+'</td>'+
                      '<td style="width:10px;">'+value['odp_sembuh']+'</td>'+
                      '<td style="width:10px;"><strong>'+value['total_odp']+'</strong></td>'+
                      //pdp
                      '<td style="width:10px;">'+value['pdp_last']+'</td>'+
                      '<td style="width:10px;">'+value['pdp_baru']+'</td>'+
                      '<td style="width:10px;">'+value['pdp_bs']+'</td>'+
                      '<td style="width:10px;">'+value['pdp_sembuh']+'</td>'+
                      '<td style="width:10px;"><strong>'+value['total_pdp']+'</strong></td>'+
                      //positif
                      '<td style="width:10px;">'+value['pos_last']+'</td>'+
                      '<td style="width:10px;">'+value['pos_baru']+'</td>'+
                      '<td style="width:10px;">'+value['pos_meninggal']+'</td>'+
                      '<td style="width:10px;">'+value['pos_sembuh']+'</td>'+
                      '<td style="width:10px;"><strong>'+value['total_pos']+'</strong></td>'+
                    '</tr>';
            no++;
          });
          html += '<tr style="background-color:#90EE90;">'+
                    '<td colspan="2" align="center"><strong>TOTAL</strong></td>'+
                    '<td><strong>'+otg_last+'</strong></td>'+
                    '<td><strong>'+otg_baru+'</strong></td>'+
                    '<td><strong>'+otg_bs+'</strong></td>'+
                    '<td><strong>'+otg_sembuh+'</strong></td>'+
                    '<td><strong>'+((otg_last+otg_baru)-(otg_bs+otg_sembuh))+'</strong></td>'+
                    '<td><strong>'+odp_last+'</strong></td>'+
                    '<td><strong>'+odp_baru+'</strong></td>'+
                    '<td><strong>'+odp_bs+'</strong></td>'+
                    '<td><strong>'+odp_sembuh+'</strong></td>'+
                    '<td><strong>'+((odp_last+odp_baru)-(odp_bs+odp_sembuh))+'</strong></td>'+
                    '<td><strong>'+pdp_last+'</strong></td>'+
                    '<td><strong>'+pdp_baru+'</strong></td>'+
                    '<td><strong>'+pdp_bs+'</strong></td>'+
                    '<td><strong>'+pdp_sembuh+'</strong></td>'+
                    '<td><strong>'+((pdp_last+pdp_baru)-(pdp_bs+pdp_sembuh))+'</strong></td>'+
                    '<td><strong>'+pos_last+'</strong></td>'+
                    '<td><strong>'+pos_baru+'</strong></td>'+
                    '<td><strong>'+pos_dead+'</strong></td>'+
                    '<td><strong>'+pos_sembuh+'</strong></td>'+
                    '<td><strong>'+((pos_last+pos_baru)-(pos_dead+pos_sembuh))+'</strong></td>'+
                  '</tr>';
        } else {
          html = '<tr><td colspan="22">Tidak ada rekap data yang akan dipublish</td></tr>';
        }
        $('#tblPublish > tbody').html(html);
        $('#tblPublish').waitMe('hide');
      }
    });
  }

  function getDataAkumulasi() {
    $.ajax({
      type: 'GET',
      url: site + 'rekapitulasi/daily/akumulasi',
      dataType: 'json',
      success: function(data) {
        if(Object.keys(data.message).length > 0) {
          $.each(data.message, function(key,value){
            $('#'+key).text(value);
          });
        }
      }
    });
  }

  $(document).on('click', '#btnPublish', function(e){
    e.preventDefault();
    // get form action url
    $(this).html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $(this).addClass('disabled');
    run_waitMe($('#formParent'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda akan mempublish rekap data covid-19 terbaru ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
              $('#formParent').waitMe('hide');
              $("#btnPublish").html('<i class="fa fa-plane"></i> PUBLISH DATA');
              $("#btnPublish").removeClass('disabled');
            }
          }
        },
        "main" : {
          "label" : "<i class='fa fa-check'></i> Ya, Lanjutkan",
          "className" : "btn-primary",
          callback:function(response){
            if (response) {
              $.ajax({
                url: site + 'rekapitulasi/daily/approve',
                type: "POST",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()},
                dataType: "json",
              }).done(function(data) {
                $('input[name="'+csrfName+'"]').val(data.csrfHash);
                if(data.status == 0) {
                  $('#errPublish').html('<div class="alert alert-danger"> '+
                                          '<strong>Peringatan!</strong> '+ data.message +
                                         '</div>');
                } else {
                  $('#errPublish').html('<div class="alert alert-dismissable alert-success">'+
                                          '<strong>Sukses!</strong> '+ data.message +
                                          '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                         '</div>');
                  getDataListPublish();
                  getDataAkumulasi();
                }
                $('#formParent').waitMe('hide');
              }).fail(function() {
                $('#errPublish').html('<div class="alert alert-danger">'+
                                       '<strong>Peringatan!</strong> Proses publish data gagal, coba reload halaman kembali...'+
                                      '</div>');
                $('#formParent').waitMe('hide');
              }).always(function() {
                $("#btnPublish").html('<i class="fa fa-plane"></i> PUBLISH DATA');
                $("#btnPublish").removeClass('disabled');
              });
            }
          }
        }
      }
    });
  });
</script>

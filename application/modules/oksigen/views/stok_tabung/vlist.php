<style>
  .clockpicker-popover
  {
    z-index : 9999;
  }
</style>
<div class="container">
  <div class="row" id="formParent">    
    <div class="col-xs-12 col-sm-12">
      <div class="btn-toolbar" style="margin-bottom: 15px">
        <a type="button" href="<?php echo site_url('kamar/pemakaian-kamar'); ?>" class="btn btn-inverse" name="button" style="padding:12px 16px;"><b><i class="fa fa-table"></i></b></a>
          <button type="button" class="btn btn-primary-alt" id="btnAdd" style="padding:11px 16px;"><b><i class="fa fa-plus"></i> Tambah Baru</b></button>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errSuccess"></div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="row">
        <div class="col-xs-12 col-sm-12">
          <div class="pull-left">
            <h3 style="font-weight:bold;text-align:left;">
              <a href="javascript:void(0);" class="btnFilter" style="text-decoration:none;color:#000000;">
                <i class="fa fa-sliders"></i> Filter Data
              </a>
            </h3>
          </div>
          <div class="pull-right">
            <h3 style="font-weight:bold;text-align:right;">
              <a href="javascript:void(0);" class="btnUpload" style="text-decoration:none;color:#000000;">
                <i class="fa fa-sliders"></i> Upload Excel
              </a>
            </h3>
          </div>
          <!-- <div class="pull-right">
            <div class="btn-toolbar">
              <button type="button" class="btn btn-success" id="printExcel"><i class="fa fa-file-excel-o"></i> EXPORT KE EXCEL </button>
            </div>
          </div> -->
        </div>
        <div class="col-xs-12 col-sm-12">
          <?php echo form_open(site_url('#'), array('id'=>'formFilter', 'style'=>'display:none;margin-bottom:20px;')); ?>
            <div style="display:block;background:#FFF;padding:20px;border:1px solid #CCC;box-shadow:0px 0px 10px #CCC;">
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group required">
                    <label for="id_rs" class="control-label"><b>Rumah Sakit <font color="red" size="1em">(*)</font></b></label>
                    <?php echo form_dropdown('id_rs', $list_id_rs, $this->input->post('id_rs'), 'class="select-all"');?>
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                        <label for="search_tanggal" class="control-label"><b>Pilih Tanggal <font color="red" size="1em">(*)</font></b></label>
                        <div class="input-group date datemonth">
                          <input type="text" class="form-control mask" name="search_tanggal" id="search_tanggal" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('search_tanggal', TRUE); ?>">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="pull-left">
                    <div class="btn-toolbar">
                      <button type="submit" class="btn btn-primary" name="filter" id="filter"><i class="fa fa-filter"></i> LAKUKAN PENCARIAN</button>
                      <button type="button" class="btn btn-danger" name="cancel" id="cancel"><i class="fa fa-refresh"></i> CANCEL</button>
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

        <div class="col-xs-12 col-sm-12">
          <?php echo form_open(site_url('#'), array('id' => 'formupload', 'style'=>'display:none;margin-bottom:20px;')); ?>
            <div style="display:block;background:#FFF;padding:20px;border:1px solid #CCC;box-shadow:0px 0px 10px #CCC;">
              <div class="row">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group">
                    <label for="file" class="control-label"><b>Silahkan upload template disini..</b></label>
                      <input type="file" name="file" id="file" class="form-control">
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="pull-left">
                    <div class="btn-toolbar">
                      <button type="submit" class="btn btn-primary" id="uploadExcel"><i class="fa fa-upload"></i> IMPORT</button>
                      <button type="button" class="btn btn-danger download"><i class="fa fa-download"></i> DOWNLOAD TEMPLATE</button>
                    </div>
                  </div>
                  <div class="pull-right">
                    <div class="btn-toolbar">
                      <button type="button" class="btn btn-default btnUpload" name="button"><i class="fa fa-times"></i> CLOSE</button>
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
          <h4>Data Stok Oksigen</h4>
        </div>
        <div class="panel-body collapse in">
          <div class="table-responsive">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tblVaksin" width="100%">
              <thead>
                <tr>
                  <th width="3%">#</th>
                  <th width="10%">Tanggal</th>
                  <th width="12%">Rumah Sakit</th>
                  <?php foreach ($list_tabung as $key => $k): ?>
                    <th width="12%"><?php echo $k['nm_tabung']; ?></th>
                  <?php endforeach; ?>
                  <th width="5%">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- container -->

<div class="modal fade in" id="modalEntryForm" tabindex="-1" role="dialog" aria-labelledby="modalEntryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="frmEntry">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 15px 10px 15px;">
        <button type="button" class="close btnClose" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>FORM ENTRI OKSIGEN</b></h4>
      </div>
      <?php echo form_open(site_url('oksigen/stok-tabung/create'), array('id' => 'formEntry')); ?>
      <div class="modal-body" style="padding:15px 15px 5px 15px;">
        <div id="errEntry"></div>
          <div class="row">
          <?php
              echo form_input(array('type'=>'hidden', 'name'=>'rsId', 'id'=>'idRs'));
              echo form_input(array('type'=>'hidden', 'name'=>'publishDate', 'id'=>'datetanggal'));
            ?>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="id_rs" class="control-label"><b>Rumah Sakit <font color="red" size="1em">(*)</font></b></label>
                <?php echo form_dropdown('id_rs', $list_id_rs, $this->input->post('id_rs'), 'class="select-all" id="id_rs"');?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="tanggal" class="control-label"><b>Tanggal <font color="red" size="1em">(*)</font></b></label>
                  <div class="input-group date datemonth">
                    <input type="text" class="form-control mask" name="tanggal" id="tanggal" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tanggal', TRUE); ?>">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                  <?php echo form_error('tanggal'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-striped table-bordered" width="100%" id="tblOtgInput">
              <thead>
                <tr>
                  <th width="3%">No.</th>
                  <th width="77%">Jenis Tabung</th>
                  <th width="20%">Re-Stok</th>
                </tr>
              </thead>
              <tbody>
              <?php $i=1; foreach ($list_tabung as $key => $k): ?>
                <tr>
                  <td><?php echo $i; ?>.</td>
                  <td id="<?php echo 'nm_tabung_'.$i; ?>"><?php echo $k['nm_tabung']; ?></td>
                  <td>
                    <input type="text" class="form-control nominal" name="param[<?php echo $k['id_kat_tabung']; ?>]" id="<?php echo 'tot_jum_stok'.$i; ?>" placeholder="Jumlah Tabung" value="<?php echo set_value('param['.$k['id_kat_tabung'].']', ''); ?>" required="" oninvalid="this.setCustomValidity('Inputan wajib diisi')" oninput="setCustomValidity('')">
                  </td>
                </tr>
              <?php $i++; endforeach; ?>
              </tbody>
            </table>
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

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var csrfName  = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site      = '<?php echo site_url();?>';

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

  $(document).ready(function() {
    getDataList();
    $('.mask').inputmask();
    $(".datemonth").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
    });
  });

  $(document).on('click', '.btnFilter', function(e){
    $('#formFilter').slideToggle('slow');
    $('.select-all').select2('val', '');
  });

  $(document).on('click', '#cancel', function(e){
    e.preventDefault();
    $('.select-all').select2('val', '');
    $('#daterangepicker1').val('');
    getDataList();
  });

  $('#formFilter').submit(function(e){
    e.preventDefault();
    getDataList();
  });

  function getDataList() {
    $('#tblVaksin').dataTable({
      "destroy": true,
      "processing":true,
      "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
      },
      "serverSide": true,
      "ordering": false,
      "ajax": {
        "url": site + "oksigen/stok-tabung/listview",
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
    $('#tblVaksin_filter input').addClass('form-control').attr('placeholder','Search Data');
    $('#tblVaksin_length select').addClass('form-control');
  }

  //panggil form Entri
  $(document).on('click', '#btnAdd', function(e){
    formReset();
    $('#modalEntryForm').modal({
      backdrop: 'static'
    });
  });

  //close form entri
  $(document).on('click', '.btnClose', function(e) {
    formReset();
    $('#modalEntryForm').modal('toggle');
  });

  function formReset() {
    $('#formEntry').attr('action', site + 'oksigen/stok-tabung/create');
    $('#errEntry').html('');
    $('.select-all').select2('val', '');
    $('#id_rs').attr('disabled', false);
    $('#status').select2('val', 1);
    $('.help-block').text('');
    $('.required').removeClass('has-error');
    $('form#formEntry').trigger('reset');
  }

  $('#formEntry').submit(function(e) {
    e.preventDefault();
    var postData = $(this).serialize();
    // get form action url
    var formActionURL = $(this).attr("action");
    $("#save").html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $("#save").addClass('disabled');
    run_waitMe($('#frmEntry'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda ingin menyimpan data ini ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
              $("#save").html('<i class="fa fa-check"></i> SUBMIT');
              $("#save").removeClass('disabled');
              $('#frmEntry').waitMe('hide');
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
                  $('#errEntry').html('<div class="alert alert-danger" id="pesanErr"><strong>Peringatan!</strong> Tolong dilengkapi form inputan dibawah...</div>');
                  $.each(data.message, function(key,value){
                    if(key != 'isi')
                      $('input[name="'+key+'"], select[name="'+key+'"]').closest('div.required').addClass('has-error').find('div.help-block').text(value);
                    else {
                      $('#pesanErr').html('<strong>Peringatan!</strong> ' +value);
                    }
                  });
                  $('#modalEntryForm').animate({
                    scrollTop: (data.message.isi) ? 0 : ($('.has-error').find('input, select').first().focus().offset().top-300)
                  }, 'slow');
                } else {
                  $('#errSuccess').html('<div class="alert alert-dismissable alert-success">'+
                                          '<strong>Sukses!</strong> '+ data.message +
                                          '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                        '</div>');
                  $('#modalEntryForm').modal('toggle');
                  getDataList();
                }
                $('#frmEntry').waitMe('hide');
              }).fail(function() {
                $('#errEntry').html('<div class="alert alert-danger">'+
                                     '<strong>Peringatan!</strong> Harap periksa kembali data yang diinputkan...'+
                                    '</div>');
                $('#frmEntry').waitMe('hide');
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

  $(document).on('submit', '#formupload', function(e) {
      e.preventDefault();
      const file = $('#file').prop('files')[0];
      var token = $('input[name="' + csrfName + '"]').val()
      let form_data = new FormData();
      form_data.append('file', file);
      form_data.append('<?php echo $this->security->get_csrf_token_name() ?>', token);

      $.ajax({
          url: site + 'oksigen/stok-tabung/upload',
          type: 'post',
          data: form_data,
          //untuk input data dengan gambar jangan lupaa proces data dan content type
          processData: false,
          contentType: false,
      }).done(function(res) {
          $('input[name="' + csrfName + '"]').val(res.csrfHash);
          // console.log(res);
          getDataList();
          alert(res.message)
          $('#file').val('')
      }).fail(function(e) {
          // console.log(e)
      })

  })

  $('#file').change(function(event) {
      let type = event.target.files[0].type
      let _size = event.target.files[0].size;
      if (type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
          alert('File Bukan Excel')
          $('#file').val('')
      }
      if (_size >= 1555555) {
          alert(`File Terlalu Besar, Max 1.55 MB `)
          $('#file').val('')
      }
  })

  $(document).on('click', '.btnUpload', function(e){
    $('#formupload').slideToggle('slow');
    $('.select-all').select2('val', '');
  });

  $(document).on('click', '.download', function(e){
      url = site + '/repository/template/import_data_stok_oksigen.xlsx';
      window.location.href = url;
  });

  //set button edit
  $(document).on('click', '.btnEdit', function(e){
    formReset();
    $('#formEntry').attr('action', site + 'oksigen/stok-tabung/update');
    var rsId   = $(this).data('id');
    var tanggal = $(this).data('date');
    $('#modalEntryForm').modal({
      backdrop: 'static'
    });
    $('#id_rs').attr('disabled', true);
    $('#idRs').val(rsId);
    $('#datetanggal').val(tanggal);
    var postData = {
      'rsId'   : rsId,
      'publishDate' : tanggal,
      '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()
    };
    run_waitMe($('#frmEntry'));
    $.ajax({
      type: 'POST',
      url: site + 'oksigen/stok-tabung/details',
      data: postData,
      dataType: 'json',
      success: function(data) {
        console.log(data);
        $('input[name="'+csrfName+'"]').val(data.csrfHash);
        if(data.status != 0) {
          $('#id_rs').select2('val', data.detail.id_rs).trigger('change');
          $('#tanggal').val(data.detail.tanggal);
          let no = 1;
          $.each(data.message, function(key,value){
            $('#tblOtgInput > tbody > tr').find('td#nm_tabung_'+no).text(value['nm_tabung']);
            $('#tblOtgInput > tbody > tr').find('td > #tot_jum_stok'+no).val(value['total_stok_tabung']);
            no++;
          });
        }
        $('#frmEntry').waitMe('hide');
      }
    });
  });

  $(document).on('click', '.btnDelete', function(e){
    e.preventDefault();
    var postData = {
      'rsId': $(this).data('id'),
      'publishDate': $(this).data('date'),
      '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+csrfName+'"]').val()
    };
    $(this).html('<i class="fa fa-hourglass-half"></i>');
    $(this).addClass('disabled');
    run_waitMe($('#formParent'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda ingin menghapus data ini ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response) {
            if (response) {
              $('.btnDelete').html('<i class="fa fa-times"></i>');
              $('.btnDelete').removeClass('disabled');
              $('#formParent').waitMe('hide');
            }
          }
        },
        "main" : {
          "label" : "<i class='fa fa-check'></i> Ya, Lanjutkan",
          "className" : "btn-primary",
          callback:function(response){
            if (response) {
              $.ajax({
                url: site + 'oksigen/stok-tabung/delete',
                type: "POST",
                data: postData,
                dataType: "json",
              }).done(function(data) {
                $('input[name="'+csrfName+'"]').val(data.csrfHash);
                if(data.status == 0) {
                  $('#errSuccess').html('<div class="alert alert-danger">'+
                                         '<strong>Informasi!</strong> '+ data.message +
                                        '</div>');
                } else {
                  $('#errSuccess').html('<div class="alert alert-dismissable alert-success">'+
                                          '<strong>Sukses!</strong> '+ data.message +
                                          '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                       '</div>');
                  getDataList();
                }
                $('#formParent').waitMe('hide');
              }).fail(function() {
                $('#errSuccess').html('<div class="alert alert-danger">'+
                                      '<strong>Peringatan!</strong> Proses delete data gagal...'+
                                     '</div>');
                $('#formParent').waitMe('hide');
              }).always(function() {
                $('.btnDelete').html('<i class="fa fa-times"></i>');
                $('.btnDelete').removeClass('disabled');
              });
            }
          }
        }
      }
    });
  });

  $(document).on('keypress keyup', '.nominal',function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });

</script>
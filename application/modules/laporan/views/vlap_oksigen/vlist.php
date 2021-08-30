<style>
  .clockpicker-popover
  {
    z-index : 9999;
  }
</style>
<div class="container">
  <div class="row" id="formParent">    
    <div class="col-xs-12 col-sm-12">
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errSuccess"></div>
    </div>
    <div class="col-xs-12 col-sm-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <?php echo form_open(site_url('#'), array('id'=>'formFilter', 'style'=>'display:;margin-bottom:20px;')); ?>
                <div style="background:#FFF;padding:20px;border:1px solid #CCC;box-shadow:0px 0px 10px #CCC;">
                    <div class="row">
                          <!-- <div class="col-xs-12 col-sm-2">
                            <div class="form-group">
                              <label for="jenis" class="control-label"><b>Pilih Jenis Laporan <font color="red" size="1em">(Wajib)</font></b></label>
                                <select class="select-data" name="typeReport" id="typeReport" style="width:100%">
                                <option value="0">Pilih Jenis</option>
                                <option value="1">Laporan</option>
                                <option value="2">Invoice Transaksi</option>
                                <option value="3">Rekap Transaksi</option>
                                </select>
                            </div>
                          </div> -->

                          <div class="col-xs-12 col-sm-3">
                            <div class="form-group required">
                                <label for="regency" class="control-label"><b>Kabupaten/Kota <font color="red" size="1em"></font></b></label>
                                <?php echo form_dropdown('regency', $list_regency_id, $this->input->post('regency'), 'class="select-all"'); ?>
                                <div class="help-block">
                              </div>
                            </div>
                          </div>

                        <div class="col-xs-12 col-sm-3">
                            <label for="tgl_range" class="control-label"><b>Pilih Tanggal <font color="red" size="1em" id="lblTanggal"></font></b></label>
                            <div class="input-daterange input-group" id="datepicker3">
                                <input type="text" class="input-small form-control" name="start_date" id="start_date" />
                                <span class="input-group-addon">Sampai</span>
                                <input type="text" class="input-small form-control" name="end_date" id="end_date"/>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-left">
                          <div class="btn-toolbar">
                              <button type="button" class="btn btn-success" id="cetakExcel"><i class="fa fa-file-excel-o"></i> EXPORT KE EXCEL </button>
                              <button type="button" class="btn btn-danger" name="cancel" id="cancel"><i class="fa fa-refresh"></i></button>
                          </div>
                        </div>
                        <div class="pull-right">
                          <div class="btn-toolbar">
                              <!-- <button type="button" class="btn btn-default btnFilter" name="button"><i class="fa fa-times"></i> CLOSE</button> -->
                          </div>
                        </div>
                    </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
  </div>
</div><!-- container -->

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var csrfName  = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site      = '<?php echo site_url();?>';
  var truckID   = '';
  var suppID    = '';

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
    // $('#daterangepicker3').daterangepicker();
    // $('#daterangepicker3').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' });
    $('#datepicker3').datepicker();
  });

  //cetak laporan excel bagi Supplier
  $(document).on('click', '#cetakExcel', function(e){
    // let kabkota  = $('#formFilter').find('select[name="regency"]').val();
    // let start_date  = $('#formFilter').find('input[name="start_date"]').val();
    // let end_date    = $('#formFilter').find('input[name="end_date"]').val();

    // url = site + 'laporan/laporan-pengujian/export-to-excel?regency_id='+kabkota+'&start_date='+start_date+'&end_date='+end_date;
    url = site + 'laporan/laporan-oksigen/export-to-excel';
    window.location.href = url;
  });

</script>
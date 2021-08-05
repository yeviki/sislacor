<div class="container">
  <div class="row" id="formParent">
    <div class="col-xs-12 col-sm-12">
      <div class="btn-toolbar" style="margin-bottom: 15px">
        <a type="button" href="<?php echo site_url('konfirmasi-kasus/identifikasi'); ?>" class="btn btn-primary-alt" name="button" style="padding:11px 16px;"><b><i class="fa fa-table"></i> Data Kasus</b></a>
        <a type="button" href="<?php echo site_url('konfirmasi-kasus/identifikasi/create') ?>" class="btn btn-inverse" name="button" style="padding:12px 16px;"><b><i class="fa fa-plus"></i> Tambah Baru</b></a>
      </div>
    </div>
    <?php echo form_open(site_url('konfirmasi-kasus/identifikasi/create'), array('id'=>'formPasien')); ?>
    <div class="col-xs-12 col-sm-12" style="margin-bottom:10px;">
      <?php echo $this->session->flashdata('message'); ?>
      <div id="errDaftar"></div>
      <div class="row">
        <div class="col-xs-12 col-sm-7" style="margin-bottom:-15px;">
          <h3><b><?php echo ($this->app_loader->is_admin()) ? 'DINAS KESEHATAN PROVINSI' : hospital($this->app_loader->current_hospital(), 1); ?></b></h3>
        </div>
        <div class="col-xs-12 col-sm-5">
          <div class="row">
            <div class="form-group required">
              <label class="control-label col-xs-12 col-sm-4" style="margin-top:6px;font-size:15px;"><b>No Rekap Medis <font color="red">*</font></b></label>
              <div class="col-xs-12 col-sm-8">
                <input type="text" class="form-control" placeholder="Nomor Rekap Medis" name="norm" id="norm" value="<?php echo $this->input->post('norm'); ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Identitas Pasien</p>
        </div>
        <div class="panel-body">
          <?php echo form_hidden('tokenId', $this->encryption->encrypt(1)); ?>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="namalkp" class="control-label"><b>Nama Pasien <font color="red">*</font></b></label>
                <input type="text" class="form-control toUpperCase" name="namalkp" id="namalkp" placeholder="Nama Pasien" value="<?php echo $this->input->post('namalkp', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="nik" class="control-label"><b>NIK <font color="red">*</font></b></label>
                <input type="number" class="form-control nominal" name="nik" id="nik" placeholder="NIK" value="<?php echo $this->input->post('nik', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="nokk"><b>NO KK</b></label>
                <input type="number" class="form-control nominal" name="nokk" id="nokk" placeholder="NO KK" value="<?php echo $this->input->post('nokk', TRUE); ?>">
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="nohp" class="control-label"><b>No HP <font color="red">*</font></b></label>
                <input type="number" class="form-control nominal" name="nohp" id="nohp" placeholder="No HP" value="<?php echo $this->input->post('nohp', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tmptlhr" class="control-label"><b>Tempat Lahir <font color="red">*</font></b></label>
                <input type="text" class="form-control toUpperCase" name="tmptlhr" id="tmptlhr" placeholder="Tempat Lahir" value="<?php echo $this->input->post('tmptlhr', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tgllhr" class="control-label"><b>Tanggal Lahir <font color="red">*</font></b></label>
                <div class="input-group date birthdate">
                  <input type="text" class="form-control mask" name="tgllhr" id="tgllhr" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tgllhr', TRUE); ?>">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="umur"><b>Umur (Th)</b></label>
                <input type="text" class="form-control" name="umur" id="umur" placeholder="Umur (th)" value="<?php echo $this->input->post('umur', TRUE); ?>" readonly>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="gender" class="control-label"><b>Jenis Kelamin <font color="red">*</font></b></label>
                <?php echo form_dropdown('gender', array(1=>'Laki-laki', 2=>'Perempuan'), $this->input->post('gender', TRUE), 'class="select-all" id="gender"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="province" class="control-label"><b>Provinsi <font color="red">*</font></b></label>
                <?php echo form_dropdown('province', $data_province, $this->input->post('province', TRUE), 'class="select-all" id="province"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="regency" class="control-label"><b>Kab/Kota <font color="red">*</font></b></label>
                <?php echo form_dropdown('regency', array(''=>'Pilih Kab/Kota'), $this->input->post('regency', TRUE), 'class="select-all" id="regency"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="district" class="control-label"><b>Kecamatan <font color="red">*</font></b></label>
                <?php echo form_dropdown('district', array(''=>'Pilih Kecamatan'), $this->input->post('district', TRUE), 'class="select-all" id="district"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="village" class="control-label"><b>Kelurahan <font color="red">*</font></b></label>
                <?php echo form_dropdown('village', array(''=>'Pilih Kelurahan'), $this->input->post('village', TRUE), 'class="select-all" id="village"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label for="address"><b>Alamat Domisili</b></label>
                <input type="text" class="form-control toUpperCase" name="address" id="address" placeholder="Alamat Domisili" value="<?php echo $this->input->post('address', TRUE); ?>" >
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="study"><b>Pendidikan</b></label>
                <?php echo form_dropdown('study', $data_study, $this->input->post('study', TRUE), 'class="select-all" id="study"'); ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                <label for="job"><b>Pekerjaan</b></label>
                <input type="text" class="form-control toUpperCase" name="job" id="job" placeholder="Pekerjaan" value="<?php echo $this->input->post('job', TRUE); ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Riwayat Sosial – Ekonomi</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-7">
              <div class="form-group required">
                <label for="aktivitasfisik" class="control-label" style="margin-bottom:0px;"><b>Aktivitas Fisik :</b></label>
                <?php foreach ($data_fisik as $key => $df): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="aktivitasfisik" id="<?php echo 'aktivitasfisik_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('aktivitasfisik', $key, ($key==1) ? TRUE : ''); ?>> <b><?php echo $df; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label for="merokok" class="control-label" style="margin-bottom:0px;"><b>Merokok :</b></label>
                <?php foreach (info(2) as $key => $m): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="merokok" id="<?php echo 'merokok_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('merokok', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $m; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="konsumsialkohol" class="control-label" style="margin-bottom:0px;"><b>Konsumsi Minuman Beralkohol :</b></label>
                <?php foreach (info(2) as $key => $ka): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="konsumsialkohol" id="<?php echo 'konsumsialkohol_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('konsumsialkohol', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $ka; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="gajiperbln" class="control-label" style="margin-bottom:0px;"><b>Penghasilan Perbulan :</b></label>
                <?php foreach ($data_gaji as $key => $dg): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="gajiperbln" id="<?php echo 'gajiperbln_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('gajiperbln', $key, ''); ?>> <b><?php echo $dg; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label for="anggotabpjs" class="control-label" style="margin-bottom:0px;"><b>Keanggotaan BPJS :</b></label>
                <?php foreach (info(2) as $key => $b): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="anggotabpjs" id="<?php echo 'anggotabpjs_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('anggotabpjs', $key, ($key=='I') ? TRUE : ''); ?>> <b><?php echo $b; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6" style="display:none;" id="field_anggotabpjs">
              <div class="form-group required">
                <label for="nobpjs" class="control-label"><b>Nomor BPJS :</b></label>
                <input type="number" class="form-control nominal" name="nobpjs" id="nobpjs" placeholder="Nomor BPJS" value="<?php echo $this->input->post('nobpjs', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Pemeriksaan Fisik</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="suhu_tubuh" class="control-label"><b>Suhu Tubuh</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="suhu_tubuh" id="suhu_tubuh" placeholder="Suhu Tubuh" value="<?php echo $this->input->post('suhu_tubuh', TRUE); ?>">
                  <span class="input-group-addon">C</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tekanandarah" class="control-label"><b>Tekanan Darah</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="tekanandarah" id="tekanandarah" placeholder="Tekanan Darah" value="<?php echo $this->input->post('tekanandarah', TRUE); ?>">
                  <span class="input-group-addon">mmHg</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="nadi" class="control-label"><b>Denyut Nadi</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="nadi" id="nadi" placeholder="Denyut Nadi" value="<?php echo $this->input->post('nadi', TRUE); ?>">
                  <span class="input-group-addon">x/menit</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="pernapasan" class="control-label"><b>Pernapasan</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="pernapasan" id="pernapasan" placeholder="Pernapasan" value="<?php echo $this->input->post('pernapasan', TRUE); ?>">
                  <span class="input-group-addon">x/menit</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="tinggibadan" class="control-label"><b>Tinggi Badan</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="tinggibadan" id="tinggibadan" placeholder="Tinggi Badan" value="<?php echo $this->input->post('tinggibadan', TRUE); ?>">
                  <span class="input-group-addon">Cm</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="beratbadan" class="control-label"><b>Berat Badan</b></label>
                <div class="input-group">
                  <input type="text" class="form-control calcius" name="beratbadan" id="beratbadan" placeholder="Berat Badan" value="<?php echo $this->input->post('beratbadan', TRUE); ?>">
                  <span class="input-group-addon">Kg</span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="form-group required">
                <label for="triage" class="control-label"><b>Hasil Triage (kalau ke UGD)</b></label>
                <input type="text" class="form-control" name="triage" id="triage" placeholder="Hasil Triage (kalau ke UGD)" value="<?php echo $this->input->post('triage', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Informasi Klinis</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="tanggalkeluhan" class="control-label"><b>Tanggal pertama muncul keluhan</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask" name="tanggalkeluhan" id="tanggalkeluhan" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tanggalkeluhan', TRUE); ?>">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <?php foreach ($data_gejala as $key => $ge){
              if($ge['tp_field'] == 'text') {
                echo '<div class="col-xs-12 col-sm-6">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$ge['nm_field'].'" class="control-label"><b>'.$ge['title'].' '.(($ge['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<input type="text" class="form-control" name="'.$ge['nm_field'].'" id="'.$ge['nm_field'].'" placeholder="'.$ge['title'].'" value="'.$this->input->post($ge['nm_field'], TRUE).'">';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              } else {
                echo '<div class="col-xs-12 col-sm-3" style="margin-bottom:20px;padding-left:0px;">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$ge['nm_field'].'" class="col-xs-12 control-label" style="margin-bottom:5px;"><b>'.$ge['title'].' '.(($ge['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<div class="col-xs-12">';
                    foreach (info($ge['val_field']) as $row => $r) {
                      echo '<label class="radio-inline">';
                        echo '<input type="radio" name="'.$ge['nm_field'].'" id="'.$ge['nm_field'].'_'.$row.'" value="'.$row.'" '.(set_radio($ge['nm_field'], $row, ($row=='N') ? TRUE : '')).'> <b>'.$r.'</b>';
                      echo '</label>';
                    }
                    echo '</div>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              }
            } ?>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Riwayat Penyakit Dahulu (Komorbiditas)</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <?php foreach ($data_penyerta as $key => $dp) {
              if($dp['tp_field'] == 'text') {
                echo '<div class="col-xs-12 col-sm-6">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$dp['nm_field'].'" class="control-label"><b>'.$dp['title'].' '.(($dp['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<input type="text" class="form-control" name="'.$dp['nm_field'].'" id="'.$dp['nm_field'].'" placeholder="'.$dp['title'].'" value="'.$this->input->post($dp['nm_field'], TRUE).'">';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              } else {
                echo '<div class="col-xs-12 col-sm-3">';
                  echo '<div class="form-group required">';
                    echo '<label class="checkbox-inline">';
                      echo '<input type="checkbox" name="'.$dp['nm_field'].'" id="'.$dp['nm_field'].'" value="Y"><b>'.$dp['title'].'</b>';
                    echo '</label>';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              }
            } ?>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Diagnosis</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <?php foreach ($data_diagnosis as $key => $dd) {
              if($dd['tp_field'] == 'text') {
                echo '<div class="col-xs-12 col-sm-6">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$dd['nm_field'].'" class="control-label"><b>'.$dd['title'].' '.(($dd['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    echo '<input type="text" class="form-control" name="'.$dd['nm_field'].'" id="'.$dd['nm_field'].'" placeholder="'.$dd['title'].'" value="'.$this->input->post($dd['nm_field'], TRUE).'">';
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              } else {
                echo '<div class="col-xs-12 col-sm-3">';
                  echo '<div class="form-group required">';
                    echo '<label for="'.$dd['nm_field'].'" class="control-label" style="margin-bottom:0px;"><b>'.$dd['title'].' '.(($dd['mandatory']=='Y') ? '<font color="red">*</font>':'').'</b></label>';
                    foreach (info($dd['val_field']) as $row => $rs) {
                      echo '<div class="radio">';
                        echo '<label>';
                          echo '<input type="radio" name="'.$dd['nm_field'].'" id="'.$dd['nm_field'].'_'.$row.'" value="'.$row.'" '.(set_radio($dd['nm_field'], $row, ($row=='N') ? TRUE : '')).'> <b>'.$rs.'</b>';
                        echo '</label>';
                      echo '</div>';
                    }
                    echo '<div class="help-block"></div>';
                  echo '</div>';
                echo '</div>';
              }
            } ?>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Faktor Kontak/Paparan</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="riwayatperjalanan" class="control-label" style="margin-bottom:0px;"><b>Dalam 14 hari sebelum sakit, apakah memiliki riwayat perjalanan ?</b></label>
                <?php foreach (info() as $key => $rp): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="riwayatperjalanan" id="<?php echo 'riwayatperjalanan_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('riwayatperjalanan', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $rp; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="kontaksuspekcovid19" class="control-label" style="margin-bottom:0px;"><b>Dalam 14 hari sebelum sakit, apakah memiliki kontak erat dengan kasus suspek COVID-19 ?</b></label>
                <?php foreach (info(2) as $key => $sp): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="kontaksuspekcovid19" id="<?php echo 'kontaksuspekcovid19_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('kontaksuspekcovid19', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $sp; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="kontakcovid19" class="control-label" style="margin-bottom:0px;"><b>Dalam 14 hari sebelum sakit, apakah memiliki kontak erat kasus konfirmasi COVID-19 ?</b></label>
                <?php foreach (info(2) as $key => $co): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="kontakcovid19" id="<?php echo 'kontakcovid19_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('kontakcovid19', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $co; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="pasienispa" class="control-label" style="margin-bottom:0px;"><b>Apakah pasien termasuk kelompok ISPA berat (pneumonia hingga membutuhkan perawatan di RS) yang tidak diketahui penyebabnya</b></label>
                <?php foreach (info(2) as $key => $pi): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="pasienispa" id="<?php echo 'pasienispa_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('pasienispa', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $pi; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row" id="field_riwayatperjalanan" style="display:none;">
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="negara" class="control-label"><b>Negara <font color="red">*</font></b></label>
                <?php echo form_dropdown('negara', $data_negara, $this->input->post('negara', TRUE), 'class="select-all" id="negara"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="kota" class="control-label"><b>Kota <font color="red">*</font></b></label>
                <input type="text" class="form-control toUpperCase" name="kota" id="kota" placeholder="Kota" value="<?php echo $this->input->post('kota', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="traveldate" class="control-label"><b>Tanggal Perjalanan</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask" name="traveldate" id="traveldate" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('traveldate', TRUE); ?>">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3" style="margin-top:-10px;">
              <div class="form-group required">
                <label for="arrivaldate" class="control-label"><b>Tanggal Kepulangan</b></label>
                <div class="input-group date datemonth">
                  <input type="text" class="form-control mask" name="arrivaldate" id="arrivaldate" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('arrivaldate', TRUE); ?>">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="petugaskesehatan" class="control-label" style="margin-bottom:0px;"><b>Merupakan Petugas Kesehatan</b></label>
                <?php foreach (info(2) as $key => $pk): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="petugaskesehatan" id="<?php echo 'petugaskesehatan_'.$key; ?>" value="<?php echo $key; ?>" <?php echo set_radio('petugaskesehatan', $key, ($key=='N') ? TRUE : ''); ?>> <b><?php echo $pk; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-9" style="display:none;" id="field_petugaskesehatan">
              <div class="form-group required">
                <label for="profesimedis" class="control-label"><b>Petugas Kesehatan</b></label>
                <?php echo form_dropdown('profesimedis', $data_medis, $this->input->post('profesimedis', TRUE), 'class="select-all" id="profesimedis"'); ?>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <label for="alatpelindungdiri" style="margin-bottom:5px;"><b>Alat perlindungan diri yang digunakan ?</b></label>
            </div>
            <div class="col-xs-12 col-sm-1">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="gown" id="gown" value="Y"><b>Gown</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="maskermedis" id="maskermedis" value="Y"><b>Masker Bedah</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="sarungtangan" id="sarungtangan" value="Y"><b>Sarung Tangan</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="maskern95standardffp2" id="maskern95standardffp2" value="Y"><b>Masker N95 standard FFP2</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-1">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="ffp3" id="ffp3" value="Y"><b>FFP3</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="kacamatapelindung" id="kacamatapelindung" value="Y"><b>Kacamata Pelindung Goggle</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group required">
                <label class="checkbox-inline">
                  <input type="checkbox" name="tidakmemakaiapd" id="tidakmemakaiapd" value="Y"><b>Tidak Sama Sekali</b>
                </label>
                <div class="help-block"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group required">
                <label for="paparanlainnya" class="control-label"><b>Paparan Lainnya Sebutkan</b></label>
                <input type="text" class="form-control" name="paparanlainnya" id="paparanlainnya" placeholder="Paparan Lainnya" value="<?php echo $this->input->post('paparanlainnya', TRUE); ?>">
                <div class="help-block"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Tindakan Perawatan</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12 col-sm-3">
              <div class="form-group required">
                <label for="tindaklanjut" class="control-label" style="margin-bottom:0px;"><b>Tindak lanjut terhadap pasien <font color="red">*</font></b></label>
                <?php foreach (array(1=>'Dirawat di Rumah Sakit', 2=>'Dirujuk ke RS Rujukan Pemerintah', 3=>'Isolasi Diri di Rumah', 4=>'Isolasi di Fasilitas Pemerintah') as $key => $tl): ?>
                  <div class="radio">
                    <label>
                      <input type="radio" name="tindaklanjut" id="<?php echo 'tindaklanjut_'.$key ?>" value="<?php echo $key; ?>" <?php echo set_radio('tindaklanjut', $key, ($key==1) ? TRUE : ''); ?>><b><?php echo $tl; ?></b>
                    </label>
                  </div>
                <?php endforeach; ?>
                <div class="help-block"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-9">
              <div class="row">
                <div class="col-xs-12 col-sm-5">
                  <div class="form-group required">
                    <label for="tanggalmasukrawat" class="control-label"><b>Tanggal Mulai Dirawat <font color="red">*</font></b></label>
                    <div class="input-group date datemonth">
                      <input type="text" class="form-control mask" name="tanggalmasukrawat" id="tanggalmasukrawat" placeholder="dd/mm/yyyy" data-inputmask="'alias': 'date'" value="<?php echo $this->input->post('tanggalmasukrawat', TRUE); ?>">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-7" id="field_hospital">
                  <div class="form-group required">
                    <label for="hospital" class="control-label"><b><span id="lblRs">Nama RS</span> <font color="red">*</font></b></label>
                    <?php if ($this->app_loader->is_admin()) {
                      echo form_dropdown('hospital', $data_hospital, $this->input->post('hospital', TRUE), 'class="select-all" id="hospital"');
                    } else { ?>
                      <input type="text" class="form-control" name="namarsrawat" id="namarsrawat" placeholder="Nama Rumah Sakit" value="<?php echo rujukan($this->app_loader->current_hospital()); ?>" readonly>
                   <?php } ?>
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12" id="field_rsrujukan" style="display:none;">
                  <div class="form-group required">
                    <label for="rsrujukan" class="control-label"><b>Nama Rumah Sakit Rujukan <font color="red">*</font></b></label>
                    <?php echo form_dropdown('rsrujukan', $data_rsrujukan, $this->input->post('rsrujukan', TRUE), 'class="select-all" id="rsrujukan"'); ?>
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-5" id="field_fasilitas" style="display:none;">
                  <div class="form-group required">
                    <label for="nm_fasilitas" class="control-label"><b>Lokasi Isolasi/Karantina <font color="red">*</font></b></label>
                    <?php echo form_dropdown('nm_fasilitas', $data_fasilitas, $this->input->post('nm_fasilitas', TRUE), 'class="select-all" id="nm_fasilitas"'); ?>
                    <div class="help-block"></div>
                  </div>
                </div>
              </div>
              <div class="row" id="field_rawat">
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group required">
                    <label for="dirawaticu" class="control-label"><b>Dirawat di Icu</b></label>
                    <?php echo form_dropdown('dirawaticu', info(1), $this->input->post('dirawaticu', TRUE), 'class="select-all" id="dirawaticu"'); ?>
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                  <div class="form-group required">
                    <label for="intubasi" class="control-label"><b>Intubasi</b></label>
                    <?php echo form_dropdown('intubasi', info(1), $this->input->post('intubasi', TRUE), 'class="select-all" id="intubasi"'); ?>
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                  <div class="form-group required">
                    <label for="penggunaanemco" class="control-label"><b>Penggunaan oksigenasi membrane ekstraskorporeal ?</b></label>
                    <?php echo form_dropdown('penggunaanemco', info(1), $this->input->post('penggunaanemco', TRUE), 'class="select-all" id="penggunaanemco"'); ?>
                    <div class="help-block"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-green">
        <div class="panel-heading">
          <p style="font-size:16px;">Daftar Kontak Pasien (dengan orang-orang dekat)</p>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-12">
              <table class="table table-striped table-bordered" width="100%">
                <tbody>
                  <tr>
                    <td>
                      <div class="row">
                        <div class="col-xs-12 col-md-3">
      										<div class="form-group required">
                            <label class="control-label" for="kontak0namalkp"><b>Nama</b></label>
                            <input type="text" class="form-control toUpperCase" name="kontak[0][namalkp]" id="kontak0namalkp" placeholder="Nama Lengkap" value="<?php echo $this->input->post('kontak[0][namalkp]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
      										<div class="form-group required">
                            <label class="control-label" for="kontak0nik"><b>NIK</b></label>
                            <input type="text" class="form-control" name="kontak[0][nik]" id="kontak0nik" placeholder="NIK" value="<?php echo $this->input->post('kontak[0][nik]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
      										<div class="form-group required">
                            <label class="control-label" for="kontak0umur"><b>Umur (Th)</b></label>
                            <input type="number" class="form-control nominal" name="kontak[0][umur]" id="kontak0umur" placeholder="Umur (Th)" value="<?php echo $this->input->post('kontak[0][umur]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                          <div class="form-group required">
                            <label class="control-label" for="kontak0gender"><b>Jenis Kelamin</b></label>
                            <?php echo form_dropdown('kontak[0][gender]', array(1=>'Laki-laki', 2=>'Perempuan'), $this->input->post('kontak[0][gender]', TRUE), 'class="select-all" id="kontak0gender"'); ?>
                            <div class="help-block"></div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 col-md-9">
                          <div class="form-group required">
                            <label class="control-label" for="kontak0address"><b>Alamat Rumah</b></label>
                            <input type="text" class="form-control alamatrumahsama toUpperCase" name="kontak[0][address]" id="kontak0address" placeholder="Alamat Rumah" value="<?php echo $this->input->post('kontak[0][address]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                          <div class="form-group" style="margin-top:-10px;margin-bottom:10px;">
                            <label class="checkbox-inline">
                              <input type="checkbox" id="kotak0rmhsama" class="rmhsama" name="kontak[0][rmhsama]" value="1"><b>Rumah Sama Dengan Pasien</b>
                            </label>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
      										<div class="form-group required">
                            <label class="control-label" for="kontak0nohp"><b>No. HP</b></label>
                            <input type="number" class="form-control nominal" name="kontak[0][nohp]" id="kontak0nohp" placeholder="No HP" value="<?php echo $this->input->post('kontak[0][nohp]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 col-md-6">
      										<div class="form-group required">
                            <label class="control-label" for="kontak0hubdgnkasus"><b>Hubungan Dengan Kasus</b></label>
                            <input type="text" class="form-control toUpperCase" name="kontak[0][hubdgnkasus]" id="kontak0hubdgnkasus" placeholder="Hubungan Dengan Kasus" value="<?php echo $this->input->post('kontak[0][hubdgnkasus]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
      										<div class="form-group required">
                            <label class="control-label" for="kontak0aktivitas"><b>Aktivitas Yang Dilakukan</b></label>
                            <input type="text" class="form-control toUpperCase" name="kontak[0][aktivitas]" id="kontak0aktivitas" placeholder="Aktivitas Yang Dilakukan" value="<?php echo $this->input->post('kontak[0][aktivitas]', TRUE); ?>">
                            <div class="help-block"></div>
                          </div>
                        </div>
                      </div>
  								  </td>
    								<td align="center" width="3%">
    									<button type="button" class="deleteItem btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
    								</td>
                  </tr>
                </tbody>
                <tfoot>
      						<tr>
      							<td colspan="2">
                      <button type="button" class="addItem btn btn-orange btn-sm"><span class="fa fa-plus"></span> Tambah Kontak Kasus Lain</button>
                    </td>
      						</tr>
      					</tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="panel-footer" style="padding:10px;">
          <div class="btn-toolbar">
            <div class="pull-right">
              <a type="button" href="<?php echo site_url('konfirmasi-kasus/identifikasi'); ?>" class="btn btn-danger" style="padding:12px 16px;"><i class="fa fa-times"></i> CANCEL</a>
              <button type="submit" name="save" id="save" class="btn btn-primary" style="padding:12px 16px;"><i class="fa fa-check"></i> SUBMIT</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  var strDate  = new Date('<?php echo date('Y-m-d', strtotime('1920-01-01')); ?>');
  var lastDate = new Date('<?php echo date('Y-m-d', strtotime('2020-01-01')); ?>');
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var site     = '<?php echo site_url();?>';
  var regeID = '', distID = '', villID = '';
  $(document).ready(function() {
    $('.mask').inputmask();
    $(".birthdate").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
      startDate: strDate
    });
    $(".datemonth").datepicker({
      autoclose: true,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      startView: 'month',
      startDate: lastDate
    });
    $('#province').select2('val', 13).trigger('change');
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

  $(document).on('change', '#tgllhr', function(e) {
    var date = $(this).val();
    var arrDate = date.split('/');
    if(date === "")
      $('#umur').val('');
    else {
      var today = new Date();
      var birth = new Date(arrDate[2]+'-'+arrDate[1]+'-'+arrDate[0]);
      var year  = 0;
      if (today.getMonth() < birth.getMonth()) {
			  year = 1;
		  } else if ((today.getMonth() == birth.getMonth()) && today.getDate() < birth.getDate()) {
			  year = 1;
		  }
  		var age = today.getFullYear() - birth.getFullYear() - year;
      $('#umur').val((age < 0 || isNaN(age)) ? 0 : age);
    }
  });
  $("#tgllhr").trigger("change");

  $(document).on('change', 'input[type="radio"]', function(e) {
    let id   = $(this).val();
    let name = $(this).attr('name');
    if(id != 'Y') {
      $('#field_'+name).hide();
    } else {
      $('#field_'+name).show();
    }
  });
  $('input[type="radio"]:checked').trigger('change');

  $(document).on('change', 'input[name="tindaklanjut"]', function(e) {
    let id = $(this).val();
    if(id == 2) {
      $('#field_rsrujukan').show();
      $('#field_rawat').show();
      $('#lblRs').text('Rumah Sakit Rawat Saat Ini');
      $('#field_fasilitas').hide();
    } else if(id == 3 || id == 4) {
      $('#lblRs').text('Rumah Sakit Rujukan Swab');
      $('#field_hospital').show();
      $('#field_rsrujukan').hide();
      $('#field_rawat').hide();
      (id==4) ? $('#field_fasilitas').show() : $('#field_fasilitas').hide();
    } else {
      $('#lblRs').text('Nama Rumah Sakit');
      $('#field_rsrujukan').hide();
      $('#field_hospital').show();
      $('#field_rawat').show();
      $('#field_fasilitas').hide();
    }
  });
  $('input[name="tindaklanjut"]:checked').trigger('change');

  $(document).on('click', '.addItem', function(e){
    e.preventDefault();
    var tbody = $(this).closest('table').find('tbody');
    var row  = tbody.find('tr');
    row.find('.select-all').each(function(index){
      $(this).select2('destroy');
    });

    var i = tbody.find('tr').length;
    var html = tbody.find('tr').first().clone().find('input').attr('id', function(idx, attrVal) {
                let nm = attrVal.split('0');
                return 'kontak' + i + nm[1];
               }).attr('name', function(idx, attrVal) {
                let nm = attrVal.split('[0]');
                return 'kontak' + '['+i+']' + nm[1];
              }).val('').removeAttr('checked').removeAttr('readonly').end().find('select').attr('id', function(idx, attrVal){
                let nm = attrVal.split('0');
                return 'kontak' + i + nm[1];
              }).attr('name', function(idx, attrVal){
                let nm = attrVal.split('[0]');
                return 'kontak' + '['+i+']' + nm[1];
              }).select2('val',1).end().find('label.control-label').attr('for', function(idx, attrVal) {
                let nm = attrVal.split('0');
                return 'kontak' + i + nm[1];
              }).end();
    $(this).closest('table').find('tbody:last').append(html);
    $("select.select-all").select2();
  });

  $(document).on('click', '.deleteItem', function(e){
    var tbody = $(this).closest('table').find('tbody tr:last');
    var total = $(this).closest('table').find('tbody > tr').length;
    if(total > 1)
      tbody.remove();
  });

  $(document).on('change', '.rmhsama', function(e){
		var address = $("#address").val() + " ";
		if($("#village").val() != ""){
			address += $("#village option:selected").text()+", ";
		}
		if($("#district").val() != ""){
			address += $("#district option:selected").text()+", ";
		}
		if($("#regency").val() != ""){
			address += $("#regency option:selected").text()+", ";
		}
		if($("#province").val() != ""){
			address += $("#province option:selected").text();
		}
		if($(this).is(":checked")){
      $(this).closest("tr").find(".alamatrumahsama").val(address);
			$(this).closest("tr").find(".alamatrumahsama").attr('readonly', 'readonly');
		}else{
			$(this).closest("tr").find(".alamatrumahsama").val("");
      $(this).closest("tr").find(".alamatrumahsama").removeAttr('readonly');
		}
  });
  $('.rmhsama').trigger('change');

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

  //mengambil data kab/kota
  function getRegency(provinceId) {
    regeID = (regeID != '') ? regeID : '<?php echo $this->input->post('regency', TRUE); ?>';
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
    let lblDis = '';
    distID = (distID != '') ? distID : '<?php echo $this->input->post('district', TRUE); ?>';
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
    let lblVil = '';
    villID = (villID != '') ? villID : '<?php echo $this->input->post('village', TRUE); ?>';
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

  $(document).on('input', '.calcius',function (e) {
    var clean = this.value.replace(/[^0-9,]/g, "")
                          .replace(/(,.*?),(.*,)?/, "$1");
    // don't move cursor to end if no change
    if (clean !== this.value) this.value = clean;
  });

  //proses menyimpan data pasien
  $('#formPasien').submit(function(e){
    e.preventDefault();
    var postData = $(this).serialize();
    var formActionURL = $(this).attr('action');
    $("#save").html('<i class="fa fa-hourglass-half"></i> DIPROSES...');
    $("#save").addClass('disabled');
    run_waitMe($('#formParent'));
    bootbox.dialog({
      title: "Konfirmasi",
      message: "Apakah anda akan menyimpan data ini ?",
      buttons: {
        "cancel" : {
          "label" : "<i class='fa fa-times'></i> Tidak",
          "className" : "btn-danger",
          callback:function(response){
            if (response) {
              $('#formParent').waitMe('hide');
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
                  $('#errDaftar').html('<div class="alert alert-danger" id="pesanErr"><strong>Peringatan!</strong> Tolong dilengkapi form inputan dibawah...</div>');
                  $.each(data.message, function(key,value){
                    if(key != 'isi') {
                      $('input[name="'+key+'"], select[name="'+key+'"]').closest('div.required').addClass('has-error').find('div.help-block').text(value);
                    } else {
                      $('#pesanErr').html('<strong>Peringatan!</strong> ' +value);
                    }
                  });
                  $('body,html').animate({
                      scrollTop: (data.message.isi) ? 0 : ($('.has-error').find('input, select').first().focus().offset().top-300)
                  }, 1000);
                } else {
                  $('#errDaftar').html('<div class="alert alert-dismissable alert-success">'+
                                        '<strong>Sukses!</strong> '+ data.message +
                                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' +
                                       '</div>');
                  window.location.href = site + 'konfirmasi-kasus/identifikasi';
                }
                $('#formParent').waitMe('hide');
              }).fail(function() {
                $('#errDaftar').html('<div class="alert alert-danger">'+
                                      '<strong>Peringatan!</strong> Harap periksa kembali data yang diinputkan...'+
                                     '</div>');
                $('#formParent').waitMe('hide');
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

</script>

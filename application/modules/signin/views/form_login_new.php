<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login | Tanggap Covid-19</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->	
      <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.ico') ?>" sizes="32x32"/>
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/bootstrap/css/bootstrap.min.css') ?>">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/animate/animate.css') ?>">
    <!--===============================================================================================-->	
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/css-hamburgers/hamburgers.min.css') ?>">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/select2/select2.min.css') ?>">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/css/util.css') ?>">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/css/main.css') ?>">
    <!--===============================================================================================-->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/form-select2/select2.css'); ?>" />
    
</head>
<body>
	
	<div class="limiter">
		
		<div class="container-login100">
			<div class="wrap-login100">

				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?php echo base_url('assets/login/images/sumbar.png') ?>" alt="IMG">
				</div>

        
          <?php echo form_open(site_url('signin/login'), array('class' => 'form-horizontal login100-form validate-form', 'role' => 'form')); ?>
          <?php echo $this->session->flashdata('message'); ?>
          <h1 style="text-align:center;margin-bottom:40px;">Tanggap <b style="color:rgb(46, 93, 172);">Covid-19</b></h1>
          <!-- <h3 style="text-align:center;">Provinsi Sumatera Barat</h3> -->

					<div class="wrap-input100 validate-input" data-validate = "Silahkan Input Username <?php echo form_error('username'); ?>">
            <input class="input100" type="text" name="username" id="username" placeholder="NIP/Username" value="<?php echo set_value('username'); ?>">
            
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password belum required <?php echo form_error('password'); ?>">
            <input class="input100" type="password" name="password" id="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
            </span>
            <?php if(isset($captcha_img)) echo $captcha_img;?>
					</div>
					
					<div class="container-login100-form-btn">
  					<input type="submit" name="submit" id="submit" class="login100-form-btn" value="Log In">
					</div>

					<div class="text-center p-t-12">
						<p style="text-align:center;color:#A9A9A9;"><?php echo "Hak Cipta ©" . ((date('Y') == "2020") ? "2020" : "2020 - ".date('Y')) . " " . " Team IT Kominfo Prov. Sumbar"; ?></p>
					</div>

          <?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<script src="<?php echo base_url('assets/js/jquery-1.10.2.min.js'); ?>"></script>
      <script type='text/javascript' src='<?php echo base_url('assets/plugins/form-select2/select2.min.js');?>'></script>

    <script type="text/javascript">
      $(".select-data").select2({
        minimumResultsForSearch: Infinity,
        allowClear: false
      });
  		$(function() {
  		  $('#submit').on('click',function() {
  		  	$(this).val("Mencoba Login...");
  		  	$(this).addClass('disabled');
  		    $('#form-login').submit();
  		  });
  		});
    </script>

    <!--===============================================================================================-->	
      <script src="<?php echo base_url('assets/login/vendor/jquery/jquery-3.2.1.min.js') ?>"></script>
    <!--===============================================================================================-->
      <script src="<?php echo base_url('assets/login/vendor/bootstrap/js/popper.js') ?>"></script>
      <script src="<?php echo base_url('assets/login/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!--===============================================================================================-->
      <script src="<?php echo base_url('assets/login/vendor/select2/select2.min.js') ?>"></script>
    <!--===============================================================================================-->
      <script src="<?php echo base_url('assets/login/vendor/tilt/tilt.jquery.min.js') ?>"></script>
      <script >
        $('.js-tilt').tilt({
          scale: 1.1
        })
      </script>
    <!--===============================================================================================-->
      <script src="<?php echo base_url('assets/login/js/main.js') ?>"></script>
      

</body>
</html>
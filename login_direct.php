<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin</title>

    <!-- Bootstrap -->
    <link href="<?php echo assets_adminUrl('bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo assets_adminUrl('css/custom.css'); ?>" rel="stylesheet">
  </head>

  <body class="bg_login">
    <div>
      <a class="hiddenanchor" id="toregister"></a>
      <a class="hiddenanchor" id="tologin"></a>

      <div id="wrapper">

        <div id="login" class=" form">
          <section class="login_content">
			  
			  <?php
			  
			   if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-danger"> <?= $this->session->flashdata('message') ?> </div>
<?php } ?>

             <?php //echo form_open('/loginAction'); ?>
             <form action="<?php echo site_url('/user/login_action');?>" method="post" accept-charset="utf-8">
               <div><label> Username</label>
				<div class="linput">  <?php echo form_input(array('name'=>'email', 'class'=>'form-control',"placeholder"=>"Username",'required'=>true)); ?> <i class="fa fa-user"></i>
              </div></div>
              <div><label> Password</label>
				<div class="linput">  <?php echo form_password(array('name'=>'password', 'class'=>'form-control',"placeholder"=>"Password",'required'=>true)); ?> <i class="fa fa-lock"></i></div>

              </div>
              <div>
				  <input type="hidden" value="submitted" name="submitted"/>
				  <button type="submit" class="btn btn-default submit">Log in</button>
              </div>
              <div class="clearfix"></div>
              <?php echo  form_close(); ?>
			
          </section>
        </div>

       </div>
    </div>
  </body>
</html>

<!DOCTYPE html>
<html>
  <head>
    <title>Admin Login</title>
    <?php $url_prefix = $this->webspice->settings()->site_url_prefix; ?>
    <!-- Bootstrap -->
    <link href="<?php $url_prefix; ?>global/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php $url_prefix; ?>global/admin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="<?php $url_prefix; ?>global/admin/assets/styles.css" rel="stylesheet" media="screen">
     <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="<?php $url_prefix; ?>global/admin/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  </head>
  <body id="login">
    <div class="container">

      <form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>

        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

        <input type="email" id="user_email" class="input-block-level" name="user_email" value="<?php echo set_value('user_email'); ?>" placeholder="Email address">
        <span class="fred"><?php echo form_error('user_email'); ?></span>

        <input type="password" class="input-block-level" id="user_password" name="user_password" value="" placeholder="Password">
        <span class="fred"><?php echo form_error('user_password'); ?></span>

        
        <label class="control-label">Company Name<span class="required">*</span></label>
        <select class="input-block-level" name="company_id">
          <option value="">Select...</option>
          <?php
            $options = $this->db->query("SELECT * FROM company WHERE STATUS = 7")->result();
          ?>
          <?php foreach($options as $option) : ?>
            <option value="<?php echo $option->COMPANY_ID; ?>" <?php echo (isset($edit['COMPANY_ID']) && $edit['COMPANY_ID'] == $option->COMPANY_ID) ? "selected" : ""; ?> ><?php echo $option->COMPANY_NAME ?></option>
          <?php endforeach; ?>
        </select>
        <span class="fred"><?php echo form_error('company_id'); ?></span>

        <!-- <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label> -->
        <br />
        <input type="submit" class="btn btn-large btn-primary" value="Sign in" />

      </form>

    </div> <!-- /container -->
    <script src="<?php $url_prefix; ?>global/admin/vendors/jquery-1.9.1.min.js"></script>
    <script src="<?php $url_prefix; ?>global/admin/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container">
            <div class="row-fluid">
                <div class="span12" id="content">
                    <div class="row-fluid">

                        <!-- here will goes alert message -->
                        <!-- <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4>Success</h4>
                            The operation completed successfully
                        </div> -->
                        <!-- alert message end -->

                          <div class="navbar">
                              <div class="navbar-inner">
                                  <ul class="breadcrumb">
                                      <li>
                                          <b>Change Password</b>  
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>


                         <!-- validation -->
                        <div class="row-fluid">
                             <!-- block -->
                            <div class="block">
                                <div class="navbar navbar-inner block-header">
                                    <div class="muted pull-left">Change Password</div>
                                </div>
                                <div class="block-content collapse in">
                                    <div class="span12">

                                    <?php echo isset($un_matched) ? $un_matched : ""; ?>

                                                <!-- BEGIN FORM-->
                                                <form method="post" action=""  enctype="multipart/form-data" id="" class="form-horizontal">

                                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                    
                                                    <fieldset>
                                                            <div class="control-group">
                                                                <label class="control-label">New Password<span class="required">*</span></label>
                                                                <div class="controls">
                                                                    <input type="password" name="new_password" data-required="1" class="span6 m-wrap" value="" />
                                                                </div>
                                                                <span class="fred"><?php echo form_error('new_password'); ?></span>
                                                            </div>

                                                            <div class="control-group">
                                                                <label class="control-label">Repeat Password<span class="required">*</span></label>
                                                                <div class="controls">
                                                                    <input type="password" name="repeat_password" data-required="1" class="span6 m-wrap" value="" />
                                                                </div>
                                                                <span class="fred"><?php echo form_error('repeat_password'); ?></span>
                                                            </div>
                                                            
                                                            <div class="form-actions">
                                                                <input type="submit" name="submit" class="btn btn-primary" value="Submit Data"  />
                                                                <button type="button" class="btn">Cancel</button>
                                                            </div>
                                                    </fieldset>
                                                </form>
                                                <!-- END FORM-->
                                            </div>
                                      </div>
                                   </div>
                              <!-- /block -->
                        </div>
                         <!-- /validation -->
                    
                    
                    
                </div>
        
            </div>
            
<?php include(APPPATH."views/admin/admin_footer.php"); ?>
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
                                          <b>Create Company</b>  
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
                                    <div class="muted pull-left">Create Company</div>
                                </div>
                                <div class="block-content collapse in">
                                    <div class="span12">

                                        <!-- BEGIN FORM-->
                                        <form method="post" action=""  enctype="multipart/form-data" id="" class="form-horizontal">

                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <input type="hidden" name="company_id" value="<?php if( isset($edit['COMPANY_ID']) && $edit['COMPANY_ID'] ){echo $this->webspice->encrypt_decrypt($edit['COMPANY_ID'], 'encrypt');} ?>" />
                                            <fieldset>
                                                
                                                <div class="control-group">
                                                    <label class="control-label">Company Name<span class="required">*</span></label>
                                                    <div class="controls">
                                                        <input type="text" name="company_name" data-required="1" class="span6 m-wrap" value="<?php echo set_value('company_name',$edit['COMPANY_NAME']); ?>" />
                                                    <span class="fred"><?php echo form_error('company_name'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Company Description<span class="required">*</span></label>
                                                    <div class="controls">
                                                        <textarea rows="5" name="company_description" data-required="1" class="span6 m-wrap" ><?php echo set_value('company_description',$edit['COMPANY_DESCRIPTION']); ?></textarea>
                                                    <span class="fred"><?php echo form_error('company_description'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Phone</label>
                                                    <div class="controls">
                                                        <input type="text" name="company_phone" data-required="1" class="span6 m-wrap" value="<?php echo set_value('company_phone',$edit['COMPANY_PHONE']); ?>" />
                                                    <span class="fred"><?php echo form_error('company_phone'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Email<span class="required">*</span></label>
                                                    <div class="controls">
                                                        <input type="text" name="company_email" data-required="1" class="span6 m-wrap" value="<?php echo set_value('company_email',$edit['COMPANY_EMAIL']); ?>" />
                                                    <span class="fred"><?php echo form_error('company_email'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Address<span class="required">*</span></label>
                                                    <div class="controls">
                                                        <textarea rows="5" name="company_address" data-required="1" class="span6 m-wrap" ><?php echo set_value('company_address',$edit['COMPANY_ADDRESS']); ?></textarea>
                                                    <span class="fred"><?php echo form_error('company_address'); ?></span>
                                                    </div>
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
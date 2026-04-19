<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container" id="wrapper">
            <div id="page_create_sub_head" class="row-fluid page_identifier">
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
                                          <b>Create Sub Head</b>
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
                                    <div class="muted pull-left">Create Sub Head</div>
                                </div>
                                <div class="block-content collapse in">
                                  <div class="span12">
                                    <!-- BEGIN FORM-->
                                    <form method="post" action=""  enctype="multipart/form-data" id="" class="form-horizontal">

                                        <input id="token" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <input type="hidden" name="sub_head_id" value="<?php if( isset($edit['SUB_HEAD_ID']) && $edit['SUB_HEAD_ID'] ){echo $this->webspice->encrypt_decrypt($edit['SUB_HEAD_ID'], 'encrypt');} ?>" />
                                        <fieldset>

                                          <div class="control-group">
                                            <label class="control-label">Group Head Name<span class="required">*</span></label>
                                            <div class="controls">
                                              <select class="span6 m-wrap" name="group_head_name">
                                                <option value="">Select...</option>
                                                  <option value="Asset" <?php echo (isset($edit['GROUP_HEAD_NAME']) && set_value('group_head_name',$edit['GROUP_HEAD_NAME'])  == "Asset") ? "selected" : ""; ?> >Asset</option>
                                                  <option value="Liability" <?php echo (isset($edit['GROUP_HEAD_NAME']) && set_value('group_head_name',$edit['GROUP_HEAD_NAME']) == "Liability") ? "selected" : ""; ?> >Liability</option>
                                                  <option value="Income" <?php echo (isset($edit['GROUP_HEAD_NAME']) && set_value('group_head_name',$edit['GROUP_HEAD_NAME']) == "Income") ? "selected" : ""; ?> >Income</option>
                                                  <option value="Expense" <?php echo (isset($edit['GROUP_HEAD_NAME']) && set_value('group_head_name',$edit['GROUP_HEAD_NAME']) == "Expense") ? "selected" : ""; ?> >Expense</option>
                                              </select>
                                              <span class="fred"><?php echo form_error('group_head_name'); ?></span>
                                            </div>
                                          </div>

                                          <div class="control-group">
                                              <label class="control-label">Sub Head Name<span class="required">*</span></label>
                                              <div class="controls">
                                                  <input type="text" name="sub_head_name" data-required="1" class="span6 m-wrap" value="<?php echo set_value('sub_head_name',$edit['SUB_HEAD_NAME']); ?>" />
                                                  <span class="fred"><?php echo form_error('sub_head_name'); ?></span>
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
<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container" id="wrapper">
            <div id="page_create_journal" class="row-fluid page_identifier">
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
                                          <b>Create Teacher Payment</b>
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
                                    <div class="muted pull-left">Create Teacher Payment</div>
                                </div>
                                <div class="block-content collapse in">
                                  <div class="span12">

                                  <?php if(isset($errors) && count($errors)) : ?>
                                    <div class="alert alert-error alert-block">
                                      <a class="close" data-dismiss="alert" href="#">&times;</a>
                                      <h4 class="alert-heading">Error!</h4>
                                      <?php
                                        foreach($errors as $error) {
                                          echo $error . "<br />";
                                        }
                                      ?>
                                    </div>
                                  <?php endif; ?>

                                    <!-- BEGIN FORM-->
                                    <form method="post" action=""  enctype="multipart/form-data" id="" class="form-horizontal">

                                        <input id="token" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <input type="hidden" name="acc_data_id" value="<?php if( isset($edit['ACC_DATA_ID']) && $edit['ACC_DATA_ID'] ){echo $this->webspice->encrypt_decrypt($edit['ACC_DATA_ID'], 'encrypt');} ?>" />
                                        <fieldset>

                                          <div class="control-group">
                                              <label class="control-label">Date<span class="required">*</span></label>
                                              <div class="controls">
                                                  <input type="text" name="acc_date" data-required="1" class="span6 m-wrap datepicker" value="<?php echo set_value('acc_date',$edit['ACC_DATE']); ?>" />
                                              <span class="fred"><?php echo form_error('acc_date'); ?></span>
                                              </div>
                                          </div>

                                          <div class="control-group">
                                            <label class="control-label">Teacher Name</label>
                                            <div class="controls">
                                              <select id="teacher_list" class="span6 m-wrap" name="teacher_id">
                                                <option value="">Select...</option>
                                                <?php
                                                  $options = $this->db->query("SELECT TEACHER_ID, TEACHER_NAME, SALARY FROM teacher")->result();
                                                 
                                                ?>
                                                <?php  foreach($options as $option) :?>
                                                  <option value="<?php echo $option->TEACHER_ID?>" <?php echo (isset($edit['TEACHER_ID']) && $edit['TEACHER_ID'] == $option->TEACHER_ID) ? "selected" : ""; ?> ><?php echo $option->TEACHER_NAME . " ( ".$option->SALARY . " )"; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                              <span class="fred"><?php echo form_error('teacher_id'); ?></span>
                                            </div>
                                          </div>

                                          <div class="prodcut_price">
                                              <div class="control-group">
                                                <!-- <div class="span12"> -->

                                                  <label class="control-label">Payment<span class="required">*</span></label>
                                                  <div class="col-md-12 my_real_div">
                                                    <div class="span3 my_span">
                                                        <div class="form-group" >
                                                          <label for="acc_amount[]">Amount<span class="required">*</span></label>
                                                          <input type="text" name="acc_amount[]" data-required="1" class="span12 m-wrap" value="<?php echo set_value('acc_amount',$edit['ACC_AMOUNT']); ?>" />
                                                          <span class="fred"><?php echo form_error('acc_amount[]'); ?></span>
                                                        </div>
                                                    </div>


                                                    <div class="span3">
                                                     
                                                      <div class="form-group">
                                                        <label>Month<span class="required">*</span></label>
                                                        
                                                        <select id="month" name="month[]" class="span8 m-wrap salary_month">
                                                          <option value="0">Select...</option>
                                                            <option value="january" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "january") ? "selected" : ""; ?> >January</option>
                                                            <option value="february" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "february") ? "selected" : ""; ?> >February</option>
                                                            <option value="march" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "march") ? "selected" : ""; ?> >March</option>
                                                            <option value="april" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "april") ? "selected" : ""; ?> >April</option>
                                                            <option value="may" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "may") ? "selected" : ""; ?> >May</option>
                                                            <option value="june" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "june") ? "selected" : ""; ?> >June</option>
                                                            <option value="july" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "july") ? "selected" : ""; ?> >July</option>
                                                            <option value="august" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "august") ? "selected" : ""; ?> >August</option>
                                                            <option value="september" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "september") ? "selected" : ""; ?> >September</option>
                                                            <option value="october" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "october") ? "selected" : ""; ?> >October</option>
                                                             <option value="november" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "november") ? "selected" : ""; ?> >November</option>
                                                             <option value="december" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "december") ? "selected" : ""; ?>>December</option>
                                                        </select>
                                                          <span class="fred"><?php echo form_error('month[]'); ?></span>
                                                        </div>
                                                     
                                                    </div>
                                                  </div>


                                                <!-- </div> -->

                                              </div>
                                          </div>
              
                                          <div class="prodcut_price_new" style="display:none">
                                              <div class="control-group">
                                                <!-- <div class="span12"> -->

                                                  <label class="control-label"><span class="required">*</span></label>
                                                  <div class="col-md-12 my_real_div">
                                                    <div class="span3 my_span">
                                                        <div class="form-group" >
                                                          <label for="payment_type">Amount<span class="required">*</span></label>
                                                          <input type="text" name="acc_amount[]" data-required="1" class="span12 m-wrap" value="<?php echo set_value('acc_amount',$edit['ACC_AMOUNT']); ?>" />
                                                          <span class="fred"><?php echo form_error('acc_amount[]'); ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="span3">
                                                     
                                                      <div class="form-group">
                                                        <label for="month">Month<span class="required">*</span></label>
                                                        
                                                        <select id="month" name="month[]" class="span8 m-wrap salary_month">
                                                          <option value="0">Select...</option>
                                                            <option value="january" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "january") ? "selected" : ""; ?> >January</option>
                                                            <option value="february" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "february") ? "selected" : ""; ?> >February</option>
                                                            <option value="march" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "march") ? "selected" : ""; ?> >March</option>
                                                            <option value="april" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "april") ? "selected" : ""; ?> >April</option>
                                                            <option value="may" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "may") ? "selected" : ""; ?> >May</option>
                                                            <option value="june" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "june") ? "selected" : ""; ?> >June</option>
                                                            <option value="july" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "july") ? "selected" : ""; ?> >July</option>
                                                            <option value="august" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "august") ? "selected" : ""; ?> >August</option>
                                                            <option value="september" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "september") ? "selected" : ""; ?> >September</option>
                                                            <option value="october" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "october") ? "selected" : ""; ?> >October</option>
                                                             <option value="november" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "november") ? "selected" : ""; ?> >November</option>
                                                             <option value="december" <?php echo (isset($edit['MONTH']) && set_value('month',$edit['MONTH'])  == "december") ? "selected" : ""; ?>>December</option>
                                                        </select>
                                                          <span class="fred"><?php echo form_error('month[]'); ?></span>
                                                        </div>
                                                     
                                                    </div>


                                                  </div>


                                                <!-- </div> -->

                                              </div>
                                          </div>

              
                                          <div id="pro_area"></div>

                                          <div class="controls" style="border:none; margin:0px padding:0px;">
                                            <button id="add_product" class="btn btn-success btn-sm">Add</button>
                                            <button id="remove_product" type="button" class="btn btn-danger btn-sm">Remove</button>
                                          </div>

                                          <div style="margin-bottom:30px"></div>

                                          <div class="control-group">
                                              <label class="control-label">Narration<span class="required">*</span></label>
                                              <div class="controls">
                                                  <textarea rows="10" cols="50" name="description" class="span6 m-wrap" ><?php echo set_value('description',$edit['DESCRIPTION']); ?></textarea>
                                                  <span class="fred"><?php echo form_error('description'); ?></span>
                                              </div>
                                          </div>

                                          <!-- <div class="control-group">
                                              <label class="control-label">Debit2<span class="required">*</span></label>
                                              <div class="controls">
                                                  <input type="text" id="search-box" name="debit2" data-required="1" class="span6 m-wrap" value="" />
                                              </div>
                                              <div class="controls">
                                                  <div class="span6 m-wrap" id="suggesstion-box"></div>
                                              </div>
                                          </div> -->
                                          
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
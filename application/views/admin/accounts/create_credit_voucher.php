<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container" id="wrapper">
            <div id="page_create_voucher" class="row-fluid page_identifier">
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
                                          <b>Create Credit Voucher</b>
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
                                    <div class="muted pull-left">Create Credit Voucher</div>
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
                                              <label class="control-label"># Voucher No<span class="required">*</span></label>
                                              <div class="controls">
                                                  <input type="text" autocomplete="off" name="voucher_no" data-required="1" class="span6 m-wrap" value="<?php echo set_value('voucher_no', $this->webspice->account_head_name($edit['VOUCHER_NO'])); ?>" />
                                                  <span class="fred"><?php echo form_error('voucher_no'); ?></span>
                                              </div>
                                          </div>

                                          <div class="control-group">
                                              <label class="control-label">Date<span class="required">*</span></label>
                                              <div class="controls">
                                                  <input type="text" name="acc_date" data-required="1" class="span6 m-wrap datepicker" value="<?php echo set_value('acc_date',$edit['ACC_DATE']); ?>" />
                                              <span class="fred"><?php echo form_error('acc_date'); ?></span>
                                              </div>
                                          </div>

                                          <div class="prodcut_price">
                                              <div class="control-group">
                                                <!-- <div class="span12"> -->

                                                  <label class="control-label">Particular<span class="required">*</span></label>
                                                  <div class="col-md-12 my_real_div">
                                                    <div class="span2 my_span">
                                                        <div class="form-group" >
                                                          <label for="exampleInputEmail1">Credit<span class="required">*</span></label>
                                                              <select class="span12 m-wrap" name="credit[]">
                                                                <option value="">Select...</option>
                                                                <?php
                                                                  $options = $this->db->query("SELECT * FROM account_name WHERE GROUP_HEAD_NAME <>'' AND GROUP_HEAD_NAME IN ('Income')")->result();
                                                                ?>
                                                                <?php foreach($options as $option) : ?>
                                                                  <option value="<?php echo $option->CODE?>" 

                                                                    <?php echo (set_value('credit', $edit['CREDIT']) == $option->CODE) ? "selected" : "" ?>
                                                                    
                                                                   ><?php echo $option->ACC_NAME; ?></option>
                                                                <?php endforeach; ?>
                                                              </select>
                                                              <span class="fred"><?php echo form_error('credit[]'); ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="span2">
                                                      <div class="form-group">
                                                        <label>Amount<span class="required">*</span></label>
                                                              <input type="text" name="amount[]" data-required="1" class="span12 m-wrap" value="<?php echo set_value('amount',$edit['ACC_AMOUNT']); ?>" />
                                                              <span class="fred"><?php echo form_error('amount[]'); ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="span2 my_span">
                                                        <div class="form-group" >
                                                          <label for="exampleInputEmail1">Narration<span class="required">*</span></label>
                                                          <textarea name="description[]" class="span12 m-wrap" ><?php echo set_value('description',$edit['DESCRIPTION']); ?></textarea>
                                                          <span class="fred"><?php echo form_error('description[]'); ?></span>
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
                                                    <div class="span2 my_span">
                                                      <div class="form-group" >
                                                        <label for="exampleInputEmail1">Credit<span class="required">*</span></label>
                                                            <select class="span12 m-wrap" name="credit[]">
                                                              <option value="">Select...</option>
                                                              <?php
                                                                $options = $this->db->query("SELECT * FROM account_name WHERE GROUP_HEAD_NAME <>'' AND GROUP_HEAD_NAME IN ('Income')")->result();
                                                              ?>
                                                              <?php foreach($options as $option) : ?>
                                                                <option value="<?php echo $option->CODE?>" 

                                                                  <?php echo (set_value('credit', $edit['CREDIT']) == $option->CODE) ? "selected" : "" ?>
                                                                  
                                                                 ><?php echo $option->ACC_NAME; ?></option>
                                                              <?php endforeach; ?>
                                                            </select>
                                                            <span class="fred"><?php echo form_error('credit[]'); ?></span>
                                                      </div>
                                                    </div>

                                                    <div class="span2">
                                                      <div class="form-group">
                                                        <label>Amount<span class="required">*</span></label>
                                                              <input type="text" name="amount[]" data-required="1" class="span12 m-wrap" value="<?php echo set_value('amount',$edit['ACC_AMOUNT']); ?>" />
                                                              <span class="fred"><?php echo form_error('amount[]'); ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="span2 my_span">
                                                        <div class="form-group" >
                                                          <label for="exampleInputEmail1">Narration<span class="required">*</span></label>
                                                          <textarea name="description[]" class="span12 m-wrap" ><?php echo set_value('description',$edit['DESCRIPTION']); ?></textarea>
                                                          <span class="fred"><?php echo form_error('description[]'); ?></span>
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

                                          <div class="prodcut_price">
                                              <div class="control-group">

                                                  <label class="control-label">Payment<span class="required">*</span></label>
                                                  <div class="col-md-12 my_real_div">
                                                    <div class="span2 my_span">
                                                        <div class="form-group" >
                                                          <label for="exampleInputEmail1">Debit<span class="required">*</span></label>
                                                              <select class="span12 m-wrap" name="debit">
                                                                <option value="">Select...</option>
                                                                <?php
                                                                  $options = $this->db->query("SELECT * FROM account_name WHERE GROUP_HEAD_NAME <>'' AND GROUP_HEAD_NAME IN ('Asset')")->result();
                                                                ?>
                                                                <?php foreach($options as $option) : ?>
                                                                  <option value="<?php echo $option->CODE?>" 

                                                                    <?php echo (set_value('debit', $edit['DEBIT']) == $option->CODE) ? "selected" : "" ?>
                                                                    
                                                                   ><?php echo $option->ACC_NAME; ?></option>
                                                                <?php endforeach; ?>
                                                              </select>
                                                              <span class="fred"><?php echo form_error('debit'); ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="span2">
                                                      <div class="form-group">
                                                        <label>Amount<span class="required">*</span></label>
                                                              <input type="text" name="debit_amount" data-required="1" class="span12 m-wrap" value="<?php echo set_value('debit_amount'); ?>" />
                                                              <span class="fred"><?php echo form_error('debit_amount'); ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="span2 my_span">
                                                        <div class="form-group" >
                                                          <label for="exampleInputEmail1">Narration<span class="required">*</span></label>
                                                          <textarea rows="1" name="debit_description" class="span12 m-wrap" ><?php echo set_value('debit_description'); ?></textarea>
                                                          <span class="fred"><?php echo form_error('debit_description'); ?></span>
                                                        </div>
                                                    </div>
                                                  </div>

                                              </div>
                                          </div>

                                          <div class="control-group">
                                              <label class="control-label"># Paid By<span class="required">*</span></label>
                                              <div class="controls">
                                                  <input type="text" name="paid_by" data-required="1" class="span6 m-wrap" value="<?php echo set_value('paid_by',$edit['PAID_BY']); ?>" />
                                                  <span class="fred"><?php echo form_error('paid_by'); ?></span>
                                              </div>
                                          </div>

                                          <div class="control-group">
                                              <label class="control-label"># Voucher Note<span class="required">*</span></label>
                                              <div class="controls">
                                                  <textarea rows="5" cols="50" name="voucher_note" class="span6 m-wrap" ><?php echo set_value('voucher_note',$edit['VOUCHER_NOTE']); ?></textarea>
                                                  <span class="fred"><?php echo form_error('voucher_note'); ?></span>
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
<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container" id="wrapper">
            <div id="page_ledger" class="row-fluid page_identifier">
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
                                          <b>Balance Sheet</b>
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
                                    <div class="muted pull-left">Balance Sheet</div>
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
                                          <fieldset>
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
                                              <tr>
                                                  <th>From Date</th>
                                                  <th>To Date</th>
                                                  <?php if($this->webspice->admin_verify()) : ?>
                                                    <th>Company Name</th>
                                                  <?php endif; ?>
                                                  <th>Action</th>
                                              </tr>
                                              <tr>
                                                  <td>
                                                    <input type="text" autocomplete="off" name="from_date" data-required="1" class="span12 m-wrap datepicker" value="<?php //echo set_value('acc_date',$edit['']); ?>" />
                                                    <span class="fred"><?php //echo form_error('acc_date'); ?></span>
                                                  </td>
                                                  <td>
                                                    <input type="text" autocomplete="off" name="to_date" data-required="1" class="span12 m-wrap datepicker" value="<?php //echo set_value('acc_date'); ?>" />
                                                    <span class="fred"><?php //echo form_error('acc_date'); ?></span>
                                                  </td>
                                                  <td>
                                                  <?php if($this->webspice->admin_verify()) : ?>
                                                    <select class="span12 m-wrap" name="company_id">
                                                      <option value="">Select...</option>
                                                      <?php
                                                        $options = $this->db->query("SELECT * FROM company WHERE STATUS = 7")->result();
                                                      ?>
                                                      <?php foreach($options as $option) : ?>
                                                        <option value="<?php echo $option->COMPANY_ID; ?>" <?php //echo (isset($edit['OPTION_ID']) && $edit['OPTION_ID'] == $option->OPTION_ID) ? "selected" : ""; ?> ><?php echo $option->COMPANY_NAME ?></option>
                                                      <?php endforeach; ?>
                                                    </select>
                                                  <?php endif; ?>
                                                  </td>
                                                  <td>
                                                      <input type="submit" name="filter" class="btn btn-primary" value="Submit Data"  />

                                                      <div class="btn-group">
                                                         <a href="<?php echo $url_prefix . 'ledger' ?>"><button class="btn btn-success">Refresh</button></a>
                                                      </div>
                                                  </td>
                                              </tr>
                                            </table>

                                              <div style="margin-top: 100px;"></div>

                                            <?php if(isset($get_record)) : ?>

                                              <table>
                                                  <tr>
                                                    <td><p>Date From :</p></td>
                                                    <td><p><?php echo $date_from; ?></p></td>
                                                  </tr>
                                                  <tr>
                                                      <td><p>Date To : </p></td>
                                                    <td><p><?php echo $date_to; ?></p></td>
                                                  </tr>
                                                  <?php if(isset($company_id)) : ?>
                                                    <tr>
                                                      <td><p>Company : </p></td>
                                                      <td><p><?php echo $this->webspice->company_name($company_id); ?></p></td>
                                                    </tr>
                                                  <?php endif; ?>
                                              </table>

                                              <div style="margin-top: 20px;"></div>

                                              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                                <tr>
                                                  <th>Balance Sheet</th>
                                                  <th>Liability</th>
                                                  <th>Asset</th>
                                                </tr>
                                                <?php
                                                $asset_val = 0;
                                                $liability_val = 0;
                                                ?>
                                                
                                                <?php foreach($get_record as $v) : ?>
                                                  <tr>
                                                    <td><?php echo $this->webspice->account_head_name($v); ?></td>
                                                    <td>
                                                      <?php
                                                        if($this->webspice->acc_group_head($v) == "Liability") {
                                                          echo $this->webspice->account_b($v);
                                                          $asset_val += $this->webspice->account_b($v);
                                                        }
                                                      ?>
                                                    </td>
                                                    <td>
                                                      <?php
                                                        if($this->webspice->acc_group_head($v) == "Asset") {
                                                           echo $this->webspice->account_b($v);
                                                           $liability_val += $this->webspice->account_b($v);
                                                        }
                                                      ?>
                                                    </td>
                                                  </tr>
                                                <?php endforeach; ?>
                                                
                                                  <tr>
                                                    <td><b>Total:</b></td>
                                                    <td><b><?php echo $liability_val; ?>/-</b></td>
                                                    <td><b><?php echo $asset_val; ?>/-</b></td>
                                                  </tr>

                                              </table>
                                            <?php else: ?>
                                            <!-- data nai vai -->
                                            <?php endif; ?>
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
<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container" id="wrapper">
            <div id="page_journal" class="row-fluid page_identifier">
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
                                          <b>Journal</b>
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
                                    <div class="muted pull-left">Journal</div>
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
                                                 <!--  <th>Account Name (Ledger)</th> -->
                                                  <th>From Date</th>
                                                  <th>To Date</th>
                                                  <?php if($this->webspice->admin_verify()) : ?>
                                                    <th>Company Name</th>
                                                  <?php endif; ?>
                                                  <th>Action</th>
                                              </tr>
                                              <tr>
                                                  <td>
                                                    <input type="text" name="from_date" data-required="1" class="span12 m-wrap datepicker" value="<?php //echo set_value('acc_date',$edit['']); ?>" />
                                                    <span class="fred"><?php //echo form_error('acc_date'); ?></span>
                                                  </td>
                                                  <td>
                                                    <input type="text" name="to_date" data-required="1" class="span12 m-wrap datepicker" value="<?php //echo set_value('acc_date'); ?>" />
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
                                                    <td><p>From Date :</p></td>
                                                    <td><p><?php echo $from_date; ?></p></td>
                                                  </tr>
                                                  <tr>
                                                    <td><p>To Date : </p></td>
                                                    <td><p><?php echo $to_date; ?></p></td>
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
                                                <!-- table heading -->
                                                <tr>
                                                  <th>Date</th>
                                                  <th>Account Name</th>
                                                  <th>Debit</th>
                                                  <th>Credit</th>
                                                </tr>
                                                <!-- end table heading -->
                                                
                                                <?php
                                                  $debit_val = array();
                                                  $credit_val = array();
                                                foreach($get_record as $val) {
                                                  $entries = $this->db->query("SELECT * FROM account_transaction WHERE ACC_DATE='".$val->ACC_DATE."'")->result();
                                                  $count = count($entries);


                                                  ?>
                                                  <?php if($count > 1) { ?>
                                                  <?php
                                                  $i=1;
                                                  foreach($entries as $entry) :
                                                    // dd($debit_val, true);
                                                    // dd($credit_val, true);
                                                    ?>
                                                    <tr>
                                                      <?php if($i==1) : ?>
                                                      <td rowspan="<?php echo ($i==1) ? $count : ""; ?>"><?php echo date("D jS F Y", strtotime($entry->ACC_DATE)); ?></td>
                                                      <?php endif; ?>
                                                      <td>
                                                        <?php
                                                          if($entry->DEBIT) {
                                                            echo $this->webspice->account_head_name($entry->DEBIT);
                                                          } else if($entry->CREDIT) {
                                                            echo $this->webspice->account_head_name($entry->CREDIT);
                                                          }
                                                        ?>
                                                      </td>
                                                      <td>
                                                        <?php
                                                          if($entry->DEBIT) {
                                                            $debit_val[] = $entry->ACC_AMOUNT;
                                                            echo $entry->ACC_AMOUNT;
                                                          }
                                                        ?>
                                                      </td>
                                                      <td>
                                                        <?php
                                                          if($entry->CREDIT) {
                                                            $credit_val[] = $entry->ACC_AMOUNT;
                                                            echo $entry->ACC_AMOUNT;
                                                          }
                                                        ?>
                                                      </td>
                                                    </tr>
                                                  <?php $i++; endforeach; ?>
                                                  <?php } else { ?>
                                                  <?php foreach($entries as $entry) : ?>
                                                    <tr>
                                                      <td><?php echo $entry->ACC_DATE; ?></td>
                                                      <td>Hallo</td>
                                                      <td>jbj</td>
                                                      <td>New</td>
                                                    </tr>
                                                  <?php endforeach; ?>
                                                  <?php } ?>
                                                <?php } ?>
                                                    <tr>
                                                      <td></td>
                                                      <td></td>
                                                      <td><?php echo array_sum($debit_val); ?></td>
                                                      <td><?php echo array_sum($credit_val); ?></td>
                                                    </tr>

                                                <!-- <tr>
                                                  <td rowspan="2">This is</td>
                                                  <td>How</td>
                                                  <td>Work</td>
                                                  <td>Togather</td>
                                                </tr>
                                                <tr>
                                                  <td>I</td>
                                                  <td>am</td>
                                                  <td>Motivated</td>
                                                </tr>

                                                <tr>
                                                  <td rowspan="1">This</td>
                                                  <td>Is</td>
                                                  <td>not</td>
                                                  <td>my fault</td>
                                                </tr> -->

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
<?php include(APPPATH."views/admin/admin_header.php"); ?>

        <div class="container" id="wrapper">
            <div id="page_payment_report" class="row-fluid page_identifier">
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
                                          <b>Teacher Statement</b>
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
                                    <div class="muted pull-left">Teacher Statement</div>
                                </div>
                                <div class="block-content collapse in">
                                    <div class="span12">
                                                <!-- BEGIN FORM-->
                                                <form method="post" action=""  enctype="multipart/form-data" id="" class="form-horizontal">

                                                    <input id="token" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                    <fieldset>
                                                           <table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
                                                              <tr>
                                                                  <th>Teacher Name</th>
                                                                  <th>Year</th>
                                                                  <th>Action</th>
                                                              </tr>
                                                              <tr>
                                                                  <td>
                                                                    <select id="class_list" class="span12 m-wrap" name="teacher_id">
                                                                      <option value="">Select...</option>
                                                                      <?php
                                                                        $options = $this->db->query("SELECT TEACHER_ID, TEACHER_NAME FROM teacher WHERE STATUS = 7")->result();
                                                                      ?>
                                                                      <?php foreach($options as $option) : ?>
                                                                        <option value="<?php echo $option->TEACHER_ID?>" <?php echo (isset($edit['TEACHER_ID']) && $edit['TEACHER_ID'] == $option->TEACHER_ID) ? "selected" : ""; ?> ><?php echo $option->TEACHER_NAME; ?></option>
                                                                      <?php endforeach; ?>
                                                                    </select>
                                                                  </td>
                                                                  <td>
                                                                    <select name="year" class="span12 m-wrap">
                                                                      <?php
                                                                          $year = date("Y");
                                                                       for($i=2015;$i<=2020;$i++):?>
                                                                          <option value="<?php echo $i;?>"
                                                                              <?php if(isset($year) && $year==$i)echo 'selected="selected"';?>>
                                                                                  <?php echo $i;?>
                                                                          </option>
                                                                        <?php endfor;?>
                                                                    </select>
                                                                  </td>
                                                                  <td>
                                                                      <input type="submit" name="filter" class="btn btn-primary" value="Submit Data"  />

                                                                      <div class="btn-group">
                                                                         <a href="<?php echo $url_prefix . 'payment_report' ?>"><button class="btn btn-success">Refresh</button></a>
                                                                      </div>
                                                                  </td>
                                                              </tr>
                                                            </table>

                                                                <div style="margin-top: 100px;"></div>

                                                              <?php if(isset($get_record)) : ?>

                                                                <table>
                                                                    <tr>
                                                                      <td><p>Teacher Name :</p></td>
                                                                      <td><p><?php echo $teacher_name; ?></p></td>
                                                                    </tr>
                                                                </table>

                                                                <div style="margin-top: 20px;"></div>

                                                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                                                  <tr>
                                                                    <th>Date</th>
                                                                    <th>Month</th>
                                                                    <th>Account Name</th>
                                                                    <th>Debit</th>
                                                                    <th>Credit</th>
                                                                    <th>Balance</th>
                                                                    <th>Description</th>
                                                                  </tr>
                                                                  <?php
                                                                  // $debit_val = array();
                                                                  // $credit_val = array();
                                                                  $debit_val = 0;
                                                                  $credit_val = 0;
                                                                  foreach($get_record as $v) :
                                                                    ?>
                                                                    <tr>
                                                                      <td><?php echo $v->ACC_DATE; ?></td>
                                                                      <td><?php echo $v->MONTH; ?></td>
                                                                      <td>
                                                                        <?php
                                                                          if($v->DEBIT) {
                                                                            echo $this->webspice->account_head_name($v->DEBIT);
                                                                          }
                                                                          else if($v->CREDIT) {
                                                                            echo $this->webspice->account_head_name($v->CREDIT);
                                                                          }
                                                                        ?>
                                                                      </td>
                                                                      <td>
                                                                        <?php
                                                                          if($v->DEBIT) {
                                                                            echo $v->ACC_AMOUNT;
                                                                            // $debit_val[] = $v->ACC_AMOUNT;
                                                                            $debit_val += $v->ACC_AMOUNT;
                                                                            // dd($debit_val, true);
                                                                          }
                                                                        ?>
                                                                      </td>
                                                                      <td>
                                                                        <?php
                                                                          if($v->CREDIT) {
                                                                            echo $v->ACC_AMOUNT;
                                                                            // $credit_val[] = $v->ACC_AMOUNT;
                                                                            $credit_val += $v->ACC_AMOUNT;
                                                                            // dd($credit_val, true);
                                                                          }
                                                                        ?>
                                                                      </td>
                                                                      <td>
                                                                        <?php
                                                                          // $debit_val = array_sum($debit_val);
                                                                          // $credit_val = array_sum($credit_val);

                                                                          echo $credit_val - $debit_val;
                                                                        ?>
                                                                      </td>
                                                                      <td><?php echo $v->DESCRIPTION; ?></td>
                                                                    </tr>
                                                                  <?php endforeach; ?>

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
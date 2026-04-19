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
                                          <b>Student Statement</b>
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
                                    <div class="muted pull-left">Student Statement</div>
                                </div>
                                <div class="block-content collapse in">
                                    <div class="span12">
                                                <!-- BEGIN FORM-->
                                                <form method="post" action=""  enctype="multipart/form-data" id="" class="form-horizontal">

                                                    <input id="token" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                    <fieldset>
                                                           <table cellpadding="0" cellspacing="0" border="0" class="table table-striped">
                                                              <tr>
                                                                  <th>Class Name</th>
                                                                  <th>Section Name</th>
                                                                  <th>Student Name</th>
                                                                  <th>Year</th>
                                                                  <th>Action</th>
                                                              </tr>
                                                              <tr>
                                                                  <td>
                                                                    <select id="class_list" class="span12 m-wrap" name="class_id">
                                                                      <option value="">Select...</option>
                                                                      <?php
                                                                        $options = $this->db->query("SELECT CLASS_ID, CLASS_NAME FROM class WHERE STATUS = 7")->result();
                                                                      ?>
                                                                      <?php foreach($options as $option) : ?>
                                                                        <option value="<?php echo $option->CLASS_ID?>" <?php echo (isset($edit['CLASS_ID']) && $edit['CLASS_ID'] == $option->CLASS_ID) ? "selected" : ""; ?> ><?php echo $option->CLASS_NAME ?></option>
                                                                      <?php endforeach; ?>
                                                                    </select>
                                                                  </td>
                                                                  <td>
                                                                      <select id="section_list" class="span12 m-wrap" name="section_id">
                                                                        <option value="">Select...</option>
                                                                        <?php
                                                                        if(isset($edit['SECTION_ID'])) :
                                                                          $options = $this->db->query("SELECT s.SECTION_ID, s.SECTION_NAME, c.CLASS_NAME FROM section AS s INNER JOIN class AS c ON s.CLASS_ID = c.CLASS_ID WHERE s.STATUS = 7")->result();

                                                                        ?>
                                                                        <?php foreach($options as $option) : ?>
                                                                          <option value="<?php echo $option->SECTION_ID?>" <?php echo (isset($edit['SECTION_ID']) && $edit['SECTION_ID'] == $option->SECTION_ID) ? "selected" : ""; ?> ><?php echo $option->CLASS_NAME . ": " . $option->SECTION_NAME; ?></option>
                                                                        <?php endforeach; endif; ?>
                                                                      </select>
                                                                  </td>
                                                                  <td>
                                                                    <select id="student_list" class="span12 m-wrap" name="student_id">
                                                                      <option value="">Select...</option>
                                                                      <?php
                                                                      if(isset($edit['STUDENT_ID'])) :
                                                                        $options = $this->db->query("SELECT sd.STUDENT_DATA_ID, sd.STUDENT_ID, si.NAME p.STUDENT_ID FROM payment AS p INNER JOIN student_data AS sd ON sd.STUDENT_ID=p.STUDENT_ID INNER JOIN student_info AS si.STUDENT_ID ON si.STUDENT_ID=sd.STUDENT_ID WHERE STATUS = 7")->result();
                                                                      dd($options);
                                                                        
                                                                      ?>
                                                                      <?php  foreach($options as $option) :?>
                                                                        <option value="<?php echo $option->STUDENT_DATA_ID?>" <?php echo (isset($edit['STUDENT_ID']) && $edit['STUDENT_ID'] == $option->STUDENT_ID) ? "selected" : ""; ?> ><?php echo $option->NAME ?></option>
                                                                      <?php endforeach; endif; ?>
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
                                                                      <td><p>Name :</p></td>
                                                                      <td><p><?php echo $student_name; ?></p></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><p>Class Name : </p></td>
                                                                      <td><p><?php echo $class_name; ?></p></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><p>Section Name :</p></td>
                                                                      <td><p><?php echo $section_name; ?></p></td>
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
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
                                          <b>Manage Credit Voucher</b>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
<!-- table start -->
                    <div class="row-fluid">
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Manage Credit Voucher</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">

                                      <div class="btn-group">
                                         <a class="btn btn-danger" href="javascript:history.back();">Back</a>
                                      </div>

                                   </div>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <?php if($this->webspice->admin_verify()) : ?>
                                                  <th>Company Name</th>
                                                <?php endif; ?>
                                                <th>Voucher No</th>
                                                <th>Paid By</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($get_record as $v) :
                                            ?>
                                              <tr class="odd gradeX">
                                                  <td><?php echo date("D jS F y", strtotime($v->ACC_DATE)); ?></td>
                                                  <?php if($this->webspice->admin_verify()) : ?>
                                                    <td><?php echo $this->webspice->company_name($v->COMPANY_ID); ?></td>
                                                  <?php endif; ?>
                                                  <td><?php echo $v->VOUCHER_NO; ?></td>
                                                  <td><?php echo $v->PAID_BY; ?></td>
                                                  <!-- <td><?php //echo $this->webspice->account_head_name($v->CREDIT); ?></td> -->
                                                  <td><?php echo $v->ACC_AMOUNT; ?></td>
                                                  <td>
                                                    <!-- <?php //if( $this->webspice->permission_verify('manage_voucher',true) && $v->STATUS!=9 ): ?>
                                                        <a href="<?php //echo $url_prefix; ?>manage_voucher/edit/<?php //echo $this->webspice->encrypt_decrypt($v->ACC_DATA_ID,'encrypt'); ?>" class="btn btn-success">Edit</a>
                                                    <?php //endif; ?> -->

                                                    <!-- <?php //if( $this->webspice->permission_verify('manage_voucher',true)): ?>
                                                        <a href="<?php //echo $url_prefix; ?>manage_voucher/delete/<?php //echo $this->webspice->encrypt_decrypt($v->ACC_DATA_ID,'encrypt'); ?>" class="btn btn-danger">Delete</a>
                                                    <?php //endif; ?> -->

                                                    <?php if( $this->webspice->permission_verify('manage_voucher',true)): ?>
                                                        <a href="<?php echo $url_prefix; ?>manage_credit_voucher/credit_voucher/<?php echo $this->webspice->encrypt_decrypt($v->VOUCHER_NO,'encrypt'); ?>" class="btn btn-info">Details</a>
                                                    <?php endif; ?>

                                                  </td>
                                              </tr>
                                          <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /block -->
                    </div>
<!-- table end -->
                    
                    
                    
                </div>
        
            </div>
            
<?php include(APPPATH."views/admin/admin_footer.php"); ?>
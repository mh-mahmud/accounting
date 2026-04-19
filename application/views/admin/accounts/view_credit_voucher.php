<?php include(APPPATH."views/admin/admin_header.php"); ?>
<style>
  /*.table th, .table td {
    text-align: center !important;
  }*/
</style>
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
                                          <b>Credit Voucher Detials</b> 
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
                                <div class="muted pull-left">Credit Voucher Details</div>
                            </div>
                            <div class="block-content collapse in" style="overflow: hidden !important">
                                <div class="span12">
                                   <div class="table-toolbar">

                                      <div class="btn-group">
                                         <a class="btn btn-danger" href="javascript:history.back();">Back</a>
                                      </div>

                                   </div>
                                    <!-- <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="text-align: center !important;vertical-align: middle !important;">
                                        <thead>
                                            <tr>
                                                <td rowspan="2">No</td>
                                                <td rowspan="2">Particular</td>
                                                <td colspan="3">A/C Head</td>
                                                <td rowspan="2" colspan="3">Amount</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">Debit</td>
                                              <td colspan="2">Credit</td>
                                              <td colspan="2">bubu</td>
                                              <td colspan="2">knk</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table> -->

                                    <div>
                                      <p>Voucher No: <?php echo $get_record[0]->VOUCHER_NO ?></p>
                                      <p>Date: <?php echo $get_record[0]->ACC_DATE ?></p>
                                      <p>Paid BY: <?php echo $get_record[0]->PAID_BY ?></p>
                                    </div>

                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                      <thead>
                                        <th>No</th>
                                        <th>PARTICULAR</th>
                                        <th>Amount</th>
                                      </thead>

                                      <tbody>
                                        <?php $i=1; foreach($get_record as $val) : ?>
                                          <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo ($val->DESCRIPTION) ? $val->DESCRIPTION : ""; ?></td>
                                            <td><?php echo $val->ACC_AMOUNT; ?></td>
                                          </tr>
                                        <?php $i++; endforeach; ?>
                                          <tr>
                                            <td colspan="2"><b>Total: </b></td>
                                            <td colspan="3"><b><?php echo $total_amt; ?>/-</b></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3">Note: <?php echo $voucher_note; ?></td>
                                          </tr>
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
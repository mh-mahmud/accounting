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
                                          <b>Accounts</b>
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
                                <div class="muted pull-left">Accounts</div>
                            </div>

                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                    




                                    <div class="papana_2">
                                        <ul>
                                            <?php if($this->webspice->permission_verify('create_account_head', true)) : ?>
                                              <li class="papana_logo_2">
                                                  <a title="Create Account Head" href="<?php echo $url_prefix; ?>create_account_head"><img src="<?php echo $url_prefix; ?>global/accounts/create_accounts_head.png"></a>
                                                  <span class="bottom_text">Create Account Head</span>
                                              </li>
                                            <?php endif; ?>

                                            <?php if($this->webspice->permission_verify('manage_account_head', true)) : ?>
                                              <li class="papana_logo_2">
                                                  <a title="Manage Account Head" href="<?php echo $url_prefix; ?>manage_account_head"><img src="<?php echo $url_prefix; ?>global/accounts/manage_accounts_head.png"></a>
                                                  <span class="bottom_text">Manage Account Head</span>
                                              </li>
                                            <?php endif; ?>

                                            <?php if($this->webspice->permission_verify('create_voucher', true)) : ?>
                                              <li class="papana_logo_2">
                                                  <a title="Create Debit Voucher" href="<?php echo $url_prefix; ?>create_voucher"><img src="<?php echo $url_prefix; ?>global/accounts/create_voucher.png"></a>
                                                  <span class="bottom_text">Create Debit Voucher</span>
                                              </li>
                                            <?php endif; ?>

                                            <?php if($this->webspice->permission_verify('manage_voucher', true)) : ?>
                                              <li class="papana_logo_2">
                                                  <a title="Manage Debit Voucher" href="<?php echo $url_prefix; ?>manage_voucher"><img src="<?php echo $url_prefix; ?>global/accounts/manage_voucher.png"></a>
                                                  <span class="bottom_text">Manage Debit Voucher</span>
                                              </li>
                                            <?php endif; ?>

                                            <?php if($this->webspice->permission_verify('create_credit_voucher', true)) : ?>
                                              <li class="papana_logo_2">
                                                  <a title="Create Credit Voucher" href="<?php echo $url_prefix; ?>create_credit_voucher"><img src="<?php echo $url_prefix; ?>global/accounts/create_credit_voucher.png"></a>
                                                  <span class="bottom_text">Create Credit Voucher</span>
                                              </li>
                                            <?php endif; ?>

                                            <?php if($this->webspice->permission_verify('manage_credit_voucher', true)) : ?>
                                              <li class="papana_logo_2">
                                                  <a title="Manage Credit Voucher" href="<?php echo $url_prefix; ?>manage_credit_voucher"><img src="<?php echo $url_prefix; ?>global/accounts/manage_credit_voucher.png"></a>
                                                  <span class="bottom_text">Manage Credit Voucher</span>
                                              </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <div class="papana_2"  style="min-height:200px">
                                      <ul>
                                        <?php if($this->webspice->permission_verify('create_journal', true)) : ?>
                                          <li class="papana_logo_2">
                                            <a title="Balance Sheet" href="<?php echo $url_prefix; ?>create_journal"><img src="<?php echo $url_prefix; ?>global/accounts/create_journal.png"></a>
                                            <span class="bottom_text">Create Journal</span>
                                          </li>
                                        <?php endif; ?>

                                        <?php if($this->webspice->permission_verify('manage_journal', true)) : ?>
                                          <li class="papana_logo_2">
                                            <a title="Balance Sheet" href="<?php echo $url_prefix; ?>manage_journal"><img src="<?php echo $url_prefix; ?>global/accounts/manage_journal.png"></a>
                                            <span class="bottom_text">Manage Journal</span>
                                          </li>
                                        <?php endif; ?>

                                        <?php if($this->webspice->permission_verify('journal', true)) : ?>
                                          <li class="papana_logo_2">
                                            <a title="Journal" href="<?php echo $url_prefix; ?>journal"><img src="<?php echo $url_prefix; ?>global/accounts/journal.png"></a>
                                            <span class="bottom_text">Journal Report</span>
                                          </li>
                                        <?php endif; ?>

                                        <?php if($this->webspice->permission_verify('ledger', true)) : ?>
                                          <li class="papana_logo_2">
                                            <a title="Ledger" href="<?php echo $url_prefix; ?>ledger"><img src="<?php echo $url_prefix; ?>global/accounts/ledger.png"></a>
                                            <span class="bottom_text">Ledger Report</span>
                                          </li>
                                        <?php endif; ?>

                                        <?php if($this->webspice->permission_verify('trial_balance', true)) : ?>
                                          <li class="papana_logo_2">
                                            <a title="Trial Balance" href="<?php echo $url_prefix; ?>trial_balance"><img src="<?php echo $url_prefix; ?>global/accounts/trial_balance.png"></a>
                                            <span class="bottom_text">Trial Balance</span>
                                          </li>
                                        <?php endif; ?>

                                        <?php if($this->webspice->permission_verify('balance_sheet', true)) : ?>
                                          <li class="papana_logo_2">
                                            <a title="Balance Sheet" href="<?php echo $url_prefix; ?>balance_sheet"><img src="<?php echo $url_prefix; ?>global/accounts/balance_sheet.png"></a>
                                            <span class="bottom_text">Balance Sheet</span>
                                          </li>
                                        <?php endif; ?>
                                      </ul>
                                    </div>

                                   </div>



                                </div>
                            </div>
                        </div>
                        <!-- /block -->
                    </div>
<!-- table end -->
                    
                    
                    
                </div>
        
            </div>
            
<?php include(APPPATH."views/admin/admin_footer.php"); ?>
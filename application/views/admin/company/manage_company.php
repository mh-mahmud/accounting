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
                                          <b>Manage Company</b>
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
                                <div class="muted pull-left">Manage Company</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">

                                      <div class="btn-group">
                                         <a href="<?php echo $url_prefix . 'create_company' ?>"><button class="btn btn-success">Add New <i class="icon-plus icon-white"></i></button></a>
                                      </div>

                                   </div>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Company Description</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($get_record as $v) :
                                            ?>
                                              <tr class="odd gradeX">
                                                  <td><?php echo $v->COMPANY_NAME; ?></td>
                                                  <td><?php echo $v->COMPANY_DESCRIPTION; ?></td>
                                                  <td><?php echo $v->COMPANY_PHONE; ?></td>
                                                  <td><?php echo $v->COMPANY_EMAIL; ?></td>
                                                  <td><?php echo $v->COMPANY_ADDRESS; ?></td>
                                                  <td>
                                                    <?php if( $this->webspice->permission_verify('manage_company',true) && $v->STATUS!=9 ): ?>
                                                        <a href="<?php echo $url_prefix; ?>manage_company/edit/<?php echo $this->webspice->encrypt_decrypt($v->COMPANY_ID,'encrypt'); ?>" class="btn btn-success">Edit</a>
                                                    <?php endif; ?>

                                                    <?php if( $this->webspice->permission_verify('manage_company',true)): ?>
                                                        <a href="<?php echo $url_prefix; ?>manage_company/delete/<?php echo $this->webspice->encrypt_decrypt($v->COMPANY_ID,'encrypt'); ?>" class="btn btn-danger">Delete</a>
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
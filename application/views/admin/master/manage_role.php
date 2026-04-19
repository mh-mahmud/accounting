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
                                          <b>Manage Role</b>  
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
                                <div class="muted pull-left">Manage Role</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="<?php echo $url_prefix; ?>create_role"><button class="btn btn-success">Add New <i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                      <!-- <div class="btn-group pull-right">
                                         <button data-toggle="dropdown" class="btn dropdown-toggle">Tools <span class="caret"></span></button>
                                         <ul class="dropdown-menu">
                                            <li><a href="#">Print</a></li>
                                            <li><a href="#">Save as PDF</a></li>
                                            <li><a href="#">Export to Excel</a></li>
                                         </ul>
                                      </div> -->
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
                                              <th>Role Name</th>
                                              <th>Permission Name</th>
                                              <th>Created By</th>
                                              <th>Created Date</th>
                                              <th>Updated By</th>
                                              <th>Updated Date</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php foreach($get_record as $k=>$v): ?>
                                          <tr>
                                            
                                            <td><?php echo $v->ROLE_NAME; ?></td>
                                            <td><?php echo ucwords(str_replace(',',', ', str_replace('_',' ',$v->PERMISSION_NAME))); ?></td>
                                            <td><?php echo $this->customcache->user_maker($v->CREATED_BY,'USER_NAME'); ?></td>
                                            <td><?php echo $this->webspice->formatted_date($v->CREATED_DATE); ?></td>
                                            <td><?php echo $this->customcache->user_maker($v->UPDATED_BY,'USER_NAME'); ?></td>
                                            <td><?php echo $this->webspice->formatted_date($v->UPDATED_DATE); ?></td>
                                            <td><?php echo $this->webspice->static_status($v->STATUS); ?></td>
                                            <td class="field_button">
                                              <?php if( $this->webspice->permission_verify('manage_role',true) && $v->STATUS!=9 ): ?>
                                              <a href="<?php echo $url_prefix; ?>manage_role/edit/<?php echo $this->webspice->encrypt_decrypt($v->ROLE_ID,'encrypt'); ?>" class="btn btn-success">Edit</a>
                                              <?php endif; ?>
                                              
                                              <?php if( $this->webspice->permission_verify('manage_role',true) && $v->STATUS==7 ): ?>
                                              <a href="<?php echo $url_prefix; ?>manage_role/inactive/<?php echo $this->webspice->encrypt_decrypt($v->ROLE_ID,'encrypt'); ?>" class="btn btn-danger">Inactive</a>
                                              <?php endif; ?>
                                              
                                              <?php if( $this->webspice->permission_verify('manage_role',true) && $v->STATUS==-7 ): ?>
                                              <a href="<?php echo $url_prefix; ?>manage_role/active/<?php echo $this->webspice->encrypt_decrypt($v->ROLE_ID,'encrypt'); ?>" class="btn btn-warning">Active</a>
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